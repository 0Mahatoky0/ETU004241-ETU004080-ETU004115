<?php

class BesoinController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== SAISIE DES BESOINS PAR VILLE =====
    
    public function saisieBesoin() {
        $regions = $this->model->getAllRegions();
        $categories = $this->model->getAllCategoriesBesoin();
        $status = $this->model->getStatusBesoinSinistre();
        
        Flight::render('besoins/saisie', [
            'regions' => $regions,
            'categories' => $categories,
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
            
            $success = $this->model->createBesoinSinistre($id_region, $id_ville, $id_besoin, $quantite, $id_status);
            
            if ($success) {
                Flight::redirect('/besoins/liste?success=1');
            } else {
                throw new Exception("Erreur lors de l'enregistrement du besoin");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/besoins/saisie?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function listeBesoins() {
        $besoins = $this->model->getAllBesoinsSinistre();
        
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
    
    public function getBesoinsByCategorie() {
        $id_categorie = Flight::request()->query['id_categorie'];
        
        if (empty($id_categorie)) {
            Flight::json(['error' => 'ID catégorie requis'], 400);
            return;
        }
        
        $besoins = $this->model->getBesoinsByCategorie($id_categorie);
        Flight::json($besoins);
    }
}
