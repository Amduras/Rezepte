-- Sicherstellen, dass Emojis & Sonderzeichen korrekt gespeichert werden
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. USER-VERWALTUNG
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'contributor', 'admin') NOT NULL DEFAULT 'user',
  `status` ENUM('active', 'banned', 'pending') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft-Delete: != NULL bedeutet gelöscht',
  UNIQUE KEY `idx_users_email` (`email`),
  UNIQUE KEY `idx_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. REZEPTE (Flexibel für User-Eingaben)
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `author_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `prep_time` INT UNSIGNED NULL COMMENT 'Zubereitungszeit in Minuten',
  `cook_time` INT UNSIGNED NULL COMMENT 'Koch-/Backzeit in Minuten',
  `servings` INT UNSIGNED NULL COMMENT 'Anzahl Portionen',
  `difficulty` ENUM('easy', 'medium', 'hard') NULL,
  `image_url` VARCHAR(500) NULL,
  `tags` JSON NULL COMMENT 'Flexibel: ["vegan", "unter-30-min", "italienisch"]',
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  KEY `idx_recipes_author` (`author_id`),
  KEY `idx_recipes_status` (`status`),
  KEY `idx_recipes_created` (`created_at`),
  CONSTRAINT `fk_recipes_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ZUTATEN (Frei formatierbar für maximale User-Flexibilität)
CREATE TABLE IF NOT EXISTS `recipe_ingredients` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `recipe_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `quantity` VARCHAR(50) NULL COMMENT 'Flexibel: "2", "1/2", "1 Prise", "nach Belieben"',
  `unit` VARCHAR(30) NULL COMMENT 'z.B. "g", "ml", "Stk", "Prise", "Bund"',
  `note` TEXT NULL COMMENT 'Zusatzinfo: "gewürfelt", "zimmerwarm", "gerieben"',
  CONSTRAINT `fk_ingredients_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE,
  INDEX `idx_ingredients_recipe` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ZUBEREITUNGSSCHRITTE
CREATE TABLE IF NOT EXISTS `recipe_steps` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `recipe_id` BIGINT UNSIGNED NOT NULL,
  `step_number` INT UNSIGNED NOT NULL,
  `instruction` TEXT NOT NULL,
  CONSTRAINT `fk_steps_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `uk_recipe_step` (`recipe_id`, `step_number`),
  INDEX `idx_steps_recipe` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. FAVORITEN (M:N zwischen User & Rezept)
CREATE TABLE IF NOT EXISTS `user_favorites` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `recipe_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `recipe_id`),
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
