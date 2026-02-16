<?php

namespace app\models;

use Flight;
use PDO;

class BesoinSinistreModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addBesoinSinistre($id_ville, $id_besoin, $quantite) {
        $statusSinitreModel = new StatusBesoinSinistreModel(Flight::db());
        $idStat = $statusSinitreModel->getIdByCode("ATT")["id"];
        return $this->insertBesoinSinistre($id_ville, $id_besoin, $quantite,$idStat);
    }

    // Méthode pour insérer un besoin sinistre
    public function insertBesoinSinistre($id_ville, $id_besoin, $quantite, $id_status_besoin_sinistre)
    {
        $sql = "INSERT INTO besoin_sinistre (id_ville, id_besoin, quantite, id_status_besoin_sinistre) 
                VALUES (:id_ville, :id_besoin, :quantite, :id_status_besoin_sinistre)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_ville' => $id_ville,
            ':id_besoin' => $id_besoin,
            ':quantite' => $quantite,
            ':id_status_besoin_sinistre' => $id_status_besoin_sinistre
        ]);

        return $this->db->lastInsertId();
    }

    // Méthode pour récupérer tous les besoins sinistres
    public function getAllBesoinSinistre()
    {
        $sql = "SELECT * FROM besoin_sinistre";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un besoin sinistre par son ID
    public function getBesoinSinistreById($id)
    {
        $sql = "SELECT * FROM besoin_sinistre WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}