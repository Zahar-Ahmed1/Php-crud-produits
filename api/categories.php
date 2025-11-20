<?php
/**
 * API REST pour les catégories
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

// Vérifier l'authentification
$user = requireAuth();

// Créer la table categories si elle n'existe pas
try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        $stmt = $conn->query("SHOW TABLES LIKE 'categories'");
        if ($stmt->rowCount() == 0) {
            $createTable = "CREATE TABLE IF NOT EXISTS categories (
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
            $conn->exec($createTable);
        }
    }
} catch (Exception $e) {
    // Ignorer l'erreur
}

$method = $_SERVER['REQUEST_METHOD'];
$category = new Category();

// Récupérer l'ID depuis l'URL
$id = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if ($id !== null) {
            $result = $category->readOne($id);
            if ($result) {
                http_response_code(200);
                echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Catégorie non trouvée"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            $categories = $category->readAll();
            http_response_code(200);
            echo json_encode($categories, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        break;

    case 'POST':
        if (!empty($data)) {
            $category->id = $data['id'] ?? uniqid('cat_', true);
            $category->name = $data['name'] ?? '';
            $category->description = $data['description'] ?? '';
            $category->image = $data['image'] ?? '';
            $category->product_count = $data['productCount'] ?? 0;
            $category->parent_id = $data['parentId'] ?? null;

            if ($category->create()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Catégorie créée avec succès",
                    "id" => $category->id
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de créer la catégorie"
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
        if ($id !== null && !empty($data)) {
            $category->id = $id;
            $category->name = $data['name'] ?? '';
            $category->description = $data['description'] ?? '';
            $category->image = $data['image'] ?? '';
            $category->product_count = $data['productCount'] ?? 0;
            $category->parent_id = $data['parentId'] ?? null;

            if ($category->update()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Catégorie mise à jour avec succès"
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de mettre à jour la catégorie"
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
        if ($id !== null) {
            $category->id = $id;
            if ($category->delete()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Catégorie supprimée avec succès"
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de supprimer la catégorie"
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

