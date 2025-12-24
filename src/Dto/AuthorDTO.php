<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(title: "AuthorDTO", description: "Input data model for a author")]
readonly class AuthorDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        #[OA\Property(description: "Name", type: "string", example: "J.K. Rowling")]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        #[OA\Property(description: "Country", type: "string", example: "UK")]
        public string $country,
    ) {}
}
