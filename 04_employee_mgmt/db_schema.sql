-- Database: db_employee
CREATE DATABASE IF NOT EXISTS db_employee;
USE db_employee;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: designations
CREATE TABLE IF NOT EXISTS designations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designation_name VARCHAR(100) NOT NULL UNIQUE
);

-- Seed Designations
INSERT INTO designations (designation_name) VALUES 
('Manager'), 
('Developer'), 
('HR'), 
('Accountant'),
('Team Lead'),
('Intern')
ON DUPLICATE KEY UPDATE designation_name=designation_name;

-- Table: employees
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    designation_id INT NOT NULL,
    email VARCHAR(100),
    salary DECIMAL(10, 2) NOT NULL, -- For financial summary
    join_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (designation_id) REFERENCES designations(id) ON DELETE CASCADE
);
