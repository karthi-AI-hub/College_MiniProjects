-- Database: db_college
CREATE DATABASE IF NOT EXISTS db_college;
USE db_college;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
-- Hash for 'admin123' generated via password_hash()
INSERT INTO admin (username, password) VALUES ('admin', 'admin123'); 

-- Table: departments
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Departments
INSERT INTO departments (id, dept_name) VALUES
(1, 'Computer Science'),
(2, 'Business Administration'),
(3, 'Mechanical Engineering'),
(4, 'Electrical Engineering'),
(5, 'Mathematics'),
(6, 'Physics'),
(7, 'Civil Engineering')
ON DUPLICATE KEY UPDATE dept_name=VALUES(dept_name);

-- Table: students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    dept_id INT NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- Seed Students
INSERT INTO students (id, name, roll_no, dept_id, email, phone, gender) VALUES
(1, 'Aarav Mehta', 'CSE-001', 1, 'aarav.mehta@example.com', '9876501234', 'Male'),
(2, 'Priya Nair', 'CSE-002', 1, 'priya.nair@example.com', '9876502345', 'Female'),
(3, 'Kiran Das', 'BUS-101', 2, 'kiran.das@example.com', '9876503456', 'Male'),
(4, 'Meera Shah', 'ME-210', 3, 'meera.shah@example.com', '9876504567', 'Female'),
(5, 'Rohan Kulkarni', 'EE-305', 4, 'rohan.kulkarni@example.com', '9876505678', 'Male'),
(6, 'Nisha Patel', 'MAT-115', 5, 'nisha.patel@example.com', '9876506789', 'Female'),
(7, 'Arjun Varma', 'PHY-121', 6, 'arjun.varma@example.com', '9876507890', 'Male'),
(8, 'Lina Roy', 'CIV-314', 7, 'lina.roy@example.com', '9876508901', 'Female'),
(9, 'Sameer Khan', 'CSE-010', 1, 'sameer.khan@example.com', '9876509012', 'Male')
ON DUPLICATE KEY UPDATE name=VALUES(name), dept_id=VALUES(dept_id), email=VALUES(email), phone=VALUES(phone), gender=VALUES(gender);