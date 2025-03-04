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

echo "🛠️ Reconstruction des assets..."
php bin/console tailwind:build --minify
php bin/console asset-map:compile

echo "✅ Déploiement terminé"