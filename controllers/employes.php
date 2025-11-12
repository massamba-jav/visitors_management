<?php
// ----- Controller pour la gestion des employés -----
// Ce fichier contient toutes les fonctions de contrôle pour les employés
// Accessible uniquement par les administrateurs

// Inclure les fichiers nécessaires
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/employe.php';
require_once '/xampp/htdocs/karabusiness/models/departement.php';

// Vérifier si l'utilisateur est connecté et est admin
session_start();

// Instancier les objets nécessaires
$db = new Database();
$conn = $db->getConnection();
$employeModel = new Employe($conn);
$departementModel = new Departement($conn);

// ----- Gestion des actions -----
$action = isset($_GET['action']) ? $_GET['action'] : 'liste';

switch ($action) {
    case 'liste':
        // Liste des employés
        $employes = $employeModel->getEmployes();
        $departements = $departementModel->getDepartements();
        
        // Format API si demandé
        if (isset($_GET['format']) && $_GET['format'] == 'json') {
            header('Content-Type: application/json');
            echo json_encode($employes);
            exit();
        }
        
        // Sinon on inclut la vue
        //include '/xampp/htdocs/karabusiness/views/employes/liste.php';
        break;
        
    case 'ajouter':
        // Formulaire d'ajout d'employé
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $employe = [
                'nom' => strtolower($_POST['nom']),
                'prenom' => strtolower($_POST['prenom']),
                'email' => strtolower($_POST['email']),
                'telephone' => $_POST['telephone'],
                'idd' => $_POST['departement']
            ];
            
            // Validation des données
            $errors = [];
            if (empty($employe['nom'])) $errors[] = "Le nom est obligatoire";
            if (empty($employe['prenom'])) $errors[] = "Le prénom est obligatoire";
            if (empty($employe['idd'])) $errors[] = "Le département est obligatoire";
            
            if (empty($errors)) {
                // Enregistrement de l'employé
                $result = $employeModel->createEmploye($employe['nom'], $employe['prenom'], $employe['email'], $employe['telephone'], $employe['idd']);
                
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result, 'id' => $conn->lastInsertId()]);
                    exit();
                }
                
                // Redirection vers la liste
                header('Location: /karabusiness/views/employes/liste.php?success=added');
                exit();
            } else {
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit();
                }
                
                // Affichage des erreurs
                $_SESSION['errors'] = $errors;
            }
        }
        
        // Récupération des départements pour le formulaire
        $departements = $departementModel->getDepartements();
        include '/xampp/htdocs/karabusiness/employes/ajouter.php';
        break;
        
    case 'modifier':
        // Vérifier que l'ID est présent
        if (!isset($_GET['id'])) {
            header('Location: /karabusiness/views/employes/liste.php?error=missing_id');
            exit();
        }
        
        $ide = $_GET['id'];
        
        // Formulaire de modification d'employé
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $employe = [
                'ide' => $ide,
                'nom' => strtolower($_POST['nom']),
                'prenom' => strtolower($_POST['prenom']),
                'email' => strtolower($_POST['email']),
                'telephone' => $_POST['telephone'],
                'idd' => $_POST['departement']
            ];
            
            // Validation des données
            $errors = [];
            if (empty($employe['nom'])) $errors[] = "Le nom est obligatoire";
            if (empty($employe['prenom'])) $errors[] = "Le prénom est obligatoire";
            if (empty($employe['idd'])) $errors[] = "Le département est obligatoire";
            
            if (empty($errors)) {
                // Mise à jour de l'employé
                $result = $employeModel->updateEmploye($employe['ide'], $employe['nom'], $employe['prenom'], $employe['email'], $employe['telephone'], $employe['idd']);
                
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result]);
                    exit();
                }
                
                // Redirection vers la liste
                header('Location: /karabusiness/views/employes/liste.php?success=updated');
                exit();
            } else {
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit();
                }
                
                // Affichage des erreurs
                $_SESSION['errors'] = $errors;
            }
        }
        
        // Récupération des données de l'employé
        $employe = $employeModel->getEmployeById($ide);
        if (!$employe) {
            header('Location: /karabusiness/views/employes/liste.php?error=not_found');
            exit();
        }
        
        // Récupération des départements pour le formulaire
        $departements = $departementModel->getDepartements();
        include '/xampp/htdocs/karabusiness/employes/modifier.php';
        break;
        
    case 'supprimer':
        // Vérifier que l'ID est présent
        if (!isset($_GET['id'])) {
            header('Location: /karabusiness/views/employes/liste.php?error=missing_id');
            exit();
        }
        
        $ide = $_GET['id'];
        
        // Suppression de l'employé
        $result = $employeModel->deleteEmploye($ide);
        
        // Format API si demandé
        if (isset($_GET['format']) && $_GET['format'] == 'json') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit();
        }
        
        // Redirection vers la liste
        header('Location: /karabusiness/views/employes/liste.php?success=deleted');
        break;
        
    case 'get':
        // Récupérer les infos d'un employé au format JSON (pour API)
        if (!isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'ID manquant']);
            exit();
        }
        
        $ide = $_GET['id'];
        $employe = $employeModel->getEmployeById($ide);
        
        header('Content-Type: application/json');
        echo json_encode($employe);
        break;
        
    default:
        // Action non reconnue
        header('Location: /karabusiness/views/employes/liste.php');
        break;
}
?>