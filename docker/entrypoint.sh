#!/bin/bash
set -e

# Only run setup if we're starting the main app (supervisord)
if [[ "$1" == *"supervisord"* ]]; then
    php /var/www/artisan storage:link --force 2>/dev/null || true
    php /var/www/artisan config:cache
    php /var/www/artisan route:cache
    php /var/www/artisan view:cache
fi

exec "$@"
