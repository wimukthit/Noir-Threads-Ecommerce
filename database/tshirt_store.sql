-- =========================================================
-- NOIR THREADS - T-Shirt Store Database
-- Import this file in phpMyAdmin (XAMPP) to set everything up
-- =========================================================

CREATE DATABASE IF NOT EXISTS tshirt_store;
USE tshirt_store;

-- ---------------------------------------------------------
-- Table: categories
-- ---------------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

INSERT INTO categories (name) VALUES
('Men'), ('Women'), ('Unisex'), ('Kids');

-- ---------------------------------------------------------
-- Table: products
-- ---------------------------------------------------------
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    sizes VARCHAR(50) DEFAULT 'S,M,L,XL',
    colors VARCHAR(100) DEFAULT 'Black,White',
    stock INT DEFAULT 50,
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO products (name, description, price, category_id, image, sizes, colors, stock, featured) VALUES
('Classic Crew Tee', 'A soft, everyday cotton tee with a relaxed fit. Perfect for layering or wearing on its own.', 2200.0, 1, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Classic+Crew+Tee', 'S,M,L,XL,XXL', 'Black,White,Grey', 60, 1),
('Oversized Street Tee', 'Boxy, oversized cut with dropped shoulders — built for the streetwear look.', 2800.0, 3, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Oversized+Street+Tee', 'S,M,L,XL', 'Black,Olive,Beige', 45, 1),
('Graphic Print Tee', 'Bold front graphic print on premium heavyweight cotton.', 3200.0, 1, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Graphic+Print+Tee', 'S,M,L,XL', 'White,Black', 30, 1),
('Ribbed Fitted Tee', 'A fitted, ribbed tee designed to flatter — soft stretch fabric.', 2600.0, 2, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Ribbed+Fitted+Tee', 'XS,S,M,L', 'White,Blush,Black', 40, 1),
('Vintage Wash Tee', 'Garment-dyed for a broken-in, vintage feel from day one.', 2900.0, 3, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=Vintage+Wash+Tee', 'S,M,L,XL', 'Sand,Faded Black', 35, 0),
('Pocket Tee', 'Simple chest-pocket tee, a wardrobe essential.', 2100.0, 1, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Pocket+Tee', 'S,M,L,XL,XXL', 'Navy,White,Black', 55, 0),
('Long Sleeve Tee', 'Lightweight long sleeve for cooler days, unisex fit.', 3000.0, 3, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Long+Sleeve+Tee', 'S,M,L,XL', 'Black,Grey', 25, 0),
('Kids Fun Print Tee', 'Playful print tee made with soft, skin-friendly cotton for kids.', 1800.0, 4, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Kids+Fun+Print+Tee', 'XS,S,M', 'Yellow,Sky Blue', 40, 0),
('Crop Tee', 'Cropped fit tee, high-waist friendly, soft cotton blend.', 2400.0, 2, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Crop+Tee', 'XS,S,M,L', 'White,Black,Pink', 30, 1),
('Heavyweight Tee', '240 GSM heavyweight cotton — structured and long-lasting.', 3400.0, 1, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Heavyweight+Tee', 'S,M,L,XL,XXL', 'Black,Olive', 20, 0),
('Tie-Dye Tee', 'Hand tie-dyed pattern, no two shirts are exactly alike.', 3100.0, 3, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=TieDye+Tee', 'S,M,L', 'Multicolor', 15, 0),
('Slogan Tee', 'Minimal slogan print across the chest, soft-hand print.', 2500.0, 2, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Slogan+Tee', 'XS,S,M,L,XL', 'White,Black', 28, 0),
('Waffle Knit Tee', 'Textured waffle-knit fabric for a subtle, elevated everyday look.', 2700.0, 1, 'https://placehold.co/600x700/1f2a44/f7f5f2?text=Waffle+Knit+Tee', 'S,M,L,XL', 'Navy,Charcoal', 32, 0),
('Acid Wash Tee', 'Distressed acid-wash finish, no two pieces look exactly alike.', 3300.0, 3, 'https://placehold.co/600x700/34495e/f7f5f2?text=Acid+Wash+Tee', 'S,M,L,XL', 'Denim Blue,Grey', 18, 1),
('Athletic Dri-Fit Tee', 'Moisture-wicking performance fabric, built for workouts and beyond.', 2900.0, 1, 'https://placehold.co/600x700/2e4034/f7f5f2?text=Athletic+DriFit+Tee', 'S,M,L,XL,XXL', 'Forest Green,Black', 38, 0),
('Lace Trim Tee', 'Soft jersey tee finished with a delicate lace trim at the sleeves.', 2600.0, 2, 'https://placehold.co/600x700/e3b8b0/1a1a1a?text=Lace+Trim+Tee', 'XS,S,M,L', 'Blush,White', 26, 0),
('Color Block Tee', 'Two-tone color block panels for a bold, modern silhouette.', 2950.0, 3, 'https://placehold.co/600x700/5c1f2e/f7f5f2?text=Color+Block+Tee', 'S,M,L,XL', 'Burgundy,Black', 22, 0),
('Striped Tee', 'Classic breton stripes on soft combed cotton.', 2300.0, 1, 'https://placehold.co/600x700/c9b28c/1a1a1a?text=Striped+Tee', 'S,M,L,XL', 'Sand,Navy Stripe', 34, 0),
('Kids Dino Print Tee', 'Fun dinosaur print tee in breathable, skin-friendly cotton.', 1950.0, 4, 'https://placehold.co/600x700/8fd3c0/1a1a1a?text=Kids+Dino+Print+Tee', 'XS,S,M', 'Mint,White', 30, 1),
('Kids Rainbow Stripe Tee', 'Bright rainbow stripe tee kids will love wearing on repeat.', 1900.0, 4, 'https://placehold.co/600x700/f4c542/1a1a1a?text=Kids+Rainbow+Stripe+Tee', 'XS,S,M', 'Yellow Multi,White', 28, 0);

-- ---------------------------------------------------------
-- Table: product_images (extra gallery photos per product)
-- The main/cover photo still lives in products.image;
-- these are the additional photos shown in the gallery.
-- ---------------------------------------------------------
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Sample gallery photos for every product (2 each: Back View + Detail)
INSERT INTO product_images (product_id, image_url, sort_order) VALUES
(1, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Classic+Crew+Tee+Back', 0),
(1, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Classic+Crew+Tee+Detail', 1),
(2, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Oversized+Street+Tee+Back', 0),
(2, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Oversized+Street+Tee+Detail', 1),
(3, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Graphic+Print+Tee+Back', 0),
(3, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Graphic+Print+Tee+Detail', 1),
(4, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Ribbed+Fitted+Tee+Back', 0),
(4, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Ribbed+Fitted+Tee+Detail', 1),
(5, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=Vintage+Wash+Tee+Back', 0),
(5, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=Vintage+Wash+Tee+Detail', 1),
(6, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Pocket+Tee+Back', 0),
(6, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Pocket+Tee+Detail', 1),
(7, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Long+Sleeve+Tee+Back', 0),
(7, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Long+Sleeve+Tee+Detail', 1),
(8, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Kids+Fun+Print+Tee+Back', 0),
(8, 'https://placehold.co/600x700/ff4e32/1a1a1a?text=Kids+Fun+Print+Tee+Detail', 1),
(9, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Crop+Tee+Back', 0),
(9, 'https://placehold.co/600x700/d8d4c8/1a1a1a?text=Crop+Tee+Detail', 1),
(10, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Heavyweight+Tee+Back', 0),
(10, 'https://placehold.co/600x700/1a1a1a/f7f5f2?text=Heavyweight+Tee+Detail', 1),
(11, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=TieDye+Tee+Back', 0),
(11, 'https://placehold.co/600x700/6b6a66/f7f5f2?text=TieDye+Tee+Detail', 1),
(12, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Slogan+Tee+Back', 0),
(12, 'https://placehold.co/600x700/2b2b2b/f7f5f2?text=Slogan+Tee+Detail', 1),
(13, 'https://placehold.co/600x700/1f2a44/f7f5f2?text=Waffle+Knit+Tee+Back', 0),
(13, 'https://placehold.co/600x700/1f2a44/f7f5f2?text=Waffle+Knit+Tee+Detail', 1),
(14, 'https://placehold.co/600x700/34495e/f7f5f2?text=Acid+Wash+Tee+Back', 0),
(14, 'https://placehold.co/600x700/34495e/f7f5f2?text=Acid+Wash+Tee+Detail', 1),
(15, 'https://placehold.co/600x700/2e4034/f7f5f2?text=Athletic+DriFit+Tee+Back', 0),
(15, 'https://placehold.co/600x700/2e4034/f7f5f2?text=Athletic+DriFit+Tee+Detail', 1),
(16, 'https://placehold.co/600x700/e3b8b0/1a1a1a?text=Lace+Trim+Tee+Back', 0),
(16, 'https://placehold.co/600x700/e3b8b0/1a1a1a?text=Lace+Trim+Tee+Detail', 1),
(17, 'https://placehold.co/600x700/5c1f2e/f7f5f2?text=Color+Block+Tee+Back', 0),
(17, 'https://placehold.co/600x700/5c1f2e/f7f5f2?text=Color+Block+Tee+Detail', 1),
(18, 'https://placehold.co/600x700/c9b28c/1a1a1a?text=Striped+Tee+Back', 0),
(18, 'https://placehold.co/600x700/c9b28c/1a1a1a?text=Striped+Tee+Detail', 1),
(19, 'https://placehold.co/600x700/8fd3c0/1a1a1a?text=Kids+Dino+Print+Tee+Back', 0),
(19, 'https://placehold.co/600x700/8fd3c0/1a1a1a?text=Kids+Dino+Print+Tee+Detail', 1),
(20, 'https://placehold.co/600x700/f4c542/1a1a1a?text=Kids+Rainbow+Stripe+Tee+Back', 0),
(20, 'https://placehold.co/600x700/f4c542/1a1a1a?text=Kids+Rainbow+Stripe+Tee+Detail', 1);

-- ---------------------------------------------------------
-- Table: users (customers)
-- ---------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table: orders
-- ---------------------------------------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Pending',
    coupon_code VARCHAR(30) NULL,
    discount DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ---------------------------------------------------------
-- Table: reviews
-- ---------------------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    user_name VARCHAR(100),
    rating TINYINT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Table: coupons
-- ---------------------------------------------------------
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE NOT NULL,
    discount_type ENUM('percent','fixed') DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    expiry_date DATE NULL
);

INSERT INTO coupons (code, discount_type, discount_value, active, expiry_date) VALUES
('WELCOME10', 'percent', 10, 1, NULL),
('FLAT500', 'fixed', 500, 1, NULL);

-- ---------------------------------------------------------
-- Table: order_items
-- ---------------------------------------------------------
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(100) NOT NULL,
    size VARCHAR(10),
    color VARCHAR(30),
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Table: admins
-- ---------------------------------------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Default admin login -> username: admin | password: admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2b$10$ySHRoD9dv6P0hZ0RNQMaTujRQ31MtgcEtoOdn.SbLGMiCir3jMDX6');

-- ---------------------------------------------------------
-- Table: contact_messages
-- ---------------------------------------------------------
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
