# 🔧 Configuration de la base de données - annrstore.com

## Problème : Table "products" n'existe pas

Si vous voyez l'erreur : **"Erreur lors du chargement des produits. Vérifiez que la table 'products' existe dans la base de données."**

## ✅ Solutions (3 méthodes)

### Méthode 1 : Script PHP automatique (RECOMMANDÉ)

1. **Uploadez le fichier** `setup-database.php` sur le serveur
2. **Accédez à** : `https://annrstore.com/produits/setup-database.php`
3. Le script va :
   - Créer la table `users` si elle n'existe pas
   - Créer la table `categories` si elle n'existe pas
   - Créer la table `products` avec la structure complète
   - Insérer les données initiales (admin, catégories, produit exemple)
4. **Supprimez le fichier** après vérification (sécurité)

### Méthode 2 : Via phpMyAdmin

1. **Connectez-vous à phpMyAdmin** sur Hostinger
2. **Sélectionnez la base** : `u899993703_produits`
3. **Onglet SQL**
4. **Copiez-collez le contenu** de `database-annrstore.sql`
5. **Cliquez sur "Exécuter"**

### Méthode 3 : Création automatique (déjà implémentée)

Les tables sont créées automatiquement lors de la première utilisation de l'API, mais cela nécessite que vous soyez connecté. Si vous n'êtes pas connecté, utilisez la Méthode 1 ou 2.

## 📋 Structure de la base de données

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

### Table `products` (Structure complète compatible Angular)
- `id` (VARCHAR 100, PRIMARY KEY)
- `name` (VARCHAR 255)
- `category` (VARCHAR 255)
- `category_id` (VARCHAR 100)
- `availability` (ENUM: 'in_stock', 'low_stock', 'out_of_stock')
- `badge` (ENUM: 'new', 'sale', 'trending', 'bestseller')
- `original_price` (DECIMAL 10,2)
- `price` (DECIMAL 10,2)
- `features` (TEXT - JSON array)
- `rating` (DECIMAL 3,1)
- `review_count` (INT)
- `description` (TEXT)
- `short_description` (TEXT)
- `image` (VARCHAR 500)
- `images` (TEXT - JSON array)
- `videos` (TEXT - JSON array)
- `sizes` (TEXT - JSON array)
- `colors` (TEXT - JSON array)
- `material` (VARCHAR 255)
- `brand` (VARCHAR 255)
- `age_range` (VARCHAR 100)
- `is_new` (BOOLEAN)
- `discount` (DECIMAL 10,2)
- `discount_percentage` (INT)
- `tags` (TEXT - JSON array)
- `created_at`, `updated_at` (TIMESTAMP)

**Index créés :**
- `idx_category_id`
- `idx_brand`
- `idx_availability`
- `idx_badge`
- `idx_price`
- `idx_rating`

## 🔍 Vérification

Après la création, vérifiez que :

1. **Les 3 tables existent** :
   ```sql
   SHOW TABLES;
   ```
   Doit afficher : `users`, `categories`, `products`

2. **La structure de `products` est complète** :
   ```sql
   DESCRIBE products;
   ```
   Doit afficher toutes les colonnes listées ci-dessus

3. **L'utilisateur admin existe** :
   ```sql
   SELECT * FROM users WHERE username = 'admin';
   ```
   Doit retourner 1 ligne avec username = 'admin'

## 🆘 Dépannage

### Erreur "Table already exists"
- C'est normal, les tables existent déjà
- Vérifiez la structure avec `DESCRIBE products;`

### Erreur "Access denied"
- Vérifiez les credentials dans `config/database.php`
- Vérifiez que l'utilisateur MySQL a les droits CREATE TABLE

### Structure incomplète
- Supprimez la table et recréez-la :
  ```sql
  DROP TABLE IF EXISTS products;
  ```
  Puis exécutez à nouveau le script SQL

### Colonnes manquantes
- Utilisez `ALTER TABLE` pour ajouter les colonnes manquantes
- Ou supprimez et recréez la table

## 📝 Notes importantes

- ✅ La structure est **100% compatible** avec le service Angular `ProductsService`
- ✅ Tous les champs JSON (features, images, videos, etc.) sont stockés en TEXT
- ✅ Les IDs sont des VARCHAR (pas d'AUTO_INCREMENT) pour correspondre à Angular
- ✅ Les badges utilisent les valeurs : `new`, `sale`, `trending`, `bestseller`
- ✅ La disponibilité utilise : `in_stock`, `low_stock`, `out_of_stock`

## 🚀 Après la configuration

1. Accédez à : `https://annrstore.com/produits/login.php`
2. Connectez-vous : `admin` / `admin123`
3. Changez le mot de passe
4. Testez la création d'un produit avec tous les champs

