#!/bin/bash

# Instal Composer jika diperlukan (opsional)
if ! [ -x "$(command -v composer)" ]; then
  curl -sS https://getcomposer.org/installer | php
  php composer.phar install --optimize-autoloader --no-dev
fi

# Jalankan perintah Laravel
php artisan config:cache
php artisan route:cache

# Jalankan Vite build
vite build
