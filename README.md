# Application PHP CRUD - Gestion de Produits

Application PHP complète pour la gestion CRUD (Create, Read, Update, Delete) des produits, basée sur le service Angular fourni.

## Structure du projet

```
app-php/
├── api/
│   └── products.php          # API REST pour les opérations CRUD
├── config/
│   └── database.php          # Configuration de la base de données
├── models/
│   └── Product.php           # Modèle Product avec les méthodes CRUD
├── database.sql              # Script SQL pour créer la base de données
├── index.php                 # Interface utilisateur HTML/PHP
├── .htaccess                 # Configuration Apache pour le routing
└── README.md                 # Ce fichier
```

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur (ou MariaDB)
- Serveur web (Apache avec mod_rewrite activé, ou Nginx)
- Extension PHP PDO activée

## Installation

### 1. Configuration de la base de données

1. Créez une base de données MySQL :
```bash
mysql -u root -p < database.sql
```

Ou importez le fichier `database.sql` via phpMyAdmin ou votre outil de gestion MySQL préféré.

### 2. Configuration de la connexion

Modifiez le fichier `config/database.php` avec vos paramètres de connexion :

```php
private $host = "localhost";        // Votre hôte MySQL
private $db_name = "products_db";   // Nom de la base de données
private $username = "root";          // Votre nom d'utilisateur MySQL
private $password = "";              // Votre mot de passe MySQL
```

### 3. Configuration du serveur web

#### Apache

Assurez-vous que `mod_rewrite` est activé :
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Le fichier `.htaccess` est déjà configuré pour le routing REST.

#### Nginx

Si vous utilisez Nginx, ajoutez cette configuration dans votre bloc `server` :

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location /api/products {
    try_files $uri $uri/ /api/products.php?$query_string;
}
```

### 4. Démarrage

1. Placez les fichiers dans le répertoire de votre serveur web (par exemple `/var/www/html/` ou `htdocs/`)
2. Accédez à l'application via votre navigateur : `http://localhost/app-php/`

## Utilisation

### Interface Web

L'interface web (`index.php`) permet de :
- **Créer** de nouveaux produits via le formulaire
- **Lire** tous les produits dans un tableau
- **Modifier** un produit en cliquant sur le bouton d'édition
- **Supprimer** un produit en cliquant sur le bouton de suppression

### API REST

L'API REST est accessible via `api/products.php` :

#### Récupérer tous les produits
```http
GET /api/products.php
```

#### Récupérer un produit par ID
```http
GET /api/products.php?id=1
```
ou
```http
GET /api/products/1
```

#### Créer un produit
```http
POST /api/products.php
Content-Type: application/json

{
  "name": "Nouveau produit",
  "category": "Catégorie",
  "description": "Description du produit",
  "price": 10.99,
  "originalPrice": 12.99,
  "discount": 15,
  "rating": 4.5,
  "reviews": 100,
  "stock": 50,
  "badge": "new"
}
```

#### Mettre à jour un produit
```http
PUT /api/products.php?id=1
Content-Type: application/json

{
  "name": "Produit modifié",
  "category": "Catégorie",
  "price": 9.99,
  "stock": 30
}
```

#### Supprimer un produit
```http
DELETE /api/products.php?id=1
```

## Structure des données

### Produit (Product)

| Champ | Type | Description |
|-------|------|-------------|
| id | INT | Identifiant unique (auto-incrémenté) |
| name | VARCHAR(255) | Nom du produit |
| category | VARCHAR(100) | Catégorie du produit |
| description | TEXT | Description détaillée |
| price | DECIMAL(10,2) | Prix actuel |
| original_price | DECIMAL(10,2) | Prix original (optionnel) |
| discount | INT | Pourcentage de remise (optionnel) |
| rating | DECIMAL(3,1) | Note sur 5 |
| reviews | INT | Nombre d'avis |
| stock | INT | Quantité en stock |
| badge | ENUM | Badge : 'promo', 'best-seller', 'new' (optionnel) |

## Fonctionnalités

- ✅ CRUD complet (Create, Read, Update, Delete)
- ✅ Interface utilisateur moderne avec Bootstrap 5
- ✅ API REST complète
- ✅ Validation des données
- ✅ Protection contre les injections SQL (PDO avec requêtes préparées)
- ✅ Support des caractères Unicode (UTF-8)
- ✅ Gestion des erreurs
- ✅ Responsive design

## Dépannage

### Erreur de connexion à la base de données

Vérifiez :
- Que MySQL/MariaDB est démarré
- Les identifiants dans `config/database.php`
- Que la base de données `products_db` existe

### Erreur 404 sur les routes API

- Vérifiez que `mod_rewrite` est activé (Apache)
- Vérifiez la configuration de `.htaccess`
- Vérifiez les permissions du fichier `.htaccess`

### Erreurs PHP

Activez l'affichage des erreurs dans `php.ini` :
```ini
display_errors = On
error_reporting = E_ALL
```

## Licence

Ce projet est fourni tel quel pour un usage éducatif et de développement.

