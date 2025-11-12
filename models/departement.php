<?php
// ---- Modèle Departement ----
class Departement {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer tous les départements
    public function getDepartements() {
        $query = "SELECT * FROM departements ORDER BY nomd ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Récupérer un département par ID
    public function getDepartementById($idd) {
        $query = "SELECT * FROM departements WHERE idd = :idd";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idd', $idd);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un département
    public function createDepartement($nomd) {
        $query = "INSERT INTO departements (nomd) VALUES (:nomd)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nomd', $nomd);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Mettre à jour un département
    public function updateDepartement($idd, $nomd) {
        $query = "UPDATE departements SET nomd = :nomd WHERE idd = :idd";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idd', $idd);
        $stmt->bindParam(':nomd', $nomd);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Supprimer un département
    public function deleteDepartement($idd) {
        $query = "DELETE FROM departements WHERE idd = :idd";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idd', $idd);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Compter le nombre d'employés par département
    public function countEmployesParDepartement() {
        $query = "SELECT d.idd, d.nomd, COUNT(e.ide) as nombre 
                  FROM departements d
                  LEFT JOIN employes e ON d.idd = e.idd
                  GROUP BY d.idd, d.nomd
                  ORDER BY nombre DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}