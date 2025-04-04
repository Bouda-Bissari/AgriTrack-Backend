#!/bin/sh

# Générer la clé Laravel si elle n'existe pas
if [ ! -f .env ]; then
  cp .env.example .env
fi

php artisan key:generate

# Exécuter les migrations (décommenter si nécessaire)
# php artisan migrate --force

exec "$@"