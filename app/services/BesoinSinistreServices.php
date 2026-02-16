<?php

namespace app\services;

use app\models\BesoinSinistreModel;
use app\models\CategorieBesoinModel;
use app\models\RegionModel;
use app\models\VilleModel;
use app\models\StatusBesoinSinistreModel;
use app\models\BesoinModel;
use app\models\DonsModel;
use app\models\MvtDonsModel;

class BesoinSinistreServices {
    private $besoinSinistreModel;
    private $categorieBesoinModel;
    private $regionModel;
    private $villeModel;
    private $statusBesoinSinistreModel;
    private $besoinModel;
    private $donsModel;
    private $mvtDonsModel;

    public function __construct($db) {
        $this->besoinSinistreModel = new BesoinSinistreModel($db);
        $this->categorieBesoinModel = new CategorieBesoinModel($db);
        $this->regionModel = new RegionModel($db);
        $this->villeModel = new VilleModel($db);
        $this->statusBesoinSinistreModel = new StatusBesoinSinistreModel($db);
        $this->besoinModel = new BesoinModel($db);
        $this->donsModel = new DonsModel($db);
        $this->mvtDonsModel = new MvtDonsModel($db);
    }

    public function getAllBesoinSinistre() {
        $list_besoin = $this->besoinSinistreModel->getAll();

        foreach ($list_besoin as &$besoin) {
            // details must be fetched first because the besoin contains the id_categorie
            $besoin['details'] = $this->besoinModel->getById($besoin['id_besoin']);
            $categorieId = $besoin['details']['id_categorie'] ?? $besoin['id_categorie'] ?? null;
            $besoin['categorie'] = $categorieId ? $this->categorieBesoinModel->getById($categorieId) : null;
            $besoin['region'] = $this->regionModel->getById($besoin['id_region']);
            $besoin['ville'] = $this->villeModel->getById($besoin['id_ville']);
            $besoin['status'] = $this->statusBesoinSinistreModel->getById($besoin['id_status_besoin_sinistre']);
        }
        return $list_besoin;
    }

    public function getNonDistribues() {
        $list_besoin = $this->besoinSinistreModel->getAll();
        $result = [];

        foreach ($list_besoin as $besoin) {
            $statusId = isset($besoin['id_status_besoin_sinistre']) ? (int)$besoin['id_status_besoin_sinistre'] : null;
            if ($statusId !== 2) {
                continue;
            }

            $besoin['details'] = $this->besoinModel->getById($besoin['id_besoin']);
            $categorieId = $besoin['details']['id_categorie'] ?? $besoin['id_categorie'] ?? null;
            $besoin['categorie'] = $categorieId ? $this->categorieBesoinModel->getById($categorieId) : null;
            $besoin['region'] = $this->regionModel->getById($besoin['id_region']);
            $besoin['ville'] = $this->villeModel->getById($besoin['id_ville']);
            $besoin['status'] = $this->statusBesoinSinistreModel->getById($besoin['id_status_besoin_sinistre']);

            $result[] = $besoin;
        }

        return $result;
    }


}

?>