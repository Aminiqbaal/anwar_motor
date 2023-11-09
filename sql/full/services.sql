-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2021 at 11:56 AM
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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `cost`) VALUES
(1, 'Bor', 5000),
(2, 'Cas Aki', 5000),
(3, 'Ganti Bearing 6300 s/d 6302', 15000),
(4, 'Ganti Bearing 6302 keatas', 30000),
(5, 'Cek Kelistrikan', 25000),
(6, 'Center Bodi', 60000),
(7, 'Ganti Piston', 150000),
(8, 'Ganti V Belt', 30000),
(9, 'Ganti Key Set', 30000),
(10, 'Ganti Cilinder Matic dan Bebek', 150000),
(11, 'Ganti Cilinder Sport', 300000),
(12, 'Ganti Valve', 100000),
(13, 'Ganti Race Set', 80000),
(14, 'Ganti Pipe Front', 25000),
(15, 'Ganti Piece Set', 30000),
(16, 'Ganti Pad Set', 10000),
(17, 'Ganti Fase', 20000),
(18, 'Ganti Filter Matic dan Bebek', 10000),
(19, 'Ganti Filter Sport', 15000),
(20, 'Ganti Knalpot', 10000),
(21, 'Ganti Lifter Assy', 10000),
(22, 'Ganti Handle', 10000),
(23, 'Ganti Tromol', 30000),
(24, 'Ganti Horn Comp', 10000),
(25, 'Ganti Gear Primary', 50000),
(26, 'Ganti Disk Set Cluth', 50000),
(27, 'Ganti Disk Brake', 15000),
(28, 'Ganti Conrod Kit', 50000),
(29, 'Ganti Coil', 20000),
(30, 'Ganti Karbu', 35000),
(31, 'Ganti Stripping', 30000),
(32, 'Ganti Cap Suppressor', 50000),
(33, 'Ganti Cap Tappet', 5000),
(34, 'Ganti Cap Hole', 5000),
(35, 'Ganti Camshaft Comp', 10000),
(36, 'Ganti Camshaft Comp Throttle', 10000),
(37, 'Ganti Camshaft Comp Throt', 10000),
(38, 'Ganti Comp Rear Brake', 10000),
(39, 'Ganti Comp Cluth', 10000),
(40, 'Ganti Brush Set', 10000),
(41, 'Ganti Brake Shoe Set', 10000),
(42, 'Ganti Brake Shoe Set + Spring', 10000),
(43, 'Ganti Velg Balok', 40000),
(44, 'Ganti Weight Primary', 30000),
(45, 'Ganti Tube Air Cleave', 10000),
(46, 'Ganti Gear Set', 30000),
(47, 'Servis Center Bodi Matic dan Bebek', 100000),
(48, 'Servis Center Bodi Sport', 150000),
(49, 'Pembersihan CVT', 35000),
(50, 'Pembersihan Karbu', 35000),
(51, 'Servis Stel Rem Depan', 15000),
(52, 'Servis Stel Rem Belakang', 20000),
(53, 'Servis Stel Rantai', 5000),
(54, 'Isi Minyak Rem', 5000),
(55, 'Ganti Bulp', 10000),
(56, 'Ganti Spion', 10000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
