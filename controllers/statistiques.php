<?php
// Contrôleur pour les statistiques

require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/visiteur.php';
$stas = new statistiques();
// ---- Gestion des requêtes API ----
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'resume':
            echo json_encode($stas->getResumeStats());
            break;
        default:
            echo json_encode(['error' => 'Action non reconnue']);
    }
    exit;
}

// ---- Fonctions pour récupérer les statistiques ----

/**
 * Récupère le nombre de visites par jour pour les 7 derniers jours
 */
class statistiques {
    private $database;
    private $pdo;

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->getConnection();
    }

function getStatsVisitesJour() {
    $pdo = $this->pdo ;
    
    $query = "SELECT DATE_FORMAT(date_entree, '%d/%m') as jour, COUNT(*) as nombre 
              FROM visiteurs 
              WHERE date_entree >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
              GROUP BY DATE_FORMAT(date_entree, '%Y-%m-%d') 
              ORDER BY date_entree";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère le nombre de visites par semaine pour les 4 dernières semaines
 */
function getStatsVisitesSemaine() {
    $pdo = $this->pdo ;
    
    $query = "SELECT WEEK(date_entree) as semaine, COUNT(*) as nombre 
              FROM visiteurs 
              WHERE date_entree >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK) 
              GROUP BY WEEK(date_entree) 
              ORDER BY semaine";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère le nombre de visites par mois pour les 6 derniers mois
 */
function getStatsVisitesMois() {
    $pdo = $this->pdo ;
    
    $query = "SELECT DATE_FORMAT(date_entree, '%m/%Y') as mois, COUNT(*) as nombre 
              FROM visiteurs 
              WHERE date_entree >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
              GROUP BY DATE_FORMAT(date_entree, '%Y-%m') 
              ORDER BY date_entree";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un résumé des statistiques principales
 */
function getResumeStats() {
    $pdo = $this->pdo ;
    
    // Total des visites
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visiteurs");
    $stmt->execute();
    $total = $stmt->fetchColumn();
    
    // Visites aujourd'hui
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visiteurs WHERE DATE(date_entree) = CURDATE()");
    $stmt->execute();
    $aujourd_hui = $stmt->fetchColumn();
    
    // Visiteurs actuellement présents
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visiteurs WHERE date_sortie IS NULL AND statut = 'actif'");
    $stmt->execute();
    $presents = $stmt->fetchColumn();
    
    // Moyenne par jour (sur les 30 derniers jours)
    $stmt = $pdo->prepare("SELECT COUNT(*) / COUNT(DISTINCT DATE(date_entree)) as moyenne 
                          FROM visiteurs 
                          WHERE date_entree >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
    $stmt->execute();
    $moyenne = $stmt->fetchColumn();
    
    return [
        'total' => $total,
        'aujourd_hui' => $aujourd_hui,
        'presents' => $presents,
        'moyenne_jour' => $moyenne ?: 0
    ];
}
}