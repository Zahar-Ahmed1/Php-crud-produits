<?php
/**
 * API publique en lecture seule pour les produits
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/data_seeder.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        $stmt = $conn->query("SHOW TABLES LIKE 'categories'");
        if ($stmt->rowCount() == 0) {
            $createCategoriesTable = "CREATE TABLE IF NOT EXISTS categories (
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
            $conn->exec($createCategoriesTable);
        }

        $stmt = $conn->query("SHOW TABLES LIKE 'products'");
        if ($stmt->rowCount() == 0) {
            $createProductsTable = "CREATE TABLE IF NOT EXISTS products (
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
            $conn->exec($createProductsTable);
        }
    } else {
        error_log("Erreur: connexion BD impossible lors de l'initialisation de l'API publique.");
    }
} catch (Exception $e) {
    error_log("Erreur création tables API publique: " . $e->getMessage());
}

DataSeeder::seedInitialData();

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée"
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$product = new Product();

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;
$query = $_GET['query'] ?? null;
$categoryId = $_GET['categoryId'] ?? null;

try {
    if ($id !== null) {
        $result = $product->readOne($id);
        if ($result) {
            http_response_code(200);
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Produit non trouvé"], JSON_UNESCAPED_UNICODE);
        }
    } elseif ($action === 'bestsellers') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
        $products = $product->getBestsellers($limit);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } elseif ($action === 'new') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
        $products = $product->getNewProducts($limit);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } elseif ($action === 'sale') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
        $products = $product->getSaleProducts($limit);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } elseif ($action === 'search' && $query) {
        $products = $product->search($query);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } elseif ($action === 'filter') {
        $filters = [
            'category' => $_GET['category'] ?? null,
            'priceMin' => isset($_GET['priceMin']) ? floatval($_GET['priceMin']) : null,
            'priceMax' => isset($_GET['priceMax']) ? floatval($_GET['priceMax']) : null,
            'rating' => isset($_GET['rating']) ? floatval($_GET['rating']) : null,
            'availability' => $_GET['availability'] ?? null,
            'badge' => $_GET['badge'] ?? null,
            'ageRange' => $_GET['ageRange'] ?? null,
            'brand' => $_GET['brand'] ?? null,
            'material' => $_GET['material'] ?? null
        ];
        $products = $product->filter($filters);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } elseif ($categoryId) {
        $products = $product->getByCategory($categoryId);
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        $products = $product->readAll();
        http_response_code(200);
        echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

