# Employee Management System (Project 04)

## Overview
A professional HR Portal for corporate environments, featuring workforce analytics and payroll summaries.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_employee`)
- **Frontend**: Bootstrap 5 (Corporate Slate/Purple Theme)

## Key Features
1. **Payroll Analytics**: Real-time calculation of total monthly cost using SQL.
2. **Employee Directory**: Searchable workforce list with designation filters.
3. **Profile Management**: Support for promotions and salary updates.
4. **Hiring Tracking**: Automatic count of new joiners for the current month.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_employee`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **SQL SUM()**: The dashboard uses the `SUM()` aggregate function to calculate total salaries directly in the query.
- **Relational Integrity**: Uses Foreign Keys (`designation_id`) to ensure data consistency between tables.
