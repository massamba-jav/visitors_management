<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /karabusiness/index.php');
    exit;
}
require_once '/xampp/htdocs/karabusiness/controllers/statistiques.php';
require_once '/xampp/htdocs/karabusiness/config/database.php';
require_once '/xampp/htdocs/karabusiness/controllers/visiteurs.php';
$stas = new statistiques();
// Récupération des statistiques
$statsJour = $stas->getStatsVisitesJour();
$statsSemaine = $stas->getStatsVisitesSemaine();
$statsMois = $stas->getStatsVisitesMois();

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
    .page-title .fa-chart-bar {
        color: #e67e22;
        font-size: 2.2rem;
    }
    .stat-card {
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(44,62,80,0.10);
        border: none;
        background: linear-gradient(135deg, #f8fafc 60%, #e0e7ef 100%);
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px 0 rgba(44,62,80,0.18);
    }
    .stat-icon {
        font-size: 2.5rem;
        padding: 18px;
        border-radius: 50%;
        color: #fff;
        position: absolute;
        top: -30px;
        right: 24px;
        opacity: 0.15;
        pointer-events: none;
    }
    .table-stat th, .table-stat td {
        border-bottom: 1.5px solid #b0b8c9 !important;
        vertical-align: middle !important;
    }
    .table-stat thead th {
        background: #f5f7fa;
        border-bottom: 2px solid #7b879c !important;
        color: #2c3e50;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .table-stat tbody tr:hover {
        background: #fdf6e3;
        transition: background 0.2s;
    }
    .card-header {
        font-weight: 600;
        font-size: 1.1rem;
        background: #f8fafc;
        border-bottom: 1.5px solid #e0e7ef;
    }
    .chart-container {
        min-height: 260px;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px 0 rgba(44,62,80,0.06);
        padding: 1.2rem 1rem 0.5rem 1rem;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="page-title">
            <i class="fa-solid fa-chart-bar"></i>
            Statistiques des visites
        </span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card p-4 position-relative">
                <i class="fa-solid fa-calendar-day stat-icon" style="background:#43e97b;"></i>
                <div class="mb-2 text-muted">Visites aujourd'hui</div>
                <div class="fw-bold" style="font-size:2.2rem;" id="visitesAujourdhui">Chargement...</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4 position-relative">
                <i class="fa-solid fa-users stat-icon" style="background:#2563eb;"></i>
                <div class="mb-2 text-muted">Total des visites</div>
                <div class="fw-bold" style="font-size:2.2rem;" id="totalVisites">Chargement...</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4 position-relative">
                <i class="fa-solid fa-user-clock stat-icon" style="background:#f7971e;"></i>
                <div class="mb-2 text-muted">Visiteurs présents</div>
                <div class="fw-bold" style="font-size:2.2rem;" id="visiteursPresents">Chargement...</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="chart-container">
                <div class="card-header mb-2"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Visites par jour</div>
                <canvas id="chartJour"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-container">
                <div class="card-header mb-2"><i class="fa-solid fa-chart-bar me-2 text-success"></i>Visites par semaine</div>
                <canvas id="chartSemaine"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="chart-container">
                <div class="card-header mb-2"><i class="fa-solid fa-chart-pie me-2 text-warning"></i>Visites par mois</div>
                <canvas id="chartMois"></canvas>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <i class="fa-solid fa-list-ol me-2 text-info"></i>Résumé des statistiques
                </div>
                <div class="card-body">
                    <table class="table table-stat mb-0">
                        <tr>
                            <th>Total des visites</th>
                            <td id="totalVisites2">Chargement...</td>
                        </tr>
                        <tr>
                            <th>Visites aujourd'hui</th>
                            <td id="visitesAujourdhui2">Chargement...</td>
                        </tr>
                        <tr>
                            <th>Visiteurs actuellement présents</th>
                            <td id="visiteursPresents2">Chargement...</td>
                        </tr>
                        <tr>
                            <th>Moyenne de visites par jour</th>
                            <td id="moyenneVisites">Chargement...</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Conversion des données PHP en format pour Chart.js
const statsJour = <?= json_encode($statsJour) ?>;
const statsSemaine = <?= json_encode($statsSemaine) ?>;
const statsMois = <?= json_encode($statsMois) ?>;

// Fonction pour créer un graphique à barres
function createBarChart(elementId, labels, data, title) {
    const ctx = document.getElementById(elementId).getContext('2d');
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: title,
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

// Création des graphiques
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des visites par jour
    const jourLabels = statsJour.map(item => item.jour);
    const jourData = statsJour.map(item => item.nombre);
    createBarChart('chartJour', jourLabels, jourData, 'Nombre de visites');
    
    // Graphique des visites par semaine
    const semaineLabels = statsSemaine.map(item => 'Semaine ' + item.semaine);
    const semaineData = statsSemaine.map(item => item.nombre);
    createBarChart('chartSemaine', semaineLabels, semaineData, 'Nombre de visites');
    
    // Graphique des visites par mois
    const moisLabels = statsMois.map(item => item.mois);
    const moisData = statsMois.map(item => item.nombre);
    createBarChart('chartMois', moisLabels, moisData, 'Nombre de visites');
    
    // Requête API pour les statistiques en temps réel
    fetch('controllers/statistiques.php?action=resume')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalVisites').textContent = data.total;
            document.getElementById('visitesAujourdhui').textContent = data.aujourd_hui;
            document.getElementById('visiteursPresents').textContent = data.presents;
            document.getElementById('totalVisites2').textContent = data.total;
            document.getElementById('visitesAujourdhui2').textContent = data.aujourd_hui;
            document.getElementById('visiteursPresents2').textContent = data.presents;
            document.getElementById('moyenneVisites').textContent = data.moyenne_jour.toFixed(2);
        });
});
</script>

<?php include '/xampp/htdocs/karabusiness/includes/footer.php'; ?>