-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 05:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test-hrcdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ปกติ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `date`, `check_in`, `check_out`, `status`, `created_at`, `updated_at`) VALUES
(6, 2, '2026-05-12', '08:59:40', '17:01:36', 'ปกติ', '2026-05-12 01:59:40', '2026-05-12 10:01:36'),
(7, 3, '2026-05-12', '09:00:36', '17:00:53', 'ปกติ', '2026-05-12 02:00:36', '2026-05-12 10:00:53'),
(8, 4, '2026-05-12', '09:27:32', '15:00:25', 'มาสาย', '2026-05-12 02:27:32', '2026-05-12 08:00:25'),
(9, 1, '2026-05-12', '09:29:48', '16:52:30', 'มาสาย', '2026-05-12 02:29:48', '2026-05-12 09:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES
(1, 'สาขากระนวน', 'ถนนเส้นน้ำพอง-กระนวน', '2026-05-15 05:59:37', '2026-05-18 08:18:46'),
(2, 'สาขาโนนทัน (สำนักงานใหญ่)', 'ตั้งอยู่ ถ.ชัยพฤกษ์ ต.ในเมือง อ.เมืองขอนแก่น', '2026-05-15 07:08:15', '2026-05-15 07:08:15'),
(4, 'สาขาเลี่ยงเมือง (ใกล้ มข.)', 'ต.ศิลา อ.เมืองขอนแก่น', '2026-05-15 07:09:16', '2026-05-15 07:09:16'),
(5, 'ตรอ.ไอดี สาขาศรีจันทร์', 'ถ.ศรีจันทร์ ต.ในเมือง อ.เมืองขอนแก่น (เน้นตรวจสภาพรถ)', '2026-05-15 07:09:16', '2026-05-15 07:09:16'),
(6, 'ตรอ.ไอดี สาขามิตรภาพ', 'ต.ในเมือง อ.เมืองขอนแก่น (เน้นตรวจสภาพรถ)', '2026-05-15 07:09:16', '2026-05-15 07:09:16'),
(7, 'สาขามหาสารคาม', 'ซ.นครสวรรค์ 66 ต.ตลาด อ.เมืองมหาสารคาม', '2026-05-15 07:09:16', '2026-05-15 07:09:16'),
(8, 'สาขาโคราช', 'ตรงข้ามย่าโม', '2026-05-20 07:41:26', '2026-05-20 07:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#0d6efd',
  `description` text DEFAULT NULL,
  `target_department` varchar(255) DEFAULT 'ทั้งหมด',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `title`, `start`, `end`, `color`, `description`, `target_department`, `created_at`, `updated_at`) VALUES
(3, 'ไปเอาเงินเดือน', '2026-05-15', NULL, '#0d6efd', NULL, 'ทั้งหมด', '2026-05-14 07:33:30', '2026-05-14 07:33:30'),
(4, 'วันขึ้นปีใหม่', '2026-01-01', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(5, 'วันหยุดพิเศษ (ปีใหม่)', '2026-01-02', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(6, 'วันมาฆบูชา', '2026-03-03', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(7, 'วันจักรี', '2026-04-06', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(8, 'วันสงกรานต์', '2026-04-13', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(9, 'วันสงกรานต์', '2026-04-14', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(10, 'วันสงกรานต์', '2026-04-15', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(11, 'วันแรงงานแห่งชาติ', '2026-05-01', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(12, 'วันฉัตรมงคล', '2026-05-04', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(13, 'ชดเชยวันวิสาขบูชา', '2026-06-01', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(14, 'วันเฉลิมฯ พระบรมราชินี', '2026-06-03', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(15, 'วันเฉลิมฯ พระบาทสมเด็จพระเจ้าอยู่หัว', '2026-07-28', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(16, 'วันอาสาฬหบูชา', '2026-07-29', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(17, 'วันแม่แห่งชาติ', '2026-08-12', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(18, 'วันนวมินทรมหาราช', '2026-10-13', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(19, 'วันปิยมหาราช', '2026-10-23', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(20, 'ชดเชยวันพ่อแห่งชาติ', '2026-12-07', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(21, 'วันรัฐธรรมนูญ', '2026-12-10', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(22, 'วันสิ้นปี', '2026-12-31', NULL, '#dc3545', NULL, 'ทั้งหมด', '2026-05-14 07:42:00', '2026-05-14 07:42:00'),
(26, 'หยุด', '2026-05-22', NULL, '#dc3545', 'กูสั่ง', 'ทรัพยากรบุคคล (HR)', '2026-05-20 08:31:18', '2026-05-20 08:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author_name`, `comment_text`, `created_at`, `updated_at`) VALUES
(4, 12, 'game', 'ผมติดแล้วครับ', '2026-05-14 03:02:39', '2026-05-14 03:02:39'),
(5, 12, 'NAMES', 'ไปครับ', '2026-05-14 03:02:56', '2026-05-14 03:02:56'),
(6, 12, 'game', 'ผมติดแล้วครับ', '2026-05-14 03:03:04', '2026-05-14 03:03:04'),
(7, 14, 'ฟิล์ม', 'ไปครับ', '2026-05-14 03:40:46', '2026-05-14 03:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ทรัพยากรบุคคล (HR)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(2, 'เทคโนโลยีสารสนเทศ (IT)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(3, 'บัญชีและการเงิน (Accounting)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(4, 'การตลาด (Marketing)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(5, 'ฝ่ายขาย (Sales)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(6, 'ผู้จัดการ(Manager)', '2026-05-18 07:32:18', '2026-05-18 16:44:16'),
(7, 'ธุรการทั่วไป (General Admin)', '2026-05-18 07:32:18', '2026-05-18 07:32:18'),
(9, 'CEO', '2026-05-18 07:41:34', '2026-05-18 07:41:34'),
(10, 'ผอ.ฝ่าย', '2026-05-26 07:12:22', '2026-05-26 07:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_extension` varchar(255) DEFAULT NULL,
  `doc_number` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `to_position` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `approver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approver_2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reject_reason` text DEFAULT NULL,
  `cc_users` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `title`, `category`, `file_name`, `file_size`, `file_extension`, `doc_number`, `branch`, `department`, `to_position`, `amount`, `content`, `approver_id`, `approver_2_id`, `status`, `reject_reason`, `cc_users`, `created_at`, `updated_at`) VALUES
(1, NULL, 'เอกสารการลา', 'แบบฟอร์มการลา', '1778561733_Leave_Form_Mockup.pdf', '0.02 MB', 'pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-05-12 04:55:33', '2026-05-12 04:55:33'),
(2, NULL, 'เอกสารภาษี/การเงิน', 'เอกสารภาษี/การเงิน', '1778561765_Tax_Finance_Mockup.pdf', '0.02 MB', 'pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-05-12 04:56:05', '2026-05-12 04:56:05'),
(3, NULL, 'สวัสดิการ/ประกันภัย', 'สวัสดิการ/ประกันภัย', '1778561796_Welfare_Insurance_Mockup.pdf', '0.02 MB', 'pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-05-12 04:56:36', '2026-05-12 04:56:36'),
(78, 2, 'ขอเบิกจ่าย', 'บันทึกข้อความภายใน', NULL, NULL, NULL, 'บข.004/2569', NULL, 'เทคโนโลยีสารสนเทศ (IT)', 'test2 (CEO - CEO)', 1500.01, '<p><strong><u>หฟดฟหดฟหดหฟดหฟหดฟเฟหด</u></strong></p>', 4, NULL, 'pending_step_2', NULL, '[\"2\"]', '2026-06-03 10:56:57', '2026-06-03 11:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `documents_file`
--

CREATE TABLE `documents_file` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_extension` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reject_reason` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `reject_reason`, `attachment`, `created_at`, `updated_at`) VALUES
(4, 3, 'ลากิจ', '2026-05-12', '2026-05-14', 'ไปงานศพครับ', 'approved', NULL, NULL, '2026-05-12 02:11:56', '2026-05-12 02:12:15'),
(5, 4, 'ลาป่วย', '2026-05-12', '2026-05-13', 'ป่วยไข้', 'rejected', NULL, NULL, '2026-05-12 05:07:42', '2026-05-13 03:03:41'),
(6, 3, 'ลากิจ', '2026-05-14', '2026-05-15', 'gasagasdagasgetyhfdfdsdvsvsf', 'approved', NULL, NULL, '2026-05-13 09:47:37', '2026-05-13 09:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_ip`, `created_at`, `updated_at`) VALUES
(7, 12, '127.0.0.1', '2026-05-14 03:02:25', '2026-05-14 03:02:25'),
(8, 14, '127.0.0.1', '2026-05-14 03:40:50', '2026-05-14 03:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_06_154106_create_employees_table', 1),
(6, '2026_05_07_134356_create_announcements_table', 1),
(7, '2026_05_07_135316_create_posts_table', 1),
(8, '2026_05_07_145242_create_likes_table', 2),
(9, '2026_05_07_145251_create_comments_table', 2),
(10, '2026_05_07_155044_add_is_admin_to_users_table', 3),
(11, '2026_05_07_170327_add_profile_fields_to_users_table', 4),
(12, '2026_05_08_040352_create_attendances_table', 5),
(13, '2026_05_08_130137_update_role_column_in_users_table', 6),
(14, '2026_05_11_112727_create_leave_requests_table', 7),
(15, '2026_05_12_100946_create_performance_reviews_table', 8),
(16, '2026_05_12_111720_create_notifications_table', 9),
(17, '2026_05_12_115154_create_documents_table', 10),
(18, '2026_05_13_102443_add_internal_doc_fields_to_documents_table', 11),
(19, '2026_05_14_142619_create_calendar_events_table', 12),
(20, '2026_05_15_115825_create_branches_table', 13),
(21, '2026_05_18_133016_create_departments_table', 14),
(22, '2026_05_19_133101_add_description_to_calendar_events_table', 15),
(23, '2026_05_19_133843_add_cc_users_to_documents_table', 16),
(24, '2026_05_26_144426_create_roles_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('da64467b-5133-442b-a94b-d498f5bd8402', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 5, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e41\\u0e08\\u0e49\\u0e07\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07\\u0e23\\u0e49\\u0e2d\\u0e07\\u0e40\\u0e23\\u0e35\\u0e22\\u0e19\\/\\u0e02\\u0e49\\u0e2d\\u0e40\\u0e2a\\u0e19\\u0e2d\\u0e41\\u0e19\\u0e30\\u0e40\\u0e1e\\u0e37\\u0e48\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e1e\\u0e31\\u0e12\\u0e19\\u0e32\\u0e2d\\u0e07\\u0e04\\u0e4c\\u0e01\\u0e23\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8001\\/manager\\/approvals\",\"type\":\"document\"}', '2026-05-26 03:50:25', '2026-05-26 02:57:06', '2026-05-26 03:50:25'),
('f7733e88-f180-44ed-b1c9-975cd17ff130', 'App\\Notifications\\NewAnnouncementNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e08\\u0e32\\u0e01\\u0e1a\\u0e23\\u0e34\\u0e29\\u0e31\\u0e17\",\"message\":\"\\u0e21\\u0e35\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07: \\u0e43\\u0e04\\u0e23\\u0e2b\\u0e32\\u0e2b\\u0e2d\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e21\\u0e31\\u0e49\\u0e22\\u0e04\\u0e23\\u0e31\\u0e1a\",\"link\":\"http:\\/\\/127.0.0.1:8000\",\"type\":\"document\"}', NULL, '2026-05-27 06:20:11', '2026-05-27 06:20:11'),
('d6885fb1-bb4e-4743-b5a7-b82290f28e71', 'App\\Notifications\\NewAnnouncementNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e08\\u0e32\\u0e01\\u0e1a\\u0e23\\u0e34\\u0e29\\u0e31\\u0e17\",\"message\":\"\\u0e21\\u0e35\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07: \\u0e43\\u0e04\\u0e23\\u0e2b\\u0e32\\u0e2b\\u0e2d\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e21\\u0e31\\u0e49\\u0e22\\u0e04\\u0e23\\u0e31\\u0e1a\",\"link\":\"http:\\/\\/127.0.0.1:8000\",\"type\":\"document\"}', '2026-05-27 06:28:40', '2026-05-27 06:20:11', '2026-05-27 06:28:40'),
('c851b1a6-ff0d-479a-ba9e-cbfdd68d8a19', 'App\\Notifications\\NewAnnouncementNotification', 'App\\Models\\User', 5, '{\"title\":\"\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e08\\u0e32\\u0e01\\u0e1a\\u0e23\\u0e34\\u0e29\\u0e31\\u0e17\",\"message\":\"\\u0e21\\u0e35\\u0e1b\\u0e23\\u0e30\\u0e01\\u0e32\\u0e28\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07: \\u0e43\\u0e04\\u0e23\\u0e2b\\u0e32\\u0e2b\\u0e2d\\u0e43\\u0e2b\\u0e21\\u0e48\\u0e21\\u0e31\\u0e49\\u0e22\\u0e04\\u0e23\\u0e31\\u0e1a\",\"link\":\"http:\\/\\/127.0.0.1:8000\",\"type\":\"document\"}', NULL, '2026-05-27 06:20:11', '2026-05-27 06:20:11'),
('b8f238bd-225c-438d-a082-bff04c9fd366', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/49\\/view\",\"type\":\"document\"}', '2026-05-27 07:52:15', '2026-05-27 07:52:09', '2026-05-27 07:52:15'),
('98f57476-49d4-402d-a54a-691f4e320ba3', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e1d\\u0e36\\u0e01\\u0e2d\\u0e1a\\u0e23\\u0e21\\u0e1e\\u0e31\\u0e12\\u0e19\\u0e32\\u0e1a\\u0e38\\u0e04\\u0e25\\u0e32\\u0e01\\u0e23\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/50\\/view\",\"type\":\"document\"}', '2026-05-27 07:57:18', '2026-05-27 07:57:14', '2026-05-27 07:57:18'),
('bf53111a-9db6-4c6b-bec9-3a09dfd0dd45', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/52\\/view\",\"type\":\"document\"}', '2026-05-27 09:11:56', '2026-05-27 09:11:51', '2026-05-27 09:11:56'),
('03bc29db-299c-4724-8439-49288e670797', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 03:23:53', '2026-05-28 03:23:53'),
('799b5931-5df9-4bda-927d-bc5f02e0935b', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/54\\/view\",\"type\":\"document\"}', '2026-05-28 03:25:42', '2026-05-28 03:25:34', '2026-05-28 03:25:42'),
('2a88ed34-34d0-468e-a455-0f5baa262bb5', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 03:44:49', '2026-05-28 03:44:49'),
('b40eb1f3-9f59-4eee-8291-8820867f4bb2', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/57\\/view\",\"type\":\"document\"}', '2026-05-28 03:45:07', '2026-05-28 03:45:01', '2026-05-28 03:45:07'),
('e958b4c8-3b2e-404d-b996-b1a9e41332f3', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', '2026-05-28 04:34:23', '2026-05-28 04:07:42', '2026-05-28 04:34:23'),
('eefef747-6c84-4a17-b6ad-7e58ca2aecbf', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 04:07:42', '2026-05-28 04:07:42'),
('cf2ae32a-f240-4bc3-b8fb-4a1e4d839c12', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/58\\/view\",\"type\":\"document\"}', '2026-05-28 04:07:53', '2026-05-28 04:07:47', '2026-05-28 04:07:53'),
('15b581df-54ef-434f-94a8-7aa5131422a9', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e16\\u0e39\\u0e01\\u0e1b\\u0e0f\\u0e34\\u0e40\\u0e2a\\u0e18\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/59\\/view\",\"type\":\"document\"}', '2026-05-28 04:34:41', '2026-05-28 04:34:15', '2026-05-28 04:34:41'),
('7ffdc778-5fac-4979-9be6-811fa2592060', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 04:52:38', '2026-05-28 04:52:38'),
('85e1ac92-6796-4f7f-9726-351b764f1118', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 04:52:38', '2026-05-28 04:52:38'),
('95c560f8-b800-407b-b485-c99d4a700add', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/60\\/view\",\"type\":\"document\"}', '2026-05-28 04:52:54', '2026-05-28 04:52:47', '2026-05-28 04:52:54'),
('bb8023ae-420d-401f-80e4-4a7dc950ff00', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 04:53:51', '2026-05-28 04:53:51'),
('bf21cf77-a3fb-42af-ac74-ca521fff768a', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 04:53:51', '2026-05-28 04:53:51'),
('97b1a26e-0adf-48b0-9ab7-526f8d381de1', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/62\\/view\",\"type\":\"document\"}', '2026-05-28 04:55:44', '2026-05-28 04:53:55', '2026-05-28 04:55:44'),
('48d12cd0-11f0-41e0-b908-d495a777ab0a', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 06:05:50', '2026-05-28 06:05:50'),
('3ad921a7-f1b7-4447-8911-734bdfa616f2', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-28 06:05:50', '2026-05-28 06:05:50'),
('fe514807-1d6b-438f-a453-8ddcc2e9b28d', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/66\\/view\",\"type\":\"document\"}', '2026-05-28 06:18:13', '2026-05-28 06:05:54', '2026-05-28 06:18:13'),
('45eae383-d8a2-4ae0-8c4d-a8b69c88e4cb', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 05:59:44', '2026-05-29 05:59:44'),
('51d745e2-c7a0-416e-a79b-74810785f904', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 05:59:44', '2026-05-29 05:59:44'),
('946e9f62-de90-416c-be6e-52a886fbd0e5', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', '2026-05-29 05:59:49', '2026-05-29 05:59:44', '2026-05-29 05:59:49'),
('b2f8b356-7441-4c6d-860e-15f0cdf4820c', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/73\\/view\",\"type\":\"document\"}', '2026-05-29 06:00:08', '2026-05-29 05:59:52', '2026-05-29 06:00:08'),
('532ca4e3-52d3-41af-8bf8-b0f8dd24b2aa', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 06:54:15', '2026-05-29 06:54:15'),
('0448cff9-ba80-4f97-bf71-d10fc5615127', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 06:54:15', '2026-05-29 06:54:15'),
('86380c3a-ab4d-4037-bdbf-b8d83720a5c6', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', '2026-05-29 09:30:51', '2026-05-29 06:54:15', '2026-05-29 09:30:51'),
('bc3f883e-65f2-4f07-9d8a-3420c6f24c4f', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 3, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/show\\/74\",\"type\":\"document\"}', '2026-05-29 07:12:35', '2026-05-29 06:54:22', '2026-05-29 07:12:35'),
('a518ec71-211a-4da1-9a51-5a5d0ec62c5b', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 09:32:21', '2026-05-29 09:32:21'),
('6928e3ef-a89f-40ad-a517-bcdb1b981da8', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 09:32:21', '2026-05-29 09:32:21'),
('7fd6734f-da76-4482-ae9c-bd14eab45eec', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-05-29 09:32:21', '2026-05-29 09:32:21'),
('d9408404-a7e3-4445-87f5-ad4b73331999', 'App\\Notifications\\DocumentStatusUpdatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0e2d\\u0e31\\u0e1b\\u0e40\\u0e14\\u0e15\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e30\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\",\"message\":\"\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e02\\u0e49\\u0e2d\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e02\\u0e2d\\u0e07\\u0e04\\u0e38\\u0e13\\u0e44\\u0e14\\u0e49\\u0e23\\u0e31\\u0e1a\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e41\\u0e25\\u0e49\\u0e27\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/archives\\/show\\/75\",\"type\":\"document\"}', '2026-05-29 09:32:39', '2026-05-29 09:32:30', '2026-05-29 09:32:39'),
('1e68c5a6-47a1-459b-b98b-8d0db9ef271e', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 4, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-06-03 10:25:43', '2026-06-03 10:25:43'),
('3243c6ed-77fd-47b0-8252-44af45b30703', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 4, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', '2026-06-03 15:15:57', '2026-06-03 10:56:57', '2026-06-03 15:15:57'),
('81f4e3a7-759a-41fc-a3ef-e48af05c464c', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-06-03 11:11:49', '2026-06-03 11:11:49'),
('03727572-50dd-40a4-885c-fb34a16ebea3', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 16, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-06-03 11:11:49', '2026-06-03 11:11:49'),
('fe696da3-3421-42c1-b3f7-416460c9135d', 'App\\Notifications\\NewDocumentRequestNotification', 'App\\Models\\User', 17, '{\"title\":\"\\u0e04\\u0e33\\u0e02\\u0e2d\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\/\\u0e1a\\u0e31\\u0e19\\u0e17\\u0e36\\u0e01\\u0e20\\u0e32\\u0e22\\u0e43\\u0e19\",\"message\":\"\\u0e21\\u0e35\\u0e40\\u0e2d\\u0e01\\u0e2a\\u0e32\\u0e23\\u0e40\\u0e23\\u0e37\\u0e48\\u0e2d\\u0e07 \\\"\\u0e02\\u0e2d\\u0e40\\u0e1a\\u0e34\\u0e01\\u0e08\\u0e48\\u0e32\\u0e22\\\" \\u0e23\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e2d\\u0e19\\u0e38\\u0e21\\u0e31\\u0e15\\u0e34\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/manager\\/approvals\",\"type\":\"document\"}', NULL, '2026-06-03 11:11:49', '2026-06-03 11:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(7) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `deduction` decimal(10,2) DEFAULT 0.00,
  `net_total` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `user_id`, `month`, `base_salary`, `bonus`, `deduction`, `net_total`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2026-05', 30000.00, 0.00, 1500.00, 28500.00, 'released', '2026-05-13 07:06:36', '2026-05-13 07:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `performance_reviews`
--

CREATE TABLE `performance_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quality_score` int(11) NOT NULL,
  `punctuality_score` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `performance_reviews`
--

INSERT INTO `performance_reviews` (`id`, `user_id`, `quality_score`, `punctuality_score`, `comments`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 5, 'อาจจะลืมเกือบงานบ้างแต่ก็ออกมาดีพอสมควร', '2026-05-12 03:21:10', '2026-05-12 03:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ประธานกรรมการบริษัท', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(2, 'กรรมการบริหาร', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(3, 'ผู้อำนวยการกลุ่มงาน', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(4, 'ผู้จัดการกลุ่มงาน', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(5, 'รองผู้จัดการกลุ่มงาน', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(6, 'หัวหน้าฝ่าย', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(7, 'เจ้าหน้าที่อาวุโส', '2026-06-03 09:37:06', '2026-06-03 09:37:06'),
(8, 'เจ้าหน้าที่', '2026-06-03 09:37:06', '2026-06-03 09:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `target_branch` varchar(255) DEFAULT 'ทั้งหมด',
  `target_department` varchar(255) DEFAULT 'ทั้งหมด',
  `author` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `category`, `target_branch`, `target_department`, `author`, `image`, `created_at`, `updated_at`) VALUES
(12, 'ระวังไวรัส', 'ไวรัสฮันตา', 'success', 'ทั้งหมด', 'ทั้งหมด', 'NAMES', '1778636656.jpg', '2026-05-13 01:44:16', '2026-05-13 01:58:39'),
(14, 'หางานใหม่ให้ครับ', 'ขาดพนักงาน', 'primary', 'ทั้งหมด', 'ทั้งหมด', 'NAMES', '1778728601.png', '2026-05-14 03:16:41', '2026-05-18 04:47:27'),
(35, 'ใครหาหอใหม่มั้ยครับ', 'ใครหาหอใหม่มั้ยครับ', 'success', 'ทั้งหมด', 'ทั้งหมด', 'game', '1779862810.png', '2026-05-27 06:20:10', '2026-05-27 06:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL COMMENT 'ไฟล์รูปลายเซ็น',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'General Employee',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `department`, `position`, `branch`, `image`, `signature`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Weerawat Tonkanya', '', 'sanhnadsenasing@gmail.com', '0871234567', NULL, 'CEO', 'บัญชีและการเงิน (Accounting)', 'สาขากระนวน', 'profile_1_1779009897.jpg', NULL, NULL, '$2y$12$CQhwSTtvMs2rkINjQ0hB5eql0Qm5Fh.tOYVZlKwhw5llqcnrDfrRu', 'CEO', NULL, '2026-05-07 08:28:22', '2026-05-29 04:34:52'),
(2, 'NAMES', '', 'admin@example.com', '0949632259', NULL, 'เทคโนโลยีสารสนเทศ (IT)', 'เทคโนโลยีสารสนเทศ (IT)', 'สาขาโนนทัน (สำนักงานใหญ่)', 'profile_2_1779122099.jpg', 'sig_2_1780030721.png', NULL, '$2y$12$s5AgThjtmvWzF7SsQ.NCUO27WN8q.PIl4sAPRylW/GzTVdlBmsi0C', 'Super Admin', NULL, '2026-05-07 08:36:46', '2026-06-02 15:44:37'),
(3, 'game', '', 'admin@shop.com', NULL, NULL, 'บัญชีและการเงิน (Accounting)', 'ธุรการทั่วไป (General Admin)', 'สาขามหาสารคาม', 'profile_3_1779084996.png', 'sig_3_1780030825.png', NULL, '$2y$12$elfYVxrcGu4OgIyRaX78sO.Bb36OgTdrEl2qY4FtbqZKAKvkPZDMu', 'HR Payroll', NULL, '2026-05-07 08:41:33', '2026-05-29 05:00:25'),
(4, 'ฟิล์ม', '', '66010916032@msu.ac.th', '0801234567', NULL, 'ทรัพยากรบุคคล (HR)', 'ทรัพยากรบุคคล (HR)', 'สาขามหาสารคาม', 'profile_4_1779009474.png', 'sig_4_1779955627.png', NULL, '$2y$12$66Wn67b8AD7KVADwzNGE0.ZipPCpeAEugAyeVgIIVPweMpBulgGDK', 'Manager', NULL, '2026-05-08 06:12:34', '2026-05-28 08:07:07'),
(5, 'gamemode1', '', 'gamemode1@gmail.com', NULL, NULL, 'เทคโนโลยีสารสนเทศ (IT)', NULL, 'สาขาโนนทัน (สำนักงานใหญ่)', 'profile_5_1779257108.jpg', NULL, NULL, '$2y$12$87BAfG0X7ksYwEq.Qgnqaej7y6iNCv1pMiJRmJ0X2MtO5v32KgE2G', 'Manager', NULL, '2026-05-15 08:50:49', '2026-05-20 06:49:27'),
(16, 'test1', '', 'user10@example.com', NULL, NULL, 'ผอ.ฝ่าย', NULL, 'สาขาโนนทัน (สำนักงานใหญ่)', 'profile_16_1779780618.png', NULL, NULL, '$2y$12$/HWE2Tq2oyi2o.GhXwV.DeCGVDIH.xCtHU7t89G37Gf/HuHr246vy', 'Director', NULL, '2026-05-26 07:22:03', '2026-05-27 03:51:38'),
(17, 'test2', '', 'user02@example.com', NULL, NULL, 'CEO', 'ประธานกรรมการบริษัท', 'สาขาโนนทัน (สำนักงานใหญ่)', NULL, 'sig_17_1780040781.png', NULL, '$2y$12$MLWnL5t396TxwjsPzeh6LuTh9NahX5lWTTLTC3hm9BUI6NMir8rrm', 'CEO', NULL, '2026-05-26 09:27:47', '2026-06-03 10:17:23'),
(18, 'test3', '', 'test3@gmail.com', NULL, NULL, 'ทรัพยากรบุคคล (HR)', NULL, 'สาขากระนวน', NULL, NULL, NULL, '$2y$12$hme1ZQa.XzAb7WWdrPXep.W3NaiR2dqLQf3alSE9JXf4Ihw0Ne0my', 'HR Manager', NULL, '2026-06-03 15:14:17', '2026-06-03 15:14:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_user_id_foreign` (`user_id`),
  ADD KEY `documents_approver_2_id_foreign` (`approver_2_id`);

--
-- Indexes for table `documents_file`
--
ALTER TABLE `documents_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_post_id_foreign` (`post_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performance_reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `positions_name_unique` (`name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `documents_file`
--
ALTER TABLE `documents_file`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_approver_2_id_foreign` FOREIGN KEY (`approver_2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents_file`
--
ALTER TABLE `documents_file`
  ADD CONSTRAINT `documents_file_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD CONSTRAINT `performance_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
