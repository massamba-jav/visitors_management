<?php
// Inclusion des fichiers nécessaires
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/config/auth.php';
require_once '/xampp/htdocs/karabusiness/models/visiteur.php';
require_once '/xampp/htdocs/karabusiness/controllers/visiteurs.php';
session_start();

// Création d'une instance du modèle visiteur
$database = new Database();
$db = $database->getConnection();
$visiteurModel = new Visiteur($db);

// Récupération de la liste des visiteurs actifs
$visiteurs = $visiteurModel->getActifs();

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
    .page-title .fa-address-book {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .table-visiteurs {
        border: 2px solid #b0b8c9;
        border-radius: 0.7rem;
        overflow: hidden;
        background: #fff;
        margin-bottom: 2rem;
    }
    .table-visiteurs th, .table-visiteurs td {
        border-bottom: 1.5px solid #b0b8c9 !important;
        vertical-align: middle !important;
    }
    .table-visiteurs thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .table-visiteurs tbody tr:hover {
        background: #fdf6e3;
        transition: background 0.2s;
    }
    .statut-badge {
        padding: 0.35em 1em;
        border-radius: 1em;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
    }
    .statut-actif {
        background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        color: #1a3a2b;
    }
    .statut-sorti {
        background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        color: #7a4e00;
    }
    .statut-archive {
        background: linear-gradient(90deg, #f953c6 0%, #b91d73 100%);
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
    .btn-action.sortie {
        background: #e7fbe7;
        color: #218838;
    }
    .btn-action.sortie:hover {
        background: #43e97b;
        color: #fff;
    }
    .btn-action.archive {
        background: #ffeaea;
        color: #b00020;
    }
    .btn-action.archive:hover {
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
            <i class="fa-solid fa-address-book"></i>
            Gestion des visiteurs
        </span>
        <a href="ajouter.php" class="add-btn">
            <i class="fa-solid fa-user-plus me-1"></i> Ajouter un visiteur
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?> fw-semibold">
        <?= $_SESSION['message'] ?>
    </div>
    <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    endif; 
    ?>

    <div class="table-responsive">
        <table class="table table-visiteurs align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><i class="fa-solid fa-user"></i> Nom</th>
                    <th><i class="fa-solid fa-signature"></i> Prénom</th>
                    <th><i class="fa-solid fa-phone"></i> Téléphone</th>
                    <th><i class="fa-solid fa-user-tie"></i> Employé visité</th>
                    <th><i class="fa-solid fa-door-open"></i> Date entrée</th>
                    <th><i class="fa-solid fa-door-closed"></i> Date sortie</th>
                    <th><i class="fa-solid fa-circle-info"></i> Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($visiteurs && count($visiteurs) > 0): ?>
                    <?php foreach ($visiteurs as $visiteur): ?>
                        <tr>
                            <td><?= $visiteur['idv'] ?></td>
                            <td><?= htmlspecialchars($visiteur['nom']) ?></td>
                            <td><?= htmlspecialchars($visiteur['prenom']) ?></td>
                            <td><?= htmlspecialchars($visiteur['telephone']) ?></td>
                            <td><?= htmlspecialchars($visiteur['employe_nom'] . ' ' . $visiteur['employe_prenom']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($visiteur['date_entree'])) ?></td>
                            <td>
                                <?= $visiteur['date_sortie'] ? date('d/m/Y H:i', strtotime($visiteur['date_sortie'])) : '-' ?>
                            </td>
                            <td>
                                <?php
                                    $statut = strtolower($visiteur['statut']);
                                    $badgeClass = 'statut-actif';
                                    $icon = 'fa-circle-dot';
                                    if ($statut === 'sorti') { $badgeClass = 'statut-sorti'; $icon = 'fa-arrow-right-from-bracket'; }
                                    elseif ($statut === 'archive') { $badgeClass = 'statut-archive'; $icon = 'fa-box-archive'; }
                                ?>
                                <span class="statut-badge <?= $badgeClass ?>">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= ucfirst($visiteur['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="modifier.php?idv=<?= $visiteur['idv'] ?>" class="btn-action edit" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <?php if ($visiteur['statut'] == 'actif'): ?>
                                <a href="/karabusiness/controllers/visiteurs.php?action=marquersortie&idv=<?= $visiteur['idv'] ?>" 
                                    class="btn-action sortie" 
                                    title="Marquer sortie"
                                    onclick="return confirm('Êtes-vous sûr de vouloir marquer la sortie de ce visiteur ?')">
                                    <i class="fa-solid fa-person-walking-arrow-right"></i>
                                </a>
                                <?php endif; ?>
                                <a href="/karabusiness/models/visiteur.php?action=archiver&idv=<?= $visiteur['idv'] ?>" 
                                    class="btn-action archive" 
                                    title="Archiver"
                                    onclick="return confirm('Êtes-vous sûr de vouloir archiver ce visiteur ?')">
                                    <i class="fa-solid fa-box-archive"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Aucun visiteur actif</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>