<?php
/**
 * Router simple pour le serveur PHP intégré
 */

$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Si c'est une requête vers /api/allproduits.php, exécuter directement le fichier
if (strpos($requestPath, '/api/allproduits.php') !== false) {
    require __DIR__ . '/api/allproduits.php';
    exit;
}

// Si c'est une requête vers /api/products-public.php, exécuter directement le fichier
if (strpos($requestPath, '/api/products-public.php') !== false) {
    require __DIR__ . '/api/products-public.php';
    exit;
}

// Pour toutes les autres requêtes, utiliser index.php par défaut
if (file_exists(__DIR__ . $requestPath) && is_file(__DIR__ . $requestPath)) {
    return false; // Laisser le serveur PHP gérer le fichier
}

// Sinon, charger index.php
require __DIR__ . '/index.php';

