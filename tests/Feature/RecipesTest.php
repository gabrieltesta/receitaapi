<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Database\Factories\RecipeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RecipesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actAsUser(['access-recipe']);

    }

    public function test_unauthorized_user_blocked_from_create_recipe()
    {
        $this->actAsUser();

        $recipe = [
            'title' => 'Recipe Test',
            'description' => 'Recipe Description Here',
            'user_id' => $this->user->id,
        ];

        $response = $this->postJson('/api/recipe', $recipe);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_recipe_empty_validation()
    {
        $recipe = [
            'title' => '',
            'description' => ''
        ];

        $response = $this->postJson('/api/recipe', $recipe);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_create_recipe_length_validation()
    {
        $recipe = [
            'title' => Str::random(256),
            'description' => Str::random(256)
        ];

        $response = $this->postJson('/api/recipe', $recipe);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_create_recipe_successful()
    {
        $recipe = [
            'title' => 'Recipe Test',
            'description' => 'Recipe Description Here',
        ];

        $response = $this->postJson('/api/recipe', $recipe);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('recipes', $recipe);

        $lastRecipe = Recipe::latest()->first();
        $this->assertEquals($recipe['title'], $lastRecipe->title);
        $this->assertEquals($recipe['description'], $lastRecipe->description);
        $this->assertEquals($this->user->id, $lastRecipe->user_id);
    }

    public function test_unauthorized_user_blocked_from_list_recipes()
    {
        $this->actAsUser();

        $response = $this->getJson('/api/recipe');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_list_recipes_successful()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson('/api/recipe');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertJson($recipe);
    }

    public function test_unauthorized_user_blocked_from_show_recipe()
    {
        $this->actAsUser();

        $recipe = Recipe::factory()->create();


        $response = $this->getJson("/api/recipe/{$recipe->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_show_recipe_successful()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipe/{$recipe->id}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertJson($recipe);
    }

    public function test_show_recipe_not_found()
    {
        $response = $this->getJson("/api/recipe/500");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_show_recipe_parameter_not_integer()
    {
        $response = $this->getJson("/api/recipe/test");

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_unauthorized_user_blocked_from_deleting_recipes()
    {
        $this->actAsUser();

        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/api/recipe/{$recipe->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_blocked_from_deleting_other_user_recipes()
    {
        $newUser = User::factory()->create();
        $this->actAsUser(['access-recipe'], $newUser);

        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/api/recipe/{$recipe->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_deleting_recipes_not_found() {
        $response = $this->deleteJson("/api/recipe/500");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_unauthorized_user_blocked_from_updating_recipes()
    {
        $this->actAsUser();

        $recipe = Recipe::factory()->create();
        $updatedData = [
            'title' => Str::random(50),
            'description' => Str::random(255),
        ];

        $response = $this->putJson("/api/recipe/{$recipe->id}", $updatedData);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_blocked_from_updating_other_user_recipes()
    {
        $newUser = User::factory()->create();
        $this->actAsUser(['access-recipe'], $newUser);

        $recipe = Recipe::factory()->create();

        $updatedData = [
            'title' => fake()->text(rand(10,20)),
            'description' => fake()->text(),
        ];

        $response = $this->putJson("/api/recipe/{$recipe->id}", $updatedData);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_updating_recipes_validation() {
        $recipe = Recipe::factory()->create();

        $updatedData = [
            'title' => '',
            'description' => '',
        ];

        $response = $this->putJson("/api/recipe/{$recipe->id}", $updatedData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_updating_recipes_validation_length() {
        $recipe = Recipe::factory()->create();

        $updatedData = [
            'title' => Str::random(256),
            'description' => Str::random(256),
        ];

        $response = $this->putJson("/api/recipe/{$recipe->id}", $updatedData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_updating_recipes_successfull() {
        $recipe = Recipe::factory()->create();

        $updatedData = [
            'title' => fake()->text(rand(10,20)),
            'description' => fake()->text(),
        ];

        $response = $this->putJson("/api/recipe/{$recipe->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment($updatedData);
    }

    private function actAsUser(array $scopes = [], User $user = null): void
    {
        Passport::actingAs(
            $user ?? $this->user,
            $scopes
        );
    }
}
