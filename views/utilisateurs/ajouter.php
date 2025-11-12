<?php
// Sécurité : cette page n'est accessible qu'aux administrateurs


require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/controllers/utilisateurs.php';
require_once '/xampp/htdocs/karabusiness/models/utilisateur.php';
$database = new Database();
$db = $database->getConnection();
$utilisateurModel = new Utilisateur($db);
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $utilisateurModel->createUtilisateur(
        $_POST['username'],
        $_POST['password'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['role']
    );
    if ($result === true) {
        $_SESSION['message'] = 'Utilisateur ajouté avec succès';
        $_SESSION['message_type'] = 'success';
        header('Location: /karabusiness/views/utilisateurs/liste.php');
        exit;
    } else {
        $error = $result;
    }
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
    .page-title .fa-user-plus {
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
        <i class="fa-solid fa-user-plus"></i>
        Ajouter un utilisateur
    </div>
    <div class="form-card">
        <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fa-solid fa-user"></i> Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fa-solid fa-lock"></i> Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label"><i class="fa-solid fa-id-card"></i> Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label"><i class="fa-solid fa-signature"></i> Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label"><i class="fa-solid fa-user-shield"></i> Rôle</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="" disabled selected>Sélectionner un rôle</option>
                    <option value="user">Utilisateur standard</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-orange"><i class="fa-solid fa-plus me-1"></i>Enregistrer</button>
                <a href="liste.php" class="btn btn-secondary"><i class="fa-solid fa-xmark me-1"></i>Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>