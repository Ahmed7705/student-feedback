# Project Report: Online Bookstore Web Application

**Course**: Web Programming (315 COMP)  
**Academic Year**: 2026 – 2027 (First Semester)  
**College of Engineering & Computer Science - Jazan University**  
**Department of Computer Science**  
**Supervisor**: Dr. Wafaa Hakami  

---

## 1. Team Members & Contributions

| # | Student Name (English) | الاسم بالعربية | Student ID | Key Contributions & Responsibilities |
|---|------------------------|----------------|------------|---------------------------------------|
| 1 | **Lamar Mamdouh Busayli** | لمار ممدوح بصيلي | **202502428** | **Backend Architecture & Auth**: Structured the core PHP application, session authentication system, role-based access control (Admin vs Customer), and routing logic. |
| 2 | **Lama Issa Madkhali** | لمى عيسى مدخلي | **202500391** | **Database Design & ORM**: Designed relational database schema (`users`, `books`), wrote `database.sql`, implemented MySQL PDO connection, and configured Laravel migrations. |
| 3 | **Rahaf Ahmed Jamali** | رهف احمد جمالي | **202503165** | **UI/UX & Frontend Styling**: Designed the responsive bookstore interface, welcome hero banner, book inventory cards layout, and unified CSS styling. |
| 4 | **Rinas Ibrahim Bahri** | ريناس ابراهيم بحري | **202500787** | **Client-Side Scripting & JS**: Developed dynamic JavaScript features including interactive total price calculator for selected books and delete confirmation modals. |
| 5 | **Maryam Yahya Madkhali** | مريم يحيى مدخلي | **202501652** | **Validation, File Uploads & QA**: Implemented server-side form validation (strict positive price, integer quantity), secure image MIME/size verification, and end-to-end testing. |

---

## 2. Project Overview & Description
The **Online Bookstore** is a full-featured web application designed to allow users to explore and browse computer science books, calculate total purchasing costs, and enable administrators to manage the store catalog through a secure dashboard.

The project is developed in two versions:
1. **Core Version (Plain PHP + MySQL)**: Built with standard PHP, PDO database abstraction, HTML5, CSS3, and JavaScript.
2. **Framework Version (Laravel Bonus)**: Converted to Laravel following MVC design patterns with Routes, Controllers, Blade Templates, Eloquent Models, and Migrations.

---

## 3. Technologies Used
- **Server-Side**: PHP 8.2 (Vanilla PHP & Laravel 11 framework)
- **Database**: MySQL (relational database engine using PDO)
- **Client-Side**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 for grid layout
- **Security**: Prepared Statements (PDO), Bcrypt password hashing, CSRF tokens, output escaping via `htmlspecialchars()`, and Role-Based Access Control (RBAC).
- **Web Hosting Target**: InfinityFree Web Hosting & XAMPP local environment.

---

## 4. Database Structure
The application uses two relational tables:

### A. Table: `users`
| Column | Data Type | Key / Attribute | Description |
|--------|-----------|-----------------|-------------|
| `id` | INT / INTEGER | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| `name` | VARCHAR(100) | NOT NULL | User full name |
| `email` | VARCHAR(150) | UNIQUE, NOT NULL | Unique login email address |
| `password` | VARCHAR(255) | NOT NULL | Securely hashed password (Bcrypt) |
| `role` | VARCHAR(20) | DEFAULT 'customer' | User privilege level (`admin` or `customer`) |

### B. Table: `books`
| Column | Data Type | Key / Attribute | Description |
|--------|-----------|-----------------|-------------|
| `id` | INT / INTEGER | PRIMARY KEY, AUTO_INCREMENT | Unique book identifier |
| `title` | VARCHAR(150) | NOT NULL | Book title |
| `author` | VARCHAR(100) | NOT NULL | Author name |
| `category` | VARCHAR(50) | NOT NULL | Book category (Programming, Database, etc.) |
| `price` | DECIMAL(8,2) | NOT NULL | Unit price in Saudi Riyals (SAR) |
| `quantity` | INT | NOT NULL | Available stock units (whole number $\ge 0$) |
| `image_path` | VARCHAR(255) | NULLABLE | File path of uploaded book cover photo |

---

## 5. Key System Features & Operations

### A. Home & Book Catalog (`index.php`)
- **Welcome Section**: Introduces the online bookstore to visitors.
- **Search & Category Filter**: Allows filtering by subject or searching titles and authors in real-time.
- **Dynamic Price Calculator**: JavaScript computes the running total price of selected checkboxes without reloading the page.
- **Responsive Cards**: Visual presentation of each book with cover, title, author, category badge, and stock count.

### B. Book Inventory Management (`manage.php`)
- **Inventory Overview Table**: Shows all books with stock status indicators.
- **Role-Based Protection**: Only authenticated administrators can add, edit, or delete books. Non-administrators and guests see friendly prompts to sign in.
- **Confirmation Prompts**: Javascript confirmation prompt prior to executing book deletion.

### C. Add & Edit Book Forms (`create.php`, `edit.php`)
- **Strict Server-Side Validation**:
  - Title and author minimum character lengths.
  - Price must be a valid positive decimal ($> 0$).
  - Quantity must be a valid non-negative integer ($\ge 0$, rejecting decimals).
  - Image upload validated for actual MIME type (`getimagesize`) and size ($\le 2\text{ MB}$).
- **Reset Button**: Form reset button for quick input clearing.

### D. Authentication & Security
- **Bcrypt Hashing**: All user passwords are encrypted using PHP's native `password_hash()`.
- **Prepared Statements**: Prevents SQL injection across all queries.
- **CSRF Tokens**: Protection against cross-site request forgery on all POST operations.

---

## 6. Setup & Installation Guide

### Local Setup (XAMPP / PHP Built-in Server):
1. Place the project files inside `C:\xampp\htdocs\online-bookstore\` (or run `php -S 127.0.0.1:8085`).
2. Open phpMyAdmin and create a database named `bookstore_db`.
3. Import `database.sql` into the database.
4. Open `config.php` and verify connection parameters (`$db_host = 'localhost'`, `$db_user = 'root'`, `$db_pass = ''`).
5. Open your browser at `http://127.0.0.1:8085/`.
6. Default administrator credentials:
   - **Email**: `admin@bookstore.com`
   - **Password**: `Admin@2026`

### Online Deployment (InfinityFree):
1. In the InfinityFree Control Panel, create a MySQL Database.
2. Open phpMyAdmin on InfinityFree and import `database.sql`.
3. Update `config.php` with the InfinityFree MySQL Host, Database Name, Username, and Password.
4. Upload all files from `online-bookstore-php` into the `htdocs/` folder via FTP or the File Manager.
