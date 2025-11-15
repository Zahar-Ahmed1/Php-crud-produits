<?php
/**
 * Configuration de la base de données
 */

class Database {
    private $host = "localhost";
    private $db_name = "u878075774_produits"; // Nom de votre base de données
    private $username = "u878075774_prod"; // Votre nom d'utilisateur MySQL
    private $password = "kyhqiv-3bewfo-puZmyd"; // Votre mot de passe MySQL (à remplir)
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
            echo "Erreur de connexion: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

