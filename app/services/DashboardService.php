<?php

namespace app\services;

use app\models\BNGRCModel;
use app\models\StatusBesoinSinistreModel;
use Flight;

class DashboardService {
    private $model;

    public function __construct() {
        $this->model = new BNGRCModel();
    }

    public function getDashboardStats() {
        $besoins = $this->model->getAllBesoinsSinistre();
        $dons = $this->model->getAllDons();

        // total besoins
        $total_besoins = count($besoins);
        $quantite_besoins = 0;
        foreach ($besoins as $b) {
            $quantite_besoins += (int)($b['quantite'] ?? 0);
        }

        // total dons
        $total_dons = count($dons);
        $quantite_dons = 0;
        foreach ($dons as $d) {
            $quantite_dons += (int)($d['quantite'] ?? 0);
        }

        // stock disponible (somme des mvt: entrer - sortie)
        $stock_rows = $this->getStockDisponible();
        $stock_total = 0;
        foreach ($stock_rows as $s) {
            $stock_total += (int)($s['stock_disponible'] ?? 0);
        }

        // satisfaction
        $satisfaits = 0;
        $non_satisfaits = 0;
        $total = $total_besoins;
        $besoins_status = $this->getBesoinsNonSatisfaits();

        // note: getBesoinsNonSatisfaits returns only non satisfaits, so compute complementary
        $non_satisfaits = count($besoins_status);
        $satisfaits = max(0, $total - $non_satisfaits);

        return [
            'besoins' => ['total' => $total_besoins, 'quantite_totale' => $quantite_besoins],
            'dons' => ['total' => $total_dons, 'quantite_totale' => $quantite_dons],
            'stock' => $stock_total,
            'satisfaction' => ['satisfaits' => $satisfaits, 'non_satisfaits' => $non_satisfaits, 'total' => $total]
        ];
    }

    public function getDashboardByVille() {
        $villes = $this->model->getAllVilles();
        $besoins = $this->model->getAllBesoinsSinistre();
        $mouvements = $this->model->getAllMouvementsDons();
        $dons = $this->model->getAllDons();

        // récupérer l'id du status ACP (code 'ACP') via le modèle de status
        $statusModel = new StatusBesoinSinistreModel(Flight::db());
        $idACProw = $statusModel->getIdByCode('ACP');
        $idACP = $idACProw['id'] ?? null;

        // index mouvements by id_besoin_sinistre for quick sums
        $alloc_by_besoin_sinistre = [];
        foreach ($mouvements as $m) {
            $id_bs = $m['id_besoin_sinistre'] ?? null;
            if (!$id_bs) continue;
            $alloc_by_besoin_sinistre[$id_bs][] = $m;
        }

        // map besoin_sinistre by id for quick lookup of ville
        $bsById = [];
        foreach ($besoins as $b) {
            $bsById[$b['id']] = $b;
        }

        $result = [];
        foreach ($villes as $v) {
            $vid = $v['id'];
            $nombre_besoins = 0;
            $quantite_totale_besoins = 0;
            $quantite_allouee = 0;
            $quantite_assignee = 0; // somme des quantites des besoins dont status == ACP
            $quantite_dons_recus = 0; // somme des quantites depuis la table `dons` pour cette ville
            $dons_distinct = [];

            foreach ($besoins as $b) {
                if ((int)$b['id_ville'] !== (int)$vid) continue;
                $nombre_besoins++;
                $quantite_totale_besoins += (int)($b['quantite'] ?? 0);

                // si le besoin est en statut ACP, l'ajouter à la quantite_assignee
                if ($idACP !== null && isset($b['id_status_besoin_sinistre']) && (int)$b['id_status_besoin_sinistre'] === (int)$idACP) {
                    $quantite_assignee += (int)($b['quantite'] ?? 0);
                }

                $bs_id = $b['id'];
                if (isset($alloc_by_besoin_sinistre[$bs_id])) {
                    foreach ($alloc_by_besoin_sinistre[$bs_id] as $m) {
                        $quantite_allouee += (int)($m['sortie'] ?? 0);
                        if (!empty($m['id_dons'])) $dons_distinct[$m['id_dons']] = true;
                    }
                }
            }

            // calculer la quantité totale des dons reçus pour la ville en croisant les dons
            // et les besoins déclarés pour cette ville (plus robuste que la requête SQL précédente)
            // construire la liste des id_besoin demandés pour cette ville
            $besoinsIdsPourVille = [];
            foreach ($besoins as $b) {
                if ((int)$b['id_ville'] === (int)$vid) {
                    $besoinsIdsPourVille[] = (int)$b['id_besoin'];
                }
            }

            if (!empty($besoinsIdsPourVille)) {
                foreach ($dons as $don) {
                    if (in_array((int)$don['id_besoin'], $besoinsIdsPourVille, true)) {
                        $quantite_dons_recus += (int)($don['quantite'] ?? 0);
                        if (!empty($don['id'])) $dons_distinct[$don['id']] = true;
                    }
                }
            }

            $result[] = [
                'ville_id' => $vid,
                'ville_libelle' => $v['libelle'] ?? null,
                'nombre_besoins' => $nombre_besoins,
                'nombre_dons' => count($dons_distinct),
                // remplacer la quantite totale par la quantite de dons recueillis pour la ville
                'quantite_totale_besoins' => $quantite_dons_recus,
                'quantite_allouee' => $quantite_allouee,
                'quantite_assignee' => $quantite_assignee,
                'quantite_restante' => $quantite_dons_recus - $quantite_assignee
            ];
        }

        return $result;
    }

