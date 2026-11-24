# دليل وتعليمات رفع وتشغيل المشروع على استضافة InfinityFree

هذا الملف يحتوي على جميع بيانات الاستضافة وقاعدة البيانات والطرق الكاملة لرفع أو تحديث المشروع في أي وقت.



## 2. كيف يعمل الاتصال بقاعدة البيانات تلقائياً؟

في ملف `student-feedback-php/config.php` تم برمجة الاتصال الذكي التلقائي:
- **عند فتح الموقع أونلاين (`students-feedback.infinityfreeapp.com`):** يتصل تلقائياً بقاعدة بيانات **MySQL** (`sql207.infinityfree.com` / `if0_43505732_studentsfeedback`) وينشئ الجداول الثلاثة (`users`, `courses`, `feedbacks`) ويضيف البيانات الافتراضية تلقائياً.
- **عند تشغيله محلياً على جهازك (`127.0.0.1:8083`):** يعمل تلقائياً على قاعدة البيانات المحلية لتتمكن من تجربته بدون إنترنت.

---

## 3. طرق رفع أو تحديث الملفات على الاستضافة

### الطريقة الأولى: عبر برنامج FileZilla
1. افتح برنامج **FileZilla**.
2. في الشريط العلوي أدخل البيانات التالية:
   - **Host:** `ftpupload.net`
   - **Username:** `if0_43505732`
   - **Password:** `sssadmin1`
   - **Port:** `21`
3. اضغط **Quickconnect**.
4. من الجهة اليمنى (السيرفر)، افتح مجلد `students-feedback.infinityfreeapp.com` ثم افتح مجلد `htdocs`.
5. اسحب ملفات مجلد `student-feedback-php` من جهازك إلى داخل مجلد `htdocs`.

### الطريقة الثانية: عبر لوحة تحكم InfinityFree (Online File Manager)
1. ادخل إلى حسابك في [InfinityFree Dashboard](https://dash.infinityfree.com).
2. اختر الدومين `students-feedback.infinityfreeapp.com` واضغط على **File Manager**.
3. افتح مجلد `htdocs` وارفع الملفات داخله.

---

## 4. حساب التجربة لتسجيل الدخول في الموقع
- **Email:** `maryam@jazanu.edu.sa`
- **Password:** `123456`
