FROM php:8.0-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /app
WORKDIR /app

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader

# Set the entrypoint
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
