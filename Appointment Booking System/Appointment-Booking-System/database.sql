CREATE DATABASE IF NOT EXISTS appointment_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE appointment_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Booked', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Booked',
    attachment VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date_time (appointment_date, appointment_time),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, password, role) VALUES
('Academic Advisor', 'admin@jazanu.edu.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE name = VALUES(name), role = VALUES(role);

INSERT INTO appointments (customer_name, email, appointment_date, appointment_time, status) VALUES
('Ahmed Al-Qahtani', 'ahmed@student.jazanu.edu.sa', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:00:00', 'Booked'),
('Sara Al-Harbi', 'sara@student.jazanu.edu.sa', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 'Booked'),
('Khalid Al-Zahrani', 'khalid@student.jazanu.edu.sa', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '11:00:00', 'Booked'),
('Noura Al-Ghamdi', 'noura@student.jazanu.edu.sa', CURDATE(), '09:00:00', 'Completed'),
('Faisal Al-Otaibi', 'faisal@student.jazanu.edu.sa', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '13:00:00', 'Cancelled');
