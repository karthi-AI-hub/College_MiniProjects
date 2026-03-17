# Shopping & Billing Management System (Project 09)

## Overview
A high-performance retail portal designed for small-to-medium stores. It combines a clean modern UI with heavy inventory-sync logic.

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_billing`)
- **Frontend**: Bootstrap 5 (Teal & Dark Grey Modern Theme)

## Logic Highlights (Viva Questions)
- **PHP Math Logic**: In `create_bill.php`, total price is calculated using `$total = $price * $quantity`. We use `number_format($total, 2)` to ensure professional currency display.
- **Stock Management (UPDATE Query)**: In `save_bill.php`, we run `UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?`. This ensures that as soon as a bill is generated, the item count in the warehouse decreases automatically.
- **Low Stock UI Alerts**: We use a conditional check `if ($stock < 10)` in the inventory table to apply a CSS class `stock-low` (Red color), alerting managers to restock.
- **Atomic Transactions**: The billing script uses `FOR UPDATE` in SQL to lock the product row during checkout, preventing two simultaneous bills from creating an "over-sold" state.

## Setup
1. Import `db_schema.sql`.
2. Login: `admin` / `admin123`.
