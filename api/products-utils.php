<?php
/**
 * API pour les utilitaires des produits (priceRange, brands, materials, ageRanges)
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

// Vérifier l'authentification
$user = requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$product = new Product();

if ($method === 'GET') {
    $action = $_GET['action'] ?? null;

    try {
        switch ($action) {
            case 'priceRange':
                $range = $product->getPriceRange();
                http_response_code(200);
                echo json_encode($range, JSON_UNESCAPED_UNICODE);
                break;

            case 'brands':
                $brands = $product->getBrands();
                http_response_code(200);
                echo json_encode($brands, JSON_UNESCAPED_UNICODE);
                break;

            case 'materials':
                $materials = $product->getMaterials();
                http_response_code(200);
                echo json_encode($materials, JSON_UNESCAPED_UNICODE);
                break;

            case 'ageRanges':
                $ageRanges = $product->getAgeRanges();
                http_response_code(200);
                echo json_encode($ageRanges, JSON_UNESCAPED_UNICODE);
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Action non valide. Utilisez: priceRange, brands, materials, ou ageRanges"
                ], JSON_UNESCAPED_UNICODE);
                break;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Erreur: " . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée"
    ], JSON_UNESCAPED_UNICODE);
}

