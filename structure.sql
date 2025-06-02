-- Adminer 5.3.0 MariaDB 10.11.11-MariaDB-0+deb12u1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
                          `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(20) NOT NULL,
                          `description` varchar(100) NOT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
                                                       (1,	'admin',	'Administrator'),
                                                       (2,	'members',	'Members');

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `ip_addresses`;
CREATE TABLE `ip_addresses` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `ip_address` varchar(45) DEFAULT NULL,
                                `country` varchar(100) DEFAULT NULL,
                                `country_code` varchar(10) DEFAULT NULL,
                                `city` varchar(100) DEFAULT NULL,
                                `timezone` varchar(100) DEFAULT NULL,
                                `lat_long` varchar(50) DEFAULT NULL,
                                `isp` varchar(100) DEFAULT NULL,
                                `org` varchar(100) DEFAULT NULL,
                                `last_visit` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                `created_at` timestamp NULL DEFAULT current_timestamp(),
                                `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
                                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                  `ip_address` varchar(45) NOT NULL,
                                  `login` varchar(100) NOT NULL,
                                  `time` int(11) unsigned DEFAULT NULL,
                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                           `meta_parent` varchar(254) DEFAULT NULL,
                           `meta_key` varchar(254) NOT NULL,
                           `meta_value` text DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           UNIQUE KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `author` int(11) DEFAULT NULL,
                            `title` varchar(255) NOT NULL,
                            `slug` varchar(255) NOT NULL,
                            `type` varchar(255) NOT NULL DEFAULT 'plugin',
                            `banners` text DEFAULT NULL,
                            `icons` text DEFAULT NULL,
                            `sections` text DEFAULT NULL,
                            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                            `deleted_at` varchar(30) NOT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `package_options`;
CREATE TABLE `package_options` (
                                   `package_id` int(11) NOT NULL,
                                   `option_name` varchar(255) NOT NULL,
                                   `option_value` text DEFAULT NULL,
                                   KEY `package_id` (`package_id`),
                                   CONSTRAINT `package_options_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `package_versions`;
CREATE TABLE `package_versions` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `package_id` int(11) NOT NULL,
                                    `file` text NOT NULL,
                                    `version` varchar(30) NOT NULL,
                                    `metadata` text DEFAULT NULL,
                                    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                    `deleted_at` varchar(30) DEFAULT NULL,
                                    PRIMARY KEY (`id`),
                                    KEY `package_id` (`package_id`),
                                    CONSTRAINT `package_versions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `update_logs`;
CREATE TABLE `update_logs` (
                               `ip` varchar(255) DEFAULT NULL,
                               `date` varchar(255) NOT NULL,
                               `http_method` varchar(30) NOT NULL,
                               `action` varchar(255) NOT NULL,
                               `slug` varchar(255) NOT NULL,
                               `installed_version` varchar(100) DEFAULT NULL,
                               `wp_version` varchar(100) DEFAULT NULL,
                               `php_version` varchar(100) DEFAULT NULL,
                               `site_url` varchar(255) DEFAULT NULL,
                               `access_granted` tinyint(1) DEFAULT 1,
                               `query` text DEFAULT NULL,
                               `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                         `ip_address` varchar(45) NOT NULL,
                         `alnum` varchar(45) NOT NULL,
                         `token` varchar(45) NOT NULL,
                         `username` varchar(100) DEFAULT NULL,
                         `password` varchar(255) NOT NULL,
                         `email` varchar(254) NOT NULL,
                         `activation_selector` varchar(255) DEFAULT NULL,
                         `activation_code` varchar(255) DEFAULT NULL,
                         `forgotten_password_selector` varchar(255) DEFAULT NULL,
                         `forgotten_password_code` varchar(255) DEFAULT NULL,
                         `forgotten_password_time` int(11) unsigned DEFAULT NULL,
                         `remember_selector` varchar(255) DEFAULT NULL,
                         `remember_code` varchar(255) DEFAULT NULL,
                         `created_on` int(11) unsigned NOT NULL,
                         `last_login` int(11) unsigned DEFAULT NULL,
                         `active` tinyint(1) unsigned DEFAULT NULL,
                         `first_name` varchar(50) DEFAULT NULL,
                         `middle_name` varchar(50) DEFAULT NULL,
                         `last_name` varchar(50) DEFAULT NULL,
                         `phone` varchar(20) DEFAULT NULL,
                         `city` varchar(255) DEFAULT NULL,
                         `state` varchar(255) DEFAULT NULL,
                         `avatar` varchar(255) DEFAULT NULL,
                         `two_factor` int(1) NOT NULL DEFAULT 0,
                         `two_factor_secret` varchar(200) DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `uc_email` (`email`),
                         UNIQUE KEY `uc_activation_selector` (`activation_selector`),
                         UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
                         UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `users` (`id`, `ip_address`, `alnum`, `token`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `middle_name`, `last_name`, `phone`, `city`, `state`, `avatar`, `two_factor`, `two_factor_secret`) VALUES
    (1,	'127.0.0.1',	'iiyeiryi',	'dfhjklkhsa',	'administrator',	'$2y$12$UY9dLZhVLfjfFIA79ukWK.fgcF9McHLFPcMkxeiEfisSZrLlgWfOi',	'bennito254@gmail.com',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	1268889823,	1748864982,	1,	'Benjamin',	NULL,	'Muriithi',	'0716483805',	NULL,	NULL,	'TXZaGCNDwS.svg',	0,	NULL);

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
                                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                `user_id` int(11) unsigned NOT NULL,
                                `group_id` mediumint(8) unsigned NOT NULL,
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
                                KEY `fk_users_groups_users1_idx` (`user_id`),
                                KEY `fk_users_groups_groups1_idx` (`group_id`),
                                CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                                CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
    (1,	1,	1);

-- 2025-06-02 13:15:03 UTC
