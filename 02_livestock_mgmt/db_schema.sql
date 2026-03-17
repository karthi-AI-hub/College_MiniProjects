-- Database: db_livestock
CREATE DATABASE IF NOT EXISTS db_livestock;
USE db_livestock;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
-- Insert hashed password (admin123)
-- $2y$10$ThpG3q.X.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w (fake)
-- We'll use plain text 'admin123' for simplicity as requested, but the login.php will support hash too.
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- Seed Categories
INSERT INTO categories (category_name) VALUES 
('Cattle'), 
('Poultry'), 
('Sheep'), 
('Goat'), 
('Pig'), 
('Horse')
ON DUPLICATE KEY UPDATE category_name=category_name;

-- Table: livestock
CREATE TABLE IF NOT EXISTS livestock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_id VARCHAR(50) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    breed VARCHAR(100),
    age VARCHAR(20), -- e.g. "2 years", "5 months"
    weight FLOAT, -- in kg
    health_status ENUM('Healthy', 'Sick', 'Under Observation') DEFAULT 'Healthy',
    last_checkup_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Seed Livestock
INSERT INTO livestock (tag_id, category_id, breed, age, weight, health_status, last_checkup_date) VALUES
('CAT-001', (SELECT id FROM categories WHERE category_name = 'Cattle'), 'Holstein Friesian', '2 years', 420.5, 'Healthy', '2026-01-18'),
('CAT-002', (SELECT id FROM categories WHERE category_name = 'Cattle'), 'Jersey', '3 years', 390.0, 'Under Observation', '2026-01-28'),
('POUL-101', (SELECT id FROM categories WHERE category_name = 'Poultry'), 'Rhode Island Red', '8 months', 2.1, 'Healthy', '2026-02-01'),
('SHP-050', (SELECT id FROM categories WHERE category_name = 'Sheep'), 'Merino', '1.5 years', 55.8, 'Sick', '2026-02-05'),
('GOT-014', (SELECT id FROM categories WHERE category_name = 'Goat'), 'Boer', '2 years', 68.0, 'Healthy', '2026-01-22'),
('PIG-007', (SELECT id FROM categories WHERE category_name = 'Pig'), 'Large White', '1 year', 95.3, 'Under Observation', '2026-01-30'),
('HRS-021', (SELECT id FROM categories WHERE category_name = 'Horse'), 'Arabian', '4 years', 430.0, 'Healthy', '2026-02-02')
ON DUPLICATE KEY UPDATE breed=VALUES(breed), age=VALUES(age), weight=VALUES(weight), health_status=VALUES(health_status), last_checkup_date=VALUES(last_checkup_date);
