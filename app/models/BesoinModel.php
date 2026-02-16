<?php

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function insertBesoin($id_categorie, $libelle, $prix_unitaire)
    {
        $sql = "INSERT INTO besoin (id_categorie, libelle, prix_unitaire) 
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $id_categorie,
            $libelle,
            $prix_unitaire
        ]);

        return $this->db->lastInsertId();
    }

    public function getAllBesoin()
    {
        $sql = "SELECT b.*, cb.libelle as categorie_libelle 
                FROM besoin b 
                JOIN categorie_besoin cb ON b.id_categorie = cb.id 
                ORDER BY cb.libelle, b.libelle";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
    
    public function getBesoinById($id)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, cb.libelle as categorie_libelle 
            FROM besoin b 
            JOIN categorie_besoin cb ON b.id_categorie = cb.id 
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getBesoinsByCategorie($id_categorie)
    {
        $stmt = $this->db->prepare("SELECT * FROM besoin WHERE id_categorie = ? ORDER BY libelle");
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll();
    }
    
    public function updateBesoin($id, $id_categorie, $libelle, $prix_unitaire)
    {
        $stmt = $this->db->prepare("UPDATE besoin SET id_categorie = ?, libelle = ?, prix_unitaire = ? WHERE id = ?");
        return $stmt->execute([$id_categorie, $libelle, $prix_unitaire, $id]);
    }
    
    public function deleteBesoin($id)
    {
        $stmt = $this->db->prepare("DELETE FROM besoin WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM besoin WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_categorie, $libelle, $prix_unitaire) {
        $stmt = $this->db->prepare('INSERT INTO besoin (id_categorie, libelle, prix_unitaire) VALUES (:id_categorie, :libelle, :prix_unitaire)');
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $id_categorie, $libelle, $prix_unitaire) {
        $stmt = $this->db->prepare('UPDATE besoin SET id_categorie = :id_categorie, libelle = :libelle, prix_unitaire = :prix_unitaire WHERE id = :id');
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM besoin WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}

?>
