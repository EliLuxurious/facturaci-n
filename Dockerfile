FROM php:8.1-apache

# Directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema requeridas para las extensiones PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensiones de PHP (requeridas por SUNAT UBL 2.1)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        soap \
        zip \
        gd \
        bcmath

# Habilitar mod_rewrite para el enrutamiento de .htaccess
RUN a2enmod rewrite

# Copiar configuraciones personalizadas de Apache y PHP
COPY docker/apache.conf /etc/apache2/conf-available/custom.conf
RUN a2enconf custom

COPY docker/php.ini $PHP_INI_DIR/conf.d/custom.ini

# Copiar Composer (para gestión de dependencias y autoload)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar script de inicio
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN tr -d '\r' < /usr/local/bin/entrypoint.sh > /usr/local/bin/entrypoint_clean.sh \
    && mv /usr/local/bin/entrypoint_clean.sh /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Copiar el código del proyecto al contenedor
COPY . /var/www/html

# Crear carpetas necesarias y asignar permisos
RUN mkdir -p api/xml api/cdr api/certificado vistas/img \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 api/xml api/cdr api/certificado vistas/img

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]

