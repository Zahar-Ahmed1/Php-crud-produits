# 🔧 Correction du fichier database.php sur le serveur

## ⚠️ Problème

Le serveur utilise encore l'ancienne configuration (`u878075774_produits`) au lieu de la nouvelle (`u899993703_produits`).

## ✅ Solution : Mettre à jour le fichier sur le serveur

### Option 1 : Via le Gestionnaire de fichiers Hostinger (Recommandé)

1. **Allez sur** : https://hpanel.hostinger.com/
2. **Ouvrez** le Gestionnaire de fichiers
3. **Naviguez vers** : `domains/annrstore.com/public_html/produits/config/`
4. **Cliquez sur** `database.php` pour l'éditer
5. **Remplacez TOUT le contenu** par ceci :

```php
<?php
/**
 * Configuration de la base de données pour annrstore.com
 */

class Database {
    private $host = "localhost";
    private $db_name = "u899993703_produits"; // Nom de votre base de données
    private $username = "u899993703_prod"; // Votre nom d'utilisateur MySQL
    private $password = "vegvUj-rosxo7-tycwyn"; // Votre mot de passe MySQL
    private $conn;

    /**
     * Obtient la connexion à la base de données
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("Erreur de connexion DB: " . $exception->getMessage());
            // Ne pas afficher l'erreur directement, la retourner null
        }

        return $this->conn;
    }
}
```

6. **Sauvegardez** le fichier

### Option 2 : Via SSH

```bash
cd ~/domains/annrstore.com/public_html/produits/config

# Créer une sauvegarde
cp database.php database.php.backup

# Éditer le fichier
nano database.php
```

Puis remplacez tout le contenu par le code ci-dessus et sauvegardez (Ctrl+X, puis Y, puis Entrée).

### Option 3 : Re-uploader le fichier

1. **Uploadez** le fichier `config/database.php` depuis votre Mac vers le serveur
2. **Remplacez** l'ancien fichier

## ✅ Vérification

Après la mise à jour :

1. **Rafraîchissez** la page : `https://annrstore.com/produits/setup.php`
2. L'erreur devrait disparaître
3. Vous devriez voir : "✓ Connexion réussie à la base de données"

## 📝 Points importants

- ✅ Base de données : `u899993703_produits` (pas `u878075774_produits`)
- ✅ Utilisateur : `u899993703_prod` (pas `u878075774_prod`)
- ✅ Mot de passe : `vegvUj-rosxo7-tycwyn`
- ✅ Host : `localhost`

