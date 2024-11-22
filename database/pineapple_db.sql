-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 04:36 AM
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
-- Database: `pineapple_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buy_now`
--

CREATE TABLE `buy_now` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buy_now`
--

INSERT INTO `buy_now` (`id`, `user_id`, `product_image`, `product_name`, `product_quantity`, `product_type`, `created_at`, `price`, `first_name`, `city`, `province`, `last_name`, `product_id`) VALUES
(68, 15, 'Segunda.jpg', 'testing', 1, 'segunda', '2024-11-01 13:04:01', 100.00, 'abdul jakol', 'antipolo', 'abra', '', 8),
(80, 1, 'kwatra.PNG', 'pineapple', 1, 'kwadra', '2024-11-04 11:10:05', 100.00, 'rens', 'antipolo', 'Abra', '', 9);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` decimal(10,2) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_image`, `product_name`, `product_quantity`, `product_type`, `created_at`, `price`, `first_name`, `city`, `province`, `last_name`, `product_id`) VALUES
(275, 31, 'Segunda.jpg', 'testing', 1.00, 'segunda', '2024-11-06 15:58:13', 100, 'abdul jakol', 'antipolo', 'Abra', '', 8),
(277, 2, 'Tercera.jpg', 'tercera', 1.00, 'tresera', '2024-11-11 18:36:42', 100, 'admin\r\n', 'antipolo', 'Abra', '', 11),
(278, 75, 'Tercera.jpg', 'tercera', 2.00, 'tresera', '2024-11-20 16:33:23', 100, 'renss', 'antipolo', 'ss', '', 10),
(279, 34, 'Tercera.jpg', 'tercera', 1.00, 'tresera', '2024-11-20 17:19:20', 100, 'renss', 'antipolo', 'ss', '', 10),
(280, 76, 'Tercera.jpg', 'tercera', 1.00, 'tresera', '2024-11-20 17:52:16', 100, 'renss', 'antipolo', 'sss', '', 11);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_image` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `recipient_id` tinyint(1) NOT NULL DEFAULT 0,
  `receiver_id` int(11) DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `first_name`, `message`, `created_at`, `is_admin`, `recipient_id`, `receiver_id`, `is_read`) VALUES
