# Library Management System (Project 07)

## Overview
A professional academic portal for managing book inventories, student lending, and transaction histories.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_library`)
- **Frontend**: Bootstrap 5 (Navy Blue & Cream Theme), Custom Font (Playfair Display)

## Key Features
1. **Inventory Tracking**: Manage books with real-time status (Available/Issued).
2. **Transactional Logic**: Handle book lending and returns with automated inventory updates.
3. **Lending History**: Full log of past and active loans.
4. **Searchable Catalog**: Quick search by Title, Author, or ISBN.

## Setup Instructions
1. Import `db_schema.sql` into a database named `db_library`.
2. Configure `config.php` with your credentials.
3. Login with `admin` / `admin123`.

## Logic Highlights (Viva Questions)
- **SQL UPDATE Logic**: When a book is issued, we run an `UPDATE` query on the `books` table to change its status to 'Issued'. This ensures the book no longer appears as an option in the lending form.
- **Database Transactions**: In `issue_book.php` and `return_book.php`, we use MySQL transactions (Begin, Commit, Rollback) to ensure that if one query fails (e.g., updating the book status), the entire transaction is rolled back, preventing data inconsistency.
- **Join Queries**: The transactions view uses a `JOIN` between `transactions` and `books` to display the book title instead of just the ID.
