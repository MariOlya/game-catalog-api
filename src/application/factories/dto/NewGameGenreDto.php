<?php

declare(strict_types=1);

namespace game\application\factories\dto;

class NewGameGenreDto
{
    public function __construct(
        readonly int $gameId,
        readonly int $genreId
    ) {
    }
}