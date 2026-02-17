<?php

use app\controllers\DonControleur;
use app\controllers\BesoinSinistreControleur;
use app\controllers\AuthController;
use app\controllers\CategorieController;
use app\controllers\ObjetController;
use app\controllers\ProfileController;
use app\controllers\UserController;
use app\controllers\EchangeControleur;
use app\controllers\VilleController;
use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DispatchController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * @var Router $router 
 * @var Engine $app
 */

// Tableau de bord
$router->get('/dashboard', function() {
    $controller = new DashboardController();
    $controller->index();
});

// ===== ROUTES BESOINS =====
$router->get('/besoins/saisie', function() {
    $controller = new app\controllers\BesoinController();
    $controller->saisieBesoin();
});

//insertion des dons
$router->group('/dons', function() use ($router,$app) {
	$router->get('/add',[DonControleur::class,"showFormDom"]);
        $router->post('/api/add',[DonControleur::class,"insertDon"]);
        $router->post('/api/add-no-auto',[DonControleur::class,"insertDonNoAuto"]);
});

//insertion des besoin des sinistrer
$router->group('/besoin_sinistre', function() use ($router,$app) {
	$router->get('/add',[BesoinSinistreControleur::class,"showForm"]);
	$router->post('/api/add',[BesoinSinistreControleur::class,"insertBesoinSinistre"]);
});

$router->post('/besoins/store', function() {
    $controller = new app\controllers\BesoinController();
    $controller->storeBesoin();
});

$router->get('/besoins/liste', function() {
    $controller = new app\controllers\BesoinController();
    $controller->listeBesoins();
});

$router->get('/besoins/ville/@id_ville', function($id_ville) {
    $controller = new app\controllers\BesoinController();
    $controller->besoinsParVille($id_ville);
});

// Détails d'une ville (liste des besoins + dons)
$router->get('/besoins/ville/@id_ville/details', function($id_ville) {
    $controller = new app\controllers\BesoinController();
    $controller->detailsVille($id_ville);
});

// ===== ROUTES DONS =====
$router->get('/dons/saisie', function() {
    $controller = new app\controllers\DonController();
    $controller->saisieDon();
});

$router->post('/dons/store', function() {
    $controller = new app\controllers\DonController();
    $controller->storeDon();
});

$router->get('/dons/liste', function() {
    $controller = new app\controllers\DonController();
    $controller->listeDons();
});

$router->get('/dons/achat', function() {
    $controller = new app\controllers\DonController();
    $controller->achatAvecDons();
});

$router->post('/dons/process-achat', function() {
    $controller = new app\controllers\DonController();
    $controller->processAchat();
});

// ===== ROUTES DISPATCH =====
$router->get('/dispatch/simulation', function() {
    $controller = new app\controllers\DispatchController();
    $controller->simulation();
});

$router->get('/dispatch/simuler/@id_besoin_sinistre', function($id_besoin_sinistre) {
    $controller = new app\controllers\DispatchController();
    $controller->simulerDispatch($id_besoin_sinistre);
});

$router->post('/dispatch/process', function() {
    $controller = new app\controllers\DispatchController();
    $controller->processDispatch();
});

$router->get('/dispatch/etat', function() {
    $controller = new app\controllers\DispatchController();
    $controller->etatBesoin();
});

$router->get('/dispatch/details/@id_besoin_sinistre', function($id_besoin_sinistre) {
    $controller = new app\controllers\DispatchController();
    $controller->detailsBesoin($id_besoin_sinistre);
});

// ===== API ROUTES =====
$router->get('/api/villes/region/@id_region', function($id_region) {
    $controller = new app\controllers\BesoinController();
    $controller->getVillesByRegion();
});

$router->get('/api/besoins/categorie/@id_categorie', function($id_categorie) {
    $controller = new app\controllers\BesoinController();
    $controller->getBesoinsByCategorie($id_categorie);
});

$router->get('/api/stock/disponible', function() {
    $controller = new app\controllers\DispatchController();
    $controller->getStockDisponible();
});

$router->get('/api/besoins/non-satisfaits', function() {
    $controller = new app\controllers\DispatchController();
    $controller->getBesoinsNonSatisfaits();
});

// Route par défaut vers le tableau de bord
$router->get('/', function() {
    Flight::redirect('/dashboard');
});

?>