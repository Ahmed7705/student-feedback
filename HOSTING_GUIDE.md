# دليل وتعليمات رفع وتشغيل المشروع على استضافة InfinityFree

هذا الملف يحتوي على جميع بيانات الاستضافة والطرق الكاملة لرفع أو تحديث المشروع في أي وقت.

---

## 1. بيانات حساب الاستضافة (InfinityFree & FTP)

- **رابط الموقع المباشر:** [http://students-feedback.infinityfreeapp.com](http://students-feedback.infinityfreeapp.com)
- **المجلد المخصص للموقع على السيرفر:** `/students-feedback.infinityfreeapp.com/htdocs`
- **بيانات اتصال FTP:**
  - **FTP Host (السيرفر):** `ftpupload.net`
  - **FTP Port (المنفذ):** `21`
  - **FTP Username (اسم المستخدم):** `if0_43505732`
  - **FTP Password (كلمة المرور):** `sssadmin1`

---

## 2. ما هي النسخة التي تُرفع على InfinityFree؟

- استضافة InfinityFree المجانية تدعم **نسخة الـ PHP العادي** الموجودة في مجلد:
  `C:\Users\ahmed\Documents\new\student-feedback-php`
- بينما **نسخة Laravel** (`C:\Users\ahmed\Documents\new\student-feedback`) تعمل محلياً على جهازك لعرض البونص (+5 درجات) عبر الأمر:
  ```powershell
  cd C:\Users\ahmed\Documents\new\student-feedback
  php artisan serve --port=8082
  ```

---

## 3. طرق رفع أو تحديث الملفات على الاستضافة

### الطريقة الأولى: عبر برنامج FileZilla (الأسهل لتحديث أي ملف)
1. افتح برنامج **FileZilla**.
2. في الشريط العلوي أدخل البيانات التالية:
   - **Host:** `ftpupload.net`
   - **Username:** `if0_43505732`
   - **Password:** `sssadmin1`
   - **Port:** `21`
3. اضغط **Quickconnect**.
4. من الجهة اليمنى (السيرفر)، افتح مجلد `students-feedback.infinityfreeapp.com` ثم افتح مجلد `htdocs`.
5. اسحب جميع ملفات مجلد `student-feedback-php` من جهازك إلى داخل مجلد `htdocs`.

---

### الطريقة الثانية: عبر لوحة تحكم InfinityFree (Online File Manager)
1. ادخل إلى حسابك في [InfinityFree Dashboard](https://dash.infinityfree.com).
2. اختر الدومين `students-feedback.infinityfreeapp.com` واضغط على **File Manager**.
3. افتح مجلد `htdocs`.
4. استخدم زر **Upload** لرفع ملفات `student-feedback-php`.

---

## 4. قاعدة البيانات على الاستضافة (SQLite تلقائي أو MySQL)

1. **الوضع التلقائي الجاهز حالياً (SQLite):**
   - في ملف `config.php` تم ضبط `$use_mysql = false;` مما يجعل الموقع ينشئ قاعدة البيانات `database.sqlite` والبيانات الافتراضية تلقائياً بمجرد فتح الموقع بدون الحاجة لأي إعداد يدوي.
2. **في حال رغبتك بتحويله إلى MySQL (phpMyAdmin على InfinityFree):**
   - من لوحة تحكم InfinityFree ادخل إلى **MySQL Databases** وأنشئ قاعدة بيانات جديدة (مثلاً `if0_43505732_feedback`).
   - افتح **phpMyAdmin** وسوِّ **Import** لملف `database.sql` الموجود داخل مجلد `student-feedback-php`.
   - افتح ملف `config.php` وعدّل المتغيرات في السطر 26:
     ```php
     $use_mysql = true;
     $db_host = 'sqlXXX.infinityfree.com'; // انسخ اسم الهوست من لوحة التحكم
     $db_name = 'if0_43505732_feedback';
     $db_user = 'if0_43505732';
     $db_pass = 'sssadmin1';
     ```

---

## 5. حساب التجربة لتسجيل الدخول في الموقع
- **Email:** `maryam@jazanu.edu.sa`
- **Password:** `123456`
