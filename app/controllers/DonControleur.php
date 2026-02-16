<?php

namespace app\controllers;

use app\models\DonModel;
use Flight;

class DonControleur {

    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function showFormDom() {
        $besoinModel = new \app\models\BesoinModel(Flight::db());
        $besoins = $besoinModel->getAllBesoin();

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

        if ($result) {
            Flight::json(["success" => true, "message" => "Don ajouté avec succès"]);
        } else {
            Flight::json(["success" => false, "message" => "Échec de l'ajout du don"], 500);
        }
    }
}
?>