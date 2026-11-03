#!/usr/bin/env bash
# Espera a que la aplicacion y la base de datos esten listas para recibir el login.
set -euo pipefail

URL="${BASE_URL:-http://localhost:8080}"
USUARIO="${E2E_USER:-demo}"
CLAVE="${E2E_PASS:-123456}"
INTENTOS="${WAIT_ATTEMPTS:-60}"

for i in $(seq 1 "$INTENTOS"); do
  respuesta="$(curl -s -X POST "$URL/ajax/usuarios.ajax.php" \
    --data-urlencode "ingUsuario=$USUARIO" \
    --data-urlencode "ingPassword=$CLAVE" \
    --data "conectar=" || true)"

  if echo "$respuesta" | grep -q "Redireccionando"; then
    echo "Aplicacion lista en $URL (intento $i)."
    exit 0
  fi
  echo "Esperando la aplicacion... intento $i/$INTENTOS"
  sleep 2
done

echo "La aplicacion no respondio dentro del tiempo esperado." >&2
docker compose logs --tail=100 >&2 || true
exit 1
