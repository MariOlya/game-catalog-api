<?php

declare(strict_types=1);

namespace game\application\factories\interfaces;

use game\application\factories\dto\NewGameDto;
use game\domain\models\Game;
use yii\db\ActiveRecord;

interface GameFactoryInterface
{
    public function createNewGame(NewGameDto $dto): Game|ActiveRecord;
}