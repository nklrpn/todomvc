<?php

// Configuration
$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

// Twig
$twig_loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new Twig_Environment($twig_loader, [
    /*'cache' => __DIR__ . '/../cache'*/
]);

$controller = new App\Controller\Controller($twig);