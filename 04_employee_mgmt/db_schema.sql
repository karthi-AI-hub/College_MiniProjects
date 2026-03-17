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

-- Seed Employees
INSERT INTO employees (emp_id, name, designation_id, email, salary, join_date) VALUES
('EMP-1001', 'Anita Rao', (SELECT id FROM designations WHERE designation_name = 'Manager'), 'anita.rao@example.com', 85000.00, '2024-06-15'),
('EMP-1002', 'Ravi Kumar', (SELECT id FROM designations WHERE designation_name = 'Developer'), 'ravi.kumar@example.com', 62000.00, '2025-01-10'),
('EMP-1003', 'Neha Singh', (SELECT id FROM designations WHERE designation_name = 'HR'), 'neha.singh@example.com', 48000.00, '2023-11-01'),
('EMP-1004', 'Vikram Iyer', (SELECT id FROM designations WHERE designation_name = 'Accountant'), 'vikram.iyer@example.com', 52000.00, '2022-09-20'),
('EMP-1005', 'Sara Ali', (SELECT id FROM designations WHERE designation_name = 'Team Lead'), 'sara.ali@example.com', 70000.00, '2025-07-05'),
('EMP-1006', 'Arjun Bose', (SELECT id FROM designations WHERE designation_name = 'Intern'), 'arjun.bose@example.com', 18000.00, '2026-01-12'),
('EMP-1007', 'Maya Joseph', (SELECT id FROM designations WHERE designation_name = 'Developer'), 'maya.joseph@example.com', 64000.00, '2024-10-09'),
('EMP-1008', 'Kabir Sen', (SELECT id FROM designations WHERE designation_name = 'Team Lead'), 'kabir.sen@example.com', 72000.00, '2023-04-18')
ON DUPLICATE KEY UPDATE name=VALUES(name), designation_id=VALUES(designation_id), email=VALUES(email), salary=VALUES(salary), join_date=VALUES(join_date);
