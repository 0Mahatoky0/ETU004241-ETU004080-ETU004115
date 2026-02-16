<?php

namespace app\models;

use Flight;

class RegionModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Flight::db();
    }
    
    public function getAllRegions()
    {
        $stmt = $this->db->query("SELECT * FROM region ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    public function getRegionById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM region WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createRegion($libelle)
    {
        $stmt = $this->db->prepare("INSERT INTO region (libelle) VALUES (?)");
        return $stmt->execute([$libelle]);
    }
    
    public function updateRegion($id, $libelle)
    {
        $stmt = $this->db->prepare("UPDATE region SET libelle = ? WHERE id = ?");
        return $stmt->execute([$libelle, $id]);
    }
    
    public function deleteRegion($id)
    {
        $stmt = $this->db->prepare("DELETE FROM region WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
