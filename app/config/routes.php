<?php

use app\controllers\DonControleur;
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
use app\controllers\AchatController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/**
 * BNGRC - ROUTES
 * Gestion des Besoins et Dons
 * 
 * @var Router $router 
 * @var Engine $app
 */

// Tableau de bord
$router->get('/dashboard', function() {
    $controller = new app\controllers\DashboardController();
    $controller->index();
});

// ===== ROUTES BESOINS =====
$router->get('/besoins/saisie', function() {
    $controller = new app\controllers\BesoinController();
    $controller->saisieBesoin();
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

// ===== ROUTES ACHATS =====
$router->get('/achats/besoins-restants', function() {
    $controller = new app\controllers\AchatController();
    $controller->listeBesoinsRestants();
});

$router->get('/achats/simulation', function() {
    $controller = new app\controllers\AchatController();
    $controller->simulationAchat();
});

$router->post('/achats/api/simuler', function() {
    $controller = new app\controllers\AchatController();
    $controller->apiSimulerAchat();
});

$router->post('/achats/valider', function() {
    $controller = new app\controllers\AchatController();
    $controller->validerAchat();
});

$router->get('/achats/liste', function() {
    $controller = new app\controllers\AchatController();
    $controller->listeAchats();
});

$router->get('/achats/configuration', function() {
    $controller = new app\controllers\AchatController();
    $controller->configuration();
});

$router->post('/achats/configuration', function() {
    $controller = new app\controllers\AchatController();
    $controller->configuration();
});

// ===== API ROUTES =====
$router->get('/api/villes/region/@id_region', function($id_region) {
    $controller = new app\controllers\BesoinController();
    $controller->getVillesByRegion();
});

$router->get('/api/besoins/categorie/@id_categorie', function($id_categorie) {
    $controller = new app\controllers\BesoinController();
    $controller->getBesoinsByCategorie();
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