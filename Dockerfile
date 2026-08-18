FROM php:8.2-fpm

# تثبيت الحزم الأساسية وأدوات النظام
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل داخل السيرفر
WORKDIR /app

# نسخ ملفات المشروع إلى السيرفر
COPY . /app

# تثبيت حزم لارافيل
RUN composer install --no-dev --optimize-autoloader

# صلاحيات المجلدات
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# تشغيل السيرفر
CMD php artisan serve --host=0.0.0.0 --port=$PORT