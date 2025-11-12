<?php
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/visiteur.php';
session_start();
// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$visiteurModel = new Visiteur($db);

// Récupération des visiteurs archivés
$visiteurs = $visiteurModel->getArchives();

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
    .page-title .fa-archive {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .table-archives {
        border: 2px solid #b0b8c9;
        border-radius: 0.7rem;
        overflow: hidden;
        background: #fff;
        margin-bottom: 2rem;
    }
    .table-archives th, .table-archives td {
        border-bottom: 1.5px solid #b0b8c9 !important;
        vertical-align: middle !important;
    }
    .table-archives thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .table-archives tbody tr:hover {
        background: #fdf6e3;
        transition: background 0.2s;
    }
    .restore-btn {
        background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        color: #1a3a2b;
        border: none;
        border-radius: 0.5em;
        padding: 0.35em 1em;
        font-size: 0.98rem;
        font-weight: 600;
        box-shadow: 0 2px 8px 0 rgba(67,233,123,0.07);
        transition: background 0.2s, color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
    }
    .restore-btn:hover {
        background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
        color: #fff;
    }
    .back-btn {
        background: linear-gradient(90deg, #e67e22 0%, #f7971e 100%);
        color: #fff;
        border: none;
        border-radius: 0.5em;
        padding: 0.5em 1.2em;
        font-size: 1.05rem;
        font-weight: 600;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
    }
    .back-btn:hover {
        background: linear-gradient(90deg, #f7971e 0%, #e67e22 100%);
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="page-title">
            <i class="fa-solid fa-archive"></i>
            Archives des visiteurs
        </span>
        <a href="liste.php" class="back-btn">
            <i class="fa-solid fa-arrow-left me-1"></i> Retourner à la liste des visiteurs
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
        <table class="table table-archives align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><i class="fa-solid fa-user"></i> Nom</th>
                    <th><i class="fa-solid fa-signature"></i> Prénom</th>
                    <th><i class="fa-solid fa-phone"></i> Téléphone</th>
                    <th><i class="fa-solid fa-user-tie"></i> Employé visité</th>
                    <th><i class="fa-solid fa-door-open"></i> Date entrée</th>
                    <th><i class="fa-solid fa-door-closed"></i> Date sortie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($visiteurs) > 0): ?>
                    <?php foreach ($visiteurs as $visiteur): ?>
                        <tr>
                            <td><?= $visiteur['idv'] ?></td>
                            <td><?= htmlspecialchars($visiteur['nom']) ?></td>
                            <td><?= htmlspecialchars($visiteur['prenom']) ?></td>
                            <td><?= htmlspecialchars($visiteur['telephone']) ?></td>
                            <td><?= htmlspecialchars($visiteur['employe_nom'] . ' ' . $visiteur['employe_prenom']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($visiteur['date_entree'])) ?></td>
                            <td><?= $visiteur['date_sortie'] ? date('d/m/Y H:i', strtotime($visiteur['date_sortie'])) : '-' ?></td>
                            <td>
                                <a href="archivage.php?action=restore&idv=<?= $visiteur['idv'] ?>" 
                                   class="restore-btn"
                                   onclick="return confirm('Êtes-vous sûr de vouloir restaurer ce visiteur ?')">
                                    <i class="fa-solid fa-rotate-left"></i> Restaurer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun visiteur archivé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>