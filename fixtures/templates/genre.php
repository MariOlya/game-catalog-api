<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'genre' => $faker->unique()->realTextBetween(5, 20),
];
