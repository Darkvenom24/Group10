-- ============================================================================
-- schema.sql - Complete MySQL Database Schema with Realistic Seed Data
-- Group 10 — Government MCA College, Maninagar (GMCA)
-- Academic Year 2025–2026 | Course: Web Technology Practicals (WTP)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `gmca_group10_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gmca_group10_db`;

-- 1. Users Table (Sign In & Sign Up Form Integration)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `enrollment` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL UNIQUE,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(30) DEFAULT 'Student',
    `avatar` VARCHAR(255) DEFAULT 'vaibhav.jpeg',
    `last_login` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Login Activity Table (Live Audit for every Sign In)
CREATE TABLE IF NOT EXISTS `login_activity` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_email` VARCHAR(120) NOT NULL,
    `enrollment` VARCHAR(20) NOT NULL,
    `ip_address` VARCHAR(50) DEFAULT '127.0.0.1',
    `user_agent` VARCHAR(255) DEFAULT 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
    `status` VARCHAR(20) DEFAULT 'Success',
    `login_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Railway Bookings Table (Practical 3 & 12 Form Integration)
CREATE TABLE IF NOT EXISTS `railway_bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `pnr` VARCHAR(15) NOT NULL UNIQUE,
    `passenger_name` VARCHAR(100) NOT NULL,
    `train_name` VARCHAR(150) NOT NULL,
    `travel_class` VARCHAR(20) NOT NULL,
    `passengers` INT DEFAULT 1,
    `journey_date` DATE NOT NULL,
    `total_fare` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Confirmed',
    `user_enrollment` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Practical 7 Students Academic Information & Project Records
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

-- 5. User Profiles Table (Practical 11.3 Form Submissions)
CREATE TABLE IF NOT EXISTS `user_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `enrollment` VARCHAR(20) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `course` VARCHAR(100) NOT NULL,
    `bio` TEXT NOT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. SQL Query Logs Table (Live Console Audit Trail)
