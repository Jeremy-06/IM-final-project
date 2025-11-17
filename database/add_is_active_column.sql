-- Add is_active column to users table
ALTER TABLE users ADD is_active TINYINT(1) DEFAULT 1 NOT NULL;

-- Update existing users to be active
UPDATE users SET is_active = 1 WHERE is_active IS NULL;