<?php
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/models/departement.php';
require_once '/xampp/htdocs/karabusiness/controllers/departements.php';
session_start();

$database = new Database();
$db = $database->getConnection();
$departementModel = new Departement($db);
$id = intval($_GET['id']);
$departement = $departementModel->getDepartementById($id);

if (!$departement) {
    header('Location: /karabusiness/views/departements/liste.php');
    exit;
}

$error = '';
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomd = trim($_POST['nomd']);
    if (empty($nomd)) {
        $error = "Le nom du département est obligatoire";
    } else {
        if ($departementModel->updateDepartement($id, $nomd)) {
            $success = true;
            $departement['nomd'] = $nomd;
        } else {
            $error = "Erreur lors de la modification du département";
        }
    }
}

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
    .page-title .fa-building-pen {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .form-card {
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(44,62,80,0.10);
        border: none;
        background: linear-gradient(135deg, #f8fafc 60%, #e0e7ef 100%);
        padding: 2.2rem 2rem 1.5rem 2rem;
        max-width: 500px;
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
        <i class="fa-solid fa-building-pen"></i>
        Modifier un département
    </div>
    <div class="form-card">
        <?php if ($success): ?>
        <div class="alert alert-success">
            Département modifié avec succès! 
            <a href="liste.php" class="alert-link">Retour à la liste</a>
        </div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= $error; ?>
        </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="nomd" class="form-label"><i class="fa-solid fa-building"></i> Nom du département</label>
                <input type="text" class="form-control" id="nomd" name="nomd" value="<?= htmlspecialchars($departement['nomd']) ?>" required>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-orange"><i class="fa-solid fa-floppy-disk me-1"></i>Enregistrer</button>
                <a href="liste.php" class="btn btn-secondary"><i class="fa-solid fa-xmark me-1"></i>Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>