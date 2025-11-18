# Use PHP-FPM with Alpine
FROM php:8.2-fpm-alpine

# Install system packages and PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    zip \
    libzip-dev \
    zlib-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    python3 \
    py3-pip \
    tesseract-ocr \
    tesseract-ocr-data-eng \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        gd \
        dom \
        xml \
        simplexml \
        fileinfo

# Install Python dependencies for OCR
RUN pip3 install --no-cache-dir pytesseract pillow --break-system-packages

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock* ./

# Install PHP dependencies (allow dev for development)
RUN composer install --no-interaction --prefer-dist || true

# Copy the rest of the project
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
