#!/usr/bin/env bash
# Crea consegna/wero-theme.zip e consegna/wero-core.zip, pronti da caricare in WordPress.
# Esclude sorgenti dei blocchi, dipendenze di sviluppo, file di build e log.
set -euo pipefail
cd "$(dirname "$0")"
mkdir -p consegna
rm -f consegna/wero-theme.zip consegna/wero-core.zip

zip -qr consegna/wero-theme.zip wero-theme \
  -x 'wero-theme/node_modules/*' '*.log' '*/.DS_Store' '*.map'

zip -qr consegna/wero-core.zip wero-core \
  -x 'wero-core/node_modules/*' 'wero-core/src/*' 'wero-core/package.json' \
     'wero-core/package-lock.json' '*.log' '*/.DS_Store' '*.map'

ls -l consegna/*.zip
