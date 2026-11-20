#!/usr/bin/env bash
# Avvia WordPress Playground con il tema e il plugin del progetto montati dalla cartella locale.
# Le modifiche ai file di wero-theme e wero-core si vedono subito, senza riavviare.
# Utente: admin / password — http://127.0.0.1:9400
set -euo pipefail
cd "$(dirname "$0")"
npx -y @wp-playground/cli@latest server \
  --blueprint=./blueprint.json \
  --mount-before-install="$PWD/wero-theme:/wordpress/wp-content/themes/wero-theme" \
  --mount-before-install="$PWD/wero-core:/wordpress/wp-content/plugins/wero-core" \
  --port=9400 --login
