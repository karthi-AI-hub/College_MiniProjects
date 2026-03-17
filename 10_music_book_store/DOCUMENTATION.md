# InfinityMedia Manager (Project 10)

## Overview
A high-end digital media storefront and management portal. This project serves as the premium "Closing Project" of the series, featuring a rich Card-based UI and cross-media type support (E-Books and Music).

## Technical Stack
- **Backend**: PHP 8+
- **Database**: MySQL (`db_mediastore`) with Normalization.
- **Frontend**: Bootstrap 5 (Deep Purple & Gold Theme).

## Logic Highlights (Viva Questions)
- **JOIN Query Implementation**: In `view_catalog.php`, we don't just display IDs. We use `JOIN inventory i JOIN media_type t ON i.type_id = t.id`. This is crucial because it allows the UI to show "E-Book" or "Audio Album" directly to the user by fetching data from the normalized `media_type` table.
- **Card-Based Architecture**: Unlike standard tables, this project uses a `row > col > .media-card` structure. This demonstrates the ability to build consumer-facing "Gallery" layouts suitable for e-commerce.
- **Conditional Iconography**: The system dynamically changes icons based on the `type_id`. Books and PDFs get an `fa-book-open` icon, while Music and Single Tracks get `fa-music`, improving visual UX.
- **Dashboard Aggregations**: The dashboard uses `GROUP BY` and `ORDER BY count DESC` to identify the "Top Genre" currently in the store's portfolio.

## Setup
1. Import `db_schema.sql`.
2. Login: `admin` / `admin123`.
