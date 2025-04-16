CREATE TABLE offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT,
    titre VARCHAR(255),
    description TEXT,
    type ENUM('stage', 'emploi'),
    localisation VARCHAR(100),
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES users(id)
);