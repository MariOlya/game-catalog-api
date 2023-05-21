<?php

declare(strict_types=1);

namespace game\application\repositories\interfaces;

use game\application\repositories\dto\UpdatedGameDto;
use game\domain\models\Game;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

interface GameRepositoryInterface
{
    public function queryAllGames(array $addModels = [], array $genreNames = [], int $limit = 50, int $offset = 0): Query|ActiveQuery;

    public function findById(int $id, array $addModels = []): array|null|ActiveRecord;

    public function updateGame(int $id, UpdatedGameDto $dto): Game|ActiveRecord;
}