(146, 2, 'admin\r\n', 'test', '2024-11-11 17:53:06', 0, 2, 0, 1),
(147, 31, '', 'pakyu testing3', '2024-11-11 17:54:16', 1, 0, 0, 0),
(148, 2, 'admin\r\n', 'sss', '2024-11-11 17:56:13', 0, 2, 0, 1),
(149, 2, 'admin\r\n', 'sss', '2024-11-11 18:01:03', 0, 2, 0, 1),
(150, 2, 'admin\r\n', 'sss', '2024-11-11 18:02:18', 0, 2, 0, 0),
(151, 2, 'admin\r\n', 'sss', '2024-11-11 18:03:40', 0, 2, 0, 0),
(152, 2, 'admin\r\n', 'what', '2024-11-11 18:04:02', 0, 2, 0, 0),
(153, 31, 'abdul jakol', 'hi po', '2024-11-11 18:04:42', 0, 2, 0, 0),
(154, 31, '', 'sss', '2024-11-11 18:04:56', 1, 0, 0, 0),
(155, 31, '', 'sss', '2024-11-11 18:06:07', 1, 0, 0, 0),
(156, 31, '', 'sss', '2024-11-11 18:07:49', 1, 0, 0, 0),
(157, 34, 'renss', 'hi po', '2024-11-11 18:09:32', 0, 2, 0, 0),
(158, 34, '', 'yow?', '2024-11-11 18:09:50', 1, 0, 0, 0),
(159, 34, 'renss', 'hi po', '2024-11-11 18:13:46', 0, 2, 0, 0),
(160, 34, '', 'yow?', '2024-11-11 18:14:05', 1, 0, 0, 0),
(161, 34, 'renss', 'test', '2024-11-11 18:18:39', 0, 2, 0, 0),
(162, 34, '', 'test', '2024-11-11 18:19:03', 1, 0, 0, 0),
(163, 34, '', 'test', '2024-11-11 18:23:16', 1, 0, 0, 0),
(164, 34, '', 'yow?', '2024-11-11 18:26:46', 1, 0, 0, 0),
(165, 34, '', 'yow?', '2024-11-11 18:29:57', 1, 0, 0, 0),
(166, 75, 'renss', 'ss', '2024-11-20 16:33:39', 0, 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_method` varchar(200) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('packing','shipped','delivered','cancelled') NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `product_image` varchar(200) DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `payment_receipt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `user_id`, `product_id`, `quantity`, `price`, `payment_method`, `order_date`, `status`, `product_name`, `product_type`, `first_name`, `email`, `phone_number`, `street_address`, `city`, `province`, `product_image`, `shipping_fee`, `payment_receipt`) VALUES
(111, 31, 9, 1, 100.00, 'Pick_up', '2024-11-22 02:12:05', 'shipped', 'pineapple', 'kwadra', 'abdul jakol', NULL, NULL, NULL, 'antipolo', 'Abra', 'kwatra.PNG', 400.00, NULL),
(112, 31, 9, 1, 100.00, 'Pick_up', '2024-11-22 02:12:07', 'shipped', 'pineapple', 'kwadra', 'abdul jakol', NULL, NULL, NULL, 'antipolo', 'Abra', 'kwatra.PNG', 400.00, NULL),
(113, 31, 9, 1, 100.00, 'Pick_up', '2024-11-22 02:12:08', 'shipped', 'pineapple', 'kwadra', 'abdul jakol', NULL, NULL, NULL, 'antipolo', 'Abra', 'kwatra.PNG', 400.00, NULL),
(114, 31, 9, 1, 100.00, 'Pick_up', '2024-11-22 02:12:09', 'shipped', 'pineapple', 'kwadra', 'abdul jakol', NULL, NULL, NULL, 'antipolo', 'Abra', 'kwatra.PNG', 400.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `quantity`, `date`, `created_at`, `price`, `image`) VALUES
(8, 'testing', 'segunda', 63, '2024-10-11', '2024-10-18 13:26:00', 100.00, 'Segunda.jpg'),
(9, 'pineapple', 'kwadra', 8, '2024-10-19', '2024-10-18 13:29:58', 100.00, 'kwatra.PNG'),
(10, 'tercera', 'tresera', 22, '2024-11-09', '2024-10-31 11:54:56', 100.00, 'Tercera.jpg'),
(11, 'tercera', 'tresera', 39, '2024-11-09', '2024-10-31 11:55:03', 100.00, 'Tercera.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `review`, `review_date`, `product_type`) VALUES
(53, 2, 9, 4, 'dddd', '2024-11-03 02:04:34', 'kwadra'),
(54, 2, 8, 3, 'hello', '2024-11-03 02:04:41', 'segunda'),
(55, 1, 1, 3, 'test', '2024-11-04 11:35:45', 'some_product_type'),
(56, 1, 2, 3, 'hello?', '2024-11-04 11:41:19', 'Fashion'),
(57, 31, 1, 5, 'fsjfsfsfha', '2024-11-04 14:31:55', 'Kwarta'),
(58, 77, 35, 3, 'test1', '2024-11-21 13:36:38', 'tresera'),
(59, 77, 38, 5, 'tesssssssssssssss', '2024-11-22 02:44:27', 'tresera');

-- --------------------------------------------------------

--
-- Table structure for table `sold`
--

CREATE TABLE `sold` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_image` varchar(200) DEFAULT NULL,
  `delivery_image` varchar(255) DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_receipt` varchar(255) DEFAULT '',
  `street_address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sold`
--

INSERT INTO `sold` (`id`, `user_id`, `product_name`, `product_type`, `quantity`, `price`, `payment_method`, `order_date`, `status`, `first_name`, `city`, `product_id`, `product_image`, `delivery_image`, `shipping_fee`, `payment_receipt`, `street_address`) VALUES
(29, 2, 'testing', 'segunda', 1, 100.00, 'Pick_up', '2024-11-02 10:43:25', 'Completed', 'admin\r\n', 'antipolo', 8, 'Segunda.jpg', NULL, 0.00, '', ''),
(30, 2, 'pineapple', 'kwadra', 1, 100.00, 'cash_on_delivery', '2024-11-02 10:46:47', 'Completed', 'admin\r\n', 'antipolo', 9, 'kwatra.PNG', NULL, 0.00, '', ''),
(31, 2, 'pineapple', 'kwadra', 1, 100.00, 'Pick_up', '2024-11-02 10:50:53', 'Completed', 'admin\r\n', 'antipolo', 9, 'kwatra.PNG', NULL, 0.00, '', ''),
(32, 2, 'testing', 'segunda', 1, 100.00, 'Pick_up', '2024-11-02 11:05:02', 'Completed', 'admin\r\n', 'antipolo', 8, 'Segunda.jpg', NULL, 0.00, '', ''),
(33, 31, 'tercera', 'tresera', 3, 100.00, 'gcash', '2024-11-11 08:51:40', 'Delivered', 'abdul jakol', 'antipolo', NULL, 'Tercera.jpg', NULL, 400.00, '', ''),
(34, 34, 'pineapple', 'kwadra', 1, 100.00, 'cash_on_delivery', '2024-11-11 11:09:08', 'Delivered', 'renss', 'antipolo', NULL, 'kwatra.PNG', NULL, 0.00, '', '123 Mabini Street, Antipolo City, Rizal, Philippines\r\n'),
(35, 77, 'tercera', 'tresera', 1, 100.00, 'cash_on_delivery', '2024-11-21 05:18:58', 'Completed', 'renss', 'antipolo', NULL, 'Tercera.jpg', NULL, 0.00, '', 'janlangpo'),
(36, 77, 'tercera', 'tresera', 1, 100.00, 'cash_on_delivery', '2024-11-21 05:19:33', 'Completed', 'renss', 'antipolo', NULL, 'Tercera.jpg', NULL, 0.00, '', 'janlangpo'),
(37, 77, 'tercera', 'tresera', 1, 100.00, 'Pick_up', '2024-11-21 06:14:40', 'Completed', 'renss', 'antipolo', NULL, 'Tercera.jpg', NULL, 0.00, '', 'janlangpo'),
(38, 77, 'tercera', 'tresera', 1, 100.00, 'cash_on_delivery', '2024-11-21 06:32:21', 'Completed', 'renss', 'antipolo', NULL, 'Tercera.jpg', '1732199541_advisory.png', 0.00, '', 'janlangpo'),
(39, 77, 'pineapple', 'kwadra', 1, 100.00, 'Pick_up', '2024-11-21 18:45:26', 'Completed', 'renss', 'antipolo', NULL, 'kwatra.PNG', NULL, 0.00, '', 'janlangpo'),
(40, 77, 'pineapple', 'kwadra', 1, 100.00, 'Pick_up', '2024-11-21 18:45:29', 'Completed', 'renss', 'antipolo', NULL, 'kwatra.PNG', NULL, 0.00, '', 'janlangpo'),
(41, 77, 'pineapple', 'kwadra', 1, 100.00, 'cash_on_delivery', '2024-11-21 18:45:31', 'Completed', 'renss', 'antipolo', NULL, 'kwatra.PNG', NULL, 0.00, '', 'janlangpo'),
(42, 77, 'pineapple', 'kwadra', 1, 100.00, 'Pick_up', '2024-11-21 18:45:32', 'Completed', 'renss', 'antipolo', NULL, 'kwatra.PNG', NULL, 0.00, '', 'janlangpo');

-- --------------------------------------------------------

--
-- Table structure for table `sold_review`
--

CREATE TABLE `sold_review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `review_date` datetime NOT NULL,
  `product_type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `baranggay` varchar(100) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `con_password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user','rider') DEFAULT NULL,
  `verify_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_image` varchar(255) DEFAULT 'default_image.png',
  `valid_id_picture` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `orcr` varchar(255) DEFAULT NULL,
  `valid_id` varchar(255) DEFAULT NULL,
  `police_clearance` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `age`, `phone_number`, `province`, `city`, `baranggay`, `municipality`, `street_address`, `zip_code`, `email`, `password`, `con_password`, `role`, `verify_code`, `created_at`, `updated_at`, `user_image`, `valid_id_picture`, `license`, `orcr`, `valid_id`, `police_clearance`) VALUES
(2, 'admin\r\n', 'acuna', 0, '09510608496', 'Abra', 'antipolo', 'ss', 'ss', NULL, '1990', 'acuna@gmail.com', '$2y$10$vSnhurodHr1pTrSULu9s5e3XOpXJx1IDLnsnkmV3GJoYQEsf/wpYG', 'admin', 'admin', NULL, '2024-10-22 17:06:31', '2024-10-31 13:07:48', 'default_image.png', NULL, NULL, NULL, NULL, NULL),
(34, 'renss', 'salsalani', 0, '09510608496', 'ss', 'antipolo', 'ss', 'ss', '123 Mabini Street, Antipolo City, Rizal, Philippines\r\n', '1990', 'hperforman@gmail.com', '$2y$10$kYMrXmPCD3jnpJoP1hwVIOzlj8Gl/tuUe0.3LGY.pRw/nRWexRsdK', 'test', 'user', NULL, '2024-11-04 14:06:19', '2024-11-11 18:49:22', '6728d4db1de16.jpg', NULL, NULL, NULL, NULL, NULL),
(77, 'renss', 'ssssss', 22, '09510608496', 'ada', 'antipolo', 'sss', 'sss', 'janlangpo', '1990', 'wasieacuna@gmail.com', '$2y$10$sKhd6SSuUIqwRNIPYiLB9eNNkt7Y1x.rIqirH5vH5LSC8yMBDpkey', 'rens', 'user', NULL, '2024-11-20 18:03:09', '2024-11-20 18:04:19', '673e245d62d89.jpg', NULL, NULL, NULL, NULL, NULL),
(78, 'renz', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rider@gmail.com', '$2y$10$D3Kb/cfSEzdxWFglh5GF3ORepChLYP2NZLCMTgaL3Irf9UCyNBjR.', 'rider', 'rider', NULL, '2024-11-21 13:15:21', '2024-11-21 13:17:40', 'default_image.png', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_time` datetime DEFAULT current_timestamp(),
  `is_guest` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_order`
--

CREATE TABLE `user_order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `baranggay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `visit_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `ip_address`, `visit_time`) VALUES
(26, '::1', '2024-10-18 18:15:08'),
(27, '::1', '2024-10-18 19:07:00'),
(28, '::1', '2024-10-18 19:08:01'),
(29, '::1', '2024-10-18 19:49:57'),
(30, '::1', '2024-10-19 18:07:30'),
(31, '::1', '2024-10-20 14:05:04'),
(32, '::1', '2024-10-20 14:50:06'),
(33, '::1', '2024-10-22 18:59:35'),
(34, '::1', '2024-10-22 19:05:54'),
(35, '::1', '2024-10-22 20:08:51'),
(36, '::1', '2024-10-22 20:16:57'),
(37, '::1', '2024-10-22 20:17:26'),
(38, '::1', '2024-10-22 20:17:55'),
(39, '::1', '2024-10-22 20:49:03'),
(40, '::1', '2024-10-23 13:15:08'),
(41, '::1', '2024-10-23 13:15:16'),
(42, '::1', '2024-10-23 13:24:41'),
(43, '::1', '2024-10-23 16:11:09'),
(44, '::1', '2024-10-23 16:16:38'),
(45, '::1', '2024-10-24 16:07:15'),
(46, '::1', '2024-10-24 16:37:49'),
(47, '::1', '2024-10-24 18:46:23'),
(48, '::1', '2024-10-24 19:04:26'),
(49, '::1', '2024-10-25 14:16:41'),
(50, '::1', '2024-10-26 01:05:30'),
(51, '::1', '2024-10-26 01:08:22'),
(52, '::1', '2024-10-26 18:14:10'),
(53, '::1', '2024-10-26 18:15:08'),
(54, '::1', '2024-10-26 18:17:58'),
(55, '::1', '2024-10-26 18:19:12'),
(56, '::1', '2024-10-26 18:23:01'),
(57, '::1', '2024-10-26 20:34:36'),
(58, '::1', '2024-10-26 20:54:55'),
(59, '::1', '2024-10-26 21:14:46'),
(60, '::1', '2024-10-27 12:22:51'),
(61, '::1', '2024-10-27 12:24:02'),
(62, '::1', '2024-10-27 13:30:17'),
(63, '::1', '2024-10-27 13:32:09'),
(64, '::1', '2024-10-27 13:33:08'),
(65, '::1', '2024-10-27 13:35:24'),
(66, '::1', '2024-10-27 13:36:15'),
(67, '::1', '2024-10-27 13:39:02'),
(68, '::1', '2024-10-27 14:18:06'),
(69, '::1', '2024-10-28 18:02:42'),
(70, '::1', '2024-10-29 13:18:07'),
(71, '::1', '2024-10-29 15:06:48'),
(72, '::1', '2024-10-29 15:08:59'),
(73, '::1', '2024-10-29 15:09:17'),
(74, '::1', '2024-10-29 15:09:26'),
(75, '::1', '2024-10-29 15:09:42'),
(76, '::1', '2024-10-29 15:09:54'),
(77, '::1', '2024-10-29 15:13:58'),
(78, '::1', '2024-10-29 15:14:23'),
(79, '::1', '2024-10-29 15:14:48'),
(80, '::1', '2024-10-29 15:15:08'),
(81, '::1', '2024-10-29 15:15:17'),
(82, '::1', '2024-10-29 15:15:40'),
(83, '::1', '2024-10-29 15:15:51'),
(84, '::1', '2024-10-29 15:18:12'),
(85, '::1', '2024-10-29 15:18:46'),
(86, '::1', '2024-10-29 15:22:34'),
(87, '::1', '2024-10-29 15:22:36'),
(88, '::1', '2024-10-29 15:35:23'),
(89, '::1', '2024-10-29 15:37:04'),
(90, '::1', '2024-10-29 15:38:04'),
(91, '::1', '2024-10-29 15:39:58'),
(92, '::1', '2024-10-29 15:40:37'),
(93, '::1', '2024-10-29 15:43:40'),
(94, '::1', '2024-10-29 15:44:55'),
(95, '::1', '2024-10-29 15:46:18'),
(96, '::1', '2024-10-29 15:46:24'),
(97, '::1', '2024-10-29 15:47:24'),
(98, '::1', '2024-10-29 15:47:44'),
(99, '::1', '2024-10-29 15:49:10'),
(100, '::1', '2024-10-29 15:49:42'),
(101, '::1', '2024-10-29 15:50:11'),
(102, '::1', '2024-10-29 15:51:21'),
(103, '::1', '2024-10-29 15:51:43'),
(104, '::1', '2024-10-29 15:53:26'),
(105, '::1', '2024-10-29 15:54:16'),
(106, '::1', '2024-10-29 15:55:35'),
(107, '::1', '2024-10-29 15:56:35'),
(108, '::1', '2024-10-29 15:56:52'),
(109, '::1', '2024-10-29 15:57:12'),
(110, '::1', '2024-10-29 15:57:34'),
(111, '::1', '2024-10-29 15:58:20'),
(112, '::1', '2024-10-29 15:58:29'),
(113, '::1', '2024-10-29 16:04:21'),
(114, '::1', '2024-10-29 16:05:48'),
(115, '::1', '2024-10-29 16:06:15'),
(116, '::1', '2024-10-29 16:07:11'),
(117, '::1', '2024-10-29 16:07:30'),
(118, '::1', '2024-10-29 16:07:51'),
(119, '::1', '2024-10-29 16:08:06'),
(120, '::1', '2024-10-29 16:09:30'),
(121, '::1', '2024-10-29 16:10:23'),
(122, '::1', '2024-10-29 16:10:49'),
(123, '::1', '2024-10-29 16:10:58'),
(124, '::1', '2024-10-29 16:11:41'),
(125, '::1', '2024-10-29 16:11:59'),
(126, '::1', '2024-10-29 16:12:23'),
(127, '::1', '2024-10-29 16:12:24'),
(128, '::1', '2024-10-29 16:12:39'),
(129, '::1', '2024-10-29 16:12:48'),
(130, '::1', '2024-10-29 16:12:54'),
(131, '::1', '2024-10-29 16:14:50'),
(132, '::1', '2024-10-29 16:15:22'),
(133, '::1', '2024-10-29 16:15:46'),
(134, '::1', '2024-10-29 16:15:49'),
(135, '::1', '2024-10-29 16:16:11'),
(136, '::1', '2024-10-29 16:16:28'),
(137, '::1', '2024-10-29 16:16:31'),
(138, '::1', '2024-10-29 16:16:41'),
(139, '::1', '2024-10-29 16:16:43'),
(140, '::1', '2024-10-29 16:16:44'),
(141, '::1', '2024-10-29 16:18:08'),
(142, '::1', '2024-10-29 16:19:05'),
(143, '::1', '2024-10-29 16:19:43'),
(144, '::1', '2024-10-29 16:20:04'),
(145, '::1', '2024-10-29 16:20:49'),
(146, '::1', '2024-10-29 16:22:54'),
(147, '::1', '2024-10-29 16:23:54'),
(148, '::1', '2024-10-29 16:26:09'),
(149, '::1', '2024-10-29 16:28:47'),
(150, '::1', '2024-10-29 16:30:32'),
(151, '::1', '2024-10-29 16:31:32'),
(152, '::1', '2024-10-29 16:34:44'),
(153, '::1', '2024-10-29 16:35:40'),
(154, '::1', '2024-10-29 16:36:31'),
(155, '::1', '2024-10-29 16:36:44'),
(156, '::1', '2024-10-31 12:38:45'),
(157, '::1', '2024-10-31 12:38:57'),
(158, '::1', '2024-10-31 16:31:59'),
(159, '::1', '2024-10-31 16:43:41'),
(160, '::1', '2024-10-31 17:08:12'),
(161, '::1', '2024-10-31 17:08:19'),
(162, '::1', '2024-10-31 17:08:20'),
(163, '::1', '2024-10-31 17:08:21'),
(164, '::1', '2024-10-31 17:08:21'),
(165, '::1', '2024-10-31 17:13:20'),
(166, '::1', '2024-10-31 17:13:33'),
(167, '::1', '2024-10-31 17:13:41'),
(168, '::1', '2024-11-01 12:29:55'),
(169, '::1', '2024-11-01 13:32:18'),
(170, '::1', '2024-11-02 17:26:17'),
(171, '::1', '2024-11-02 17:26:26'),
(172, '::1', '2024-11-02 17:27:16'),
(173, '::1', '2024-11-02 19:15:02'),
(174, '::1', '2024-11-02 19:30:56'),
(175, '::1', '2024-11-04 12:07:20'),
(176, '::1', '2024-11-04 13:06:47'),
(177, '::1', '2024-11-04 14:18:43'),
(178, '::1', '2024-11-04 14:43:43'),
(179, '::1', '2024-11-04 14:47:31'),
(180, '::1', '2024-11-04 14:49:03'),
(181, '::1', '2024-11-04 14:50:19'),
(182, '::1', '2024-11-04 15:02:30'),
(183, '::1', '2024-11-04 15:03:08'),
(184, '::1', '2024-11-04 15:05:48'),
(185, '::1', '2024-11-04 15:07:57'),
(186, '::1', '2024-11-04 15:09:49'),
(187, '::1', '2024-11-04 15:11:14'),
(188, '::1', '2024-11-04 15:14:52'),
(189, '::1', '2024-11-04 15:17:54'),
(190, '::1', '2024-11-04 15:19:56'),
(191, '::1', '2024-11-04 15:38:47'),
(192, '::1', '2024-11-04 15:42:11'),
(193, '::1', '2024-11-06 16:33:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buy_now`
--
ALTER TABLE `buy_now`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sold`
--
ALTER TABLE `sold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sold_review`
--
ALTER TABLE `sold_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_order`
--
ALTER TABLE `user_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `buy_now`
--
ALTER TABLE `buy_now`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `sold`
--
ALTER TABLE `sold`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `sold_review`
--
ALTER TABLE `sold_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_order`
--
ALTER TABLE `user_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_order`
--
ALTER TABLE `user_order`
  ADD CONSTRAINT `user_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_order_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
