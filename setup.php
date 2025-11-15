<?php
/**
 * Script de configuration et d'importation des données
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Product.php';

// Configuration de la base de données (à adapter selon vos paramètres)
$host = "localhost";
$db_name = "u878075774_produits"; // Nom de votre base de données
$username = "u878075774_produits"; // Votre nom d'utilisateur (généralement le même que la base)
$password = ""; // Votre mot de passe

echo "<h2>Configuration de la base de données</h2>";
echo "<p>Base de données: <strong>$db_name</strong></p>";

// Test de connexion
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "<p style='color: green;'>✓ Connexion réussie à la base de données</p>";
    
    // Vérifier si la table existe
    $stmt = $conn->query("SHOW TABLES LIKE 'products'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p style='color: orange;'>⚠ La table 'products' n'existe pas. Création en cours...</p>";
        
        // Créer la table
        $createTable = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            original_price DECIMAL(10, 2) NULL,
            discount INT NULL,
            rating DECIMAL(3, 1) NOT NULL DEFAULT 0.0,
            reviews INT NOT NULL DEFAULT 0,
            stock INT NOT NULL DEFAULT 0,
            badge ENUM('promo', 'best-seller', 'new') NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category (category),
            INDEX idx_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $conn->exec($createTable);
        echo "<p style='color: green;'>✓ Table 'products' créée avec succès</p>";
    } else {
        echo "<p style='color: green;'>✓ La table 'products' existe déjà</p>";
    }
    
    // Vérifier le nombre de produits
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        echo "<p style='color: orange;'>⚠ Aucun produit dans la base. Importation des données...</p>";
        
        // Données à importer
        $products = [
            ['Cahier 96 pages Premium', 'Cahiers', 'Papier 90g ligné, couverture rigide plastifiée, reliure cousue pour une durabilité maximale.', 3.5, 4.2, 17, 4.8, 241, 180, 'best-seller'],
            ['Stylo bille bleu FlowMax', 'Stylos', 'Pointe 0.7mm, encre à séchage rapide anti-bavures. Lot de 3 stylos.', 2.7, 3.3, 18, 4.6, 189, 320, 'promo'],
            ['Cartable scolaire Explorer', 'Cartables', 'Volume 25L, renforts dorsaux ergonomiques, multiples compartiments et housse anti-pluie.', 58, NULL, NULL, 4.9, 97, 45, 'best-seller'],
            ['Gomme blanche SoftClean', 'Fournitures', 'Gomme sans PVC, efface sans laisser de traces ni abîmer le papier.', 0.95, NULL, NULL, 4.4, 112, 410, NULL],
            ['Feutres de coloriage Artistik 12 couleurs', 'Papeterie créative', 'Encre à base d\'eau, couleurs vibrantes, pointe fine et résistante idéale pour les détails.', 9.9, NULL, NULL, 4.7, 158, 130, 'new'],
            ['Agenda scolaire 2025 Inspire', 'Papeterie créative', 'Agenda hebdomadaire, couverture souple, stickers organisateurs, pages ressources scolaires.', 12.5, NULL, NULL, 4.5, 74, 96, NULL],
            ['Calculatrice scientifique ProCalc X200', 'Technologie', '280 fonctions, écran haute résolution, mode examen conforme aux normes scolaires.', 39.9, NULL, NULL, 4.8, 65, 58, 'new'],
            ['Lot de classeurs A4 ColorMix (x4)', 'Organisation', 'Classeur 4 anneaux, dos 4cm, carton rigide pelliculé, coloris assortis.', 14.5, NULL, NULL, 4.3, 52, 140, NULL],
            ['Sacoche ordinateur Campus 15"', 'Accessoires', 'Protection rembourrée, sangle ajustable, poches organisatrices pour chargeurs et stylos.', 32, NULL, NULL, 4.6, 83, 72, 'promo'],
            ['Pack crayons à papier HB Graphite Pro (x12)', 'Stylos', 'Bois certifié FSC, mine 2.5mm HB, gomme intégrée, idéal pour le dessin et l\'écriture.', 5.6, NULL, NULL, 4.4, 131, 260, NULL],
            ['Surligneurs PastelGlow (x6)', 'Fournitures', 'Couleurs pastel, encre anti-transfert, capuchon clip et grip ergonomique.', 6.8, NULL, NULL, 4.7, 142, 195, 'best-seller'],
            ['Trousse organisatrice Modulo', 'Accessoires', 'Trousse modulable avec séparateurs, poches élastiques et fermeture renforcée.', 11.9, NULL, NULL, 4.5, 121, 160, NULL],
            ['Papier imprimante recyclé A4 (500 feuilles)', 'Organisation', '80g/m², blanc naturel, certifié écologique, compatible laser et jet d\'encre.', 7.5, NULL, NULL, 4.2, 98, 210, NULL],
            ['Cahier de croquis ArtBoard A3', 'Papeterie créative', 'Papier 120g blanc naturel, spirales métalliques, 60 pages micro-perforées.', 8.9, NULL, NULL, 4.6, 54, 88, NULL],
            ['Pack étiquettes autocollantes scolaires (120 unités)', 'Organisation', 'Étiquettes résistantes à l\'eau, plusieurs formats, parfaites pour marquer les fournitures.', 4.3, NULL, NULL, 4.4, 77, 240, NULL]
        ];
        
        $insert = $conn->prepare("INSERT INTO products (name, category, description, price, original_price, discount, rating, reviews, stock, badge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($products as $product) {
            $insert->execute($product);
        }
        
        echo "<p style='color: green;'>✓ " . count($products) . " produits importés avec succès</p>";
    } else {
        echo "<p style='color: blue;'>ℹ $count produit(s) déjà présent(s) dans la base de données</p>";
    }
    
    echo "<hr>";
    echo "<h3>Configuration à mettre dans config/database.php :</h3>";
    echo "<pre style='background: #f4f4f4; padding: 15px; border-radius: 5px;'>";
    echo "private \$host = \"$host\";\n";
    echo "private \$db_name = \"$db_name\";\n";
    echo "private \$username = \"$username\";\n";
    echo "private \$password = \"$password\";\n";
    echo "</pre>";
    
    echo "<p><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Accéder à l'application</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Erreur de connexion: " . $e->getMessage() . "</p>";
    echo "<p>Vérifiez vos paramètres de connexion dans setup.php</p>";
}

