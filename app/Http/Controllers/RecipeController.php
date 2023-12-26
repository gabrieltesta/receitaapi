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
}
