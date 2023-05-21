<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewGameGenreDto;
use game\application\factories\interfaces\GameGenreFactoryInterface;
use game\domain\models\GameGenre;
use yii\db\ActiveRecord;

class GameGenreFactory implements GameGenreFactoryInterface
{
    public function __construct(
        readonly GameGenre $gameGenre
    ) {
    }

    public function createNewRelationGameGenre(NewGameGenreDto $dto): GameGenre|ActiveRecord
    {
        $this->gameGenre->game_id = $dto->gameId;
        $this->gameGenre->genre_id = $dto->genreId;
        $this->gameGenre->save();

        return $this->gameGenre;
    }
}