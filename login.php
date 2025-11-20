<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-shield-lock"></i>
            <h2>Connexion</h2>
            <p class="text-muted">Accès administrateur</p>
        </div>
        
        <form id="login-form">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            
            <div id="error-message" class="alert alert-danger" style="display: none;" role="alert"></div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>
        </form>
        
        <div class="text-center text-muted small">
            <p>Identifiants par défaut :<br>
            <strong>admin</strong> / <strong>admin123</strong></p>
        </div>
    </div>

    <?php
    // Créer l'utilisateur admin au démarrage si nécessaire
    require_once __DIR__ . '/helpers/user_setup.php';
    UserSetup::ensureAdminExists();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('error-message');
            
            // Masquer l'erreur précédente
            errorDiv.style.display = 'none';
            
            fetch('api/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.token) {
                    // Stocker le token dans localStorage
                    localStorage.setItem('jwt_token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Rediriger vers l'application
                    window.location.href = 'index.php';
                } else {
                    errorDiv.textContent = data.message || 'Erreur de connexion';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                errorDiv.textContent = 'Erreur de connexion au serveur';
                errorDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>

