<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraint as Assert;

class CreateBlogPostDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $title,

        #[Assert\NotBlank]
        #[Assert\Length(min: 10)]
        public readonly string $content,
    ) {}
}
