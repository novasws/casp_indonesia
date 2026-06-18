FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && a2enmod rewrite \
    && a2dissite 000-default

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Apache config to point to /public
RUN printf '<VirtualHost *:80>\n    DocumentRoot /var/www/html/public\n    <Directory /var/www/html/public>\n        AllowOverride All\n        Require all granted\n        <IfModule mod_rewrite.c>\n            RewriteEngine On\n            RewriteCond %%{REQUEST_FILENAME} !-f\n            RewriteCond %%{REQUEST_FILENAME} !-d\n            RewriteRule ^ index.php [L]\n        </IfModule>\n    </Directory>\n    <Directory /var/www/html/public/storage>\n        <IfModule mod_rewrite.c>\n            RewriteEngine Off\n        </IfModule>\n    </Directory>\n    ErrorLog /var/log/apache2/error.log\n    CustomLog /var/log/apache2/access.log combined\n</VirtualHost>' > /etc/apache2/sites-available/casp.conf \
    && a2ensite casp

# Create storage link during build
RUN php artisan storage:link || true

EXPOSE 80

CMD php artisan migrate --force; apache2-foreground
