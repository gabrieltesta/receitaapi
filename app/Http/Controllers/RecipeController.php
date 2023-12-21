<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\RecipeDTO;
use App\Http\Requests\RecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        protected RecipeService $service
    )
    {
    }

    /**
     * @param RecipeRequest $request
     * @return RecipeResource
     */
    public function store(RecipeRequest $request): RecipeResource
    {
        $recipe = $this->service->store(RecipeDTO::fromApiRequest($request));


        return RecipeResource::make(
            $recipe
        );
//        return response()->json([
//            'recipe' => $recipe
//        ]);
    }
}
