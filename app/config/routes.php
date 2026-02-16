<?php

use app\controllers\DonControleur;
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

	$router->get('/test', function () use ($app) {
		$app->render('testModels');
	});

	$router->get('/dons/add',[DonControleur::class,"showFormDom"]);

	$router->post('/dons/api/add',[DonControleur::class,"insertDon"]);

}, [ SecurityHeadersMiddleware::class ]);

?>