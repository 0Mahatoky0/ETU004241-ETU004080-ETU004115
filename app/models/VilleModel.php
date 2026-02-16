<?php

namespace app\models;

use PDO;
class VilleModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Méthode pour récupérer toutes les villes
    public function getAllVilles()
    {
        $sql = "SELECT * FROM ville";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM ville WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_region, $libelle) {
        $stmt = $this->db->prepare('INSERT INTO ville (id_region, libelle) VALUES (:id_region, :libelle)');
        $stmt->bindParam(':id_region', $id_region, PDO::PARAM_INT);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $id_region, $libelle) {
        $stmt = $this->db->prepare('UPDATE ville SET id_region = :id_region, libelle = :libelle WHERE id = :id');
        $stmt->bindParam(':id_region', $id_region, PDO::PARAM_INT);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM ville WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
?>