<?php

declare(strict_types=1);

namespace app\fixtures;

use game\domain\models\Game;
use yii\test\ActiveFixture;

class GameFixture extends ActiveFixture
{
    public $modelClass = Game::class;
}