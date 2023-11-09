-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2021 at 05:52 AM
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
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `salaries_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
