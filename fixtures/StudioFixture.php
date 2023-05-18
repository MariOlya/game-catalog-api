<?php

declare(strict_types=1);

namespace app\fixtures;

use yii\test\ActiveFixture;
use game\domain\models\Studio;

class StudioFixture extends ActiveFixture
{
    public $modelClass = Studio::class;
}