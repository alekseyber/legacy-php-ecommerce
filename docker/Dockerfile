# PHP 7.0 + Apache для локального стенда.
# Код использует оператор ?? (PHP 7), поэтому база 7.0.
# Расширения собираются из исходников, входящих в образ: apt в stretch больше
# не работает (репозитории в архиве), а для запуска магазина хватает pdo_mysql
# и mbstring. gd не собирается — поэтому загрузка изображений из админки на
# стенде не проверяется (это ограничение, а не «работает»).
FROM php:7.0-apache

# composer 1.x — последняя ветка, работающая на PHP 7.0
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo_mysql mbstring
# unzip нужен composer'у для распаковки пакетов (zip-расширения в образе нет,
# а apt в stretch не работает). Ставим .deb напрямую из архива Debian.
RUN curl -fsSL http://archive.debian.org/debian/pool/main/u/unzip/unzip_6.0-21+deb9u2_amd64.deb -o /tmp/unzip.deb \
 && dpkg -i /tmp/unzip.deb \
 && rm -f /tmp/unzip.deb


RUN a2enmod rewrite && \
    { echo 'short_open_tag = On'; \
      echo 'display_errors = On'; \
      echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT'; \
      echo 'date.timezone = Europe/Moscow'; \
      echo 'upload_max_filesize = 32M'; \
      echo 'post_max_size = 40M'; \
      echo 'memory_limit = 256M'; \
    } > /usr/local/etc/php/conf.d/zz-ishop.ini && \
    sed -ri 's#AllowOverride None#AllowOverride All#' /etc/apache2/apache2.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
