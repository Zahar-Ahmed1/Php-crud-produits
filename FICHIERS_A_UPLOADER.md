# 📦 Liste complète des fichiers à uploader pour annrstore.com

## Structure complète

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

## Fichiers à uploader (20 fichiers)

### ✅ Fichiers racine (4 fichiers)

1. **`index.php`**
   - Interface complète avec onglets
   - Gestion de tous les champs produits
   - Modal de changement de mot de passe

2. **`login.php`**
   - Page de connexion
   - Création automatique de l'utilisateur admin

3. **`.htaccess`**
   - Configuration Apache pour le routing

4. **`setup-database.php`** ⭐ NOUVEAU
   - Script de création automatique des tables
   - Crée users, categories, products avec structure complète
   - À supprimer après utilisation (sécurité)

### ✅ Dossier `api/` (6 fichiers)

4. **`api/auth.php`**
   - Authentification JWT
   - Génération de token

5. **`api/products.php`**
   - CRUD complet des produits
   - Filtrage, recherche, bestsellers, etc.

6. **`api/categories.php`** ⭐ NOUVEAU
   - CRUD des catégories
   - Support hiérarchique

7. **`api/products-utils.php`** ⭐ NOUVEAU
   - Utilitaires : priceRange, brands, materials, ageRanges

8. **`api/change-password.php`**
   - Changement de mot de passe utilisateur

9. **`api/check-first-use.php`**
   - Détection de la première utilisation

### ✅ Dossier `config/` (2 fichiers)

10. **`config/database.php`**
    - Configuration de connexion à la base de données
    - ⚠️ Vérifiez que les credentials sont corrects :
      - `db_name = "u899993703_produits"`
      - `username = "u899993703_prod"`
      - `password = "vegvUj-rosxo7-tycwyn"`

11. **`config/jwt.php`**
    - Configuration JWT (secret key, expiration, etc.)

### ✅ Dossier `helpers/` (2 fichiers)

12. **`helpers/jwt_helper.php`**
    - Fonctions de génération et validation JWT

13. **`helpers/user_setup.php`**
    - Création automatique de l'utilisateur admin
    - Création automatique de la table users

### ✅ Dossier `middleware/` (1 fichier)

14. **`middleware/auth_middleware.php`**
    - Middleware d'authentification
    - Protection des routes API

### ✅ Dossier `models/` (3 fichiers)

15. **`models/Product.php`**
    - Modèle Product complet
    - Toutes les méthodes de filtrage et recherche

16. **`models/Category.php`** ⭐ NOUVEAU
    - Modèle Category
    - Support hiérarchique

17. **`models/User.php`**
    - Modèle User
    - Authentification et gestion des utilisateurs

## 📋 Checklist d'upload

### Étape 1 : Créer les dossiers
- [ ] `api/`
- [ ] `config/`
- [ ] `helpers/`
- [ ] `middleware/`
- [ ] `models/`

### Étape 2 : Uploader les fichiers racine
- [ ] `index.php`
- [ ] `login.php`
- [ ] `.htaccess`
- [ ] `setup-database.php` (pour créer les tables)

### Étape 3 : Uploader dans `api/`
- [ ] `api/auth.php`
- [ ] `api/products.php`
- [ ] `api/categories.php`
- [ ] `api/products-utils.php`
- [ ] `api/change-password.php`
- [ ] `api/check-first-use.php`

### Étape 4 : Uploader dans `config/`
- [ ] `config/database.php` ⚠️ Vérifier les credentials
- [ ] `config/jwt.php`

### Étape 5 : Uploader dans `helpers/`
- [ ] `helpers/jwt_helper.php`
- [ ] `helpers/user_setup.php`

### Étape 6 : Uploader dans `middleware/`
- [ ] `middleware/auth_middleware.php`

### Étape 7 : Uploader dans `models/`
- [ ] `models/Product.php`
- [ ] `models/Category.php`
- [ ] `models/User.php`

## 🔧 Configuration des permissions

Après l'upload, configurez les permissions via SSH :

```bash
cd ~/domains/annrstore.com/public_html/produits

# Fichiers
chmod 644 *.php
chmod 644 .htaccess

# Dossiers
chmod 755 .
chmod 755 api config helpers middleware models

# Fichiers dans les dossiers
find api config helpers middleware models -type f -exec chmod 644 {} \;
find api config helpers middleware models -type d -exec chmod 755 {} \;
```

## ⚠️ Points importants

1. **`config/database.php`** : Vérifiez absolument que les credentials sont corrects avant l'upload
2. **Ordre d'upload** : Vous pouvez uploader dans n'importe quel ordre, mais assurez-vous que tous les fichiers sont présents
3. **Permissions** : Configurez les permissions après l'upload complet
4. **Base de données** : Les tables seront créées automatiquement, mais vous pouvez aussi exécuter `database.sql` manuellement

## 🧪 Test après upload

1. **Créez les tables** :
   - Accédez à : `https://annrstore.com/produits/setup-database.php`
   - Vérifiez que toutes les tables sont créées
   - Supprimez le fichier `setup-database.php` après vérification

2. **Testez l'application** :
   - Accédez à : `https://annrstore.com/produits/login.php`
   - Connectez-vous avec : `admin` / `admin123`
   - Vérifiez que le modal de changement de mot de passe s'affiche
   - Testez la création d'un produit
   - Testez la création d'une catégorie

## 📝 Notes

- Tous les fichiers sont nécessaires pour le bon fonctionnement
- Les fichiers marqués ⭐ NOUVEAU sont les nouveaux fichiers de cette version
- La création automatique des tables et de l'utilisateur fonctionne dès le premier accès
