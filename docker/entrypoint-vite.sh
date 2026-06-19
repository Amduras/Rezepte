#!/bin/sh
set -e

# Node-Abhängigkeiten installieren, falls node_modules fehlt
if [ ! -d "node_modules" ]; then
    echo "📦 node_modules nicht gefunden – führe npm install aus..."
    npm install
else
    echo "✅ node_modules vorhanden – überspringe npm install"
fi

# Hauptprozess starten (Vite Dev-Server)
exec "$@"
