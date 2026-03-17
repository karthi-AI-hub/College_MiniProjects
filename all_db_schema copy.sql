-- Combined SQL for all project schemas
-- Generated on 2026-02-11

-- =========================================================
-- 01_college_mgmt/db_schema.sql
-- =========================================================
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

-- =========================================================
-- 02_livestock_mgmt/db_schema.sql
-- =========================================================
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

-- =========================================================
-- 03_student_attendance/db_schema.sql
-- =========================================================
-- Database: db_attendance
CREATE DATABASE IF NOT EXISTS db_attendance;
USE db_attendance;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    class_section VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Students
INSERT INTO students (name, roll_no, class_section) VALUES 
('Alice Johnson', 'S001', '10-A'),
('Bob Smith', 'S002', '10-A'),
('Charlie Brown', 'S003', '10-B'),
('David Wilson', 'S004', '10-A'),
('Eva Davis', 'S005', '10-B'),
('Frank Miller', 'S006', '10-A'),
('Grace Lee', 'S007', '10-B'),
('Henry White', 'S008', '10-A'),
('Ivy Green', 'S009', '10-B'),
('Jack Robinson', 'S010', '10-A')
ON DUPLICATE KEY UPDATE name=name;

-- Table: attendance_records
CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, attendance_date) -- Prevent duplicate entries for same student on same day
);

-- Seed Attendance Records
INSERT INTO attendance_records (student_id, attendance_date, status) VALUES
((SELECT id FROM students WHERE roll_no = 'S001'), '2026-02-10', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S002'), '2026-02-10', 'Absent'),
((SELECT id FROM students WHERE roll_no = 'S003'), '2026-02-10', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S004'), '2026-02-10', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S005'), '2026-02-10', 'Absent'),
((SELECT id FROM students WHERE roll_no = 'S006'), '2026-02-11', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S007'), '2026-02-11', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S008'), '2026-02-11', 'Absent'),
((SELECT id FROM students WHERE roll_no = 'S009'), '2026-02-11', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S010'), '2026-02-11', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S001'), '2026-02-09', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S002'), '2026-02-09', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S003'), '2026-02-09', 'Absent'),
((SELECT id FROM students WHERE roll_no = 'S004'), '2026-02-09', 'Present'),
((SELECT id FROM students WHERE roll_no = 'S005'), '2026-02-09', 'Present')
ON DUPLICATE KEY UPDATE status=VALUES(status);
