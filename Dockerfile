# Menggunakan image PHP 8.1
FROM php:8.1-cli

# Install dependensi sistem yang diperlukan (termasuk Composer dan ekstensi PHP untuk Laravel)
RUN apt-get update && apt-get install -y \
    libssl-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Menyalin seluruh aplikasi ke dalam container
COPY . /app
WORKDIR /app

# Install dependensi PHP menggunakan Composer
RUN composer install --no-dev --optimize-autoloader

# Menjalankan server PHP built-in
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
