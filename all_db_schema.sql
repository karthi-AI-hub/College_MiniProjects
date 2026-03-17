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

-- =========================================================
-- 04_employee_mgmt/db_schema.sql
-- =========================================================
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

-- =========================================================
-- 05_event_mgmt/db_schema.sql
-- =========================================================
-- Database: db_event
CREATE DATABASE IF NOT EXISTS db_event;
USE db_event;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: event_types
CREATE TABLE IF NOT EXISTS event_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL UNIQUE
);

-- Seed Event Types
INSERT INTO event_types (type_name) VALUES 
('Seminar'), 
('Workshop'), 
('Cultural'), 
('Sports'),
('Conference'),
('Webinar')
ON DUPLICATE KEY UPDATE type_name=type_name;

-- Table: events
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    type_id INT NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(200) NOT NULL,
    organizer VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES event_types(id) ON DELETE CASCADE
);

-- Seed some sample events (Upcoming and Completed)
INSERT INTO events (title, type_id, event_date, venue, organizer, description) VALUES
('Tech Symposium 2026', 1, '2026-03-15', 'Main Auditorium', 'IT Department', 'A grand seminar on emerging technologies.'),
('Yoga Workshop', 2, '2026-02-20', 'College Gym', 'Student Council', 'Hands-on training session for beginners.'),
('Annual Fest', 3, '2025-12-10', 'Open Grounds', 'Cultural Club', 'The biggest celebration of the year (Completed).'),
('Inter-College Cricket', 4, '2026-04-05', 'Sports Complex', 'Sports Dept', 'A high-stakes tournament.');

-- Additional Mock Events
INSERT INTO events (id, title, type_id, event_date, venue, organizer, description) VALUES
(5, 'Research Expo', 6, '2026-05-08', 'Innovation Center', 'Research Cell', 'Showcase of student and faculty research.'),
(6, 'Startup Pitch Night', 1, '2026-02-28', 'Seminar Hall B', 'Entrepreneurship Club', 'Live startup demo day with mentors.'),
(7, 'Alumni Connect', 5, '2025-11-18', 'Auditorium Annex', 'Alumni Office', 'Networking session with alumni leaders.'),
(8, 'AI Ethics Roundtable', 1, '2026-03-02', 'Board Room', 'Computer Science Dept', 'Panel discussion on responsible AI.'),
(9, 'Photography Walk', 3, '2026-02-22', 'Campus Garden', 'Arts Club', 'Creative photography showcase.'),
(10, 'Basketball League', 4, '2026-03-20', 'Indoor Stadium', 'Sports Dept', 'Inter-department league matches.')
ON DUPLICATE KEY UPDATE title=VALUES(title), type_id=VALUES(type_id), event_date=VALUES(event_date), venue=VALUES(venue), organizer=VALUES(organizer), description=VALUES(description);

-- =========================================================
-- 06_transport_mgmt/db_schema.sql
-- =========================================================
-- Database: db_transport
CREATE DATABASE IF NOT EXISTS db_transport;
USE db_transport;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: routes
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL UNIQUE,
    start_point VARCHAR(100) NOT NULL,
    end_point VARCHAR(100) NOT NULL,
    distance_km DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Routes
INSERT INTO routes (route_name, start_point, end_point, distance_km) VALUES 
('Campus Express', 'Main Gate', 'Research Wing', 2.5),
('City Shuttle', 'Campus', 'Downtown', 12.0),
('Airport Link', 'Main Building', 'International Airport', 45.5),
('Residential Loop', 'Staff Quarters', 'Students Hostel', 5.0)
ON DUPLICATE KEY UPDATE route_name=route_name;

-- Table: vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_no VARCHAR(20) NOT NULL UNIQUE, -- Prominent display requirement
    model VARCHAR(100) NOT NULL,
    driver_name VARCHAR(100) NOT NULL,
    route_id INT, -- Foreign Key Link
    capacity INT NOT NULL,
    status ENUM('Active', 'Maintenance', 'Out of Service') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Foreign Key logic: This links each vehicle to a specific route from the routes table.
    -- If a route is deleted, we set the route_id to NULL rather than deleting the vehicle.
    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE SET NULL
);

-- Seed Vehicles
INSERT INTO vehicles (vehicle_no, model, driver_name, route_id, capacity, status) VALUES 
('TN-01-AB-1234', 'Tata Marcopolo Bus', 'Samuel Raj', 1, 50, 'Active'),
('TN-01-CD-5678', 'Force Traveller', 'Iniyan K', 2, 14, 'Active'),
('TN-01-EF-9012', 'Ashok Leyland Staff Bus', 'Gowtham S', 3, 40, 'Maintenance'),
('TN-01-XY-4321', 'Toyota Hiace', 'Arun Kumar', 4, 12, 'Active');

-- Additional Mock Vehicles
INSERT INTO vehicles (vehicle_no, model, driver_name, route_id, capacity, status) VALUES
('TN-02-GH-7788', 'Eicher School Bus', 'Meera N', 1, 45, 'Active'),
('TN-02-JK-9900', 'Tempo Traveller', 'Rakesh P', 2, 12, 'Out of Service'),
('TN-03-LM-1122', 'Mini City Bus', 'Naveen T', 3, 28, 'Active'),
('TN-03-NO-3344', 'Force Urbania', 'Sita M', 4, 17, 'Maintenance')
ON DUPLICATE KEY UPDATE model=VALUES(model), driver_name=VALUES(driver_name), route_id=VALUES(route_id), capacity=VALUES(capacity), status=VALUES(status);

