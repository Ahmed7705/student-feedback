# Project 3: Appointment Booking System

**Course:** 315 COMP — Web Programming  
**University:** Jazan University — College of Engineering & Computer Sciences

---

## 1. Project Description

A web application for booking and managing **Academic Advising** appointments. Students can view upcoming available and booked time slots and submit new bookings. Signed-in advisors can filter, edit, cancel, delete, and export appointments.

---

## 2. Technologies Used

- **Core Project (`php-app/`):** HTML5, CSS3, JavaScript, PHP (MySQLi Prepared Statements), MySQL
- **Bonus Project (`laravel-app/`):** Laravel (Routes, Controllers, Eloquent Models, Migrations, Seeders, Blade Templates)
- **Security:** Prepared statements / Eloquent ORM (SQLi), `htmlspecialchars()` / Blade `{{ }}` (XSS), CSRF tokens / `@csrf`, `password_hash()` (bcrypt), server-side validation and availability checks

---

## 3. Database Structure

SQL file: `database.sql`

### Table: `appointments`
| Column | Type | Notes |
|---|---|---|
| `id` | `INT AUTO_INCREMENT` | Primary Key |
| `customer_name` | `VARCHAR(100)` | Student / visitor name |
| `email` | `VARCHAR(120)` | Contact email |
| `appointment_date` | `DATE` | Booking date |
| `appointment_time` | `TIME` | Booking time |
| `status` | `ENUM('Booked','Completed','Cancelled')` | Default: `Booked` |
| `attachment` | `VARCHAR(255)` | Optional uploaded file path |
| `created_at` | `TIMESTAMP` | Auto timestamp |

### Table: `users`
| Column | Type | Notes |
|---|---|---|
| `id` | `INT AUTO_INCREMENT` | Primary Key |
| `name` | `VARCHAR(100)` | Advisor name |
| `email` | `VARCHAR(120)` | Unique login email |
| `password` | `VARCHAR(255)` | Bcrypt hash |
| `role` | `ENUM('admin','user')` | Default: `user` |
| `created_at` | `TIMESTAMP` | Auto timestamp |

**Default Advisor Login:** `admin@jazanu.edu.sa` / `password`

---

## 4. Setup Instructions

### A. PHP + MySQL (Local XAMPP)
1. Copy `php-app/` into `C:\xampp\htdocs\appointment-booking\`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin and import `database.sql`.
4. Visit `http://localhost/appointment-booking/`.

### B. Deployment on InfinityFree
1. Create a MySQL database in InfinityFree Control Panel and import `database.sql` via phpMyAdmin.
2. Update `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME` in `php-app/config.php`.
3. Upload all contents of `php-app/` into `htdocs/` via File Manager or FTP.

### C. Laravel Bonus Version (`laravel-app/`)
1. Create a Laravel project and copy `laravel-app/` files into it:
   ```bash
   composer create-project laravel/laravel appointment-laravel
   ```
   Copy `routes/`, `app/`, `database/`, `resources/`, and `public/` from `laravel-app/` into `appointment-laravel/`.
2. Copy `.env.example` to `.env`, set MySQL credentials, and run:
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate --seed
   php artisan serve
   ```
3. Open `http://127.0.0.1:8000/`.

---

## 5. Team Contributions

| Student Name | Student ID | Contribution |
|---|---|---|
| Member 1 | | Database schema, `config.php`, booking backend |
| Member 2 | | UI layout, CSS, JavaScript validation & confirmations |
| Member 3 | | Manage, filter, edit, cancel, delete, and CSV export |
| Member 4 | | Authentication, Laravel conversion, deployment & report |
