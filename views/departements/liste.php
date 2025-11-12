<?php
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/departement.php';
require_once '/xampp/htdocs/karabusiness/controllers/departements.php';
require_once '/xampp/htdocs/karabusiness/config/auth.php';

// Vérification si l'utilisateur est connecté
session_start();

// Création d'une instance du modèle visiteur
$database = new Database();
$db = $database->getConnection();
$departementcontroller = new DepartementsController($db);
// Traitement des actions
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $departementcontroller->supprimer($id);
    header('Location: liste.php?success=1');
    exit;
}

// Récupération des départements
$dp = new Departement($db);
$departements = $dp->getDepartements();

// Inclusion de l'entête
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
    .page-title .fa-building-columns {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .table-departements {
        border: 2px solid #b0b8c9;
        border-radius: 0.7rem;
        overflow: hidden;
        background: #fff;
        margin-bottom: 2rem;
    }
    .table-departements th, .table-departements td {
        border-bottom: 1.5px solid #b0b8c9 !important;
        vertical-align: middle !important;
    }
    .table-departements thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .table-departements tbody tr:hover {
        background: #fdf6e3;
        transition: background 0.2s;
    }
    .departement-badge {
        padding: 0.35em 1em;
        border-radius: 1em;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
        background: linear-gradient(90deg, #e67e22 0%, #f7971e 100%);
        color: #fff;
    }
    .btn-action {
        border: none;
        border-radius: 0.5em;
        padding: 0.35em 0.8em;
        font-size: 0.98rem;
        font-weight: 500;
        margin-right: 0.3em;
        transition: background 0.2s, color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
    }
    .btn-action.edit {
        background: #fff3cd;
        color: #856404;
    }
    .btn-action.edit:hover {
        background: #ffe082;
        color: #7a4e00;
    }
    .btn-action.delete {
        background: #ffeaea;
        color: #b00020;
    }
    .btn-action.delete:hover {
        background: #ffb3b3;
        color: #fff;
    }
    .add-btn {
        background: linear-gradient(90deg, #e67e22 0%, #f7971e 100%);
        color: #fff;
        border: none;
        border-radius: 0.5em;
        padding: 0.5em 1.2em;
        font-size: 1.05rem;
        font-weight: 600;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
        transition: background 0.2s;
    }
    .add-btn:hover {
        background: linear-gradient(90deg, #f7971e 0%, #e67e22 100%);
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="page-title">
            <i class="fa-solid fa-building-columns"></i>
            Gestion des départements
        </span>
        <a href="ajouter.php" class="add-btn">
            <i class="fa-solid fa-plus me-1"></i> Ajouter un département
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success fw-semibold">
        Opération réussie!
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-departements align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><i class="fa-solid fa-building"></i> Nom du département</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($departements) > 0): ?>
                    <?php foreach ($departements as $departement): ?>
                        <tr>
                            <td><?= $departement['idd']; ?></td>
                            <td>
                                <span class="departement-badge">
                                    <i class="fa-solid fa-building"></i>
                                    <?= htmlspecialchars($departement['nomd']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="modifier.php?id=<?= $departement['idd']; ?>" class="btn-action edit" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="liste.php?action=delete&id=<?= $departement['idd']; ?>" class="btn-action delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Aucun département trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>