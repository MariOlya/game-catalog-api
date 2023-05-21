<?php

declare(strict_types=1);

namespace game\application\factories;

use game\application\factories\dto\NewGameDto;
use game\application\factories\dto\NewGameGenreDto;
use game\application\factories\dto\NewStudioDto;
use game\application\factories\interfaces\GameFactoryInterface;
use game\application\factories\interfaces\GameGenreFactoryInterface;
use game\application\factories\interfaces\GenreFactoryInterface;
use game\application\factories\interfaces\StudioFactoryInterface;
use game\application\services\gameGenre\interfaces\GameGenreServiceInterface;
use game\domain\models\Game;
use game\domain\models\Studio;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

class GameFactory implements GameFactoryInterface
{
    public function __construct(
        readonly Game $game,
        readonly StudioFactoryInterface $studioFactory,
        readonly GenreFactoryInterface $genreFactory,
        readonly GameGenreFactoryInterface $gameGenreFactory,
        readonly GameGenreServiceInterface $gameGenreService
    ) {
    }

    /**
     * @param NewGameDto $dto
     * @return Game|ActiveRecord
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function createNewGame(NewGameDto $dto): Game|ActiveRecord
    {
        if (!$transaction = Yii::$app->db->beginTransaction()) {
            throw new ServerErrorHttpException(
                'Service is not available, please, try later',
                500
            );
        }

        try {
            $studio = Studio::find()->where(['name' => $dto->studio])->one() ??
                $this->studioFactory->createNewStudio(new NewStudioDto($dto->studio));

            $this->game->name = $dto->name;
            $this->game->studio_id = $studio->id;
            $this->game->save();

            $newRelatedGenreIds = $this->gameGenreService->collectAllRelatedGenres($dto->getArrayGenreNames());

            foreach ($newRelatedGenreIds as $genreId) {
                $this->gameGenreFactory->createNewRelationGameGenre(new NewGameGenreDto($this->game->id, $genreId));
            }

            $transaction->commit();

            if (!$this->game->id) {
                throw new HttpException(422, 'This game has already been added', 422);
            }

            return  $this->game;

        } catch (HttpException $e) {
            throw $e;
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