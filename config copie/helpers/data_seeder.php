<?php
/**
 * Seeder pour insérer des catégories et produits par défaut
 * Utilisé au démarrage de l'application (login, API, etc.)
 */

require_once __DIR__ . '/../config/database.php';

class DataSeeder {
    private static $seeded = false;

    public static function seedInitialData() {
        if (self::$seeded) {
            return;
        }

        $database = new Database();
        $conn = $database->getConnection();

        if (!$conn) {
            return;
        }

        try {
            $countStmt = $conn->query("SHOW TABLES LIKE 'products'");
            if ($countStmt->rowCount() === 0) {
                return;
            }

            $count = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
            if ((int)$count > 0) {
                self::$seeded = true;
                return;
            }

            $categories = [
                ['poussettes', 'Poussettes & Sièges Autos', 'Poussettes confortables et sûres.', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=1080&q=80', 45],
                ['shoes', 'Chaussures', 'Chaussures robustes pour les petits.', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=1080&q=80', 32],
                ['toys', 'Jouets', 'Jouets éducatifs et amusants.', 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=1080&q=80', 28],
                ['accessories', 'Accessoires', 'Accessoires pratiques et stylés.', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=1080&q=80', 23],
                ['baby-care', 'Soins Bébé', 'Produits de soin pour les tout-petits.', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=1080&q=80', 19]
            ];

            $insertCategory = $conn->prepare("INSERT IGNORE INTO categories (id, name, description, image, product_count) VALUES (:id, :name, :description, :image, :count)");
            foreach ($categories as $cat) {
                $insertCategory->execute([
                    ':id' => $cat[0],
                    ':name' => $cat[1],
                    ':description' => $cat[2],
                    ':image' => $cat[3],
                    ':count' => $cat[4]
                ]);
            }

            $products = [
                [
                    'id' => 'prod_poussette_001',
                    'name' => 'Poussette 3 en 1 Premium',
                    'category' => 'Poussettes & Sièges Autos',
                    'category_id' => 'poussettes',
                    'availability' => 'in_stock',
                    'badge' => 'new',
                    'original_price' => 1499.99,
                    'price' => 1299.99,
                    'features' => ['Mode nacelle', 'Roues tout-terrain', 'Canopy UV50+'],
                    'rating' => 4.7,
                    'review_count' => 124,
                    'description' => "Poussette haut de gamme avec système 3-en-1 (nacelle, cosy, poussette). Suspensions intégrales et pliage d'une main.",
                    'short_description' => 'Poussette 3-en-1 confortable et évolutive.',
                    'image' => 'https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-coffee_lm0zji.avif',
                    'images' => [
                        'https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-coffee_lm0zji.avif',
                        'https://res.cloudinary.com/dbxudfl1u/image/upload/v1762206107/kidilo-poussette-valise-reversible-6530b-noir_vexozt.avif'
                    ],
                    'videos' => [
                        'https://res.cloudinary.com/dbxudfl1u/video/upload/v1762206679/Premium_Baby_Stroller_3-in-1_with_Car_Seat_Travel_System_Set_kmxewp.mp4'
                    ],
                    'sizes' => ['0-3M', '3-6M', '6-9M', '9-12M', '12-36M'],
                    'colors' => ['Noir', 'Gris', 'Beige'],
                    'material' => 'Aluminium + tissus premium',
                    'brand' => 'Kidilo',
                    'age_range' => '0-36 mois',
                    'is_new' => true,
                    'discount' => 200,
                    'discount_percentage' => 13,
                    'tags' => ['premium', '3-en-1', 'bébé']
                ],
                [
                    'id' => 'prod_shoes_002',
                    'name' => 'Chaussures premiers pas FlexiStep',
                    'category' => 'Chaussures',
                    'category_id' => 'shoes',
                    'availability' => 'in_stock',
                    'badge' => 'bestseller',
                    'original_price' => 69.99,
                    'price' => 59.99,
                    'features' => ['Semelle antidérapante', 'Cuir respirant', 'Fermeture velcro'],
                    'rating' => 4.8,
                    'review_count' => 89,
                    'description' => "Chaussures souples recommandées par les podologues pour les premiers pas. Semelle flexible et soutien de la cheville.",
                    'short_description' => 'Chaussures premiers pas en cuir respirant.',
                    'image' => 'https://images.unsplash.com/photo-1514986888952-8cd320577b68?auto=format&fit=crop&w=1080&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1514986888952-8cd320577b68?auto=format&fit=crop&w=1080&q=80',
                        'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=1080&q=80'
                    ],
                    'videos' => [],
                    'sizes' => ['18', '19', '20', '21', '22', '23'],
                    'colors' => ['Blanc', 'Rose', 'Bleu'],
                    'material' => 'Cuir italien respirant',
                    'brand' => 'FlexiStep',
                    'age_range' => '9-24 mois',
                    'is_new' => false,
                    'discount' => 10,
                    'discount_percentage' => 14,
                    'tags' => ['premiers pas', 'podologue', 'cuir']
                ],
                [
                    'id' => 'prod_toys_003',
                    'name' => 'Cube d\'éveil Montessori 8 activités',
                    'category' => 'Jouets',
                    'category_id' => 'toys',
                    'availability' => 'in_stock',
                    'badge' => 'trending',
                    'original_price' => 79.99,
                    'price' => 69.99,
                    'features' => ['8 activités sensorielles', 'Bois FSC', 'Peintures à l’eau'],
                    'rating' => 4.9,
                    'review_count' => 152,
                    'description' => "Cube d'éveil inspiré de la pédagogie Montessori pour développer la motricité fine, la coordination et la curiosité.",
                    'short_description' => 'Cube d’éveil Montessori en bois FSC.',
                    'image' => 'https://images.unsplash.com/photo-1511452885600-a3d2c9148a31?auto=format&fit=crop&w=1080&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1511452885600-a3d2c9148a31?auto=format&fit=crop&w=1080&q=80',
                        'https://images.unsplash.com/photo-1493666438817-866a91353ca9?auto=format&fit=crop&w=1080&q=80'
                    ],
                    'videos' => [],
                    'sizes' => [],
                    'colors' => ['Multicolore'],
                    'material' => 'Bois FSC + peinture à l’eau',
                    'brand' => 'Montessori Lab',
                    'age_range' => '12-36 mois',
                    'is_new' => true,
                    'discount' => 10,
                    'discount_percentage' => 13,
                    'tags' => ['montessori', 'éveil', 'bois']
                ]
            ];

            $insertProduct = $conn->prepare("
                INSERT INTO products (
                    id, name, category, category_id, availability, badge,
                    original_price, price, features, rating, review_count,
                    description, short_description, image, images, videos,
                    sizes, colors, material, brand, age_range, is_new,
                    discount, discount_percentage, tags
                ) VALUES (
                    :id, :name, :category, :category_id, :availability, :badge,
                    :original_price, :price, :features, :rating, :review_count,
                    :description, :short_description, :image, :images, :videos,
                    :sizes, :colors, :material, :brand, :age_range, :is_new,
                    :discount, :discount_percentage, :tags
                )
            ");

            foreach ($products as $product) {
                $insertProduct->execute([
                    ':id' => $product['id'],
                    ':name' => $product['name'],
                    ':category' => $product['category'],
                    ':category_id' => $product['category_id'],
                    ':availability' => $product['availability'],
                    ':badge' => $product['badge'],
                    ':original_price' => $product['original_price'],
                    ':price' => $product['price'],
                    ':features' => json_encode($product['features'], JSON_UNESCAPED_UNICODE),
                    ':rating' => $product['rating'],
                    ':review_count' => $product['review_count'],
                    ':description' => $product['description'],
                    ':short_description' => $product['short_description'],
                    ':image' => $product['image'],
                    ':images' => json_encode($product['images'], JSON_UNESCAPED_UNICODE),
                    ':videos' => json_encode($product['videos'], JSON_UNESCAPED_UNICODE),
                    ':sizes' => json_encode($product['sizes'], JSON_UNESCAPED_UNICODE),
                    ':colors' => json_encode($product['colors'], JSON_UNESCAPED_UNICODE),
                    ':material' => $product['material'],
                    ':brand' => $product['brand'],
                    ':age_range' => $product['age_range'],
                    ':is_new' => $product['is_new'] ? 1 : 0,
                    ':discount' => $product['discount'],
                    ':discount_percentage' => $product['discount_percentage'],
                    ':tags' => json_encode($product['tags'], JSON_UNESCAPED_UNICODE)
                ]);
            }

            self::$seeded = true;
        } catch (Exception $e) {
            error_log('DataSeeder error: ' . $e->getMessage());
        }
    }
}
?>

