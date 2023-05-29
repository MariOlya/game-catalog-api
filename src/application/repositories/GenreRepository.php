<?php

declare(strict_types=1);

namespace game\application\repositories;

use game\application\repositories\interfaces\GenreRepositoryInterface;
use game\domain\models\Genre;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class GenreRepository implements GenreRepositoryInterface
{
    /**
     * @param array $genreIds
     * @return array
     */
    public function findAllByIds(array $genreIds): array
    {
        return Genre::find()->where(['id' => $genreIds])->all();
    }

    /**
     * @param int $id
     * @param array $addModels
     * @return array|ActiveRecord|null
     */
    public function findModelById(int $id, array $addModels = []): array|null|ActiveRecord
    {
        return Genre::find()
            ->joinWith($addModels)
            ->where(['genre.id' => $id])
            ->one();
    }

    /**
     * @param int $id
     * @return void
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteById(int $id): void
    {
        /** @var Genre $genre */
        if (!$genre = $this->findModelById($id, Yii::$app->params['expandModelsForGenre'])) {
            throw new NotFoundHttpException('This genre is not found', 404);
        }
        if ($genre->games) {
            throw new BadRequestHttpException('You can not delete this genre because it has linked games', 400);
        }

        $genre->delete();
    }
}