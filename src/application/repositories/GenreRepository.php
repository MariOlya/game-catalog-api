<?php

declare(strict_types=1);

namespace game\application\repositories;

use game\application\repositories\interfaces\GenreRepositoryInterface;
use game\domain\models\Genre;

class GenreRepository implements GenreRepositoryInterface
{
    public function findAllByIds(array $genreIds): array
    {
        return Genre::find()->where(['id' => $genreIds])->all();
    }
}