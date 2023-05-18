<?php

use game\domain\models\Studio;

$studios = Studio::find()->select('id')->asArray()->all();

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->unique()->realTextBetween(5, 20),
    'studio_id' => $faker->randomElement($studios)['id'],
];
