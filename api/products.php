<?php
/**
 * API REST pour les opérations CRUD sur les produits (structure complète)
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';
require_once __DIR__ . '/../helpers/data_seeder.php';

// Créer les tables si elles n'existent pas (categories d'abord, puis products)
// Cette création se fait AVANT l'authentification pour permettre la création automatique
try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        // Créer la table users d'abord si elle n'existe pas
        $stmt = $conn->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() == 0) {
            $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) NOT NULL DEFAULT 'admin',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_username (username)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $conn->exec($createUsersTable);
        }
        
        // Créer la table categories si elle n'existe pas
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
        
        // Créer la table products si elle n'existe pas
        $stmt = $conn->query("SHOW TABLES LIKE 'products'");
        if ($stmt->rowCount() == 0) {
            $createTable = "CREATE TABLE IF NOT EXISTS products (
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
            $conn->exec($createTable);
        }
    } else {
        // Si la connexion échoue, on log l'erreur mais on continue
        error_log("Erreur: Impossible de se connecter à la base de données pour créer les tables");
    }
} catch (Exception $e) {
    error_log("Erreur création tables: " . $e->getMessage());
    // On continue même en cas d'erreur, requireAuth() gérera l'erreur
}

// Injecter des produits par défaut si la base est vide
DataSeeder::seedInitialData();

// Vérifier l'authentification pour toutes les opérations
$user = requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$product = new Product();

// Récupérer les paramètres de requête
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null; // bestsellers, new, sale, search, filter, byCategory
$query = $_GET['query'] ?? null;
$categoryId = $_GET['categoryId'] ?? null;

// Récupérer les données JSON du body
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        try {
            if ($id !== null) {
                // Récupérer un produit spécifique
                $result = $product->readOne($id);
                if ($result) {
                    http_response_code(200);
                    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Produit non trouvé"], JSON_UNESCAPED_UNICODE);
                }
            } elseif ($action === 'bestsellers') {
                // Produits bestseller
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
                $products = $product->getBestsellers($limit);
                http_response_code(200);
                echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } elseif ($action === 'new') {
                // Nouveaux produits
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
                $products = $product->getNewProducts($limit);
                http_response_code(200);
                echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } elseif ($action === 'sale') {
                // Produits en promotion
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
                $products = $product->getSaleProducts($limit);
                http_response_code(200);
                echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } elseif ($action === 'search' && $query) {
                // Recherche
                $products = $product->search($query);
                http_response_code(200);
                echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } elseif ($action === 'filter') {
                // Filtrage avancé
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
                // Produits par catégorie
                $products = $product->getByCategory($categoryId);
                http_response_code(200);
                echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } else {
                // Récupérer tous les produits
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
        break;

    case 'POST':
        // Créer un nouveau produit
        if (!empty($data)) {
            try {
                $product->id = $data['id'] ?? uniqid('prod_', true);
                $product->name = $data['name'] ?? '';
                $product->category = $data['category'] ?? '';
                $product->category_id = $data['categoryId'] ?? '';
                $product->availability = $data['availability'] ?? 'in_stock';
                $product->badge = $data['badge'] ?? null;
                $product->original_price = $data['originalPrice'] ?? null;
                $product->price = $data['price'] ?? 0;
                $product->features = $data['features'] ?? [];
                $product->rating = $data['rating'] ?? 0;
                $product->review_count = $data['reviewCount'] ?? 0;
                $product->description = $data['description'] ?? '';
                $product->short_description = $data['shortDescription'] ?? '';
                $product->image = $data['image'] ?? '';
                $product->images = $data['images'] ?? [];
                $product->videos = $data['videos'] ?? [];
                $product->sizes = $data['sizes'] ?? [];
                $product->colors = $data['colors'] ?? [];
                $product->material = $data['material'] ?? null;
                $product->brand = $data['brand'] ?? '';
                $product->age_range = $data['ageRange'] ?? '';
                $product->is_new = $data['isNew'] ?? false;
                $product->discount = $data['discount'] ?? null;
                $product->discount_percentage = $data['discountPercentage'] ?? null;
                $product->tags = $data['tags'] ?? [];

                if ($product->create()) {
                    http_response_code(201);
                    echo json_encode([
                        "success" => true,
                        "message" => "Produit créé avec succès",
                        "id" => $product->id
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(503);
                    echo json_encode([
                        "success" => false,
                        "message" => "Impossible de créer le produit"
                    ], JSON_UNESCAPED_UNICODE);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Erreur lors de la création: " . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Données incomplètes"
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'PUT':
    case 'PATCH':
        // Mettre à jour un produit
        if ($id !== null && !empty($data)) {
            try {
                $product->id = $id;
                $product->name = $data['name'] ?? '';
                $product->category = $data['category'] ?? '';
                $product->category_id = $data['categoryId'] ?? '';
                $product->availability = $data['availability'] ?? 'in_stock';
                $product->badge = $data['badge'] ?? null;
                $product->original_price = $data['originalPrice'] ?? null;
                $product->price = $data['price'] ?? 0;
                $product->features = $data['features'] ?? [];
                $product->rating = $data['rating'] ?? 0;
                $product->review_count = $data['reviewCount'] ?? 0;
                $product->description = $data['description'] ?? '';
                $product->short_description = $data['shortDescription'] ?? '';
                $product->image = $data['image'] ?? '';
                $product->images = $data['images'] ?? [];
                $product->videos = $data['videos'] ?? [];
                $product->sizes = $data['sizes'] ?? [];
                $product->colors = $data['colors'] ?? [];
                $product->material = $data['material'] ?? null;
                $product->brand = $data['brand'] ?? '';
                $product->age_range = $data['ageRange'] ?? '';
                $product->is_new = $data['isNew'] ?? false;
                $product->discount = $data['discount'] ?? null;
                $product->discount_percentage = $data['discountPercentage'] ?? null;
                $product->tags = $data['tags'] ?? [];

                if ($product->update()) {
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "message" => "Produit mis à jour avec succès"
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(503);
                    echo json_encode([
                        "success" => false,
                        "message" => "Impossible de mettre à jour le produit"
                    ], JSON_UNESCAPED_UNICODE);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Erreur lors de la mise à jour: " . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Données incomplètes ou ID manquant"
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        // Supprimer un produit
        if ($id !== null) {
            try {
                $product->id = $id;
                if ($product->delete()) {
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "message" => "Produit supprimé avec succès"
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(503);
                    echo json_encode([
                        "success" => false,
                        "message" => "Impossible de supprimer le produit"
                    ], JSON_UNESCAPED_UNICODE);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Erreur lors de la suppression: " . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID manquant"
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Méthode non autorisée"
        ], JSON_UNESCAPED_UNICODE);
        break;
}
