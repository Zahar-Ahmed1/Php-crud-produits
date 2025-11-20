<?php
/**
 * Modèle Category pour les catégories de produits
 */

require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $description;
    public $image;
    public $product_count;
    public $parent_id;
    public $children;

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
     * Récupère toutes les catégories
     */
    public function readAll() {
        $query = "SELECT id, name, description, image, product_count, parent_id 
                  FROM " . $this->table_name . " 
                  ORDER BY name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Construire la hiérarchie
        return $this->buildHierarchy($categories);
    }

    /**
     * Récupère une catégorie par son ID
     */
    public function readOne($id) {
        $query = "SELECT id, name, description, image, product_count, parent_id 
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->product_count = $row['product_count'];
            $this->parent_id = $row['parent_id'];
        }

        return $row;
    }

    /**
     * Crée une nouvelle catégorie
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id, name, description, image, product_count, parent_id) 
                  VALUES 
                  (:id, :name, :description, :image, :product_count, :parent_id)";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->product_count = intval($this->product_count ?? 0);
        $this->parent_id = $this->parent_id ? htmlspecialchars(strip_tags($this->parent_id)) : null;

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':product_count', $this->product_count);
        $stmt->bindParam(':parent_id', $this->parent_id);

        return $stmt->execute();
    }

    /**
     * Met à jour une catégorie
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                    name = :name,
                    description = :description,
                    image = :image,
                    product_count = :product_count,
                    parent_id = :parent_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->product_count = intval($this->product_count ?? 0);
        $this->parent_id = $this->parent_id ? htmlspecialchars(strip_tags($this->parent_id)) : null;

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':product_count', $this->product_count);
        $stmt->bindParam(':parent_id', $this->parent_id);

        return $stmt->execute();
    }

    /**
     * Supprime une catégorie
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Construit la hiérarchie des catégories
     */
    private function buildHierarchy($categories) {
        $map = [];
        $roots = [];

        // Créer un map de toutes les catégories
        foreach ($categories as $category) {
            $map[$category['id']] = $category;
            $map[$category['id']]['children'] = [];
        }

        // Construire la hiérarchie
        foreach ($map as $id => $category) {
            if ($category['parent_id'] && isset($map[$category['parent_id']])) {
                $map[$category['parent_id']]['children'][] = &$map[$id];
            } else {
                $roots[] = &$map[$id];
            }
        }

        return $roots;
    }
}

