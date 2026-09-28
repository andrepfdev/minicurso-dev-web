#!/usr/bin/env bash
# Verifica se a máquina tem tudo que o minicurso precisa.
set -e

echo "== PHP =="
php -v || echo "PHP não encontrado!"
echo
echo "-- Extensões necessárias --"
for ext in intl mbstring sqlite3 pdo_sqlite; do
  if php -m | grep -qi "^${ext}$"; then
    echo "[OK] $ext"
  else
    echo "[FALTA] $ext"
  fi
done

echo
echo "== Composer =="
composer -V || echo "Composer não encontrado!"

echo
echo "== Node / npm =="
node -v || echo "Node não encontrado!"
npm -v || echo "npm não encontrado!"

echo
echo "Verificação concluída."
