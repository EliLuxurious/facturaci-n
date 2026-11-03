#!/bin/bash
# MPR-001 (preventivo): crea el usuario que usa el sistema, con permisos minimos.
# Se ejecuta automaticamente la primera vez que se crea el contenedor de MariaDB.
set -e

mariadb -uroot -p"${MYSQL_ROOT_PASSWORD}" <<SQL
CREATE USER IF NOT EXISTS '${DB_APP_USER}'@'%' IDENTIFIED BY '${DB_APP_PASSWORD}';
-- Operaciones normales del sistema
GRANT SELECT, INSERT, UPDATE, DELETE ON \`${MYSQL_DATABASE}\`.* TO '${DB_APP_USER}'@'%';
-- "Pasar a produccion" vacia estas tablas con TRUNCATE (requiere DROP)
GRANT DROP ON \`${MYSQL_DATABASE}\`.venta TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.detalle TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.nota_credito TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.nota_credito_detalle TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.nota_debito TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.nota_debito_detalle TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.envio_resumen TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.envio_resumen_detalle TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.guia TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.guia_detalle TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.pago_credito TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.detalle_cotizaciones TO '${DB_APP_USER}'@'%';
GRANT DROP ON \`${MYSQL_DATABASE}\`.cotizaciones TO '${DB_APP_USER}'@'%';
-- (abono_cuota se crea despues, en 04_; queda cubierta por el GRANT sobre .* de arriba)
-- El sistema agrega columnas faltantes en estas tablas al actualizarse
GRANT ALTER ON \`${MYSQL_DATABASE}\`.emisor TO '${DB_APP_USER}'@'%';
GRANT ALTER ON \`${MYSQL_DATABASE}\`.guia TO '${DB_APP_USER}'@'%';
FLUSH PRIVILEGES;
SQL
