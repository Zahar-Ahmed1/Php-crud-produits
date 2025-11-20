<?php
/**
 * API d'authentification JWT
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
require_once __DIR__ . '/../helpers/user_setup.php';

// Créer l'utilisateur admin au démarrage si nécessaire
UserSetup::ensureAdminExists();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === 'POST') {
    // Login
    if (isset($data['username']) && isset($data['password'])) {
        try {
            $user = new User();
            
            if ($user->authenticate($data['username'], $data['password'])) {
                // Générer le token JWT
                $token = JWTHelper::generateToken($user->id, $user->username, $user->role);
                
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Authentification réussie",
                    "token" => $token,
                    "user" => [
                        "id" => $user->id,
                        "username" => $user->username,
                        "role" => $user->role
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "Nom d'utilisateur ou mot de passe incorrect"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Erreur de connexion à la base de données: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Nom d'utilisateur et mot de passe requis"
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée"
    ], JSON_UNESCAPED_UNICODE);
}

