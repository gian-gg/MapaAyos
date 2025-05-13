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
-- Table structure for table `baranggayInfo`
--

CREATE TABLE `baranggayInfo` (
  `baranggayID` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `landArea` decimal(10,2) DEFAULT NULL,
  `population` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baranggayInfo`
--

INSERT INTO `baranggayInfo` (`baranggayID`, `description`, `landArea`, `population`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 4.63, 33382),
(2, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat lorem ipsum dolor.', 4.23, 45853),
(3, 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat.', 2.36, 6336);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baranggayInfo`
--
ALTER TABLE `baranggayInfo`
  ADD PRIMARY KEY (`baranggayID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baranggayInfo`
--
ALTER TABLE `baranggayInfo`
  ADD CONSTRAINT `baranggayinfo_ibfk_1` FOREIGN KEY (`baranggayID`) REFERENCES `baranggays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
