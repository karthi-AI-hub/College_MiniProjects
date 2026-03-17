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
