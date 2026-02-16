<?php

namespace app\models;

use Flight;

class VilleModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Flight::db();
    }
    
    public function getAllVilles()
    {
        $stmt = $this->db->query("
            SELECT v.*, r.libelle as region_libelle 
            FROM ville v 
            JOIN region r ON v.id_region = r.id 
            ORDER BY r.libelle, v.libelle
        ");
        return $stmt->fetchAll();
    }
    
    public function getVillesByRegion($id_region)
    {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_region = ? ORDER BY libelle");
        $stmt->execute([$id_region]);
        return $stmt->fetchAll();
    }
    
    public function getVilleById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createVille($id_region, $libelle)
    {
        $stmt = $this->db->prepare("INSERT INTO ville (id_region, libelle) VALUES (?, ?)");
        return $stmt->execute([$id_region, $libelle]);
    }
    
    public function updateVille($id, $id_region, $libelle)
    {
        $stmt = $this->db->prepare("UPDATE ville SET id_region = ?, libelle = ? WHERE id = ?");
        return $stmt->execute([$id_region, $libelle, $id]);
    }
    
    public function deleteVille($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ville WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
