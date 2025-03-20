#!/bin/bash

cd domains/sygenes.esign.cm/public_html || { echo "❌ Échec: impossible d'accéder au répertoire du projet"; exit 1; }

echo "📦 Préparation du déploiement..."
# Suppression des anciens fichiers de build n'est plus nécessaire car git reset les remplacera

echo "🔄 Mise à jour du code depuis GitHub..."
git fetch origin || { echo "⚠️ Problème avec git fetch"; }
git reset --hard origin/main || { echo "⚠️ Problème avec git reset"; }
# Les builds précompilés de GitHub sont maintenant en place

echo "📦 Mise à jour des dépendances..."
php composer.phar install --no-interaction --no-progress --prefer-dist || { echo "⚠️ Installation des dépendances incomplète, mais on continue"; }

echo "🔄 Mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force --no-interaction || { echo "⚠️ Mise à jour du schéma échouée, mais on continue"; }

# On ne reconstruit pas les assets, on utilise ceux de GitHub
echo "📦 Installation des assets sans reconstruction..."
php bin/console assets:install || { echo "⚠️ Installation des assets échouée, mais on continue"; }

echo "✅ Déploiement terminé avec les builds de GitHub"