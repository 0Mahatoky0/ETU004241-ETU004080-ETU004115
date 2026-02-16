<?php

namespace app\controllers;

use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\BesoinSinistreModel;
use Flight;

class BesoinSinistreControleur
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    // Afficher le formulaire d'insertion de Besoin Sinistre
    public function showForm()
    {
        $villeModel = new VilleModel(Flight::db());
        $besoinModel = new BesoinModel(Flight::db());

        $villes = $villeModel->getAllVilles();
        $besoins = $besoinModel->getAllBesoin();

        // Rediriger vers le formulaire avec les données nécessaires
        Flight::render("besoin_sinistre/form", [
            "villes" => $villes,
            "besoins" => $besoins
        ]);
    }

    // Insérer un besoin sinistre à partir des données du formulaire
    public function insertBesoinSinistre()
    {
        $data = Flight::request()->data;

        $id_ville = $data->id_ville;
        $id_besoin = $data->id_besoin;
        $quantite = $data->quantite;

        $besoinSinistreModel = new BesoinSinistreModel(Flight::db());
        $result = $besoinSinistreModel->addBesoinSinistre($id_ville, $id_besoin, $quantite);

        if ($result) {
            Flight::json(["success" => true, "message" => "Besoin sinistre ajouté avec succès"]);
        } else {
            Flight::json(["success" => false, "message" => "Échec de l'ajout du besoin sinistre"], 500);
        }
    }
}