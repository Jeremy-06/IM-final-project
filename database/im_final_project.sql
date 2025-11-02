-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 05:41 AM
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
(7, 'Category 1', '', 1, '2025-11-01 15:19:06'),
(8, 'Category 2', '', 1, '2025-11-01 16:01:45'),
(9, 'Category 3', '', 1, '2025-11-01 16:01:51'),
(10, 'Category 4', '', 1, '2025-11-01 16:01:56'),
(11, 'Category 5', '', 1, '2025-11-01 16:02:02'),
(12, 'Category 6', '', 1, '2025-11-01 16:20:34'),
(13, 'Category 7', '', 1, '2025-11-01 16:20:42'),
(14, 'Category 8', '', 1, '2025-11-01 16:20:49');

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
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_date`, `category`, `description`, `amount`, `payment_method`, `receipt_number`, `vendor_name`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2025-10-01', 'Inventory', 'Purchase of plushie stock from supplier', 15000.00, 'bank_transfer', NULL, 'Plushie Wholesale Co.', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(2, '2025-10-05', 'Shipping', 'Shipping costs for October deliveries', 2500.00, 'cash', NULL, 'FastShip Logistics', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(3, '2025-10-10', 'Marketing', 'Social media advertising campaign', 3000.00, 'credit_card', NULL, 'Facebook Ads', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(4, '2025-10-15', 'Utilities', 'Office electricity and internet bill', 1200.00, 'bank_transfer', NULL, 'City Utilities', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(5, '2025-10-20', 'Packaging', 'Boxes, bubble wrap, and packaging materials', 800.00, 'cash', NULL, 'PackPro Supply', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(6, '2025-10-25', 'Maintenance', 'Website hosting and maintenance', 500.00, 'credit_card', NULL, 'WebHost Pro', NULL, 1, '2025-11-01 15:11:03', '2025-11-01 15:11:03'),
(7, '2025-11-01', 'Inventory', 'Restocking: Product 5 (+50 units @ ₱90 each)', 4500.00, 'cash', NULL, NULL, 'Auto-generated expense for product restocking', 1, '2025-11-01 16:53:49', '2025-11-01 16:53:49'),
(8, '2025-11-02', 'Inventory', 'Initial stock for product: Product 6 (50 units @ ₱120 each)', 6000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:42:33', '2025-11-01 18:42:33'),
(9, '2025-11-02', 'Inventory', 'Initial stock for product: Product 7 (50 units @ ₱100 each)', 5000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:43:03', '2025-11-01 18:43:03'),
(10, '2025-11-02', 'Inventory', 'Initial stock for product: Product 8 (50 units @ ₱100 each)', 5000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:43:28', '2025-11-01 18:43:28'),
(11, '2025-11-02', 'Inventory', 'Initial stock for product: Product 9 (50 units @ ₱100 each)', 5000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:43:55', '2025-11-01 18:43:55'),
(12, '2025-11-02', 'Inventory', 'Initial stock for product: Product 10 (50 units @ ₱100 each)', 5000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:44:14', '2025-11-01 18:44:14'),
(13, '2025-11-02', 'Inventory', 'Initial stock for product: Product 11 (50 units @ ₱500 each)', 25000.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', 1, '2025-11-01 18:49:11', '2025-11-01 18:49:11');

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
(7, 7, 50, 10, '2025-11-01 16:03:48'),
(8, 8, 50, 10, '2025-11-01 16:04:13'),
(9, 9, 50, 10, '2025-11-01 16:04:32'),
(11, 11, 50, 10, '2025-11-01 18:42:33'),
(12, 12, 50, 10, '2025-11-01 18:43:03'),
(13, 13, 40, 10, '2025-11-02 04:18:05'),
(14, 14, 50, 10, '2025-11-01 18:43:55'),
(15, 15, 50, 10, '2025-11-01 18:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
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
(1, 3, 'ORD-1761787326-3', 'completed', 162.94, 50.00, 19.55, 232.49, '2025-10-30 01:22:06', '2025-11-01 14:15:39'),
(2, 3, 'ORD-1761787435-3', 'completed', 19.99, 50.00, 2.40, 72.39, '2025-10-30 01:23:55', '2025-11-01 14:15:36'),
(3, 3, 'ORD-1761787889-3', 'completed', 12.99, 50.00, 1.56, 64.55, '2025-10-30 01:31:29', '2025-11-01 11:15:43'),
(4, 3, 'ORD-1761995143-3', 'completed', 12.99, 50.00, 1.56, 64.55, '2025-11-01 11:05:43', '2025-11-01 14:15:33'),
(5, 3, 'ORD-1761996660-3', 'completed', 99.95, 50.00, 11.99, 161.94, '2025-11-01 11:31:00', '2025-11-01 14:15:31'),
(6, 3, 'ORD-1762002933-3', 'completed', 149.95, 0.00, 0.00, 149.95, '2025-11-01 13:15:33', '2025-11-01 14:15:28'),
(7, 3, 'ORD-1762007273-3', 'cancelled', 12.99, 0.00, 0.00, 12.99, '2025-11-01 14:27:53', '2025-11-01 14:55:23'),
(8, 3, 'ORD-1762019182-3', 'completed', 295.00, 0.00, 0.00, 295.00, '2025-11-01 17:46:22', '2025-11-01 18:16:41'),
(9, 3, 'ORD-1762021339-3', 'completed', 550.00, 0.00, 0.00, 550.00, '2025-11-01 18:22:19', '2025-11-01 18:23:06'),
(10, 3, 'ORD-1762056282-3', 'completed', 5500.00, 0.00, 0.00, 5500.00, '2025-11-02 04:04:42', '2025-11-02 04:06:06'),
(11, 3, 'ORD-1762057085-3', 'completed', 1500.00, 0.00, 0.00, 1500.00, '2025-11-02 04:18:05', '2025-11-02 04:25:32'),
(12, 5, 'TEST-1762058204', 'completed', 25.00, 5.00, 3.00, 33.00, '2025-11-02 04:36:44', '2025-11-02 04:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
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
(13, 11, 13, 'Product 8', '', 10, 150.00, 1500.00, '2025-11-02 04:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
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

INSERT INTO `products` (`id`, `category_id`, `product_name`, `description`, `cost_price`, `selling_price`, `img_path`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 8, 'Product 2', 'sample', 60.00, 80.00, '', 1, '2025-11-01 16:03:48', '2025-11-01 16:03:48'),
(8, 7, 'Product 3', 'sample', 70.00, 90.00, '', 1, '2025-11-01 16:04:13', '2025-11-01 18:50:01'),
(9, 7, 'Product 4', 'sample', 80.00, 100.00, '', 1, '2025-11-01 16:04:32', '2025-11-01 18:49:54'),
(11, 7, 'Product 6', 'sample', 120.00, 140.00, '', 1, '2025-11-01 18:42:33', '2025-11-01 18:42:33'),
(12, 7, 'Product 7', 'sample', 100.00, 150.00, '', 1, '2025-11-01 18:43:03', '2025-11-01 18:43:03'),
(13, 7, 'Product 8', 'sample', 100.00, 150.00, '', 1, '2025-11-01 18:43:28', '2025-11-01 18:43:28'),
(14, 7, 'Product 9', 'sample', 100.00, 150.00, '', 1, '2025-11-01 18:43:55', '2025-11-01 18:43:55'),
(15, 7, 'Product 10', 'sample', 100.00, 150.00, '', 0, '2025-11-01 18:44:14', '2025-11-02 03:06:32');

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
(2, 3, '2025-10-30 01:20:16', '2025-10-30 01:20:16'),
(3, 1, '2025-11-01 10:38:25', '2025-11-01 10:38:25');

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
(3, 'jeremy@gmail.com', 'RON JEREMY', 'PRIMAVERA', '', '23 ROAD 8\r\nNorth Daanghari', 'Taguig City', '1630', 'Philippines', 'user_3_1762052624.png', '$2y$12$8mT0Zp/PN9wApJqnnV3hMuv5T.frbce/B5JAP1a8FdHCGIfQlU6AG', 'customer', '2025-10-30 01:18:54'),
(5, 'test.customer@example.com', 'Test', 'Customer', NULL, NULL, NULL, NULL, 'Philippines', NULL, '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5YmMxSUmGEJOi', 'customer', '2025-11-02 04:36:44');

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
  ADD KEY `idx_category` (`category`);

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
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`),
  ADD KEY `idx_customer` (`customer_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD CONSTRAINT `shopping_carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
