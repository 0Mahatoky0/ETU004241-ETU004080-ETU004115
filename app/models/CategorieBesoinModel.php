<?php

namespace app\models;

use Flight;

class CategorieBesoinModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Flight::db();
    }
    
    public function getAllCategories()
    {
        $stmt = $this->db->query("SELECT * FROM categorie_besoin ORDER BY libelle");
        return $stmt->fetchAll();
    }
    
    public function getCategorieById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categorie_besoin WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createCategorie($code, $libelle)
    {
        $stmt = $this->db->prepare("INSERT INTO categorie_besoin (code, libelle) VALUES (?, ?)");
        return $stmt->execute([$code, $libelle]);
    }
    
    public function updateCategorie($id, $code, $libelle)
    {
        $stmt = $this->db->prepare("UPDATE categorie_besoin SET code = ?, libelle = ? WHERE id = ?");
        return $stmt->execute([$code, $libelle, $id]);
    }
    
    public function deleteCategorie($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categorie_besoin WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
