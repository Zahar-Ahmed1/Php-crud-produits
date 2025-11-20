<?php
/**
 * API publique pour récupérer tous les produits en JSON (sans sécurité)
 */

// Envoyer les headers en premier
if (!headers_sent()) {
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/data_seeder.php';

try {
    // S'assurer que les tables existent et ont des données
    DataSeeder::seedInitialData();
    
    $product = new Product();
    $products = $product->readAll();
    
    if (!headers_sent()) {
        http_response_code(200);
    }
    echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo json_encode([
        "success" => false,
        "message" => "Erreur: " . $e->getMessage(),
        "products" => []
    ], JSON_UNESCAPED_UNICODE);
}

