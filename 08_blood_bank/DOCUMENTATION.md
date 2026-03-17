# Blood Bank Management System (Project 08)

## Overview
A high-priority emergency portal for managing a centralized registry of blood donors, categorized by blood groups and geography.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_bloodbank`)
- **Frontend**: Bootstrap 5 (Medical Red & White Theme)

## Key Features
1. **Emergency Command Center**: Real-time stats for universal donors (O-).
2. **Instant Search**: Partial match location search using SQL `LIKE`.
3. **Donor Lifecycle**: Track donation history and contact details.
4. **Blood Group Mapping**: High-visibility badges for group identification.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_bloodbank`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **SQL LIKE Operator**: In `view_donors.php`, we use the `LIKE` operator (e.g., `city LIKE '%london%'`) to allow for approximate string matching. The `%` wildcard ensures that the query finds records where the search term exists anywhere within the city name.
- **Emergency UX**: Contact numbers are styled with a monospace "Emergency" font to emphasize their importance in critical situations.
- **ENUM Integrity**: The database uses `ENUM` types for `blood_group` and `gender` to restrict data inputs to valid medical categories.
