<?php
// ----- Controller pour la gestion des utilisateurs -----
// Ce fichier contient toutes les fonctions de contrôle pour les utilisateurs
// Accessible uniquement par les administrateurs

// Inclure les fichiers nécessaires
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/utilisateur.php';

// Vérifier si l'utilisateur est connecté et est admin
session_start();

// Instancier les objets nécessaires
$db = new Database();
$conn = $db->getConnection();
$utilisateurModel = new Utilisateur($conn);

// ----- Gestion des actions -----
$action = isset($_GET['action']) ? $_GET['action'] : 'liste';

switch ($action) {
    case 'liste':
        // Liste des utilisateurs
        $utilisateurs = $utilisateurModel->getUtilisateurs();
        
        // Format API si demandé
        if (isset($_GET['format']) && $_GET['format'] == 'json') {
            header('Content-Type: application/json');
            echo json_encode($utilisateurs);
            exit();
        }
        
        break;
        
    case 'ajouter':
        // Formulaire d'ajout d'utilisateur
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $utilisateur = [
                'username' => strtolower($_POST['username']),
                'password' => $_POST['password'], // Pas de hachage comme demandé
                'nom' => strtolower($_POST['nom']),
                'prenom' => strtolower($_POST['prenom']),
                'email' => strtolower($_POST['email']),
                'role' => strtolower($_POST['role'])
            ];
            
            // Validation des données
            $errors = [];
            if (empty($utilisateur['username'])) $errors[] = "Le nom d'utilisateur est obligatoire";
            if (empty($utilisateur['password'])) $errors[] = "Le mot de passe est obligatoire";
            if (empty($utilisateur['nom'])) $errors[] = "Le nom est obligatoire";
            if (empty($utilisateur['prenom'])) $errors[] = "Le prénom est obligatoire";
            if (empty($utilisateur['role'])) $errors[] = "Le rôle est obligatoire";
            
            // Vérifier si le nom d'utilisateur existe déjà
            if ($utilisateurModel->usernameExists($utilisateur['username'])) {
                $errors[] = "Ce nom d'utilisateur existe déjà";
            }
            
            if (empty($errors)) {
                // Enregistrement de l'utilisateur
                $result = $utilisateurModel->createUtilisateur($utilisateur['username'], $utilisateur['password'], $utilisateur['nom'], $utilisateur['prenom'], $utilisateur['email'], $utilisateur['role']);
                
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result, 'id' => $conn->lastInsertId()]);
                    exit();
                }
                
                // Redirection vers la liste
                header('Location: /karabusiness/views/utilisateurs/liste.php?success=added');
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
        
        include '/xampp/htdocs/karabusiness/views/utilisateurs/ajouter.php';
        break;
        
    case 'modifier':
        // Vérifier que l'ID est présent
        if (!isset($_GET['id'])) {
            header('Location: /karabusiness/views/utilisateurs/liste.php?error=missing_id');
            exit();
        }
        
        $idu = $_GET['id'];
        
        // Protection pour éviter qu'un admin se supprime lui-même les droits
        if ($idu == $_SESSION['user']['idu']) {
            header('Location: /karabusiness/views/utilisateurs/liste.php?error=cannot_edit_self');
            exit();
        }
        
        // Formulaire de modification d'utilisateur
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupération des données du formulaire
            $utilisateur = [
                'idu' => $idu,
                'username' => strtolower($_POST['username']),
                'nom' => strtolower($_POST['nom']),
                'prenom' => strtolower($_POST['prenom']),
                'email' => strtolower($_POST['email']),
                'role' => strtolower($_POST['role'])
            ];
            
            // Gestion du mot de passe (modification optionnelle)
            if (!empty($_POST['password'])) {
                $utilisateur['password'] = $_POST['password']; // Pas de hachage comme demandé
            }
            
            // Validation des données
            $errors = [];
            if (empty($utilisateur['username'])) $errors[] = "Le nom d'utilisateur est obligatoire";
            if (empty($utilisateur['nom'])) $errors[] = "Le nom est obligatoire";
            if (empty($utilisateur['prenom'])) $errors[] = "Le prénom est obligatoire";
            if (empty($utilisateur['role'])) $errors[] = "Le rôle est obligatoire";
            
            // Vérifier si le nom d'utilisateur existe déjà (sauf si c'est le même utilisateur)
            if ($utilisateurModel->getUtilisateurById( $idu) != null) {
                $errors[] = "Ce nom d'utilisateur existe déjà";
            }
            
            if (empty($errors)) {
                // Mise à jour de l'utilisateur
                $result = $utilisateurModel->updateUtilisateur($utilisateur['idu'], $utilisateur['username'], $utilisateur['password'], $utilisateur['nom'], $utilisateur['prenom'], $utilisateur['email'], $utilisateur['role']);
                
                // Format API si demandé
                if (isset($_GET['format']) && $_GET['format'] == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result]);
                    exit();
                }
                
                // Redirection vers la liste
                header('Location: /karabusiness/views/utilisateurs/liste.php?success=updated');
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
        
        // Récupération des données de l'utilisateur
        $utilisateur = $utilisateurModel->getUtilisateurById($idu);
        if (!$utilisateur) {
            header('Location: /karabusiness/views/utilisateurs/liste.php?error=not_found');
            exit();
        }
        
        include '/xampp/htdocs/karabusiness/views/utilisateurs/modifier.php';
        break;
        
    case 'supprimer':
        // Vérifier que l'ID est présent
        if (!isset($_GET['id'])) {
            header('Location: /karabusiness/views/utilisateurs/liste.php?error=missing_id');
            exit();
        }
        
        $idu = $_GET['id'];
        
        // Protection pour éviter qu'un admin se supprime lui-même
        if ($idu == $_SESSION['user']['idu']) {
            // Format API si demandé
            if (isset($_GET['format']) && $_GET['format'] == 'json') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Impossible de supprimer votre propre compte']);
                exit();
            }
            
            header('Location: /karabusiness/views/utilisateurs/liste.php?error=cannot_delete_self');
            exit();
        }
        
        // Suppression de l'utilisateur
        $result = $utilisateurModel->deleteUtilisateur($idu);
        
        // Format API si demandé
        if (isset($_GET['format']) && $_GET['format'] == 'json') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit();
        }
        
        // Redirection vers la liste
        header('Location: /karabusiness/views/utilisateurs/liste.php?success=deleted');
        break;
        
    case 'get':
        // Récupérer les infos d'un utilisateur au format JSON (pour API)
        if (!isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'ID manquant']);
            exit();
        }
        
        $idu = $_GET['id'];
        $utilisateur = $utilisateurModel->getUtilisateurById($idu);
        
        // On ne renvoie pas le mot de passe
        if (isset($utilisateur['password'])) {
            unset($utilisateur['password']);
        }
        
        header('Content-Type: application/json');
        echo json_encode($utilisateur);
        break;
        
    default:
        // Action non reconnue
        header('Location: /karabusiness/views/utilisateurs/liste.php');
        break;
}
?>