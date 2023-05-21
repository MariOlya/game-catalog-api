<?php

declare(strict_types=1);

namespace game\application\factories\dto;

use game\application\repositories\dto\UpdatedGameDto;

class NewGameDto extends UpdatedGameDto
{
    public function __construct(
        string $name,
        string $studio,
        string $genresData
    ) {
       parent::__construct(
           $name,
           $studio,
           $genresData
       ) ;
    }
}