-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 26, 2026 at 09:15 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pemesanan_aksara`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendance_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `type`, `date`, `time`, `photo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Masuk', '2026-05-26', '08:59:04', 'uploads/attendance/att_1_1779785943.jpeg', '2026-05-26 08:59:04', '2026-05-26 08:59:04'),
(2, 1, 'Keluar', '2026-05-26', '08:59:18', 'uploads/attendance/att_1_1779785958.jpeg', '2026-05-26 08:59:18', '2026-05-26 08:59:18'),
(3, 4, 'Masuk', '2026-05-26', '11:12:41', 'uploads/attendance/att_4_1779786761.png', '2026-05-26 11:12:41', '2026-05-26 11:12:41'),
(4, 5, 'Masuk', '2026-05-26', '09:15:14', 'uploads/attendance/att_5_1779786914.jpeg', '2026-05-26 09:15:14', '2026-05-26 09:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-05-23-080000', 'App\\Database\\Migrations\\CreateTables', 'default', 'App', 1779525183, 1),
(2, '2026-05-23-090000', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1779528485, 2),
(3, '2026-05-26-154000', 'App\\Database\\Migrations\\CreateAttendanceTable', 'default', 'App', 1779784836, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Guest',
  `total` int NOT NULL DEFAULT '0',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `order_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dine In',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `total`, `payment_method`, `order_type`, `status`, `created_at`) VALUES
('ord_6a115e6a1a97b', 'Pelanggan (Meja 78)', 25000, 'Cash', 'Dine In', 'completed', '2026-05-23 07:59:38'),
('ord_6a0f39ba00fd2', 'Pelanggan (Meja 78)', 25000, 'Cash', 'Dine In', 'completed', '2026-05-21 16:58:34'),
('ord_6a0f396362029', 'Pelanggan (Meja 78)', 25000, 'QRIS', 'Dine In', 'completed', '2026-05-21 16:57:07'),
('ord_6a0f38e931f69', 'Pelanggan (Meja 78)', 25000, 'QRIS', 'Dine In', 'completed', '2026-05-21 16:55:05'),
('ord_6a0f37572774e', 'Pelanggan (Meja 78)', 25000, 'Cash', 'Dine In', 'completed', '2026-05-21 16:48:23'),
('ord_6a0f36bb259c8', 'Pelanggan (Meja 78)', 25000, 'Cash', 'Dine In', 'completed', '2026-05-21 16:45:47'),
('ord_6a0f1dc93cbad', 'Guest', 25000, 'Cash', 'Dine In', 'completed', '2026-05-21 14:59:21'),
('ord_6a0d52388b544', 'Guest', 25000, 'Cash', 'Dine In', 'completed', '2026-05-20 06:18:32'),
('ord_6a0d5207250d5', 'Guest', 25000, 'Cash', 'Dine In', 'completed', '2026-05-20 06:17:43'),
('ord_6a0c830249307', 'Guest', 82000, 'Cash', 'Dine In', 'completed', '2026-05-19 15:34:26'),
('ord_6a0c7e6611a6b', 'Guest', 57000, 'Cash', 'Dine In', 'completed', '2026-05-19 15:14:46'),
('ord_6a0c7e42c91d3', 'Guest', 57000, 'Cash', 'Dine In', 'completed', '2026-05-19 15:14:10'),
('ord_6a0c7e2a36fb8', 'Guest', 57000, 'Cash', 'Dine In', 'completed', '2026-05-19 15:13:46'),
('ord_6a0c7e2638d87', 'Guest', 57000, 'Cash', 'Dine In', 'completed', '2026-05-19 15:13:42'),
('ord_6a0c7a741604a', 'Guest', 57000, 'Cash', 'Dine In', 'completed', '2026-05-19 14:57:56'),
('ord_6a059433cb124', 'tes2', 57000, 'Cash', 'Dine In', 'completed', '2026-05-14 09:21:55'),
('ord_6a0593edb1a3d', 'fghsh', 25000, 'Cash', 'Dine In', 'completed', '2026-05-14 09:20:45'),
('ord_6a12f7126002a', 'Pelanggan (Meja 78)', 25000, 'Cash', 'Dine In', 'pending', '2026-05-24 13:03:14'),
('ord_6a1557806ee9e', 'Pelanggan (Meja 78)', 25000, 'QRIS', 'Dine In', 'pending', '2026-05-26 08:19:12'),
('ord_6a155ff30576e', 'Guest (Dine In)', 30160, 'QRIS', 'Dine In', 'pending', '2026-05-26 08:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `name`, `price`, `qty`, `notes`) VALUES
(1, 'ord_6a115e6a1a97b', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(2, 'ord_6a0f39ba00fd2', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(3, 'ord_6a0f396362029', 1, 'Kopi Susu Aksara', 25000, 1, 'less sugar'),
(4, 'ord_6a0f38e931f69', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(5, 'ord_6a0f37572774e', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(6, 'ord_6a0f36bb259c8', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(7, 'ord_6a0f1dc93cbad', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(8, 'ord_6a0d52388b544', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(9, 'ord_6a0d5207250d5', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(10, 'ord_6a0c830249307', 1, 'Kopi Susu Aksara', 25000, 2, ''),
(11, 'ord_6a0c830249307', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(12, 'ord_6a0c7e6611a6b', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(13, 'ord_6a0c7e6611a6b', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(14, 'ord_6a0c7e42c91d3', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(15, 'ord_6a0c7e42c91d3', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(16, 'ord_6a0c7e2a36fb8', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(17, 'ord_6a0c7e2a36fb8', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(18, 'ord_6a0c7e2638d87', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(19, 'ord_6a0c7e2638d87', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(20, 'ord_6a0c7a741604a', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(21, 'ord_6a0c7a741604a', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(22, 'ord_6a059433cb124', 2, 'Emerald Matcha Espresso', 32000, 1, ''),
(23, 'ord_6a059433cb124', 5, 'Green Apple Mojito', 25000, 1, ''),
(24, 'ord_6a0593edb1a3d', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(25, 'ord_6a12f7126002a', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(26, 'ord_6a1557806ee9e', 1, 'Kopi Susu Aksara', 25000, 1, ''),
(27, 'ord_6a155ff30576e', 6, 'Signature Chocolate', 26000, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `img`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Kopi Susu Aksara', 25000, 'coffee', 'menu_kopi_susu.png', 39, '2026-05-23 08:33:12', '2026-05-26 08:19:12'),
(2, 'Emerald Matcha Espresso', 32000, 'coffee', 'menu_matcha_espresso.png', 40, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(3, 'Classic Cappuccino', 28000, 'coffee', 'menu_cappuccino.png', 45, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(4, 'Pure Matcha Latte', 28000, 'non-coffee', 'hero_coffee.png', 30, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(5, 'Green Apple Mojito', 25000, 'non-coffee', 'hero_coffee.png', 35, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(6, 'Signature Chocolate', 26000, 'non-coffee', 'hero_coffee.png', 39, '2026-05-23 08:33:12', '2026-05-26 08:55:15'),
(7, 'Matcha Brownies', 20000, 'snack', 'hero_coffee.png', 20, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(8, 'Butter Croissant', 18000, 'snack', 'hero_coffee.png', 15, '2026-05-23 08:33:12', '2026-05-23 08:33:12'),
(9, 'Aksara Mix Platter', 35000, 'snack', 'hero_coffee.png', 25, '2026-05-23 08:33:12', '2026-05-23 08:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE IF NOT EXISTS `shifts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'closed',
  `initial_cash` int NOT NULL DEFAULT '0',
  `open_time` datetime DEFAULT NULL,
  `close_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `status`, `initial_cash`, `open_time`, `close_time`) VALUES
(1, 'open', 100000, '2026-05-26 08:54:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'kasir', '$2y$10$mdxfR93SGmZJjwXovJg.euGdJnkBY74SZW2GyXUkc7vDpaNY.7Xme', 'Ahmad (Kasir)', 'kasir', '2026-05-23 09:29:05', '2026-05-23 09:29:05'),
(2, 'admin', '$2y$10$YpKOEA0fFLJYXYD/O/bcFucMLLSqWTSJ215TDcqanHKd5aMtqLvHm', 'Dewi (Admin)', 'admin', '2026-05-23 09:29:05', '2026-05-23 09:29:05'),
(3, 'owner', '$2y$10$OeiNbO5zAP4iaToDGPW5H.Ou4QpmTttd2VRi66ag8Q4bvl2DA.jPu', 'Budi (Owner)', 'owner', '2026-05-23 09:29:05', '2026-05-23 09:29:05'),
(4, 'rianpramanacli6a15640942ce8', '$2y$10$rh.l/bxoEA6rxTE2ptxUe.ZunjTE1J0eQ.m92QCYx.7pKyWQBnB3.', 'Rian Pramana CLI 6a15640942ce8', 'kasir', '2026-05-26 11:12:41', '2026-05-26 11:12:41'),
(5, 'novalkasir', '$2y$10$lu4e69xiCRd4rQzY8wPGEe4dJKeOy0HTJFZ2ADcp6qorNU74J7D7e', 'noval (kasir)', 'kasir', '2026-05-26 09:15:14', '2026-05-26 09:15:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
