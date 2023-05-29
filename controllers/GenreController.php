<?php

declare(strict_types=1);

namespace app\controllers;

use game\application\repositories\interfaces\GenreRepositoryInterface;
use Yii;
use yii\rest\Controller;

class GenreController extends Controller
{
    public function __construct(
        $id,
        $module,
        readonly GenreRepositoryInterface $genreRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @param int $id
     * @return void
     */
    public function actionDelete(int $id)
    {
        $this->genreRepository->deleteById($id);

        Yii::$app->response->statusCode = 204;
    }
}