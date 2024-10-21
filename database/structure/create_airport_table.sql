CREATE TABLE IF NOT EXISTS airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    airport_name VARCHAR(255) NOT NULL,
    airport_code VARCHAR(10) NOT NULL,
    airport_city VARCHAR(255) NOT NULL,
    country_id INT NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES airport_users(id),
    FOREIGN KEY (country_id) REFERENCES airport_countries(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
