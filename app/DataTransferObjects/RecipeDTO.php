<?php

namespace App\DataTransferObjects;

use App\Http\Requests\RecipeRequest;

class RecipeDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly int    $userId
    )
    {
    }

    public static function fromApiRequest(RecipeRequest $request): RecipeDTO
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            userId: $request->validated('user_id')
        );
    }
}
