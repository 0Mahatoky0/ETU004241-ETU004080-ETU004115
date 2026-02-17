<?php

namespace app\controllers;

use app\models\BNGRCModel;
use Exception;
use Flight;

class AchatController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== PAGE LISTE DES BESOINS RESTANTS POUR ACHAT =====
    
    public function listeBesoinsRestants() {
        $villes = $this->model->getAllVilles();
        $categories = $this->model->getAllCategoriesBesoin();
        
        $id_ville = Flight::request()->query['id_ville'] ?? null;
        $besoins = $this->model->getBesoinsRestantsPourAchat($id_ville);
        
        Flight::render('achats/besoins_restants', [
            'villes' => $villes,
            'categories' => $categories,
            'besoins' => $besoins,
            'id_ville_selected' => $id_ville
        ]);
    }
    
    // ===== PAGE SIMULATION =====
    
    public function simulationAchat() {
        $id_besoin_sinistre = Flight::request()->query['id'] ?? null;
        
        if (!$id_besoin_sinistre) {
            Flight::redirect(BASE_URL . '/achats/besoins-restants?error=' . urlencode('Besoin non spécifié'));
            return;
        }
        
        // Récupérer les informations du besoin
        $besoins = $this->model->getBesoinsRestantsPourAchat();
        $besoin = null;
        
        foreach ($besoins as $b) {
            if ($b['besoin_sinistre_id'] == $id_besoin_sinistre) {
                $besoin = $b;
                break;
            }
        }
        
        if (!$besoin) {
            Flight::redirect(BASE_URL . '/achats/besoins-restants?error=' . urlencode('Besoin non trouvé ou déjà couvert'));
            return;
        }
        
        Flight::render('achats/simulation', [
            'besoin' => $besoin
        ]);
    }
    
    // ===== API POUR SIMULATION =====
    
    public function apiSimulerAchat() {
        $data = Flight::request()->data;
        
        try {
            $quantite = $data['quantite'] ?? 0;
            $prix_unitaire = $data['prix_unitaire'] ?? 0;
            
            if ($quantite <= 0 || $prix_unitaire <= 0) {
                throw new Exception("La quantité et le prix unitaire doivent être supérieurs à 0");
            }
            
            $simulation = $this->model->simulerAchat($quantite, $prix_unitaire);
            
            Flight::json([
                'success' => true,
                'simulation' => $simulation
            ]);
            
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    // ===== VALIDATION ACHAT =====
    
    public function validerAchat() {
        $data = Flight::request()->data;
        
        try {
            $id_besoin_sinistre = $data['id_besoin_sinistre'] ?? 0;
            $id_besoin = $data['id_besoin'] ?? 0;
            $id_ville = $data['id_ville'] ?? 0;
            $quantite = $data['quantite'] ?? 0;
            $prix_unitaire = $data['prix_unitaire'] ?? 0;
            
            if (empty($id_besoin_sinistre) || empty($id_besoin) || empty($id_ville) || empty($quantite) || empty($prix_unitaire)) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            if ($quantite <= 0 || $prix_unitaire <= 0) {
                throw new Exception("La quantité et le prix unitaire doivent être supérieurs à 0");
            }
            
            // Vérifier que le besoin existe encore
            $besoins = $this->model->getBesoinsRestantsPourAchat();
            $besoin_trouve = false;
            $quantite_restante = 0;
            
            foreach ($besoins as $b) {
                if ($b['besoin_sinistre_id'] == $id_besoin_sinistre) {
                    $besoin_trouve = true;
                    $quantite_restante = $b['quantite_restante'];
                    break;
                }
            }
            
            if (!$besoin_trouve) {
                throw new Exception("Ce besoin n'existe plus dans les dons restants");
            }
            
            if ($quantite > $quantite_restante) {
                throw new Exception("Quantité demandée (" . $quantite . ") supérieure à la quantité restante (" . $quantite_restante . ")");
            }
            
            $resultat = $this->model->validerAchat($id_besoin_sinistre, $quantite, $id_besoin, $id_ville, $prix_unitaire);
            
            Flight::redirect(BASE_URL . '/achats/liste?success=' . urlencode('Achat enregistré avec succès - Montant total: ' . number_format($resultat['montant_total'], 2, ',', ' ') . ' Ar'));
            
        } catch (Exception $e) {
            Flight::redirect(BASE_URL . '/achats/simulation?id=' . $id_besoin_sinistre . '&error=' . urlencode($e->getMessage()));
        }
    }
    
    // ===== PAGE LISTE DES ACHATS =====
    
    public function listeAchats() {
        $villes = $this->model->getAllVilles();
        $categories = $this->model->getAllCategoriesBesoin();
        
        $id_ville = Flight::request()->query['id_ville'] ?? null;
        $date_debut = Flight::request()->query['date_debut'] ?? null;
        $date_fin = Flight::request()->query['date_fin'] ?? null;
        $id_categorie = Flight::request()->query['id_categorie'] ?? null;
        
        $achats = $this->model->getAllAchats($id_ville, $date_debut, $date_fin, $id_categorie);
        
        Flight::render('achats/liste', [
            'villes' => $villes,
            'categories' => $categories,
            'achats' => $achats,
            'id_ville_selected' => $id_ville,
            'date_debut_selected' => $date_debut,
            'date_fin_selected' => $date_fin,
            'id_categorie_selected' => $id_categorie
        ]);
    }
    
    // ===== CONFIGURATION DES FRAIS =====
    
    public function configuration() {
        $config = $this->model->getConfiguration();
        
        if (Flight::request()->method === 'POST') {
            try {
                $frais_percent = Flight::request()->data['frais_achat_percent'] ?? 0;
                
                if ($frais_percent < 0 || $frais_percent > 100) {
                    throw new Exception("Le pourcentage de frais doit être entre 0 et 100");
                }
                
                $this->model->updateFraisAchat($frais_percent);
                
                Flight::redirect(BASE_URL . '/achats/configuration?success=' . urlencode('Configuration mise à jour avec succès'));
                
            } catch (Exception $e) {
                Flight::render('achats/configuration', [
                    'config' => $config,
                    'error' => $e->getMessage()
                ]);
                return;
            }
        }
        
        Flight::render('achats/configuration', [
            'config' => $config
        ]);
    }
    
    // ===== PAGE RÉCAPITULATIVE =====
    
    public function recapitulatif() {
        $recapitulatif = $this->model->getRecapitulatifBesoins();
        $recapitulatif_par_region = $this->model->getRecapitulatifParRegion();
        
        Flight::render('achats/recapitulatif', [
            'recapitulatif' => $recapitulatif,
            'recapitulatif_par_region' => $recapitulatif_par_region
        ]);
    }
    
    // ===== API POUR RÉCAPITULATIF AJAX =====
    
    public function apiRecapitulatif() {
        try {
            $recapitulatif = $this->model->getRecapitulatifBesoins();
            $recapitulatif_par_region = $this->model->getRecapitulatifParRegion();
            
            Flight::json([
                'success' => true,
                'recapitulatif' => $recapitulatif,
                'recapitulatif_par_region' => $recapitulatif_par_region,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
