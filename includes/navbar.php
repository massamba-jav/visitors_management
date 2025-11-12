
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/karabusiness/dashboard.php">
            <i class="fas fa-hand-holding-usd me-2"></i>KaraBusiness
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/karabusiness/dashboard.php">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/visiteurs/liste.php">Visiteurs</a></li>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/visiteurs/ajouter.php">Ajouter un visiteur</a></li>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/visiteurs/archivage.php">Archives</a></li>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/departements/liste.php">Départements</a></li>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/employes/liste.php">Employés</a></li>
                <?php if (isset($auth) && $auth->isAdmin()): ?>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/views/utilisateurs/liste.php">Utilisateurs</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="/karabusiness/statistiques.php">Statistiques</a></li>
                <li class="nav-item">
                    <span class="nav-link">Bienvenue, <?= $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']; ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/karabusiness/logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>