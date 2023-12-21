<?php

namespace App\Services;

use App\DataTransferObjects\RecipeDTO;
use App\Models\Recipe;

class RecipeService
{

    public function store(RecipeDTO $dto) {
        return Recipe::create([
            'title'         => $dto->title,
            'description'   => $dto->description,
            'user_id'       => $dto->userId
        ]);
    }
}
