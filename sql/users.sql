-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2021 at 02:19 PM
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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `workshop_id`) VALUES
(1, 'manager1', 'a', 'manager', 1),
(2, 'manager2', 'a', 'manager', 2),
(3, 'manager3', 'a', 'manager', 3),
(4, 'cashier1', 'a', 'cashier', 1),
(5, 'cashier2', 'a', 'cashier', 2),
(6, 'cashier3', 'a', 'cashier', 3),
(7, 'mubin', 'a', 'mechanic', 1),
(8, "ta'in", 'a', 'mechanic', 1),
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
