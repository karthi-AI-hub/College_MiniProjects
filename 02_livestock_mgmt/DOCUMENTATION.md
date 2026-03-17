# Livestock Management System (Project 02)

## Overview
A nature-inspired livestock tracking system designed for farmers to manage animal health, categories, and inventory.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_livestock`)
- **Frontend**: Bootstrap 5 (Green/Nature Theme), Custom Font (Segoe UI)

## Key Features
1. **Health Tracking**: Visual status indicators (Healthy, Sick, Observation).
2. **Category Management**: Animals grouped by types (Cattle, Poultry, etc.).
3. **Filtering**: Advanced filtering by health and category.
4. **Reports**: Printable health reports for sick animals.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_livestock`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **Status Badges**: Conditional PHP logic used to swap CSS classes based on database values.
- **Prepared Statements**: Used in `delete_animal.php` to prevent SQL injection.
