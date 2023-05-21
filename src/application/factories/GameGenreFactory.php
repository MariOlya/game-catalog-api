<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewGameGenreDto;
use game\application\factories\interfaces\GameGenreFactoryInterface;
use game\domain\models\GameGenre;
use yii\db\ActiveRecord;

class GameGenreFactory implements GameGenreFactoryInterface
{
    public function createNewRelationGameGenre(NewGameGenreDto $dto): GameGenre|ActiveRecord
    {
        $newRelation = new GameGenre();
        $newRelation->game_id = $dto->gameId;
        $newRelation->genre_id = $dto->genreId;
        $newRelation->save();

        return $newRelation;
    }
}