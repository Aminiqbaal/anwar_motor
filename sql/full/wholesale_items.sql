-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2021 at 05:07 PM
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
-- Table structure for table `wholesale_items`
--

CREATE TABLE `wholesale_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` enum('Set','Unit') NOT NULL,
  `purchase_price` bigint(20) NOT NULL,
  `selling_price` bigint(20) NOT NULL,
  `category` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `wholesale_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wholesale_items`
--
ALTER TABLE `wholesale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wholesale_id` (`wholesale_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wholesale_items`
--
ALTER TABLE `wholesale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wholesale_items`
--
ALTER TABLE `wholesale_items`
  ADD CONSTRAINT `wholesale_items_ibfk_1` FOREIGN KEY (`wholesale_id`) REFERENCES `wholesales` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
