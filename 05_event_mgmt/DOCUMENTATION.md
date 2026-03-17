# Event Management System (Project 05)

## Overview
A vibrant event planning and management portal ("Event Horizon") with an integrated pass generator.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_event`)
- **Frontend**: Bootstrap 5 (Magenta/Dark Grey Theme), Poppins Typography

## Key Features
1. **Real-time Spotlight**: Automatic detection of next 3 upcoming events.
2. **Pass Generator**: Generates professional, printable entry tickets.
3. **History/Registry**: Tracking of both future and past events via system date.
4. **Rescheduling**: Easy update forms for changing venues or dates.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_event`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **CURDATE()**: Uses the MySQL `CURDATE()` function to dynamically separate "Upcoming" and "Completed" events without manual status updates.
- **Print Optimization**: Uses CSS `@media print` queries to ensure tickets print perfectly without sidebars or navbars.
