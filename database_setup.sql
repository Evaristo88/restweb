DROP DATABASE IF EXISTS restweb;
-- Create the database
CREATE DATABASE IF NOT EXISTS restweb;
USE restweb;
-- Create tables
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id),
    total_amount DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'preparing', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS categories (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(256) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS menu_items (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    FOREIGN KEY(category_id) REFERENCES categories(id),
    title VARCHAR(50) NOT NULL,
    description VARCHAR(256) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS order_items (
    order_id INT,
    menu_item_id INT,
    PRIMARY KEY(order_id, menu_item_id),
    quantity INT NOT NULL
);
CREATE TABLE IF NOT EXISTS cart (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id),
    menu_item_id INT NOT NULL,
    FOREIGN KEY(menu_item_id) REFERENCES menu_items(id),
    quantity INT NOT NULL,
    image VARCHAR(255)
);
-- Insert admin user
INSERT INTO users (first_name, last_name, email, password, role)
VALUES (
        'veda',
        'ndowera',
        'veda@gmail.com',
        'veda',
        'admin'
    );
-- Insert sample users
INSERT INTO users (first_name, last_name, email, password, role)
VALUES (
        'andrew',
        'maina',
        'andrew@gmail.com',
        'andrew',
        'customer'
    );
INSERT INTO users (first_name, last_name, email, password, role)
VALUES (
        'tom',
        'otieno',
        'tom@gmail.com',
        'tom',
        'customer'
    );
INSERT INTO users (first_name, last_name, email, password, role)
VALUES (
        'elvis',
        'kimani',
        'elvis@gmail.com',
        'elvis',
        'customer'
    );
INSERT INTO users (first_name, last_name, email, password, role)
VALUES (
        'janet',
        'mbugua',
        'janet@gmail.com',
        'janet',
        'customer'
    );
-- Insert categories
INSERT INTO categories (title, description)
VALUES (
        'beverage',
        'All types of drinks'
    );
INSERT INTO categories (title, description)
VALUES (
        'food',
        'All types of food'
    );
-- Insert menu items
INSERT INTO menu_items (category_id, title, description, price, featured)
VALUES (
        1,
        'soda',
        'All types of soda. Ask for available flavours',
        150,
        TRUE
    );
INSERT INTO menu_items (category_id, title, description, price, featured)
VALUES (
        1,
        'juice',
        'All types of juice. Ask for available flavours',
        100,
        TRUE
    );
INSERT INTO menu_items (category_id, title, description, price, featured)
VALUES (
        2,
        'Grilled half chicken',
        'Grilled half chicken served with a choice of fries, rice or potato wedges',
        300,
        TRUE
    );
INSERT INTO menu_items (category_id, title, description, price, featured)
VALUES (
        2,
        'Beef stew',
        'Beef stew served with rice',
        250,
        TRUE
    );
INSERT INTO menu_items (category_id, title, description, price, featured)
VALUES (
        2,
        'Pilau',
        'Pilau served with kachumbari',
        200,
        TRUE
    );