#!/bin/bash

set -e

cd domains/sygenes.esign.cm/public_html || exit 1

echo "🔄 Sauvegarde et reset..."
git reset --hard HEAD
git clean -fd

echo "🔄 Forçage de la mise à jour..."
git fetch origin
git reset --hard origin/main

echo "📦 Installation des dépendances..."
php composer.phar install --no-interaction --no-progress --prefer-dist || exit 1

echo "🔄 Mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force --no-interaction || exit 1

echo "🎨 Compilation des assets..."
php bin/console tailwind:build --minify
php bin/console asset-map:compile

echo "✅ Déploiement terminé"