<?php
/**
 * Helper pour gérer les tokens JWT
 */

require_once __DIR__ . '/../config/jwt.php';

class JWTHelper {
    
    /**
     * Génère un token JWT
     */
    public static function generateToken($userId, $username, $role = 'admin') {
        $header = [
            'typ' => 'JWT',
            'alg' => JWTConfig::ALGORITHM
        ];
        
        $payload = [
            'user_id' => $userId,
            'username' => $username,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + JWTConfig::TOKEN_EXPIRY
        ];
        
        $base64Header = self::base64UrlEncode(json_encode($header));
        $base64Payload = self::base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac(
            'sha256',
            $base64Header . "." . $base64Payload,
            JWTConfig::SECRET_KEY,
            true
        );
        
        $base64Signature = self::base64UrlEncode($signature);
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    /**
     * Valide et décode un token JWT
     */
    public static function validateToken($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        list($base64Header, $base64Payload, $base64Signature) = $parts;
        
        // Vérifier la signature
        $signature = hash_hmac(
            'sha256',
            $base64Header . "." . $base64Payload,
            JWTConfig::SECRET_KEY,
            true
        );
        
        $expectedSignature = self::base64UrlEncode($signature);
        
        if ($base64Signature !== $expectedSignature) {
            return false;
        }
        
        // Décoder le payload
        $payload = json_decode(self::base64UrlDecode($base64Payload), true);
        
        // Vérifier l'expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
    
    /**
     * Vérifie si l'utilisateur est authentifié
     */
    public static function isAuthenticated() {
        $authHeader = self::getAuthHeader();
        
        if (!$authHeader) {
            return false;
        }
        
        // Format: "Bearer <token>"
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            $payload = self::validateToken($token);
            return $payload !== false;
        }
        
        return false;
    }
    
    /**
     * Récupère les données de l'utilisateur depuis le token
     */
    public static function getCurrentUser() {
        $authHeader = self::getAuthHeader();
        
        if (!$authHeader) {
            return null;
        }
        
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            return self::validateToken($token);
        }
        
        return null;
    }
    
    /**
     * Encode en base64 URL-safe
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Décode depuis base64 URL-safe
     */
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
    
    /**
     * Récupère le header Authorization de manière compatible
     */
    private static function getAuthHeader() {
        // Essayer getallheaders() d'abord (fonctionne avec Apache)
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            return $headers['Authorization'] ?? $headers['authorization'] ?? null;
        }
        
        // Alternative pour Nginx et autres serveurs
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        
        return $headers['Authorization'] ?? null;
    }
}

