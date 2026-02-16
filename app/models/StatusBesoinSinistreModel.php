<?php

namespace app\models;

use PDO;

class StatusBesoinSinistreModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM status_besoin_sinistre');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM status_besoin_sinistre WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($code, $libelle) {
        $stmt = $this->db->prepare('INSERT INTO status_besoin_sinistre (code, libelle) VALUES (:code, :libelle)');
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $code, $libelle) {
        $stmt = $this->db->prepare('UPDATE status_besoin_sinistre SET code = :code, libelle = :libelle WHERE id = :id');
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM status_besoin_sinistre WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
?>
