# TATC Parking

A web system for registering vehicles and issuing parking permits at **Sattahip Technical College** (วิทยาลัยเทคนิคสัตหีบ).

Final project for my Diploma in Information Technology (2023). It replaces paper forms: students and staff register a car or motorcycle online, upload their documents, and track the request. Staff review the request and print the parking sticker.

The interface is in Thai.

## Features

**Students and personnel**
- Register as a student or staff member and log in
- Register a car or motorcycle with owner details and uploaded documents (vehicle registration, ID cards, three vehicle photos, signed form)
- Check the status of each request, from submitted through review to card received
- View the parking card, with a QR code
- Notifications and college announcements, with file downloads
- Report a problem about a vehicle
- In-app user manual

**Admins (`/backend`)**
- Dashboard with registration and report summaries
- Review requests: filter, check documents, and mark them complete, incomplete or invalid
- Print parking stickers
- Manage users, admins (admin role restricted by position), and announcements
- Handle problem reports
- System settings: open or close registration, set the per-user vehicle limit, and manage lookup data (prefixes, departments, majors, class levels, vehicle types, brands, colors)

## Tech stack

- PHP 8 (no framework, no Composer), mysqli
- MariaDB 10.4 (`tatc-parking-database.sql` for the schema, `tatc-parking-seed-data.sql` for reference data)
- Bootstrap, jQuery, SweetAlert2, AOS, html2pdf.js, qrcode.js

## Run locally

Requires XAMPP (Apache + MariaDB) or an equivalent PHP and MariaDB stack.

1. Copy this folder to `htdocs/TATCParking`. The name matters because the app hardcodes `/TATCParking/` in its URLs.
2. Import `tatc-parking-database.sql`. It creates the `tatcparking` database and its tables, empty.
3. Import `tatc-parking-seed-data.sql` (statuses, vehicle and department lookups, Thai provinces, districts and subdistricts).
4. Create the first admin: uncomment the `INSERT INTO admin` template at the end of the seed file, change the password, and run it.
5. Check the connection settings in `import/connect.php` (default: `root`, empty password).
6. Open `http://localhost/TATCParking/` for users or `http://localhost/TATCParking/backend/` for admins.

Both SQL files hold no personal data. Students, personnel, registrations and announcements start empty.

## Documentation

- [User manual (PDF)](user_manual_users.pdf)
- [Admin manual (PDF)](backend/user_manual_admin.pdf)

## Known limitations

This is a student project and was never hardened for production:

- SQL is built by string concatenation from request data (injection risk), and prepared statements are not used.
- Passwords are stored and compared in plaintext.
- `ajax_db.php`, the shared AJAX endpoint, does no authentication or ownership checks.
- Uploaded documents are stored as blobs in the database.

Do not deploy it as-is or load real personal data into it.

## Author

Poorin Thatsakhon
