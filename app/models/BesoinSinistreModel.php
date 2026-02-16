<?php

namespace app\models;

class BesoinSinistreModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour insérer un besoin sinistre
    public function insertBesoinSinistre($id_region, $id_ville, $id_besoin, $quantite, $id_status_besoin_sinistre)
    {
        $sql = "INSERT INTO besoin_sinistre (id_region, id_ville, id_besoin, quantite, id_status_besoin_sinistre) 
                VALUES (:id_region, :id_ville, :id_besoin, :quantite, :id_status_besoin_sinistre)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_region' => $id_region,
            ':id_ville' => $id_ville,
            ':id_besoin' => $id_besoin,
            ':quantite' => $quantite,
            ':id_status_besoin_sinistre' => $id_status_besoin_sinistre
        ]);

        return $this->db->lastInsertId();
    }
}