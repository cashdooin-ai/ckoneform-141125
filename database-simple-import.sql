-- CollegeKampus OneForm - Simple Database Import
-- Just the CREATE TABLE statements - no system queries

CREATE TABLE IF NOT EXISTS `wp_ck_oneform_students` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` varchar(50) NOT NULL,
    `full_name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `mobile` varchar(20) NOT NULL,
    `password` varchar(255) NOT NULL,
    `dob` date DEFAULT NULL,
    `gender` varchar(20) DEFAULT NULL,
    `category` varchar(50) DEFAULT NULL,
    `address` text,
    `state` varchar(100) DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `pincode` varchar(10) DEFAULT NULL,
    `photo_url` varchar(500) DEFAULT NULL,
    `status` varchar(20) DEFAULT 'active',
    `email_verified` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `last_login` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `student_id` (`student_id`),
    UNIQUE KEY `email` (`email`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wp_ck_oneform_student_sessions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `session_token` varchar(255) NOT NULL,
    `ip_address` varchar(50) DEFAULT NULL,
    `user_agent` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `expires_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `session_token` (`session_token`),
    KEY `student_id` (`student_id`),
    KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
