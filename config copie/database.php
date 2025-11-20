<?php
/**
 * Configuration de la base de données pour annrstore.com
 */

class Database {
    private $host = "localhost";
    private $db_name = "u899993703_produits"; // Nom de votre base de données
    private $username = "u899993703_prod"; // Votre nom d'utilisateur MySQL
    private $password = "vegvUj-rosxo7-tycwyn"; // Votre mot de passe MySQL
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
