<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewGenreDto;
use game\application\factories\interfaces\GenreFactoryInterface;
use game\domain\models\Genre;
use yii\db\ActiveRecord;

class GenreFactory implements GenreFactoryInterface
{
    public function __construct(
        readonly Genre $genre
    ) {
    }

    public function createNewGenre(NewGenreDto $dto): Genre|ActiveRecord
    {
        $this->genre->genre = $dto->genre;
        $this->genre->save();

        return $this->genre;
    }
}