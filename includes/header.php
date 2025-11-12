<?php
// ---- en-tête du site ----
require_once '/xampp/htdocs/karabusiness/config/auth.php';
$db = new Database();
$auth = new Authentifi($db->getConnection());
$auth->requireLogin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KaraBusiness - Gestion des Visiteurs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS (optionnel, à adapter selon vos besoins) -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            color: #2c3e50 !important;
        }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
            color: #e74c3c !important;
        }
        .container-fluid {
            margin-top: 30px;
        }
                /* Loader overlay */
                #loader {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        }

        /* Agrandir le SVG du loader */
        #loader svg {
        width: 120px !important;
        height: 120px !important;
        display: block;
        }

        /* Animation pour la voiture */
        #loader .car {
        stroke: #e74c3c;
        stroke-width: 4;
        fill: none;
        stroke-dasharray: 15, 85;
        stroke-dashoffset: 0;
        stroke-linecap: round;
        animation: travel 1.4s linear infinite;
        }

        #loader .track {
        stroke: #e67e22;
        stroke-width: 4;
        fill: none;
        opacity: 0.15;
        }

        @keyframes travel {
        0% { stroke-dashoffset: 0; }
        100% { stroke-dashoffset: -100; }
        }

    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="main-content">
    <?php include_once '/xampp/htdocs/karabusiness/includes/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4"></main>