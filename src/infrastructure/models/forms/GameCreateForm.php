<?php

declare(strict_types=1);

namespace game\infrastructure\models\forms;

use game\domain\models\Genre;
use yii\base\Model;

class GameCreateForm extends Model
{
    /** @var string  */
    public string $name = '';

    /** @var string */
    public string $studio = '';

    /** @var string */
    public string $genresData = '';

    public function rules(): array
    {
        return [
            [['name', 'studio', 'genresData'], 'required'],
            [['name', 'studio','genresData'], 'string', 'min' => 3, 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'name',
            'studio' => 'studio',
            'genresData' => 'genres'
        ];
    }
}