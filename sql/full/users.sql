-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2021 at 01:27 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','admin','mechanic') NOT NULL,
  `workshop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `workshop_id`) VALUES
(1, 'anwar1', 'a', 'manager', 1),
(2, 'anwar2', 'a', 'manager', 2),
(3, 'anwar3', 'a', 'manager', 3),
(4, 'admin1', 'a', 'admin', 1),
(5, 'admin2', 'a', 'admin', 2),
(6, 'admin3', 'a', 'admin', 3),
(7, 'mubin', 'a', 'mechanic', 1),
(8, 'tain', 'a', 'mechanic', 1),
(9, 'iwan', 'a', 'mechanic', 1),
(10, 'arifin_ilham', 'a', 'mechanic', 1),
(11, 'munir', 'a', 'mechanic', 1),
(12, 'qosim', 'a', 'mechanic', 2),
(13, 'wahid', 'a', 'mechanic', 2),
(14, 'ali_hidayat', 'a', 'mechanic', 2),
(15, 'rochim', 'a', 'mechanic', 2),
(16, 'muchlis', 'a', 'mechanic', 2),
(17, 'arip', 'a', 'mechanic', 2),
(18, 'faisol', 'a', 'mechanic', 2),
(19, 'heri', 'a', 'mechanic', 2),
(20, 'udin', 'a', 'mechanic', 3),
(21, 'agus', 'a', 'mechanic', 3),
(22, 'shodiqin', 'a', 'mechanic', 3),
(23, 'irul', 'a', 'mechanic', 3),
(24, 'dani', 'a', 'mechanic', 3),
(25, 'jabar', 'a', 'mechanic', 3),
(26, 'rozi', 'a', 'mechanic', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `workshop_id` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
