# Online Bookstore (COMP-315 Project 2)

## Team Members / فريق العمل:
| # | Name (English) | الاسم (بالعربية) | Student ID / الرقم الجامعي |
|---|----------------|-------------------|----------------------------|
| 1 | Lamar Mamdouh Busayli | لمار ممدوح بصيلي | 202502428 |
| 2 | Lama Issa Madkhali | لمى عيسى مدخلي | 202500391 |
| 3 | Rahaf Ahmed Jamali | رهف احمد جمالي | 202503165 |
| 4 | Rinas Ibrahim Bahri | ريناس ابراهيم بحري | 202500787 |
| 5 | Maryam Yahya Madkhali | مريم يحيى مدخلي | 202501652 |

---

## Project Structure
- `online-bookstore-php/`: Core project developed in Plain PHP + PDO + SQLite / MySQL (Port `8085`).
- `online-bookstore-laravel/`: Laravel version with exact 1:1 view parity and Eloquent ORM (Port `8084`).
- `online_bookstore_php.zip`: Standalone ready-to-upload archive for Plain PHP.
- `online_bookstore_laravel.zip`: Standalone archive for Laravel version.

## Key Features
- **Catalog**: Search by title or author, filter by category, dynamic JavaScript total price calculator.
- **Inventory Management**: Overview table with stock indicators, Edit, and Delete operations.
- **Add / Edit Book**: Comprehensive validation (title, author, category, positive price, non-negative quantity) and image upload.
- **Authentication**: User registration, session login, validation error handling, and logout.
- **Access Control**: Guest protection preventing unauthorized modifications.
- **Zero Icons & Warm Mocha Theme**: Clean, responsive layout adhering strictly to course requirements.
