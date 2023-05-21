<?php

declare(strict_types=1);

namespace app\controllers;

use game\application\factories\dto\NewGameDto;
use game\application\factories\interfaces\GameFactoryInterface;
use game\application\repositories\dto\UpdatedGameDto;
use game\application\repositories\interfaces\GameRepositoryInterface;
use game\domain\models\Game;
use game\infrastructure\models\forms\GameCreateForm;
use game\infrastructure\models\forms\GameEditForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\rest\ActiveController;
use yii\rest\Serializer;
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
        readonly GameRepositoryInterface $gameRepository,
        readonly GameFactoryInterface $gameFactory,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * @return array|object
     * @throws InvalidConfigException
     */
    public function prepareDataProvider(): array|object
    {
        $genresData = Yii::$app->request->get('genres');
        $genreNames = array_map(static fn ($genre) => strtolower($genre), explode(',', $genresData));

        $games = $this->gameRepository->queryAllGames(Yii::$app->params['expandModelsForGame'], $genreNames);

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $games,
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function actionCreate(): array|null|ActiveRecord
    {
        $newCreateForm = $this->createForm;
        $newCreateForm->load(Yii::$app->request->post(), '');

        if (!$newCreateForm->validate()) {
            return $newCreateForm->errors;
        }

        $newGameDto = new NewGameDto(
            name: $newCreateForm['name'],
            studio: $newCreateForm['studio'],
            genresData: $newCreateForm['genresData']
        );

        $newGame = $this->gameFactory->createNewGame($newGameDto);
        $newGameId = $newGame->id;

        Yii::$app->response->statusCode = 201;
        return $this->gameRepository->findById($newGameId, Yii::$app->params['expandModelsForGame']);
    }

    /**
     * @param int $id
     * @return array|Response
     */
    public function actionUpdate(int $id): array|Response
    {
        $newEditForm = $this->editForm;
        $newEditForm->load(Yii::$app->request->post(), '');

        if (!$newEditForm->validate()) {
            return $newEditForm->errors;
        }

        $updatedGameDto = new UpdatedGameDto(
            name: $newEditForm['name'] ?? null,
            studio: $newEditForm['name'] ?? null,
            genresData: $newEditForm['genresData'] ?? null
        );

        $this->gameRepository->updateGame($id, $updatedGameDto);
        return $this->redirect(
            Yii::$app->params['baseApiRoute'] . '/games/' . $id . '?' . Yii::$app->params['expandParamForRoute']
        );
    }
}