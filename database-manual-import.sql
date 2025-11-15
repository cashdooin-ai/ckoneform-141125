-- CollegeKampus OneForm - Manual Database Table Creation
-- Instructions:
-- 1. Open phpMyAdmin
-- 2. Select your database (u939138857_bxqfM)
-- 3. Click on "SQL" tab
-- 4. Copy and paste this entire file
-- 5. Click "Go" to execute
-- 6. Verify tables are created

-- Students Table
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

-- Student Sessions Table
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

-- Applications Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_applications` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `user_id` bigint(20) UNSIGNED DEFAULT 0,
    `form_id` bigint(20) UNSIGNED NOT NULL,
    `application_number` varchar(50) NOT NULL,
    `college_ids` text,
    `status` varchar(50) DEFAULT 'pending',
    `form_data` longtext,
    `submission_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `admin_notes` text,
    PRIMARY KEY (`id`),
    UNIQUE KEY `application_number` (`application_number`),
    KEY `student_id` (`student_id`),
    KEY `user_id` (`user_id`),
    KEY `form_id` (`form_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Submissions Meta Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_submissions_meta` (
    `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `submission_id` bigint(20) UNSIGNED NOT NULL,
    `meta_key` varchar(255) DEFAULT NULL,
    `meta_value` longtext,
    PRIMARY KEY (`meta_id`),
    KEY `submission_id` (`submission_id`),
    KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_payments` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_id` bigint(20) UNSIGNED NOT NULL,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `user_id` bigint(20) UNSIGNED DEFAULT 0,
    `transaction_id` varchar(100) DEFAULT NULL,
    `amount` decimal(10,2) NOT NULL,
    `currency` varchar(10) DEFAULT 'INR',
    `payment_method` varchar(50) DEFAULT NULL,
    `status` varchar(50) DEFAULT 'pending',
    `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `application_id` (`application_id`),
    KEY `student_id` (`student_id`),
    KEY `user_id` (`user_id`),
    KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_documents` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_id` bigint(20) UNSIGNED NOT NULL,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `user_id` bigint(20) UNSIGNED DEFAULT 0,
    `document_type` varchar(100) DEFAULT NULL,
    `file_name` varchar(255) DEFAULT NULL,
    `file_path` varchar(500) DEFAULT NULL,
    `file_size` bigint(20) DEFAULT NULL,
    `uploaded_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `application_id` (`application_id`),
    KEY `student_id` (`student_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_services` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text,
    `service_type` varchar(50) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT 0.00,
    `features` text,
    `icon` varchar(100) DEFAULT NULL,
    `status` varchar(20) DEFAULT 'active',
    `display_order` int(11) DEFAULT 0,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mock Tests Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_mock_tests` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text,
    `exam_type` varchar(100) DEFAULT NULL,
    `duration` int(11) DEFAULT NULL,
    `total_questions` int(11) DEFAULT NULL,
    `total_marks` int(11) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT 0.00,
    `thumbnail` varchar(500) DEFAULT NULL,
    `status` varchar(20) DEFAULT 'active',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`),
    KEY `exam_type` (`exam_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Offers Table
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_offers` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text,
    `offer_type` varchar(50) DEFAULT NULL,
    `discount_value` decimal(10,2) DEFAULT NULL,
    `discount_type` varchar(20) DEFAULT NULL,
    `valid_from` datetime DEFAULT NULL,
    `valid_until` datetime DEFAULT NULL,
    `terms` text,
    `banner_image` varchar(500) DEFAULT NULL,
    `target_students` text,
    `status` varchar(20) DEFAULT 'active',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`),
    KEY `valid_from` (`valid_from`),
    KEY `valid_until` (`valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student Services Table (Purchased Services)
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_student_services` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `service_id` bigint(20) UNSIGNED NOT NULL,
    `order_id` varchar(100) DEFAULT NULL,
    `amount_paid` decimal(10,2) DEFAULT NULL,
    `status` varchar(50) DEFAULT 'active',
    `purchased_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `expires_date` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `student_id` (`student_id`),
    KEY `service_id` (`service_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student Mock Tests Table (Purchased/Attempted Tests)
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_student_tests` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `test_id` bigint(20) UNSIGNED NOT NULL,
    `order_id` varchar(100) DEFAULT NULL,
    `amount_paid` decimal(10,2) DEFAULT NULL,
    `status` varchar(50) DEFAULT 'purchased',
    `attempts` int(11) DEFAULT 0,
    `best_score` decimal(5,2) DEFAULT NULL,
    `purchased_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `last_attempt_date` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `student_id` (`student_id`),
    KEY `test_id` (`test_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student Offers Table (Assigned Offers)
CREATE TABLE IF NOT EXISTS `wp_ck_oneform_student_offers` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` bigint(20) UNSIGNED NOT NULL,
    `offer_id` bigint(20) UNSIGNED NOT NULL,
    `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
    `is_used` tinyint(1) DEFAULT 0,
    `used_date` datetime DEFAULT NULL,
    `assigned_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `student_id` (`student_id`),
    KEY `offer_id` (`offer_id`),
    KEY `is_used` (`is_used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SUCCESS MESSAGE
SELECT 'All tables created successfully! Now test student registration at /student-login/' AS 'Status';
