<?php
/**
 * Configuration de la base de données pour le développement local
 * Ce fichier peut être utilisé en local en renommant database.php en database.prod.php
 * et database.local.php en database.php
 */

class Database {
    private $host = "localhost";
    private $db_name = "products_db"; // Base de données locale
    private $username = "root"; // Utilisateur local
    private $password = ""; // Mot de passe local (généralement vide en local)
    private $conn;

    /**
     * Obtient la connexion à la base de données
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("Erreur de connexion DB: " . $exception->getMessage());
            // Ne pas afficher l'erreur directement, la retourner null
        }

        return $this->conn;
    }
}

