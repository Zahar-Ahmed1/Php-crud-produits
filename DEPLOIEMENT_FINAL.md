# 🚀 Guide de déploiement final - annrstore.com (Version complète)

## ✅ Configuration actuelle

- **Base de données** : `u899993703_produits`
- **Utilisateur** : `u899993703_prod`
- **Mot de passe** : `vegvUj-rosxo7-tycwyn`
- **Domaine** : `annrstore.com`
- **Chemin** : `/domains/annrstore.com/public_html/produits/`

## 📦 Fichiers à uploader (20 fichiers)

### Fichiers principaux (racine - 4 fichiers)
- ✅ `index.php` (interface complète avec onglets)
- ✅ `login.php` (création auto de l'utilisateur)
- ✅ `.htaccess`
- ✅ `setup-database.php` (script de création des tables - **NOUVEAU**)

### Dossier `api/` (6 fichiers)
- ✅ `api/auth.php` (authentification JWT)
- ✅ `api/products.php` (CRUD produits complet)
- ✅ `api/categories.php` (CRUD catégories - **NOUVEAU**)
- ✅ `api/products-utils.php` (utilitaires - **NOUVEAU**)
- ✅ `api/change-password.php` (changement de mot de passe)
- ✅ `api/check-first-use.php` (détection première utilisation)

### Dossier `config/` (2 fichiers)
- ✅ `config/database.php` (configuration de production)
- ✅ `config/jwt.php` (configuration JWT)

### Dossier `helpers/` (2 fichiers)
- ✅ `helpers/jwt_helper.php` (génération/validation JWT)
- ✅ `helpers/user_setup.php` (création auto de l'utilisateur)

### Dossier `middleware/` (1 fichier)
- ✅ `middleware/auth_middleware.php` (middleware d'authentification)

### Dossier `models/` (3 fichiers)
- ✅ `models/Product.php` (modèle produit complet)
- ✅ `models/Category.php` (modèle catégorie - **NOUVEAU**)
- ✅ `models/User.php` (modèle utilisateur)

## 🔧 Étapes de déploiement

### 1. Préparer la base de données

**Option A : Script PHP automatique (RECOMMANDÉ) ⭐**
1. Uploadez le fichier `setup-database.php` sur le serveur
2. Accédez à : `https://annrstore.com/produits/setup-database.php`
3. Le script crée automatiquement toutes les tables avec la structure complète
4. Supprimez le fichier après vérification (sécurité)

**Option B : Via phpMyAdmin**
1. Connectez-vous à phpMyAdmin sur Hostinger
2. Sélectionnez la base `u899993703_produits`
3. Onglet SQL → Copiez-collez le contenu de `database-annrstore.sql`
4. Cliquez sur "Exécuter"

**Option C : Automatique**
- Les tables seront créées automatiquement lors de la première utilisation de l'API
- Nécessite d'être connecté pour fonctionner

### 2. Uploader les fichiers

**Via le Gestionnaire de fichiers Hostinger :**

1. Allez sur https://hpanel.hostinger.com/
2. Ouvrez le **Gestionnaire de fichiers**
3. Naviguez vers `domains/annrstore.com/public_html/produits/`
4. **Créez les dossiers** s'ils n'existent pas :
   - `api/`
   - `config/`
   - `helpers/`
   - `middleware/`
   - `models/`
5. **Uploadez tous les fichiers** listés ci-dessus dans leurs dossiers respectifs

**Structure finale :**
```
produits/
├── index.php
├── login.php
├── .htaccess
├── api/
│   ├── auth.php
│   ├── products.php
│   ├── categories.php
│   ├── products-utils.php
│   ├── change-password.php
│   └── check-first-use.php
├── config/
│   ├── database.php
│   └── jwt.php
├── helpers/
│   ├── jwt_helper.php
│   └── user_setup.php
├── middleware/
│   └── auth_middleware.php
└── models/
    ├── Product.php
    ├── Category.php
    └── User.php
```

### 3. Configurer les permissions (via SSH)

```bash
cd ~/domains/annrstore.com/public_html/produits

# Permissions des fichiers
chmod 644 *.php
chmod 644 .htaccess

# Permissions des dossiers
chmod 755 .
chmod 755 api config helpers middleware models

# Permissions des fichiers dans les dossiers
find api config helpers middleware models -type f -exec chmod 644 {} \;
find api config helpers middleware models -type d -exec chmod 755 {} \;
```

### 4. Vérifier la configuration

**Vérifiez que `config/database.php` contient :**
```php
private $db_name = "u899993703_produits";
private $username = "u899993703_prod";
private $password = "vegvUj-rosxo7-tycwyn";
```

### 5. Tester l'application

1. **Accédez à** : `https://annrstore.com/produits/login.php`
   - L'utilisateur admin sera créé automatiquement au premier chargement
   - Les tables seront créées automatiquement si elles n'existent pas

2. **Connectez-vous avec** :
   - Username : `admin`
   - Password : `admin123`

3. **À la première connexion** :
   - Un modal s'affichera automatiquement pour changer le mot de passe
   - Choisissez un nouveau mot de passe sécurisé (minimum 6 caractères)
   - Le modal ne s'affichera plus après le changement

4. **Testez les fonctionnalités** :
   - Créer un produit avec tous les nouveaux champs
   - Gérer les catégories
   - Filtrer et rechercher les produits

## ✨ Fonctionnalités automatiques

### Création automatique
- ✅ La table `users` est créée automatiquement si elle n'existe pas
- ✅ La table `categories` est créée automatiquement si elle n'existe pas
- ✅ La table `products` est créée automatiquement si elle n'existe pas
- ✅ L'utilisateur admin est créé automatiquement si aucun utilisateur n'existe
- ✅ Plus besoin d'exécuter `setup.php` manuellement !

### Changement de mot de passe
- ✅ Détection automatique de la première utilisation
- ✅ Modal de changement de mot de passe obligatoire
- ✅ Validation du nouveau mot de passe (minimum 6 caractères)

### Structure de données
- ✅ Support complet de tous les champs du service Angular
- ✅ Images multiples, vidéos Cloudinary
- ✅ Tailles, couleurs, matériaux, tags
- ✅ Badges : new, sale, trending, bestseller
- ✅ Disponibilité : in_stock, low_stock, out_of_stock

## 📋 Endpoints API disponibles

### Produits
- `GET api/products.php` - Tous les produits
- `GET api/products.php?id={id}` - Un produit spécifique
- `GET api/products.php?action=bestsellers&limit=6` - Produits bestseller
- `GET api/products.php?action=new&limit=6` - Nouveaux produits
- `GET api/products.php?action=sale&limit=6` - Produits en promotion
- `GET api/products.php?action=search&query={term}` - Recherche
- `GET api/products.php?action=filter&category={id}&priceMin={min}&...` - Filtrage
- `GET api/products.php?categoryId={id}` - Produits par catégorie
- `POST api/products.php` - Créer un produit
- `PUT api/products.php?id={id}` - Modifier un produit
- `DELETE api/products.php?id={id}` - Supprimer un produit

### Catégories
- `GET api/categories.php` - Toutes les catégories
- `GET api/categories.php?id={id}` - Une catégorie
- `POST api/categories.php` - Créer une catégorie
- `PUT api/categories.php?id={id}` - Modifier une catégorie
- `DELETE api/categories.php?id={id}` - Supprimer une catégorie

### Utilitaires
- `GET api/products-utils.php?action=priceRange` - Plage de prix
- `GET api/products-utils.php?action=brands` - Liste des marques
- `GET api/products-utils.php?action=materials` - Liste des matériaux
- `GET api/products-utils.php?action=ageRanges` - Liste des tranches d'âge

## ✅ Checklist de vérification

### Avant le déploiement
- [ ] Tous les fichiers listés sont présents localement
- [ ] `config/database.php` contient les bonnes credentials
- [ ] `config/jwt.php` existe et contient une clé secrète

### Après le déploiement
- [ ] Tous les fichiers uploadés dans les bons dossiers
- [ ] Permissions configurées (644 pour fichiers, 755 pour dossiers)
- [ ] Connexion réussie avec `admin` / `admin123`
- [ ] Modal de changement de mot de passe affiché
- [ ] Mot de passe changé avec succès
- [ ] CRUD des produits fonctionne
- [ ] CRUD des catégories fonctionne
- [ ] Filtrage et recherche fonctionnent
- [ ] Déconnexion fonctionne

## 🆘 Dépannage

### Erreur "Erreur de connexion au serveur"
- Vérifiez que `config/database.php` contient les bons identifiants
- Vérifiez que la base de données existe dans phpMyAdmin
- Vérifiez les logs d'erreur PHP dans hPanel
- Testez la connexion via phpMyAdmin

### Erreur "Impossible de se connecter à la base de données"
- Vérifiez les credentials dans `config/database.php`
- Vérifiez que la base de données `u899993703_produits` existe
- Vérifiez que l'utilisateur `u899993703_prod` a les droits sur la base

### Erreur "Table products n'existe pas"
- **Solution rapide** : Accédez à `https://annrstore.com/produits/setup-database.php`
- Ou exécutez `database-annrstore.sql` dans phpMyAdmin
- Consultez `SETUP_DATABASE.md` pour plus de détails

### Les tables ne sont pas créées automatiquement
- Vérifiez les permissions des fichiers (chmod 644)
- Vérifiez les logs d'erreur PHP
- Créez manuellement les tables via `database.sql` dans phpMyAdmin

### L'utilisateur admin n'est pas créé
- Vérifiez que `helpers/user_setup.php` est uploadé
- Vérifiez les permissions du fichier (chmod 644)
- Vérifiez les logs d'erreur PHP

### Le modal de changement de mot de passe ne s'affiche pas
- Vérifiez que `api/check-first-use.php` est uploadé
- Ouvrez la console du navigateur (F12) pour voir les erreurs
- Vérifiez que le token JWT est valide
- Vérifiez que l'utilisateur s'appelle bien "admin"

### Erreur 500 ou page blanche
- Activez l'affichage des erreurs PHP temporairement
- Vérifiez les logs d'erreur dans hPanel
- Vérifiez que tous les fichiers sont uploadés
- Vérifiez les permissions

## 📝 URLs importantes

- **Login** : `https://annrstore.com/produits/login.php`
- **Application** : `https://annrstore.com/produits/`
- **API Auth** : `https://annrstore.com/produits/api/auth.php`
- **API Products** : `https://annrstore.com/produits/api/products.php`
- **API Categories** : `https://annrstore.com/produits/api/categories.php`

## 🔐 Sécurité

- ✅ JWT avec expiration (24h)
- ✅ Authentification requise pour toutes les opérations
- ✅ Mot de passe hashé avec bcrypt
- ✅ Changement de mot de passe obligatoire à la première connexion
- ✅ Protection CORS configurée

## 📊 Structure de la base de données

### Table `users`
- `id` (INT AUTO_INCREMENT)
- `username` (VARCHAR 100, UNIQUE)
- `password` (VARCHAR 255, hashé)
- `role` (VARCHAR 50, default 'admin')
- `created_at` (TIMESTAMP)

### Table `categories`
- `id` (VARCHAR 100, PRIMARY KEY)
- `name` (VARCHAR 255)
- `description` (TEXT)
- `image` (VARCHAR 500)
- `product_count` (INT)
- `parent_id` (VARCHAR 100, nullable)
- `created_at`, `updated_at` (TIMESTAMP)

### Table `products`
- `id` (VARCHAR 100, PRIMARY KEY)
- Tous les champs du service Angular (voir `database.sql`)

---

**Dernière mise à jour** : Version complète avec support du service Angular
