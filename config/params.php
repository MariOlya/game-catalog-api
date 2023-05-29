<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'expandModelsForGame' => ['studio', 'genres'],
    'expandModelsForGenre' => ['games'],
    'expandParamForRoute' => 'expand=studio, genres',
    'baseApiRoute' => 'http://localhost:8000/api',
    'defaultCountGivenGames' => 50,
];
