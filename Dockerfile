FROM php:8.3-fpm

# Install system dependencies, PHP extensions, and Composer in one layer
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (zip extension now available)
RUN composer install --no-scripts --no-autoloader

# Copy package.json and install Node dependencies
COPY package.json package-lock.json* ./
RUN npm install

# Copy application code
COPY . .

# Generate autoloader and optimize
RUN composer dump-autoload --optimize

# Build Vite assets for production
RUN npm run build

# Verify build directory exists
RUN ls -la /var/www/html/public/build/ || echo "Build directory not found"

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]
# Fix final permissions
RUN chown -R www-data:www-data /var/www/html/public/build \
    && chmod -R 755 /var/www/html/public/build
