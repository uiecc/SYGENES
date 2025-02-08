#!/bin/bash

set -e  # Arrêter immédiatement le script en cas d'erreur

echo "📂 Accès au répertoire du projet..."
cd domains/sygenes.esign.cm/public_html || exit 1

echo "🔄 Mise à jour du dépôt Git..."
git pull --quiet || exit 1

echo "🛠 Vérification des différences de schéma..."
php bin/console doctrine:schema:update --dump-sql > schema_changes.sql || exit 1

echo "✅ Marquage des migrations existantes..."
php bin/console make:migration --add --all --no-interaction || exit 1


echo "🔄 Synchronisation du stockage des migrations..."
php bin/console doctrine:migrations:sync-metadata-storage --no-interaction || exit 1

echo "✅ Marquage des migrations existantes..."
php bin/console doctrine:migrations:version --add --all --no-interaction || exit 1

echo "🚀 Exécution des migrations..."
if ! php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration; then
    echo "⚠️ Migration échouée, forçage de la mise à jour du schéma..."
    
    php bin/console doctrine:schema:update --force --no-interaction
    echo "✅ Schéma mis à jour avec succès"

    echo "🔄 Nouvelle tentative d'exécution des migrations..."
    if ! php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration; then
        echo "❌ La migration a échoué même après mise à jour du schéma"
        exit 1
    fi
fi

echo "📦 Mise à jour des dépendances Composer..."
php composer.phar install --no-interaction --no-progress --prefer-dist || exit 1

echo "🧹 Nettoyage des fichiers temporaires..."
rm -f schema_changes.sql

echo "✅ Déploiement terminé avec succès"
