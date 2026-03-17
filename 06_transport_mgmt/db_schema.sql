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
