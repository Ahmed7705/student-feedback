# Online Bookstore (COMP-315 Project 2 - Laravel Version)

## Team Members / فريق العمل:
- **Lamar Mamdouh Busayli** (لمار ممدوح بصيلي) - `202502428`
- **Lama Issa Madkhali** (لمى عيسى مدخلي) - `202500391`
- **Rahaf Ahmed Jamali** (رهف احمد جمالي) - `202503165`
- **Rinas Ibrahim Bahri** (ريناس ابراهيم بحري) - `202500787`
- **Maryam Yahya Madkhali** (مريم يحيى مدخلي) - `202501652`

## Features:
- Book Catalog with search, category filter, and JavaScript total price calculation
- Add Book form with price and quantity validation and optional cover upload
- Manage Books inventory table with stock indicators, Edit, and Delete actions
- User session authentication: Registration, Login, and Logout
- Test credentials: `admin@bookstore.com` / `123456`

## Database Tables:
- `users` (id, name, email, password)
- `books` (id, title, author, category, price, quantity, image_path)

## Run Locally:
```bash
composer install
php artisan migrate --seed
php artisan serve --port=8084
```
