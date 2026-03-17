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
