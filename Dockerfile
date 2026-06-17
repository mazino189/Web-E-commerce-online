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
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip bcmath

# Kích hoạt module rewrite của Apache (cho Laravel Routing)
RUN a2enmod rewrite

# Trỏ Document Root của Apache vào thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Sao chép mã nguồn vào container
WORKDIR /var/www/html
COPY . .

# Copy the startup script
COPY start.sh /var/www/html/start.sh

# Fix Windows CRLF line endings to Linux LF
RUN sed -i -e 's/\r$//' /var/www/html/start.sh

# Grant execute permissions
RUN chmod +x /var/www/html/start.sh

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Chạy composer install
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs
RUN npm install 
RUN npm run build

# Chown necessary directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng kết nối 80 (Render tự động map)
EXPOSE 80

CMD ./start.sh