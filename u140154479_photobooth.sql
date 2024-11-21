-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 23, 2024 at 10:44 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u140154479_photobooth`
--

-- --------------------------------------------------------

--
-- Table structure for table `photo_list`
--

CREATE TABLE `photo_list` (
  `ph_id` bigint(20) NOT NULL,
  `ul_id` bigint(20) NOT NULL,
  `pl_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `undangan_list`
--

CREATE TABLE `undangan_list` (
  `ul_id` bigint(20) NOT NULL,
  `ul_name` varchar(255) DEFAULT NULL,
  `ul_date` datetime DEFAULT NULL,
  `ul_qr` text DEFAULT NULL,
  `ul_showqr` enum('y','n') DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `photo_list`
--
ALTER TABLE `photo_list`
  ADD PRIMARY KEY (`ph_id`),
  ADD KEY `ul_id` (`ul_id`);

--
-- Indexes for table `undangan_list`
--
ALTER TABLE `undangan_list`
  ADD PRIMARY KEY (`ul_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `photo_list`
--
ALTER TABLE `photo_list`
  MODIFY `ph_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `undangan_list`
--
ALTER TABLE `undangan_list`
  MODIFY `ul_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `photo_list`
--
ALTER TABLE `photo_list`
  ADD CONSTRAINT `photo_list_ibfk_1` FOREIGN KEY (`ul_id`) REFERENCES `undangan_list` (`ul_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
