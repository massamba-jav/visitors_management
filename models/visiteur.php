<?php
// ---- modèle visiteur ----
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/visiteur.php'; // s'assurer que la classe est incluse
if (isset($_GET['action']) && $_GET['action'] === 'archiver' && isset($_GET['idv'])) {
    $idv = $_GET['idv']; 
    $database = new Database();
    $db = $database->getConnection();
    $visiteur = new Visiteur($db);
    session_start();
    $_SESSION['message'] = 'Visiteur archivé avec succès !';
    $_SESSION['message_type'] = 'success';
    $visiteur->archiver($idv);
}
if (isset($_GET['action']) && $_GET['action'] === 'restore' && isset($_GET['idv'])) {
    $idv = $_GET['idv']; 
    $database = new Database();
    $db = $database->getConnection();
    $visiteur = new Visiteur($db);
    session_start();
    $_SESSION['message'] = 'Visiteur restauré avec succès !';
    $_SESSION['message_type'] = 'success';
    $visiteur->restaurer($idv);
}
if (isset($_GET['action']) && $_GET['action'] === 'marquersortie' && isset($_GET['idv'])) {
    $idv = $_GET['idv']; 
    $database = new Database();
    $db = $database->getConnection();
    $visiteur = new Visiteur($db);
    session_start();
    $_SESSION['message'] = 'Sortie du visiteur enregistré avec succès !';
    $_SESSION['message_type'] = 'success';
    $visiteur->marquerSortie($idv);
}
class Visiteur {
    // connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "visiteurs";

    // propriétés
    public $idv;
    public $nom;
    public $prenom;
    public $telephone;
    public $email;
    public $type_piece;
    public $numero_piece;
    public $motif;
    public $date_entree;
    public $date_sortie;
    public $ide;
    public $statut;

    // constructeur avec $db pour la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // lire tous les visiteurs
    public function getAll() {
        // requête select all
        $query = "SELECT v.*, e.nom as employe_nom, e.prenom as employe_prenom, d.nomd
                FROM " . $this->table_name . " v
                LEFT JOIN employes e ON v.ide = e.ide
                LEFT JOIN departements d ON v.idd = d.idd
                ORDER BY v.date_entree DESC";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // exécuter la requête
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // lire les visiteurs actifs (non archivés)
    public function getActifs() {
        // requête select avec filtre
        $query = "SELECT v.*, e.nom as employe_nom, e.prenom as employe_prenom
                FROM " . $this->table_name . " v
                LEFT JOIN employes e ON v.ide = e.ide
                WHERE v.statut != 'archive'
                ORDER BY v.date_entree DESC";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // exécuter la requête
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // lire les visiteurs archivés
    public function getArchives() {
        // requête select avec filtre
        $query = "SELECT v.*, e.nom as employe_nom, e.prenom as employe_prenom
                FROM " . $this->table_name . " v
                LEFT JOIN employes e ON v.ide = e.ide
                WHERE v.statut = 'archive'
                ORDER BY v.date_entree DESC";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // exécuter la requête
        
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // lire un seul visiteur
    public function getOne($idv) {
        $this->idv = $idv ;
        // requête pour lire un seul enregistrement
        $query = "SELECT v.*, e.nom as employe_nom, e.prenom as employe_prenom
                FROM " . $this->table_name . " v
                LEFT JOIN employes e ON v.ide = e.ide
                WHERE v.idv = ?
                LIMIT 0,1";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // liaison de l'id
        $stmt->bindParam(1, $this->idv);

        // exécuter la requête
        $stmt->execute();

        // récupérer la ligne
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // définir les valeurs des propriétés de l'objet
        if ($row) {
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->telephone = $row['telephone'];
            $this->email = $row['email'];
            $this->type_piece = $row['type_piece'];
            $this->numero_piece = $row['numero_piece'];
            $this->motif = $row['motif'];
            $this->date_entree = $row['date_entree'];
            $this->date_sortie = $row['date_sortie'];
            $this->ide = $row['ide'];
            $this->statut = $row['statut'];
            return $row;
        }
        return null ;
    }

    // créer un visiteur
    public function create($nom, $prenom, $telephone, $email, $type_piece, $numero_piece, $motif, $ide) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->type_piece = $type_piece;
        $this->numero_piece = $numero_piece;
        $this->motif = $motif;
        $this->date_entree = date('Y-m-d H:i:s');
        $this->ide = $ide;
        
        // requête d'insertion
        $query = "INSERT INTO " . $this->table_name . "
                (nom, prenom, telephone, email, type_piece, numero_piece, motif, date_entree, ide)
                VALUES
                (:nom, :prenom, :telephone, :email, :type_piece, :numero_piece, :motif, :date_entree, :ide)";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->type_piece = htmlspecialchars(strip_tags($this->type_piece));
        $this->numero_piece = htmlspecialchars(strip_tags($this->numero_piece));
        $this->motif = htmlspecialchars(strip_tags($this->motif));

        // liaison des paramètres
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":type_piece", $this->type_piece);
        $stmt->bindParam(":numero_piece", $this->numero_piece);
        $stmt->bindParam(":motif", $this->motif);
        $stmt->bindParam(":date_entree", $this->date_entree);
        $stmt->bindParam(":ide", $this->ide);

        // exécuter la requête
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return null;
    }

    // mettre à jour un visiteur
    public function update($idv, $nom, $prenom, $telephone, $email, $type_piece, $numero_piece, $motif, $ide, $statut) {
        // requête de mise à jour
        $query = "UPDATE " . $this->table_name . "
                SET
                    nom = :nom,
                    prenom = :prenom,
                    telephone = :telephone,
                    email = :email,
                    type_piece = :type_piece,
                    numero_piece = :numero_piece,
                    motif = :motif,
                    statut = :statut,
                    ide = :ide
                WHERE
                    idv = :idv";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // liaison des paramètres
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":telephone", $telephone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":type_piece", $type_piece);
        $stmt->bindParam(":numero_piece", $numero_piece);
        $stmt->bindParam(":motif", $motif);
        $stmt->bindParam(":statut", $statut);
        $stmt->bindParam(":ide", $ide);
        $stmt->bindParam(":idv", $idv);

        // exécuter la requête
        return $stmt->execute();
    }

