FROM php:8.4-apache

# 1. تثبيت الحزم الأساسية للنظام
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. تثبيت إضافات PHP المحتاجة لـ Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# 3. توجيه سيرفر Apache إلى مجلد public الخاص بـ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. تفعيل مود الـ Rewrite في Apache لعمل روابط Laravel (Routing)
RUN a2enmod rewrite

# 5. تحديد مجلد العمل ونسخ الملفات
WORKDIR /var/www/html
COPY . .

# 6. تثبيت الـ Composer وجلب المكتبات
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. إعطاء الصلاحيات المناسبة لمجلدات التخزين والكاش
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 🚀 السحر هنا: تشغيل الميغريشن تلقائياً فوراً عند إقلاع السيرفر ثم تشغيل Apache
CMD sh -c "php artisan migrate --force && apache2-foreground"