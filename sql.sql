CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password TEXT,
    role ENUM('candidat', 'entreprise'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
