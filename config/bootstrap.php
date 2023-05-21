<?php

Yii::$container->set(
\game\application\repositories\interfaces\GameRepositoryInterface::class,
    \game\application\repositories\GameRepository::class
);

Yii::$container->set(
    \game\application\factories\interfaces\GameFactoryInterface::class,
    \game\application\factories\GameFactory::class
);

Yii::$container->set(
    \game\application\factories\interfaces\StudioFactoryInterface::class,
    \game\application\factories\StudioFactory::class
);

Yii::$container->set(
    \game\application\factories\interfaces\GenreFactoryInterface::class,
    \game\application\factories\GenreFactory::class
);

Yii::$container->set(
    \game\application\factories\interfaces\GameGenreFactoryInterface::class,
    \game\application\factories\GameGenreFactory::class
);

Yii::$container->set(
    \game\application\repositories\interfaces\GenreRepositoryInterface::class,
    \game\application\repositories\GenreRepository::class
);

Yii::$container->set(
    \game\application\services\gameGenre\interfaces\GameGenreServiceInterface::class,
    \game\application\services\gameGenre\GameGenreService::class
);
