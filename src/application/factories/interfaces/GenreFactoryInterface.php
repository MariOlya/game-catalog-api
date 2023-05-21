<?php

declare(strict_types=1);

namespace game\application\factories\interfaces;

use game\application\factories\dto\NewGenreDto;
use game\domain\models\Genre;
use yii\db\ActiveRecord;

interface GenreFactoryInterface
{
    public function createNewGenre(NewGenreDto $dto): Genre|ActiveRecord;
}