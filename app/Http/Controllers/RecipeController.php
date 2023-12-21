<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeRequest;
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
     * @return JsonResponse
     */
    public function store(RecipeRequest $request): JsonResponse {
        $recipe = $this->service->store(
            $request->get('title'),
            $request->get('description'),
            $request->get('user_id')
        );

        return response()->json([
            'recipe' => $recipe
        ]);
    }
}
