<?php

return [
    'null_city_delivery' => [], // массив id СДЭК городов, в которых доставка = 0, пример ['137' => true,] - Санкт-Петербург
    'smtp_host' => 'smtp.beget.com',
    'smtp_port' => '465',
    'smtp_protocol' => 'ssl',
    'smtp_login' => 'no-replay@example.com',
    'smtp_password' => '',
    'seo_link_on' => true, //ссылки страниц на основе seo_link таблица sef - ОТКЛЮЧАТЬ НЕ РЕКОМЕНДУЕТСЯ
    'prefix_link_on' => true, //добавляет / к относительной ссылке продуктов и категорий
    'on_breadcrumbs_all_product' => true, //добавить в хлебные крошки каталога ссылку все товары
    'on_opengraph' => true, //Включает мета теги Open Graph к каталогам товарам и новостям
    'img_property' => [// для товаров
        '1500' => ['path' => '/images/product/1500/', 'img_width' => 1500, 'img_height' => 1500, 'superbig' => true, 'data' => ''],
        '1000' => ['path' => '/images/product/1000/', 'img_width' => 1000, 'img_height' => 1000, 'superbig' => false, 'data' => 'main'], //большое по умолчанию
        '300' => ['path' => '/images/product/300/', 'img_width' => 300, 'img_height' => 300, 'superbig' => false, 'data' => 'thumb'], //thumb по умолчанию
        '160' => ['path' => '/images/product/160/', 'img_width' => 160, 'img_height' => 160, 'superbig' => false, 'data' => ''],
    ],
    'img_original_path' => '/images/product/original/', //папка для исходных загруженных товаров
    'img_parsimg_path' => '/images/product/parsimg/', //папка для спарсенных фото
    'url_parsimg_prefix' => 'https://www.ugg.com/dw/image/v2/AAFF_PRD/on/demandware.static/-/Sites-masterCatalogUgg/default/dwb9ac1ca1/images/grey/', //начало url img оф сайте
    'url_parsimg_postfix' => '?sw=1500', //конец url img оф сайте
    'img_category_path' => '/images/category/',
    'img_category_original_path' => 'images/category/original/', //папка для исходных загруженных категорий
    'img_category_property' => [// для категорий
        '150' => ['path' => '/images/category/', 'img_width' => 386, 'img_height' => 515, 'superbig' => false, 'data' => 'thumb'],
    ],
    'img_thumb_category_property' => [// для категорий thumb
        '150' => ['path' => '/images/category/', 'img_width' => 50, 'img_height' => 50, 'superbig' => false, 'data' => 'thumb'],
    ],
    'img_news_path' => '/images/news/',
    'img_news_original_path' => '/images/news/original/',
    'img_news_property' => [
        '410' => ['path' => '/images/news/', 'img_width' => 410, 'img_height' => 410, 'superbig' => false, 'data' => 'thumb'],
    ],
    'img_srsset_on' => true, //включить srsset в фото товаров
    'detectadresip_on' => true, //вычислять регион посетителя через модуль GEOIP в момент сохранения заказа
    'cdekProductSize' => [
        'weight' => 2,
        'length' => 38,
        'width' => 32,
        'height' => 2
    ], //сдек габариты 1-й единицы товара
    'cdekTarifPriority' => [
        'courier' => [233, 137],
        'pickup' => [234, 136],
    ], //сдек приоритет тарифов
    'recomacces_qty' => 3, //сколько раз одному покупателю показать рекомендованые товары в форме добавлено в корзину не задействован
    'recomacces_count' => 2, //количество рекомендованных товаров в форме добавлено в корзину не задействован
    'cachetime_filter' => 36000, //время хранения кеша (текстовые файлы в разделе tmp/cache)
    'cachetime_category' => 36000,
    'cachetime_productids' => 36000,
    'cachetime_getparams' => 36000,
    'cachetime_menu' => 36000,
    'cachetime_menu_tree' => 36000,
    'cachetime_sort' => 36000,
    'cachetime_top_slider' => 36000,
    'cachetime_maincontent' => 36000,
    'cachetime_brand' => 36000,
    'cachetime_sizegroup' => 36000,
    'cachetime_promo' => 36000,
    'cachetime_recomacces' => 36000,
    'cachetime_product_content' => 36000,
    'filter_colorrustitle' => true, //выводить русские названия цветов в фильтре
    'filter_gr_on' => true, //Включить режим группировки параметров фильтра
    'js_link_true' => true, //загрузка через аякс для этого класса ссылок
    'js_link_true2' => true, //загрузка через аякс для этого класса ссылок
    'serch_base_str' => 'купить угги ugg угг сапожки дешево дёшево распродажа см', //добавить к строке индекса эти слова
    'serch_descr_cat_on' => false, //включать в индекс descr каталога
    'serch_title_rus_on' => false, //выводить русские названия цветов в результатах поиска
    'serch_title_del' => ['Сапоги', 'Полусапоги'], //удалить эти слова из титла результатов поиска
    'reCAPTCHA' => ['key' => '', 'secret' => ''],
    'vk_key' => ['id' => '6846539', 'secret' => '', 'service_key' => ''],
    'fb_key' => ['id' => '339183753605982', 'secret' => ''],
    'time_delive' => [//для заказа выбор диапазонов времени доставки
        0 => 'не задано',
        1 => '8 - 10',
        2 => '10 - 12',
        3 => '12 - 14',
        4 => '14 - 16',
        5 => '16 - 18',
        6 => '18 - 20',
        7 => '20 - 22',
    ],
    'last-modified_header_on' => true, //отдавать заголовок last-modified   
    'prefix_tags' => 'угги ', //добавит к сборному тегу в анкор перед в конце добавить пробел
    'webp_on' => 1, //включает webp для фото товаров, категорий, новостей. 0 - выкл, 1 - вкл
    'webp_quality' => 60, //качество webp для фото товаров, категорий, новостей. 60 - 100
    'dop_menu_items' => [
        ['href' => '/delivery', 'title' => 'Доставка и оплата'],
        ['href' => '/news', 'title' => 'Блог'],
        ['href' => '/vozvrat', 'title' => 'Вернуть/обменять'],
        ['href' => '/sizechart', 'title' => 'Размерная сетка'],
        ['href' => '/comment', 'title' => 'Отзывы'],
        ['href' => '/contacts', 'title' => 'Контакты'],
    ], //дополнительные пункты меню - массив, выводится в шапке и футере    
    'main_inf_b_items' => [
        ['icon' => 'account_balance_wallet', 'text' => 'Оплата после примерки'],
        ['icon' => 'alarm', 'text' => 'Доставка в день заказа'],
        ['icon' => 'update', 'text' => 'Возврат/обмен - 30 дней'],
        ['icon' => 'looks_3', 'text' => '3 пары на выбор'],
        ['icon' => 'history_toggle_off', 'text' => '65 дней гарантия'],
        ['icon' => 'star_rate', 'text' => '100% натуральная овчина'],
    ], //инф баннер на главной данные
    'main_sale_b_data' => [
        'background-color' => '#ffc107',
        'color' => '#000',
        'lines' => ['Еще больше товаров, еще больше скидок!', 'Распродажа: скидки до -70%'],
    ], // инф баннер на главной между хитами и новинками данные    
    'product_tr_items' => [
        ['img' => '/images/b_div.png', 'text' => 'Быстрая доставка'],
        ['img' => '/images/b_pr.png', 'text' => 'Примерка перед покупкой'],
        ['img' => '/images/b_ret.png', 'text' => 'Гарантия, обмен/возврат'],
        ['img' => '/images/b_off.png', 'text' => 'Натуральная овчина'],
    ], //значки тригеров в карточке товара
    'filter_content' => '', //не изменять, резевирование ключа, обновляется программно
    //=========Перенесено в BD===========35
//    'admin_email' => 'orders@example.com', //add
//    'shop_name' => 'Демо-магазин', //add
//    'shop_name_rus' => 'Винтер-Бутс', //add
//    'shop_tel' => '8 (800) 000-00-00', //add
//    'shop_tel_link' => '+7 999 000-00-00', //add
//    'shop_email' => 'sales@example.com', //add
//    'shop_adress' => 'г. Москва, ул. Примерная, д. 1', //add
//    'shop_work' => 'Ежедневно c 9 до 20', //add
//    'price_null_home_delivery' => 5000, // от скольки по Москве доставка бесплатно //add
//    'price_null_region_delivery' => 10000, // от скольки по России доставка бесплатно //add
//    'pagination' => 20, //количество товаров на странице пагинации //add
//    'pagination_news' => 24, //количество новостей на странице пагинации //add
//    'pagination_comment' => 6, //количество отзывов на странице пагинации //add
//    'filter_default_uri' => 'ugg-all', //категория для фильтра по умолчанию если он не в разделе каталога //add
//    'name_default_uri' => 'Все товары', //названии категории для фильтра по умолчанию //add
//    'hit_count' => 8, //сколько хитов на главной странице //add
//    'divel_add' => 200, //сколько рублей добавить к стоимости доставки вычесленной калькулятором сдека //add
//    'cdekSenderCityId' => 44, //сдэк город отправки //add
//    'cdekModeDeliveryId' => 3, //сдэк режим доставки (дверь-дверь=1, дверь-склад=2, склад-дверь=3, склад-склад=4) //add
//    'cdekAccount' => '', //СДЭК API Account //add
//    'cdekKey' => '', //СДЭК API KEY  //add
//    'product_meta_title' => '@product_gender@ угги @product_name@ @brand_title@ @product_price@ - @product_price_discont_proc@', //add // шаблон мета для продукта, если он не заполнен подставится этот
//    'product_meta_description' => '@product_gender@ угги @product_name@ @product_price_discont_procoi@. @colors@ Интернет-магазин @shop_name@, телефон: @shop_tel@', //add
//    'product_meta_keywords' => '@brand_title@ @product_name@', //add
//    'city_meta_title' => 'Купить угги в @city_iname@ не дорого', // шаблон мета для города, если он не заполнен подставится этот //add
//    'city_meta_description' => 'Доставка обуви и аксессуаров UGG Australia в @city_name@ занимает максимум @deliveryPeriodMax@ - стоимость @deliveryPriceCurrency@', //add
//    'city_meta_keywords' => 'угги @city_name@, купить угги в @city_iname@, ugg @city_name@ оригинал', //add
//    'divel_hour_podtv' => 18, //час подтверждения заказа когда сегодня можно обработать - выводится в разделе достака //add
//    'ya_map_api_key' => '', // Yandex Map API KEY //add
//    'main_inf_b_on' => true, //вкл/выкл баннера на главной //add
//    'newpr_count' => 8, //сколько новинок на главной странице //add
//    'sales_count' => 0, //сколько распродаж на главной странице //add
//    'main_comment_count' => 5, //сколько отзывов на главной странице //add
//    'main_sale_b_on' => true, //вкл/выкл баннера на главной между хитами и новинками //add
//    'product_tr_on' => true, //вкл/выкл значков тригеров в карточке товара //add
];


