#!/bin/bash

# Se déplacer dans le répertoire du projet
cd domains/sygenes.esign.cm/public_html || exit 1

# Mettre à jour le dépôt Git sans interaction
git pull --quiet || exit 1

# Vérifier les différences de schéma
echo "Vérification des différences de schéma..."
php bin/console doctrine:schema:update --dump-sql > schema_changes.sql

# Synchroniser les migrations
echo "Synchronisation des migrations..."
php bin/console doctrine:migrations:sync-metadata-storage --no-interaction

# Marquer les migrations précédentes comme exécutées
echo "Marquage des migrations existantes..."
php bin/console doctrine:migrations:version --add --all --no-interaction

# Tenter d'appliquer les migrations de manière sécurisée
if ! php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration; then
    echo "Première tentative de migration échouée, tentative de synchronisation du schéma..."
    
    # Si la migration échoue, essayer de synchroniser le schéma
    if php bin/console doctrine:schema:update --force --no-interaction; then
        echo "Schéma synchronisé avec succès"
        
        # Réessayer les migrations après la synchronisation
        if ! php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration; then
            echo "La migration a échoué même après synchronisation du schéma"
            exit 1
        fi
    else
        echo "La synchronisation du schéma a échoué"
        exit 1
    fi
fi

# Mettre à jour les dépendances Composer
echo "Mise à jour des dépendances..."
php composer.phar update --no-interaction --no-progress --prefer-dist || exit 1

# Nettoyer
rm -f schema_changes.sql

echo "Déploiement terminé avec succès"