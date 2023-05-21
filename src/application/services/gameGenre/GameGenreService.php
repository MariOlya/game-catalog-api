<?php

namespace game\application\services\gameGenre;

use game\application\factories\dto\NewGameGenreDto;
use game\application\factories\dto\NewGenreDto;
use game\application\factories\GenreFactory;
use game\application\factories\interfaces\GameGenreFactoryInterface;
use game\application\factories\interfaces\GenreFactoryInterface;
use game\application\repositories\interfaces\GenreRepositoryInterface;
use game\application\repositories\interfaces\GameRepositoryInterface;
use game\application\services\gameGenre\interfaces\GameGenreServiceInterface;
use game\domain\models\Game;
use game\domain\models\GameGenre;
use game\domain\models\Genre;
use yii\db\Exception;
use yii\db\StaleObjectException;

class GameGenreService implements GameGenreServiceInterface
{
    public function __construct(
        readonly GenreFactoryInterface $genreFactory,
        readonly GenreRepositoryInterface $genreRepository,
        readonly GameGenreFactoryInterface $gameGenreFactory
    ) {
    }

    public function collectAllRelatedGenres(array $genres): array
    {
        $alreadyExistedGenreModels = Genre::find()->where(['genre' => $genres])->asArray()->all();
        $existedGenres = array_map(
            static fn ($genreData) => $genreData['genre'],
            $alreadyExistedGenreModels
        );

        $genreIds = array_map(
            static fn ($genreData) => $genreData['id'],
            $alreadyExistedGenreModels
        );

        foreach ($genres as $genre) {
            $isExisted = in_array(strtolower($genre), $existedGenres, true);
            if (!$isExisted) {
                $newGenre = $this->genreFactory->createNewGenre(new NewGenreDto($genre));
                $genreIds[] = $newGenre->id;
            }
        }

        return $genreIds;
    }

    /**
     * @param Game $game
     * @param array $addedGenreIds
     * @return void
     * @throws Exception
     * @throws StaleObjectException
     */
    public function updateRelations(Game $game, array $addedGenreIds): void
    {
        $currentGameGenresIds = array_map(
            static function ($gameGenre) {
                return $gameGenre->id;
            },
            $game->genres
        );

        if ($outdatedGenresIds = array_diff($currentGameGenresIds, $addedGenreIds)) {
            $genresWithDeletedRelation = $this->genreRepository->findAllByIds($outdatedGenresIds);

            /** @var GameGenre $genre */
            foreach ($genresWithDeletedRelation as $genre) {
                $game->unlink('genres', $genre, true);
            }
        }

        if ($updatedGenresIds = array_diff($addedGenreIds, $currentGameGenresIds)) {
            foreach ($updatedGenresIds as $genreId) {
                $this->gameGenreFactory->createNewRelationGameGenre(new NewGameGenreDto($game->id, $genreId));
            }
        }
    }
}