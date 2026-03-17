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
