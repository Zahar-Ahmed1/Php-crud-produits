<?php
/**
 * API pour changer le mot de passe
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === 'POST') {
    // Vérifier l'authentification
    $user = requireAuth();
    
    if (isset($data['currentPassword']) && isset($data['newPassword'])) {
        try {
            $userModel = new User();
            
            // Vérifier le mot de passe actuel
            if ($userModel->authenticate($user['username'], $data['currentPassword'])) {
                // Mettre à jour le mot de passe
                if ($userModel->updatePassword($user['user_id'], $data['newPassword'])) {
                    http_response_code(200);
                    echo json_encode([
                        "success" => true,
                        "message" => "Mot de passe modifié avec succès"
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Erreur lors de la mise à jour du mot de passe"
                    ], JSON_UNESCAPED_UNICODE);
                }
            } else {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "Mot de passe actuel incorrect"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Erreur: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Mot de passe actuel et nouveau mot de passe requis"
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée"
    ], JSON_UNESCAPED_UNICODE);
}

