-- Migration Script: Add Pharmacy Table and Secure Passwords
-- Run this SQL script to apply the security updates

USE smart_op;

-- Create Pharmacy Table (if not exists)
CREATE TABLE IF NOT EXISTS pharmacy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Pharmacy User
-- Username: pharmacy, Password: pharmacy123
INSERT INTO pharmacy (name, username, password) 
VALUES ('Pharmacy Staff', 'pharmacy', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE id=id;

-- Verify the table was created
SELECT 'Pharmacy table created successfully' AS status;
SELECT * FROM pharmacy;
