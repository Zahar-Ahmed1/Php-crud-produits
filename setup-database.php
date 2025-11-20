<?php
/**
 * Script de création automatique de la base de données
 * Compatible avec le service Angular ProductsService
 * 
 * Accès : https://annrstore.com/produits/setup-database.php
 */

require_once __DIR__ . '/config/database.php';

header("Content-Type: text/html; charset=UTF-8");

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("<h2 style='color: red;'>❌ Erreur de connexion à la base de données</h2><p>Vérifiez config/database.php</p>");
}

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Base de données - annrstore.com</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { color: orange; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>🔧 Configuration de la base de données</h1>
    <p><strong>Base de données :</strong> u899993703_produits</p>
    <hr>";

try {
    // Étape 1 : Créer la table users
    echo "<div class='step'><h2>Étape 1 : Table users</h2>";
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        $createUsers = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL DEFAULT 'admin',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $conn->exec($createUsers);
        echo "<p class='success'>✅ Table 'users' créée avec succès</p>";
        
        // Créer l'utilisateur admin par défaut
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insertUser = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $insertUser->execute(['admin', $defaultPassword, 'admin']);
        echo "<p class='success'>✅ Utilisateur admin créé (username: admin, password: admin123)</p>";
    } else {
        echo "<p class='info'>ℹ️ Table 'users' existe déjà</p>";
    }
    echo "</div>";

    // Étape 2 : Créer la table categories
    echo "<div class='step'><h2>Étape 2 : Table categories</h2>";
    $stmt = $conn->query("SHOW TABLES LIKE 'categories'");
    if ($stmt->rowCount() == 0) {
        $createCategories = "CREATE TABLE IF NOT EXISTS categories (
            id VARCHAR(100) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(500),
            product_count INT NOT NULL DEFAULT 0,
            parent_id VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_parent_id (parent_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $conn->exec($createCategories);
        echo "<p class='success'>✅ Table 'categories' créée avec succès</p>";
        
        // Insérer les catégories initiales
        $categories = [
            ['poussettes', 'Poussettes & Sièges Autos', 'Vêtements confortables et stylés pour tous les âges', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 45],
            ['shoes', 'Chaussures', 'Chaussures robustes et confortables pour les petits pieds', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 32],
            ['toys', 'Jouets', 'Jouets éducatifs et amusants pour stimuler la créativité', 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 28],
            ['accessories', 'Accessoires', 'Accessoires pratiques et élégants pour compléter la tenue', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 23],
            ['baby-care', 'Soins Bébé', 'Produits de soin et d\'hygiène pour les tout-petits', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 19]
        ];
        
        $insertCategory = $conn->prepare("INSERT INTO categories (id, name, description, image, product_count) VALUES (?, ?, ?, ?, ?)");
        foreach ($categories as $cat) {
            try {
                $insertCategory->execute($cat);
            } catch (PDOException $e) {
                // Ignorer les doublons
            }
        }
        echo "<p class='success'>✅ Catégories initiales insérées</p>";
    } else {
        echo "<p class='info'>ℹ️ Table 'categories' existe déjà</p>";
    }
    echo "</div>";

    // Étape 3 : Créer la table products
    echo "<div class='step'><h2>Étape 3 : Table products</h2>";
    $stmt = $conn->query("SHOW TABLES LIKE 'products'");
    if ($stmt->rowCount() == 0) {
        $createProducts = "CREATE TABLE IF NOT EXISTS products (
            id VARCHAR(100) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(255) NOT NULL,
            category_id VARCHAR(100) NOT NULL,
            availability ENUM('in_stock', 'low_stock', 'out_of_stock') NOT NULL DEFAULT 'in_stock',
            badge ENUM('new', 'sale', 'trending', 'bestseller') NULL,
            original_price DECIMAL(10, 2) NULL,
            price DECIMAL(10, 2) NOT NULL,
            features TEXT,
            rating DECIMAL(3, 1) NOT NULL DEFAULT 0.0,
            review_count INT NOT NULL DEFAULT 0,
            description TEXT,
            short_description TEXT,
            image VARCHAR(500),
            images TEXT,
            videos TEXT,
            sizes TEXT,
            colors TEXT,
            material VARCHAR(255) NULL,
            brand VARCHAR(255) NOT NULL,
            age_range VARCHAR(100) NOT NULL,
            is_new BOOLEAN DEFAULT FALSE,
            discount DECIMAL(10, 2) NULL,
            discount_percentage INT NULL,
            tags TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category_id (category_id),
            INDEX idx_brand (brand),
            INDEX idx_availability (availability),
            INDEX idx_badge (badge),
            INDEX idx_price (price),
            INDEX idx_rating (rating)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $conn->exec($createProducts);
        echo "<p class='success'>✅ Table 'products' créée avec succès</p>";
        echo "<p class='info'>ℹ️ Structure complète compatible avec le service Angular ProductsService</p>";
    } else {
        echo "<p class='info'>ℹ️ Table 'products' existe déjà</p>";
        
        // Vérifier si la structure est complète
        $columns = $conn->query("SHOW COLUMNS FROM products")->fetchAll(PDO::FETCH_COLUMN);
        $requiredColumns = ['id', 'name', 'category', 'category_id', 'availability', 'badge', 'original_price', 'price', 'features', 'rating', 'review_count', 'description', 'short_description', 'image', 'images', 'videos', 'sizes', 'colors', 'material', 'brand', 'age_range', 'is_new', 'discount', 'discount_percentage', 'tags', 'created_at', 'updated_at'];
        $missingColumns = array_diff($requiredColumns, $columns);
        
        if (!empty($missingColumns)) {
            echo "<p class='warning'><strong>⚠️ Colonnes manquantes :</strong> " . implode(', ', $missingColumns) . "</p>";
            echo "<p class='info'>ℹ️ La table existe mais avec une structure incomplète (ancienne version)</p>";
            
            // Proposer de recréer la table
            if (isset($_GET['recreate_products']) && $_GET['recreate_products'] === 'yes') {
                echo "<p class='info'>🔄 Recréation de la table 'products' en cours...</p>";
                
                // Sauvegarder les données existantes si nécessaire
                $backupData = [];
                try {
                    $existingProducts = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($existingProducts)) {
                        $backupData = $existingProducts;
                        echo "<p class='info'>ℹ️ " . count($backupData) . " produit(s) trouvé(s) - sauvegarde effectuée</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='warning'>⚠️ Impossible de sauvegarder les données existantes</p>";
                }
                
                // Supprimer la table
                $conn->exec("DROP TABLE IF EXISTS products");
                echo "<p class='success'>✅ Ancienne table supprimée</p>";
                
                // Recréer avec la structure complète
                $createProducts = "CREATE TABLE products (
                    id VARCHAR(100) PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    category VARCHAR(255) NOT NULL,
                    category_id VARCHAR(100) NOT NULL,
                    availability ENUM('in_stock', 'low_stock', 'out_of_stock') NOT NULL DEFAULT 'in_stock',
                    badge ENUM('new', 'sale', 'trending', 'bestseller') NULL,
                    original_price DECIMAL(10, 2) NULL,
                    price DECIMAL(10, 2) NOT NULL,
                    features TEXT,
                    rating DECIMAL(3, 1) NOT NULL DEFAULT 0.0,
                    review_count INT NOT NULL DEFAULT 0,
                    description TEXT,
                    short_description TEXT,
                    image VARCHAR(500),
                    images TEXT,
                    videos TEXT,
                    sizes TEXT,
                    colors TEXT,
                    material VARCHAR(255) NULL,
                    brand VARCHAR(255) NOT NULL,
                    age_range VARCHAR(100) NOT NULL,
                    is_new BOOLEAN DEFAULT FALSE,
                    discount DECIMAL(10, 2) NULL,
                    discount_percentage INT NULL,
                    tags TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_category_id (category_id),
                    INDEX idx_brand (brand),
                    INDEX idx_availability (availability),
                    INDEX idx_badge (badge),
                    INDEX idx_price (price),
                    INDEX idx_rating (rating)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                $conn->exec($createProducts);
                echo "<p class='success'>✅ Table 'products' recréée avec la structure complète</p>";
                
                // Note : Les données ne sont pas restaurées car la structure est différente
                if (!empty($backupData)) {
                    echo "<p class='warning'>⚠️ Les données existantes n'ont pas été restaurées car la structure a changé</p>";
                    echo "<p class='info'>ℹ️ Vous devrez recréer vos produits avec la nouvelle structure</p>";
                }
            } else {
                echo "<p class='warning'><strong>🔧 Solution :</strong> Cliquez sur le bouton ci-dessous pour recréer la table avec la structure complète</p>";
                echo "<p><a href='?recreate_products=yes' style='display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;' onclick='return confirm(\"⚠️ ATTENTION: Cette action va supprimer la table products et toutes ses données. Êtes-vous sûr ?\")'>🔄 Recréer la table products</a></p>";
                echo "<p class='info'><small>ℹ️ Cette action supprimera toutes les données existantes dans la table products</small></p>";
            }
        } else {
            echo "<p class='success'>✅ Structure de la table 'products' est complète</p>";
        }
    }
    echo "</div>";

    // Résumé
    echo "<div class='step'><h2>✅ Résumé</h2>";
    $tables = ['users', 'categories', 'products'];
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $count = $conn->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<p class='success'>✅ Table '$table' : $count enregistrement(s)</p>";
        } else {
            echo "<p class='error'>❌ Table '$table' : n'existe pas</p>";
        }
    }
    echo "</div>";

    echo "<hr>";
    echo "<p><a href='login.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Accéder à l'application</a></p>";
    echo "<p><small>Vous pouvez supprimer ce fichier après vérification</small></p>";

} catch (Exception $e) {
    echo "<div class='step'><h2 class='error'>❌ Erreur</h2>";
    echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "</body></html>";
?>

