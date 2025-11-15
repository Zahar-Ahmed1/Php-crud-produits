-- Base de données pour l'application de gestion de produits
CREATE DATABASE IF NOT EXISTS products_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE products_db;

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2) NULL,
    discount INT NULL,
    rating DECIMAL(3, 1) NOT NULL DEFAULT 0.0,
    reviews INT NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    badge ENUM('promo', 'best-seller', 'new') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des données initiales
INSERT INTO products (id, name, category, description, price, original_price, discount, rating, reviews, stock, badge) VALUES
(1, 'Cahier 96 pages Premium', 'Cahiers', 'Papier 90g ligné, couverture rigide plastifiée, reliure cousue pour une durabilité maximale.', 3.5, 4.2, 17, 4.8, 241, 180, 'best-seller'),
(2, 'Stylo bille bleu FlowMax', 'Stylos', 'Pointe 0.7mm, encre à séchage rapide anti-bavures. Lot de 3 stylos.', 2.7, 3.3, 18, 4.6, 189, 320, 'promo'),
(3, 'Cartable scolaire Explorer', 'Cartables', 'Volume 25L, renforts dorsaux ergonomiques, multiples compartiments et housse anti-pluie.', 58, NULL, NULL, 4.9, 97, 45, 'best-seller'),
(4, 'Gomme blanche SoftClean', 'Fournitures', 'Gomme sans PVC, efface sans laisser de traces ni abîmer le papier.', 0.95, NULL, NULL, 4.4, 112, 410, NULL),
(5, 'Feutres de coloriage Artistik 12 couleurs', 'Papeterie créative', 'Encre à base d\'eau, couleurs vibrantes, pointe fine et résistante idéale pour les détails.', 9.9, NULL, NULL, 4.7, 158, 130, 'new'),
(6, 'Agenda scolaire 2025 Inspire', 'Papeterie créative', 'Agenda hebdomadaire, couverture souple, stickers organisateurs, pages ressources scolaires.', 12.5, NULL, NULL, 4.5, 74, 96, NULL),
(7, 'Calculatrice scientifique ProCalc X200', 'Technologie', '280 fonctions, écran haute résolution, mode examen conforme aux normes scolaires.', 39.9, NULL, NULL, 4.8, 65, 58, 'new'),
(8, 'Lot de classeurs A4 ColorMix (x4)', 'Organisation', 'Classeur 4 anneaux, dos 4cm, carton rigide pelliculé, coloris assortis.', 14.5, NULL, NULL, 4.3, 52, 140, NULL),
(9, 'Sacoche ordinateur Campus 15"', 'Accessoires', 'Protection rembourrée, sangle ajustable, poches organisatrices pour chargeurs et stylos.', 32, NULL, NULL, 4.6, 83, 72, 'promo'),
(10, 'Pack crayons à papier HB Graphite Pro (x12)', 'Stylos', 'Bois certifié FSC, mine 2.5mm HB, gomme intégrée, idéal pour le dessin et l\'écriture.', 5.6, NULL, NULL, 4.4, 131, 260, NULL),
(11, 'Surligneurs PastelGlow (x6)', 'Fournitures', 'Couleurs pastel, encre anti-transfert, capuchon clip et grip ergonomique.', 6.8, NULL, NULL, 4.7, 142, 195, 'best-seller'),
(12, 'Trousse organisatrice Modulo', 'Accessoires', 'Trousse modulable avec séparateurs, poches élastiques et fermeture renforcée.', 11.9, NULL, NULL, 4.5, 121, 160, NULL),
(13, 'Papier imprimante recyclé A4 (500 feuilles)', 'Organisation', '80g/m², blanc naturel, certifié écologique, compatible laser et jet d\'encre.', 7.5, NULL, NULL, 4.2, 98, 210, NULL),
(14, 'Cahier de croquis ArtBoard A3', 'Papeterie créative', 'Papier 120g blanc naturel, spirales métalliques, 60 pages micro-perforées.', 8.9, NULL, NULL, 4.6, 54, 88, NULL),
(15, 'Pack étiquettes autocollantes scolaires (120 unités)', 'Organisation', 'Étiquettes résistantes à l\'eau, plusieurs formats, parfaites pour marquer les fournitures.', 4.3, NULL, NULL, 4.4, 77, 240, NULL);

