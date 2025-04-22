-- Create database
CREATE DATABASE IF NOT EXISTS voting_system;
USE voting_system;

-- Create admin table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create voters table
CREATE TABLE voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    has_voted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create candidates table
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    party VARCHAR(100) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create votes table
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id VARCHAR(20),
    candidate_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES voters(voter_id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);

-- Insert sample admin
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Insert sample candidates
INSERT INTO candidates (name, party, image_url) VALUES
('John Smith', 'Progressive Party', 'https://images.pexels.com/photos/5669619/pexels-photo-5669619.jpeg?auto=compress&cs=tinysrgb&w=150'),
('Sarah Johnson', 'Conservative Party', 'https://images.pexels.com/photos/5792641/pexels-photo-5792641.jpeg?auto=compress&cs=tinysrgb&w=150'),
('Michael Brown', 'Liberal Party', 'https://images.pexels.com/photos/6325954/pexels-photo-6325954.jpeg?auto=compress&cs=tinysrgb&w=150'),
('Emily Davis', 'Independent', 'https://images.pexels.com/photos/8107217/pexels-photo-8107217.jpeg?auto=compress&cs=tinysrgb&w=150');

-- Insert sample voters
INSERT INTO voters (voter_id, name, password) VALUES
('V001', 'Alice Wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('V002', 'Bob Anderson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('V003', 'Carol Martinez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');