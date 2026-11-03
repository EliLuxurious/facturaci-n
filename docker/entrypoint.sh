#!/bin/sh
set -e

# Asegurar carpetas requeridas para comprobantes, CDRs y certificados
mkdir -p /var/www/html/api/xml \
         /var/www/html/api/cdr \
         /var/www/html/api/certificado \
         /var/www/html/vistas/img

# Asignar permisos de escritura para el servidor web
chmod -R 777 /var/www/html/api/xml \
             /var/www/html/api/cdr \
             /var/www/html/api/certificado \
             /var/www/html/vistas/img 2>/dev/null || true

# Ejecutar el comando principal (Apache)
exec "$@"

