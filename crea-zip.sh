#!/usr/bin/env bash
# Crea in consegna/ i tre pacchetti da caricare in WordPress:
#   wero-theme.zip      tema (solo aspetto)
#   wero-core.zip       plugin del sito (senza sorgenti dei blocchi e dipendenze di sviluppo)
#   wero-installer.zip  plugin temporaneo che importa i contenuti iniziali (contiene contenuti/)
set -euo pipefail
cd "$(dirname "$0")"
mkdir -p consegna
rm -f consegna/wero-theme.zip consegna/wero-core.zip consegna/wero-installer.zip

zip -qr consegna/wero-theme.zip wero-theme \
  -x 'wero-theme/node_modules/*' '*.log' '*/.DS_Store' '*.map'

zip -qr consegna/wero-core.zip wero-core \
  -x 'wero-core/node_modules/*' 'wero-core/src/*' 'wero-core/package.json' \
     'wero-core/package-lock.json' '*.log' '*/.DS_Store' '*.map'

# Installer: il file del plugin + una copia di contenuti/ nella stessa cartella.
tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT
mkdir -p "$tmp/wero-installer"
cp installer/wero-installer.php "$tmp/wero-installer/"
cp -R contenuti "$tmp/wero-installer/contenuti"
find "$tmp" -name '.DS_Store' -delete
(cd "$tmp" && zip -qr wero-installer.zip wero-installer)
mv "$tmp/wero-installer.zip" consegna/

ls -l consegna/*.zip
