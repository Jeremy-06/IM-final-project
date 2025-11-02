-- Add supplier_id column to expenses table if it doesn't exist
ALTER TABLE expenses ADD COLUMN supplier_id INT DEFAULT NULL AFTER notes;
ALTER TABLE expenses ADD CONSTRAINT expenses_ibfk_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL;
