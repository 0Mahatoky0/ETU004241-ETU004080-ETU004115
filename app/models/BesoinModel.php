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

    // Méthode pour insérer un besoin
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

    // Méthode pour récupérer tous les besoins
    public function getAllBesoin()
    {
        $sql = "SELECT * FROM besoin";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un besoin par son ID
    public function getBesoinById($id)
    {
        $sql = "SELECT * FROM besoin WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}