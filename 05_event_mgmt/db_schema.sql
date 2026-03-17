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
