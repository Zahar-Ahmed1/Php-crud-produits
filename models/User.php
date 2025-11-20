<?php
/**
 * Modèle User pour l'authentification
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $role;
    public $created_at;

    public function __construct($db = null) {
        if ($db === null) {
            $database = new Database();
            $this->conn = $database->getConnection();
            if ($this->conn === null) {
                throw new Exception("Impossible de se connecter à la base de données");
            }
        } else {
            $this->conn = $db;
        }
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, password, role) 
                  VALUES 
                  (:username, :password, :role)";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->role = htmlspecialchars(strip_tags($this->role));
        
        // Hasher le mot de passe
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate($username, $password) {
        if ($this->conn === null) {
            throw new Exception("Connexion à la base de données non disponible");
        }
        
        $query = "SELECT id, username, password, role 
                  FROM " . $this->table_name . " 
                  WHERE username = :username 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->role = $row['role'];
            return true;
        }

        return false;
    }

    /**
     * Vérifie si un utilisateur existe
     */
    public function userExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Met à jour le mot de passe d'un utilisateur
     */
    public function updatePassword($userId, $newPassword) {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        // Hasher le nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }
}

