<?php
// Inclusion des fichiers nécessaires
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/config/auth.php';
require_once '/xampp/htdocs/karabusiness/models/visiteur.php';
require_once '/xampp/htdocs/karabusiness/models/employe.php';

session_start();
$db = new Database();
$auth = new Authentifi($db->getConnection());
if (!$auth->isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

// Vérification de l'ID du visiteur
if (!isset($_GET['idv']) || empty($_GET['idv'])) {
    $_SESSION['message'] = 'ID du visiteur non spécifié';
    $_SESSION['message_type'] = 'danger';
    header('Location: liste.php');
    exit();
}

$idVisiteur = $_GET['idv'];

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$employeModel = new Employe($db);
$visiteurModel = new Visiteur($db);

// Récupération des données du visiteur
$visiteur = $visiteurModel->getOne($idVisiteur);

// Vérification si le visiteur existe
if (!$visiteur) {
    $_SESSION['message'] = 'Visiteur non trouvé';
    $_SESSION['message_type'] = 'danger';
    header('Location: liste.php');
    exit();
}

// Récupération des employés
$employes = $employeModel->getEmployes();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visiteurModel->update(
        $idVisiteur,
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['email'],
        $_POST['type_piece'],
        $_POST['numero_piece'],
        $_POST['motif'],
        $_POST['ide'],
        $_POST['statut']
    );
    $_SESSION['message'] = 'Visiteur modifié avec succès !';
    $_SESSION['message_type'] = 'success';
    header('Location: liste.php');
    exit();
}

// Inclusion du header
include '/xampp/htdocs/karabusiness/includes/header.php';
?>
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Loader l-trefoil -->
<div id="loader" style="position:fixed;top:0;left:0;width:100vw;height:100vh;display:flex;align-items:center;justify-content:center;background:#fff;z-index:9999;">
    <l-trefoil
        size="80"
        stroke="4"
        stroke-length="0.15"
        bg-opacity="0.1"
        speed="1.4"
        color="#e67e22">
    </l-trefoil>
</div>
<script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/trefoil.js"></script>
<script>
window.addEventListener('load', function() {
  setTimeout(function() {
    var loader = document.getElementById('loader');
    if(loader) loader.style.display = 'none';
  }, 1200);
});
</script>
<style>
    .page-title {
        font-size: 2.1rem;
        font-weight: bold;
        letter-spacing: 1px;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.7em;
    }
    .page-title .fa-user-pen {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .form-card {
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(44,62,80,0.10);
        border: none;
        background: linear-gradient(135deg, #f8fafc 60%, #e0e7ef 100%);
        padding: 2.2rem 2rem 1.5rem 2rem;
        max-width: 600px;
        margin: 0 auto;
    }
    .form-label {
        font-weight: 600;
        color: #e67e22;
        margin-bottom: 0.3rem;
    }
    .form-control:focus {
        border-color: #e67e22;
        box-shadow: 0 0 0 0.2rem rgba(230,126,34,0.15);
    }
    .input-group-text {
        background: #fff3cd;
        color: #e67e22;
        border: none;
        font-size: 1.1rem;
    }
    .btn-orange {
        background: linear-gradient(90deg, #e67e22 0%, #f7971e 100%);
        color: #fff;
        border: none;
        border-radius: 0.5em;
        padding: 0.5em 1.5em;
        font-size: 1.08rem;
        font-weight: 600;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
        transition: background 0.2s;
    }
    .btn-orange:hover {
        background: linear-gradient(90deg, #f7971e 0%, #e67e22 100%);
        color: #fff;
    }
    .btn-secondary {
        border-radius: 0.5em;
        font-weight: 600;
    }
</style>

<div class="container py-4">
    <div class="page-title mb-4">
        <i class="fa-solid fa-user-pen"></i>
        Modifier un visiteur
    </div>
    <div class="form-card">
        <form method="post" action="">
            <div class="mb-3">
                <label for="nom" class="form-label"><i class="fa-solid fa-user"></i> Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($visiteur['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label"><i class="fa-solid fa-signature"></i> Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($visiteur['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label"><i class="fa-solid fa-phone"></i> Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($visiteur['telephone']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($visiteur['email']) ?>">
            </div>
            <div class="mb-3">
                <label for="type_piece" class="form-label"><i class="fa-solid fa-id-card"></i> Type de pièce d'identité</label>
                <select class="form-control" id="type_piece" name="type_piece" required>
                    <option value="cni" <?= $visiteur['type_piece'] == 'cni' ? 'selected' : '' ?>>Carte Nationale d'Identité</option>
                    <option value="passeport" <?= $visiteur['type_piece'] == 'passeport' ? 'selected' : '' ?>>Passeport</option>
                    <option value="permis" <?= $visiteur['type_piece'] == 'permis' ? 'selected' : '' ?>>Permis de conduire</option>
                    <option value="autre" <?= $visiteur['type_piece'] == 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="numero_piece" class="form-label"><i class="fa-solid fa-hashtag"></i> Numéro de pièce</label>
                <input type="text" class="form-control" id="numero_piece" name="numero_piece" value="<?= htmlspecialchars($visiteur['numero_piece']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="motif" class="form-label"><i class="fa-solid fa-comment-dots"></i> Motif de la visite</label>
                <textarea class="form-control" id="motif" name="motif" required><?= htmlspecialchars($visiteur['motif']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="employe" class="form-label"><i class="fa-solid fa-user-tie"></i> Employé à visiter</label>
                <select class="form-control" id="employe" name="ide" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($employes as $employe): ?>
                        <option value="<?= $employe['ide'] ?>" <?= $visiteur['ide'] == $employe['ide'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employe['nom']) ?> <?= htmlspecialchars($employe['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="statut" class="form-label"><i class="fa-solid fa-circle-info"></i> Statut</label>
                <select class="form-control" id="statut" name="statut" required>
                    <option value="actif" <?= $visiteur['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="sorti" <?= $visiteur['statut'] == 'sorti' ? 'selected' : '' ?>>Sorti</option>
                </select>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-orange"><i class="fa-solid fa-floppy-disk me-1"></i>Enregistrer</button>
                <a href="liste.php" class="btn btn-secondary"><i class="fa-solid fa-xmark me-1"></i>Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>