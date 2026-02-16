<?php

use app\controllers\DonControleur;
use app\controllers\AuthController;
use app\controllers\CategorieController;
use app\controllers\ObjetController;
use app\controllers\ProfileController;
use app\controllers\UserController;
use app\controllers\EchangeControleur;
use app\controllers\VilleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * TAKALO TAKALO - ROUTES
 * Architecture Refactorisée
 * 
 * @var Router $router 
 * @var Engine $app
 */

// Instancier le contrôleur des villes
$villeController = new VilleController($app);

$router->group('', function(Router $router) use ($app, $villeController) {

	//$router->get('/', [$authController, 'showRegister']);

}, [ SecurityHeadersMiddleware::class ]);

?>