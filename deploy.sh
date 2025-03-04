#!/bin/bash

set -e

cd domains/sygenes.esign.cm/public_html || exit 1

echo "📦 Suppression des fichiers de build générés..."
rm -rf public/build/*

echo "🔄 Mise à jour du code..."
git fetch origin
git reset --hard origin/main  # Remplacez 'main' par votre branche principale

echo "📦 Mise à jour des dépendances..."
php composer.phar install --no-interaction --no-progress --prefer-dist

echo "🔄 Mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force --no-interaction

echo "🛠️ Reconstruction des assets avec mode verbose..."
php bin/console tailwind:build --minify -v

# Ajoutez une vérification explicite du code de sortie
if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de la construction de Tailwind CSS"
    exit 1
fi

php bin/console asset-map:compile

echo "✅ Déploiement terminé"