-- =========================================================
-- 07_library_mgmt/db_schema.sql
-- =========================================================
-- Database: db_library
CREATE DATABASE IF NOT EXISTS db_library;
USE db_library;

-- Table: admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- Table: books
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(50) NOT NULL UNIQUE,
    category VARCHAR(100),
    status ENUM('Available', 'Issued') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Books
INSERT INTO books (title, author, isbn, category, status) VALUES 
('Clean Code', 'Robert C. Martin', '978-0132350884', 'Computer Science', 'Available'),
('The Pragmatic Programmer', 'Andrew Hunt', '978-0201616224', 'Software Engineering', 'Available'),
('Design Patterns', 'Erich Gamma', '978-0201633610', 'Computer Science', 'Available'),
('Introduction to Algorithms', 'Thomas H. Cormen', '978-0262033848', 'Mathematics', 'Available'),
('Head First Design Patterns', 'Eric Freeman', '978-0596007126', 'Computer Science', 'Available')
ON DUPLICATE KEY UPDATE title=title;

-- Table: transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    status ENUM('Active', 'Returned') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Seed Transactions
INSERT INTO transactions (id, book_id, student_name, issue_date, return_date, status) VALUES
(1, (SELECT id FROM books WHERE isbn = '978-0132350884'), 'Aarav Mehta', '2026-01-12', NULL, 'Active'),
(2, (SELECT id FROM books WHERE isbn = '978-0201616224'), 'Priya Nair', '2026-01-05', '2026-01-20', 'Returned'),
(3, (SELECT id FROM books WHERE isbn = '978-0201633610'), 'Rohan Kulkarni', '2026-02-02', NULL, 'Active'),
(4, (SELECT id FROM books WHERE isbn = '978-0262033848'), 'Meera Shah', '2025-12-18', '2026-01-06', 'Returned'),
(5, (SELECT id FROM books WHERE isbn = '978-0596007126'), 'Lina Roy', '2026-02-06', NULL, 'Active'),
(6, (SELECT id FROM books WHERE isbn = '978-0132350884'), 'Sameer Khan', '2026-01-22', '2026-02-01', 'Returned')
ON DUPLICATE KEY UPDATE book_id=VALUES(book_id), student_name=VALUES(student_name), issue_date=VALUES(issue_date), return_date=VALUES(return_date), status=VALUES(status);

-- =========================================================
-- 08_blood_bank/db_schema.sql
-- =========================================================
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

-- =========================================================
-- 09_billing_shopping_system/db_schema.sql
-- =========================================================
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

-- =========================================================
-- 10_music_book_store/db_schema.sql
-- =========================================================
-- Database: db_mediastore
CREATE DATABASE IF NOT EXISTS db_mediastore;
USE db_mediastore;

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

-- Table: media_type (Normalization)
CREATE TABLE IF NOT EXISTS media_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL UNIQUE
);

-- Seed Media Types
INSERT INTO media_type (type_name) VALUES 
('E-Book'),
('Audio Album'),
('Music Single'),
('PDF Document'),
('Digital Magazine')
ON DUPLICATE KEY UPDATE type_name=type_name;

-- Table: inventory (Digital Media)
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    creator VARCHAR(150) NOT NULL, -- Author or Artist
    type_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    release_year INT,
    genre VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES media_type(id)
);

-- Seed Inventory
INSERT INTO inventory (title, creator, type_id, price, release_year, genre) VALUES 
('Midnight Jazz', 'The Blue Notes', 2, 12.99, 2024, 'Jazz'),
('Python Mastery 2026', 'Sarah Williams', 1, 29.50, 2026, 'Educational'),
('Neon Dreams', 'CyberWave', 3, 1.99, 2025, 'Electronic'),
('The Silent Echo', 'Marcus Thorne', 4, 9.99, 2023, 'Mystery/Thriller'),
('Weekly Tech Review', 'Digital Press', 5, 4.50, 2026, 'Technology')
ON DUPLICATE KEY UPDATE title=title;

-- Additional Mock Inventory
INSERT INTO inventory (id, title, creator, type_id, price, release_year, genre) VALUES
(6, 'Solar Winds', 'Ava Monroe', 2, 11.50, 2024, 'Ambient'),
(7, 'Design Systems 101', 'Kiran Bhat', 1, 22.00, 2025, 'Design'),
(8, 'Night Drive', 'Pulse City', 3, 1.49, 2025, 'Synthwave'),
(9, 'Data Structures Quickstart', 'Nora Fields', 4, 7.99, 2024, 'Education'),
(10, 'Lo-Fi Study Beats', 'Studio Loft', 2, 9.49, 2023, 'Lo-Fi')
ON DUPLICATE KEY UPDATE title=VALUES(title), creator=VALUES(creator), type_id=VALUES(type_id), price=VALUES(price), release_year=VALUES(release_year), genre=VALUES(genre);
