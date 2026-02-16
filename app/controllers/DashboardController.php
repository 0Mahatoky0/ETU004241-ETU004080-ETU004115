<?php

namespace app\controllers;

use app\models\BNGRCModel;
use Flight;

class DashboardController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== TABLEAU DE BORD GLOBAL =====
    
    public function index() {
        $stats = $this->model->getDashboardStats();
        $stats_by_region = $this->model->getDashboardByRegion();
        $stock_disponible = $this->model->getStockDisponible();
        $besoins_non_satisfaits = $this->model->getBesoinsNonSatisfaits();
        
        Flight::render('dashboard/index', [
            'stats' => $stats,
            'stats_by_region' => $stats_by_region,
            'stock_disponible' => $stock_disponible,
            'besoins_non_satisfaits' => $besoins_non_satisfaits
        ]);
    }
    
    // ===== API POUR LES GRAPHIQUES =====
    
    public function getChartData() {
        $stats = $this->model->getDashboardStats();
        $stats_by_region = $this->model->getDashboardByRegion();
        
        Flight::json([
            'stats' => $stats,
            'stats_by_region' => $stats_by_region
        ]);
    }
    
    public function getStockChart() {
        $stock = $this->model->getStockDisponible();
        Flight::json($stock);
    }
    
    public function getBesoinChart() {
        $besoins = $this->model->getBesoinsNonSatisfaits();
        Flight::json($besoins);
    }
}
