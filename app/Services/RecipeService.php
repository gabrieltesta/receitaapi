<?php

namespace App\Services;

use App\DataTransferObjects\RecipeDTO;
use App\Models\Recipe;

class RecipeService
{

    /**
     * Stores new recipes
     *
     * @param RecipeDTO $dto
     * @return Recipe
     */
    public function store(RecipeDTO $dto): Recipe
    {
        return Recipe::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'user_id' => $dto->userId
        ]);
    }
}
