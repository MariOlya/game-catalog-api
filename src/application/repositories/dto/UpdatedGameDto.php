<?php

declare(strict_types=1);

namespace game\application\repositories\dto;

class UpdatedGameDto
{
    public function __construct(
        readonly ?string $name,
        readonly ?string $studio,
        readonly ?string $genresData
    ) {
    }

    public function getArrayGenreNames(): null|array
    {
        return $this->genresData ?
            array_map(static fn ($genre) => strtolower($genre), explode(',', $this->genresData)) :
            null;
    }
}