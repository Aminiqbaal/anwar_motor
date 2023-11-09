-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2021 at 12:13 PM
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
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reported_at` date NOT NULL,
  `sparepart_id` int(11) NOT NULL,
  `workshop_id` int(11) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reported_at`, `sparepart_id`, `workshop_id`, `is_done`) VALUES
(1, '2021-01-01', 25, 1, 0),
(2, '2021-01-01', 159, 1, 0),
(3, '2021-01-01', 351, 1, 0),
(4, '2021-01-01', 354, 1, 0),
(5, '2021-01-01', 357, 1, 0),
(6, '2021-01-01', 399, 1, 0),
(7, '2021-01-01', 412, 1, 0),
(8, '2021-01-01', 180, 2, 0),
(9, '2021-01-01', 274, 2, 0),
(10, '2021-01-01', 420, 2, 0),
(11, '2021-01-01', 423, 2, 0),
(12, '2021-01-01', 434, 2, 0),
(13, '2021-01-01', 19, 3, 0),
(14, '2021-01-01', 39, 3, 0),
(15, '2021-01-01', 43, 3, 0),
(16, '2021-01-01', 114, 3, 0),
(17, '2021-01-01', 202, 3, 0),
(18, '2021-01-01', 219, 3, 0),
(19, '2021-01-01', 223, 3, 0),
(20, '2021-01-01', 263, 3, 0),
(21, '2021-01-01', 273, 3, 0),
(22, '2021-01-01', 290, 3, 0),
(23, '2021-01-01', 396, 3, 0),
(24, '2021-01-01', 405, 3, 0),
(25, '2021-01-01', 415, 3, 0),
(26, '2021-01-01', 440, 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sparepart_id` (`sparepart_id`),
  ADD KEY `workshop_id` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
