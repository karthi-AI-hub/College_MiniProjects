# Transport Management System (Project 06)

## Overview
A high-contrast Fleet Management portal designed for tracking vehicles, drivers, and operational routes in a corporate or campus environment.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_transport`)
- **Frontend**: Bootstrap 5 (Dark Grey & Yellow Theme)

## Key Features
1. **Fleet Analytics**: Real-time stats for units in "Maintenance" or "Out of Service".
2. **Prominent Identification**: Bold vehicle number display for quick registry lookups.
3. **Route Mapping**: Logical link between transport units and specific geographic routes.
4. **Capacity Tracking**: Monitors seating capacity for fleet optimization.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_transport`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **Foreign Key Logic**: The `vehicles` table uses a `route_id` as a foreign key to link to the `routes` table. This prevents "orphan" data and allows us to see exactly how many units are assigned to a path.
- **ENUM Status**: The `status` field uses an `ENUM` type in MySQL to restrict inputs to only specific operational states (Active, Maintenance, Out of Service), ensuring data integrity.
- **Prepared Statements**: All CRUD operations use prepared statements to sanitize inputs and mitigate SQL injection risks.
