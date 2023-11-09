-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2021 at 02:20 PM
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
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`id`, `name`, `address`, `phone`) VALUES
(1, 'Anwar Motor 1', 'Jl. Raya Manyar No.121, Manyarejo, Kec. Manyar, Kab. Gresik', '-'),
(2, 'Anwar Motor 2', 'Jl. Raya Sukomulyo No.22, Sekarsore, Roomo, Kec. Manyar, Kab. Gresik', '(031) 3958588 / 0857-3193-8585'),
(3, 'Anwar Motor 3', 'Blok H no. 2 Residance Jalan Manyar Raya, Maduran, Roomo, Maduran, Kab. Gresik', '(031) 3958234');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
