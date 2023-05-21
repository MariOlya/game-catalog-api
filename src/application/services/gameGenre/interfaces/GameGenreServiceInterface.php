<?php

declare(strict_types=1);

namespace game\application\services\gameGenre\interfaces;

interface GameGenreServiceInterface
{
    public function collectAllRelatedGenres(array $genres): array;

    public function updateRelations(int $gameId, array $addedGenreIds): void;
}