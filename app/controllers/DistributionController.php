<?php

namespace app\controllers;

use Exception;
use app\models\BNGRCModel;
use Flight;

// Pas de session_start() ici, c'est déjà fait dans public/index.php

class DistributionController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    public function index() {
        // Vérifier s'il y a une simulation en cours
        $distribution_en_cours = isset($_SESSION['distribution']) ? $_SESSION['distribution'] : null;
        $distribution_proportionnelle_en_cours = isset($_SESSION['distribution_proportionnelle']) ? $_SESSION['distribution_proportionnelle'] : null;
        $distribution_prioritaire_en_cours = isset($_SESSION['distribution_prioritaire']) ? $_SESSION['distribution_prioritaire'] : null;
        
        // Debug temporaire
        error_log("Controller - Distribution en cours: " . ($distribution_en_cours ? "YES" : "NO"));
        error_log("Controller - Distribution proportionnelle: " . ($distribution_proportionnelle_en_cours ? "YES" : "NO"));
        error_log("Controller - Distribution prioritaire: " . ($distribution_prioritaire_en_cours ? "YES" : "NO"));
        error_log("Controller - Prioritaire count: " . ($distribution_prioritaire_en_cours ? count($distribution_prioritaire_en_cours['distribution']) : 'NULL'));
        
        $besoins = $this->model->getBesoinsForDistribution();
        $stock = $this->model->getStockBngrc();
        $statistiques = $this->model->getStatistiquesDistribution();
        
        Flight::render('distribution/index', [
            'besoins' => $besoins,
            'stock' => $stock,
            'statistiques' => $statistiques,
            'distribution_en_cours' => $distribution_en_cours,
            'distribution_proportionnelle_en_cours' => $distribution_proportionnelle_en_cours,
            'distribution_prioritaire_en_cours' => $distribution_prioritaire_en_cours
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
                // Supprimer TOUTES les simulations de la session
                unset($_SESSION['distribution']);
                unset($_SESSION['distribution_proportionnelle']);
                unset($_SESSION['distribution_prioritaire']);
                unset($_SESSION['distribution_type']);
                
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
    
    // ===== MÉTHODES DE DISTRIBUTION PROPORTIONNELLE =====
    
    public function simulerDistributionProportionnelle() {
        try {
            $distribution = $this->model->simulerDistributionProportionnelle();
            
            // Stocker la simulation en session avec un identifiant unique
            $_SESSION['distribution_proportionnelle'] = $distribution;
            $_SESSION['distribution_type'] = 'proportionnelle';
            
            Flight::redirect('/distribution?simulation_proportionnelle=1');
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function validerDistributionProportionnelle() {
        try {
            // Vérifier qu'il y a une simulation en cours
            if (!isset($_SESSION['distribution_proportionnelle'])) {
                throw new Exception("Aucune simulation proportionnelle en cours à valider");
            }
            
            $distribution = $_SESSION['distribution_proportionnelle'];
            $success = $this->model->validerDistributionProportionnelle($distribution);
            
            if ($success) {
                // Supprimer la simulation de la session
                unset($_SESSION['distribution_proportionnelle']);
                unset($_SESSION['distribution_type']);
                Flight::redirect('/distribution?valide_proportionnelle=1');
            } else {
                throw new Exception("Erreur lors de la validation de la distribution proportionnelle");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function apiSimulationProportionnelle() {
        try {
            // Désactiver l'affichage de Tracy pour les appels API
            header('Content-Type: application/json');
            
            $distribution = $this->model->simulerDistributionProportionnelle();
            echo json_encode([
                'success' => true,
                'distribution' => $distribution,
                'total_alloue' => array_sum(array_column($distribution['distribution'], 'quantite_allouee'))
            ]);
            exit;
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }
    
    // ===== MÉTHODES DE DISTRIBUTION PRIORITAIRE =====
    
    public function simulerDistributionPrioritaire() {
        try {
            $distribution = $this->model->simulerDistributionPrioritaire();
            
            // Stocker la simulation en session
            $_SESSION['distribution_prioritaire'] = $distribution;
            $_SESSION['distribution_type'] = 'prioritaire';
            
            Flight::redirect('/distribution?simulation_prioritaire=1');
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function validerDistributionPrioritaire() {
        try {
            // Vérifier qu'il y a une simulation en cours
            if (!isset($_SESSION['distribution_prioritaire'])) {
                throw new Exception("Aucune simulation prioritaire en cours à valider");
            }
            
            $distribution = $_SESSION['distribution_prioritaire'];
            $success = $this->model->validerDistributionPrioritaire($distribution);
            
            if ($success) {
                // Supprimer la simulation de la session
                unset($_SESSION['distribution_prioritaire']);
                unset($_SESSION['distribution_type']);
                Flight::redirect('/distribution?valide_prioritaire=1');
            } else {
                throw new Exception("Erreur lors de la validation de la distribution prioritaire");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/distribution?error=' . urlencode($e->getMessage()));
        }
    }
}