    // marquer un visiteur comme sorti
    public function marquerSortie($idv) {
        $this->idv = $idv ;
        // requête de mise à jour
        $query = "UPDATE " . $this->table_name . "
                SET
                    date_sortie = NOW(),
                    statut = 'sorti'
                WHERE
                    idv = :idv";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // liaison de l'id
        $stmt->bindParam(":idv", $this->idv);

        // exécuter la requête
        $stmt->execute();
        header('Location: /karabusiness/views/visiteurs/liste.php');
        return ; 
    }

    // archiver un visiteur
    public function archiver($idv) {
        $this->idv = $idv ;
        // requête de mise à jour
        $query = "UPDATE " . $this->table_name . "
                SET
                    statut = 'archive'
                WHERE
                    idv = :idv";

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // liaison de l'id
        $stmt->bindParam(":idv", $this->idv);
        
        // exécuter la requête
        $stmt->execute();
        header('Location: /karabusiness/views/visiteurs/liste.php');
        return ;
    }

    // restaurer un visiteur archivé
    public function restaurer($idv) {
        $this->idv = $idv ;
        $database = new Database();
        $db = $database->getConnection();
        $visiteurModel = new Visiteur($db);
        $visiteur = $visiteurModel->getOne($idv);
        // requête de mise à jour
        if ($visiteur['date_sortie'] == null) {
            $query = "UPDATE " . $this->table_name . "
                SET
                    statut = 'actif'
                WHERE
                    idv = :idv AND statut = 'archive'";
        } else {
            $query = "UPDATE " . $this->table_name . "
                SET
                    statut = 'sorti'
                WHERE
                    idv = :idv AND statut = 'archive'";
        }

        // préparer la requête
        $stmt = $this->conn->prepare($query);

        // liaison de l'id
        $stmt->bindParam(":idv", $this->idv);

        // exécuter la requête
        $stmt->execute();
        header('Location: /karabusiness/views/visiteurs/archivage.php');
        return ;
    }
}