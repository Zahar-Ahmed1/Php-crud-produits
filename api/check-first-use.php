<?php
/**
 * API pour vérifier si c'est la première utilisation
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

require_once __DIR__ . '/../helpers/jwt_helper.php';
require_once __DIR__ . '/../helpers/user_setup.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $user = requireAuth();
        
        // Vérifier si c'est la première utilisation
        $isFirstUse = UserSetup::isFirstUse($user['username']);
        
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "isFirstUse" => $isFirstUse
        ], JSON_UNESCAPED_UNICODE);
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

