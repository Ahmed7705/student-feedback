CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    quantity INT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL
);

INSERT INTO books (title, author, category, price, quantity) VALUES
('PHP & MySQL Web Development', 'Luke Welling', 'Programming', 45.00, 12),
('Database System Concepts', 'Abraham Silberschatz', 'Database', 60.00, 8),
('Clean Code Handbook', 'Robert Martin', 'Software Engineering', 50.00, 15),
('Computer Networking', 'James Kurose', 'Networks', 55.00, 10);
