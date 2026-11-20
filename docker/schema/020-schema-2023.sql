-- Структура БД магазина из дампа uggirus_winter.sql (phpMyAdmin 4.9.7,
-- 13.07.2023, MySQL 5.7.21). Только схема, данные и INSERT'ы вырезаны —
-- в исходном дампе были реальные заказы, письма и 169 телефонных номеров.
-- 51 таблица. Четыре сущности, которые встречаются в коде, но отсутствуют
-- и здесь, восстанавливаются по моделям: productclass, options, optionsgrupp,
-- related_product.

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `acquirers` (
  `id` int(11) UNSIGNED NOT NULL,
  `phone` varchar(15) NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `api` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `metods_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `apimetods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `askquestion` (
  `id` int(10) UNSIGNED NOT NULL,
  `acquirers_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `geo_city` varchar(150) NOT NULL,
  `geo_region` text NOT NULL,
  `ip` bigint(20) UNSIGNED NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `attribute_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `color` tinyint(1) NOT NULL DEFAULT '0',
  `sizes` tinyint(1) NOT NULL DEFAULT '0',
  `filter` tinyint(1) NOT NULL DEFAULT '1',
  `cartproduct` tinyint(1) NOT NULL DEFAULT '0',
  `radio` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(20) NOT NULL,
  `sortvalue` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `delnot` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `tablegrupp` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `tablenpbinary` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `attribute_product` (
  `id` int(10) NOT NULL,
  `attrgr_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `bitmask` int(64) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `attribute_value` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `attr_group_id` int(10) UNSIGNED NOT NULL,
  `sdvig` tinyint(3) NOT NULL DEFAULT '0',
  `sortvalue` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `carton` tinyint(1) NOT NULL DEFAULT '0',
  `tablegr_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bagdeproduct` (
  `id` int(10) NOT NULL,
  `title` varchar(15) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `fixed` enum('0','1') NOT NULL DEFAULT '0',
  `redcolor` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `blockip` (
  `id` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `ipstr` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `brand` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'brand_no_image.jpg',
  `description` varchar(255) DEFAULT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `htitle` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `parent_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `meta_title` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `promo` text NOT NULL,
  `content` text NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `img_thumb` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `cat_default` tinyint(1) NOT NULL DEFAULT '0',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `maintop` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categoryproduct` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `citypage` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `city_name` varchar(150) NOT NULL,
  `city_bname` varchar(150) NOT NULL,
  `city_cname` varchar(150) NOT NULL,
  `city_dname` varchar(150) NOT NULL,
  `city_iname` varchar(150) NOT NULL,
  `city_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `promo` text NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `content_on` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cityrus` (
  `ID` int(11) NOT NULL,
  `CityName` varchar(50) NOT NULL,
  `OblName` varchar(27) DEFAULT NULL,
  `NalSumLimit` varchar(8) NOT NULL,
  `cityDD` int(11) NOT NULL,
  `pvzCode` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `citysettings` (
  `id` smallint(3) UNSIGNED NOT NULL,
  `content_top` text NOT NULL,
  `content_bottom` text NOT NULL,
  `divel_title` varchar(255) NOT NULL,
  `selected` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `color` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `rustitle` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `sortvalue` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `search_str` varchar(255) NOT NULL,
  `tablegr_id` int(10) UNSIGNED NOT NULL DEFAULT '13',
  `attr_group_id` int(10) UNSIGNED NOT NULL DEFAULT '5',
  `tags` varchar(500) NOT NULL,
  `colorkey` varchar(25) NOT NULL DEFAULT '#111'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `colorya` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `sortvalue` smallint(6) NOT NULL DEFAULT '0',
  `alias` varchar(10) NOT NULL,
  `tags` varchar(500) NOT NULL,
  `colorkey` varchar(25) NOT NULL DEFAULT '#111'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comment` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `social` tinyint(1) NOT NULL DEFAULT '0',
  `author_name` varchar(150) NOT NULL,
  `author_avatar` varchar(255) NOT NULL,
  `author_provider` varchar(10) NOT NULL,
  `author_uid` varchar(100) NOT NULL,
  `commen_text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(150) NOT NULL,
  `value` float UNSIGNED NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` date NOT NULL DEFAULT '2100-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol_left` varchar(10) NOT NULL,
  `symbol_right` varchar(75) NOT NULL,
  `value` float(15,2) NOT NULL,
  `base` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `modification_id` int(10) UNSIGNED NOT NULL,
  `img` varchar(255) NOT NULL,
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `zoomon` tinyint(1) NOT NULL DEFAULT '1',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `getparams` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deluri` enum('0','1') NOT NULL DEFAULT '0',
  `setsession` enum('0','1') NOT NULL DEFAULT '0',
  `setcookie` enum('0','1') NOT NULL DEFAULT '0',
  `cookietime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pattern` varchar(255) DEFAULT NULL,
  `onlyint` enum('0','1') NOT NULL DEFAULT '0',
  `metods_id` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `headerstr` (
  `id` int(10) UNSIGNED NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  `orderdone` text NOT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `indexproduct` (
  `ids` varchar(10) NOT NULL,
  `sortvalue` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `index_search` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lastmodified` (
  `id` int(10) UNSIGNED NOT NULL,
  `controler` varchar(100) NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  `expires_index` int(10) NOT NULL DEFAULT '0',
  `update_at` datetime NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `metods` (
  `id` tinyint(3) NOT NULL,
  `title` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `modification` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `color_id` int(10) UNSIGNED NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `imgto` tinyint(1) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `old_price` float NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `bagdeproduct_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `modificationregistr` (
  `id` int(10) UNSIGNED NOT NULL,
  `modification_id` int(10) UNSIGNED NOT NULL,
  `sizes_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `promo` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `content` text NOT NULL,
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ordernum` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_num` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orderproduct` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `registr_id` int(10) UNSIGNED NOT NULL,
  `img_thumb` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `link` varchar(255) NOT NULL,
  `nosizeselect` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `orderstatus_id` smallint(6) UNSIGNED NOT NULL DEFAULT '1',
  `acquirers_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL,
  `currency` varchar(10) NOT NULL,
  `order_num` varchar(255) NOT NULL,
  `note` text,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `geo_city` varchar(150) NOT NULL,
  `geo_region` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `ip` bigint(20) UNSIGNED NOT NULL,
  `cupon_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `delivery` text NOT NULL,
  `delivery_pvz` text NOT NULL,
  `note_admin` text NOT NULL,
  `dat_deliv` date NOT NULL,
  `time_deliv` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `metro` varchar(100) NOT NULL,
  `cdek` varchar(12) NOT NULL,
  `utm_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orderstatus` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `status` varchar(100) NOT NULL,
  `sortvalue` smallint(6) NOT NULL,
  `action` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `page` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `promo` text NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `params` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `value` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `pattern` varchar(310) NOT NULL,
  `typevalue` enum('string','int','bool') NOT NULL DEFAULT 'string'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `product` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_model` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1-(цвет, размер);

CREATE TABLE `promo` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `link_title` text NOT NULL,
  `content` text NOT NULL,
  `content_url` varchar(255) DEFAULT NULL,
  `delnot` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `registr` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `modification_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `modificationregistr_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sef` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sortvalue` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `search_str` varchar(255) NOT NULL,
  `rudop` varchar(20) DEFAULT NULL,
  `tablegr_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `attr_group_id` int(10) UNSIGNED NOT NULL DEFAULT '7',
  `tags` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sizesgroup` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `link_title` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sizesgroupselect` (
  `sizesgroup_id` int(10) UNSIGNED NOT NULL,
  `sizes_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `slider` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `js` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `slidervalue` (
  `id` int(10) UNSIGNED NOT NULL,
  `slider_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sortgroup` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sortvalue` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `sortgroup_id` int(10) UNSIGNED NOT NULL,
  `tip` enum('0','1','2') NOT NULL DEFAULT '1',
  `col_name` varchar(100) NOT NULL,
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0',
  `sort_default` enum('0','1') NOT NULL DEFAULT '0',
  `sost` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tablegrupp` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tablenobinary` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `utm` (
  `id` int(11) NOT NULL,
  `key_item` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `promo` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `all_on` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vozvrats` (
  `id` int(10) UNSIGNED NOT NULL,
  `acquirers_id` int(10) UNSIGNED NOT NULL,
  `geo_city` varchar(150) NOT NULL,
  `geo_region` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `ip` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `action` varchar(15) NOT NULL,
  `orders` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
