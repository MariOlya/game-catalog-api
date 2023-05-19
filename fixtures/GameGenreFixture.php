<?php

declare(strict_types=1);

namespace app\fixtures;

use game\domain\models\GameGenre;
use yii\test\ActiveFixture;

class GameGenreFixture extends ActiveFixture
{
    public $modelClass = GameGenre::class;
    public $dataFile = __DIR__ . '/data/gameGenre.php';
}