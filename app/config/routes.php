<?php

use app\controllers\DonControleur;
use app\controllers\BesoinSinistreControleur;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

use app\services\BesoinSinistreServices;

/**
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

	$router->get('/test', function () use ($app) {
		$app->render('testModels');
	});

	//insertion des dons
	$router->get('/dons/add',[DonControleur::class,"showFormDom"]);
	$router->post('/dons/api/add',[DonControleur::class,"insertDon"]);

	//insertion des besoin des sinistrer
	$router->get('/besoin_sinistre/add',[BesoinSinistreControleur::class,"showForm"]);
	$router->post('/besoin_sinistre/api/add',[BesoinSinistreControleur::class,"insertBesoinSinistre"]);

}, [ SecurityHeadersMiddleware::class ]);

?>