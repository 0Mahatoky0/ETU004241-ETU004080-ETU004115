<?php

namespace app\controllers;

use app\models\DonModel;
use app\models\BesoinModel;
use app\models\MvtDonsModel;
use app\models\BesoinSinistreModel;
use app\models\StatusBesoinSinistreModel;

use Flight;

class DonControleur {

    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function showFormDom() {
        $besoinModel = new BesoinModel(Flight::db());
        $besoins = $besoinModel->getAll();

        // Rediriger vers le formulaire
        Flight::render("dons/form", ["besoins" => $besoins]);
    }

    public function insertDon() {
        $data = Flight::request()->data;

        $id_besoin = $data->id_besoin;
        $quantite = $data->quantite;
        $source = $data->source;
        $date = $data->date;

        $donModel = new DonModel(Flight::db());
        $result = $donModel->insertDon($id_besoin, $quantite, $source, $date);

        // Automatiser l'insertion dans mvt_dons
        if ($result) {
            $besoin_sinistre = new BesoinSinistreModel(Flight::db());
            $mvtDonsModel = new MvtDonsModel(Flight::db());
            $statusSinitreModel = new StatusBesoinSinistreModel(Flight::db());
            $besoin_sinistre_ancien = $besoin_sinistre->getLePlusAncienBesoinSinistre()['id'] ?? null;
            if (!$besoin_sinistre_ancien) {
                Flight::json(["success" => false, "message" => "Aucun besoin sinistre disponible"], 404);
                return;
            }

            $idStat = $statusSinitreModel->getIdByCode("ACP")["id"] ?? null;
            // Vérifier le statut actuel pour éviter d'ajouter un mouvement si déjà en ACP
            $current = $besoin_sinistre->getBesoinSinistreById($besoin_sinistre_ancien);
            $currentStatus = $current['id_status_besoin_sinistre'] ?? null;
            if ($currentStatus == $idStat) {
                Flight::json(["success" => false, "message" => "Le besoin sinistre est déjà en statut ACP"], 200);
                return;
            }

            $mvtResult = $mvtDonsModel->create($result, null, $quantite, $besoin_sinistre_ancien, date('Y-m-d H:i:s'));

            if ($mvtResult) {
                $besoin_sinistre->updateStatus($besoin_sinistre_ancien, $idStat); // Mettre à jour le statut du besoin sinistre à "Distribué"
                Flight::json(["success" => true, "message" => "Mouvement de don ajouté avec succès"]);
            } else {
                Flight::json(["success" => false, "message" => "Échec de l'ajout du mouvement de don"], 500);
            }

        } else {
            Flight::json(["success" => false, "message" => "Échec de l'ajout du don"], 500);
        }
    }
 
}
?>
