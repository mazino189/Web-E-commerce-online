FROM php:8.3-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Kích hoạt module rewrite của Apache (cho Laravel Routing)
RUN a2enmod rewrite

# Trỏ Document Root của Apache vào thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Optimized Docker Cache Layers: Copy lock files first
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-scripts --no-autoloader --no-dev --ignore-platform-reqs

# Copy root NPM if needed
COPY package.json package-lock.json* ./
RUN npm install

# Copy everything else
COPY . .

# Complete composer setup
RUN composer dump-autoload --optimize

# Build frontend/root dependencies
RUN npm run build

# Fix Windows CRLF line endings and grant execute permissions
RUN sed -i -e 's/\r$//' start.sh && chmod +x start.sh

# Chown necessary directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng kết nối 80 (Render tự động map)
EXPOSE 80

CMD ./start.sh