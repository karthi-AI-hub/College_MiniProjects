-- Database: db_bloodbank
CREATE DATABASE IF NOT EXISTS db_bloodbank;
USE db_bloodbank;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: donors
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    blood_group ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    age INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    city VARCHAR(100) NOT NULL,
    last_donation_date DATE DEFAULT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Donors
INSERT INTO donors (name, blood_group, gender, age, phone, email, city, last_donation_date) VALUES 
('John Doe', 'O+', 'Male', 28, '9876543210', 'john@example.com', 'London', '2025-11-20'),
('Jane Smith', 'AB-', 'Female', 24, '8765432109', 'jane@example.com', 'Manchester', '2025-12-15'),
('Robert Wilson', 'A+', 'Male', 35, '7654321098', 'robert@example.com', 'Birmingham', NULL),
('Alice Green', 'B-', 'Female', 30, '6543210987', 'alice@example.com', 'London', '2026-01-10'),
('David Brown', 'O-', 'Male', 42, '5432109876', 'david@example.com', 'Liverpool', '2025-10-05')
ON DUPLICATE KEY UPDATE name=name;

-- Additional Mock Donors
INSERT INTO donors (id, name, blood_group, gender, age, phone, email, city, last_donation_date) VALUES
(6, 'Nina Kapoor', 'A-', 'Female', 27, '9123456780', 'nina.kapoor@example.com', 'Leeds', '2026-01-28'),
(7, 'Omar Khan', 'B+', 'Male', 31, '9234567801', 'omar.khan@example.com', 'London', NULL),
(8, 'Sofia Perez', 'AB+', 'Female', 29, '9345678012', 'sofia.perez@example.com', 'Bristol', '2025-12-02'),
(9, 'Ethan Mills', 'O+', 'Male', 33, '9456780123', 'ethan.mills@example.com', 'Sheffield', '2026-01-02'),
(10, 'Hanna Lee', 'B-', 'Female', 26, '9567801234', 'hanna.lee@example.com', 'London', NULL)
ON DUPLICATE KEY UPDATE name=VALUES(name), blood_group=VALUES(blood_group), gender=VALUES(gender), age=VALUES(age), phone=VALUES(phone), email=VALUES(email), city=VALUES(city), last_donation_date=VALUES(last_donation_date);
