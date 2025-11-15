<?php
/**
 * Modèle Product pour les opérations CRUD
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $category;
    public $description;
    public $price;
    public $original_price;
    public $discount;
    public $rating;
    public $reviews;
    public $stock;
    public $badge;

    public function __construct($db = null) {
        if ($db === null) {
            $database = new Database();
            $this->conn = $database->getConnection();
        } else {
            $this->conn = $db;
        }
    }

    /**
     * Récupère tous les produits
     */
    public function readAll() {
        $query = "SELECT 
                    id, name, category, description, price, 
                    original_price as originalPrice, discount, 
                    rating, reviews, stock, badge
                  FROM " . $this->table_name . " 
                  ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un produit par son ID
     */
    public function readOne($id) {
        $query = "SELECT 
                    id, name, category, description, price, 
                    original_price as originalPrice, discount, 
                    rating, reviews, stock, badge
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->category = $row['category'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->original_price = $row['originalPrice'];
            $this->discount = $row['discount'];
            $this->rating = $row['rating'];
            $this->reviews = $row['reviews'];
            $this->stock = $row['stock'];
            $this->badge = $row['badge'];
        }

        return $row;
    }

    /**
     * Crée un nouveau produit
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, category, description, price, original_price, discount, rating, reviews, stock, badge) 
                  VALUES 
                  (:name, :category, :description, :price, :original_price, :discount, :rating, :reviews, :stock, :badge)";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = floatval($this->price);
        $this->original_price = $this->original_price ? floatval($this->original_price) : null;
        $this->discount = $this->discount ? intval($this->discount) : null;
        $this->rating = floatval($this->rating);
        $this->reviews = intval($this->reviews);
        $this->stock = intval($this->stock);
        $this->badge = $this->badge ? htmlspecialchars(strip_tags($this->badge)) : null;

        // Liaison des paramètres
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':original_price', $this->original_price);
        $stmt->bindParam(':discount', $this->discount);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':reviews', $this->reviews);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':badge', $this->badge);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Met à jour un produit
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                    name = :name,
                    category = :category,
                    description = :description,
                    price = :price,
                    original_price = :original_price,
                    discount = :discount,
                    rating = :rating,
                    reviews = :reviews,
                    stock = :stock,
                    badge = :badge
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = floatval($this->price);
        $this->original_price = $this->original_price ? floatval($this->original_price) : null;
        $this->discount = $this->discount ? intval($this->discount) : null;
        $this->rating = floatval($this->rating);
        $this->reviews = intval($this->reviews);
        $this->stock = intval($this->stock);
        $this->badge = $this->badge ? htmlspecialchars(strip_tags($this->badge)) : null;

        // Liaison des paramètres
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':original_price', $this->original_price);
        $stmt->bindParam(':discount', $this->discount);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':reviews', $this->reviews);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':badge', $this->badge);

        return $stmt->execute();
    }

    /**
     * Supprime un produit
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Récupère toutes les catégories uniques
     */
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table_name . " ORDER BY category ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category'];
        }

        return $categories;
    }
}

