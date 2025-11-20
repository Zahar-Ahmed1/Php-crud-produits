# 🚀 Guide de déploiement rapide - annrstore.com

## ⚡ Déploiement en 5 étapes

### 1️⃣ Préparer les fichiers localement

Assurez-vous d'avoir tous ces fichiers dans votre projet :
- ✅ 3 fichiers racine : `index.php`, `login.php`, `.htaccess`
- ✅ 6 fichiers dans `api/` : `auth.php`, `products.php`, `categories.php`, `products-utils.php`, `change-password.php`, `check-first-use.php`
- ✅ 2 fichiers dans `config/` : `database.php`, `jwt.php`
- ✅ 2 fichiers dans `helpers/` : `jwt_helper.php`, `user_setup.php`
- ✅ 1 fichier dans `middleware/` : `auth_middleware.php`
- ✅ 3 fichiers dans `models/` : `Product.php`, `Category.php`, `User.php`

**Total : 20 fichiers**

### 2️⃣ Vérifier la configuration

**Ouvrez `config/database.php` et vérifiez :**
```php
private $db_name = "u899993703_produits";
private $username = "u899993703_prod";
private $password = "vegvUj-rosxo7-tycwyn";
```

### 3️⃣ Uploader via le Gestionnaire de fichiers Hostinger

1. Allez sur https://hpanel.hostinger.com/
2. Ouvrez le **Gestionnaire de fichiers**
3. Naviguez vers `domains/annrstore.com/public_html/produits/`
4. **Créez les dossiers** s'ils n'existent pas :
   - `api/`, `config/`, `helpers/`, `middleware/`, `models/`
5. **Uploadez tous les fichiers** dans leurs dossiers respectifs

### 4️⃣ Configurer les permissions (SSH)

```bash
cd ~/domains/annrstore.com/public_html/produits
chmod 644 *.php .htaccess
chmod 755 api config helpers middleware models
find api config helpers middleware models -type f -exec chmod 644 {} \;
```

### 5️⃣ Tester

1. Accédez à : **https://annrstore.com/produits/login.php**
2. Connectez-vous : `admin` / `admin123`
3. Changez le mot de passe (modal automatique)
4. Testez la création d'un produit

## ✅ C'est tout !

Les tables et l'utilisateur seront créés automatiquement.

## 🆘 Si ça ne marche pas

### Erreur de connexion
- Vérifiez `config/database.php` (credentials)
- Vérifiez que la base existe dans phpMyAdmin

### Erreur 500
- Vérifiez les permissions (chmod 644 pour fichiers, 755 pour dossiers)
- Vérifiez les logs d'erreur dans hPanel

### Tables non créées
- Exécutez `database.sql` manuellement dans phpMyAdmin
- Ou vérifiez les permissions des fichiers PHP

## 📞 Support

Consultez `DEPLOIEMENT_FINAL.md` pour plus de détails.

