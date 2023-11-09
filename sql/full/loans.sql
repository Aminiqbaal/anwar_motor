-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2021 at 05:51 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anwarmotor`
--

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `remaining` int(11) NOT NULL,
  `percentage` enum('10','20','30','40','50') NOT NULL,
  `cut_amount` int(11) NOT NULL,
  `info` text NOT NULL,
  `is_approved` enum('-1','0','1') NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `workshop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `workshop_id` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`mechanic_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
