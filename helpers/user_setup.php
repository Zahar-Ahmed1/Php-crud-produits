<?php
/**
 * Helper pour créer l'utilisateur admin au démarrage
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserSetup {
    
    /**
     * Vérifie et crée l'utilisateur admin si nécessaire
     */
    public static function ensureAdminExists() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            if (!$conn) {
                return false;
            }
            
            // Vérifier si la table users existe
            try {
                $stmt = $conn->query("SHOW TABLES LIKE 'users'");
                $tableExists = $stmt->rowCount() > 0;
            } catch (Exception $e) {
                $tableExists = false;
            }
            
            // Créer la table users si elle n'existe pas
            if (!$tableExists) {
                $createTable = "CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(50) NOT NULL DEFAULT 'admin',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_username (username)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                $conn->exec($createTable);
            }
            
            // Vérifier si un utilisateur existe déjà
            try {
                $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
                $result = $stmt->fetch();
                $userCount = $result['count'] ?? 0;
            } catch (Exception $e) {
                $userCount = 0;
            }
            
            // Si aucun utilisateur n'existe, créer l'admin par défaut
            if ($userCount == 0) {
                $adminUser = new User();
                $adminUser->username = 'admin';
                $adminUser->password = 'admin123'; // Mot de passe par défaut
                $adminUser->role = 'admin';
                
                if ($adminUser->create()) {
                    error_log("Utilisateur admin créé automatiquement au démarrage");
                    return true;
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la création de l'utilisateur admin: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Vérifie si c'est la première utilisation (mot de passe par défaut)
     */
    public static function isFirstUse($username = 'admin') {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            if (!$conn) {
                return false;
            }
            
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Vérifier si le mot de passe correspond au mot de passe par défaut
                return password_verify('admin123', $user['password']);
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification de la première utilisation: " . $e->getMessage());
            return false;
        }
    }
}

