# College Management System Documentation

## Project Overview

This College Management System is a web-based application designed to manage student records, departments, and provide quick insights through a dashboard. It is built using PHP (backend), MySQL (database), and Bootstrap 5 (frontend).

### Features

*   **Secure Authentication**: Role-based login system for administrators.
*   **Dynamic Dashboard**: Real-time statistics on student and department counts.
*   **Department Management**: Create, Read, and Delete academic departments.
*   **Student Management**: 
    *   Add new students with department selection.
    *   View all students in a sortable/searchable list.
    *   Edit existing student details.
    *   Delete student records.
    *   Print-friendly student directory.
*   **Responsive Design**: Works on desktops, tablets, and mobile devices.

---

## Technical Stack

*   **Backend**: PHP 7.4+ (Compatible with PHP 8.x)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, CSS3, Bootstrap 5.3, JavaScript
*   **Server**: Apache / Nginx (XAMPP/WAMP/MAMP recommended for local testing)

---

## Installation & Setup Guide

follow these steps to set up the project on your local machine or server.

### Prerequisites

*   A local server environment (e.g., XAMPP, MAMP, WAMP).
*   A web browser.

### Step 1: Database Setup

1.  Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`).
2.  Create a new database named `db_college`.
3.  Click on the **Import** tab.
4.  Choose the file `db_schema.sql` located in the project folder.
5.  Click **Go** to import the tables and default data.

### Step 2: Configuration

1.  Open the project folder in your code editor.
2.  Locate `config.php`.
3.  Update the database credentials if necessary:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root'); // Your MySQL Username
    define('DB_PASS', '');     // Your MySQL Password
    define('DB_NAME', 'db_college');
    ```

### Step 3: Run the Application

1.  Move the project folder to your server's root directory (`htdocs` for XAMPP, `www` for WAMP).
2.  Open your browser and navigate to: `http://localhost/masterminds/01_college_mgmt/`
3.  You will be redirected to the login page.

### Default Login Credentials

*   **Username**: `admin`
*   **Password**: `admin123`

---

## Troubleshooting

*   **"Headers already sent" Error**: Ensure there are no spaces or newlines before `<?php` or after `?>` in `config.php`. (Fixed in latest version).
*   **Database Connection Failed**: check your username/password in `config.php`.
*   **404 Not Found**: Ensure the URL path matches your folder structure.

---

## File Structure

```
01_college_mgmt/
├── assets/
│   └── css/
│       └── style.css       # Custom styles
├── includes/
│   ├── header.php          # HTML Head & Session check
│   ├── sidebar.php         # Navigation Sidebar
│   └── footer.php          # Footer & Scripts
├── config.php              # Database Connection
├── db_schema.sql           # SQL Database Import File
├── index.php               # Dashboard
├── login.php               # Login Page
├── logout.php              # Logout Script
├── departments.php         # Department Management
├── delete_department.php   # Delete Dept Logic
├── view_students.php       # Student List
├── add_student.php         # Add Student Form
├── edit_student.php        # Edit Student Form
└── delete_student.php      # Delete Student Logic
```
