<?php

declare(strict_types=1);

namespace app\fixtures;

use game\domain\models\Genre;
use yii\test\ActiveFixture;

class GenreFixture extends ActiveFixture
{
    public $modelClass = Genre::class;
}