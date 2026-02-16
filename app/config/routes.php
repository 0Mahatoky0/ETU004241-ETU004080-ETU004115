<?php

use app\controllers\DonControleur;
use app\controllers\BesoinSinistreControleur;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	$router->get('/test', function () use ($app) {
		$app->render('testModels');
	});

	//insertion des dons
	$router->group('/dons', function() use ($router,$app) {
		$router->get('/add',[DonControleur::class,"showFormDom"]);
		$router->post('/api/add',[DonControleur::class,"insertDon"]);
	});

	//insertion des besoin des sinistrer
	$router->group('/besoin_sinistre', function() use ($router,$app) {
		$router->get('/add',[BesoinSinistreControleur::class,"showForm"]);
		$router->post('/api/add',[BesoinSinistreControleur::class,"insertBesoinSinistre"]);
	});

}, [ SecurityHeadersMiddleware::class ]);

?>