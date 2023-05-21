<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewStudioDto;
use game\application\factories\interfaces\StudioFactoryInterface;
use game\domain\models\Studio;
use yii\db\ActiveRecord;

class StudioFactory implements StudioFactoryInterface
{
    public function __construct(
        readonly Studio $studio
    ) {
    }

    public function createNewStudio(NewStudioDto $dto): Studio|ActiveRecord
    {
        $this->studio->name = $dto->name;
        $this->studio->save();

        return $this->studio;
    }
}