-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS mindful_spending;

-- Use the newly created database
USE mindful_spending;

-- Drop the table if it already exists (for resetting purposes)
DROP TABLE IF EXISTS users;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('User', 'Admin') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert an example admin user (you can remove this in production)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$examplehashedpassword...', 'Admin'); -- replace with a hashed password

-- Sample categories table (if you want to add spending categories for users)
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example data for categories
INSERT INTO categories (name, description) VALUES 
('Essentials', 'Basic living expenses'),
('Experiences', 'Spending on life experiences'),
('Investments', 'Spending on future growth');

-- Optional table for tracking expenses by users
DROP TABLE IF EXISTS expenses;

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Example data for expenses
INSERT INTO expenses (user_id, category_id, amount, description) VALUES
(1, 1, 100.00, 'Grocery shopping'),
(1, 2, 50.00, 'Movie night');

-- Optional: Add some default values or further constraints as needed
