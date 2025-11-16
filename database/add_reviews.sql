
-- Add reviews table
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add average_rating and review_count to products table
ALTER TABLE `products`
ADD COLUMN `average_rating` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
ADD COLUMN `review_count` INT(11) NOT NULL DEFAULT 0;

-- Add has_reviewed to order_items table
ALTER TABLE `order_items`
ADD COLUMN `has_reviewed` BOOLEAN NOT NULL DEFAULT FALSE;
