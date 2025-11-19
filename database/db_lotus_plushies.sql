-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 04:11 AM
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
(64, 9, 66, 1, '2025-11-16 14:51:42'),
(65, 8, 71, 1, '2025-11-16 15:30:59');

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
(26, 'Animals', 'Soft and cuddly animal plush toys', 1, '2025-11-02 14:26:09'),
(36, 'Seasonal', 'Holiday and seasonal themed plushies', 1, '2025-11-16 15:20:25'),
(37, 'Plush Sets', 'Product in bulk / multiple Items', 1, '2025-11-16 15:48:53'),
(38, 'Soft Cotton', 'Made with soft cotton', 1, '2025-11-16 15:49:24');

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
(18, '2025-11-02', 'Inventory', 'Restocking: Keychain Pals Set (+50 units @ ₱500 each)', 25000.00, 'cash', NULL, 'Global Toys International', 'Auto-generated expense for product restocking', NULL, 1, '2025-11-02 15:00:17', '2025-11-02 15:00:17'),
(19, '2025-11-04', 'Inventory', 'Restocking: Dragon Collectible (+55 units @ ₱1500 each)', 82500.00, 'cash', NULL, 'Premium Plush Ltd.', 'Auto-generated expense for product restocking', NULL, 1, '2025-11-04 00:27:44', '2025-11-04 00:27:44'),
(20, '2025-11-07', 'Inventory', 'Restocking: Dragon Collectible (+60 units @ ₱1500 each)', 90000.00, 'cash', NULL, 'Premium Plush Ltd.', 'Auto-generated expense for product restocking', NULL, 1, '2025-11-07 06:21:04', '2025-11-07 06:21:04'),
(21, '2025-11-09', 'Inventory', 'Initial stock for product: ne (5 units @ ₱5 each)', 25.00, 'cash', NULL, 'Cuddly Creations Co.', 'Auto-generated expense for initial product stock', NULL, 1, '2025-11-08 18:10:50', '2025-11-08 18:10:50'),
(22, '2025-11-09', 'Inventory', 'Initial stock for product: aa (5 units @ ₱5 each)', 25.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', NULL, 1, '2025-11-08 18:16:46', '2025-11-08 18:16:46'),
(23, '2025-11-09', 'Inventory', 'Initial stock for product: a (5 units @ ₱5 each)', 25.00, 'cash', NULL, NULL, 'Auto-generated expense for initial product stock', NULL, 1, '2025-11-08 18:17:14', '2025-11-08 18:17:14'),
(24, '2025-11-09', 'Inventory', 'Initial stock for product: a (5 units @ ₱5 each)', 25.00, 'cash', NULL, 'Cuddly Creations Co.', 'Auto-generated expense for initial product stock', NULL, 1, '2025-11-08 18:21:04', '2025-11-08 18:21:04'),
(25, '2025-11-16', 'Inventory', 'Initial stock for product: Aurora Bear (20 units @ ₱650 each)', 13000.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 13:59:04', '2025-11-16 13:59:04'),
(26, '2025-11-16', 'Inventory', 'Initial stock for product: Axolotl (15 units @ ₱600 each)', 9000.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for initial product stock', 16, 14, '2025-11-16 14:00:55', '2025-11-16 14:00:55'),
(27, '2025-11-16', 'Inventory', 'Initial stock for product: Pigeon and Dovey (10 units @ ₱1400 each)', 14000.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 14, '2025-11-16 14:04:49', '2025-11-16 14:04:49'),
(28, '2025-11-16', 'Inventory', 'Initial stock for product: Bun Bun (18 units @ ₱600 each)', 10800.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 14:06:22', '2025-11-16 14:06:22'),
(29, '2025-11-16', 'Inventory', 'Initial stock for product: Capybara Familt (45 units @ ₱700 each)', 31500.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 14, '2025-11-16 14:14:33', '2025-11-16 14:14:33'),
(30, '2025-11-16', 'Inventory', 'Initial stock for product: Sakura Cat (20 units @ ₱1200 each)', 24000.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 14, '2025-11-16 14:18:19', '2025-11-16 14:18:19'),
(31, '2025-11-16', 'Inventory', 'Initial stock for product: Sakura Cow (20 units @ ₱1200 each)', 24000.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 14, '2025-11-16 14:19:43', '2025-11-16 14:19:43'),
(32, '2025-11-16', 'Inventory', 'Initial stock for product: Derpy (15 units @ ₱2000 each)', 30000.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for initial product stock', 16, 14, '2025-11-16 14:21:48', '2025-11-16 14:21:48'),
(33, '2025-11-16', 'Inventory', 'Restocking: Axolotl (+20 units @ ₱600 each)', 12000.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for product restocking', 16, 14, '2025-11-16 14:22:21', '2025-11-16 14:22:21'),
(34, '2025-11-16', 'Inventory', 'Initial stock for product: Dragon (12 units @ ₱3500 each)', 42000.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for initial product stock', 16, 14, '2025-11-16 14:24:00', '2025-11-16 14:24:00'),
(35, '2025-11-16', 'Inventory', 'Initial stock for product: Fox Tales (10 units @ ₱4650 each)', 46500.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for initial product stock', 16, 14, '2025-11-16 14:28:14', '2025-11-16 14:28:14'),
(36, '2025-11-16', 'Inventory', 'Initial stock for product: Froggit (50 units @ ₱400 each)', 20000.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 14, '2025-11-16 14:30:39', '2025-11-16 14:30:39'),
(37, '2025-11-16', 'Inventory', 'Initial stock for product: Hedgie Huggies (25 units @ ₱3000 each)', 75000.00, 'cash', NULL, 'Plush-A-Toy', 'Auto-generated expense for initial product stock', 16, 14, '2025-11-16 14:32:18', '2025-11-16 14:32:18'),
(38, '2025-11-16', 'Inventory', 'Initial stock for product: Lambutan (25 units @ ₱800 each)', 20000.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 14:35:13', '2025-11-16 14:35:13'),
(39, '2025-11-16', 'Inventory', 'Initial stock for product: Panda (30 units @ ₱500 each)', 15000.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 14:47:14', '2025-11-16 14:47:14'),
(40, '2025-11-16', 'Inventory', 'Initial stock for product: Panda (30 units @ ₱500 each)', 15000.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 14:47:15', '2025-11-16 14:47:15'),
(41, '2025-11-16', 'Inventory', 'Initial stock for product: Sealing (40 units @ ₱700 each)', 28000.00, 'cash', NULL, 'Snuggle Teddy PH', 'Auto-generated expense for initial product stock', 17, 14, '2025-11-16 14:49:10', '2025-11-16 14:49:10'),
(42, '2025-11-17', 'Inventory', 'Initial stock for product: New Product (20 units @ ₱50 each)', 1000.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 1, '2025-11-17 07:22:12', '2025-11-17 07:22:12'),
(43, '2025-11-17', 'Inventory', 'Initial stock for product: a (1 units @ ₱1 each)', 1.00, 'cash', NULL, 'GTG Stuffed Toys', 'Auto-generated expense for initial product stock', 15, 1, '2025-11-17 08:27:53', '2025-11-17 08:27:53');

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
(46, 66, 16, 10, '2025-11-16 18:23:05'),
(47, 67, 28, 10, '2025-11-16 16:41:08'),
(48, 68, 10, 10, '2025-11-16 14:04:49'),
(49, 69, 17, 10, '2025-11-17 11:04:49'),
(50, 70, 44, 10, '2025-11-16 16:49:23'),
(51, 71, 19, 10, '2025-11-17 12:01:23'),
(52, 72, 18, 10, '2025-11-16 18:05:32'),
(53, 73, 11, 10, '2025-11-17 11:09:35'),
(54, 74, 10, 10, '2025-11-16 16:23:49'),
(55, 75, 10, 10, '2025-11-17 12:01:06'),
(56, 76, 49, 10, '2025-11-17 10:38:56'),
(57, 77, 25, 10, '2025-11-16 14:32:18'),
(58, 78, 20, 10, '2025-11-17 10:02:32'),
(59, 79, 29, 10, '2025-11-17 10:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','delivered','completed','cancelled') DEFAULT 'pending',
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
(19, NULL, 'ORD-1762076156-8', 'completed', 5000.00, 50.00, 0.00, 5050.00, '2025-11-02 09:35:56', '2025-11-16 18:21:40'),
(20, NULL, 'ORD-1762077738-8', 'completed', 10000.00, 50.00, 0.00, 10050.00, '2025-11-02 10:02:18', '2025-11-16 18:21:40'),
(21, NULL, 'ORD-1762095690-8', 'completed', 8990.00, 50.00, 0.00, 9040.00, '2025-11-02 15:01:30', '2025-11-16 18:21:40'),
(22, NULL, 'ORD-1762095850-8', 'completed', 21980.00, 50.00, 0.00, 22030.00, '2025-11-02 15:04:10', '2025-11-16 18:21:40'),
(23, 11, 'ORD-1762096676-11', 'cancelled', 9495.00, 50.00, 0.00, 9545.00, '2025-11-02 15:17:56', '2025-11-16 18:21:40'),
(24, 11, 'ORD-1762096716-11', 'cancelled', 14990.00, 50.00, 0.00, 15040.00, '2025-11-02 15:18:36', '2025-11-16 18:21:40'),
(25, 11, 'ORD-1762096743-11', 'completed', 7495.00, 50.00, 0.00, 7545.00, '2025-11-02 15:19:03', '2025-11-16 18:21:40'),
(26, 11, 'ORD-1762097047-11', 'completed', 16240.00, 50.00, 0.00, 16290.00, '2025-11-02 15:24:07', '2025-11-16 18:21:40'),
(27, 11, 'ORD-1762097231-11', 'completed', 14584.00, 50.00, 0.00, 14634.00, '2025-11-02 15:27:11', '2025-11-16 18:21:40'),
(28, 11, 'ORD-1762098406-11', 'completed', 13240.00, 50.00, 0.00, 13290.00, '2025-11-02 15:46:46', '2025-11-16 18:21:40'),
(29, 11, 'ORD-1762099672-11', 'completed', 14490.00, 50.00, 0.00, 14540.00, '2025-11-02 16:07:52', '2025-11-16 18:21:40'),
(30, 11, 'ORD-1762102512-11', 'completed', 20990.00, 50.00, 0.00, 21040.00, '2025-11-02 16:55:12', '2025-11-16 18:21:40'),
(31, 11, 'ORD-1762216234-11', 'completed', 29485.00, 50.00, 0.00, 29535.00, '2025-11-04 00:30:34', '2025-11-16 18:21:40'),
(32, 11, 'ORD-1762238186-11', 'completed', 29485.00, 50.00, 0.00, 29535.00, '2025-11-04 06:36:26', '2025-11-16 18:21:40'),
(33, NULL, 'ORD-1762395560-8', 'completed', 12490.00, 50.00, 0.00, 12540.00, '2025-11-06 02:19:20', '2025-11-16 18:21:40'),
(34, 14, 'ORD-1762882412-14', 'completed', 15192.00, 50.00, 0.00, 15242.00, '2025-11-11 17:33:32', '2025-11-16 18:21:40'),
(35, 11, 'ORD-1762882521-11', 'completed', 20990.00, 50.00, 0.00, 21040.00, '2025-11-11 17:35:21', '2025-11-16 18:21:40'),
(36, 11, 'ORD-1762882690-11', 'completed', 2799.00, 50.00, 0.00, 2849.00, '2025-11-11 17:38:10', '2025-11-16 18:21:40'),
(37, 11, 'ORD-1762883823-11', 'completed', 2799.00, 50.00, 0.00, 2849.00, '2025-11-11 17:57:03', '2025-11-16 18:21:40'),
(38, 11, 'ORD-1763302383-11', 'completed', 4500.00, 50.00, 0.00, 4550.00, '2025-11-16 14:13:03', '2025-11-16 18:21:40'),
(39, 11, 'ORD-1763302485-11', 'completed', 1900.00, 50.00, 0.00, 1950.00, '2025-11-16 14:14:45', '2025-11-16 18:21:40'),
(40, NULL, 'ORD-1763309965-15', 'completed', 5000.00, 50.00, 0.00, 5050.00, '2025-11-16 16:19:25', '2025-11-16 18:21:40'),
(41, NULL, 'ORD-1763310229-15', 'completed', 8000.00, 50.00, 0.00, 8050.00, '2025-11-16 16:23:49', '2025-11-16 18:21:40'),
(42, NULL, 'ORD-1763310948-15', 'completed', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 16:35:48', '2025-11-16 18:21:40'),
(43, NULL, 'ORD-1763311268-15', 'completed', 1800.00, 50.00, 0.00, 1850.00, '2025-11-16 16:41:08', '2025-11-16 18:21:40'),
(44, NULL, 'ORD-1763311763-15', 'completed', 1000.00, 50.00, 0.00, 1050.00, '2025-11-16 16:49:23', '2025-11-16 18:21:40'),
(45, NULL, 'ORD-1763312572-15', 'cancelled', 5000.00, 50.00, 0.00, 5050.00, '2025-11-16 17:02:52', '2025-11-16 18:21:40'),
(46, NULL, 'ORD-1763312730-15', 'cancelled', 2500.00, 50.00, 0.00, 2550.00, '2025-11-16 17:05:30', '2025-11-16 18:21:40'),
(47, NULL, 'ORD-1763312911-15', 'cancelled', 2500.00, 50.00, 0.00, 2550.00, '2025-11-16 17:08:31', '2025-11-16 18:21:40'),
(48, NULL, 'ORD-1763313060-15', 'completed', 2500.00, 50.00, 0.00, 2550.00, '2025-11-16 17:11:00', '2025-11-16 18:21:40'),
(49, NULL, 'ORD-1763313411-15', 'completed', 2500.00, 50.00, 0.00, 2550.00, '2025-11-16 17:16:51', '2025-11-17 09:47:04'),
(50, NULL, 'ORD-1763314816-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 17:40:16', '2025-11-16 18:21:40'),
(51, NULL, 'ORD-1763315182-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 17:46:22', '2025-11-16 18:21:40'),
(52, NULL, 'ORD-1763315424-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 17:50:24', '2025-11-16 18:21:40'),
(53, NULL, 'ORD-1763315584-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 17:53:04', '2025-11-16 18:21:40'),
(54, NULL, 'ORD-1763315908-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 17:58:28', '2025-11-16 18:21:40'),
(55, NULL, 'ORD-1763316332-15', 'cancelled', 1500.00, 50.00, 0.00, 1550.00, '2025-11-16 18:05:32', '2025-11-17 09:47:18'),
(56, NULL, 'ORD-1763317385-15', 'completed', 1900.00, 50.00, 0.00, 1950.00, '2025-11-16 18:23:05', '2025-11-16 18:24:45'),
(57, NULL, 'ORD-1763318177-15', 'completed', 2000.00, 50.00, 0.00, 2050.00, '2025-11-16 18:36:17', '2025-11-16 18:39:11'),
(58, NULL, 'ORD-1763318820-15', 'cancelled', 1000.00, 50.00, 0.00, 1050.00, '2025-11-16 18:47:00', '2025-11-16 18:47:59'),
(59, NULL, 'ORD-1763318948-15', 'completed', 1000.00, 50.00, 0.00, 1050.00, '2025-11-16 18:49:08', '2025-11-17 09:47:11'),
(60, NULL, 'ORD-1763364218-15', 'completed', 300.00, 50.00, 0.00, 350.00, '2025-11-17 07:23:38', '2025-11-17 07:26:19'),
(61, NULL, 'ORD-1763370286-15', 'completed', 1000.00, 50.00, 0.00, 1050.00, '2025-11-17 09:04:46', '2025-11-17 09:46:58'),
(62, NULL, 'ORD-1763373752-16', 'cancelled', 1800.00, 50.00, 0.00, 1850.00, '2025-11-17 10:02:32', '2025-11-17 11:06:03'),
(63, 18, 'ORD-1763374560-18', 'cancelled', 800.00, 50.00, 0.00, 850.00, '2025-11-17 10:16:00', '2025-11-17 10:31:59'),
(64, 18, 'ORD-1763375936-18', 'completed', 600.00, 50.00, 0.00, 650.00, '2025-11-17 10:38:56', '2025-11-17 10:44:06'),
(65, 18, 'ORD-1763377386-18', 'cancelled', 5000.00, 50.00, 0.00, 5050.00, '2025-11-17 11:03:06', '2025-11-17 11:04:21'),
(66, 18, 'ORD-1763377489-18', 'completed', 950.00, 50.00, 0.00, 1000.00, '2025-11-17 11:04:49', '2025-11-17 11:06:08'),
(67, 18, 'ORD-1763377666-18', 'cancelled', 2500.00, 50.00, 0.00, 2550.00, '2025-11-17 11:07:46', '2025-11-17 11:09:35'),
(68, 18, 'ORD-1763380258-18', 'cancelled', 5000.00, 50.00, 0.00, 5050.00, '2025-11-17 11:50:58', '2025-11-17 12:01:06'),
(69, 18, 'ORD-1763380883-18', 'completed', 1500.00, 50.00, 0.00, 1550.00, '2025-11-17 12:01:23', '2025-11-17 12:02:18');

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
(9, 22, 'RON JEREMY PRIMAVERA', 'primaveraron@gmail.com', 21980.00, '[{\"product_name\":\"Lotus Flower Plush\",\"quantity\":20,\"unit_price\":\"1099.00\"}]', '2025-11-02 15:04:10'),
(10, 25, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 7495.00, '[{\"product_name\":\"Ocean Dolphin\",\"quantity\":5,\"unit_price\":\"1499.00\"}]', '2025-11-02 15:19:03'),
(11, 26, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 16240.00, '[{\"product_name\":\"Lion King\",\"quantity\":5,\"unit_price\":\"1899.00\"},{\"product_name\":\"Owl Wisdom\",\"quantity\":5,\"unit_price\":\"1349.00\"}]', '2025-11-02 15:24:07'),
(13, 28, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 13240.00, '[{\"product_name\":\"Ocean Dolphin\",\"quantity\":5,\"unit_price\":\"1499.00\"},{\"product_name\":\"Turtle Buddy\",\"quantity\":5,\"unit_price\":\"1149.00\"}]', '2025-11-02 15:46:46'),
(21, 38, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 4500.00, '[{\"product_name\":\"Axolotl\",\"quantity\":5,\"unit_price\":\"900.00\"}]', '2025-11-16 14:13:21'),
(22, 39, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 1900.00, '[{\"product_name\":\"Aurora Bear\",\"quantity\":2,\"unit_price\":\"950.00\"}]', '2025-11-16 14:15:04'),
(23, 40, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 5000.00, '[{\"product_name\":\"Derpy\",\"quantity\":2,\"unit_price\":\"2500.00\"}]', '2025-11-16 16:21:13'),
(24, 41, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 8000.00, '[{\"product_name\":\"Pastel Dragon\",\"quantity\":2,\"unit_price\":\"4000.00\"}]', '2025-11-16 16:24:43'),
(25, 42, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1500.00, '[{\"product_name\":\"Sakura Cow\",\"quantity\":1,\"unit_price\":\"1500.00\"}]', '2025-11-16 16:36:17'),
(26, 43, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1800.00, '[{\"product_name\":\"Axolotl\",\"quantity\":2,\"unit_price\":\"900.00\"}]', '2025-11-16 16:41:37'),
(27, 44, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1000.00, '[{\"product_name\":\"Capybara Family\",\"quantity\":1,\"unit_price\":\"1000.00\"}]', '2025-11-16 16:50:04'),
(28, 48, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 2500.00, '[{\"product_name\":\"Derpy\",\"quantity\":1,\"unit_price\":\"2500.00\"}]', '2025-11-16 17:40:04'),
(29, 56, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1950.00, '[{\"product_name\":\"Aurora Bear\",\"quantity\":2,\"unit_price\":\"950.00\"}]', '2025-11-16 18:24:45'),
(30, 57, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 2050.00, '[{\"product_name\":\"Lambutan\",\"quantity\":2,\"unit_price\":\"1000.00\"}]', '2025-11-16 18:39:11'),
(31, 60, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 350.00, '[{\"product_name\":\"New Product\",\"quantity\":5,\"unit_price\":\"60.00\"}]', '2025-11-17 07:26:19'),
(35, 21, '', '', 9040.00, '[{\"product_name\":\"Keychain Pals Set\",\"quantity\":10,\"unit_price\":\"899.00\"},{\"product_name\":\"Keychain Pals Set\",\"quantity\":1,\"unit_price\":\"10.00\"}]', '2025-11-17 09:59:15'),
(36, 27, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 14634.00, '[{\"product_name\":\"Keychain Pals Set\",\"quantity\":15,\"unit_price\":\"899.00\"}]', '2025-11-17 09:59:15'),
(37, 29, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 14540.00, '[{\"product_name\":\"Holiday Santa\",\"quantity\":5,\"unit_price\":\"1299.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-17 09:59:15'),
(38, 30, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 21040.00, '[{\"product_name\":\"Dragon Collectible\",\"quantity\":5,\"unit_price\":\"2599.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-17 09:59:15'),
(39, 31, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 29535.00, '[{\"product_name\":\"Unicorn Dreams\",\"quantity\":5,\"unit_price\":\"1699.00\"},{\"product_name\":\"Dragon Collectible\",\"quantity\":5,\"unit_price\":\"2599.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-17 09:59:15'),
(40, 32, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 29535.00, '[{\"product_name\":\"Unicorn Dreams\",\"quantity\":5,\"unit_price\":\"1699.00\"},{\"product_name\":\"Dragon Collectible\",\"quantity\":5,\"unit_price\":\"2599.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-17 09:59:15'),
(41, 33, '', '', 12540.00, '[{\"product_name\":\"Bunny Hop\",\"quantity\":10,\"unit_price\":\"1249.00\"}]', '2025-11-17 09:59:15'),
(42, 34, 'Julrimi Tumavera', 'lilithzie@gmail.com', 15242.00, '[{\"product_name\":\"Baby Elephant\",\"quantity\":8,\"unit_price\":\"1899.00\"}]', '2025-11-17 09:59:15'),
(43, 35, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 21040.00, '[{\"product_name\":\"Dragon Collectible\",\"quantity\":5,\"unit_price\":\"2599.00\"},{\"product_name\":\"Panda Cuddles\",\"quantity\":5,\"unit_price\":\"1599.00\"}]', '2025-11-17 09:59:15'),
(44, 36, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 2849.00, '[{\"product_name\":\"Phoenix Rising\",\"quantity\":1,\"unit_price\":\"2799.00\"}]', '2025-11-17 09:59:15'),
(45, 37, 'Julianne Tumpap', 'juliannetumpap08@gmail.com', 2849.00, '[{\"product_name\":\"Phoenix Rising\",\"quantity\":1,\"unit_price\":\"2799.00\"}]', '2025-11-17 09:59:15'),
(46, 49, '', '', 2550.00, '[{\"product_name\":\"Derpy\",\"quantity\":1,\"unit_price\":\"2500.00\"}]', '2025-11-17 09:59:15'),
(47, 59, '', '', 1050.00, '[{\"product_name\":\"Lambutan\",\"quantity\":1,\"unit_price\":\"1000.00\"}]', '2025-11-17 09:59:15'),
(48, 61, '', '', 1050.00, '[{\"product_name\":\"Lambutan\",\"quantity\":1,\"unit_price\":\"1000.00\"}]', '2025-11-17 09:59:15'),
(49, 64, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 650.00, '[{\"product_name\":\"Froggit\",\"quantity\":1,\"unit_price\":\"600.00\"}]', '2025-11-17 10:44:06'),
(50, 66, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1000.00, '[{\"product_name\":\"Bun Bun\",\"quantity\":1,\"unit_price\":\"950.00\"}]', '2025-11-17 11:06:09'),
(51, 69, 'Ron Jeremy Primavera', 'primaveraron@gmail.com', 1550.00, '[{\"product_name\":\"Sakura Cat\",\"quantity\":1,\"unit_price\":\"1500.00\"}]', '2025-11-17 12:02:18');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `has_reviewed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `unit_price`, `item_total`, `created_at`, `has_reviewed`) VALUES
(22, 21, NULL, 'Keychain Pals Set', NULL, 10, 899.00, 8990.00, '2025-11-02 15:01:30', 0),
(30, 27, NULL, 'Keychain Pals Set', NULL, 15, 899.00, 13485.00, '2025-11-02 15:27:11', 0),
(33, 21, NULL, 'Keychain Pals Set', '', 1, 10.00, 10.00, '2025-11-02 16:04:50', 0),
(34, 29, NULL, 'Holiday Santa', '', 5, 1299.00, 6495.00, '2025-11-02 16:07:52', 0),
(35, 29, NULL, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-02 16:07:52', 0),
(36, 30, NULL, 'Dragon Collectible', '', 5, 2599.00, 12995.00, '2025-11-02 16:55:12', 0),
(37, 30, NULL, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-02 16:55:12', 0),
(38, 31, NULL, 'Unicorn Dreams', '', 5, 1699.00, 8495.00, '2025-11-04 00:30:34', 0),
(39, 31, NULL, 'Dragon Collectible', 'products/product_44_1762105605.png', 5, 2599.00, 12995.00, '2025-11-04 00:30:34', 0),
(40, 31, NULL, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-04 00:30:34', 0),
(41, 32, NULL, 'Unicorn Dreams', '', 5, 1699.00, 8495.00, '2025-11-04 06:36:26', 0),
(42, 32, NULL, 'Dragon Collectible', 'products/product_44_1762105605.png', 5, 2599.00, 12995.00, '2025-11-04 06:36:26', 0),
(43, 32, NULL, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-04 06:36:26', 0),
(44, 33, NULL, 'Bunny Hop', '', 10, 1249.00, 12490.00, '2025-11-06 02:19:20', 0),
(45, 34, NULL, 'Baby Elephant', 'products/product_46_17626703413125_1786.jpg', 8, 1899.00, 15192.00, '2025-11-11 17:33:32', 0),
(46, 35, NULL, 'Dragon Collectible', 'products/product_44_1762105605.png', 5, 2599.00, 12995.00, '2025-11-11 17:35:21', 0),
(47, 35, NULL, 'Panda Cuddles', '', 5, 1599.00, 7995.00, '2025-11-11 17:35:21', 0),
(48, 36, NULL, 'Phoenix Rising', '', 1, 2799.00, 2799.00, '2025-11-11 17:38:10', 0),
(49, 37, NULL, 'Phoenix Rising', '', 1, 2799.00, 2799.00, '2025-11-11 17:57:03', 1),
(50, 38, 67, 'Axolotl', 'products/product_67_17633016555069_8996.jpg', 5, 900.00, 4500.00, '2025-11-16 14:13:03', 0),
(51, 39, 66, 'Aurora Bear', 'products/product_66_17633015445838_9454.jpg', 2, 950.00, 1900.00, '2025-11-16 14:14:45', 0),
(52, 40, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 2, 2500.00, 5000.00, '2025-11-16 16:19:25', 0),
(53, 41, 74, 'Pastel Dragon', 'products/product_74_17633030404687_8830.jpg', 2, 4000.00, 8000.00, '2025-11-16 16:23:49', 0),
(54, 42, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 16:35:48', 0),
(55, 43, 67, 'Axolotl', 'products/product_67_17633016555069_8996.jpg', 2, 900.00, 1800.00, '2025-11-16 16:41:08', 0),
(56, 44, 70, 'Capybara Family', 'products/product_70_17633024738180_6526.jpg', 1, 1000.00, 1000.00, '2025-11-16 16:49:23', 0),
(57, 45, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 2, 2500.00, 5000.00, '2025-11-16 17:02:52', 0),
(58, 46, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 1, 2500.00, 2500.00, '2025-11-16 17:05:30', 0),
(59, 47, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 1, 2500.00, 2500.00, '2025-11-16 17:08:31', 0),
(60, 48, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 1, 2500.00, 2500.00, '2025-11-16 17:11:00', 0),
(61, 49, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 1, 2500.00, 2500.00, '2025-11-16 17:16:51', 0),
(62, 50, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 17:40:16', 0),
(63, 51, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 17:46:22', 0),
(64, 52, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 17:50:24', 0),
(65, 53, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 17:53:05', 0),
(66, 54, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 17:58:28', 0),
(67, 55, 72, 'Sakura Cow', 'products/product_72_17633027830786_8633.jpg', 1, 1500.00, 1500.00, '2025-11-16 18:05:32', 0),
(68, 56, 66, 'Aurora Bear', 'products/product_66_17633015445838_9454.jpg', 2, 950.00, 1900.00, '2025-11-16 18:23:05', 0),
(69, 57, 78, 'Lambutan', 'products/product_78_17633037135542_1953.jpg', 2, 1000.00, 2000.00, '2025-11-16 18:36:17', 0),
(70, 58, 78, 'Lambutan', 'products/product_78_17633037135542_1953.jpg', 1, 1000.00, 1000.00, '2025-11-16 18:47:00', 0),
(71, 59, 78, 'Lambutan', 'products/product_78_17633037135542_1953.jpg', 1, 1000.00, 1000.00, '2025-11-16 18:49:08', 0),
(72, 60, NULL, 'New Product', 'products/product_95_17633641327523_5225.png', 5, 60.00, 300.00, '2025-11-17 07:23:38', 0),
(73, 61, 78, 'Lambutan', 'products/product_78_17633037135542_1953.jpg', 1, 1000.00, 1000.00, '2025-11-17 09:04:46', 0),
(74, 62, 78, 'Lambutan', 'products/product_78_17633037135542_1953.jpg', 1, 1000.00, 1000.00, '2025-11-17 10:02:32', 0),
(75, 62, 79, 'Panda Coco', 'products/product_79_17633044345829_5036.jpg', 1, 800.00, 800.00, '2025-11-17 10:02:32', 0),
(76, 63, 79, 'Panda Coco', 'products/product_79_17633044345829_5036.jpg', 1, 800.00, 800.00, '2025-11-17 10:16:01', 0),
(77, 64, 76, 'Froggit', 'products/product_76_17633034398940_7798.jpg', 1, 600.00, 600.00, '2025-11-17 10:38:56', 1),
(78, 65, 75, 'Fox Tales', 'products/product_75_17633032942877_9031.jpg', 1, 5000.00, 5000.00, '2025-11-17 11:03:06', 0),
(79, 66, 69, 'Bun Bun', 'products/product_69_17633019829208_5204.jpg', 1, 950.00, 950.00, '2025-11-17 11:04:49', 1),
(80, 67, 73, 'Derpy', 'products/product_73_17633029085546_5869.jpg', 1, 2500.00, 2500.00, '2025-11-17 11:07:46', 0),
(81, 68, 75, 'Fox Tales', 'products/product_75_17633032942877_9031.jpg', 1, 5000.00, 5000.00, '2025-11-17 11:50:58', 0),
(82, 69, 71, 'Sakura Cat', 'products/product_71_17633026998882_4139.jpg', 1, 1500.00, 1500.00, '2025-11-17 12:01:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `review_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `supplier_id`, `product_name`, `description`, `cost_price`, `selling_price`, `img_path`, `is_active`, `created_at`, `updated_at`, `average_rating`, `review_count`) VALUES
(66, 26, 17, 'Aurora Bear', 'Soft premium teddy bear; perfect for gifting.', 650.00, 950.00, 'products/product_66_17633015445838_9454.jpg', 1, '2025-11-16 13:59:04', '2025-11-16 18:26:16', 5.00, 7),
(67, 26, 16, 'Axolotl', 'Cute pastel pink axolotl plush with fluffy gills.', 600.00, 900.00, 'products/product_67_17633016555069_8996.jpg', 1, '2025-11-16 14:00:55', '2025-11-16 14:13:46', 5.00, 1),
(68, 26, 15, 'Pigeon and Dovey', 'Birds of a feather flock together for real', 1400.00, 1800.00, 'products/product_68_17633018892706_7182.jpg', 1, '2025-11-16 14:04:49', '2025-11-16 14:04:49', 0.00, 0),
(69, 26, 17, 'Bun Bun', 'Cute and cuddly bunny plush with long floppy ears.', 600.00, 950.00, 'products/product_69_17633019829208_5204.jpg', 1, '2025-11-16 14:06:22', '2025-11-17 11:06:30', 4.00, 1),
(70, 26, 15, 'Capybara Family', 'Soft and chubby Capybara plushies inspired by the popular “Capybara Chill” trend.', 700.00, 1000.00, 'products/product_70_17633024738180_6526.jpg', 1, '2025-11-16 14:14:33', '2025-11-16 16:50:52', 5.00, 1),
(71, 26, 15, 'Sakura Cat', 'Soft kitty plush with cute embroidered whiskers.', 1200.00, 1500.00, 'products/product_71_17633026998882_4139.jpg', 1, '2025-11-16 14:18:19', '2025-11-16 14:18:19', 0.00, 0),
(72, 26, 15, 'Sakura Cow', 'Soft cow plush with tiny horns and a friendly smile, friends with Sakura Cat', 1200.00, 1500.00, 'products/product_72_17633027830786_8633.jpg', 1, '2025-11-16 14:19:43', '2025-11-16 14:19:43', 0.00, 0),
(73, 26, 16, 'Derpy', 'A funny, silly-faced plush tiger inspired by K-Pop Demon Hunters\' Derpy.', 2000.00, 2500.00, 'products/product_73_17633029085546_5869.jpg', 1, '2025-11-16 14:21:48', '2025-11-16 14:21:48', 0.00, 0),
(74, 26, 16, 'Pastel Dragon', 'Cute chibi-style dragon with soft wings and pastel rainbow colors.', 3500.00, 4000.00, 'products/product_74_17633030404687_8830.jpg', 1, '2025-11-16 14:24:00', '2025-11-16 14:50:05', 0.00, 0),
(75, 26, 16, 'Fox Tales', 'Redi and Bluey, the cute nine-tailed fox lovers plushies.', 4650.00, 5000.00, 'products/product_75_17633032942877_9031.jpg', 1, '2025-11-16 14:28:14', '2025-11-16 14:28:14', 0.00, 0),
(76, 26, 15, 'Froggit', 'Round frog plush with big eyes and a cute smile.', 400.00, 600.00, 'products/product_76_17633034398940_7798.jpg', 1, '2025-11-16 14:30:39', '2025-11-17 10:48:51', 5.00, 1),
(77, 26, 16, 'Hedgie Huggies', 'A family of huggable hedgehog plushies with round body and tiny paws.', 3000.00, 3250.00, 'products/product_77_17633035383665_5896.jpeg', 1, '2025-11-16 14:32:18', '2025-11-16 14:32:18', 0.00, 0),
(78, 26, 17, 'Lambutan', 'Soft and cuddly lamb plush with curled wool texture and horns. (Buying grants a random lamb plush.)', 800.00, 1000.00, 'products/product_78_17633037135542_1953.jpg', 1, '2025-11-16 14:35:13', '2025-11-17 09:00:00', 5.00, 1),
(79, 26, 17, 'Panda Coco', 'Classic black-and-white panda plush with soft fur. (Buying may grant the special edition red panda.)', 500.00, 800.00, 'products/product_79_17633044345829_5036.jpg', 1, '2025-11-16 14:47:14', '2025-11-17 07:34:10', 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`, `created_at`) VALUES
(2, 67, 26, '2025-11-16 15:06:30'),
(4, 69, 26, '2025-11-16 15:06:30'),
(6, 71, 26, '2025-11-16 15:06:30'),
(7, 72, 26, '2025-11-16 15:06:30'),
(30, 76, 38, '2025-11-16 15:50:51'),
(31, 76, 26, '2025-11-16 15:50:51'),
(32, 75, 37, '2025-11-16 15:51:10'),
(33, 75, 26, '2025-11-16 15:51:10'),
(34, 75, 36, '2025-11-16 15:51:10'),
(35, 68, 38, '2025-11-16 15:53:03'),
(36, 68, 37, '2025-11-16 15:53:03'),
(37, 73, 26, '2025-11-16 15:53:19'),
(38, 73, 36, '2025-11-16 15:53:19'),
(41, 74, 26, '2025-11-16 15:54:20'),
(42, 74, 36, '2025-11-16 15:54:20'),
(43, 66, 26, '2025-11-16 15:54:35'),
(44, 77, 37, '2025-11-16 15:54:52'),
(45, 77, 26, '2025-11-16 15:54:52'),
(66, 79, 26, '2025-11-17 07:36:27'),
(67, 79, 38, '2025-11-17 07:36:27'),
(86, 70, 26, '2025-11-17 07:54:02'),
(87, 70, 37, '2025-11-17 07:54:02'),
(88, 70, 38, '2025-11-17 07:54:02'),
(143, 78, 26, '2025-11-17 09:00:02'),
(144, 78, 38, '2025-11-17 09:00:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `display_order`, `is_primary`, `created_at`) VALUES
(30, 66, 'products/product_66_17633015445838_9454.jpg', 0, 1, '2025-11-16 13:59:04'),
(31, 66, 'products/product_66_17633015445945_2701.jpg', 1, 0, '2025-11-16 13:59:04'),
(32, 67, 'products/product_67_17633016555069_8996.jpg', 0, 1, '2025-11-16 14:00:55'),
(33, 67, 'products/product_67_17633016815902_5846.jpg', 1, 0, '2025-11-16 14:01:21'),
(34, 67, 'products/product_67_17633016815963_6593.png', 2, 0, '2025-11-16 14:01:21'),
(35, 68, 'products/product_68_17633018892706_7182.jpg', 0, 1, '2025-11-16 14:04:49'),
(36, 68, 'products/product_68_17633018892791_2683.jpg', 1, 0, '2025-11-16 14:04:49'),
(37, 68, 'products/product_68_17633018892863_9073.jpg', 2, 0, '2025-11-16 14:04:49'),
(38, 68, 'products/product_68_17633018892935_9851.jpg', 3, 0, '2025-11-16 14:04:49'),
(39, 69, 'products/product_69_17633019829208_5204.jpg', 0, 1, '2025-11-16 14:06:22'),
(40, 69, 'products/product_69_17633019829291_6191.jpg', 1, 0, '2025-11-16 14:06:22'),
(41, 69, 'products/product_69_17633019829357_5741.jpg', 2, 0, '2025-11-16 14:06:22'),
(42, 69, 'products/product_69_17633019829426_1894.jpg', 3, 0, '2025-11-16 14:06:22'),
(43, 70, 'products/product_70_17633024738180_6526.jpg', 0, 1, '2025-11-16 14:14:33'),
(44, 70, 'products/product_70_17633025095444_9448.jpg', 1, 0, '2025-11-16 14:15:09'),
(45, 70, 'products/product_70_17633025222862_8947.jpg', 2, 0, '2025-11-16 14:15:22'),
(46, 71, 'products/product_71_17633026998882_4139.jpg', 0, 1, '2025-11-16 14:18:19'),
(47, 71, 'products/product_71_17633026998931_8724.jpg', 1, 0, '2025-11-16 14:18:19'),
(48, 72, 'products/product_72_17633027830786_8633.jpg', 0, 1, '2025-11-16 14:19:43'),
(49, 72, 'products/product_72_17633027830875_2235.jpg', 1, 0, '2025-11-16 14:19:43'),
(50, 72, 'products/product_72_17633027830932_3040.jpg', 2, 0, '2025-11-16 14:19:43'),
(51, 73, 'products/product_73_17633029085546_5869.jpg', 0, 1, '2025-11-16 14:21:48'),
(52, 73, 'products/product_73_17633029085613_4296.jpg', 1, 0, '2025-11-16 14:21:48'),
(53, 74, 'products/product_74_17633030404546_4940.jpg', 0, 0, '2025-11-16 14:24:00'),
(54, 74, 'products/product_74_17633030404597_9032.jpg', 1, 0, '2025-11-16 14:24:00'),
(55, 74, 'products/product_74_17633030404636_1477.jpg', 2, 0, '2025-11-16 14:24:00'),
(56, 74, 'products/product_74_17633030404687_8830.jpg', 3, 1, '2025-11-16 14:24:00'),
(57, 75, 'products/product_75_17633032942877_9031.jpg', 0, 1, '2025-11-16 14:28:14'),
(58, 75, 'products/product_75_17633032942934_6924.jpg', 1, 0, '2025-11-16 14:28:14'),
(59, 75, 'products/product_75_17633032942983_6126.jpg', 2, 0, '2025-11-16 14:28:14'),
(60, 75, 'products/product_75_17633032943026_7828.jpg', 3, 0, '2025-11-16 14:28:14'),
(61, 76, 'products/product_76_17633034398940_7798.jpg', 0, 1, '2025-11-16 14:30:39'),
(62, 76, 'products/product_76_17633034399003_1191.jpg', 1, 0, '2025-11-16 14:30:39'),
(63, 77, 'products/product_77_17633035383665_5896.jpeg', 0, 1, '2025-11-16 14:32:18'),
(64, 77, 'products/product_77_17633035383713_2618.jpg', 1, 0, '2025-11-16 14:32:18'),
(65, 77, 'products/product_77_17633035383751_9609.jpg', 2, 0, '2025-11-16 14:32:18'),
(66, 77, 'products/product_77_17633035383793_9809.jpg', 3, 0, '2025-11-16 14:32:18'),
(67, 78, 'products/product_78_17633037135542_1953.jpg', 0, 1, '2025-11-16 14:35:13'),
(68, 78, 'products/product_78_17633037135597_6358.jpg', 1, 0, '2025-11-16 14:35:13'),
(69, 79, 'products/product_79_17633044345829_5036.jpg', 0, 1, '2025-11-16 14:47:14');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_reply` text DEFAULT NULL,
  `admin_reply_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`, `admin_reply`, `admin_reply_at`) VALUES
(24, 67, 11, 5, 'ang ganda', '2025-11-16 14:13:46', '2025-11-19 09:47:17', 'puta', '2025-11-19 01:47:17'),
(25, 66, 11, 5, 'cute b***t hehehe', '2025-11-16 14:15:49', '2025-11-16 22:52:17', 'kalbonara', '2025-11-16 14:52:17'),
(35, 76, 18, 5, '', '2025-11-17 10:48:51', '2025-11-17 18:48:51', NULL, NULL),
(36, 69, 18, 4, '', '2025-11-17 11:06:21', '2025-11-17 19:06:30', NULL, NULL);

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
(8, 11, '2025-11-02 15:12:05', '2025-11-02 15:12:05'),
(9, 14, '2025-11-11 17:31:31', '2025-11-11 17:31:31'),
(13, 18, '2025-11-17 10:15:55', '2025-11-17 10:15:55');

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
(15, 'GTG Stuffed Toys', 'Gloria Nina Fercol', '+63 917 556 8240', 'info@gtgstuffy.com', '26 Acacia St., Cembo, Makati City, Metro Manila', '2025-11-16 13:51:34', '2025-11-16 13:51:34'),
(16, 'Plush-A-Toy', 'Ana Ysabelle Ramos', '+63 939 937 0491', 'support@plushatoy.com', 'Unit 8, S&R Complex, MacArthur Highway, San Fernando, Pampanga', '2025-11-16 13:52:48', '2025-11-16 13:52:48'),
(17, 'Snuggle Teddy PH', 'Joshua R. Manlapaz', '+63 995 441 2309', 'hello@snuggleteddyph.com', '12-B Banawe St., Brgy. Lourdes, Quezon City, Metro Manila', '2025-11-16 13:54:17', '2025-11-16 13:54:17');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `phone`, `address`, `city`, `postal_code`, `country`, `profile_picture`, `password_hash`, `role`, `created_at`, `is_active`) VALUES
(1, 'admin@shop.com', 'ADMIN', 'PRIMAVERA', '', 'NORTH DAANGHARI', 'TAGUIG CITY', '1632', 'Philippines', 'user_1_17633571403720_2818.png', '$2y$12$jimZJ6U8/A9zi6tvIzA4BeYEHR.2s7kaJi6oisRcgMLIzjXSzr9SS', 'admin', '2025-10-27 15:03:27', 1),
(11, 'juliannetumpap08@gmail.com', 'Julianne', 'Tumpap', '09771910453', 'Culdesac, Sun Valley', 'Parañaque City', '0', 'Philippines', 'user_11_1762578715.jpg', '$2y$12$TVJZwOX/jmyk3iF8E8hoLe19mSiQUHQwug7WS2nsGAtuV5sjvhzSO', 'customer', '2025-11-02 10:26:54', 1),
(14, 'lilithzie@gmail.com', 'Julrimi', 'Tumavera', '09143143143', '2292 I Love Jirimi, Baby Street', 'Paprima', '8888', 'Philippines', 'user_14_17628823928293_5354.jpg', '$2y$12$GbvZSyuDAiANRVtNdQejhenAemaZDBxYwWKK.TILwtaPNhTBYj2Mq', 'admin', '2025-11-11 17:30:58', 1),
(17, 'akotojem@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Philippines', NULL, '$2y$12$nwMr7I2/mnWeKm.0Ro41BezI3uSlky2yOcZLeYTgOcbXO/WJdcGO6', 'customer', '2025-11-17 09:40:59', 1),
(18, 'primaveraron@gmail.com', 'Ron Jeremy', 'Primavera', '09943327537', 'Road 8, North Daanghari, Taguig City', 'TAGUIG CITY', '1632', 'Philippines', NULL, '$2y$12$XZGzL3srb2ZVw8rWarY8.uUgtQIhRdt0/LrUkXSmZqIIKBQIWZi9u', 'customer', '2025-11-17 10:15:25', 1);

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
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_category` (`product_id`,`category_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD CONSTRAINT `shopping_carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
