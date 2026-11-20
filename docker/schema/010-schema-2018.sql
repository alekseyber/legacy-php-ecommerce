-- Схема БД магазина по дампу от 24.09.2018 (слепок того же дня).
-- Только структура: данные вырезаны, в исходном дампе были реальные записи.
-- 41 таблица. Служит точкой отсчёта для сравнения с 2023 годом.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acquirers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(15) NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `metods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `metods_id` (`metods_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apimetods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `askquestion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acquirers_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `geo_city` varchar(150) NOT NULL,
  `geo_region` text NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `color` tinyint(1) NOT NULL DEFAULT '0',
  `sizes` tinyint(1) NOT NULL DEFAULT '0',
  `filter` tinyint(1) NOT NULL DEFAULT '1',
  `cartproduct` tinyint(1) NOT NULL DEFAULT '0',
  `alias` varchar(20) NOT NULL,
  `sortvalue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `delnot` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`) USING BTREE,
  KEY `filter` (`filter`),
  KEY `status` (`status`),
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `attrgr_id` int(10) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `bitmask` int(64) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`attrgr_id`) USING BTREE,
  KEY `bitmask` (`bitmask`),
  KEY `attrgr_id` (`attrgr_id`),
  CONSTRAINT `attribute_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attribute_product_ibfk_2` FOREIGN KEY (`attrgr_id`) REFERENCES `attribute_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `attr_group_id` int(10) unsigned NOT NULL,
  `sdvig` tinyint(3) NOT NULL DEFAULT '0',
  `sortvalue` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`),
  KEY `attr_group_id` (`attr_group_id`),
  KEY `sortvalue` (`sortvalue`),
  KEY `status` (`status`),
  CONSTRAINT `attribute_value_ibfk_1` FOREIGN KEY (`attr_group_id`) REFERENCES `attribute_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bagdeproduct` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(15) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `fixed` enum('0','1') NOT NULL DEFAULT '0',
  `redcolor` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blockip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(11) NOT NULL,
  `ipstr` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'brand_no_image.jpg',
  `description` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `htitle` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `meta_title` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `promo` text NOT NULL,
  `content` text NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `cat_default` tinyint(1) NOT NULL DEFAULT '0',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoryproduct` (
  `product_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`) USING BTREE,
  KEY `category_id` (`category_id`),
  CONSTRAINT `categoryproduct_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `categoryproduct_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cityrus` (
  `ID` int(11) NOT NULL,
  `CityName` varchar(50) NOT NULL,
  `OblName` varchar(27) DEFAULT NULL,
  `NalSumLimit` varchar(8) NOT NULL,
  `cityDD` int(11) NOT NULL,
  `pvzCode` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `CityName` (`CityName`),
  KEY `OblName` (`OblName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `rustitle` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `sortvalue` smallint(5) unsigned NOT NULL DEFAULT '0',
  `search_str` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `value` float unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` date NOT NULL DEFAULT '2100-12-31',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `date_end` (`date_end`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol_left` varchar(10) NOT NULL,
  `symbol_right` varchar(75) NOT NULL,
  `value` float(15,2) NOT NULL,
  `base` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modification_id` int(10) unsigned NOT NULL,
  `img` varchar(255) NOT NULL,
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `zoomon` tinyint(1) NOT NULL DEFAULT '1',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sortvalue` (`sortvalue`),
  KEY `modification_id` (`modification_id`) USING BTREE,
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`modification_id`) REFERENCES `modification` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `getparams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `deluri` enum('0','1') NOT NULL DEFAULT '0',
  `setsession` enum('0','1') NOT NULL DEFAULT '0',
  `setcookie` enum('0','1') NOT NULL DEFAULT '0',
  `cookietime` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) DEFAULT NULL,
  `onlyint` enum('0','1') NOT NULL DEFAULT '0',
  `metods_id` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `metods_id` (`metods_id`),
  CONSTRAINT `getparams_ibfk_1` FOREIGN KEY (`metods_id`) REFERENCES `metods` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `indexproduct` (
  `ids` varchar(10) NOT NULL,
  `sortvalue` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `index_search` text NOT NULL,
  PRIMARY KEY (`ids`),
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lastmodified` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controler` varchar(100) NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  `expires_index` int(10) NOT NULL DEFAULT '0',
  `update_at` datetime NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controler` (`controler`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metods` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `color_id` int(10) unsigned NOT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no_image.jpg',
  `imgto` tinyint(1) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `old_price` float NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `bagdeproduct_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`color_id`) USING BTREE,
  KEY `status` (`status`),
  KEY `bagdeproduct_id` (`bagdeproduct_id`),
  CONSTRAINT `modification_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modificationregistr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modification_id` int(10) unsigned NOT NULL,
  `sizes_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modification_id` (`modification_id`,`sizes_id`) USING BTREE,
  KEY `sizes_id` (`sizes_id`),
  CONSTRAINT `modificationregistr_ibfk_1` FOREIGN KEY (`modification_id`) REFERENCES `modification` (`id`) ON DELETE CASCADE,
  CONSTRAINT `modificationregistr_ibfk_2` FOREIGN KEY (`sizes_id`) REFERENCES `sizes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `status` (`status`),
  KEY `update_at` (`update_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordernum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderproduct` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `registr_id` int(10) unsigned NOT NULL,
  `img_thumb` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `registr_id` (`registr_id`),
  CONSTRAINT `orderproduct_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orderproduct_ibfk_2` FOREIGN KEY (`registr_id`) REFERENCES `registr` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderstatus_id` smallint(6) unsigned NOT NULL DEFAULT '1',
  `acquirers_id` int(10) unsigned NOT NULL,
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
  `ip` int(10) unsigned NOT NULL,
  `cupon_id` int(10) unsigned NOT NULL DEFAULT '0',
  `delivery` text NOT NULL,
  `delivery_pvz` text NOT NULL,
  `note_admin` text NOT NULL,
  `dat_deliv` date NOT NULL,
  `time_deliv` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `metro` varchar(100) NOT NULL,
  `cdek` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderstatus_id` (`orderstatus_id`),
  KEY `acquirers_id` (`acquirers_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`orderstatus_id`) REFERENCES `orderstatus` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`acquirers_id`) REFERENCES `acquirers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(100) NOT NULL,
  `sortvalue` smallint(6) NOT NULL,
  `action` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_model` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1-(цвет, размер); 2-(цвет); 3-(цвет один); 4-(цвет один с разм)',
  `brand_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `seolink` varchar(255) NOT NULL,
  `sku` varchar(20) DEFAULT NULL,
  `gender` enum('Женские','Мужские','Детские') NOT NULL,
  `content` text,
  `price` float NOT NULL DEFAULT '0',
  `old_price` float NOT NULL DEFAULT '0',
  `cart_on` enum('1','2') NOT NULL DEFAULT '1' COMMENT 'В корзине предлагать',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `hit` enum('1','2') NOT NULL DEFAULT '2',
  `sizesgroup_id` tinyint(3) NOT NULL,
  `color_default` varchar(50) DEFAULT '''che''',
  `related` int(11) NOT NULL DEFAULT '0',
  `promo_id` int(10) unsigned NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `hit` (`hit`),
  KEY `price` (`price`),
  KEY `sku` (`sku`),
  KEY `title` (`title`),
  KEY `update_at` (`update_at`),
  KEY `product_model` (`product_model`),
  KEY `cart_on` (`cart_on`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link_title` text NOT NULL,
  `content` text NOT NULL,
  `content_url` varchar(255) DEFAULT NULL,
  `delnot` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `modification_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modificationregistr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`modification_id`,`modificationregistr_id`) USING BTREE,
  KEY `amount` (`amount`),
  CONSTRAINT `registr_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sef` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sortvalue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `search_str` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`) USING BTREE,
  KEY `sortvalue` (`sortvalue`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizesgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `link_title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizesgroupselect` (
  `sizesgroup_id` int(10) unsigned NOT NULL,
  `sizes_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sizes_id`,`sizesgroup_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `js` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slidervalue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slider_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `img` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sortvalue` (`sortvalue`),
  KEY `slider_id` (`slider_id`),
  CONSTRAINT `slidervalue_ibfk_1` FOREIGN KEY (`slider_id`) REFERENCES `slider` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sortgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sortvalue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `sortgroup_id` int(10) unsigned NOT NULL,
  `tip` enum('0','1','2') NOT NULL DEFAULT '1',
  `col_name` varchar(100) NOT NULL,
  `sortvalue` tinyint(3) NOT NULL DEFAULT '0',
  `sort_default` enum('0','1') NOT NULL DEFAULT '0',
  `sost` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sortvalue_ibfk_1` (`sortgroup_id`),
  KEY `sortvalue` (`sortvalue`),
  CONSTRAINT `sortvalue_ibfk_1` FOREIGN KEY (`sortgroup_id`) REFERENCES `sortgroup` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