CREATE TABLE IF NOT EXISTS `sql_query_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `query` TEXT NOT NULL,
    `action` VARCHAR(30) DEFAULT 'QUERY',
    `execution_time_ms` DECIMAL(10,2) DEFAULT 0.00,
    `status` VARCHAR(20) DEFAULT 'Success',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- SEED DATA: Realistic Indian-Context Records for Evaluation
-- ============================================================================

-- Seed 1: Users
INSERT INTO `users` (`enrollment`, `name`, `email`, `username`, `password`, `role`, `avatar`, `last_login`) VALUES
('26GMCA52', 'Vaibhav Senjaliya', 'vaibhav.senjaliya@gmca.ac.in', 'vaibhav52', 'password123', 'Developer', 'vaibhav.jpeg', '2026-10-26 11:45:00'),
('26GMCA61', 'Ridham Bambhaniya', 'ridham.bambhaniya@gmca.ac.in', 'ridham61', 'password123', 'Team Lead', 'ridham.jpeg', '2026-10-26 12:10:00'),
('26GMCA33', 'Riya Thakkar', 'riya.thakkar@gmca.ac.in', 'riya33', 'password123', 'Core Member', 'riya.jpeg', '2026-10-26 10:20:00'),
('26GMCA01', 'Aarav Patel', 'aarav.patel@gmca.ac.in', 'aarav01', 'password123', 'Student Member', 'temp.jpeg', '2026-10-25 16:30:00'),
('26GMCA15', 'Priya Sharma', 'priya.sharma@gmca.ac.in', 'priya15', 'password123', 'Student Member', 'temp.jpeg', '2026-10-25 14:15:00'),
('26GMCA40', 'Rohan Mehta', 'rohan.mehta@gmca.ac.in', 'rohan40', 'password123', 'Student Member', 'temp.jpeg', '2026-10-24 09:50:00')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed 2: Login Activity
INSERT INTO `login_activity` (`user_email`, `enrollment`, `ip_address`, `user_agent`, `status`, `login_time`) VALUES
('ridham.bambhaniya@gmca.ac.in', '26GMCA61', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/129.0', 'Success', '2026-10-26 12:10:00'),
('vaibhav.senjaliya@gmca.ac.in', '26GMCA52', '192.168.1.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/129.0', 'Success', '2026-10-26 11:45:00'),
('riya.thakkar@gmca.ac.in', '26GMCA33', '192.168.1.108', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', 'Success', '2026-10-26 10:20:00'),
('aarav.patel@gmca.ac.in', '26GMCA01', '10.0.4.15', 'Mozilla/5.0 (Linux; Android 14; Pixel 8)', 'Success', '2026-10-25 16:30:00'),
('priya.sharma@gmca.ac.in', '26GMCA15', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/131.0', 'Success', '2026-10-25 14:15:00'),
('rohan.mehta@gmca.ac.in', '26GMCA40', '192.168.1.112', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/129.0', 'Success', '2026-10-24 09:50:00');

-- Seed 3: Railway Bookings
INSERT INTO `railway_bookings` (`pnr`, `passenger_name`, `train_name`, `travel_class`, `passengers`, `journey_date`, `total_fare`, `status`, `user_enrollment`, `created_at`) VALUES
('PNR748291', 'Ridham Bambhaniya', '12957 - Ahmedabad Swarna Jayanti Rajdhani', '3A', 2, '2026-11-05', 2400.00, 'Confirmed', '26GMCA61', '2026-10-20 10:15:00'),
('PNR831902', 'Ridham Bambhaniya', '22953 - Gujarat SF Express', 'SL', 1, '2026-11-12', 400.00, 'Confirmed', '26GMCA61', '2026-10-21 14:20:00'),
('PNR612944', 'Riya Thakkar', '12009 - Mumbai Central Vande Bharat Express', '2A', 1, '2026-11-08', 1800.00, 'Confirmed', '26GMCA33', '2026-10-22 11:30:00'),
('PNR930182', 'Vaibhav Senjaliya', '12957 - Ahmedabad Swarna Jayanti Rajdhani', '2A', 1, '2026-11-15', 1800.00, 'Confirmed', '26GMCA52', '2026-10-23 09:40:00'),
('PNR441920', 'Vaibhav Senjaliya', '19038 - Avadh Express', 'SL', 2, '2026-11-18', 700.00, 'Confirmed', '26GMCA52', '2026-10-24 16:50:00'),
('PNR552194', 'Aarav Patel', '12932 - Ahmedabad Double Decker Express', 'CC', 2, '2026-11-20', 950.00, 'Confirmed', '26GMCA01', '2026-10-25 11:10:00')
ON DUPLICATE KEY UPDATE `total_fare`=VALUES(`total_fare`);

-- Seed 4: Practical 7 Students
INSERT INTO `practical7_students` (`enrollment`, `full_name`, `email`, `phone`, `department`, `semester`, `gender`, `dob`, `skills`, `project_title`, `project_desc`, `address`, `status`) VALUES
('26GMCA52', 'Vaibhav Senjaliya', 'vaibhav.senjaliya@gmca.ac.in', '9876543210', 'Master of Computer Applications (MCA)', 'Semester 1', 'Male', '2003-04-12', 'PHP, MySQL, JavaScript, HTML5, CSS3', 'Automated Railway Reservation & Dynamic Pricing Engine', 'Built an interactive train ticket booking and fare calculation web application with persistent local dataset and fare rules.', 'Ahmedabad, Gujarat', 'Approved'),
('26GMCA61', 'Ridham Bambhaniya', 'ridham.bambhaniya@gmca.ac.in', '9825012345', 'Master of Computer Applications (MCA)', 'Semester 1', 'Male', '2002-11-20', 'PHP, MySQL, HTML5, CSS3, Python', 'Institutional Web Portal & Practical Catalog System', 'Developed multi-tier responsive institutional portal architecture demonstrating academic practical catalogs and member profiles.', 'Maninagar, Ahmedabad, Gujarat', 'Approved'),
('26GMCA33', 'Riya Thakkar', 'riya.thakkar@gmca.ac.in', '9898711223', 'Master of Computer Applications (MCA)', 'Semester 1', 'Female', '2003-08-19', 'PHP, JavaScript, HTML5, CSS3', 'Real-time Expression Evaluator & Keyboard Interface', 'Engineered arithmetic parser with event-driven keyboard shortcuts, error handling and dynamic layout stacking.', 'Satellite, Ahmedabad, Gujarat', 'Approved'),
('26GMCA01', 'Aarav Patel', 'aarav.patel@gmca.ac.in', '9879012345', 'Master of Computer Applications (MCA)', 'Semester 1', 'Male', '2003-01-15', 'AngularJS, PHP, SQL', 'Smart Library Book Management & Fine Computation', 'Web system with book barcode tracking and overdue fine calculation.', 'Naranpura, Ahmedabad', 'Submitted'),
('26GMCA15', 'Priya Sharma', 'priya.sharma@gmca.ac.in', '9824054321', 'Master of Computer Applications (MCA)', 'Semester 1', 'Female', '2003-06-25', 'HTML5, CSS3, JavaScript', 'Online Clinic Appointment Scheduler', 'Patient token reservation portal with doctor specialization queues.', 'Vastrapur, Ahmedabad', 'Approved'),
('26GMCA40', 'Rohan Mehta', 'rohan.mehta@gmca.ac.in', '9712098765', 'Master of Computer Applications (MCA)', 'Semester 1', 'Male', '2002-09-10', 'PHP, MySQL, Bootstrap', 'Campus Placement Portal & Resume Parser', 'Company hiring board and applicant profile screening portal.', 'Navrangpura, Ahmedabad', 'Submitted')
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`);

