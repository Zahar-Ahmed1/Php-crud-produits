# Guide de déploiement sur Hostinger

## Étapes de déploiement

### 1. Sur votre serveur Hostinger (SSH)

```bash
# Naviguer vers le répertoire du domaine
cd ~/domains/chezlibrairie.com/public_html

# Ou si vous voulez créer un sous-dossier pour l'application
cd ~/domains/chezlibrairie.com/public_html
mkdir produits
cd produits
```

### 2. Uploader les fichiers

**Option A : Via SCP (depuis votre Mac)**
```bash
cd "/Users/zahar/Desktop/dossier sans titre 3/app-php"
scp -r * u878075774@92.113.18.53:~/domains/chezlibrairie.com/public_html/produits/
```

**Option B : Via FTP/SFTP**
- Utilisez FileZilla ou un client FTP
- Connectez-vous avec vos identifiants Hostinger
- Naviguez vers `domains/chezlibrairie.com/public_html/`
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
cd ~/domains/chezlibrairie.com/public_html/produits
chmod 755 .
chmod 644 *.php
chmod 644 *.sql
chmod 644 .htaccess
chmod 755 api
chmod 755 config
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

Le fichier `config/database.php` est déjà configuré avec :
- Host: localhost (correct pour Hostinger)
- Database: u878075774_produits
- Username: u878075774_prod
- Password: (déjà configuré)

### 6. Accéder à l'application

- Interface principale : `https://chezlibrairie.com/produits/`
- API : `https://chezlibrairie.com/produits/api/products.php`
- Setup : `https://chezlibrairie.com/produits/setup.php` (à supprimer après installation)

### 7. Sécurité (Important !)

Après le déploiement, supprimez ou protégez `setup.php` :
```bash
# Sur le serveur
cd ~/domains/chezlibrairie.com/public_html/produits
rm setup.php
# Ou renommez-le
mv setup.php setup.php.bak
```

## Structure finale sur le serveur

```
~/domains/chezlibrairie.com/public_html/produits/
├── api/
│   └── products.php
├── config/
│   └── database.php
├── models/
│   └── Product.php
├── .htaccess
├── index.php
└── database.sql (peut être supprimé après import)
```

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

