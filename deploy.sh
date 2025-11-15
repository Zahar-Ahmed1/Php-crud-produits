#!/bin/bash

# Script de déploiement pour Hostinger
# Usage: ./deploy.sh

SERVER="u878075774@92.113.18.53"
REMOTE_PATH="~/domains/chezlibrairie.com/public_html/produits"
LOCAL_PATH="/Users/zahar/Desktop/dossier sans titre 3/app-php"

echo "🚀 Déploiement de l'application PHP sur Hostinger..."
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "index.php" ]; then
    echo "❌ Erreur: index.php non trouvé. Assurez-vous d'être dans le répertoire de l'application."
    exit 1
fi

echo "📦 Préparation des fichiers..."
# Créer un répertoire temporaire sans les fichiers inutiles
TEMP_DIR=$(mktemp -d)
cp -r api config models *.php .htaccess "$TEMP_DIR/"
# Ne pas copier setup.php, database.sql, README.md, etc.

echo "📤 Upload des fichiers vers le serveur..."
scp -r "$TEMP_DIR"/* "$SERVER:$REMOTE_PATH/"

if [ $? -eq 0 ]; then
    echo "✅ Fichiers uploadés avec succès!"
    echo ""
    echo "🔧 Configuration des permissions..."
    ssh "$SERVER" "cd $REMOTE_PATH && chmod 755 . && chmod 644 *.php && chmod 644 .htaccess && chmod 755 api config models"
    
    echo ""
    echo "✅ Déploiement terminé!"
    echo ""
    echo "🌐 Accédez à votre application:"
    echo "   https://chezlibrairie.com/produits/"
    echo ""
    echo "📝 N'oubliez pas de:"
    echo "   1. Exécuter setup.php pour créer la table et importer les données"
    echo "   2. Supprimer setup.php après l'installation"
else
    echo "❌ Erreur lors de l'upload"
    exit 1
fi

# Nettoyer
rm -rf "$TEMP_DIR"

