<?php

namespace app\models;

use PDO;

class BesoinModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insertBesoin($id_categorie, $libelle, $prix_unitaire)
    {
        $sql = "INSERT INTO besoin (id_categorie, libelle, prix_unitaire) 
                VALUES (:id_categorie, :libelle, :prix_unitaire)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_categorie' => $id_categorie,
            ':libelle' => $libelle,
            ':prix_unitaire' => $prix_unitaire
        ]);

        return $this->db->lastInsertId();
    }

    public function getAllBesoin()
    {
        $sql = "SELECT * FROM besoin";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}