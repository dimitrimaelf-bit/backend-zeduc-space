-- Mon Miam Miam — base de données MVP
-- MySQL 8.0+

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS cookie_consents;
DROP TABLE IF EXISTS app_settings;
DROP TABLE IF EXISTS claims;
DROP TABLE IF EXISTS loyalty_transactions;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS promotions;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS menu_categories;
DROP TABLE IF EXISTS referrals;
DROP TABLE IF EXISTS referral_codes;
DROP TABLE IF EXISTS loyalty_accounts;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    label VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

INSERT INTO roles (code, label) VALUES
    ('student', 'Étudiant'),
    ('employee', 'Employé'),
    ('manager', 'Gérant'),
    ('admin', 'Administrateur');

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(30) NULL,
    password_hash VARCHAR(255) NOT NULL,
    delivery_address TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id),
    INDEX idx_users_role_active (role_id, is_active)
) ENGINE=InnoDB;

CREATE TABLE loyalty_accounts (
    user_id BIGINT UNSIGNED PRIMARY KEY,
    balance_points INT UNSIGNED NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_loyalty_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE referral_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    code VARCHAR(30) NOT NULL UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_referral_code_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE referrals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_user_id BIGINT UNSIGNED NOT NULL,
    referred_user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    code_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'rewarded', 'cancelled') NOT NULL DEFAULT 'pending',
    rewarded_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_referrals_referrer FOREIGN KEY (referrer_user_id) REFERENCES users(id),
    CONSTRAINT fk_referrals_referred FOREIGN KEY (referred_user_id) REFERENCES users(id),
    CONSTRAINT fk_referrals_code FOREIGN KEY (code_id) REFERENCES referral_codes(id),
    CONSTRAINT chk_referrals_not_self CHECK (referrer_user_id <> referred_user_id),
    INDEX idx_referrals_referrer (referrer_user_id),
    INDEX idx_referrals_status (status)
) ENGINE=InnoDB;

CREATE TABLE menu_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    sort_order INT NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE menu_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    price_fcfa INT UNSIGNED NOT NULL,
    image_url VARCHAR(500) NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    is_daily_special BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_menu_item_category FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE SET NULL,
    INDEX idx_menu_items_available (is_available, category_id)
) ENGINE=InnoDB;

CREATE TABLE promotions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_promotion_period CHECK (ends_at IS NULL OR starts_at IS NULL OR ends_at > starts_at)
) ENGINE=InnoDB;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(30) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    service_mode ENUM('delivery', 'on_site') NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    delivery_address TEXT NULL,
    arrival_at DATETIME NULL,
    customer_note TEXT NULL,
    subtotal_fcfa INT UNSIGNED NOT NULL DEFAULT 0,
    discount_fcfa INT UNSIGNED NOT NULL DEFAULT 0,
    delivery_fee_fcfa INT UNSIGNED NOT NULL DEFAULT 0,
    total_fcfa INT UNSIGNED NOT NULL DEFAULT 0,
    loyalty_points_used INT UNSIGNED NOT NULL DEFAULT 0,
    loyalty_points_earned INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_delivery_address CHECK (service_mode = 'on_site' OR delivery_address IS NOT NULL),
    INDEX idx_orders_user_created (user_id, created_at),
    INDEX idx_orders_status_created (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    menu_item_id BIGINT UNSIGNED NULL,
    item_name VARCHAR(150) NOT NULL,
    unit_price_fcfa INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    line_total_fcfa INT UNSIGNED NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_menu_item FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL,
    CONSTRAINT chk_order_item_quantity CHECK (quantity > 0),
    INDEX idx_order_items_order (order_id)
) ENGINE=InnoDB;

CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL UNIQUE,
    provider VARCHAR(40) NOT NULL,
    provider_transaction_id VARCHAR(150) NULL,
    method ENUM('mobile_money', 'card', 'cash', 'test') NOT NULL,
    status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    amount_fcfa INT UNSIGNED NOT NULL,
    paid_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    UNIQUE KEY uq_provider_transaction (provider, provider_transaction_id),
    INDEX idx_payments_status (status)
) ENGINE=InnoDB;

CREATE TABLE loyalty_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NULL,
    type ENUM('earn', 'redeem', 'referral', 'adjust', 'expire') NOT NULL,
    points INT NOT NULL,
    description VARCHAR(255) NULL,
    expires_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_loyalty_tx_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_loyalty_tx_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    CONSTRAINT chk_loyalty_points_nonzero CHECK (points <> 0),
    INDEX idx_loyalty_tx_user_created (user_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE claims (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    subject VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'rejected') NOT NULL DEFAULT 'open',
    response TEXT NULL,
    handled_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_claim_order FOREIGN KEY (order_id) REFERENCES orders(id),
    CONSTRAINT fk_claim_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_claim_handler FOREIGN KEY (handled_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_claims_status_created (status, created_at),
    INDEX idx_claims_user_created (user_id, created_at)
) ENGINE=InnoDB;

CREATE TABLE app_settings (
    setting_key VARCHAR(80) PRIMARY KEY,
    setting_value TEXT NOT NULL,
    updated_by BIGINT UNSIGNED NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_setting_user FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO app_settings (setting_key, setting_value) VALUES
    ('loyalty_fcfa_per_point', '1000'),
    ('loyalty_points_per_1000_fcfa_discount', '15'),
    ('loyalty_discount_fcfa', '1000'),
    ('restaurant_open', '08:00'),
    ('restaurant_close', '22:00');

CREATE TABLE cookie_consents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    session_id VARCHAR(120) NULL,
    consented BOOLEAN NOT NULL,
    policy_version VARCHAR(30) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cookie_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_cookie_subject CHECK (user_id IS NOT NULL OR session_id IS NOT NULL),
    INDEX idx_cookie_user_created (user_id, created_at)
) ENGINE=InnoDB;

DELIMITER $$

CREATE TRIGGER trg_create_loyalty_account
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO loyalty_accounts (user_id, balance_points) VALUES (NEW.id, 0);
END$$

DELIMITER ;

