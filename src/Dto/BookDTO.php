<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BookDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $author,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $name,

        #[Assert\NotBlank]
        public int $amount,

        #[Assert\NotBlank]
        public float $price,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $currency,

        public bool $visible = false,
    ) {}
}
