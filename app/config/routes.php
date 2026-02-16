<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

use app\services\BesoinSinistreServices;

/**
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->redirect('/home');
	});

	$router->get('/home', function() use ($app) {
		$service = new BesoinSinistreServices(Flight::db());
		$app->render('besoinSinistre/liste', [
			'besoins' => $service->getNonDistribues()
		]);
	});

}, [ SecurityHeadersMiddleware::class ]);

?>