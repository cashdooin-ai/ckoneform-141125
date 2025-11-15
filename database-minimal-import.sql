-- CollegeKampus OneForm - MINIMAL Database Tables (Just for Student Registration)
-- This file contains only the 2 tables needed for student registration to work
-- Instructions:
-- 1. Open phpMyAdmin
-- 2. Select your database (u939138857_bxqfM)
-- 3. Click on "SQL" tab
-- 4. Copy and paste this entire file
-- 5. Click "Go" to execute
-- 6. Test registration at /student-login/

-- Students Table (Required for Registration)
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

-- Student Sessions Table (Required for Login)
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

-- Verify tables were created
SELECT
    CASE
        WHEN COUNT(*) = 2 THEN '✓ SUCCESS: Both tables created! You can now register students at /student-login/'
        ELSE '✗ ERROR: Some tables missing. Please check for errors above.'
    END AS 'Status'
FROM information_schema.tables
WHERE table_schema = 'u939138857_bxqfM'
AND table_name IN ('wp_ck_oneform_students', 'wp_ck_oneform_student_sessions');
