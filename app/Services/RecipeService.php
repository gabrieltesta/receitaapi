<?php

namespace App\Services;

use App\DataTransferObjects\RecipeDTO;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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

    /**
     * Returns a paginated list of recipes
     *
     * @param int $paginationSize Number of items in pagination
     * @return LengthAwarePaginator
     */
    public function listAll(int $paginationSize = 50): LengthAwarePaginator
    {
        return Recipe::paginate($paginationSize);
    }

    /**
     * @param int $id
     * @return Recipe
     */
    public function show(int $id): Recipe {
        return Recipe::findOrFail($id);
    }
}