    public function getStockDisponible() {
        $dons = $this->model->getAllDons();
        $mouvements = $this->model->getAllMouvementsDons();
        $besoins = $this->model->getAllBesoins();

        $donsById = [];
        foreach ($dons as $d) $donsById[$d['id']] = $d;
        $besoinsById = [];
        foreach ($besoins as $b) $besoinsById[$b['id']] = $b;

        // compute stock per don
        $stockByDon = [];
        foreach ($mouvements as $m) {
            $id_don = $m['id_dons'] ?? null;
            if (!$id_don) continue;
            if (!isset($stockByDon[$id_don])) $stockByDon[$id_don] = ['entrer' => 0, 'sortie' => 0];
            $stockByDon[$id_don]['entrer'] += (int)($m['entrer'] ?? 0);
            $stockByDon[$id_don]['sortie'] += (int)($m['sortie'] ?? 0);
        }

        // aggregate by besoin
        $stockByBesoin = [];
        foreach ($dons as $don) {
            $bid = $don['id_besoin'];
            $donId = $don['id'];
            $entrer = $stockByDon[$donId]['entrer'] ?? 0;
            $sortie = $stockByDon[$donId]['sortie'] ?? 0;
            $stock = $entrer - $sortie;
            if ($stock <= 0) continue;
            if (!isset($stockByBesoin[$bid])) {
                $b = $besoinsById[$bid] ?? ['libelle' => null, 'prix_unitaire' => null, 'categorie_libelle' => null];
                $stockByBesoin[$bid] = [
                    'besoin_id' => $bid,
                    'besoin_libelle' => $b['libelle'] ?? null,
                    'categorie_libelle' => $b['categorie_libelle'] ?? null,
                    'prix_unitaire' => $b['prix_unitaire'] ?? null,
                    'stock_disponible' => 0
                ];
            }
            $stockByBesoin[$bid]['stock_disponible'] += $stock;
        }

        // sort by categorie_libelle then besoin_libelle
        $rows = array_values($stockByBesoin);
        usort($rows, function($a, $b) {
            $ca = $a['categorie_libelle'] ?? '';
            $cb = $b['categorie_libelle'] ?? '';
            if ($ca === $cb) return strcmp($a['besoin_libelle'] ?? '', $b['besoin_libelle'] ?? '');
            return strcmp($ca, $cb);
        });

        return $rows;
    }

    public function getBesoinsNonSatisfaits() {
        $besoins = $this->model->getAllBesoinsSinistre();
        $mouvements = $this->model->getAllMouvementsDons();

        $sortiesByBs = [];
        foreach ($mouvements as $m) {
            $bs = $m['id_besoin_sinistre'] ?? null;
            if (!$bs) continue;
            $sortiesByBs[$bs] = ($sortiesByBs[$bs] ?? 0) + (int)($m['sortie'] ?? 0);
        }

        $result = [];
        foreach ($besoins as $b) {
            $assigned = $sortiesByBs[$b['id']] ?? 0;
            $restante = (int)($b['quantite'] ?? 0) - $assigned;
            if ($restante > 0) {
                $result[] = [
                    'id' => $b['id'],
                    'quantite_requise' => (int)$b['quantite'],
                    'quantite_assignee' => $assigned,
                    'quantite_restante' => $restante,
                    'besoin_libelle' => $b['besoin_libelle'] ?? null,
                    'prix_unitaire' => $b['prix_unitaire'] ?? null,
                    'categorie_libelle' => $b['categorie_libelle'] ?? null,
                    'ville_libelle' => $b['ville_libelle'] ?? null,
                    'status_libelle' => $b['status_libelle'] ?? null
                ];
            }
        }

        return $result;
    }

}
