<?php

namespace game\application\factories\interfaces;

use game\application\factories\dto\NewStudioDto;
use game\domain\models\Studio;
use yii\db\ActiveRecord;

interface StudioFactoryInterface
{
    public function createNewStudio(NewStudioDto $dto): Studio|ActiveRecord;
}