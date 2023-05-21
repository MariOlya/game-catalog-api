<?php

declare(strict_types=1);

namespace game\application\services\gameGenre\interfaces;

use game\domain\models\Game;

interface GameGenreServiceInterface
{
    public function collectAllRelatedGenres(array $genres): array;

    public function updateRelations(Game $game, array $addedGenreIds): void;
}