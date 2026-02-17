<?php

namespace app\controllers;

use app\models\BNGRCModel;
use Exception;
use Flight;

class DonController {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // ===== SAISIE DES DONS =====
    
    public function saisieDon() {
        $categories = $this->model->getAllCategoriesBesoin();
        
        Flight::render('dons/form', [
            'categories' => $categories,
            'besoins' => []
        ]);
    }
    
    public function storeDon() {
        $data = Flight::request()->data;
        
        try {
            $id_categorie = isset($data['id_categorie']) ? $data['id_categorie'] : null;
            $id_besoin = isset($data['id_besoin']) ? $data['id_besoin'] : null;
            $quantite = isset($data['quantite']) ? $data['quantite'] : null;
            $source = isset($data['source']) ? $data['source'] : null;
            $montant = isset($data['montant']) ? $data['montant'] : null;

            if (empty($id_categorie) || empty($source)) {
                throw new Exception("La catégorie et la source sont obligatoires");
            }

            // Si catégorie 'argent' (id=3) : besoin n'est pas requis
            if ((int)$id_categorie === 3) {
                if ($montant === null || $montant === '' || $montant <= 0) {
                    throw new Exception("Le montant est requis pour les dons en argent");
                }

                // Obtenir ou créer un besoin placeholder pour les dons en argent
                if (empty($id_besoin)) {
                    $id_besoin = $this->model->getOrCreateDonArgentBesoin(3);
                }

                $don_id = $this->model->createDon($id_besoin, null, $source, $montant);
                if ($don_id) {
                    Flight::redirect('/dashboard');
                } else {
                    throw new Exception("Erreur lors de l'enregistrement du don en argent");
                }
            } else {
                // Don matériel classique
                if (empty($id_besoin)) {
                    throw new Exception("Le besoin est requis pour ce type de don");
                }

                if ($quantite === null || $quantite === '' ) {
                    throw new Exception("La quantité est requise pour ce type de don");
                }

                if ($quantite <= 0) {
                    throw new Exception("La quantité doit être supérieure à 0");
                }

                $don_id = $this->model->createDon($id_besoin, $quantite, $source, null);
                if ($don_id) {
                    // Créer le mouvement d'entrée automatiquement
                    $this->model->createMouvementDon($don_id, $quantite, 0);
                    Flight::redirect('/dashboard');
                } else {
                    throw new Exception("Erreur lors de l'enregistrement du don");
                }
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
    
    public function achatAvecDons($id_besoin) {
        $categories = $this->model->getAllCategoriesBesoin();
        $stock_disponible = $this->model->getStockDisponible();
        // Récupérer le montant total disponible pour la catégorie 'argent' (id=3)
        $montant_disponible_total = $this->model->getTotalMontantByCategorie(3);

        Flight::render('dons/achat', [
            'categories' => $categories,
            'stock_disponible' => $stock_disponible,
            'id_besoin' => $id_besoin,
            'montant_disponible_total' => $montant_disponible_total
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

    // API: total montant des dons pour une catégorie (par défaut catégorie id=3 "argent")
    public function apiMontantByCategorie($id_categorie = null) {
        // Accept either route param or query param
        $id = $id_categorie ?: Flight::request()->query['id_categorie'] ?? 3;
        $total = $this->model->getTotalMontantByCategorie($id);
        Flight::json(['total' => (float) $total]);
    }
}
