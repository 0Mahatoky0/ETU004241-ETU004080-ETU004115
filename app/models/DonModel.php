<?php

namespace app\models;

use Flight;

class DonModel
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function insertDon($id_besoin, $quantite, $source, $date = null)
    {
        $sql = "INSERT INTO dons (id_besoin, quantite, source, date) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $id_besoin,
            $quantite,
            $source,
            $date ?: date('Y-m-d H:i:s')
        ]);

        return $this->db->lastInsertId();
    }
    
    public function getAllDons()
    {
        $stmt = $this->db->query("
            SELECT d.*, b.libelle as besoin_libelle, cb.libelle as categorie_libelle
            FROM dons d
            JOIN besoin b ON d.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            ORDER BY d.date DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getDonsByBesoin($id_besoin)
    {
        $stmt = $this->db->prepare("
            SELECT d.*, b.libelle as besoin_libelle, cb.libelle as categorie_libelle
            FROM dons d
            JOIN besoin b ON d.id_besoin = b.id
            JOIN categorie_besoin cb ON b.id_categorie = cb.id
            WHERE d.id_besoin = ?
            ORDER BY d.date DESC
        ");
        $stmt->execute([$id_besoin]);
        return $stmt->fetchAll();
    }
}
