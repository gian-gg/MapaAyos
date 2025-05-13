-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 13, 2025 at 02:49 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mapaayosDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `createdAt`, `role`, `hasProfilePic`, `assignedBaranggay`) VALUES
(5, 'Juan', 'Dela Cruz', 'juan@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 1),
(6, 'Ana', 'Santos', 'ana@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 1, 1),
(7, 'Carlos', 'Torres', 'carlos@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 1),
(8, 'Maria', 'Reyes', 'maria@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 2),
(9, 'Leo', 'Gutierrez', 'leo@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 2),
(10, 'Ella', 'Navarro', 'ella@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 2),
(11, 'Jose', 'Lopez', 'jose@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 3),
(12, 'Grace', 'Lim', 'grace@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 3),
(13, 'Mark', 'Rivera', 'mark@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:26:12', 'official', 0, 3),
(17, 'Liam', 'Garcia', 'liam@user.com', '$2y$10$xoiJILSJbUgOYPZHXwEQIeITOfuR/ivmUeJrEBubK7IR1L54ksDqe', '2025-05-07 01:30:35', 'user', 1, NULL),
(18, 'Chloe', 'Morales', 'chloe@user.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:30:35', 'user', 0, NULL),
(19, 'Noahe', 'Fernandez', 'noahe@user.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 01:30:35', 'user', 0, NULL),
(20, 'admin', 'admin', 'admin@admin.com', '$2y$10$B0OsAybM9ha6zYgyAhLCwe3EcxTfscYRvrW18ImTxjJEEIQ/QzgEG', '2025-05-07 22:50:32', 'admin', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_assigned_baranggay` (`assignedBaranggay`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_assigned_baranggay` FOREIGN KEY (`assignedBaranggay`) REFERENCES `baranggays` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
