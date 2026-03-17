# Student Attendance Management System (Project 03)

## Overview
A minimalist attendance tracker for schools and colleges, focusing on quick daily marking and monthly reporting.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_attendance`)
- **Frontend**: Bootstrap 5 (Orange/Amber Theme)

## Key Features
1. **Bulk Marking**: Mark attendance for an entire class at once.
2. **Attendance History**: View past records by specific date.
3. **Monthly Statistics**: Automated calculation of attendance percentages.
4. **Progress Indicators**: Color-coded progress bars for student consistency.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_attendance`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **Foreach Loop**: In `save_attendance.php`, a loop is used to process an array of student statuses in a single form submission.
- **JOIN Queries**: The history view uses `LEFT JOIN` to combine student data with attendance records even if a record doesn't exist for a certain day.
