<?php

namespace app\models;

use Flight;

class StatusBesoinModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Flight::db();
    }
    
    public function getAllStatus()
    {
        $stmt = $this->db->query("SELECT * FROM status_besoin_sinistre ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    public function getStatusById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM status_besoin_sinistre WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createStatus($code, $libelle)
    {
        $stmt = $this->db->prepare("INSERT INTO status_besoin_sinistre (code, libelle) VALUES (?, ?)");
        return $stmt->execute([$code, $libelle]);
    }
    
    public function updateStatus($id, $code, $libelle)
    {
        $stmt = $this->db->prepare("UPDATE status_besoin_sinistre SET code = ?, libelle = ? WHERE id = ?");
        return $stmt->execute([$code, $libelle, $id]);
    }
    
    public function deleteStatus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM status_besoin_sinistre WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
