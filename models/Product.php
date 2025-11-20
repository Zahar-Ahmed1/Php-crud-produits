<?php
/**
 * Modèle Product pour les opérations CRUD (structure complète)
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $category;
    public $category_id;
    public $availability;
    public $badge;
    public $original_price;
    public $price;
    public $features;
    public $rating;
    public $review_count;
    public $description;
    public $short_description;
    public $image;
    public $images;
    public $videos;
    public $sizes;
    public $colors;
    public $material;
    public $brand;
    public $age_range;
    public $is_new;
    public $discount;
    public $discount_percentage;
    public $tags;

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
     * Récupère tous les produits
     */
    public function readAll() {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Décoder les champs JSON
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère un produit par son ID
     */
    public function readOne($id) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->decodeJsonFields($row);
        }

        return null;
    }

    /**
     * Crée un nouveau produit
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id, name, category, category_id, availability, badge, original_price, price,
                   features, rating, review_count, description, short_description, image, images,
                   videos, sizes, colors, material, brand, age_range, is_new, discount, 
                   discount_percentage, tags) 
                  VALUES 
                  (:id, :name, :category, :category_id, :availability, :badge, :original_price, :price,
                   :features, :rating, :review_count, :description, :short_description, :image, :images,
                   :videos, :sizes, :colors, :material, :brand, :age_range, :is_new, :discount,
                   :discount_percentage, :tags)";

        $stmt = $this->conn->prepare($query);

        // Générer un ID si non fourni
        if (empty($this->id)) {
            $this->id = uniqid('prod_', true);
        }

        // Nettoyage et préparation des données
        $this->prepareData();

        // Liaison des paramètres
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':availability', $this->availability);
        $stmt->bindParam(':badge', $this->badge);
        $stmt->bindParam(':original_price', $this->original_price);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':features', $this->features);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':review_count', $this->review_count);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':short_description', $this->short_description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':videos', $this->videos);
        $stmt->bindParam(':sizes', $this->sizes);
        $stmt->bindParam(':colors', $this->colors);
        $stmt->bindParam(':material', $this->material);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':age_range', $this->age_range);
        $stmt->bindParam(':is_new', $this->is_new, PDO::PARAM_BOOL);
        $stmt->bindParam(':discount', $this->discount);
        $stmt->bindParam(':discount_percentage', $this->discount_percentage);
        $stmt->bindParam(':tags', $this->tags);

        return $stmt->execute();
    }

    /**
     * Met à jour un produit
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                    name = :name,
                    category = :category,
                    category_id = :category_id,
                    availability = :availability,
                    badge = :badge,
                    original_price = :original_price,
                    price = :price,
                    features = :features,
                    rating = :rating,
                    review_count = :review_count,
                    description = :description,
                    short_description = :short_description,
                    image = :image,
                    images = :images,
                    videos = :videos,
                    sizes = :sizes,
                    colors = :colors,
                    material = :material,
                    brand = :brand,
                    age_range = :age_range,
                    is_new = :is_new,
                    discount = :discount,
                    discount_percentage = :discount_percentage,
                    tags = :tags
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Nettoyage et préparation des données
        $this->prepareData();

        // Liaison des paramètres
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':availability', $this->availability);
        $stmt->bindParam(':badge', $this->badge);
        $stmt->bindParam(':original_price', $this->original_price);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':features', $this->features);
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':review_count', $this->review_count);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':short_description', $this->short_description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':videos', $this->videos);
        $stmt->bindParam(':sizes', $this->sizes);
        $stmt->bindParam(':colors', $this->colors);
        $stmt->bindParam(':material', $this->material);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':age_range', $this->age_range);
        $stmt->bindParam(':is_new', $this->is_new, PDO::PARAM_BOOL);
        $stmt->bindParam(':discount', $this->discount);
        $stmt->bindParam(':discount_percentage', $this->discount_percentage);
        $stmt->bindParam(':tags', $this->tags);

        return $stmt->execute();
    }

    /**
     * Supprime un produit
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Filtre les produits selon plusieurs critères
     */
    public function filter($filters) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE 1=1";
        
        $params = [];

        if (isset($filters['category']) && $filters['category']) {
            $query .= " AND category_id = :category";
            $params[':category'] = $filters['category'];
        }

        if (isset($filters['priceMin']) && $filters['priceMin'] !== null) {
            $query .= " AND price >= :priceMin";
            $params[':priceMin'] = floatval($filters['priceMin']);
        }

        if (isset($filters['priceMax']) && $filters['priceMax'] !== null) {
            $query .= " AND price <= :priceMax";
            $params[':priceMax'] = floatval($filters['priceMax']);
        }

        if (isset($filters['rating']) && $filters['rating'] !== null) {
            $query .= " AND rating >= :rating";
            $params[':rating'] = floatval($filters['rating']);
        }

        if (isset($filters['availability']) && $filters['availability']) {
            $query .= " AND availability = :availability";
            $params[':availability'] = $filters['availability'];
        }

        if (isset($filters['badge']) && $filters['badge']) {
            $query .= " AND badge = :badge";
            $params[':badge'] = $filters['badge'];
        }

        if (isset($filters['ageRange']) && $filters['ageRange']) {
            $query .= " AND age_range = :ageRange";
            $params[':ageRange'] = $filters['ageRange'];
        }

        if (isset($filters['brand']) && $filters['brand']) {
            $query .= " AND brand LIKE :brand";
            $params[':brand'] = '%' . $filters['brand'] . '%';
        }

        if (isset($filters['material']) && $filters['material']) {
            $query .= " AND material LIKE :material";
            $params[':material'] = '%' . $filters['material'] . '%';
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Recherche de produits
     */
    public function search($query) {
        $searchQuery = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE 
                    name LIKE :query OR
                    description LIKE :query OR
                    category LIKE :query OR
                    brand LIKE :query OR
                    tags LIKE :query
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($searchQuery);
        $searchTerm = '%' . $query . '%';
        $stmt->bindParam(':query', $searchTerm);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère les produits bestseller
     */
    public function getBestsellers($limit = 6) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE badge = 'bestseller' OR rating >= 4.5
                  ORDER BY rating DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère les nouveaux produits
     */
    public function getNewProducts($limit = 6) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE is_new = 1 OR badge = 'new'
                  ORDER BY created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère les produits en promotion
     */
    public function getSaleProducts($limit = 6) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE discount > 0 AND discount IS NOT NULL
                  ORDER BY discount_percentage DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère les produits par catégorie
     */
    public function getByCategory($categoryId) {
        $query = "SELECT 
                    id, name, category, category_id as categoryId, availability, badge,
                    original_price as originalPrice, price, features, rating, 
                    review_count as reviewCount, description, short_description as shortDescription,
                    image, images, videos, sizes, colors, material, brand, 
                    age_range as ageRange, is_new as isNew, discount, 
                    discount_percentage as discountPercentage, tags,
                    created_at as createdAt, updated_at as updatedAt
                  FROM " . $this->table_name . " 
                  WHERE category_id = :categoryId
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'decodeJsonFields'], $products);
    }

    /**
     * Récupère la plage de prix
     */
    public function getPriceRange() {
        $query = "SELECT MIN(price) as min, MAX(price) as max FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les marques disponibles
     */
    public function getBrands() {
        $query = "SELECT DISTINCT brand FROM " . $this->table_name . " WHERE brand IS NOT NULL ORDER BY brand ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $brands = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $brands[] = $row['brand'];
        }
        return $brands;
    }

    /**
     * Récupère tous les matériaux disponibles
     */
    public function getMaterials() {
        $query = "SELECT DISTINCT material FROM " . $this->table_name . " WHERE material IS NOT NULL ORDER BY material ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $materials = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $materials[] = $row['material'];
        }
        return $materials;
    }

    /**
     * Récupère toutes les tranches d'âge disponibles
     */
    public function getAgeRanges() {
        $query = "SELECT DISTINCT age_range FROM " . $this->table_name . " WHERE age_range IS NOT NULL ORDER BY age_range ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $ageRanges = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ageRanges[] = $row['age_range'];
        }
        return $ageRanges;
    }

    /**
     * Prépare les données avant insertion/mise à jour
     */
    private function prepareData() {
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->availability = $this->availability ?? 'in_stock';
        $this->badge = $this->badge ? htmlspecialchars(strip_tags($this->badge)) : null;
        $this->original_price = $this->original_price ? floatval($this->original_price) : null;
        $this->price = floatval($this->price);
        $this->features = is_array($this->features) ? json_encode($this->features, JSON_UNESCAPED_UNICODE) : ($this->features ?? '[]');
        $this->rating = floatval($this->rating ?? 0);
        $this->review_count = intval($this->review_count ?? 0);
        $this->description = htmlspecialchars(strip_tags($this->description ?? ''));
        $this->short_description = htmlspecialchars(strip_tags($this->short_description ?? ''));
        $this->image = htmlspecialchars(strip_tags($this->image ?? ''));
        $this->images = is_array($this->images) ? json_encode($this->images, JSON_UNESCAPED_UNICODE) : ($this->images ?? '[]');
        $this->videos = is_array($this->videos) ? json_encode($this->videos, JSON_UNESCAPED_UNICODE) : ($this->videos ?? '[]');
        $this->sizes = is_array($this->sizes) ? json_encode($this->sizes, JSON_UNESCAPED_UNICODE) : ($this->sizes ?? '[]');
        $this->colors = is_array($this->colors) ? json_encode($this->colors, JSON_UNESCAPED_UNICODE) : ($this->colors ?? '[]');
        $this->material = $this->material ? htmlspecialchars(strip_tags($this->material)) : null;
        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->age_range = htmlspecialchars(strip_tags($this->age_range));
        $this->is_new = isset($this->is_new) ? (bool)$this->is_new : false;
        $this->discount = $this->discount ? floatval($this->discount) : null;
        $this->discount_percentage = $this->discount_percentage ? intval($this->discount_percentage) : null;
        $this->tags = is_array($this->tags) ? json_encode($this->tags, JSON_UNESCAPED_UNICODE) : ($this->tags ?? '[]');
    }

    /**
     * Décode les champs JSON dans un produit
     */
    private function decodeJsonFields($product) {
        if (isset($product['features'])) {
            $product['features'] = json_decode($product['features'], true) ?? [];
        }
        if (isset($product['images'])) {
            $product['images'] = json_decode($product['images'], true) ?? [];
        }
        if (isset($product['videos'])) {
            $product['videos'] = json_decode($product['videos'], true) ?? [];
        }
        if (isset($product['sizes'])) {
            $product['sizes'] = json_decode($product['sizes'], true) ?? [];
        }
        if (isset($product['colors'])) {
            $product['colors'] = json_decode($product['colors'], true) ?? [];
        }
        if (isset($product['tags'])) {
            $product['tags'] = json_decode($product['tags'], true) ?? [];
        }
        if (isset($product['isNew'])) {
            $product['isNew'] = (bool)$product['isNew'];
        }
        if (isset($product['createdAt'])) {
            $product['createdAt'] = $product['createdAt'];
        }
        if (isset($product['updatedAt'])) {
            $product['updatedAt'] = $product['updatedAt'];
        }
        return $product;
    }
}
