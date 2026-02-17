<?php

namespace app\models;

use Flight;
use Exception;
use PDO;

class BNGRCModel {
    private $db;
    
    public function __construct() {
        $this->db = Flight::db();
    }
    
    // ===== GESTION DES VILLES =====
    
    public function getAllRegions() {
        $stmt = $this->db->query("SELECT * FROM region ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    public function getVillesByRegion($id_region) {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_region = ? ORDER BY libelle");
        $stmt->execute([$id_region]);
        return $stmt->fetchAll();
    }
    
    public function getAllVilles() {
        $stmt = $this->db->query("
            SELECT v.*, r.libelle as region_libelle 
            FROM ville v 
            JOIN region r ON v.id_region = r.id 
            ORDER BY r.libelle, v.libelle
        ");
        return $stmt->fetchAll();
    }
    
    // ===== GESTION DES BESOINS =====
    
    public function getAllCategoriesBesoin() {
        $stmt = $this->db->query("SELECT * FROM categorie_besoin ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    public function getBesoinsByCategorie($id_categorie) {
        $stmt = $this->db->prepare("SELECT * FROM besoin WHERE id_categorie = ? ORDER BY libelle");
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll();
    }
    
    public function getAllBesoins() {
        $stmt = $this->db->query("
            SELECT b.*, cb.libelle as categorie_libelle 
            FROM besoin b 
            JOIN categorie_besoin cb ON b.id_categorie = cb.id 
            ORDER BY cb.libelle, b.libelle
        ");
        return $stmt->fetchAll();
    }
    
    public function getStatusBesoinSinistre() {
        $stmt = $this->db->query("SELECT * FROM status_besoin_sinistre ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    // ===== SAISIE DES BESOINS PAR VILLE =====
    
    public function createBesoinSinistre($id_ville, $id_besoin, $quantite, $id_status = 2) {
        $stmt = $this->db->prepare("
            INSERT INTO besoin_sinistre (id_ville, id_besoin, quantite, quantite_initiale, quantite_restante, id_status_besoin_sinistre) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_ville, $id_besoin, $quantite, $quantite, $quantite, $id_status]);
    }
    
    public function getBesoinsSinistreByVille($id_ville) {
        $stmt = $this->db->prepare("
            SELECT bs.*, bs.quantite as quantite_requise, b.libelle as besoin_libelle, b.prix_unitaire, 
                   cb.libelle as categorie_libelle, sbs.libelle as status_libelle,
                   v.libelle as ville_libelle, r.libelle as region_libelle
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN status_besoin_sinistre sbs ON bs.id_status_besoin_sinistre = sbs.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            WHERE bs.id_ville = ?
            ORDER BY cb.libelle, b.libelle
        ");
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll();
    }
    
    public function getAllBesoinsSinistre() {
        $stmt = $this->db->query("
            SELECT bs.*, bs.quantite as quantite_requise, b.libelle as besoin_libelle, b.prix_unitaire, 
                   cb.libelle as categorie_libelle, sbs.libelle as status_libelle,
                   v.libelle as ville_libelle, r.libelle as region_libelle
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN status_besoin_sinistre sbs ON bs.id_status_besoin_sinistre = sbs.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            ORDER BY r.libelle, v.libelle, cb.libelle, b.libelle
        ");
        return $stmt->fetchAll();
    }
    
    // ===== GESTION DES DONS =====
    
    public function createDon($id_besoin, $quantite = null, $source = null, $montant = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO dons (id_besoin, quantite, source, date, montant) VALUES (?, ?, ?, NOW(), ?)"
        );
        $stmt->execute([$id_besoin, $quantite, $source, $montant]);
        return $this->db->lastInsertId();
    }

    public function getBesoinById($id_besoin) {
        $stmt = $this->db->prepare("SELECT * FROM besoin WHERE id = ?");
        $stmt->execute([$id_besoin]);
        return $stmt->fetch();
    }

    public function getOrCreateDonArgentBesoin($id_categorie = 3) {
        // Cherche un besoin spécifique 'Don en argent' pour cette catégorie
        $stmt = $this->db->prepare("SELECT * FROM besoin WHERE id_categorie = ? AND libelle = 'Don en argent' LIMIT 1");
        $stmt->execute([$id_categorie]);
        $row = $stmt->fetch();
        if ($row) {
            return $row['id'];
        }

        // Créer un besoin placeholder pour les dons en argent
        $insert = $this->db->prepare("INSERT INTO besoin (id_categorie, libelle, prix_unitaire) VALUES (?, 'Don en argent', 0)");
        $insert->execute([$id_categorie]);
        return $this->db->lastInsertId();
    }
    
    public function getAllDons() {
        $stmt = $this->db->query("
            SELECT d.*, b.libelle as besoin_libelle, b.prix_unitaire, cb.libelle as categorie_libelle
            FROM dons d
            JOIN besoin b ON d.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            ORDER BY d.date DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retourne la somme des montants disponibles pour une catégorie de besoin.
     * Si la colonne `montant` existe dans `dons`, on somme cette colonne.
     * Sinon on retombe sur la colonne `quantite` (compatibilité avec schéma existant).
     */
    public function getTotalMontantByCategorie($id_categorie = 3) {
        // Vérifier si la colonne `montant` existe
        $stmt = $this->db->query("SHOW COLUMNS FROM dons LIKE 'montant'");
        $hasMontant = $stmt->rowCount() > 0;

        if ($hasMontant) {
            $sql = "SELECT COALESCE(SUM(d.montant), 0) as total FROM dons d JOIN besoin b ON d.id_besoin = b.id WHERE b.id_categorie = ?";
        } else {
            // fallback: sommer la colonne quantite
            $sql = "SELECT COALESCE(SUM(d.quantite), 0) as total FROM dons d JOIN besoin b ON d.id_besoin = b.id WHERE b.id_categorie = ?";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_categorie]);
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getBesoinsRestantsPourAchat($id_ville = null) {
        $sql = "
            SELECT 
                bs.id as besoin_sinistre_id,
                bs.id_besoin,
                bs.id_ville,
                bs.quantite as quantite_requise,
                COALESCE(SUM(md.sortie), 0) as quantite_assignee,
                bs.quantite - COALESCE(SUM(md.sortie), 0) as quantite_restante,
                b.libelle as besoin_libelle,
                b.prix_unitaire,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                r.libelle as region_libelle,
                sbs.libelle as status_libelle
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            JOIN status_besoin_sinistre sbs ON bs.id_status_besoin_sinistre = sbs.id
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
        ";
        
        if ($id_ville) {
            $sql .= " WHERE bs.id_ville = ?";
        }
        
        $sql .= "
            GROUP BY bs.id, bs.id_besoin, bs.id_ville, bs.quantite, b.libelle, b.prix_unitaire, cb.libelle, v.libelle, r.libelle, sbs.libelle
            HAVING quantite_restante > 0
            ORDER BY r.libelle, v.libelle, cb.libelle, b.libelle
        ";
        
        $stmt = $this->db->prepare($sql);
        if ($id_ville) {
            $stmt->execute([$id_ville]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getAllAchats($id_ville = null, $date_debut = null, $date_fin = null, $id_categorie = null) {
        $sql = "
            SELECT 
                a.*,
                b.libelle as besoin_libelle,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                r.libelle as region_libelle
            FROM achat a
            JOIN besoin b ON a.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON a.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($id_ville) {
            $sql .= " AND a.id_ville = ?";
            $params[] = $id_ville;
        }
        
        if ($date_debut) {
            $sql .= " AND a.date_achat >= ?";
            $params[] = $date_debut;
        }
        
        if ($date_fin) {
            $sql .= " AND a.date_achat <= ?";
            $params[] = $date_fin;
        }
        
        if ($id_categorie) {
            $sql .= " AND b.id_categorie = ?";
            $params[] = $id_categorie;
        }
        
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function simulerAchat($quantite, $prix_unitaire) {
        $config = $this->getConfiguration();
        $frais_percent = $config['frais_achat_percent'];
        
        $montant_brut = $quantite * $prix_unitaire;
        $montant_frais = $montant_brut * $frais_percent / 100;
        $montant_total = $montant_brut + $montant_frais;
        
        return [
            'montant_brut' => $montant_brut,
            'montant_frais' => $montant_frais,
            'montant_total' => $montant_total,
            'frais_percent' => $frais_percent
        ];
    }

    public function validerAchat($id_besoin_sinistre, $quantite, $id_besoin, $id_ville, $prix_unitaire) {
        $this->db->beginTransaction();
        
        try {
            // Récupérer la configuration des frais
            $config = $this->getConfiguration();
            $frais_percent = $config['frais_achat_percent'];
            
            // Calculer les montants
            $montant_brut = $quantite * $prix_unitaire;
            $montant_frais = $montant_brut * $frais_percent / 100;
            $montant_total = $montant_brut + $montant_frais;
            
            // Créer l'achat
            $this->createAchat($id_besoin, $id_ville, $quantite, $prix_unitaire, $frais_percent, $montant_brut, $montant_frais, $montant_total);
            
            // Mettre à jour la quantité restante dans besoin_sinistre
            $stmt = $this->db->prepare("
                UPDATE besoin_sinistre 
                SET quantite = quantite - ? 
                WHERE id = ? AND quantite >= ?
            ");
            $result = $stmt->execute([$quantite, $id_besoin_sinistre, $quantite]);
            
            if (!$result || $stmt->rowCount() === 0) {
                throw new Exception("Quantité insuffisante ou besoin non trouvé");
            }
            
            $this->db->commit();
            return [
                'success' => true,
                'montant_brut' => $montant_brut,
                'montant_frais' => $montant_frais,
                'montant_total' => $montant_total
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getConfiguration() {
        $stmt = $this->db->query("SELECT * FROM configuration ORDER BY id DESC LIMIT 1");
        return $stmt->fetch();
    }

    public function updateFraisAchat($frais_percent) {
        $stmt = $this->db->prepare("UPDATE configuration SET frais_achat_percent = ?, updated_at = CURRENT_TIMESTAMP");
        return $stmt->execute([$frais_percent]);
    }

    public function createAchat($id_besoin, $id_ville, $quantite, $prix_unitaire, $frais_percent, $montant_brut, $montant_frais, $montant_total) {
        $stmt = $this->db->prepare("
            INSERT INTO achat (id_besoin, id_ville, quantite, prix_unitaire, frais_percent, montant_brut, montant_frais, montant_total, date_achat) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        return $stmt->execute([$id_besoin, $id_ville, $quantite, $prix_unitaire, $frais_percent, $montant_brut, $montant_frais, $montant_total]);
    }

    public function getRecapitulatifBesoins() {
        $stmt = $this->db->query("
            SELECT 
                -- Besoins totaux
                SUM(bs.quantite * b.prix_unitaire) as montant_total_besoins,
                COUNT(DISTINCT bs.id) as nombre_total_besoins,
                SUM(bs.quantite) as quantite_totale_besoins,
                
                -- Besoins satisfaits (quantité allouée)
                SUM(COALESCE(md.sortie, 0) * b.prix_unitaire) as montant_satisfait,
                COUNT(DISTINCT CASE WHEN COALESCE(SUM(md.sortie), 0) > 0 THEN bs.id END) as nombre_besoins_satisfaits,
                SUM(COALESCE(md.sortie, 0)) as quantite_satisfaite,
                
                -- Besoins restants
                SUM((bs.quantite - COALESCE(md.sortie, 0)) * b.prix_unitaire) as montant_restant,
                COUNT(DISTINCT CASE WHEN (bs.quantite - COALESCE(md.sortie, 0)) > 0 THEN bs.id END) as nombre_besoins_restants,
                SUM(bs.quantite - COALESCE(md.sortie, 0)) as quantite_restante,
                
                -- Taux de satisfaction
                ROUND(
                    (SUM(COALESCE(md.sortie, 0) * b.prix_unitaire) / SUM(bs.quantite * b.prix_unitaire)) * 100, 
                    2
                ) as taux_satisfaction_montant,
                
                ROUND(
                    (SUM(COALESCE(md.sortie, 0)) / SUM(bs.quantite)) * 100, 
                    2
                ) as taux_satisfaction_quantite
                
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
            WHERE bs.quantite > 0
        ");
        
        $result = $stmt->fetch();
        
        // Calculer les pourcentages si nécessaire
        if ($result['montant_total_besoins'] > 0) {
            $result['pourcentage_montant_satisfait'] = round(($result['montant_satisfait'] / $result['montant_total_besoins']) * 100, 2);
            $result['pourcentage_montant_restant'] = round(($result['montant_restant'] / $result['montant_total_besoins']) * 100, 2);
        } else {
            $result['pourcentage_montant_satisfait'] = 0;
            $result['pourcentage_montant_restant'] = 0;
        }
        
        if ($result['quantite_totale_besoins'] > 0) {
            $result['pourcentage_quantite_satisfaite'] = round(($result['quantite_satisfaite'] / $result['quantite_totale_besoins']) * 100, 2);
            $result['pourcentage_quantite_restante'] = round(($result['quantite_restante'] / $result['quantite_totale_besoins']) * 100, 2);
        } else {
            $result['pourcentage_quantite_satisfaite'] = 0;
            $result['pourcentage_quantite_restante'] = 0;
        }
        
        return $result;
    }


    public function getRecapitulatifParRegion() {
        $stmt = $this->db->query("
            SELECT 
                r.libelle as region_libelle,
                r.id as region_id,
                
                -- Besoins totaux par région
                SUM(bs.quantite * b.prix_unitaire) as montant_total_besoins,
                COUNT(DISTINCT bs.id) as nombre_total_besoins,
                SUM(bs.quantite) as quantite_totale_besoins,
                
                -- Besoins satisfaits par région
                SUM(COALESCE(md.sortie, 0) * b.prix_unitaire) as montant_satisfait,
                SUM(COALESCE(md.sortie, 0)) as quantite_satisfaite,
                
                -- Besoins restants par région
                SUM((bs.quantite - COALESCE(md.sortie, 0)) * b.prix_unitaire) as montant_restant,
                SUM(bs.quantite - COALESCE(md.sortie, 0)) as quantite_restante,
                
                -- Taux de satisfaction
                ROUND(
                    (SUM(COALESCE(md.sortie, 0) * b.prix_unitaire) / NULLIF(SUM(bs.quantite * b.prix_unitaire), 0)) * 100, 
                    2
                ) as taux_satisfaction_montant
                
            FROM region r
            LEFT JOIN ville v ON r.id = v.id_region
            LEFT JOIN besoin_sinistre bs ON v.id = bs.id_ville
            LEFT JOIN besoin b ON bs.id_besoin = b.id
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
            WHERE bs.quantite > 0 OR bs.id IS NULL
            GROUP BY r.id, r.libelle
            ORDER BY r.libelle
        ");
        
        return $stmt->fetchAll();
    }
    
    
    public function getDonsByBesoin($id_besoin) {
        $stmt = $this->db->prepare("
            SELECT d.*, b.libelle as besoin_libelle, cb.libelle as categorie_libelle
            FROM dons d
            JOIN besoin b ON d.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            WHERE d.id_besoin = ?
            ORDER BY d.date DESC
        ");
        $stmt->execute([$id_besoin]);
        return $stmt->fetchAll();
    }
    
    // ===== MOUVEMENTS DE DONS =====
    
    public function createMouvementDon($id_dons, $entrer, $sortie, $id_besoin_sinistre = null) {
        $stmt = $this->db->prepare("\n            INSERT INTO mvt_dons (id_dons, entrer, sortie, id_besoin_sinistre, date) \n            VALUES (?, ?, ?, ?, NOW())\n        ");
        return $stmt->execute([$id_dons, $entrer, $sortie, $id_besoin_sinistre]);
    }
    
    public function getAllMouvementsDons() {
        $stmt = $this->db->query("
            SELECT md.*, d.source as don_source, b.libelle as besoin_libelle,
                   bs.quantite as besoin_quantite, v.libelle as ville_libelle
            FROM mvt_dons md
            JOIN dons d ON md.id_dons = d.id
            JOIN besoin b ON d.id_besoin = b.id
                LEFT JOIN besoin_sinistre bs ON md.id_besoin_sinistre = bs.id
            LEFT JOIN ville v ON bs.id_ville = v.id
            ORDER BY md.date DESC
        ");
        return $stmt->fetchAll();
    }
    
    // ===== SIMULATION DE DISPATCH =====
    
    public function getStockDisponible() {
        $stmt = $this->db->query("
            SELECT 
                b.id as besoin_id,
                b.libelle as besoin_libelle,
                cb.libelle as categorie_libelle,
                b.prix_unitaire,
                COALESCE(SUM(md.entrer), 0) - COALESCE(SUM(md.sortie), 0) as stock_disponible
            FROM besoin b
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            LEFT JOIN dons d ON b.id = d.id_besoin
            LEFT JOIN mvt_dons md ON d.id = md.id_dons
            GROUP BY b.id, b.libelle, cb.libelle, b.prix_unitaire
            HAVING stock_disponible > 0
            ORDER BY cb.libelle, b.libelle
        ");
        return $stmt->fetchAll();
    }
    
    public function getBesoinsNonSatisfaits() {
        $stmt = $this->db->query("
            SELECT 
                bs.id,
                bs.quantite as quantite_requise,
                COALESCE(SUM(md.sortie), 0) as quantite_assignee,
                bs.quantite - COALESCE(SUM(md.sortie), 0) as quantite_restante,
                b.libelle as besoin_libelle,
                b.prix_unitaire,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                sbs.libelle as status_libelle
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN status_besoin_sinistre sbs ON bs.id_status_besoin_sinistre = sbs.id
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
            GROUP BY bs.id, bs.quantite, b.libelle, b.prix_unitaire, cb.libelle, v.libelle, sbs.libelle
            HAVING quantite_restante > 0
            ORDER BY v.libelle, cb.libelle, b.libelle
        ");
        return $stmt->fetchAll();
    }
    
    public function simulerDispatch($id_besoin_sinistre, $quantite) {
        $this->db->beginTransaction();
        
        try {
            // Récupérer le besoin
            $stmt = $this->db->prepare("SELECT * FROM besoin_sinistre WHERE id = ?");
            $stmt->execute([$id_besoin_sinistre]);
            $besoin = $stmt->fetch();
            
            if (!$besoin) {
                throw new Exception("Besoin non trouvé");
            }
            
            // Récupérer les dons disponibles pour ce besoin
            $stmt = $this->db->prepare("
                SELECT d.*, COALESCE(SUM(md.entrer), 0) - COALESCE(SUM(md.sortie), 0) as stock_disponible
                FROM dons d
                LEFT JOIN mvt_dons md ON d.id = md.id_dons
                WHERE d.id_besoin = ?
                GROUP BY d.id
                HAVING stock_disponible > 0
                ORDER BY d.date ASC
            ");
            $stmt->execute([$besoin['id_besoin']]);
            $dons_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $quantite_a_allouer = $quantite;
            
            foreach ($dons_disponibles as $don) {
                if ($quantite_a_allouer <= 0) break;
                
                $quantite_pouvant_allouer = min($quantite_a_allouer, $don['stock_disponible']);
                
                // Créer le mouvement de sortie
                $this->createMouvementDon($don['id'], 0, $quantite_pouvant_allouer, $id_besoin_sinistre);
                
                $quantite_a_allouer -= $quantite_pouvant_allouer;
            }
            
            if ($quantite_a_allouer > 0) {
                throw new Exception("Stock insuffisant pour allouer la quantité demandée");
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    // ===== TABLEAU DE BORD =====
    
    public function getDashboardStats() {
        $stats = [];
        
        // Total des besoins
        $stmt = $this->db->query("SELECT COUNT(*) as total, SUM(quantite) as quantite_totale FROM besoin_sinistre");
        $stats['besoins'] = $stmt->fetch();
        
        // Total des dons
        $stmt = $this->db->query("SELECT COUNT(*) as total, SUM(quantite) as quantite_totale FROM dons");
        $stats['dons'] = $stmt->fetch();
        
        // Stock disponible
        $stmt = $this->db->query("
            SELECT SUM(COALESCE(md.entrer, 0) - COALESCE(md.sortie, 0)) as stock_total
            FROM mvt_dons md
        ");
        $stock_result = $stmt->fetch();
        $stats['stock'] = $stock_result['stock_total'] ?? 0;
        
        // Besoins satisfaits vs restants
        $stmt = $this->db->query("
            SELECT 
                SUM(CASE WHEN quantite_restante = 0 THEN 1 ELSE 0 END) as satisfaits,
                SUM(CASE WHEN quantite_restante > 0 THEN 1 ELSE 0 END) as non_satisfaits,
                COUNT(*) as total
            FROM (
                SELECT 
                    bs.quantite - COALESCE(SUM(md.sortie), 0) as quantite_restante
                FROM besoin_sinistre bs
                LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
                GROUP BY bs.id, bs.quantite
            ) as besoins_status
        ");
        $stats['satisfaction'] = $stmt->fetch();
        
        return $stats;
    }
    
    public function getDashboardByRegion() {
        $stmt = $this->db->query("
            SELECT 
                r.libelle as region_libelle,
                COUNT(DISTINCT bs.id) as nombre_besoins,
                SUM(bs.quantite) as quantite_totale_besoins,
                COALESCE(SUM(md.sortie), 0) as quantite_allouee,
                SUM(bs.quantite) - COALESCE(SUM(md.sortie), 0) as quantite_restante
            FROM region r
            LEFT JOIN ville v ON r.id = v.id_region
            LEFT JOIN besoin_sinistre bs ON v.id = bs.id_ville
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
            GROUP BY r.id, r.libelle
            ORDER BY r.libelle
        ");
        return $stmt->fetchAll();
    }

    public function getDashboardByVille() {
        $sql = <<<'SQL'
            SELECT 
                v.id as ville_id,
                v.libelle as ville_libelle,
                COUNT(DISTINCT bs.id) as nombre_besoins,
                COALESCE(COUNT(DISTINCT md.id_dons), 0) as nombre_dons,
                SUM(bs.quantite) as quantite_totale_besoins,
                COALESCE(SUM(md.sortie), 0) as quantite_allouee,
                SUM(bs.quantite) - COALESCE(SUM(md.sortie), 0) as quantite_restante
            FROM ville v
            LEFT JOIN besoin_sinistre bs ON v.id = bs.id_ville
            LEFT JOIN mvt_dons md ON bs.id = md.id_besoin_sinistre
            GROUP BY v.id, v.libelle
            ORDER BY v.libelle
        SQL;

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getDonsByVille($id_ville) {
        $stmt = $this->db->prepare("
            SELECT d.*, b.libelle as besoin_libelle, cb.libelle as categorie_libelle, bs.id_ville
            FROM dons d
            JOIN besoin b ON d.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN besoin_sinistre bs ON b.id = bs.id_besoin
            WHERE bs.id_ville = ?
            ORDER BY d.date DESC
        ");
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll();
    }
    
    // ===== FONCTIONNALITÉS DE DISTRIBUTION =====
    
    public function getStockBngrc() {
        $stmt = $this->db->query("
            SELECT sb.*, b.libelle as besoin_libelle, cb.libelle as categorie_libelle, b.prix_unitaire
            FROM stock_bngrc sb
            JOIN besoin b ON sb.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            ORDER BY cb.libelle, b.libelle
        ");
        return $stmt->fetchAll();
    }
    
    public function getBesoinsForDistribution() {
        $stmt = $this->db->query("
            SELECT 
                bs.id,
                bs.quantite_initiale,
                bs.quantite_restante,
                b.libelle as besoin_libelle,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                r.libelle as region_libelle,
                b.prix_unitaire,
                bs.date
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            WHERE bs.quantite_restante > 0
            ORDER BY bs.date ASC, bs.quantite_restante DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function simulerDistribution() {
        $besoins = $this->getBesoinsForDistribution();
        $stock = $this->getStockBngrc();
        
        $distribution = [];
        $stock_disponible = [];
        
        // Organiser le stock disponible par besoin
        foreach ($stock as $item) {
            $stock_disponible[$item['id_besoin']] = $item['quantite'];
        }
        
        // Simuler la distribution
        foreach ($besoins as $besoin) {
            $id_besoin = $besoin['id'];
            $id_besoin_type = $this->getBesoinTypeById($id_besoin);
            $quantite_necessaire = $besoin['quantite_restante'];
            $quantite_disponible = $stock_disponible[$id_besoin_type] ?? 0;
            
            $quantite_allouee = min($quantite_necessaire, $quantite_disponible);
            
            if ($quantite_allouee > 0) {
                $distribution[] = [
                    'id_besoin_sinistre' => $id_besoin,
                    'id_besoin_type' => $id_besoin_type,
                    'besoin_libelle' => $besoin['besoin_libelle'],
                    'ville_libelle' => $besoin['ville_libelle'],
                    'quantite_requise' => $quantite_necessaire,
                    'quantite_allouee' => $quantite_allouee,
                    'quantite_restante_apres' => $quantite_necessaire - $quantite_allouee,
                    'stock_restant_apres' => $quantite_disponible - $quantite_allouee
                ];
                
                // Mettre à jour le stock disponible pour la simulation
                $stock_disponible[$id_besoin_type] -= $quantite_allouee;
            }
        }
        
        return $distribution;
    }
    
    private function getBesoinTypeById($id_besoin_sinistre) {
        $stmt = $this->db->prepare("SELECT id_besoin FROM besoin_sinistre WHERE id = ?");
        $stmt->execute([$id_besoin_sinistre]);
        $result = $stmt->fetch();
        return $result['id_besoin'] ?? null;
    }
    
    public function validerDistribution($distribution) {
        $this->db->beginTransaction();
        
        try {
            foreach ($distribution as $item) {
                $id_besoin_sinistre = $item['id_besoin_sinistre'];
                $quantite_allouee = $item['quantite_allouee'];
                $id_besoin_type = $item['id_besoin_type'];
                
                // Mettre à jour la quantité restante dans besoin_sinistre
                $stmt = $this->db->prepare("
                    UPDATE besoin_sinistre 
                    SET quantite_restante = quantite_restante - ? 
                    WHERE id = ? AND quantite_restante >= ?
                ");
                $stmt->execute([$quantite_allouee, $id_besoin_sinistre, $quantite_allouee]);
                
                // Mettre à jour le stock dans stock_bngrc
                $stmt = $this->db->prepare("
                    UPDATE stock_bngrc 
                    SET quantite = quantite - ? 
                    WHERE id_besoin = ? AND quantite >= ?
                ");
                $stmt->execute([$quantite_allouee, $id_besoin_type, $quantite_allouee]);
                
                // Créer un mouvement de don pour le suivi
                $this->createMouvementDonForDistribution($id_besoin_sinistre, $quantite_allouee);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function createMouvementDonForDistribution($id_besoin_sinistre, $quantite) {
        // Récupérer le don associé pour le mouvement
        $stmt = $this->db->prepare("
            SELECT d.id 
            FROM dons d 
            JOIN besoin_sinistre bs ON d.id_besoin = bs.id_besoin 
            WHERE bs.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id_besoin_sinistre]);
        $don = $stmt->fetch();
        
        if ($don) {
            $this->createMouvementDon($don['id'], 0, $quantite, $id_besoin_sinistre);
        }
    }
    
    public function reinitialiserDistribution() {
        $this->db->beginTransaction();
        
        try {
            // Réinitialiser besoin_sinistre
            $stmt = $this->db->prepare("
                UPDATE besoin_sinistre 
                SET quantite_restante = quantite_initiale
            ");
            $stmt->execute();
            
            // Réinitialiser stock_bngrc
            $stmt = $this->db->prepare("
                UPDATE stock_bngrc 
                SET quantite = quantite_initiale
            ");
            $stmt->execute();
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getStatistiquesDistribution() {
        $stats = [];
        
        // Besoins totaux
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as nombre_total,
                SUM(quantite_initiale) as quantite_totale_initiale,
                SUM(quantite_restante) as quantite_totale_restante,
                SUM(quantite_initiale - quantite_restante) as quantite_totale_satisfaite
            FROM besoin_sinistre
        ");
        $stats['besoins'] = $stmt->fetch();
        
        // Stock total
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as nombre_types,
                SUM(quantite_initiale) as quantite_totale_initiale,
                SUM(quantite) as quantite_totale_restante,
                SUM(quantite_initiale - quantite) as quantite_totale_distribuee
            FROM stock_bngrc
        ");
        $stats['stock'] = $stmt->fetch();
        
        // Taux de satisfaction
        $besoins = $stats['besoins'];
        $taux_satisfaction = $besoins['quantite_totale_initiale'] > 0 
            ? (($besoins['quantite_totale_satisfaite'] / $besoins['quantite_totale_initiale']) * 100) 
            : 0;
        $stats['taux_satisfaction'] = round($taux_satisfaction, 2);
        
        return $stats;
    }
    
    // ===== FONCTIONNALITÉS DE DISTRIBUTION PROPORTIONNELLE =====
    
    public function getTotalBesoins() {
        $stmt = $this->db->query("SELECT SUM(quantite_restante) AS total_besoins FROM besoin_sinistre WHERE quantite_restante > 0");
        $result = $stmt->fetch();
        return $result['total_besoins'] ?? 0;
    }
    
    public function getBesoinsTriesPourDistribution() {
        $stmt = $this->db->query("
            SELECT 
                bs.id,
                bs.quantite_initiale,
                bs.quantite_restante,
                b.libelle as besoin_libelle,
                cb.libelle as categorie_libelle,
                v.libelle as ville_libelle,
                r.libelle as region_libelle,
                b.prix_unitaire,
                bs.date
            FROM besoin_sinistre bs
            JOIN besoin b ON bs.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            JOIN ville v ON bs.id_ville = v.id
            JOIN region r ON v.id_region = r.id
            WHERE bs.quantite_restante > 0
            ORDER BY bs.quantite_restante ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function getStockTotalDisponible() {
        $stmt = $this->db->query("SELECT SUM(quantite) AS stock_total FROM stock_bngrc WHERE quantite > 0");
        $result = $stmt->fetch();
        return $result['stock_total'] ?? 0;
    }
    
    public function simulerDistributionProportionnelle() {
        $besoins = $this->getBesoinsTriesPourDistribution();
        $total_besoins = $this->getTotalBesoins();
        $stock_total = $this->getStockTotalDisponible();
        
        $distribution = [];
        $stock_distribue = 0;
        
        // Calcul proportionnel pour chaque besoin
        foreach ($besoins as $besoin) {
            if ($total_besoins > 0 && $stock_total > 0 && $stock_distribue < $stock_total) {
                // Formule: Part = (besoin / total_besoins) × stock
                $part_calculee = ($besoin['quantite_restante'] / $total_besoins) * $stock_total;
                
                // Prendre seulement la partie entière (floor)
                $part_finale = floor($part_calculee);
                
                // Vérifier que la part ne dépasse pas le besoin réel
                $part_finale = min($part_finale, $besoin['quantite_restante']);
                
                // Vérifier qu'on ne dépasse pas le stock disponible
                $part_finale = min($part_finale, $stock_total - $stock_distribue);
                
                if ($part_finale > 0) {
                    $distribution[] = [
                        'id_besoin_sinistre' => $besoin['id'],
                        'id_besoin_type' => $this->getBesoinTypeById($besoin['id']),
                        'besoin_libelle' => $besoin['besoin_libelle'],
                        'ville_libelle' => $besoin['ville_libelle'],
                        'quantite_requise' => $besoin['quantite_restante'],
                        'part_calculee' => round($part_calculee, 2),
                        'quantite_allouee' => $part_finale,
                        'quantite_restante_apres' => $besoin['quantite_restante'] - $part_finale,
                        'pourcentage' => round(($besoin['quantite_restante'] / $total_besoins) * 100, 2)
                    ];
                    
                    $stock_distribue += $part_finale;
                }
            }
        }
        
        return [
            'distribution' => $distribution,
            'total_besoins' => $total_besoins,
            'stock_total' => $stock_total,
            'stock_distribue' => $stock_distribue,
            'stock_restant' => $stock_total - $stock_distribue,
            'besoins_satisfaits' => count(array_filter($distribution, fn($d) => $d['quantite_restante_apres'] == 0))
        ];
    }
    
    public function validerDistributionProportionnelle($distribution_data) {
        $this->db->beginTransaction();
        
        try {
            $total_distribue = 0;
            
            foreach ($distribution_data['distribution'] as $item) {
                $id_besoin_sinistre = $item['id_besoin_sinistre'];
                $quantite_allouee = $item['quantite_allouee'];
                
                // Mettre à jour la quantité restante dans besoin_sinistre
                $stmt = $this->db->prepare("
                    UPDATE besoin_sinistre 
                    SET quantite_restante = quantite_restante - ? 
                    WHERE id = ? AND quantite_restante >= ?
                ");
                $stmt->execute([$quantite_allouee, $id_besoin_sinistre, $quantite_allouee]);
                
                $total_distribue += $quantite_allouee;
            }
            
            // Mettre à jour le stock global (déduction totale)
            // On répartit la déduction sur les types de besoins proportionnellement
            $stmt = $this->db->prepare("
                UPDATE stock_bngrc 
                SET quantite = GREATEST(0, quantite - ?)
            ");
            $stmt->execute([$total_distribue]);
            
            // Créer un mouvement de don global pour le suivi
            $this->createMouvementDonGlobal($total_distribue);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function createMouvementDonGlobal($quantite) {
        // Créer un mouvement de don global pour suivre la distribution proportionnelle
        $stmt = $this->db->prepare("
            INSERT INTO mvt_dons (id_dons, entrer, sortie, id_besoin_sinistre, date) 
            VALUES (1, 0, ?, NOW())
        ");
        return $stmt->execute([$quantite]);
    }
}