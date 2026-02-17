<?php

namespace app\controllers;

use Exception;
use app\models\BNGRCModel;
use Flight;

class DistributionController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    public function index() {
        // Vérifier s'il y a une simulation en cours
        $distribution_en_cours = isset($_SESSION['distribution']) ? $_SESSION['distribution'] : null;
        
        $besoins = $this->model->getBesoinsForDistribution();
        $stock = $this->model->getStockBngrc();
        $statistiques = $this->model->getStatistiquesDistribution();
        
        Flight::render('distribution/index', [
            'besoins' => $besoins,
            'stock' => $stock,
            'statistiques' => $statistiques,
            'distribution_en_cours' => $distribution_en_cours
        ]);
    }
    
    public function simulerDistribution() {
        try {
            $distribution = $this->model->simulerDistribution();
            
            // Stocker la simulation en session
            $_SESSION['distribution'] = $distribution;
            
            Flight::redirect('/distribution?simulation=1');
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function validerDistribution() {
        try {
            // Vérifier qu'il y a une simulation en cours
            if (!isset($_SESSION['distribution'])) {
                throw new Exception("Aucune simulation en cours à valider");
            }
            
            $distribution = $_SESSION['distribution'];
            $success = $this->model->validerDistribution($distribution);
            
            if ($success) {
                // Supprimer la simulation de la session
                unset($_SESSION['distribution']);
                Flight::redirect('/distribution?valide=1');
            } else {
                throw new Exception("Erreur lors de la validation de la distribution");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function reinitialiserDistribution() {
        try {
            $success = $this->model->reinitialiserDistribution();
            
            if ($success) {
                // Supprimer la simulation de la session si elle existe
                unset($_SESSION['distribution']);
                Flight::redirect('/distribution?reinitialise=1');
            } else {
                throw new Exception("Erreur lors de la réinitialisation");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function recapitulatif() {
        $statistiques = $this->model->getStatistiquesDistribution();
        $besoins = $this->model->getAllBesoinsSinistre();
        $stock = $this->model->getStockBngrc();
        
        Flight::render('distribution/recap', [
            'statistiques' => $statistiques,
            'besoins' => $besoins,
            'stock' => $stock
        ]);
    }
    
    public function apiSimulation() {
        try {
            $distribution = $this->model->simulerDistribution();
            Flight::json([
                'success' => true,
                'distribution' => $distribution,
                'total_alloue' => array_sum(array_column($distribution, 'quantite_allouee'))
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function apiStatistiques() {
        try {
            $statistiques = $this->model->getStatistiquesDistribution();
            Flight::json([
                'success' => true,
                'statistiques' => $statistiques
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
