# 10–15 Minute Presentation Guide & Script
## COMP-315 Mini-Project: Student Feedback Portal
**Course:** COMP-315 Web Programming  
**Supervised by:** Dr. Abdulkarim Malkadi  
**College of Engineering & Computer Sciences — Jazan University**

---

### 🎙️ Team Speaking Distribution Plan (12–15 Minutes Total)

```
[00:00 - 04:00] Maryam Jafry  -> Introduction, Problem Statement, Objectives, UI/UX Design & Bootstrap
[04:00 - 08:30] Khadija Hussein -> Backend PHP Architecture, Server-Side Validation, File Upload, Code Reuse
[08:30 - 12:30] Mayaz Ahmed   -> Database Schema, CRUD Operations, Session/Cookie Auth, Security & Prepared Statements
[12:30 - 15:00] Team Together   -> Live Demonstration, Challenges & Solutions, Future Enhancements & Q&A
```

---

## 1. Speaker 1: Maryam Jafry (ID: 202500647) — [0:00 – 4:00]

### Slide 1: Title & Welcome
> "Good morning, Dr. Abdulkarim and fellow colleagues. Today, our team—Maryam Jafry, Khadija Hussein, and Mayaz Ahmed—is proud to present our COMP-315 Web Programming mini-project: **Student Feedback Portal**."

### Slide 2: Project Overview & Objectives
> "Every academic semester, students encounter various learning experiences across lectures, coursework, and laboratory sessions. However, traditional course evaluation mechanisms are often static or closed until the semester ends.
> 
> Our objective was to develop a **responsive, dynamic, full-stack web application** adhering to all eight chapters of COMP-315. The portal allows students to evaluate courses, submit constructive ratings across specific categories, upload supporting materials, and maintain anonymity if desired."

### Slide 3: Frontend Architecture & User Interface Design
> "For the frontend interface, we applied principles from **Chapter 1**:
> - **HTML5 & Semantic Markup:** Clean, structured tags ensuring accessibility.
> - **Bootstrap 5:** Fully responsive grid system (`row`, `col-md-*`), cards, badges, and modals.
> - **Soft Academic Styling:** We intentionally chose a calm, university-standard color palette—academic navy blue, soft slate gray, and warm amber star ratings—avoiding jarring or exaggerated AI gradients so the application looks like a genuine, professional student creation.
> - **Interactive Components:** Dynamic star rating selection and real-time form checks implemented via unobtrusive JavaScript."

---

## 2. Speaker 2: Khadija Hussein (ID: 202514175) — [4:00 – 8:30]

### Slide 4: Backend PHP Architecture & Reusability
> "Thank you, Maryam. I will explain the server-side architecture and business logic.
> As taught in **Chapter 5**, writing clean and maintainable code requires **Code Reuse**. Instead of duplicating navigation and database logic across pages:
> - We modularized common components into `header.php`, `navbar.php`, `footer.php`, and `auth.php`.
> - We used `require_once` for critical dependencies like `config.php`, ensuring that if a vital file is missing, the script halts with a clear diagnostic notice rather than running with silent failures."

### Slide 5: Server-Side Form Validation (CLO 1.2)
> "In `submit_feedback.php` and `register.php`, we implemented strict server-side validation:
> - Verification of non-empty fields and string length requirements.
> - Email validation using PHP's `filter_var($email, FILTER_VALIDATE_EMAIL)`.
> - Rating integrity checking to ensure values are strictly integers between 1 and 5.
> - Client input sanitization via custom `clean_input()` functions to strip unwanted tags and whitespace."

### Slide 6: File Upload Implementation (Chapter 4)
> "In accordance with **Chapter 4 Slides 20–21**, our application supports file handling:
> - Students can upload custom profile avatars and attach supplementary documents (e.g., assignment PDFs or lecture screenshots) to their feedback.
> - The form employs `enctype='multipart/form-data'`.
> - On the server, we inspect `$_FILES['attachment']`, validate against a whitelist of permitted extensions (`jpg`, `jpeg`, `png`, `pdf`, `docx`), enforce a 2MB size ceiling, generate a cryptographically random filename via `uniqid()` to prevent file-overwriting attacks, and relocate the file safely using `move_uploaded_file()`."

