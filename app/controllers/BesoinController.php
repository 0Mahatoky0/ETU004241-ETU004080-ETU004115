<?php

namespace app\controllers;

use Exception;
use app\models\BNGRCModel;
use Flight;

class BesoinController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== SAISIE DES BESOINS PAR VILLE =====
    
    public function saisieBesoin() {
        // $regions = $this->model->getAllRegions();
        $villes = $this->model->getAllVilles();
        $categories = $this->model->getAllCategoriesBesoin();
        $status = $this->model->getStatusBesoinSinistre();
        
        Flight::render('besoins/form', [
            'villes' => $villes,
            'besoins' => $categories,
            'status' => $status
        ]);
    }
    
    public function storeBesoin() {
        $data = Flight::request()->data;
        
        try {
            $id_region = $data['id_region'];
            $id_ville = $data['id_ville'];
            $id_besoin = $data['id_besoin'];
            $quantite = $data['quantite'];
            $id_status = $data['id_status_besoin_sinistre'] ?? 2; // En attente par défaut
            
            if (empty($id_region) || empty($id_ville) || empty($id_besoin) || empty($quantite)) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            if ($quantite <= 0) {
                throw new Exception("La quantité doit être supérieure à 0");
            }
            
            // the besoin_sinistre table does not store region directly; pass ville, besoin, quantite, status
            $success = $this->model->createBesoinSinistre($id_ville, $id_besoin, $quantite, $id_status);
            
            if ($success) {
                Flight::redirect(BASE_URL . '/besoins/liste?success=1');
            } else {
                throw new Exception("Erreur lors de l'enregistrement du besoin");
            }
            
        } catch (Exception $e) {
            Flight::redirect(BASE_URL . '/besoins/saisie?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function listeBesoins() {
        // Afficher la liste générale des besoins (catalogue), pas les besoins sinistre par ville
        $besoins = $this->model->getAllBesoins();

        Flight::render('besoins/liste', [
            'besoins' => $besoins
        ]);
    }
    
    public function besoinsParVille($id_ville) {
        $ville_info = null;
        $villes = $this->model->getAllVilles();
        foreach ($villes as $v) {
            if ($v['id'] == $id_ville) {
                $ville_info = $v;
                break;
            }
        }
        
        $besoins = $this->model->getBesoinsSinistreByVille($id_ville);
        
        Flight::render('besoins/par_ville', [
            'ville' => $ville_info,
            'besoins' => $besoins
        ]);
    }

    public function detailsVille($id_ville) {
        $ville_info = null;
        $villes = $this->model->getAllVilles();
        foreach ($villes as $v) {
            if ($v['id'] == $id_ville) {
                $ville_info = $v;
                break;
            }
        }

        $besoins = $this->model->getBesoinsSinistreByVille($id_ville);
        // Retourner la liste complète des dons (ne pas filtrer par ville)
        $dons = $this->model->getAllDons();

        Flight::render('besoins/details', [
            'ville' => $ville_info,
            'besoins' => $besoins,
            'dons' => $dons
        ]);
    }
    
    // ===== API POUR AJAX =====
    
    public function getVillesByRegion() {
        $id_region = Flight::request()->query['id_region'];
        
        if (empty($id_region)) {
            Flight::json(['error' => 'ID région requis'], 400);
            return;
        }
        
        $villes = $this->model->getVillesByRegion($id_region);
        Flight::json($villes);
    }
    
    public function getBesoinsByCategorie($id_categorie = null) {
        // Accept id from route parameter or query string
        if ($id_categorie === null) {
            $id_categorie = Flight::request()->query['id_categorie'] ?? null;
        }

        if (empty($id_categorie)) {
            Flight::json(['error' => 'ID catégorie requis'], 400);
            return;
        }

        $besoins = $this->model->getBesoinsByCategorie($id_categorie);
        Flight::json($besoins);
    }
}
