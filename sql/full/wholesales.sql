-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2021 at 11:57 AM
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
-- Table structure for table `wholesales`
--

CREATE TABLE `wholesales` (
  `id` int(11) NOT NULL,
  `purchased_at` date NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `photo` text DEFAULT NULL,
  `grand_total` int(11) NOT NULL,
  `workshop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wholesales`
--

INSERT INTO `wholesales` (`id`, `purchased_at`, `supplier_id`, `photo`, `grand_total`, `workshop_id`) VALUES
(1, '0000-00-00', 1, NULL, 0, 1),
(2, '0000-00-00', 2, NULL, 0, 1),
(3, '0000-00-00', 3, NULL, 0, 1),
(4, '0000-00-00', 4, NULL, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wholesales`
--
ALTER TABLE `wholesales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `workshop_id` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wholesales`
--
ALTER TABLE `wholesales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wholesales`
--
ALTER TABLE `wholesales`
  ADD CONSTRAINT `wholesales_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `wholesales_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
