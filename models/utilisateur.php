<?php
// ---- Modèle Utilisateur ----
class Utilisateur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Connexion utilisateur
    public function login($username, $password) {
        $query = "SELECT * FROM utilisateurs WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Update last login timestamp
            $this->updateLastLogin($user['idu']);
            
            // Initialize session
            $_SESSION['user_id'] = $user['idu'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            
            return $_SESSION ; // Login successful
        }
        
        return null ; // Login failed
    }

    // Mise à jour de la dernière connexion
    private function updateLastLogin($idu) {
        $query = "UPDATE utilisateurs SET lastlogin = NOW() WHERE idu = :idu";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idu', $idu);
        $stmt->execute();
    }

    // Récupérer tous les utilisateurs
    public function getUtilisateurs() {
        $query = "SELECT * FROM utilisateurs ORDER BY nom ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Récupérer un utilisateur par ID
    public function getUtilisateurById($idu) {
        $query = "SELECT * FROM utilisateurs WHERE idu = :idu";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idu', $idu);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un utilisateur
    public function createUtilisateur($username, $password, $nom, $prenom, $email, $role) {
        $query = "INSERT INTO utilisateurs (username, password, nom, prenom, email, role) 
                  VALUES (:username, :password, :nom, :prenom, :email, :role)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Mettre à jour un utilisateur
    public function updateUtilisateur($idu, $username, $password, $nom, $prenom, $email, $role) {
        // Si le mot de passe est vide, on ne le met pas à jour
        if(empty($password)) {
            $query = "UPDATE utilisateurs 
                      SET username = :username, nom = :nom, prenom = :prenom, 
                          email = :email, role = :role 
                      WHERE idu = :idu";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idu', $idu);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
        } else {
            $query = "UPDATE utilisateurs 
                      SET username = :username, password = :password, nom = :nom, 
                          prenom = :prenom, email = :email, role = :role 
                      WHERE idu = :idu";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':idu', $idu);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
        }

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Supprimer un utilisateur
    public function deleteUtilisateur($idu) {
        $query = "DELETE FROM utilisateurs WHERE idu = :idu";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idu', $idu);

        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Vérifier si un username existe déjà
    public function usernameExists($username, $idu = 0) {
        if($idu > 0) {
            // Pour mise à jour (exclure l'utilisateur courant)
            $query = "SELECT * FROM utilisateurs WHERE username = :username AND idu != :idu";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':idu', $idu);
        } else {
            // Pour création
            $query = "SELECT * FROM utilisateurs WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}