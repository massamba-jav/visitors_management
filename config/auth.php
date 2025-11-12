<?php
// ---- fonctions d'authentification ----
class Authentifi {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // vérifier les identifiants de l'utilisateur
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }
// vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

// rediriger si non connecté
function requireLogin() {
    if (!$this->isLoggedIn()) {
        header("Location: /karabusiness/index.php");
        exit();
    }
}

// rediriger si non admin
function requireAdmin() {
    $this->requireLogin();
    if (!$this->isAdmin()) {
        header("Location: /karabusiness/dashboard.php");
        exit();
    }
}

// déconnexion
function logout() {
    session_unset();
    session_destroy();
    header("Location: /karabusiness/index.php"); 
    exit();
}
}