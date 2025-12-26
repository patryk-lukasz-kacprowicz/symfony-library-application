<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(title: "ReviewDTO", description: "Input data model for a review")]
readonly class ReviewDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[OA\Property(description: "Book ID", type: "integer", example: 1)]
        public int $bookId,

        #[Assert\NotBlank]
        #[Assert\Type(type: "float")]
        #[Assert\Range(min: 0.0, max: 10.0)]
        #[OA\Property(description: "Review score", type: "float", example: 5.3)]
        public float $score,

        #[Assert\NotBlank]
        #[Assert\Type(type: "string")]
        #[Assert\Length(min: 1)]
        #[OA\Property(description: "Message", type: "string", example: "Harry Potter is awesome!")]
        public string $message,
    ) {}
}
