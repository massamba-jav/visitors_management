<?php
// ---- tableau de bord ----
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/config/auth.php';
session_start();

// connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$auth = new Authentifi($db);
if (!$auth->isLoggedIn()) {
    header('Location: /karabusiness/index.php');
    exit;
}
// statistiques: nombre total de visiteurs
$query = "SELECT COUNT(*) as total FROM visiteurs";
$stmt = $db->prepare($query);
$stmt->execute();
$total_visiteurs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// statistiques: visiteurs aujourd'hui
$query = "SELECT COUNT(*) as aujourd_hui FROM visiteurs WHERE DATE(date_entree) = CURDATE()";
$stmt = $db->prepare($query);
$stmt->execute();
$visiteurs_aujourd_hui = $stmt->fetch(PDO::FETCH_ASSOC)['aujourd_hui'];

// statistiques: visiteurs actuellement présents
$query = "SELECT COUNT(*) as presents FROM visiteurs WHERE date_sortie IS NULL AND statut = 'actif'";
$stmt = $db->prepare($query);
$stmt->execute();
$visiteurs_presents = $stmt->fetch(PDO::FETCH_ASSOC)['presents'];

// derniers visiteurs enregistrés
$query = "SELECT v.*, e.nom as employe_nom, e.prenom as employe_prenom 
          FROM visiteurs v 
          LEFT JOIN employes e ON v.ide = e.ide  
          ORDER BY v.date_entree DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$derniers_visiteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '/xampp/htdocs/karabusiness/includes/header.php';
?>

<!-- Polices modernes -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f6f9fc;
        color: #2c3e50;
    }

    .dashboard-card {
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(44,62,80,0.10);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        background: linear-gradient(135deg, #f8fafc 60%, #e0e7ef 100%);
        position: relative;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px 0 rgba(44,62,80,0.18);
    }

    .dashboard-card:active {
        transform: scale(0.98);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .dashboard-icon {
        font-size: 2.5rem;
        padding: 18px;
        border-radius: 50%;
        color: #fff;
        position: absolute;
        top: -30px;
        right: 24px;
        opacity: 0.15;
        pointer-events: none;
        transition: transform 0.4s ease;
    }

    .dashboard-card:hover .dashboard-icon {
        transform: rotate(10deg) scale(1.1);
    }

    .dashboard-badge {
        font-size: 0.95rem;
        padding: 0.4em 1em;
        border-radius: 1em;
        font-weight: 600;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        transition: all 0.2s ease-in-out;
    }

    .dashboard-badge:hover {
        filter: brightness(1.1);
        cursor: pointer;
    }

    .badge-present {
        background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        color: #1a3a2b;
    }

    .badge-out {
        background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        color: #7a4e00;
    }

    .badge-absent {
        background: linear-gradient(90deg, #f953c6 0%, #b91d73 100%);
        color: #fff;
    }

    .table-dashboard {
        border: 1.5px solid #b0b8c9;
        border-radius: 0.7rem;
        overflow: hidden;
    }

    .table-dashboard th, .table-dashboard td {
        border-bottom: 1.5px solid #b0b8c9 !important;
    }

    .table-dashboard thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
    }

    .table-dashboard tbody tr {
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .table-dashboard tbody tr:hover {
        background: #eef6ff;
        transform: scale(1.005);
    }

    .table-dashboard td, .table-dashboard th {
        vertical-align: middle !important;
    }

    .table-dashboard .fa-user {
        font-size: 1.3rem;
        margin-right: 0.5em;
    }

    .refresh-btn {
        background: none;
        border: none;
        color: #2563eb;
        font-weight: 600;
        transition: color 0.2s;
    }

    .refresh-btn:hover {
        color: #e67e22;
        text-decoration: underline;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #a1aab9;
        border-radius: 10px;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark" style="font-size:2.2rem;letter-spacing:1px;">
            <i class="fa-solid fa-chart-line me-2 text-primary"></i>Tableau de bord
        </h1>
        <div class="text-secondary" style="font-size:1.1rem;">
            <i class="fa-regular fa-calendar-days me-1"></i>
            <span id="current-date"></span>
            <span id="current-time" class="ms-2 text-muted" style="font-size:1rem;"></span>
        </div>
    </div>

    <div id="loader" style="position:fixed;top:0;left:0;width:100vw;height:100vh;display:flex;align-items:center;justify-content:center;background:#fff;z-index:9999;">
        <l-trefoil size="80" stroke="4" stroke-length="0.15" bg-opacity="0.1" speed="1.4" color="#e67e22"></l-trefoil>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card p-4 position-relative">
                <i class="fa-solid fa-users dashboard-icon" style="background:#2563eb;"></i>
                <div class="mb-2 text-muted">Total des visiteurs</div>
                <div class="fw-bold" style="font-size:2.5rem;"><?php echo $total_visiteurs; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card p-4 position-relative">
                <i class="fa-solid fa-calendar-day dashboard-icon" style="background:#43e97b;"></i>
                <div class="mb-2 text-muted">Visiteurs aujourd'hui</div>
                <div class="fw-bold" style="font-size:2.5rem;"><?php echo $visiteurs_aujourd_hui; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card p-4 position-relative">
                <i class="fa-solid fa-user-clock dashboard-icon" style="background:#f7971e;"></i>
                <div class="mb-2 text-muted">Visiteurs présents</div>
                <div class="fw-bold" style="font-size:2.5rem;"><?php echo $visiteurs_presents; ?></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-users-viewfinder me-2 text-primary"></i>Derniers visiteurs enregistrés</h5>
            <button class="refresh-btn" onclick="handleRefresh(this)">
                <i class="fa-solid fa-arrows-rotate me-1"></i>Actualiser
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dashboard align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Employé visité</th>
                            <th>Date entrée</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($derniers_visiteurs as $visiteur): ?>
                        <tr>
                            <td>
                                <span class="badge rounded-pill bg-primary-subtle me-2">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <span class="fw-semibold"><?php echo htmlspecialchars($visiteur['nom'] . ' ' . $visiteur['prenom']); ?></span>
                            </td>
                            <td>
                                <span class="text-dark"><?php echo htmlspecialchars($visiteur['telephone']); ?></span>
                            </td>
                            <td>
                                <span class="fw-semibold"><?php echo htmlspecialchars($visiteur['employe_nom'] . ' ' . $visiteur['employe_prenom']); ?></span>
                            </td>
                            <td>
                                <span class="text-dark"><?php echo date('d/m/Y H:i', strtotime($visiteur['date_entree'])); ?></span>
                            </td>
                            <td>
                                <?php if ($visiteur['date_sortie']): ?>
                                    <span class="dashboard-badge badge-out">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sorti
                                    </span>
                                <?php else: ?>
                                    <span class="dashboard-badge badge-present">
                                        <i class="fa-solid fa-circle-dot"></i> Présent
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Affichage date + heure
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('current-date').textContent = now.toLocaleDateString('fr-FR', options);

    function updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    updateTime();
    setInterval(updateTime, 10000);

    // Animation de disparition du loader
    // window.addEventListener('load', function () {
    //     const loader = document.getElementById('loader');
    //     loader.style.transition = "opacity 0.6s ease";
    //     loader.style.opacity = 0;
    //     setTimeout(() => {
    //         loader.style.display = "none";
    //     }, 700);
    // });

    function handleRefresh(btn) {
        const icon = btn.querySelector('i');
        icon.classList.add('fa-spin');
        setTimeout(() => location.reload(), 600);
    }
</script>

<?php include_once '/xampp/htdocs/karabusiness/includes/footer.php'; ?>
