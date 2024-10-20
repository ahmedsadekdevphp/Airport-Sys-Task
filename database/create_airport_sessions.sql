CREATE TABLE airport_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(255)  NULL,
    user_id INT  NULL,
    action VARCHAR(255) NOT NULL,
    count INT DEFAULT 1,
    last_action TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ip (ip),
    FOREIGN KEY (user_id) REFERENCES airport_users(id)

);
