<?php

namespace app\controllers;

use app\models\BNGRCModel;
use Flight;

class DonControleur {
    private $model;
    
    public function __construct() {
        $this->model = new BNGRCModel();
    }
    
    // Ce fichier est un duplicata de DonController.php
    // Utilisez DonController.php à la place
    public function showFormDom() {
        $categories = $this->model->getAllCategoriesBesoin();
        Flight::render("dons/form", ["categories" => $categories]);
    }
}
