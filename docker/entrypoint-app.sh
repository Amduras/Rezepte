#!/bin/sh
set -e

# Composer-Abhängigkeiten installieren, falls vendor-Ordner fehlt
if [ ! -d "vendor" ]; then
    echo "📦 vendor-Ordner nicht gefunden – führe composer install aus..."
    composer install --no-interaction --optimize-autoloader
else
    echo "✅ vendor-Ordner vorhanden – überspringe composer install"
fi

# Hauptprozess starten (php-fpm)
exec "$@"
