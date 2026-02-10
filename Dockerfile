FROM php:8.2-cli

# 1) Cài package hệ thống + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev npm \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath exif pcntl gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3) Thư mục làm việc
WORKDIR /app
# 4) Copy source
COPY . .

# 6) Build Vite assets
RUN npm install

# 7) Quyền ghi cho Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# 8) Port cho Render
EXPOSE 10000

# 9) Lệnh chạy app
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
