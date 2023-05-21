<?php

declare(strict_types=1);

namespace app\controllers;

use game\domain\models\Game;
use game\domain\models\GameGenre;
use game\domain\models\Genre;
use game\domain\models\Studio;
use game\infrastructure\models\forms\GameCreateForm;
use game\infrastructure\models\forms\GameEditForm;
use Psy\Util\Json;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\rest\Serializer;
use yii\web\HttpException;
use yii\web\JsonResponseFormatter;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\Response;

class GameController extends ActiveController
{
    public $modelClass = Game::class;
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    public function __construct(
        $id,
        $module,
        readonly GameCreateForm $createForm,
        readonly GameEditForm $editForm,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }


    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete" и "create"
        unset($actions['create'], $actions['update']);

        // настроить подготовку провайдера данных с помощью метода "prepareDataProvider()"
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $genresData = Yii::$app->request->get('genres');
        $genres = array_map(static fn ($genre) => strtolower($genre), explode(',', $genresData));
        $games = Game::find()
            ->joinWith(['studio', 'genres']);
        if($genres) {
            $games = $games->where(['in', 'genre', $genres]);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $games,
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
    }

    public function actionCreate()
    {
        $newCreateForm = $this->createForm;
        $newCreateForm->load(Yii::$app->request->post(), '');

        if (!$newCreateForm->validate()) {
            return $newCreateForm->errors;
        }

        if (!$transaction = Yii::$app->db->beginTransaction()) {
            throw new ServerErrorHttpException(
                'Service is not available, please, try later',
                500
            );
        }

        $studioName = $newCreateForm['studio'];
        $gameName = $newCreateForm['name'];
        $genresData = $newCreateForm['genresData'];
        $genres = array_map(static fn ($genre) => strtolower($genre), explode(',', $genresData));

        try {
            /** @var Studio $studio */
            if ($studio = Studio::find()->where(['name' => strtolower($studioName)])->one()) {
                $studioId = $studio->id;
            } else {
                $newStudio = new Studio();
                $newStudio->name = $studioName;
                $newStudio->save();
                $studioId = $newStudio->id;
            }

            $newGame = new Game();
            $newGame->name = $gameName;
            $newGame->studio_id = $studioId;
            $newGame->save();

            $alreadyExistedGenres = Genre::find()->where(['genre' =>$genres])->asArray()->all();
            $existedGenres = array_map(
                static fn ($genreData) => $genreData['genre'],
                $alreadyExistedGenres
            );
            $genreIds = array_map(
                static fn ($genreData) => $genreData['id'],
                $alreadyExistedGenres
            );

            foreach ($genres as $genre) {
                $isExisted = in_array(strtolower($genre), $existedGenres, true);
                if (!$isExisted) {
                    $newGenre = new Genre();
                    $newGenre->genre = $genre;
                    $newGenre->save();

                    $genreIds[] = $newGenre->id;
                }
            }

            foreach ($genreIds as $genreId) {
                $newRelationGameGenre = new GameGenre();
                $newRelationGameGenre->game_id = $newGame->id;
                $newRelationGameGenre->genre_id = $genreId;
                $newRelationGameGenre->save();
            }

            $transaction->commit();

            $newGameId = $newGame->id;

            if (!$newGameId) {
                throw new HttpException(422, 'This game has already been added', 422);
            }

            Yii::$app->response->statusCode = 201;

            return  Game::find()
                    ->joinWith(['studio', 'genres'])
                ->where(['game.id' => $newGameId])
                ->asArray()
                ->one();

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

    public function actionUpdate(int $id)
    {
        if (!$game = Game::findOne($id)) {
            throw new NotFoundHttpException('This game is not found', 404);
        }

        $newEditForm = $this->editForm;
        $newEditForm->load(Yii::$app->request->post(), '');

        if (!$newEditForm->validate()) {
            return $newEditForm->errors;
        }

        if (!$transaction = Yii::$app->db->beginTransaction()) {
            throw new ServerErrorHttpException(
                'Service is not available, please, try later',
                500
            );
        }

        $studioName = $newEditForm['studio'] ?? null;
        $gameName = $newEditForm['name'] ?? null;

        if ($genresData = $newEditForm['genresData'] ?? null) {
            $genres = array_map(static fn ($genre) => strtolower($genre), explode(',', $genresData));
        }

        try {
            if ($gameName) {
                $game->name = $gameName;
                $game->save();
            }

            if ($studioName) {
                /** @var Studio $studio */
                if ($studio = Studio::find()->where(['name' => strtolower($studioName)])->one()) {
                    $studioId = $studio->id;
                } else {
                    $newStudio = new Studio();
                    $newStudio->name = $studioName;
                    $newStudio->save();
                    $studioId = $newStudio->id;
                }
                $game->studio_id = $studioId;
                $game->save();
            }

            if (isset($genres)) {
                $currentGameGenresIds = array_map(
                    static function ($gameGenre) {
                        return $gameGenre->id;
                    },
                    $game->genres
                );
                $alreadyExistedGenres = Genre::find()->where(['genre' => $genres])->asArray()->all();
                $existedGenres = array_map(
                    static fn($genreData) => $genreData['genre'],
                    $alreadyExistedGenres
                );
                $genreIds = array_map(
                    static fn($genreData) => $genreData['id'],
                    $alreadyExistedGenres
                );

                foreach ($genres as $genre) {
                    $isExisted = in_array(strtolower($genre), $existedGenres, true);
                    if (!$isExisted) {
                        $newGenre = new Genre();
                        $newGenre->genre = $genre;
                        $newGenre->save();

                        $genreIds[] = $newGenre->id;
                    }
                }

                $outdatedGenres = array_diff($currentGameGenresIds, $genreIds);
                $updatedGenres = array_diff($genreIds, $currentGameGenresIds);

                if ($outdatedGenres) {
                    $deletedGenres = GameGenre::find()
                        ->where(['genre_id' => $outdatedGenres])
                        ->andWhere(['game_id' => $id])
                        ->all();

                    /** @var GameGenre $genre */
                    foreach ($deletedGenres as $genre) {
                        $genre->delete();
                    }
                }

                if ($updatedGenres) {
                    foreach ($updatedGenres as $genreId) {
                        $newRelationGameGenre = new GameGenre();
                        $newRelationGameGenre->game_id = $id;
                        $newRelationGameGenre->genre_id = $genreId;
                        $newRelationGameGenre->save();
                    }
                }
            }

            $transaction->commit();

            return $this->redirect('http://localhost:8000/api/games/'.$id.'?expand=studio, genres', );
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