<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(title: "BookDTO", description: "Input data model for a book")]
readonly class BookDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[OA\Property(description: "Author ID", type: "integer", example: 1)]
        public int $authorId,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        #[OA\Property(description: "Book name", type: "string", example: "Harry Potter and the Goblet of Fire")]
        public string $name,

        #[Assert\NotBlank]
        #[OA\Property(description: "Book amount", type: "integer", example: 45)]
        public int $amount,

        #[Assert\NotBlank]
        #[OA\Property(description: "Price", type: "float", example: 1)]
        public float $price,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        #[OA\Property(description: "Price currency", type: "string", example: "USD")]
        public string $currency,

        #[OA\Property(description: "Visibility", type: "boolean", default: false, example: false)]
        public bool $visible = false,
    ) {}
}
