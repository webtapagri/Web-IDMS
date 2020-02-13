/*
SQLyog Enterprise v12.4.1 (64 bit)
MySQL - 5.7.25 : Database - IDMS
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `TBM_MENU` */

DROP TABLE IF EXISTS `TBM_MENU`;

CREATE TABLE `TBM_MENU` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `menu_code` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(50) NOT NULL,
  `sort` int(2) NOT NULL,
  `description` mediumtext,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `city_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Table structure for table `TBM_MODULE` */

DROP TABLE IF EXISTS `TBM_MODULE`;

CREATE TABLE `TBM_MODULE` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` mediumtext,
  `sort` int(2) DEFAULT NULL,
  `icon` varchar(124) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `city_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Table structure for table `TBM_ROLE` */

DROP TABLE IF EXISTS `TBM_ROLE`;

CREATE TABLE `TBM_ROLE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` mediumtext,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 PACK_KEYS=0;

/*Table structure for table `TBM_ROLE_ACCESS` */

DROP TABLE IF EXISTS `TBM_ROLE_ACCESS`;

CREATE TABLE `TBM_ROLE_ACCESS` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `create` char(1) NOT NULL DEFAULT '0',
  `read` char(1) NOT NULL DEFAULT '0',
  `update` char(1) NOT NULL DEFAULT '0',
  `delete` char(1) NOT NULL DEFAULT '0',
  `grant` char(1) DEFAULT '0',
  `description` mediumtext,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_by` varchar(64) DEFAULT NULL,
  `deleted_on` date DEFAULT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `city_name` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=486 DEFAULT CHARSET=utf8;

/*Table structure for table `TBM_USER` */

DROP TABLE IF EXISTS `TBM_USER`;

CREATE TABLE `TBM_USER` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `NIK` char(16) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'this field is using for get data username on select2',
  `username` char(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `img` longblob,
  `job_code` varchar(32) NOT NULL,
  `area_code` varchar(124) NOT NULL,
  `date_log` date DEFAULT NULL,
  `time_log` time DEFAULT NULL,
  `st_log` char(1) DEFAULT NULL,
  `status` char(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `group` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 PACK_KEYS=0;

/*Table structure for table `TM_AFDELING` */

DROP TABLE IF EXISTS `TM_AFDELING`;

CREATE TABLE `TM_AFDELING` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `afdeling_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `afdeling_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `estate_id` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `deleted_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `region_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `werks` int(11) DEFAULT NULL,
  `werks_afd_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_afdeling_estate_id_foreign` (`estate_id`),
  CONSTRAINT `tm_afdeling_estate_id_foreign` FOREIGN KEY (`estate_id`) REFERENCES `TM_ESTATE` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1623 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_BLOCK` */

DROP TABLE IF EXISTS `TM_BLOCK`;

CREATE TABLE `TM_BLOCK` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `block_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `block_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `deleted_by` int(10) unsigned NOT NULL,
  `afdeling_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `region_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estate_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `werks` int(11) DEFAULT NULL,
  `werks_afd_block_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude_block` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude_block` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_valid` date NOT NULL,
  `end_valid` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_block_afdeling_id_foreign` (`afdeling_id`),
  CONSTRAINT `tm_block_afdeling_id_foreign` FOREIGN KEY (`afdeling_id`) REFERENCES `TM_AFDELING` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5828 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_COMPANY` */

DROP TABLE IF EXISTS `TM_COMPANY`;

CREATE TABLE `TM_COMPANY` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `region_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `national` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_ESTATE` */

DROP TABLE IF EXISTS `TM_ESTATE`;

CREATE TABLE `TM_ESTATE` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estate_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `estate_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `werks` int(11) DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_estate_company_id_foreign` (`company_id`),
  CONSTRAINT `tm_estate_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `TM_COMPANY` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_GENERAL_DATA` */

DROP TABLE IF EXISTS `TM_GENERAL_DATA`;

CREATE TABLE `TM_GENERAL_DATA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GENERAL_CODE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DESCRIPTION_CODE` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DESCRIPTION` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `STATUS` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_ROAD` */

DROP TABLE IF EXISTS `TM_ROAD`;

CREATE TABLE `TM_ROAD` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `werks` int(11) NOT NULL,
  `afdeling_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `road_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `road_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status_pekerasan` tinyint(1) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estate_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `total_length` int(11) NOT NULL,
  `asset_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `segment` int(11) NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afdeling_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_road_status_id_foreign` (`status_id`),
  KEY `tm_road_category_id_foreign` (`category_id`),
  KEY `tm_road_werks_index` (`werks`),
  CONSTRAINT `tm_road_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `TM_ROAD_CATEGORY` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tm_road_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `TM_ROAD_STATUS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3470709 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_ROAD_CATEGORY` */

DROP TABLE IF EXISTS `TM_ROAD_CATEGORY`;

CREATE TABLE `TM_ROAD_CATEGORY` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_id` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_code` int(11) NOT NULL,
  `category_initial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_road_category_status_id_foreign` (`status_id`),
  CONSTRAINT `tm_road_category_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `TM_ROAD_STATUS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_ROAD_STATUS` */

DROP TABLE IF EXISTS `TM_ROAD_STATUS`;

CREATE TABLE `TM_ROAD_STATUS` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `updated_by` int(10) unsigned NOT NULL,
  `status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_code` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TM_TOKEN` */

DROP TABLE IF EXISTS `TM_TOKEN`;

CREATE TABLE `TM_TOKEN` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` text COLLATE utf8_unicode_ci,
  `valid_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TR_PERIOD` */

DROP TABLE IF EXISTS `TR_PERIOD`;

CREATE TABLE `TR_PERIOD` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `werks` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `month` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TR_ROAD_LOG` */

DROP TABLE IF EXISTS `TR_ROAD_LOG`;

CREATE TABLE `TR_ROAD_LOG` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `road_id` int(10) unsigned NOT NULL,
  `total_length` int(11) NOT NULL,
  `asset_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_road_log_road_id_foreign` (`road_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2928 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TR_ROAD_PAVEMENT_PROGRESS` */

DROP TABLE IF EXISTS `TR_ROAD_PAVEMENT_PROGRESS`;

CREATE TABLE `TR_ROAD_PAVEMENT_PROGRESS` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `road_id` int(10) unsigned NOT NULL,
  `length` int(11) NOT NULL,
  `month` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_road_pavement_progress_road_id_index` (`road_id`),
  CONSTRAINT `tr_road_pavement_progress_road_id_foreign` FOREIGN KEY (`road_id`) REFERENCES `TM_ROAD` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1218057 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `TR_ROAD_STATUS` */

DROP TABLE IF EXISTS `TR_ROAD_STATUS`;

CREATE TABLE `TR_ROAD_STATUS` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `road_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `road_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `road_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `segment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_road_status_status_id_foreign` (`status_id`),
  KEY `tr_road_status_category_id_foreign` (`category_id`),
  KEY `tr_road_status_road_id_foreign` (`road_id`),
  CONSTRAINT `tr_road_status_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `TM_ROAD_CATEGORY` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tr_road_status_road_id_foreign` FOREIGN KEY (`road_id`) REFERENCES `TM_ROAD` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tr_road_status_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `TM_ROAD_STATUS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2930 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
