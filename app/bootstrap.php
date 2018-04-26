<?php
/**
 * Configuration
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

/**
 * Twig
 */
$twigLoader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new Twig_Environment($twigLoader, [
    /*'cache' => __DIR__ . '/../cache'*/
]);

/**
 * Doctrine
 */
$dbParams = [
    'driver' => getenv('APP_DATABASE_DRIVER'),
    'host' => getenv('APP_DATABASE_HOST'),
    'dbname' => getenv('APP_DATABASE_DBNAME'),
    'user' => getenv('APP_DATABASE_USER'),
    'password' => getenv('APP_DATABASE_PASSWORD'),
];
$isDevMode = true;

$dbConf = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../src/Entity'], $isDevMode, null, null, false);
$entityManager = Doctrine\ORM\EntityManager::create($dbParams, $dbConf);

/**
 * Auth
 */
$auth = new App\Controller\AuthController(
    new App\Storage\AuthStorage($entityManager)
);

/**
 * Storage
 */
if ($auth->isLogged()) {
    $storage = new App\Storage\DatabaseStorage($entityManager);
} else {
    //$storage = new App\Storage\JsonStorage();
    $storage = new App\Storage\SessionStorage();
}

/**
 * Controller
 */
$controller = new App\Controller\StorageController($twig, $storage);

/**
 * Router
 */
$router = new App\Router();
