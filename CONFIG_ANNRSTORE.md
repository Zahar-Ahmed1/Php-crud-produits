# Configuration pour annrstore.com

## ✅ Configuration de la base de données

La configuration a été mise à jour pour utiliser :

- **Base de données** : `u899993703_produits`
- **Utilisateur** : `u899993703_prod`
- **Host** : `localhost`
- **Domaine** : `annrstore.com`

## 📝 Fichier de configuration

Le fichier `config/database.prod.php` contient la configuration pour annrstore.com.

Pour utiliser cette configuration en production :

```bash
cp config/database.prod.php config/database.php
```

## 🚀 Déploiement

1. **Uploadez tous les fichiers** vers : `domains/annrstore.com/public_html/produits/`

2. **Exécutez setup.php** : `https://annrstore.com/produits/setup.php`
   - Créera les tables `products` et `users`
   - Importera les données initiales
   - Créera l'utilisateur admin

3. **Connectez-vous** : `https://annrstore.com/produits/login.php`
   - Username : `admin`
   - Password : `admin123`

4. **Supprimez setup.php** après vérification

## ⚠️ Important

Si votre base de données nécessite un mot de passe, modifiez la ligne 10 dans `config/database.prod.php` :

```php
private $password = "votre_mot_de_passe"; // Votre mot de passe MySQL
```

