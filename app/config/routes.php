<?php

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

	// Route pour le tableau de bord (page d'accueil)
	$router->get('/', function() {
		require __DIR__ . '/../views/dashboard.php';
	});
	
	// Routes pour les villes
	$router->get('/villes', [$villeController, 'index']);
	$router->get('/villes/@id', [$villeController, 'show']);
	$router->get('/api/villes', [$villeController, 'apiIndex']);
	
	// Route pour le tableau de bord original
	$router->get('/dashboard', function() {
		require __DIR__ . '/../views/dashboard.php';
	});

}, [ SecurityHeadersMiddleware::class ]);

?>