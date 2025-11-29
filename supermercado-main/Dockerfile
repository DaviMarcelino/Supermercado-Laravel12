FROM php:8.2-fpm

WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip curl zip libpng-dev libonig-dev libxml2-dev npm nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copiar todo el proyecto
COPY . .

# Instalar dependencias PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --prefer-dist --no-interaction

# Compilar assets (CSS, JS, etc.)
RUN npm install && npm run build

# Generar claves y cachear config (APP_KEY lo debes pasar desde Render)
RUN php artisan config:cache

# Puerto que expone Laravel
EXPOSE 8000
RUN chmod -R 775 storage bootstrap/cache

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
