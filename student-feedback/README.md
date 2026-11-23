# Student Feedback Portal (COMP-315)

Team Members:
- Maryam Jafry (202500647)
- Khadija Hussein (202514175)
- Mayaz Ahmed (202502521)

Features:
- CRUD operations for course feedback
- Course filter and search
- Login, registration, and logout (Test account: maryam@jazanu.edu.sa / 123456)
- Image upload stored in public/uploads

Database Tables:
- users (id, name, email, password)
- courses (id, course_code, course_name, instructor_name, department)
- feedbacks (id, course_id, student_name, student_id, rating, category, title, comments, image_path, is_anonymous)

Run Locally:
composer install
php artisan migrate --seed
php artisan serve --port=8082
