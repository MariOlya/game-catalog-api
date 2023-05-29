<?php

declare(strict_types=1);

namespace game\application\repositories\interfaces;

use yii\db\ActiveRecord;

interface GenreRepositoryInterface
{
    public function findAllByIds(array $genreIds): array;

    public function findModelById(int $id, array $addModels = []): array|null|ActiveRecord;

    public function deleteById(int $id): void;
}