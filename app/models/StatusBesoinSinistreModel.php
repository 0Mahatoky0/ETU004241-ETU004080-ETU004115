<?php

namespace app\models;

use PDO;

class StatusBesoinSinistreModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour récupérer l'ID d'un statut via son code
    public function getIdByCode($code)
    {
        $sql = "SELECT id FROM status_besoin_sinistre WHERE code = :code";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => $code]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}