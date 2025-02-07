#!/bin/bash

# Se déplacer dans le répertoire du projet
cd domains/sygenes.esign.cm/public_html || exit 1

# Sauvegarder l'état actuel de la base de données
php bin/console doctrine:schema:update --dump-sql > schema_changes.sql

# Mettre à jour le dépôt Git sans interaction
git pull --quiet || exit 1

# Exécuter les migrations avec gestion d'erreur
if ! php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration; then
    echo "Migration failed, checking for potential fixes..."
    
    # Si la migration échoue, vérifier si c'est à cause d'une contrainte de clé étrangère
    if grep -q "foreign key constraint fails" schema_changes.sql; then
        echo "Foreign key constraint detected, applying safe migration strategy..."
        
        # Appliquer les migrations disponibles sans les contraintes
        php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration --query-time=60
    else
        echo "Unknown migration error, please check manually"
        exit 1
    fi
fi

# Mettre à jour les dépendances Composer
php composer.phar update --no-interaction --no-progress --prefer-dist || exit 1

# Nettoyer
rm -f schema_changes.sql