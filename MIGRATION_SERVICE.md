# Migration vers la structure du service Angular

## Résumé des changements

L'application PHP a été complètement refactorisée pour correspondre à la structure du service Angular `ProductsService`.

## Nouvelle structure de la base de données

### Table `categories`
- `id` (VARCHAR) - Identifiant unique
- `name` - Nom de la catégorie
- `description` - Description
- `image` - URL de l'image
- `product_count` - Nombre de produits
- `parent_id` - ID de la catégorie parente (pour hiérarchie)
- `created_at`, `updated_at` - Timestamps

### Table `products` (structure complète)
- `id` (VARCHAR) - Identifiant unique (au lieu de INT AUTO_INCREMENT)
- `name` - Nom du produit
- `category` - Nom de la catégorie
- `category_id` - ID de la catégorie
- `availability` - ENUM('in_stock', 'low_stock', 'out_of_stock')
- `badge` - ENUM('new', 'sale', 'trending', 'bestseller')
- `original_price` - Prix original
- `price` - Prix actuel
- `features` - JSON array (caractéristiques)
- `rating` - Note (0-5)
- `review_count` - Nombre d'avis
- `description` - Description complète
- `short_description` - Description courte
- `image` - URL image principale
- `images` - JSON array (images supplémentaires)
- `videos` - JSON array (vidéos Cloudinary)
- `sizes` - JSON array (tailles)
- `colors` - JSON array (couleurs)
- `material` - Matériau
- `brand` - Marque
- `age_range` - Tranche d'âge
- `is_new` - BOOLEAN
- `discount` - Montant de la remise
- `discount_percentage` - Pourcentage de remise
- `tags` - JSON array
- `created_at`, `updated_at` - Timestamps

## Nouveaux fichiers créés

1. **`models/Category.php`** - Modèle pour les catégories
2. **`api/categories.php`** - API REST pour les catégories
3. **`api/products-utils.php`** - API pour les utilitaires (priceRange, brands, materials, ageRanges)

## Fichiers modifiés

1. **`database.sql`** - Structure complète avec catégories et nouveaux champs produits
2. **`models/Product.php`** - Refactorisé avec tous les nouveaux champs et méthodes :
   - `filter()` - Filtrage avancé
   - `search()` - Recherche
   - `getBestsellers()` - Produits bestseller
   - `getNewProducts()` - Nouveaux produits
   - `getSaleProducts()` - Produits en promotion
   - `getByCategory()` - Produits par catégorie
   - `getPriceRange()` - Plage de prix
   - `getBrands()` - Liste des marques
   - `getMaterials()` - Liste des matériaux
   - `getAgeRanges()` - Liste des tranches d'âge
3. **`api/products.php`** - API complète avec :
   - Support de tous les nouveaux champs
   - Actions : `bestsellers`, `new`, `sale`, `search`, `filter`, `byCategory`
   - Création automatique de la table si elle n'existe pas
4. **`index.php`** - Interface complètement refaite :
   - Formulaire organisé en onglets (Informations de base, Médias, Détails)
   - Gestion de tous les nouveaux champs
   - Support des tableaux (images, videos, sizes, colors, features, tags)
   - Affichage amélioré avec badges de disponibilité

## Endpoints API disponibles

### Produits
- `GET api/products.php` - Tous les produits
- `GET api/products.php?id={id}` - Un produit spécifique
- `GET api/products.php?action=bestsellers&limit=6` - Produits bestseller
- `GET api/products.php?action=new&limit=6` - Nouveaux produits
- `GET api/products.php?action=sale&limit=6` - Produits en promotion
- `GET api/products.php?action=search&query={term}` - Recherche
- `GET api/products.php?action=filter&category={id}&priceMin={min}&priceMax={max}&...` - Filtrage avancé
- `GET api/products.php?categoryId={id}` - Produits par catégorie
- `POST api/products.php` - Créer un produit
- `PUT api/products.php?id={id}` - Modifier un produit
- `DELETE api/products.php?id={id}` - Supprimer un produit

### Catégories
- `GET api/categories.php` - Toutes les catégories (avec hiérarchie)
- `GET api/categories.php?id={id}` - Une catégorie spécifique
- `POST api/categories.php` - Créer une catégorie
- `PUT api/categories.php?id={id}` - Modifier une catégorie
- `DELETE api/categories.php?id={id}` - Supprimer une catégorie

### Utilitaires
- `GET api/products-utils.php?action=priceRange` - Plage de prix
- `GET api/products-utils.php?action=brands` - Liste des marques
- `GET api/products-utils.php?action=materials` - Liste des matériaux
- `GET api/products-utils.php?action=ageRanges` - Liste des tranches d'âge

## Migration des données existantes

⚠️ **ATTENTION** : Si vous avez des données existantes, vous devrez :
1. Sauvegarder vos données actuelles
2. Exécuter le nouveau `database.sql` pour créer les nouvelles tables
3. Migrer les données vers la nouvelle structure (si nécessaire)

## Notes importantes

1. **ID des produits** : Les IDs sont maintenant des VARCHAR (ex: `prod_1234567890`) au lieu d'INT AUTO_INCREMENT
2. **Badges** : Les valeurs ont changé :
   - Ancien : `promo`, `best-seller`, `new`
   - Nouveau : `new`, `sale`, `trending`, `bestseller`
3. **Disponibilité** : Utilise maintenant `in_stock`, `low_stock`, `out_of_stock` au lieu d'un champ `stock` numérique
4. **Champs JSON** : Plusieurs champs sont maintenant des tableaux JSON : `features`, `images`, `videos`, `sizes`, `colors`, `tags`
5. **Création automatique** : Les tables sont créées automatiquement si elles n'existent pas lors de la première utilisation

## Compatibilité

L'application est maintenant compatible avec la structure du service Angular `ProductsService` et peut être utilisée comme backend pour une application Angular.

