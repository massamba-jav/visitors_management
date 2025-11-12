<?php
// ---- page de connexion ----
session_start();
require_once '/xampp/htdocs/karabusiness/config/auth.php';
require_once '/xampp/htdocs/karabusiness/models/utilisateur.php';
require_once '/xampp/htdocs/karabusiness/config/database.php';

$db = new Database();
$utilisateur = new Utilisateur($db->getConnection());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($utilisateur->login($username, $password)) {
        $_SESSION['user_nom'] = $utilisateur->login($username, $password)['user_nom'];
        $_SESSION['user_prenom'] = $utilisateur->login($username, $password)['user_prenom'];
        header("Location: /karabusiness/dashboard.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - KaraBusiness</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(120deg, #f8fafc 0%, #e0e7ef 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 1.5px;
            margin-bottom: 0.5rem;
        }

        .logo-section p {
            font-size: 1rem;
            color: #e67e22;
            font-style: italic;
            font-weight: 500;
        }

        .form-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #e67e22;
            font-size: 0.95rem;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #f9f9f9;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #e67e22;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #e67e22;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            color: #5a6c7d;
            cursor: pointer;
            margin: 0;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 0;
            background: linear-gradient(90deg, #e74c3c 0%, #f39c12 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.08);
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #f39c12 0%, #e74c3c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.15);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .error-message {
            background: #ffeaea;
            border: 1.5px solid #b00020;
            color: #b00020;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            text-align: center;
            font-size: 0.95rem;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        @media (max-width: 480px) {
            .logo-section h1 {
                font-size: 2rem;
            }

            .form-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div id="loader">
        <l-trefoil
            size="80"
            stroke="4"
            stroke-length="0.15"
            bg-opacity="0.1"
            speed="1.4"
            color="#e67e22">
        </l-trefoil>
    </div>

    <div class="login-wrapper">
        <div class="logo-section">
            <h1><i class="fas fa-hand-holding-usd" style="color: #e67e22;"></i> KaraBusiness</h1>
            <p>Work hard or give up</p>
        </div>

        <div class="form-card">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Entrez votre nom d'utilisateur"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez votre mot de passe"
                        autocomplete="current-password"
                        required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="subscribe" name="subscribe" required>
                    <label for="subscribe">J'accepte la politique de confidentialité</label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Connexion
                </button>
            </form>
        </div>
    </div>

    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/trefoil.js"></script>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                if (loader) loader.style.display = 'none';
            }, 1500);
        });
    </script>
</body>
</html>