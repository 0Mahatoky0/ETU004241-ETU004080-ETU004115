<?php

namespace app\controllers;

use app\services\DashboardService;
use Flight;

class DashboardController {
    private $service;
    
    public function __construct() {
        $this->service = new DashboardService();
    }
    
    // ===== TABLEAU DE BORD GLOBAL =====
    
    public function index() {
        $stats = $this->service->getDashboardStats();
        $stats_by_ville = $this->service->getDashboardByVille();
        $stock_disponible = $this->service->getStockDisponible();
        $besoins_non_satisfaits = $this->service->getBesoinsNonSatisfaits();
        
        Flight::render('dashboard/index', [
            'stats' => $stats,
            'stats_by_ville' => $stats_by_ville,
            'stock_disponible' => $stock_disponible,
            'besoins_non_satisfaits' => $besoins_non_satisfaits
        ]);
    }
    
    // ===== API POUR LES GRAPHIQUES =====
    
    public function getChartData() {
        $stats = $this->service->getDashboardStats();
        $stats_by_region = $this->service->getDashboardByVille();
        
        Flight::json([
            'stats' => $stats,
            'stats_by_region' => $stats_by_region
        ]);
    }
    
    public function getStockChart() {
        $stock = $this->service->getStockDisponible();
        Flight::json($stock);
    }
    
    public function getBesoinChart() {
        $besoins = $this->service->getBesoinsNonSatisfaits();
        Flight::json($besoins);
    }
}
