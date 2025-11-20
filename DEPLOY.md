# Guide de déploiement sur Hostinger

## Étapes de déploiement

### 1. Sur votre serveur Hostinger (SSH)

```bash
# Naviguer vers le répertoire du domaine
cd ~/domains/chezlibrairie.com/public_html

# Ou si vous voulez créer un sous-dossier pour l'application
cd ~/domains/annrstore.com/public_html
mkdir produits
cd produits
```

### 2. Uploader les fichiers

**Option A : Via SCP (depuis votre Mac)**
```bash
cd "/Users/zahar/Desktop/dossier sans titre 3/app-php"
scp -r * u899993703@[votre-serveur]:~/domains/annrstore.com/public_html/produits/
```

**Option B : Via FTP/SFTP**
- Utilisez FileZilla ou un client FTP
- Connectez-vous avec vos identifiants Hostinger
- Naviguez vers `domains/annrstore.com/public_html/`
- Créez un dossier `produits` si nécessaire
- Uploadez tous les fichiers

**Option C : Via Git (si vous avez un repo)**
```bash
# Sur le serveur
cd ~/domains/chezlibrairie.com/public_html
git clone [votre-repo] produits
```

### 3. Configurer les permissions

```bash
# Sur le serveur Hostinger
cd ~/domains/annrstore.com/public_html/produits
chmod 755 .
chmod 644 *.php
chmod 644 .htaccess
chmod 755 api
chmod 755 config
chmod 755 helpers
chmod 755 middleware
chmod 755 models
```

### 4. Créer la base de données et importer les données

**Via phpMyAdmin :**
1. Allez sur https://hpanel.hostinger.com/
2. Ouvrez phpMyAdmin
3. Sélectionnez la base `u878075774_produits`
4. Importez le fichier `database.sql` ou exécutez les commandes SQL

**Via SSH (si vous avez accès MySQL) :**
```bash
mysql -u u878075774_prod -p u878075774_produits < database.sql
```

**Via le script setup.php :**
1. Accédez à : `https://chezlibrairie.com/produits/setup.php`
2. Le script créera automatiquement la table et importera les données

### 5. Vérifier la configuration

Le fichier `config/database.php` doit être configuré avec :
- Host: localhost (correct pour Hostinger)
- Database: u899993703_produits
- Username: u899993703_prod
- Password: (à configurer si nécessaire)

### 6. Accéder à l'application

- **Page de connexion** : `https://annrstore.com/produits/login.php`
- **Interface principale** : `https://annrstore.com/produits/` (redirige vers login si non connecté)
- **API Auth** : `https://annrstore.com/produits/api/auth.php`
- **API Products** : `https://annrstore.com/produits/api/products.php` (nécessite authentification)
- **Setup** : `https://annrstore.com/produits/setup.php` (à supprimer après installation)

### 7. Première connexion

1. Accédez à `https://annrstore.com/produits/login.php`
2. Utilisez les identifiants par défaut :
   - **Username** : `admin`
   - **Password** : `admin123`
3. ⚠️ **Changez le mot de passe après la première connexion !**

### 8. Sécurité (Important !)

Après le déploiement, supprimez ou protégez `setup.php` :
```bash
# Sur le serveur
cd ~/domains/annrstore.com/public_html/produits
rm setup.php
# Ou renommez-le
mv setup.php setup.php.bak
```

## Structure finale sur le serveur

```
~/domains/chezlibrairie.com/public_html/produits/
├── api/
│   ├── auth.php              ✅ NOUVEAU (API d'authentification)
│   └── products.php
├── config/
│   ├── database.php
│   └── jwt.php               ✅ NOUVEAU (Configuration JWT)
├── helpers/
│   └── jwt_helper.php        ✅ NOUVEAU (Helper JWT)
├── middleware/
│   └── auth_middleware.php   ✅ NOUVEAU (Middleware d'auth)
├── models/
│   ├── Product.php
│   └── User.php              ✅ NOUVEAU (Modèle User)
├── .htaccess
├── index.php
├── login.php                 ✅ NOUVEAU (Page de connexion)
└── setup.php                 (À supprimer après installation)
```

## Fichiers à uploader (avec JWT)

### Fichiers principaux :
- ✅ `index.php` (mis à jour avec authentification)
- ✅ `login.php` (nouveau)
- ✅ `.htaccess`
- ✅ `setup.php`

### Dossiers et fichiers :
- ✅ `api/auth.php` (nouveau)
- ✅ `api/products.php` (mis à jour)
- ✅ `config/database.php`
- ✅ `config/jwt.php` (nouveau)
- ✅ `helpers/jwt_helper.php` (nouveau)
- ✅ `middleware/auth_middleware.php` (nouveau)
- ✅ `models/Product.php`
- ✅ `models/User.php` (nouveau)

### Fichiers à NE PAS uploader :
- ❌ `database.local.php` (pour développement local uniquement)
- ❌ `README.md`, `DEPLOY.md`, `SECURITY.md` (documentation)
- ❌ `deploy.sh` (script local)
- ❌ `database.sql` (peut être supprimé après import)

## Dépannage

### Erreur 500
- Vérifiez les permissions des fichiers
- Vérifiez que PHP est activé
- Vérifiez les logs d'erreur dans hPanel

### Erreur de connexion à la base de données
- Vérifiez que le host est bien "localhost" (pas 127.0.0.1)
- Vérifiez les identifiants dans config/database.php
- Vérifiez que la base de données existe dans phpMyAdmin

### .htaccess ne fonctionne pas
- Vérifiez que mod_rewrite est activé (généralement activé par défaut sur Hostinger)
- Contactez le support Hostinger si nécessaire

