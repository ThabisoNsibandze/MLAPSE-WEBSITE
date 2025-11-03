-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `ngo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ngo`;

-- Table structure for users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for donation
CREATE TABLE IF NOT EXISTS `donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `payment_id` varchar(255) DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample admin user (password: admin123)
-- Password is hashed using PHP password_hash()
INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@mlapse.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', current_timestamp())
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample posts
INSERT INTO `posts` (`user_id`, `title`, `content`, `created_at`) VALUES
(1, 'Welcome to MLAPSE Community', 'We are excited to launch our community platform where members can share stories, updates, and connect with each other. This is a space for empowerment, advocacy, and building inclusive communities together.', current_timestamp()),
(1, 'Upcoming Accessibility Workshop', 'Join us for our upcoming workshop on digital accessibility. We will cover web accessibility standards, assistive technologies, and how to make digital content more inclusive. Date and venue to be announced soon.', current_timestamp()),
(1, 'Thank You to Our Volunteers', 'We want to express our heartfelt gratitude to all our volunteers who dedicate their time and energy to make a difference in the lives of people with disabilities. Your support means everything to us!', current_timestamp())
ON DUPLICATE KEY UPDATE id=id;

-- Success message
SELECT 'Database setup completed successfully!' AS message;
SELECT 'Default admin credentials: email: admin@mlapse.org, password: admin123' AS credentials;