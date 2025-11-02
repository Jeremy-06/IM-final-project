-- Add Suppliers Table
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(255) NOT NULL UNIQUE,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_supplier_name (supplier_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add supplier_id to products table
ALTER TABLE products ADD COLUMN supplier_id INT DEFAULT NULL AFTER category_id;
ALTER TABLE products ADD CONSTRAINT products_ibfk_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL;
ALTER TABLE products ADD INDEX idx_supplier (supplier_id);

-- Insert sample suppliers
INSERT INTO suppliers (supplier_name, contact_person, phone, email, address) VALUES
('Plushie Wholesale Co.', 'John Smith', '555-0001', 'john@plushiewholesale.com', '123 Wholesale Ave, Manila'),
('Craft Supplies Inc.', 'Maria Garcia', '555-0002', 'maria@craftsupplies.com', '456 Supply Street, Makati'),
('Import Hub', 'Chen Wei', '555-0003', 'chen@importhub.com', '789 Port Road, Port Area');
