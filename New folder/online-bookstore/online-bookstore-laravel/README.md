# Online Bookstore (Laravel Version)

Laravel implementation of the Online Bookstore project using MVC architecture (Routes, Controllers, Blade Views, Models, and Migrations).

## Team Members:
- **Lamar Mamdouh Busayli** (لمار ممدوح بصيلي) - `202502428`
- **Lama Issa Madkhali** (لمى عيسى مدخلي) - `202500391`
- **Rahaf Ahmed Jamali** (رهف احمد جمالي) - `202503165`
- **Rinas Ibrahim Bahri** (ريناس ابراهيم بحري) - `202500787`
- **Maryam Yahya Madkhali** (مريم يحيى مدخلي) - `202501652`

## Features:
- **Book Catalog**: Responsive card view, category filter, search, and dynamic JavaScript price sum calculation.
- **Inventory Management**: Manage stock, edit book details, and delete with confirmation.
- **Form Handling**: Request validation for prices, whole-number quantities, and book cover uploads.
- **User Authentication**: Secure authentication with role-based access for store administrators.
- **Administrator Account**: `admin@bookstore.com` / `Admin@2026`

## Running Locally:
```bash
composer install
php artisan migrate --seed
php artisan serve --port=8084
```
