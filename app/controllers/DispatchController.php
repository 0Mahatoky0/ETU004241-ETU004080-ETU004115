<?php

namespace app\controllers;

use app\models\BNGRCModel;
use Exception;
use Flight;

class DispatchController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== SIMULATION DE DISPATCH =====
    
    public function simulation() {
        $stock_disponible = $this->model->getStockDisponible();
        $besoins_non_satisfaits = $this->model->getBesoinsNonSatisfaits();
        $tous_besoins = $this->model->getAllBesoinsSinistre();
        
        Flight::render('dispatch/simulation', [
            'stock_disponible' => $stock_disponible,
            'besoins_non_satisfaits' => $besoins_non_satisfaits,
            'tous_besoins' => $tous_besoins
        ]);
    }
    
    public function simulerDispatch($id_besoin_sinistre) {
        $besoin_details = null;
        $besoins = $this->model->getAllBesoinsSinistre();
        foreach ($besoins as $besoin) {
            if ($besoin['id'] == $id_besoin_sinistre) {
                $besoin_details = $besoin;
                break;
            }
        }
        
        if (!$besoin_details) {
            Flight::redirect(BASE_URL . '/dispatch/simulation?error=' . urlencode('Besoin non trouvé'));
            return;
        }
        
        $stock_disponible = $this->model->getStockDisponible();
        $stock_pour_besoin = array_filter($stock_disponible, fn($s) => $s['besoin_id'] == $besoin_details['id_besoin']);
        
        Flight::render('dispatch/simuler', [
            'besoin' => $besoin_details,
            'stock_disponible' => $stock_pour_besoin
        ]);
    }
    
    public function processDispatch() {
        $data = Flight::request()->data;
        
        try {
            $id_besoin_sinistre = $data['id_besoin_sinistre'];
            $quantite = $data['quantite'];
            
            if (empty($id_besoin_sinistre) || empty($quantite)) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            if ($quantite <= 0) {
                throw new Exception("La quantité doit être supérieure à 0");
            }
            
            $success = $this->model->simulerDispatch($id_besoin_sinistre, $quantite);
            
            if ($success) {
                Flight::redirect(BASE_URL . '/dispatch/simulation?success=dispatch');
            } else {
                throw new Exception("Erreur lors de la simulation de dispatch");
            }
            
        } catch (Exception $e) {
            Flight::redirect(BASE_URL . '/dispatch/simuler/' . $id_besoin_sinistre . '?error=' . urlencode($e->getMessage()));
        }
    }
    
    // ===== BESOINS SATISFAITS/RESTANTS =====
    
    public function etatBesoin() {
        $tous_besoins = $this->model->getAllBesoinsSinistre();
        $besoins_non_satisfaits = $this->model->getBesoinsNonSatisfaits();
        
        // Calculer les besoins satisfaits
        $besoins_satisfaits = array_filter($tous_besoins, function($besoin) use ($besoins_non_satisfaits) {
            foreach ($besoins_non_satisfaits as $non_satisfait) {
                if ($non_satisfait['id'] == $besoin['id']) {
                    return false;
                }
            }
            return true;
        });
        
        Flight::render('dispatch/etat', [
            'tous_besoins' => $tous_besoins,
            'besoins_satisfaits' => $besoins_satisfaits,
            'besoins_non_satisfaits' => $besoins_non_satisfaits
        ]);
    }
    
    public function detailsBesoin($id_besoin_sinistre) {
        $besoin_details = null;
        $tous_besoins = $this->model->getAllBesoinsSinistre();
        foreach ($tous_besoins as $besoin) {
            if ($besoin['id'] == $id_besoin_sinistre) {
                $besoin_details = $besoin;
                break;
            }
        }
        
        if (!$besoin_details) {
            Flight::redirect(BASE_URL . '/dispatch/etat?error=' . urlencode('Besoin non trouvé'));
            return;
        }
        
        // Récupérer les mouvements pour ce besoin
        $mouvements = $this->model->getAllMouvementsDons();
        $mouvements_besoin = array_filter($mouvements, fn($m) => $m['id_besoin_sinistre'] == $id_besoin_sinistre);
        
        Flight::render('dispatch/details', [
            'besoin' => $besoin_details,
            'mouvements' => $mouvements_besoin
        ]);
    }
    
    // ===== API POUR AJAX =====
    
    public function getStockDisponible() {
        $stock = $this->model->getStockDisponible();
        Flight::json($stock);
    }
    
    public function getBesoinsNonSatisfaits() {
        $besoins = $this->model->getBesoinsNonSatisfaits();
        Flight::json($besoins);
    }
    
    public function getDispatchPreview() {
        $id_besoin_sinistre = Flight::request()->query['id_besoin_sinistre'];
        $quantite = Flight::request()->query['quantite'];
        
        if (empty($id_besoin_sinistre) || empty($quantite)) {
            Flight::json(['error' => 'Paramètres requis'], 400);
            return;
        }
        
        try {
            // Récupérer le besoin
            $tous_besoins = $this->model->getAllBesoinsSinistre();
            $besoin = null;
            foreach ($tous_besoins as $b) {
                if ($b['id'] == $id_besoin_sinistre) {
                    $besoin = $b;
                    break;
                }
            }
            
            if (!$besoin) {
                Flight::json(['error' => 'Besoin non trouvé'], 404);
                return;
            }
            
            // Récupérer les dons disponibles
            $stock_disponible = $this->model->getStockDisponible();
            $stock_pour_besoin = array_filter($stock_disponible, fn($s) => $s['besoin_id'] == $besoin['id_besoin']);
            
            $quantite_restante = $quantite;
            $allocation = [];
            
            foreach ($stock_pour_besoin as $stock) {
                if ($quantite_restante <= 0) break;
                
                $quantite_allouee = min($quantite_restante, $stock['stock_disponible']);
                $allocation[] = [
                    'besoin_libelle' => $stock['besoin_libelle'],
                    'stock_disponible' => $stock['stock_disponible'],
                    'quantite_allouee' => $quantite_allouee,
                    'reste_apres_allocation' => $stock['stock_disponible'] - $quantite_allouee
                ];
                
                $quantite_restante -= $quantite_allouee;
            }
            
            Flight::json([
                'besoin' => $besoin,
                'quantite_demandee' => $quantite,
                'allocation_possible' => $quantite_restante == 0,
                'allocation' => $allocation,
                'quantite_non_allouee' => $quantite_restante
            ]);
            
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
