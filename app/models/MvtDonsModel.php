<?php

namespace app\models;

use PDO;

class MvtDonsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM mvt_dons');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM mvt_dons WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_dons, $entrer, $sortie, $id_besoin_ville, $date) {
        $stmt = $this->db->prepare('INSERT INTO mvt_dons (id_dons, entrer, sortie, id_besoin_ville, date) VALUES (:id_dons, :entrer, :sortie, :id_besoin_ville, :date)');
        $stmt->bindParam(':id_dons', $id_dons, PDO::PARAM_INT);
        $stmt->bindParam(':entrer', $entrer, PDO::PARAM_INT);
        $stmt->bindParam(':sortie', $sortie, PDO::PARAM_INT);
        $stmt->bindParam(':id_besoin_ville', $id_besoin_ville, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $id_dons, $entrer, $sortie, $id_besoin_ville, $date) {
        $stmt = $this->db->prepare('UPDATE mvt_dons SET id_dons = :id_dons, entrer = :entrer, sortie = :sortie, id_besoin_ville = :id_besoin_ville, date = :date WHERE id = :id');
        $stmt->bindParam(':id_dons', $id_dons, PDO::PARAM_INT);
        $stmt->bindParam(':entrer', $entrer, PDO::PARAM_INT);
        $stmt->bindParam(':sortie', $sortie, PDO::PARAM_INT);
        $stmt->bindParam(':id_besoin_ville', $id_besoin_ville, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM mvt_dons WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
?>
