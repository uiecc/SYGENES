#!/bin/bash

set -e

cd domains/sygenes.esign.cm/public_html || exit 1

echo "⚠️ Réinitialisation du code..."
git reset --hard HEAD
git clean -fd

echo "🔄 Mise à jour du code..."
git pull --quiet || exit 1
echo "🔄 Mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force --no-interaction || exit 1

echo "📦 Mise à jour des dépendances..."
php composer.phar install --no-interaction --no-progress --prefer-dist || exit 1

echo "✅ Déploiement terminé"
php bin/console tailwind:build --minify
php bin/console asset-map:compile