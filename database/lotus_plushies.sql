-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 06:22 PM
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
-- Database: `im_final_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `added_at`) VALUES
(33, 6, 52, 10, '2025-11-02 15:10:55'),
(47, 8, 45, 5, '2025-11-02 17:02:21'),
(48, 8, 44, 5, '2025-11-02 17:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `is_active`, `created_at`) VALUES
(26, 'Plush Toys', 'Soft and cuddly plush toys', 1, '2025-11-02 14:26:09'),
(27, 'Collectibles', 'Limited edition collectible plushies', 1, '2025-11-02 14:26:09'),
(28, 'Gift Sets', 'Curated gift sets with multiple items', 1, '2025-11-02 14:26:09'),
(29, 'Seasonal', 'Holiday and seasonal themed plushies', 1, '2025-11-02 14:26:09'),
(30, 'Animals', 'Animal-themed plush toys', 1, '2025-11-02 14:26:09'),
(31, 'Fantasy', 'Mythical and fantasy creature plushies', 1, '2025-11-02 14:26:09'),
(32, 'Baby & Toddler', 'Safe plushies for young children', 1, '2025-11-02 14:26:09'),
(33, 'Home Decor', 'Decorative plush items', 1, '2025-11-02 14:26:09'),
(34, 'Accessories', 'Plush keychains and accessories', 1, '2025-11-02 14:26:09'),
(35, 'Licensed Characters', 'Popular character plushies', 1, '2025-11-02 14:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_date` date NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'cash',
  `receipt_number` varchar(100) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_date`, `category`, `description`, `amount`, `payment_method`, `receipt_number`, `vendor_name`, `notes`, `supplier_id`, `created_by`, `created_at`, `updated_at`) VALUES
(16, '2025-11-02', 'Inventory', 'Initial stock for product: Product 2 (10 units @ ₱800 each)', 8000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', NULL, 1, '2025-11-02 10:01:47', '2025-11-02 10:01:47'),
(18, '2025-11-02', 'Inventory', 'Restocking: Keychain Pals Set (+50 units @ ₱500 each)', 25000.00, 'cash', NULL, 'Global Toys International', 'Auto-generated expense for product restocking', 13, 1, '2025-11-02 15:00:17', '2025-11-02 15:00:17');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_on_hand` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) DEFAULT 10,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity_on_hand`, `reorder_level`, `updated_at`) VALUES
(24, 44, 45, 10, '2025-11-02 16:55:12'),
(25, 45, 120, 20, '2025-11-02 14:26:09'),
(26, 46, 80, 15, '2025-11-02 14:26:09'),
(29, 49, 60, 10, '2025-11-02 14:26:09'),
(30, 50, 100, 20, '2025-11-02 16:55:12'),
(31, 51, 40, 10, '2025-11-02 14:26:09'),
(32, 52, 130, 25, '2025-11-02 14:26:09'),
(33, 53, 70, 15, '2025-11-02 14:26:09'),
(36, 56, 95, 20, '2025-11-02 14:26:09'),
(37, 57, 120, 25, '2025-11-02 14:26:09'),
(38, 58, 75, 15, '2025-11-02 14:26:09'),
(40, 60, 25, 5, '2025-11-02 14:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `order_status` enum('pending','shipped','completed','cancelled') DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_number`, `order_status`, `subtotal`, `shipping_cost`, `tax_amount`, `total_amount`, `created_at`, `updated_at`) VALUES
(19, 8, 'ORD-1762076156-8', 'completed', 5000.00, 0.00, 0.00, 5000.00, '2025-11-02 09:35:56', '2025-11-02 09:38:00'),
(20, 8, 'ORD-1762077738-8', 'completed', 10000.00, 0.00, 0.00, 10000.00, '2025-11-02 10:02:18', '2025-11-02 10:02:50'),
(21, 8, 'ORD-1762095690-8', 'completed', 8990.00, 0.00, 0.00, 8990.00, '2025-11-02 15:01:30', '2025-11-02 15:02:07'),
(22, 8, 'ORD-1762095850-8', 'completed', 21980.00, 0.00, 0.00, 21980.00, '2025-11-02 15:04:10', '2025-11-02 15:04:43'),
(23, 11, 'ORD-1762096676-11', 'cancelled', 9495.00, 0.00, 0.00, 9495.00, '2025-11-02 15:17:56', '2025-11-02 15:18:04'),
(24, 11, 'ORD-1762096716-11', 'cancelled', 14990.00, 0.00, 0.00, 14990.00, '2025-11-02 15:18:36', '2025-11-02 15:18:41'),
(25, 11, 'ORD-1762096743-11', 'completed', 7495.00, 0.00, 0.00, 7495.00, '2025-11-02 15:19:03', '2025-11-02 15:19:46'),
(26, 11, 'ORD-1762097047-11', 'completed', 16240.00, 0.00, 0.00, 16240.00, '2025-11-02 15:24:07', '2025-11-02 15:24:32'),
(27, 11, 'ORD-1762097231-11', 'completed', 14584.00, 0.00, 0.00, 14584.00, '2025-11-02 15:27:11', '2025-11-02 15:27:35'),
(28, 11, 'ORD-1762098406-11', 'completed', 13240.00, 0.00, 0.00, 13240.00, '2025-11-02 15:46:46', '2025-11-02 15:47:40'),
(29, 11, 'ORD-1762099672-11', 'completed', 14490.00, 0.00, 0.00, 14490.00, '2025-11-02 16:07:52', '2025-11-02 16:08:45'),
(30, 11, 'ORD-1762102512-11', 'completed', 20990.00, 0.00, 0.00, 20990.00, '2025-11-02 16:55:12', '2025-11-02 16:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `customer_name`, `customer_email`, `total_amount`, `items`, `created_at`) VALUES
(6, 19, 'RON JEREMY PRIMAVERA', 'primaveraron@gmail.com', 5000.00, '[{\"product_name\":\"Product 1\",\"quantity\":5,\"unit_price\":\"1000.00\"}]', '2025-11-02 09:35:56'),
(7, 20, 'RON JEREMY PRIMAVERA', 'primaveraron@gmail.com', 10000.00, '[{\"product_name\":\"Product 2\",\"quantity\":10,\"unit_price\":\"1000.00\"}]', '2025-11-02 10:02:18'),
(8, 21, 'RON JEREMY PRIMAVERA', 'primaveraron@gmail.com', 8990.00, '[{\"product_name\":\"Keychain Pals Set\",\"quantity\":10,\"unit_price\":\"899.00\"}]', '2025-11-02 15:01:30'),
(9, 22, 'RON JEREMY PRIMAVERA', 'primaveraron@gmail.com', 21980.00, '[{\"product_name\":\"Lotus Flower Plush\",\"quantity\":20,\"unit_price\":\"1099.00\"}]', '2025-11-02 15:04:10'),
(10, 25, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 7495.00, '[{\"product_name\":\"Ocean Dolphin\",\"quantity\":5,\"unit_price\":\"1499.00\"}]', '2025-11-02 15:19:03'),
(11, 26, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 16240.00, '[{\"product_name\":\"Lion King\",\"quantity\":5,\"unit_price\":\"1899.00\"},{\"product_name\":\"Owl Wisdom\",\"quantity\":5,\"unit_price\":\"1349.00\"}]', '2025-11-02 15:24:07'),
(12, 27, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 14584.00, '[{\"product_name\":\"Lotus Flower Plush\",\"quantity\":1,\"unit_price\":\"1099.00\"},{\"product_name\":\"Keychain Pals Set\",\"quantity\":15,\"unit_price\":\"899.00\"}]', '2025-11-02 15:27:11'),
(13, 28, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 13240.00, '[{\"product_name\":\"Ocean Dolphin\",\"quantity\":5,\"unit_price\":\"1499.00\"},{\"product_name\":\"Turtle Buddy\",\"quantity\":5,\"unit_price\":\"1149.00\"}]', '2025-11-02 15:46:46'),
(14, 29, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 14490.00, '[{\"product_name\":\"Holiday Santa\",\"quantity\":5,\"unit_price\":\"1299.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-02 16:07:52'),
(15, 30, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 20990.00, '[{\"product_name\":\"Dragon Collectible\",\"quantity\":5,\"unit_price\":\"2599.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-02 16:55:12');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `item_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `unit_price`, `item_total`, `created_at`) VALUES
(22, 21, NULL, 'Keychain Pals Set', NULL, 10, 899.00, 8990.00, '2025-11-02 15:01:30'),
(30, 27, NULL, 'Keychain Pals Set', NULL, 15, 899.00, 13485.00, '2025-11-02 15:27:11'),
(33, 21, NULL, 'Keychain Pals Set', '', 1, 10.00, 10.00, '2025-11-02 16:04:50'),
(34, 29, NULL, 'Holiday Santa', '', 5, 1299.00, 6495.00, '2025-11-02 16:07:52'),
(35, 29, 50, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-02 16:07:52'),
(36, 30, 44, 'Dragon Collectible', '', 5, 2599.00, 12995.00, '2025-11-02 16:55:12'),
(37, 30, 50, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-02 16:55:12');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `supplier_id`, `product_name`, `description`, `cost_price`, `selling_price`, `img_path`, `is_active`, `created_at`, `updated_at`) VALUES
(44, 27, 12, 'Dragon Collectible', 'Limited edition dragon plush, hand-stitched', 1500.00, 2599.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:27:37'),
(45, 31, 11, 'Unicorn Dreams', 'Rainbow unicorn with sparkly horn', 950.00, 1699.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:27:51'),
(46, 32, 14, 'Baby Elephant', 'Gentle elephant for babies, organic cotton', 1100.00, 1899.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:27:48'),
(49, 28, 12, 'Gift Set Deluxe', 'Three mini plushies in gift box', 1300.00, 2299.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:01'),
(50, 26, 10, 'Panda Cuddles', 'Black and white panda, extra soft', 900.00, 1599.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 17:12:31'),
(51, 31, 12, 'Phoenix Rising', 'Majestic phoenix with vibrant colors', 1600.00, 2799.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:10'),
(52, 30, 11, 'Bunny Hop', 'Adorable white bunny with floppy ears', 700.00, 1249.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:13'),
(53, 33, 13, 'Throw Pillow Bear', 'Decorative bear-shaped pillow', 1150.00, 1999.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:16'),
(56, 31, 11, 'Mermaid Princess', 'Beautiful mermaid with iridescent tail', 1000.00, 1799.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:30'),
(57, 35, 13, 'Cartoon Hero Bear', 'Licensed superhero character plush', 900.00, 1549.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:27'),
(58, 29, 10, 'Easter Bunny Special', 'Seasonal Easter bunny with basket', 800.00, 1399.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:28:36'),
(60, 27, 12, 'Collectors Dragon Gold', 'Premium gold dragon, numbered edition', 2600.00, 4499.00, NULL, 1, '2025-11-02 14:26:09', '2025-11-02 14:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_carts`
--

CREATE TABLE `shopping_carts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_carts`
--

INSERT INTO `shopping_carts` (`id`, `customer_id`, `created_at`, `updated_at`) VALUES
(3, 1, '2025-11-01 10:38:25', '2025-11-01 10:38:25'),
(6, 8, '2025-11-02 07:31:50', '2025-11-02 07:31:50'),
(8, 11, '2025-11-02 15:12:05', '2025-11-02 15:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `contact_person`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(10, 'Soft Touch Manufacturing', 'John Smith', '555-0101', 'john@softtouch.com', '123 Factory Lane, Manila', '2025-11-02 14:26:09', '2025-11-02 14:26:09'),
(11, 'Cuddly Creations Co.', 'Sarah Johnson', '555-0102', 'sarah@cuddlycreations.com', '456 Plush Street, Cebu', '2025-11-02 14:26:09', '2025-11-02 14:26:09'),
(12, 'Premium Plush Ltd.', 'Mike Chen', '555-0103', 'mike@premiumplush.com', '789 Comfort Ave, Davao', '2025-11-02 14:26:09', '2025-11-02 14:26:09'),
(13, 'Global Toys International', 'Emma Davis', '555-0104', 'emma@globaltoys.com', '321 Trade Blvd, Makati', '2025-11-02 14:26:09', '2025-11-02 14:26:09'),
(14, 'EcoFriendly Plushies Inc.', 'David Brown', '555-0105', 'david@ecoplush.com', '654 Green Way, Quezon City', '2025-11-02 14:26:09', '2025-11-02 14:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Philippines',
  `profile_picture` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `phone`, `address`, `city`, `postal_code`, `country`, `profile_picture`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin@shop.com', 'ADMIN', 'PRIMAVERA', '', 'NORTH DAANGHARI', 'TAGUIG CITY', '1632', 'Philippines', 'user_1_1762052651.png', '$2y$12$jimZJ6U8/A9zi6tvIzA4BeYEHR.2s7kaJi6oisRcgMLIzjXSzr9SS', 'admin', '2025-10-27 15:03:27'),
(8, 'primaveraron@gmail.com', 'RON JEREMY', 'PRIMAVERA', '09943327537', '23 ROAD 8\r\nNorth Daanghari', 'Taguig City', '1630', 'Philippines', NULL, '$2y$12$.EQHu.qXMkGWu6vmuaZqseiKR31vTN.P41VZ1umC7au6hCG1S62Yy', 'customer', '2025-11-02 07:31:13'),
(11, 'juliannetumpap08@gmail.com', 'Julianne', 'Tumpap', '09771910453', 'Culdesac, Sun Valley', 'Parañaque City', '0', 'Philippines', NULL, '$2y$12$TVJZwOX/jmyk3iF8E8hoLe19mSiQUHQwug7WS2nsGAtuV5sjvhzSO', 'customer', '2025-11-02 10:26:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_cart` (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_expense_date` (`expense_date`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `expenses_ibfk_supplier` (`supplier_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_status` (`order_status`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `order_items_fk_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_supplier` (`supplier_id`);

--
-- Indexes for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_name` (`supplier_name`),
  ADD KEY `idx_supplier_name` (`supplier_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_ibfk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD CONSTRAINT `shopping_carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