---

## 3. Speaker 3: Mayaz Ahmed (ID: 202502521) — [8:30 – 12:30]

### Slide 7: Database Schema & Relational Design (Chapter 6)
> "Thank you, Khadija. I will now cover our database integration, CRUD queries, and security mechanisms.
> Our MySQL database `student_feedback_db` contains three structured tables:
> 1. `users`: Stores student and admin credentials with role-based attributes.
> 2. `courses`: Catalogs course codes, course names, departments, and instructors.
> 3. `feedbacks`: Stores student reviews linked via foreign keys to both users and courses with `ON DELETE CASCADE` integrity."

### Slide 8: Full CRUD Implementation
> "As outlined in the grading rubric (CLO 2.3), each student must understand all database queries:
> - **SELECT:** Used extensively with `WHERE`, `ORDER BY`, `LIKE`, and `JOIN` across `feedbacks.php` and `courses.php`. We also used aggregate functions `COUNT(*)` and `AVG(rating)` grouped by course.
> - **INSERT:** Utilized in `register.php` for user onboarding and `submit_feedback.php` for posting reviews.
> - **UPDATE:** Implemented in `edit_feedback.php` to let students revise their reviews and in `profile.php` for updating student profiles.
> - **DELETE:** Implemented in `delete_feedback.php` with ownership checks so students can delete their own entries and admins can moderate inappropriate reviews."

### Slide 9: Authentication & Security Architecture (Chapter 6 & 7)
> "Security was a top priority throughout development:
> - **Sessions & Cookies:** User authentication uses PHP sessions (`session_start()`, `$_SESSION`). A 'Remember Me' feature is implemented using HTTP cookies (`setcookie()`).
> - **Password Hashing:** Passwords are never stored in plain text. We employ `password_hash($password, PASSWORD_BCRYPT)` and verify credentials with `password_verify()`.
> - **SQL Injection Prevention:** Every dynamic query that receives user input uses **MySQLi Prepared Statements** (`$conn->prepare()`, `bind_param()`, `execute()`), separating SQL commands from user parameters.
> - **XSS Prevention:** All output rendered into HTML is escaped with `htmlspecialchars()`."

---

## 4. Live Demonstration & Conclusion — All Team Members [12:30 – 15:00]

### Live Demo Steps to Show Dr. Abdulkarim:
1. **Homepage:** Show live stats counters and recent feedback cards.
2. **Registration & Login:** Log in as a student using `maryam@jazanu.edu.sa`.
3. **Submit Evaluation:** Submit a new review for `COMP-315 Web Programming`, give a 5-star rating, attach a file, and check "Submit Anonymously".
4. **Dashboard & CRUD:**
   - Go to "My Dashboard" to show the newly submitted evaluation.
   - Click **Edit** to modify the rating or text (verifying `UPDATE`).
   - Click **Delete** to remove a test review (verifying `DELETE`).
5. **Admin Portal:** Log in as `admin@jazanu.edu.sa` to demonstrate course satisfaction analytics (`GROUP BY`) and moderation controls.

### Slide 10: Technical Challenges & Solutions
- **Challenge 1:** Managing file upload security and preventing duplicate filenames.  
  *Solution:* Sanitized extensions and applied `uniqid('att_', true)` before calling `move_uploaded_file()`.
- **Challenge 2:** Ensuring anonymous reviews protect student privacy while maintaining database foreign key integrity.  
  *Solution:* Stored the true `user_id` in the database for tracking ownership, but dynamically masked student names as 'Anonymous Student' when displaying publicly.

### Slide 11: Future Enhancements & Q&A
- Instructor reply system to respond directly to student suggestions.
- Sentiment analysis on student feedback comments.
- Integration with Jazan University Single Sign-On (SSO).
> *"Thank you for your time and guidance, Dr. Abdulkarim. We are now delighted to answer any questions."*
