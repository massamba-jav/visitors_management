</main>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
                :root {
            --primary-color:rgb(0, 0, 0);
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --light-bg: #f8f9fa;
        }
    
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1 0 auto;
        }
    
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
    
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
    
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1569025698325-0e7e0ce9ba4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 40px;
        }
    
        .article-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    
        .article-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
    
        .article-img {
            height: 200px;
            object-fit: cover;
        }
    
        .badge-custom {
            background-color: var(--accent-color);
            color: white;
        }
    
        .list-icon {
            color: var(--secondary-color);
            margin-right: 10px;
        }
    
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            flex-shrink: 0;
        }
    
        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s;
        }
    
        .social-icons a:hover {
            color: var(--accent-color);
        }
    
        .btn-custom {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
    
        .btn-custom:hover {
            background-color: #c0392b;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
    </div> <!-- Si vous avez ouvert un .main-content dans le header ou la page -->
    <footer class="text-center">
        <div class="container">
            <div class="social-icons mb-4">
                <a href="https://www.facebook.com/share/15pEVtvrBc/?mibextid=wwXIfr"><i class="fab fa-facebook"></i></a>
                <a href="https://x.com/massamba2211?s=21"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/jnvqpd22?igsh=MXZuMDU1MHp6b3RrNQ%3D%3D&utm_source=qr"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.me/221777578012"><i class="fab fa-whatsapp"></i></a>
            </div>
            <p class="mb-2">© 2025 Massamba Diagne & KaraBusiness. Tous droits réservés. Contact : +221 77 757 80 12</p>
            <p class="mb-0">Gestionnaires agréés de visites à Dakar et au Sénégal</p>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/trefoil.js"></script>
    <script>
        window.addEventListener('load', function() {
        setTimeout(function() {
            var loader = document.getElementById('loader');
            if(loader) loader.style.display = 'none';
        }, 1500);
        });
    </script>

</body>
</html>