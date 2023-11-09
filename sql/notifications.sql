-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2021 at 08:48 AM
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
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `is_read`, `redirect`, `target_user_id`) VALUES
(1, 'Pengajuan pinjaman dari Mubin sebesar Rp10.000.', 0, '/loan', 1),
(2, 'Laporan Stok Rendah: Bearing Ball 6300.', 0, '/report', 1),
(3, 'Laporan Stok Rendah: CDI KVY Honda Beat Karbu.', 0, '/report', 1),
(4, 'Laporan Stok Rendah: Brush Set SHO Suzuki Shogun.', 0, '/report', 1),
(5, 'Laporan Stok Rendah: CDI S5R Suzuki Shogun 125 R.', 0, '/report', 1),
(6, 'Laporan Stok Rendah: Conn Rod Kit SAT Suzuki Satria F150.', 0, '/report', 1),
(7, 'Laporan Stok Rendah: Cable Comp Thrott MIJ Yamaha Mio J / GT.', 0, '/report', 1),
(8, 'Laporan Stok Rendah: Coil Ignition MIO Yamaha Mio Smile / Sporty.', 0, '/report', 1),
(9, 'Laporan Stok Rendah: Cylinder Block Kit K44 Honda Beat ESP.', 0, '/report', 2),
(10, 'Laporan Stok Rendah: Race Set Steering 028 Honda Supra.', 0, '/report', 2),
(11, 'Laporan Stok Rendah: Conn Rod Kit VZR Yamaha Vega ZR / R / RR / Old.', 0, '/report', 2),
(12, 'Laporan Stok Rendah: Disk Brake Depan MIO Yamaha Mio Smile / Sporty.', 0, '/report', 2),
(13, 'Laporan Stok Rendah: Disk Set Clutch 5 JMN Yamaha Jupiter MX New.', 0, '/report', 2),
(14, 'Laporan Stok Rendah: Bearing Ball 6204.', 0, '/report', 3),
(15, 'Laporan Stok Rendah: Brake Shoe Set KPH Honda Kharisma 125.', 0, '/report', 3),
(16, 'Laporan Stok Rendah: Carb Cleaner 300ml.', 0, '/report', 3),
(17, 'Laporan Stok Rendah: Cable Comp Rear Brake KVB Honda Vario 110.', 0, '/report', 3),
(18, 'Laporan Stok Rendah: Element Comp Air Cleaner KVY Honda Beat Karbu.', 0, '/report', 3),
(19, 'Laporan Stok Rendah: Hub RR Wheel Silver GN5 Honda Grand / Supra 110.', 0, '/report', 3),
(20, 'Laporan Stok Rendah: Key Set KEV Honda Supra 125.', 0, '/report', 3),
(21, 'Laporan Stok Rendah: Piece Slide Set GCC Honda Beat.', 0, '/report', 3),
(22, 'Laporan Stok Rendah: Race Set Steering KTR Honda GL Pro / GL Max / Mega Pro / Tiger Lama / Tiger Revo / Verza.', 0, '/report', 3),
(23, 'Laporan Stok Rendah: V Belt K44 Honda Beat ESP.', 0, '/report', 3),
(24, 'Laporan Stok Rendah: Cable Comp Thrott JMX Yamaha Jupiter MX Old.', 0, '/report', 3),
(25, 'Laporan Stok Rendah: Camshaft Comp VZR Yamaha Vega R / RR / ZR.', 0, '/report', 3),
(26, 'Laporan Stok Rendah: Conn Rod Kit RXK Yamaha RX King.', 0, '/report', 3),
(27, 'Laporan Stok Rendah: Weight Primary MIO Yamaha Mio.', 0, '/report', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
