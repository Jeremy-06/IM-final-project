-- Migration: Update orders table to use SET NULL for customer_id (safe deletion)
-- and create order_history table for backup of completed orders

-- First, update existing orders table foreign key
ALTER TABLE orders MODIFY customer_id INT;
ALTER TABLE orders DROP FOREIGN KEY orders_ibfk_1;
ALTER TABLE orders ADD FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL;

-- Update order_items table to allow product_id to be NULL
ALTER TABLE order_items MODIFY product_id INT;
ALTER TABLE order_items DROP FOREIGN KEY order_items_ibfk_2;
ALTER TABLE order_items ADD FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL;

-- Create order_history table if it doesn't exist
CREATE TABLE IF NOT EXISTS order_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL UNIQUE,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    total_amount DECIMAL(10, 2) NOT NULL,
    items JSON NOT NULL,
    order_status VARCHAR(50) DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_archived_at (archived_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrate completed orders to history (if any exist)
INSERT INTO order_history (order_id, customer_name, customer_email, total_amount, items, order_status, created_at)
SELECT 
    o.id,
    CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')),
    u.email,
    o.total_amount,
    JSON_ARRAYAGG(JSON_OBJECT('product_name', oi.product_name, 'quantity', oi.quantity, 'unit_price', oi.unit_price)),
    'completed',
    o.created_at
FROM orders o
LEFT JOIN users u ON o.customer_id = u.id
LEFT JOIN order_items oi ON o.id = oi.order_id
WHERE o.order_status = 'completed'
GROUP BY o.id
ON DUPLICATE KEY UPDATE archived_at = NOW();
