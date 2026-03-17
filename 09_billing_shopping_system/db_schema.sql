-- Database: db_billing
CREATE DATABASE IF NOT EXISTS db_billing;
USE db_billing;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
-- In a real app, use password_hash()
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Products
INSERT INTO products (product_name, category, price, stock_quantity) VALUES 
('Organic Milk 1L', 'Dairy', 50.00, 45),
('Whole Wheat Bread', 'Bakery', 35.00, 12),
('Dark Chocolate 100g', 'Snacks', 120.00, 8),
('Greek Yogurt', 'Dairy', 85.00, 25),
('Almonds 500g', 'Dry Fruits', 450.00, 15),
('Green Tea Bags', 'Beverages', 210.00, 5)
ON DUPLICATE KEY UPDATE product_name=product_name;

-- Table: bills
CREATE TABLE IF NOT EXISTS bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    bill_after_stock_update JSON, -- Optional: store bill items for audit
    bill_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
