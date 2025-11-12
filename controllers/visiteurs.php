<?php
// ---- Controller Visiteurs ----
require_once '/xampp/htdocs/karabusiness/models/visiteur.php';
require_once '/xampp/htdocs/karabusiness/models/employe.php';

class VisiteursController {
    private $db;
    private $visiteurModel;
    private $employeModel;

    public function __construct($db) {
        $this->db = $db;
        $this->visiteurModel = new Visiteur($db);
        $this->employeModel = new Employe($db);
    }

    // ---- Actions pour les vues ----
    
    // Afficher la liste des visiteurs
    public function liste() {
        $result = $this->visiteurModel->getAll();
        $visiteurs = $result->fetchAll(PDO::FETCH_ASSOC);
        
        // Pour affichage dans la vue
        include '/xampp/htdocs/karabusiness/views/visiteurs/liste.php';
    }
    
    // Afficher le formulaire d'ajout
    public function ajouterForm() {
        $employes = $this->employeModel->getEmployes()->fetchAll(PDO::FETCH_ASSOC);
        
        include '/xampp/htdocs/karabusiness/views/visiteurs/ajouter.php';
    }
    
    // Traiter le formulaire d'ajout
    public function ajouter() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = strtolower($_POST['nom']);
            $prenom = strtolower($_POST['prenom']);
            $telephone = $_POST['telephone'];
            $email = strtolower($_POST['email']);
            $type_piece = strtolower($_POST['type_piece']);
            $numero_piece = $_POST['numero_piece'];
            $motif = strtolower($_POST['motif']);
            $ide = $_POST['ide'];
            
            if($this->visiteurModel->create($nom, $prenom, $telephone, $email, $type_piece, $numero_piece, $motif, $ide)) {
                // Succès
                header('Location: /karabusiness/views/visiteurs/liste.php?success=1');
                exit;
            } else {
                // Erreur
                include '/xampp/htdocs/karabusiness/views/visiteurs/ajouter.php';
            }
        }
    }
    
    // Afficher le formulaire de modification
    public function modifierForm($idv) {
        $visiteur = $this->visiteurModel->getOne($idv);
        $employes = $this->employeModel->getEmployes()->fetchAll(PDO::FETCH_ASSOC);
        
        include '/xampp/htdocs/karabusiness/views/visiteurs/modifier.php';
    }
    
    // Traiter le formulaire de modification
    public function modifier($idv) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = strtolower($_POST['nom']);
            $prenom = strtolower($_POST['prenom']);
            $telephone = $_POST['telephone'];
            $email = strtolower($_POST['email']);
            $type_piece = strtolower($_POST['type_piece']);
            $numero_piece = $_POST['numero_piece'];
            $motif = strtolower($_POST['motif']);
            $ide = $_POST['ide'];
            $statut = $_POST['statut'];
            
            if($this->visiteurModel->update($idv, $nom, $prenom, $telephone, $email, $type_piece, $numero_piece, $motif, $ide, $statut)) {
                // Succès
                header('Location: /karabusiness/views/visiteurs/liste.php?success=2');
                exit;
            } else {
                // Erreur
                $visiteur = $this->visiteurModel->getOne($idv);
                include '/xampp/htdocs/karabusiness/views/visiteurs/modifier.php';
            }
        }
    }
    
    // Marquer la sortie d'un visiteur
    public function sortie($idv) {
        if($this->visiteurModel->marquerSortie($idv)) {
            // Succès
            header('Location: /karabusiness/views/visiteurs/liste.php?success=3');
            exit;
        } else {
            // Erreur
            header('Location: /karabusiness/views/visiteurs/liste.php?error=1');
            exit;
        }
    }
    
    // Archiver un visiteur
    public function archiver($idv) {
        if($this->visiteurModel->archiver($idv)) {
            // Succès
            header('Location: /karabusiness/views/visiteurs/liste.php?success=4');
            exit;
        } else {
            // Erreur
            header('Location: /karabusiness/views/visiteurs/liste.php?error=2');
            exit;
        }
    }
    
    // Afficher la liste des visiteurs archivés
    public function archives() {
        $result = $this->visiteurModel->getArchives();
        $visiteurs = $result->fetchAll(PDO::FETCH_ASSOC);
        
        include '/xampp/htdocs/karabusiness/views/visiteurs/archivage.php';
    }
    
    // // ---- API functions ----
    
    // // Liste des visiteurs au format JSON
    // public function apiListe() {
    //     $result = $this->visiteurModel->getAll();
    //     $visiteurs = $result->fetchAll(PDO::FETCH_ASSOC);
        
    //     header('Content-Type: application/json');
    //     echo json_encode($visiteurs);
    // }
    
    // // Détails d'un visiteur au format JSON
    // public function apiDetails($idv) {
    //     $visiteur = $this->visiteurModel->getOne($idv);
        
    //     header('Content-Type: application/json');
    //     echo json_encode($visiteur);
    // }
    
    // // Ajouter un visiteur via API
    // public function apiAjouter() {
    //     // Récupérer les données JSON
    //     $data = json_decode(file_get_contents('php://input'), true);
        
    //     if(isset($data['nom']) && isset($data['prenom']) && isset($data['telephone'])) {
    //         $nom = strtolower($data['nom']);
    //         $prenom = strtolower($data['prenom']);
    //         $telephone = $data['telephone'];
    //         $email = isset($data['email']) ? strtolower($data['email']) : '';
    //         $type_piece = strtolower($data['type_piece']);
    //         $numero_piece = $data['numero_piece'];
    //         $motif = strtolower($data['motif']);
    //         $ide = $data['ide'];
    //         $idd = $data['idd'];
            
    //         if($this->visiteurModel->create($nom, $prenom, $telephone, $email, $type_piece, $numero_piece, $motif, $ide)) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => true, 'message' => 'Visiteur ajouté avec succès']);
    //         } else {
    //             header('Content-Type: application/json');
    //             http_response_code(500);
    //             echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du visiteur']);
    //         }
    //     } else {
    //         header('Content-Type: application/json');
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'Données insuffisantes']);
    //     }
    // }
    
    // // Marquer la sortie d'un visiteur via API
    // public function apiSortie($idv) {
    //     if($this->visiteurModel->marquerSortie($idv)) {
    //         header('Content-Type: application/json');
    //         echo json_encode(['success' => true, 'message' => 'Sortie marquée avec succès']);
    //     } else {
    //         header('Content-Type: application/json');
    //         http_response_code(500);
    //         echo json_encode(['success' => false, 'message' => 'Erreur lors du marquage de la sortie']);
    //     }
    // }
}