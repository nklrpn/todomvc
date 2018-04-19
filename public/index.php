<?php
require_once __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../app/bootstrap.php';

$controller->render('index.twig', [
    'todos' => [
        [
            'id' => 1,
            'text' => 'Taste JavaScript',
            'is_completed' => true,
        ],
        [
            'id' => 2,
            'text' => 'Buy a unicorn',
            'is_completed' => false,
        ],
        [
            'id' => 3,
            'text' => 'Try Twig',
            'is_completed' => false,
        ],
    ],
]);