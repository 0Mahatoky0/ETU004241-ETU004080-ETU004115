<?php

use app\controllers\AuthController;
use app\controllers\CategorieController;
use app\controllers\ObjetController;
use app\controllers\ProfileController;
use app\controllers\UserController;
use app\controllers\EchangeControleur;
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

$router->group('', function(Router $router) use ($app) {

	// Route pour le tableau de bord
	$router->get('/dashboard', function() {
		require __DIR__ . '/../views/dashboard.php';
	});
	
	// Route par défaut
	$router->get('/', function() {
		echo '<h1>Bienvenue!</h1><p><a href="/dashboard">Accéder au tableau de bord</a></p>';
	});

}, [ SecurityHeadersMiddleware::class ]);

?>