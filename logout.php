<?php
// ---- déconnexion ----
require_once '/xampp/htdocs/karabusiness/config/auth.php';
require_once '/xampp/htdocs/karabusiness/config/database.php';
session_start();
$db = new Database();
$auth = new Authentifi($db->getConnection());
$auth->logout(); // cette fonction gère déjà la redirection