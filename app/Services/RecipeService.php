<?php

namespace App\Services;

use App\Models\Recipe;

class RecipeService
{

    public function store(string $title, string $description, int $userId) {
        return Recipe::create([
            'title'         => $title,
            'description'   => $description,
            'user_id'       => $userId
        ]);
    }
}
