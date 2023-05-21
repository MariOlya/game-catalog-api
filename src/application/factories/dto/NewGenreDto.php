<?php

declare(strict_types=1);

namespace game\application\factories\dto;

class NewGenreDto
{
    public function __construct(
        readonly string $genre
    ) {
    }
}