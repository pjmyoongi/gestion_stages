CREATE TABLE candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidat_id INT,
    offre_id INT,
    date_postulation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (candidat_id) REFERENCES users(id),
    FOREIGN KEY (offre_id) REFERENCES offres(id)
);
