<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class AuthorDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $country,
    ) {}
}