-- Seed 5: User Profiles
INSERT INTO `user_profiles` (`enrollment`, `full_name`, `email`, `course`, `bio`, `submitted_at`) VALUES
('26GMCA52', 'Vaibhav Senjaliya', 'vaibhav.senjaliya@gmca.ac.in', 'MCA Semester 1', 'Passionate about web architectures, full stack software development, and relational database systems.', '2026-10-26 10:00:00'),
('26GMCA61', 'Ridham Bambhaniya', 'ridham.bambhaniya@gmca.ac.in', 'MCA Semester 1', 'Team Lead exploring responsive design systems, semantic HTML5, and high-performance serverless backends.', '2026-10-26 10:05:00'),
('26GMCA33', 'Riya Thakkar', 'riya.thakkar@gmca.ac.in', 'MCA Semester 1', 'Focused on frontend user experiences, interactive state management, and real-time form evaluation.', '2026-10-26 10:10:00'),
('26GMCA01', 'Aarav Patel', 'aarav.patel@gmca.ac.in', 'MCA Semester 1', 'Exploring AngularJS routing, client-side caching, and MySQL database indexing.', '2026-10-25 15:30:00'),
('26GMCA15', 'Priya Sharma', 'priya.sharma@gmca.ac.in', 'MCA Semester 1', 'Dedicated to building accessible web portals with modern web technology standards.', '2026-10-25 16:45:00');

-- Seed 6: SQL Query Logs
INSERT INTO `sql_query_logs` (`query`, `action`, `execution_time_ms`, `status`, `created_at`) VALUES
('SELECT * FROM `users` ORDER BY `id` ASC;', 'SELECT', 0.85, 'Success', '2026-10-26 12:00:00'),
('SELECT * FROM `railway_bookings` WHERE `status` = \'Confirmed\';', 'SELECT', 1.20, 'Success', '2026-10-26 12:05:00'),
('SELECT * FROM `practical7_students` WHERE `semester` = \'Semester 1\';', 'SELECT', 0.95, 'Success', '2026-10-26 12:12:00'),
('SHOW TABLE STATUS FROM `gmca_group10_db`;', 'STATUS', 2.10, 'Success', '2026-10-26 12:20:00'),
('SELECT DATABASE(), VERSION(), CURRENT_USER();', 'SYSTEM', 0.40, 'Success', '2026-10-26 12:25:00');
