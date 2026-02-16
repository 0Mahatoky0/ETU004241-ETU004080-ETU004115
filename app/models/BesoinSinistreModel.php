<?php

namespace app\models;

class BesoinSinistreModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM besoin_sinistre');
        return $stmt->fetchAll();
    }

    

}
?>