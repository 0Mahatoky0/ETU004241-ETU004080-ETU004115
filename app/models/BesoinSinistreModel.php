<?php

namespace app\models;
use PDO;
use Flight;
class BesoinSinistreModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM besoin_sinistre');
        return $stmt->fetchAll();
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

    public function updateStatus($id_besoin_sinistre, $new_status_id) {
        $sql = "UPDATE besoin_sinistre SET id_status_besoin_sinistre = :new_status_id WHERE id = :id_besoin_sinistre";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':new_status_id' => $new_status_id,
            ':id_besoin_sinistre' => $id_besoin_sinistre
        ]);
    }
    

    public function getLePlusAncienBesoinSinistre() {
        $statusSinitreModel = new StatusBesoinSinistreModel(Flight::db());
        $idAccepted = $statusSinitreModel->getIdByCode("ACP")["id"] ?? null;

        if ($idAccepted === null) {
            // Si le code ACP n'existe pas, fallback: prendre le plus ancien par date sans filtre
            $sql = "SELECT * FROM besoin_sinistre ORDER BY `date` ASC LIMIT 1";
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $sql = "SELECT * FROM besoin_sinistre WHERE id_status_besoin_sinistre != :id_accepted ORDER BY `date` ASC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_accepted' => $idAccepted]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
?>
