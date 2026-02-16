<?php
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\models\DonModel;

/**
 * TAKALO TAKALO - ROUTES
 * Architecture Refactorisée
 * 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	$router->get('/test', function () use ($app) {
		$app->render('testModels');
	});

}, [ SecurityHeadersMiddleware::class ]);

?>