-- Script SQL pour annrstore.com
-- Base de données : u899993703_produits
-- Compatible avec le service Angular ProductsService

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(100) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    product_count INT NOT NULL DEFAULT 0,
    parent_id VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des produits (structure complète compatible avec Angular ProductsService)
CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(100) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    category_id VARCHAR(100) NOT NULL,
    availability ENUM('in_stock', 'low_stock', 'out_of_stock') NOT NULL DEFAULT 'in_stock',
    badge ENUM('new', 'sale', 'trending', 'bestseller') NULL,
    original_price DECIMAL(10, 2) NULL,
    price DECIMAL(10, 2) NOT NULL,
    features TEXT, -- JSON array
    rating DECIMAL(3, 1) NOT NULL DEFAULT 0.0,
    review_count INT NOT NULL DEFAULT 0,
    description TEXT,
    short_description TEXT,
    image VARCHAR(500),
    images TEXT, -- JSON array
    videos TEXT, -- JSON array (Cloudinary)
    sizes TEXT, -- JSON array
    colors TEXT, -- JSON array
    material VARCHAR(255) NULL,
    brand VARCHAR(255) NOT NULL,
    age_range VARCHAR(100) NOT NULL,
    is_new BOOLEAN DEFAULT FALSE,
    discount DECIMAL(10, 2) NULL,
    discount_percentage INT NULL,
    tags TEXT, -- JSON array
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category_id (category_id),
    INDEX idx_brand (brand),
    INDEX idx_availability (availability),
    INDEX idx_badge (badge),
    INDEX idx_price (price),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion de l'utilisateur admin par défaut
-- Username: admin, Password: admin123
INSERT IGNORE INTO users (username, password, role) VALUES
('admin', '$2y$12$uoItqyrkbaKSqWa6yypgienjQ03bmLeA9Zhfuou.VSMo3hjbrNo.q', 'admin');

-- Insertion des catégories initiales
INSERT IGNORE INTO categories (id, name, description, image, product_count) VALUES
('poussettes', 'Poussettes & Sièges Autos', 'Vêtements confortables et stylés pour tous les âges', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 45),
('shoes', 'Chaussures', 'Chaussures robustes et confortables pour les petits pieds', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 32),
('toys', 'Jouets', 'Jouets éducatifs et amusants pour stimuler la créativité', 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 28),
('accessories', 'Accessoires', 'Accessoires pratiques et élégants pour compléter la tenue', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 23),
('baby-care', 'Soins Bébé', 'Produits de soin et d\'hygiène pour les tout-petits', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 19);

-- Insertion d'un produit exemple (optionnel)
INSERT IGNORE INTO products (
    id, name, category, category_id, availability, badge, price, original_price,
    features, rating, review_count, description, short_description, image, images,
    videos, sizes, colors, material, brand, age_range, is_new, discount, discount_percentage, tags
) VALUES (
    '1',
    'Poussette 3 pièces réversible',
    'Poussettes & Sièges Autos',
    'poussettes',
    'in_stock',
    'new',
    1299.99,
    1499.99,
    '["4 saisons", "Tissu respirant", "Fermeture éclair", "Lavable en machine"]',
    4.2,
    127,
    'Poussette valise réversible 6530B conçue pour répondre aux besoins des parents tout en assurant le bien-être de leur tout-petit.',
    'Une poussette pratique, élégante et confortable pensée pour faciliter la vie des parents tout en assurant le bien-être de bébé.',
    'https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-coffee_lm0zji.avif',
    '["https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-coffee_lm0zji.avif", "https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-noir_vexozt.avif", "https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-1_b5v9vs.avif"]',
    '["https://res.cloudinary.com/dbxudfl1u/video/upload/v1762206679/Premium_Baby_Stroller_3-in-1_with_Car_Seat_Travel_System_Set_kmxewp.mp4"]',
    '["0-3M", "3-6M", "6-9M", "9-12M", "12-36M"]',
    '["Noir", "Marron"]',
    'Tissus résistants et faciles à nettoyer',
    'Kidilo',
    '0-36 mois',
    TRUE,
    200,
    13,
    '["4 saisons", "premium", "bébé"]'
);

