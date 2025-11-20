<?php
/**
 * Middleware d'authentification JWT
 */

require_once __DIR__ . '/../helpers/jwt_helper.php';

function requireAuth() {
    if (!JWTHelper::isAuthenticated()) {
        http_response_code(401);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode([
            "success" => false,
            "message" => "Authentification requise. Veuillez vous connecter."
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    return JWTHelper::getCurrentUser();
}

