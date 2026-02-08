# Dockerfile (FIX) - Laravel on Railway
FROM php:8.2-cli

# Install system dependencies + PHP extensions needed for Laravel/MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
  && docker-php-ext-install pdo pdo_mysql zip \
  && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /app

# Copy project files
COPY . .

# Install PHP deps (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# IMPORTANT:
# - Jangan jalankan "php artisan key:generate" di sini
#   karena .env tidak ada saat build di Railway.
# - APP_KEY akan kamu set dari Railway Variables.

# Railway menyediakan PORT via env, jadi pakai $PORT
CMD php artisan serve --host=0.0.0.0 --port ${PORT:-8080}
