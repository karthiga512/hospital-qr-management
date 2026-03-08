-- Smart QR Based OP & Prescription Management System
-- Database Schema

-- Create Database
CREATE DATABASE IF NOT EXISTS smart_op;
USE smart_op;

-- Patients Table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    qr_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_visit TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctors Table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Visits Table
CREATE TABLE IF NOT EXISTS visits (
    visit_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    problem TEXT NOT NULL,
    diagnosis TEXT NOT NULL,
    prescription TEXT NOT NULL,
    treatment TEXT,
    medicine_status ENUM('pending', 'given') DEFAULT 'pending',
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin Table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pharmacy Table
CREATE TABLE IF NOT EXISTS pharmacy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin
-- Username: admin, Password: admin123 (Plain Text)
INSERT INTO admin (username, password) VALUES 
('admin', 'admin123');

-- Insert Default Pharmacy User
-- Username: pharmacy, Password: pharmacy123 (Plain Text)
INSERT INTO pharmacy (name, username, password) VALUES 
('Pharmacy Staff', 'pharmacy', 'pharmacy123');

-- Insert Default Doctor
-- Username: doctor1, Password: doctor123 (Plain Text)
INSERT INTO doctors (name, username, password) VALUES 
('Dr. John Smith', 'doctor1', 'doctor123');

-- Create indexes for better performance
CREATE INDEX idx_patient_id ON visits(patient_id);
CREATE INDEX idx_doctor_id ON visits(doctor_id);
CREATE INDEX idx_visit_date ON visits(visit_date);
