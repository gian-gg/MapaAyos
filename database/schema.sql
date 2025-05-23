-- Database Schema for mapaayosDB

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Users Table
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','official','admin') DEFAULT 'user',
  `hasProfilePic` tinyint(1) DEFAULT 0,
  `assignedBaranggay` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Reports Table
CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `baranggay` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','pending','resolved','denied') DEFAULT 'pending',
  `createdBy` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagePath` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `moderatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Baranggays Table
CREATE TABLE `baranggays` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `geojson` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Baranggay Info Table
CREATE TABLE `baranggayInfo` (
  `baranggayID` int(11) NOT NULL,
  `description` text DEFAULT 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
  `landArea` decimal(10,2) DEFAULT 0,
  `population` int(11) DEFAULT 0,
  `phone` varchar(20) DEFAULT '09123456789',
  `email` varchar(100) DEFAULT 'support@baranggay.com',
  `address` text DEFAULT '1234 Baranggay St, City, Country',
  `operating_hours_weekdays` varchar(100) DEFAULT '8:00 AM - 5:00 PM',
  `operating_hours_saturday` varchar(100) DEFAULT '6:00 AM - 12:00 PM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_assigned_baranggay` (`assignedBaranggay`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reports_user` (`createdBy`),
  ADD KEY `fk_moderatedBy` (`moderatedBy`);

ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `baranggays`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `baranggayInfo`
  ADD PRIMARY KEY (`baranggayID`);

-- Foreign Keys
ALTER TABLE `users`
  ADD CONSTRAINT `fk_assigned_baranggay` FOREIGN KEY (`assignedBaranggay`) REFERENCES `baranggays` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `reports`
  ADD CONSTRAINT `fk_moderatedBy` FOREIGN KEY (`moderatedBy`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reports_user` FOREIGN KEY (`createdBy`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `baranggayInfo`
  ADD CONSTRAINT `baranggayinfo_ibfk_1` FOREIGN KEY (`baranggayID`) REFERENCES `baranggays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;