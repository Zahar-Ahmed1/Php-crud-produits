# ⚠️ INSTRUCTIONS URGENTES - Résoudre l'erreur "Table products n'existe pas"

## 🎯 Solution immédiate (2 minutes)

### Option 1 : Script PHP (LE PLUS RAPIDE) ⭐

1. **Ouvrez votre navigateur**
2. **Accédez à** : `https://annrstore.com/produits/setup-database.php`
3. **Le script va créer toutes les tables automatiquement**
4. **Vous verrez un message de confirmation**
5. **Rafraîchissez la page** `index.php`

### Option 2 : Via phpMyAdmin

1. **Connectez-vous à phpMyAdmin** sur Hostinger
2. **Sélectionnez la base** : `u899993703_produits`
3. **Cliquez sur l'onglet "SQL"**
4. **Ouvrez le fichier** `database-annrstore.sql` sur votre ordinateur
5. **Copiez tout le contenu** du fichier
6. **Collez-le dans la zone SQL** de phpMyAdmin
7. **Cliquez sur "Exécuter"**
8. **Rafraîchissez la page** `index.php`

## 🔍 Vérification

Après avoir exécuté le script, vérifiez que les tables existent :

### Dans phpMyAdmin :
1. Sélectionnez la base `u899993703_produits`
2. Vous devriez voir 3 tables :
   - ✅ `users`
   - ✅ `categories`
   - ✅ `products`

### Via le script setup-database.php :
Le script affichera automatiquement un résumé avec :
- ✅ Table 'users' : X enregistrement(s)
- ✅ Table 'categories' : X enregistrement(s)
- ✅ Table 'products' : X enregistrement(s)

## 🆘 Si ça ne marche toujours pas

### Vérifiez la connexion à la base de données

1. **Ouvrez** `config/database.php` sur le serveur
2. **Vérifiez que les credentials sont corrects** :
   ```php
   private $db_name = "u899993703_produits";
   private $username = "u899993703_prod";
   private $password = "vegvUj-rosxo7-tycwyn";
   ```

### Vérifiez les permissions

Les fichiers PHP doivent avoir les permissions 644 :
```bash
chmod 644 *.php
chmod 644 api/*.php
chmod 644 config/*.php
chmod 644 helpers/*.php
chmod 644 middleware/*.php
chmod 644 models/*.php
```

### Vérifiez les logs d'erreur

Dans hPanel → Logs → Error Logs, cherchez les erreurs liées à :
- "Table doesn't exist"
- "Access denied"
- "Connection failed"

## 📝 Fichiers nécessaires

Assurez-vous que ces fichiers sont uploadés :

- ✅ `setup-database.php` (dans la racine `produits/`)
- ✅ `config/database.php` (avec les bons credentials)
- ✅ `api/products.php` (mis à jour)

## ✅ Après résolution

Une fois les tables créées :
1. **Supprimez** `setup-database.php` (sécurité)
2. **Rafraîchissez** la page `index.php`
3. **Les produits devraient se charger correctement**

---

**Note** : Le script `setup-database.php` est sûr à utiliser et peut être exécuté plusieurs fois sans problème (il utilise `CREATE TABLE IF NOT EXISTS`).

