<?php
// ---- Controller Departements ----
require_once '/xampp/htdocs/karabusiness/models/departement.php';

class DepartementsController {
    private $db;
    private $departementModel;

    public function __construct($db) {
        $this->db = $db;
        $this->departementModel = new Departement($db);
    }

    // ---- Actions pour les vues ----
    
    // Afficher la liste des départements
    public function liste() {
        $result = $this->departementModel->getDepartements();
        $departements = $result->fetchAll(PDO::FETCH_ASSOC);
        
        include '/xampp/htdocs/karabusiness/views/departements/liste.php';
    }
    
    // Afficher le formulaire d'ajout
    public function ajouterForm() {
        include '/xampp/htdocs/karabusiness/views/departements/ajouter.php';
    }
    
    // Traiter le formulaire d'ajout
    public function ajouter() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomd = strtolower($_POST['nomd']);
            
            if($this->departementModel->createDepartement($nomd)) {
                header('Location: /karabusiness/views/departements/liste.php?success=1');
                exit;
            } else {
                include '/xampp/htdocs/karabusiness/views/departements/ajouter.php';
            }
        }
    }
    
    // Afficher le formulaire de modification
    public function modifierForm($idd) {
        $departement = $this->departementModel->getDepartementById($idd);
        
        include '/xampp/htdocs/karabusiness/views/departements/modifier.php';
    }
    
    // Traiter le formulaire de modification
    public function modifier($idd) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomd = strtolower($_POST['nomd']);
            
            if($this->departementModel->updateDepartement($idd, $nomd)) {
                header('Location: /karabusiness/views/departements/liste.php?success=2');
                exit;
            } else {
                $departement = $this->departementModel->getDepartementById($idd);
                include '/xampp/htdocs/karabusiness/views/departements/modifier.php';
            }
        }
    }
    
    // Supprimer un département
    public function supprimer($idd) {
        if($this->departementModel->deleteDepartement($idd)) {
            header('Location: /karabusiness/views/departements/liste.php?success=3');
            exit;
        } else {
            header('Location: /karabusiness/views/departements/liste.php?error=1');
            exit;
        }
    }
    
    // ---- API functions ----
    
    // Liste des départements au format JSON
    public function apiListe() {
        $result = $this->departementModel->getDepartements();
        $departements = $result->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($departements);
    }
    
    // Détails d'un département au format JSON
    public function apiDetails($idd) {
        $departement = $this->departementModel->getDepartementById($idd);
        
        header('Content-Type: application/json');
        echo json_encode($departement);
    }
    
    // Ajouter un département via API
    public function apiAjouter() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if(isset($data['nomd'])) {
            $nomd = strtolower($data['nomd']);
            
            if($this->departementModel->createDepartement($nomd)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Département ajouté avec succès']);
            } else {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du département']);
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données insuffisantes']);
        }
    }
    
    // Statistiques des départements (nombre d'employés et visiteurs par département)
    public function apiStats() {
        $stats = $this->departementModel->countEmployesParDepartement();
        $result = $stats->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}