-- Quick Update Script: Convert Hashed Passwords to Plain Text
-- Run this if you already have the pharmacy table with hashed passwords

USE smart_op;

-- Update admin password to plain text
UPDATE admin SET password = 'admin123' WHERE username = 'admin';

-- Update doctor password to plain text
UPDATE doctors SET password = 'doctor123' WHERE username = 'doctor1';

-- Update pharmacy password to plain text (if exists)
UPDATE pharmacy SET password = 'pharmacy123' WHERE username = 'pharmacy';

-- Verify updates
SELECT 'Passwords updated to plain text' AS status;
SELECT username, password FROM admin;
SELECT username, password FROM doctors;
SELECT username, password FROM pharmacy;
