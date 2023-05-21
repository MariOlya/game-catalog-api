<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewGenreDto;
use game\application\factories\interfaces\GenreFactoryInterface;
use game\domain\models\Genre;
use yii\db\ActiveRecord;

class GenreFactory implements GenreFactoryInterface
{
    public function createNewGenre(NewGenreDto $dto): Genre|ActiveRecord
    {
        $newGenre = new Genre();
        $newGenre->genre = $dto->genre;
        $newGenre->save();

        return $newGenre;
    }
}