<?php

namespace app\models;

class DonModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insertDon($id_besoin, $quantite, $source, $date)
    {
        $sql = "INSERT INTO dons (id_besoin, quantite, source, date) 
                VALUES (:id_besoin, :quantite, :source, :date)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_besoin' => $id_besoin,
            ':quantite' => $quantite,
            ':source' => $source,
            ':date' => $date
        ]);

        return $this->db->lastInsertId();
    }
}
