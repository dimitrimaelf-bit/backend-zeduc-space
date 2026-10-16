CREATE TABLE users (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role            ENUM('student','employee','manager','admin') NOT NULL DEFAULT 'student',
  full_name       VARCHAR(100) NOT NULL,
  email           VARCHAR(190) NOT NULL UNIQUE,
  phone           VARCHAR(20),
  password_hash   VARCHAR(255) NOT NULL,
  address         VARCHAR(255),                 -- default delivery location
  referral_code   VARCHAR(12) UNIQUE,           -- generated for students
  points_balance  INT NOT NULL DEFAULT 0,       -- cache of loyalty_ledger
  is_active       BOOLEAN NOT NULL DEFAULT TRUE,
  created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category      VARCHAR(50),                    -- plat, boisson, ...
  name          VARCHAR(120) NOT NULL,
  description   TEXT,
  price_fcfa    INT UNSIGNED NOT NULL,
  image_url     VARCHAR(255),
  is_available  BOOLEAN NOT NULL DEFAULT TRUE,  -- "plat épuisé"
  daily_date    DATE DEFAULT (CURDATE())  -- "menu du jour"
);

CREATE TABLE promotions (                       -- promos, events, mini-jeux
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type         ENUM('promo','event','game') NOT NULL,
  title        VARCHAR(150) NOT NULL,
  description  TEXT,
  poster_url   VARCHAR(255),
  starts_at    DATETIME NOT NULL,
  ends_at      DATETIME NOT NULL,
  is_active    BOOLEAN NOT NULL DEFAULT TRUE,
  INDEX idx_promo_dates (starts_at, ends_at)
);

CREATE TABLE orders (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id          INT UNSIGNED NOT NULL,
  fulfillment      ENUM('dine_in','delivery') NOT NULL,
  arrival_time     DATETIME NULL,                   -- dine_in
  delivery_address VARCHAR(255) NULL,           -- delivery
  status           ENUM('pending','confirmed','preparing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending',
  subtotal_fcfa    INT UNSIGNED NOT NULL,
  discount_fcfa    INT UNSIGNED NOT NULL DEFAULT 0,
  total_fcfa       INT UNSIGNED NOT NULL,
  points_used      INT UNSIGNED NOT NULL DEFAULT 0,
  points_earned    INT UNSIGNED NOT NULL DEFAULT 0,
  comment          TEXT NULL,                   -- left after delivery
  created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_orders_created (created_at),
  INDEX idx_orders_user (user_id, created_at),
  INDEX idx_orders_status (status)
);

CREATE TABLE order_items (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id        INT UNSIGNED NOT NULL,
  menu_item_id    INT UNSIGNED NULL,            -- NULL if item deleted later
  name_snapshot   VARCHAR(120) NOT NULL,
  unit_price_fcfa INT UNSIGNED NOT NULL,
  quantity        SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
);

CREATE TABLE payments (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id      INT UNSIGNED NOT NULL,
  provider      VARCHAR(30) NOT NULL,           -- cinetpay, stripe, ...
  provider_ref  VARCHAR(100) UNIQUE,            -- transaction id from aggregator
  amount_fcfa   INT UNSIGNED NOT NULL,
  status        ENUM('pending','success','failed','refunded') NOT NULL,
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE loyalty_ledger (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  order_id    INT UNSIGNED NULL,
  points      INT NOT NULL,                     -- positive = earned, negative = spent/expired
  reason      ENUM('order','redeem','referral_reward','manual','expiration') NOT NULL,
  expires_at  DATETIME NULL,                    -- set on earned points (e.g. +12 months)
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  INDEX idx_ledger_user (user_id),
  INDEX idx_ledger_expiry (expires_at)
);

CREATE TABLE referrals (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  referrer_id  INT UNSIGNED NOT NULL,
  referee_id   INT UNSIGNED NOT NULL UNIQUE,    -- one referrer per student
  status       ENUM('pending','rewarded') NOT NULL DEFAULT 'pending',
  rewarded_at  DATETIME NULL,
  created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (referrer_id) REFERENCES users(id),
  FOREIGN KEY (referee_id) REFERENCES users(id)
);

CREATE TABLE complaints (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  order_id    INT UNSIGNED NULL,
  subject     VARCHAR(150) NOT NULL,
  message     TEXT NOT NULL,
  status      ENUM('open','answered','closed') NOT NULL DEFAULT 'open',
  response    TEXT NULL,
  handled_by  INT UNSIGNED NULL,                -- employee/manager who answered
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (handled_by) REFERENCES users(id)
);

CREATE TABLE settings (                         -- opening hours, points rate, expiry, ...
  `key`  VARCHAR(60) PRIMARY KEY,
  value  TEXT NOT NULL
);