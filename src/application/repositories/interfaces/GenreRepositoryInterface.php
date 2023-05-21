<?php

declare(strict_types=1);

namespace game\application\repositories\interfaces;

interface GenreRepositoryInterface
{
    public function findAllByIds(array $genreIds): array;
}