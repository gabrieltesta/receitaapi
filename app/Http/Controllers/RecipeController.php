<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\RecipeDTO;
use App\Http\Requests\RecipeRequest;
use App\Http\Resources\RecipeCollectionResource;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
    public function __construct(
        protected RecipeService $service,
    )
    {
    }

    /**
     * Stores new recipes
     *
     * @param RecipeRequest $request
     * @return RecipeResource
     */
    public function store(RecipeRequest $request): RecipeResource
    {
        $recipe = $this->service->store(RecipeDTO::fromApiRequest($request));

        return RecipeResource::make($recipe);
    }

    /**
     * Returns a paginated list of recipes
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $recipes = $this->service->listAll();

        return RecipeResource::collection($recipes);
    }


    /**
     * Returns a single recipe record
     *
     * @param int $id
     * @return RecipeResource
     */
    public function show(int $id): RecipeResource
    {
        $recipe = $this->service->show($id);

        return RecipeResource::make($recipe);
    }


    /**
     * Deletes the recipe record if current user is owner, and record exists
     *
     * @param int $id
     * @return JsonResponse|RecipeResource
     */
    public function destroy(int $id): JsonResponse|RecipeResource
    {
        $recipe = Recipe::findOrFail($id);
        if($recipe->user_id != auth()->user()->id)
            return response()->json([])->setStatusCode(Response::HTTP_FORBIDDEN);

        $this->service->destroy($id);

        return RecipeResource::make($recipe);
    }


    /**
     * Updates the recipe record if current user is owner, and record exists
     *
     * @param int $id
     * @return JsonResponse|RecipeResource
     */
    public function update(int $id, RecipeRequest $request): JsonResponse|RecipeResource
    {
        $recipe = Recipe::findOrFail($id);
        if($recipe->user_id != auth()->user()->id)
            return response()->json([])->setStatusCode(Response::HTTP_FORBIDDEN);

        $recipe = $this->service->update($recipe, RecipeDTO::fromApiRequest($request));

        return RecipeResource::make($recipe);
    }
}
