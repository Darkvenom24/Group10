-- ============================================================================
-- schema.sql - MySQL Database Schema for Practical 12 & Practical 7
-- Group 10 — Government MCA College, Maninagar (GMCA)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `gmca_group10_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gmca_group10_db`;

-- 1. Users Table (Used for Index Page Registration & Login)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `enrollment` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL UNIQUE,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(30) DEFAULT 'Student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Practical 7 Students Academic Information & Project Records Table
CREATE TABLE IF NOT EXISTS `practical7_students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `enrollment` VARCHAR(20) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `phone` VARCHAR(15) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    `gender` VARCHAR(15) DEFAULT 'Not Specified',
    `dob` DATE NULL,
    `skills` TEXT NOT NULL,
    `project_title` VARCHAR(255) NOT NULL,
    `project_desc` TEXT NULL,
    `address` TEXT NULL,
    `status` VARCHAR(30) DEFAULT 'Submitted',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_enrollment` (`enrollment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Sample Records for Group 10
INSERT INTO `users` (`enrollment`, `name`, `email`, `username`, `password`, `role`) VALUES
('26GMCA52', 'Vaibhav Senjaliya', 'vaibhav.senjaliya@gmca.ac.in', 'vaibhav52', 'password123', 'Developer'),
('26GMCA61', 'Ridham Bambhaniya', 'ridham.bambhaniya@gmca.ac.in', 'ridham61', 'password123', 'Team Lead'),
('26GMCA33', 'Riya Thakkar', 'riya.thakkar@gmca.ac.in', 'riya33', 'password123', 'Core Member')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `practical7_students` (`enrollment`, `full_name`, `email`, `phone`, `department`, `semester`, `gender`, `dob`, `skills`, `project_title`, `project_desc`, `address`) VALUES
('26GMCA52', 'Vaibhav Senjaliya', 'vaibhav.senjaliya@gmca.ac.in', '9876543210', 'Master of Computer Applications (MCA)', 'Semester 2', 'Male', '2003-04-12', 'PHP, MySQL, JavaScript, HTML5, CSS3', 'Automated Railway Reservation & Dynamic Pricing Engine', 'Built an interactive train ticket booking and fare calculation web application with persistent local dataset and fare rules.', 'Ahmedabad, Gujarat'),
('26GMCA61', 'Ridham Bambhaniya', 'ridham.bambhaniya@gmca.ac.in', '9825012345', 'Master of Computer Applications (MCA)', 'Semester 2', 'Male', '2002-11-20', 'PHP, MySQL, HTML5, CSS3, Python', 'Institutional Web Portal & Practical Catalog System', 'Developed multi-tier responsive institutional portal architecture demonstrating academic practical catalogs and member profiles.', 'Maninagar, Ahmedabad, Gujarat'),
('26GMCA33', 'Riya Thakkar', 'riya.thakkar@gmca.ac.in', '9898711223', 'Master of Computer Applications (MCA)', 'Semester 2', 'Female', '2003-08-19', 'PHP, JavaScript, HTML5, CSS3', 'Real-time Expression Evaluator & Keyboard Interface', 'Engineered arithmetic parser with event-driven keyboard shortcuts, error handling and dynamic layout stacking.', 'Satellite, Ahmedabad, Gujarat')
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`);
