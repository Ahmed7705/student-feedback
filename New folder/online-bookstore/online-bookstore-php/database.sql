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
('Modern PHP Programming', 'Josh Lockhart', 'Programming', 48.00, 14),
('SQL & Relational Databases', 'Chris Date', 'Database', 62.00, 9),
('Web Application Architecture', 'Leon Shklar', 'Software Engineering', 54.00, 11),
('Network Security Essentials', 'William Stallings', 'Networks', 58.00, 7);
