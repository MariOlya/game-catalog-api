<?php

declare(strict_types=1);

namespace game\application\factories\interfaces;

use game\application\factories\dto\NewGameGenreDto;
use game\domain\models\GameGenre;
use yii\db\ActiveRecord;

interface GameGenreFactoryInterface
{
    public function createNewRelationGameGenre(NewGameGenreDto $dto): GameGenre|ActiveRecord;
}