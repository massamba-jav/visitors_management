<?php
// Sécurité : cette page n'est accessible qu'aux administrateurs
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/controllers/utilisateurs.php';
require_once '/xampp/htdocs/karabusiness/config/auth.php';
require_once '/xampp/htdocs/karabusiness/models/utilisateur.php';

$database = new Database();
$db = $database->getConnection();
$utilisateurModel = new Utilisateur($db);

// Récupération de la liste des utilisateurs
$utilisateurs = $utilisateurModel->getUtilisateurs();

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
    .page-title .fa-users-cog {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .table-users {
        border: 2px solid #b0b8c9;
        border-radius: 0.7rem;
        overflow: hidden;
        background: #fff;
        margin-bottom: 2rem;
    }
    .table-users th, .table-users td {
        border-bottom: 1.5px solid #b0b8c9 !important;
        vertical-align: middle !important;
    }
    .table-users thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .table-users tbody tr:hover {
        background: #fdf6e3;
        transition: background 0.2s;
    }
    .role-badge {
        padding: 0.35em 1em;
        border-radius: 1em;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        box-shadow: 0 2px 8px 0 rgba(230,126,34,0.07);
    }
    .role-admin {
        background: linear-gradient(90deg, #e67e22 0%, #f7971e 100%);
        color: #fff;
    }
    .role-user {
        background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        color: #1a3a2b;
    }
    .role-guest {
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
            <i class="fa-solid fa-users-cog"></i>
            Gestion des utilisateurs
        </span>
        <a href="ajouter.php" class="add-btn">
            <i class="fa-solid fa-user-plus me-1"></i> Ajouter un utilisateur
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> fw-semibold">
            <?= $_SESSION['message'] ?>
        </div>
        <?php 
            unset($_SESSION['message']); 
            unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-users align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><i class="fa-solid fa-user"></i> Nom d'utilisateur</th>
                    <th><i class="fa-solid fa-id-card"></i> Nom</th>
                    <th><i class="fa-solid fa-signature"></i> Prénom</th>
                    <th><i class="fa-solid fa-envelope"></i> Email</th>
                    <th><i class="fa-solid fa-user-shield"></i> Rôle</th>
                    <th><i class="fa-solid fa-clock"></i> Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= $utilisateur['idu'] ?></td>
                    <td><?= htmlspecialchars($utilisateur['username']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td>
                        <?php
                            $role = strtolower($utilisateur['role']);
                            $badgeClass = 'role-user';
                            $icon = 'fa-user';
                            if ($role === 'admin') { $badgeClass = 'role-admin'; $icon = 'fa-user-shield'; }
                            elseif ($role === 'guest') { $badgeClass = 'role-guest'; $icon = 'fa-user-secret'; }
                        ?>
                        <span class="role-badge <?= $badgeClass ?>">
                            <i class="fa-solid <?= $icon ?>"></i> <?= ucfirst($utilisateur['role']) ?>
                        </span>
                    </td>
                    <td><?= $utilisateur['lastlogin'] ? date('d/m/Y H:i', strtotime($utilisateur['lastlogin'])) : 'Jamais' ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $utilisateur['idu'] ?>" class="btn-action edit" title="Modifier">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/karabusiness/controllers/utilisateurs.php?action=supprimer&id=<?= $utilisateur['idu'] ?>"
                           class="btn-action delete"
                           title="Supprimer"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>