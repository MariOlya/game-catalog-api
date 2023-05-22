<?php

declare(strict_types=1);

namespace game\application\repositories;

use game\application\factories\dto\NewStudioDto;
use game\application\factories\interfaces\StudioFactoryInterface;
use game\application\repositories\dto\UpdatedGameDto;
use game\application\repositories\interfaces\GameRepositoryInterface;
use game\application\services\gameGenre\interfaces\GameGenreServiceInterface;
use game\domain\models\Game;
use game\domain\models\Studio;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class GameRepository implements GameRepositoryInterface
{
    public function __construct(
        readonly StudioFactoryInterface $studioFactory,
        readonly GameGenreServiceInterface $gameGenreService
    ) {
    }

    /**
     * @param string[] $addModels
     * @param string[] $genreNames
     * @return Query|ActiveQuery
     */
    public function queryAllGames(array $addModels = [],  array $genreNames = [],): Query|ActiveQuery
    {
        $games = Game::find()->joinWith($addModels);

        if(!empty($genreNames)) {
            $games = $games->where(['in', 'genre', $genreNames]);
        }

        return $games;
    }

    /**
     * @param int $id
     * @param string[] $addModels
     * @return array|ActiveRecord|null
     */
    public function findById(int $id, array $addModels = []): array|null|ActiveRecord
    {
        return Game::find()
            ->joinWith($addModels)
            ->where(['game.id' => $id])
            ->asArray()
            ->one();
    }

    public function findModelById(int $id, array $addModels = []): array|null|ActiveRecord
    {
        return Game::find()
            ->joinWith($addModels)
            ->where(['game.id' => $id])
            ->one();
    }

    /**
     * @param int $id
     * @param UpdatedGameDto $dto
     * @return Game|ActiveRecord
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function updateGame(int $id, UpdatedGameDto $dto): Game|ActiveRecord
    {
        /** @var Game $game */
        if (!$game = $this->findModelById($id)) {
            throw new NotFoundHttpException('This game is not found', 404);
        }

        if (!$transaction = Yii::$app->db->beginTransaction()) {
            throw new ServerErrorHttpException(
                'Service is not available, please, try later',
                500
            );
        }

        try {
            if ($dto->name) {
                $game->name = $dto->name;
                $game->save();
            }

            if ($dto->studio) {
                $studio = Studio::find()->where(['name' => $dto->studio])->one() ??
                    $this->studioFactory->createNewStudio(new NewStudioDto($dto->studio));

                $game->studio_id = $studio->id;
                $game->save();
            }

            if ($genres = $dto->getArrayGenreNames()) {
                $newRelatedGenreIds = $this->gameGenreService->collectAllRelatedGenres($genres);
                $this->gameGenreService->updateRelations($game, $newRelatedGenreIds);
            }

            $transaction->commit();

            return $game;
        } catch (Throwable $e) {
            $transaction->rollBack();
            Yii::error([$e->getMessage(), $e->getTraceAsString()]);
            throw new ServerErrorHttpException(
                'Service is not available, please, try later',
                500
            );
        }
    }
}