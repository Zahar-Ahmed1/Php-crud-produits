# 🔧 Guide de dépannage - Erreur de connexion au serveur

## ⚠️ Erreur : "Erreur de connexion au serveur" sur la page de login

Cette erreur signifie généralement que :
1. La table `users` n'existe pas dans la base de données
2. L'utilisateur admin n'existe pas
3. Il y a un problème de connexion à la base de données

## ✅ Solution : Exécuter setup.php

### Étape 1 : Accéder à setup.php

1. **Allez sur** : `https://annrstore.com/produits/setup.php`
2. Le script va automatiquement :
   - Vérifier la connexion à la base de données
   - Créer la table `products` si elle n'existe pas
   - Créer la table `users` si elle n'existe pas
   - Créer l'utilisateur admin (username: `admin`, password: `admin123`)
   - Importer les 15 produits

### Étape 2 : Vérifier que tout est créé

Après avoir exécuté `setup.php`, vous devriez voir :
- ✅ Connexion réussie à la base de données
- ✅ La table 'products' existe déjà (ou créée)
- ✅ La table 'users' existe déjà (ou créée)
- ✅ Utilisateur admin créé
- ✅ X produits importés

### Étape 3 : Tester la connexion

1. **Allez sur** : `https://annrstore.com/produits/login.php`
2. **Connectez-vous avec** :
   - Username : `admin`
   - Password : `admin123`

## 🔍 Vérification manuelle (via phpMyAdmin)

Si setup.php ne fonctionne pas, vérifiez manuellement :

1. **Allez sur** : https://hpanel.hostinger.com/
2. **Ouvrez phpMyAdmin**
3. **Sélectionnez** la base `u899993703_produits`
4. **Vérifiez** que les tables existent :
   - `products`
   - `users`

5. **Si la table `users` n'existe pas**, créez-la avec cette requête SQL :

```sql
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

6. **Créez l'utilisateur admin** :

```sql
INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$12$K4gfjy7vk18gn4wB7wa/zOIGGrTAQqE2pgWXUzdnFPVa2g/vvwTWG', 'admin');
```

Ce hash correspond au mot de passe : `admin123`

## 📝 Checklist de vérification

- [ ] Le fichier `config/database.php` est correct sur le serveur
- [ ] La base de données `u899993703_produits` existe
- [ ] La table `users` existe
- [ ] L'utilisateur `admin` existe dans la table `users`
- [ ] Le mot de passe de l'utilisateur admin est correct
- [ ] `setup.php` a été exécuté avec succès

## 🆘 Si le problème persiste

1. **Vérifiez les logs d'erreur** dans hPanel
2. **Vérifiez** que tous les fichiers sont uploadés correctement
3. **Vérifiez** les permissions des fichiers (chmod 644 pour les .php)
4. **Contactez le support Hostinger** si nécessaire

