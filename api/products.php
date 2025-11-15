<?php
/**
 * API REST pour les opérations CRUD sur les produits
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

require_once __DIR__ . '/../models/Product.php';

$method = $_SERVER['REQUEST_METHOD'];
$product = new Product();

// Récupérer l'ID depuis l'URL si présent
$id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    // Essayer de récupérer depuis l'URL
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);
    $lastSegment = end($uri);
    if (is_numeric($lastSegment)) {
        $id = (int)$lastSegment;
    }
}

// Récupérer les données JSON du body
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
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
        } else {
            // Récupérer tous les produits
            $products = $product->readAll();
            http_response_code(200);
            echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        break;

    case 'POST':
        // Créer un nouveau produit
        if (!empty($data)) {
            $product->name = $data['name'] ?? '';
            $product->category = $data['category'] ?? '';
            $product->description = $data['description'] ?? '';
            $product->price = $data['price'] ?? 0;
            $product->original_price = $data['originalPrice'] ?? null;
            $product->discount = $data['discount'] ?? null;
            $product->rating = $data['rating'] ?? 0;
            $product->reviews = $data['reviews'] ?? 0;
            $product->stock = $data['stock'] ?? 0;
            $product->badge = $data['badge'] ?? null;

            if ($product->create()) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Produit créé avec succès",
                    "id" => $product->id
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Impossible de créer le produit"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Données incomplètes"], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'PUT':
    case 'PATCH':
        // Mettre à jour un produit
        if ($id !== null && !empty($data)) {
            $product->id = $id;
            $product->name = $data['name'] ?? '';
            $product->category = $data['category'] ?? '';
            $product->description = $data['description'] ?? '';
            $product->price = $data['price'] ?? 0;
            $product->original_price = $data['originalPrice'] ?? null;
            $product->discount = $data['discount'] ?? null;
            $product->rating = $data['rating'] ?? 0;
            $product->reviews = $data['reviews'] ?? 0;
            $product->stock = $data['stock'] ?? 0;
            $product->badge = $data['badge'] ?? null;

            if ($product->update()) {
                http_response_code(200);
                echo json_encode(["message" => "Produit mis à jour avec succès"], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Impossible de mettre à jour le produit"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Données incomplètes ou ID manquant"], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        // Supprimer un produit
        if ($id !== null) {
            $product->id = $id;
            if ($product->delete()) {
                http_response_code(200);
                echo json_encode(["message" => "Produit supprimé avec succès"], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Impossible de supprimer le produit"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID manquant"], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"], JSON_UNESCAPED_UNICODE);
        break;
}

