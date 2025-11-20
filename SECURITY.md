# Sécurité JWT - Guide d'utilisation

## Authentification JWT

L'application utilise maintenant l'authentification JWT (JSON Web Tokens) pour sécuriser l'accès à l'API.

## Identifiants par défaut

- **Username** : `admin`
- **Password** : `admin123`

⚠️ **IMPORTANT** : Changez le mot de passe par défaut après la première connexion !

## Fonctionnement

### 1. Connexion

1. Accédez à `login.php`
2. Entrez vos identifiants
3. Un token JWT est généré et stocké dans le localStorage du navigateur
4. Vous êtes redirigé vers l'application principale

### 2. Utilisation de l'API

Toutes les requêtes vers `api/products.php` nécessitent maintenant un token JWT valide dans le header :

```
Authorization: Bearer <votre_token_jwt>
```

Le token est automatiquement inclus dans toutes les requêtes depuis l'interface web.

### 3. Expiration du token

Les tokens JWT expirent après **24 heures**. Si votre token expire :
- Vous serez automatiquement déconnecté
- Vous devrez vous reconnecter via `login.php`

## Structure des fichiers de sécurité

```
app-php/
├── config/
│   └── jwt.php                    # Configuration JWT (clé secrète, expiration)
├── helpers/
│   └── jwt_helper.php            # Fonctions pour générer/valider les tokens
├── middleware/
│   └── auth_middleware.php        # Middleware pour protéger les routes
├── models/
│   └── User.php                  # Modèle User pour l'authentification
├── api/
│   ├── auth.php                  # API de connexion
│   └── products.php              # API protégée (nécessite JWT)
└── login.php                     # Interface de connexion
```

## Changer la clé secrète JWT

Pour des raisons de sécurité, changez la clé secrète dans `config/jwt.php` :

```php
const SECRET_KEY = "votre_nouvelle_cle_secrete_tres_longue_et_aleatoire";
```

⚠️ **Attention** : Si vous changez la clé, tous les tokens existants deviendront invalides.

## Créer un nouvel utilisateur

Pour créer un nouvel utilisateur, vous pouvez :

1. **Via SQL direct** :
```sql
INSERT INTO users (username, password, role) 
VALUES ('nouvel_utilisateur', '$2y$10$...', 'admin');
```

2. **Via PHP** :
```php
require_once 'models/User.php';
$user = new User();
$user->username = 'nouvel_utilisateur';
$user->password = 'mot_de_passe';
$user->role = 'admin';
$user->create();
```

## Sécurité

- ✅ Mots de passe hashés avec `password_hash()` (bcrypt)
- ✅ Tokens JWT signés avec HMAC SHA-256
- ✅ Expiration automatique des tokens (24h)
- ✅ Protection contre les injections SQL (PDO préparé)
- ✅ Validation des tokens à chaque requête API

## Dépannage

### Erreur 401 "Authentification requise"
- Vérifiez que vous êtes connecté
- Vérifiez que le token n'a pas expiré
- Reconnectez-vous via `login.php`

### Token invalide
- Le token peut être expiré (24h)
- La clé secrète peut avoir changé
- Le format du token peut être incorrect

### Impossible de se connecter
- Vérifiez que la table `users` existe
- Vérifiez que l'utilisateur existe dans la base de données
- Exécutez `setup.php` pour créer la table et l'utilisateur par défaut

