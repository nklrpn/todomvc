<?php

// Configuration
$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

// Twig
$twig_loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new Twig_Environment($twig_loader, [
    /*'cache' => __DIR__ . '/../cache'*/
]);

$template = $twig->load('index.twig');
echo $template->render([
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