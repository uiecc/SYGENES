#!/bin/bash

# Se déplacer dans le répertoire du projet
cd domains/sygenes.esign.cm/public_html || exit 1

# Mettre à jour le dépôt Git sans interaction
git pull --quiet || exit 1

# Exécuter les migrations en forçant leur application
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration || exit 1

# Mettre à jour les dépendances Composer sans interaction et sans erreur bloquante
php composer.phar update --no-interaction --no-progress --prefer-dist || exit 1
