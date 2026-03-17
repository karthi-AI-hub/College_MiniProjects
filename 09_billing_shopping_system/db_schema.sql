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

-- Seed Bills
INSERT INTO bills (id, customer_name, total_amount, bill_after_stock_update, bill_date) VALUES
(1, 'Riya Sharma', 520.00, '[{"item":"Organic Milk 1L","qty":2,"price":50.00},{"item":"Greek Yogurt","qty":3,"price":85.00}]', '2026-02-09 10:15:00'),
(2, 'Kunal Verma', 305.00, '[{"item":"Whole Wheat Bread","qty":3,"price":35.00},{"item":"Green Tea Bags","qty":1,"price":210.00}]', '2026-02-10 16:40:00'),
(3, 'Meera Singh', 570.00, '[{"item":"Almonds 500g","qty":1,"price":450.00},{"item":"Dark Chocolate 100g","qty":1,"price":120.00}]', '2026-02-11 12:05:00'),
(4, 'Ishaan Patel', 260.00, '[{"item":"Organic Milk 1L","qty":2,"price":50.00},{"item":"Whole Wheat Bread","qty":2,"price":35.00},{"item":"Green Tea Bags","qty":1,"price":210.00}]', '2026-02-11 18:20:00'),
(5, 'Zara Ali', 155.00, '[{"item":"Greek Yogurt","qty":1,"price":85.00},{"item":"Dark Chocolate 100g","qty":1,"price":120.00}]', '2026-02-08 09:45:00')
ON DUPLICATE KEY UPDATE customer_name=VALUES(customer_name), total_amount=VALUES(total_amount), bill_after_stock_update=VALUES(bill_after_stock_update), bill_date=VALUES(bill_date);
