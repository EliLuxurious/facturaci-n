#!/usr/bin/env bash
# Mutation testing manual de MPR-001 (complemento obligatorio del preventivo, Tabla 6).
# Cada mutante deshace una parte de la proteccion; si alguna prueba @preventivo falla,
# el mutante queda "muerto" (la suite lo detecta). Al final se restauran los archivos.
set -u
cd "$(dirname "$0")/.."
salida=resultados/mutantes-mpr001-${1:-ronda}.csv
echo "mutante;descripcion;resultado;pruebas_que_fallaron" > "$salida"

correr() {
  local id="$1" desc="$2"
  local json fallidas
  json=$(npx playwright test --grep @preventivo --reporter=json 2>/dev/null)
  fallidas=$(node -e '
    const r = JSON.parse(require("fs").readFileSync(0, "utf8"));
    const f = [];
    const rec = (s) => { (s.specs||[]).forEach(sp => sp.tests.forEach(t => { if (t.status === "unexpected") f.push(sp.title.split(" ")[0]); })); (s.suites||[]).forEach(rec); };
    r.suites.forEach(rec); console.log(f.join(" "));' <<< "$json")
  if [ -n "$fallidas" ]; then res=muerto; else res=vivo; fi
  echo "$id;$desc;$res;$fallidas" | tee -a "$salida"
  git checkout -q -- .htaccess Conect/Conexion.php
}

sed -i '/^RewriteRule (\^|\/)\\\./d' .htaccess
correr M1 "Quitar la regla que bloquea archivos ocultos (.env .git)"

sed -i '/^RewriteRule \^(BD|docker/d' .htaccess
correr M2 "Quitar la regla que bloquea carpetas internas (BD api/certificado ...)"

sed -i '/<FilesMatch/,/<\/FilesMatch>/d' .htaccess
correr M3 "Quitar el bloqueo por extension (sql pfx env ...)"

sed -i '/^RewriteRule (\^|\/)\\\./d; /^RewriteRule \^(BD|docker/d; /<FilesMatch/,/<\/FilesMatch>/d' .htaccess
correr M4 "Quitar las tres protecciones del .htaccess"

sed -i "s|^namespace Conect;|namespace Conect;\necho 'DB_USER=facturacion_app DB_PASSWORD=facturacion_app_dev';|" Conect/Conexion.php
correr M5 "Conexion.php imprime las credenciales al cargarse"

sed -i "s|\$user = self::config('DB_USER');|\$user = self::config('DB_USER', 'root');|; s|\$pass = self::config('DB_PASSWORD', '');|\$pass = self::config('DB_PASSWORD', 'root');|" Conect/Conexion.php
correr M6 "Volver a dejar credenciales escritas en el codigo (root/root por defecto)"
