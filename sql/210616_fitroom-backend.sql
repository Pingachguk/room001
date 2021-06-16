-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `club-metro`;
CREATE TABLE `club-metro` (
  `club_id` bigint unsigned NOT NULL,
  `metro_id` bigint unsigned NOT NULL,
  KEY `club_metro_club_id_foreign` (`club_id`),
  KEY `club_metro_metro_id_foreign` (`metro_id`),
  CONSTRAINT `club_metro_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `club_metro_metro_id_foreign` FOREIGN KEY (`metro_id`) REFERENCES `metros` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `clubs`;
CREATE TABLE `clubs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned NOT NULL,
  `gk_id` bigint unsigned DEFAULT NULL,
  `filial_id` bigint unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apikey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clubs_name_unique` (`name`),
  UNIQUE KEY `clubs_club_id_unique` (`club_id`),
  UNIQUE KEY `clubs_apikey_unique` (`apikey`),
  KEY `clubs_city_id_foreign` (`city_id`),
  KEY `clubs_gk_id_foreign` (`gk_id`),
  KEY `clubs_filial_id_foreign` (`filial_id`),
  CONSTRAINT `clubs_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `clubs_filial_id_foreign` FOREIGN KEY (`filial_id`) REFERENCES `filials` (`id`),
  CONSTRAINT `clubs_gk_id_foreign` FOREIGN KEY (`gk_id`) REFERENCES `g_k_s` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clubs` (`id`, `created_at`, `updated_at`, `name`, `city_id`, `gk_id`, `filial_id`, `address`, `club_id`, `apikey`) VALUES
(6,	'2021-06-15 15:38:03',	'2021-06-15 15:38:03',	'Маршала Блюхера',	3,	NULL,	1,	'пр-т Маршала Блюхера, д.6, к.2',	'bf1e201a-01c3-11eb-bbdb-005056838e97',	'24955a4d-0467-4886-ba0b-1f54d5a50bb2'),
(7,	'2021-06-15 15:41:32',	'2021-06-15 15:41:32',	'наб.реки Смоленки',	3,	4,	1,	'В.О наб.реки Смоленки, д.3, к.1',	'a23e2522-e7ad-11ea-bbd8-005056838e97',	'c164bc33-4dfe-4235-bae7-0b5aa38f2452'),
(8,	'2021-06-15 15:43:30',	'2021-06-15 15:43:30',	'Выборгское шоссе 17',	3,	5,	1,	'Выборгское шоссе д.17, к.1',	'e64f0bc2-0652-11eb-bbdb-005056838e97',	'a955977a-1177-48a5-a12a-70199432c5cb'),
(9,	'2021-06-15 15:49:00',	'2021-06-15 15:49:00',	'Радуга',	3,	6,	1,	'Загребский бульвар, д.9',	'22bd71b4-e7af-11ea-bbd8-005056838e97',	'1799c52b-a66a-4187-ac5a-073e4515ec46'),
(10,	'2021-06-15 15:50:48',	'2021-06-15 15:50:48',	'Кудрово',	3,	NULL,	1,	'Каштановая аллея, д.2',	'd987364c-e7ae-11ea-bbd8-005056838e97',	'0cc5eb3f-a77e-406d-a212-aa3d514415d3'),
(11,	'2021-06-15 15:52:00',	'2021-06-15 15:52:00',	'Лыжный переулок',	3,	NULL,	1,	'Лыжный переулок, д.2',	'630de9d9-e7ae-11ea-bbd8-005056838e97',	'19d06469-3a5e-43f9-977c-ca18324a31e1'),
(12,	'2021-06-15 15:54:15',	'2021-06-15 15:54:15',	'Донской проезд',	4,	NULL,	1,	'3-й Донской проезд, д.1',	'cb28be84-0651-11eb-bbdb-005056838e97',	'bf10824c-dab7-413d-addf-cc80c4111576'),
(13,	'2021-06-15 15:56:33',	'2021-06-15 15:56:33',	'Граф Орлов',	3,	7,	1,	'пр-т Московский, д.183-185',	'39158ee8-e79e-11ea-bbd8-005056838e97',	'1a5a6f3b-4504-40b7-b286-14941fd2f635'),
(14,	'2021-06-15 15:58:00',	'2021-06-15 15:58:00',	'Премьер Палас',	3,	NULL,	1,	'ул. Ждановская, д.43, к.1',	'd2c3b3d6-e7ad-11ea-bbd8-005056838e97',	'9c5a3bb6-16b1-48f3-9e78-84299a619fda'),
(15,	'2021-06-15 15:59:32',	'2021-06-15 15:59:32',	'Фламинго',	3,	9,	1,	'пр-т Маршала Блюхера, д.9, к.1',	'37948207-e7ae-11ea-bbd8-005056838e97',	'31b8db60-cb60-457f-bed0-69c9a3a65e84'),
(16,	'2021-06-15 16:00:38',	'2021-06-15 16:00:38',	'Космос',	3,	10,	1,	'пр-т Юрия Гагарина, д.7',	'64b8476a-e7ad-11ea-bbd8-005056838e97',	'6a8a7d85-4836-4ace-a495-9a5262975b83'),
(17,	'2021-06-15 16:03:29',	'2021-06-15 16:03:29',	'Пушкин',	6,	NULL,	1,	'Московское шоссе, 34',	'915d3c3b-daff-11ea-bbd8-005056838e97',	'e25f4e9d-92ec-4750-9b84-8655bb89f0ff'),
(18,	'2021-06-15 16:04:36',	'2021-06-15 16:04:36',	'Ренессанс',	3,	11,	1,	'ул. Дыбенко,д.8, к.3',	'603cb73d-e7af-11ea-bbd8-005056838e97',	'f7bd7973-287a-4a0e-a706-745b25758182'),
(19,	'2021-06-15 16:06:35',	'2021-06-15 16:06:35',	'Текстильщик',	3,	NULL,	1,	'ул. Красного Текстильщика, д.7',	'42632fd3-e7af-11ea-bbd8-005056838e97',	'bfbce456-def7-43d0-a8f4-725176c67341'),
(20,	'2021-06-15 16:07:45',	'2021-06-15 16:07:45',	'Парковый переулок',	5,	NULL,	1,	'Парковый переулок, 5',	'15281aec-07c8-11eb-bbdb-005056838e97',	'bf54b61c-9039-4df2-b837-d85c6f0cbf4f'),
(21,	'2021-06-15 16:08:43',	'2021-06-15 16:08:43',	'Путилково',	4,	NULL,	1,	'д.Путилково ул.Новотушинская д.2',	'b9ea102a-0ec0-11eb-bbdb-005056838e97',	'f8e758e6-effe-4516-9ae1-34a946a0d5b9'),
(22,	'2021-06-15 16:09:47',	'2021-06-15 16:09:47',	'Автозаводская',	4,	NULL,	1,	'ул.Автозаводская д.23Б',	'129d154c-0ec1-11eb-bbdb-005056838e97',	'f3525e34-b232-4c1e-a2b7-11ae35f8bbaf');

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `filials`;
CREATE TABLE `filials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filials_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `g_k_s`;
CREATE TABLE `g_k_s` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `g_k_s_name_unique` (`name`),
  KEY `g_k_s_city_id_foreign` (`city_id`),
  CONSTRAINT `g_k_s_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `metros`;
CREATE TABLE `metros` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metros_name_unique` (`name`),
  KEY `metros_city_id_foreign` (`city_id`),
  CONSTRAINT `metros_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `telescope_entries`;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `telescope_entries_tags`;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `telescope_monitoring`;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-06-16 07:58:57
