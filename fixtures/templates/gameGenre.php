<?php

use game\domain\models\Game;
use game\domain\models\Genre;

$games = Game::find()->select('id')->asArray()->all();
$genres = Genre::find()->select('id')->asArray()->all();

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'game_id' => $faker->unique()->randomElement($games)['id'],
    'genre_id' => $faker->randomElement($genres)['id'],
];
