CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'customer'
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

-- Default Admin Account (Password: Admin@2026)
INSERT INTO users (name, email, password, role) VALUES
('Store Admin', 'admin@bookstore.com', '$2y$10$99jDtAJ2OdpGCoBKzMyeWucN0170VVoI4nOyvFC1ByYaFO1iGUemi', 'admin');

-- Initial Books
INSERT INTO books (title, author, category, price, quantity) VALUES
('Modern PHP Programming', 'Josh Lockhart', 'Programming', 48.00, 14),
('SQL & Relational Databases', 'Chris Date', 'Database', 62.00, 9),
('Web Application Architecture', 'Leon Shklar', 'Software Engineering', 54.00, 11),
('Network Security Essentials', 'William Stallings', 'Networks', 58.00, 7),
('Data Structures & Algorithms', 'Robert Sedgewick', 'Algorithms', 65.00, 12),
('Cloud Computing Principles', 'Thomas Erl', 'Cloud Computing', 52.00, 8);
