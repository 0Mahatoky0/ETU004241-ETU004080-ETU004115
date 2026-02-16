<?php

namespace app\models;

use PDO;

class VilleModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour récupérer toutes les villes
    public function getAllVilles()
    {
        $sql = "SELECT * FROM ville";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}