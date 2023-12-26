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

    /**
     * Creates a new DTO from the request object
     *
     * @param RecipeRequest $request
     * @return RecipeDTO
     */
    public static function fromApiRequest(RecipeRequest $request): RecipeDTO
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            userId: auth('api')->user()->id,
        );
    }

    /**
     * Returns current DTO to array
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
