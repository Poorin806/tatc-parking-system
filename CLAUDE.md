# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

TATC Parking: vehicle parking-card registration system for Sattahip Technical College (วิทยาลัยเทคนิคสัตหีบ). Diploma in Information Technology final project (2023). Plain PHP 8 + MariaDB 10.4 + Bootstrap, UI text is Thai. No framework, no Composer, no build step, no tests, no linter.

## Running it

Built for XAMPP (Apache + MariaDB):

1. Put the folder at `htdocs/TATCParking`. The name is hardcoded: `import/connect.php` sets `$root_folder = "/TATCParking/"`, and pages use it for asset URLs. This repo's folder name (`tatc-parking-system`) will not work as-is.
2. Import `tatc-parking-database.sql` (creates DB `tatcparking`; connection is user `root`, empty password, set in `import/connect.php`). It is schema only, so every table starts empty. Then import `tatc-parking-seed-data.sql` (reference rows: statuses, lookups, province/district/subdistrict) and create an `admin` row (commented template at the end of the seed file) before backend login works. The original dump with sample data is in git history at commit `61e621e` (`git show 61e621e:"TATC Parking - Database.sql"`).
3. Open `http://localhost/TATCParking/` (users) or `http://localhost/TATCParking/backend/` (admins).

There is nothing to compile or test; verify changes by loading pages in the browser. Front-end libraries come from CDNs (jQuery, SweetAlert2, AOS, html2pdf.js, Bootstrap Icons) and `css/bootstrap`, `js/bootstrap`, `js/qrcode*.js` are vendored.

## Architecture

**Page-per-file, server-rendered.** Every page is a standalone `.php` file that includes the shared bits from `import/` at the top, then emits HTML. No router, no controllers.

- `import/connect.php`: the real DB config. Creates `$con` (mysqli), sets UTF-8 and `Asia/Bangkok`, calls `session_start()`. The root-level `connect.php` is a stale stub (points at `vue_finalexam`); ignore it.
- `import/SQLQuery.php`: thin helpers over `$con` (`SQLSelect`, `SQLSelectLoop`, `SQLSelectAssoc`, `SQLSelectNumRow`, `SQLQuery`) and `formatDateThai()` (Buddhist-era years).
- `import/LoginChecking.php`, `navbar.php`, `sidebar.php`, `css.php`, `js.php`: included by user pages.
- `backend/` is the admin site, a parallel copy of that structure with its own `LoginChecking.php`, `navbar.php`, `sidebar.php`. Backend pages include `../import/connect.php` and `../import/SQLQuery.php`.

**Two separate auth realms**, both plain PHP sessions:
- Users (`login.php`): `$_SESSION['UserID']` + `$_SESSION['UserType']` (`Student` → table `student`, anything else → `personnel`).
- Admins (`backend/login.php`): `$_SESSION['AdminID']` + `$_SESSION['AdminPosition']` (`admin.php` gates admin management on position `1`).

**Registration workflow** (the core of the app):
`registration-form.php` (user fills the vehicle and owner form and uploads documents) → admin reviews in `backend/registration-request.php` / `registration-checking.php` / `registration-overall.php` → `backend/sticker*.php` prints the parking card/sticker → user picks up the card (`ajax_db.php?function=ConfirmCompleteRegistration`).
- Progress is an append-only history in `registration_status_detail` (`regis_id`, status id, note, date, time, …), one row per transition. Statuses live in `registration_status`: 1 draft, 2 submitted, 3 under review, 4 review done, 5 card received, 6 modified vehicle, 7 documents incomplete, 8 documents invalid, 9 other. Code currently only writes 2–5.
- Uploaded documents and photos (ID cards, vehicle registration, three vehicle photos, signature PDF) are stored as `mediumblob` columns in `registration`, with the original filenames alongside. There is no upload directory (no `move_uploaded_file`); admin pages such as `backend/registration-checking.php` fetch them over `ajax_db.php` and render them as base64 `data:` URIs. `file_downloading.php` is separate: it serves announcement attachments (base64 in `announcement.file`).
- `parkingcard-detail.php` draws a QR code (`js/qrcode.js`) of the page's own URL (`window.location.href`). PDFs are generated in the browser with html2pdf.js (`PDFExport.php` is a scratch page, as is `testaction.php`).

**`ajax_db.php`** (1100+ lines) is the shared AJAX/action endpoint, dispatched on `$_POST['function']` / `$_GET['function']` (province→district→subdistrict cascades, registration delete, announcement detail, report modals). Its second half handles CRUD for the lookup tables (`prefix`, `department`, `major`, `class_level`, `vehicle_type`, `vehicle_brand`, `color`) via a `$type` switch, driven from `backend/system.php`. `system_settings` (key/value rows such as `vehicle_registration`, `max_vehicle_registration`) toggles registration open/closed and the per-user vehicle cap.

## Things to know before editing

- **Not safe as written.** Queries are built by string interpolation of `$_POST`/`$_GET`/`$_SESSION` (SQL injection), passwords are stored and compared in plaintext, and `ajax_db.php` performs no authentication or ownership check. Don't copy these patterns into new code: use prepared statements, `password_hash`/`password_verify`, and server-side auth/role checks. Fixing existing pages is welcome but is a behavior change; confirm scope first.
- `LoginChecking.php` redirects with an echoed `<script>` and does not `exit`, so the rest of the page still executes and renders for unauthenticated requests. Add an `exit` if you touch it.
- Thai strings are everywhere (labels, status names, SQL data). Keep files UTF-8 and the connection on `utf8`.
- `tatc-parking-database.sql` is schema only and `tatc-parking-seed-data.sql` is reference data only. Keep both free of people data. The schema file is the source of truth for table structure; don't edit its DDL without being asked.
- `README.md` is the public-facing overview (features, setup, known limitations). `backend/user_manual_admin.pdf` and `user_manual_users.pdf` are the end-user manuals. Keep the README's feature list in sync when adding or removing features.
