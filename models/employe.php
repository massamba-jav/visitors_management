<?php
// ---- Modèle Employe ----
class Employe {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer tous les employés
    public function getEmployes() {
        $query = "SELECT e.*, d.nomd as departement 
                  FROM employes e 
                  LEFT JOIN departements d ON e.idd = d.idd 
                  ORDER BY e.nom ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Récupérer un employé par ID
    public function getEmployeById($ide) {
        $query = "SELECT * FROM employes WHERE ide = :ide";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ide', $ide);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un employé
    public function createEmploye($nom, $prenom, $email, $telephone, $idd) {
        $query = "INSERT INTO employes (nom, prenom, email, telephone, idd) 
                  VALUES (:nom, :prenom, :email, :telephone, :idd)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':idd', $idd);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Mettre à jour un employé
    public function updateEmploye($ide, $nom, $prenom, $email, $telephone, $idd) {
        $query = "UPDATE employes 
                  SET nom = :nom, prenom = :prenom, email = :email, 
                      telephone = :telephone, idd = :idd 
                  WHERE ide = :ide";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ide', $ide);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':idd', $idd);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Supprimer un employé
    public function deleteEmploye($ide) {
        $query = "DELETE FROM employes WHERE ide = :ide";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ide', $ide);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Récupérer les employés par département
    public function getEmployesByDepartement($idd) {
        $query = "SELECT * FROM employes WHERE idd = :idd ORDER BY nom ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idd', $idd);
        $stmt->execute();
        return $stmt;
    }
}