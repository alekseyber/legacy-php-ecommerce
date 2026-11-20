#!/bin/sh
# Первый запуск: в репозитории нет vendor (кроме движка ishop), зависимости
# ставятся из composer.lock прямо в примонтированный каталог проекта.
set -e

if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "composer install: зависимости не найдены, ставлю..."
    cd /var/www/html
    # ext-gd в образе нет: собрать его без apt нельзя (репозитории stretch в архиве).
    # PhpSpreadsheet требует gd формально — для Excel-выгрузок он не нужен,
    # для картинок нужен. Composer 1 не умеет пропускать одно расширение,
    # поэтому пропускаем проверки платформы целиком и пишем об этом в лог.
    if composer install --no-dev --no-interaction --no-progress --ignore-platform-reqs; then
        echo "composer install: готово"
    else
        echo "composer install: не удалось — проверьте сеть и версию PHP"
    fi
fi

mkdir -p /var/www/html/tmp/cache /var/www/html/public/upload
chmod -R 777 /var/www/html/tmp/cache /var/www/html/public/upload 2>/dev/null || true

exec apache2-foreground
