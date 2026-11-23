-- Database Schema for MySQL / phpMyAdmin (InfinityFree / XAMPP)

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(150) NOT NULL,
    instructor_name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(30) NOT NULL,
    rating INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    comments TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    is_anonymous TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO courses (course_code, course_name, instructor_name) VALUES
('COMP-315', 'Web Programming', 'Dr. Khalid Al-Zahrani'),
('COMP-212', 'Object-Oriented Programming', 'Dr. Sara Al-Qahtani'),
('COMP-241', 'Database Systems', 'Dr. Noura Al-Shehri'),
('COMP-331', 'Computer Networks', 'Dr. Omar Al-Harbi');
