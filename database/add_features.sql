-- =========================================================
-- NOIR THREADS - Feature Update Migration
-- If you already imported tshirt_store.sql before, run THIS
-- file in phpMyAdmin > Import to add the new features.
-- (If you're setting up fresh, just import the updated
-- tshirt_store.sql instead - it already includes all this.)
-- =========================================================
USE tshirt_store;

-- Product image gallery (extra photos beyond the main products.image)
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product reviews & ratings
CREATE TABLE IF NOT EXISTS reviews (
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

-- Coupons
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE NOT NULL,
    discount_type ENUM('percent','fixed') DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    expiry_date DATE NULL
);

INSERT IGNORE INTO coupons (code, discount_type, discount_value, active, expiry_date) VALUES
('WELCOME10', 'percent', 10, 1, NULL),
('FLAT500', 'fixed', 500, 1, NULL);

-- Track which coupon (if any) was used on an order
ALTER TABLE orders ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(30) NULL;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS discount DECIMAL(10,2) DEFAULT 0;

-- Contact form messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
