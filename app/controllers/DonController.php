<?php

class DonController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== SAISIE DES DONS =====
    
    public function saisieDon() {
        $categories = $this->model->getAllCategoriesBesoin();
        
        Flight::render('dons/saisie', [
            'categories' => $categories
        ]);
    }
    
    public function storeDon() {
        $data = Flight::request()->data;
        
        try {
            $id_besoin = $data['id_besoin'];
            $quantite = $data['quantite'];
            $source = $data['source'];
            
            if (empty($id_besoin) || empty($quantite) || empty($source)) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            if ($quantite <= 0) {
                throw new Exception("La quantité doit être supérieure à 0");
            }
            
            $don_id = $this->model->createDon($id_besoin, $quantite, $source);
            
            if ($don_id) {
                // Créer le mouvement d'entrée automatiquement
                $this->model->createMouvementDon($don_id, $quantite, 0);
                
                Flight::redirect('/dons/liste?success=1');
            } else {
                throw new Exception("Erreur lors de l'enregistrement du don");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/dons/saisie?error=' . urlencode($e->getMessage()));
        }
    }
    
    public function listeDons() {
        $dons = $this->model->getAllDons();
        
        Flight::render('dons/liste', [
            'dons' => $dons
        ]);
    }
    
    // ===== GESTION FINANCIÈRE - ACHAT AVEC DONS EN ARGENT =====
    
    public function achatAvecDons() {
        $categories = $this->model->getAllCategoriesBesoin();
        $stock_disponible = $this->model->getStockDisponible();
        
        Flight::render('dons/achat', [
            'categories' => $categories,
            'stock_disponible' => $stock_disponible
        ]);
    }
    
    public function processAchat() {
        $data = Flight::request()->data;
        
        try {
            $id_besoin = $data['id_besoin'];
            $quantite = $data['quantite'];
            $montant_disponible = $data['montant_disponible'];
            
            if (empty($id_besoin) || empty($quantite) || empty($montant_disponible)) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            if ($quantite <= 0) {
                throw new Exception("La quantité doit être supérieure à 0");
            }
            
            // Récupérer le prix unitaire du besoin
            $besoins = $this->model->getAllBesoins();
            $besoin_courant = null;
            foreach ($besoins as $besoin) {
                if ($besoin['id'] == $id_besoin) {
                    $besoin_courant = $besoin;
                    break;
                }
            }
            
            if (!$besoin_courant) {
                throw new Exception("Besoin non trouvé");
            }
            
            $cout_total = $quantite * $besoin_courant['prix_unitaire'];
            
            if ($cout_total > $montant_disponible) {
                throw new Exception("Montant insuffisant. Coût total: " . number_format($cout_total, 2) . " MGA");
            }
            
            // Créer le don (achat)
            $don_id = $this->model->createDon($id_besoin, $quantite, "Achat avec dons en argent");
            
            if ($don_id) {
                // Créer le mouvement d'entrée
                $this->model->createMouvementDon($don_id, $quantite, 0);
                
                Flight::redirect('/dons/liste?success=achat');
            } else {
                throw new Exception("Erreur lors de l'achat");
            }
            
        } catch (Exception $e) {
            Flight::redirect('/dons/achat?error=' . urlencode($e->getMessage()));
        }
    }
    
    // ===== API POUR AJAX =====
    
    public function getBesoinsByCategorie() {
        $id_categorie = Flight::request()->query['id_categorie'];
        
        if (empty($id_categorie)) {
            Flight::json(['error' => 'ID catégorie requis'], 400);
            return;
        }
        
        $besoins = $this->model->getBesoinsByCategorie($id_categorie);
        Flight::json($besoins);
    }
    
    public function getStockDisponible() {
        $stock = $this->model->getStockDisponible();
        Flight::json($stock);
    }
}
