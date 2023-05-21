<?php

Yii::$container->set(
\game\application\repositories\interfaces\GameRepositoryInterface::class,
    \game\application\repositories\GameRepository::class
);
