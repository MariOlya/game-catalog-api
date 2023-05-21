<?php

declare(strict_types=1);

namespace game\infrastructure\models\forms;

class GameEditForm extends GameCreateForm
{
    public function rules(): array
    {
        return [
            [['name', 'studio','genresData'], 'string', 'min' => 3, 'max' => 255],
        ];
    }
}