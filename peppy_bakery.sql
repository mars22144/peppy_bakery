-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 12:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peppy_bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tgl_order` datetime DEFAULT current_timestamp(),
  `ttl_harga` decimal(10,2) NOT NULL,
  `status_order` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `tgl_order`, `ttl_harga`, `status_order`) VALUES
(1, 3, '2026-06-09 19:13:20', 40000.00, 'selesai'),
(2, 3, '2026-06-11 09:41:43', 40000.00, 'pending'),
(3, 3, '2026-06-15 14:09:30', 50000.00, 'pending'),
(13, 3, '2026-06-15 14:38:28', 50000.00, 'pending'),
(14, 3, '2026-06-15 14:39:40', 50000.00, 'pending'),
(15, 3, '2026-06-15 14:39:54', 50000.00, 'pending'),
(16, 3, '2026-06-15 14:43:08', 50000.00, 'pending'),
(17, 3, '2026-06-15 14:51:58', 50000.00, 'pending'),
(18, 3, '2026-06-15 20:15:40', 50000.00, 'pending'),
(19, 3, '2026-06-15 20:19:38', 35000.00, 'pending'),
(20, 3, '2026-06-15 21:32:01', 40000.00, 'pending'),
(21, 6, '2026-06-16 10:02:57', 40000.00, 'pending'),
(22, 6, '2026-06-16 10:14:34', 80000.00, 'pending'),
(23, 6, '2026-06-16 10:30:46', 40000.00, 'pending'),
(24, 6, '2026-06-16 10:31:01', 50000.00, 'pending'),
(25, 6, '2026-06-16 10:31:17', 50000.00, 'pending'),
(26, 6, '2026-06-16 10:31:42', 50000.00, 'pending'),
(27, 6, '2026-06-16 10:36:23', 3540000.00, 'dibatalkan'),
(28, 6, '2026-06-16 17:55:12', 40000.00, 'dikirim'),
(29, 3, '2026-06-21 20:58:57', 300000.00, 'dibatalkan'),
(30, 3, '2026-06-21 22:38:32', 40000.00, 'pending'),
(31, 3, '2026-06-22 08:13:31', 40000.00, 'pending'),
(32, 3, '2026-06-22 11:17:17', 50000.00, 'selesai'),
(33, 3, '2026-06-22 18:55:21', 40000.00, 'pending'),
(34, 3, '2026-06-22 18:55:29', 40000.00, 'dibatalkan'),
(35, 3, '2026-06-22 20:01:40', 56000.00, 'selesai'),
(36, 3, '2026-06-22 20:27:59', 112000.00, 'dibatalkan'),
(37, 3, '2026-06-22 20:28:30', 56000.00, 'diproses'),
(38, 3, '2026-06-22 21:19:53', 112000.00, 'dibatalkan'),
(39, 3, '2026-06-22 21:20:18', 56000.00, 'dibatalkan'),
(40, 3, '2026-06-22 21:20:40', 56000.00, 'dibatalkan'),
(41, 3, '2026-06-22 21:39:33', 112000.00, 'dibatalkan'),
(42, 3, '2026-06-23 08:44:38', 50000.00, 'pending'),
(43, 8, '2026-06-23 10:38:42', 56000.00, 'dibatalkan'),
(44, 8, '2026-06-23 10:40:04', 40000.00, 'diproses'),
(45, 11, '2026-06-23 11:33:05', 120000.00, 'dibatalkan'),
(46, 11, '2026-06-23 11:34:30', 40000.00, 'diproses');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id_detail` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_produk` varchar(150) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id_detail`, `id_order`, `id_produk`, `nama_produk`, `harga`, `qty`, `sub_total`) VALUES
(1, 1, 2, 'apalah', 40000.00, 1, 40000.00),
(2, 2, 2, 'apalah', 40000.00, 1, 40000.00),
(3, 3, 3, 'roti enak', 50000.00, 1, 50000.00),
(13, 13, 3, 'roti enak', 50000.00, 1, 50000.00),
(14, 14, 3, 'roti enak', 50000.00, 1, 50000.00),
(15, 15, 3, 'roti enak', 50000.00, 1, 50000.00),
(16, 16, 3, 'roti enak', 50000.00, 1, 50000.00),
(17, 17, 3, 'roti enak', 50000.00, 1, 50000.00),
(18, 18, 3, 'roti enak', 50000.00, 1, 50000.00),
(19, 19, 1, 'adalah pokoknya', 35000.00, 1, 35000.00),
(20, 20, 4, 'inilah', 40000.00, 1, 40000.00),
(21, 21, 4, 'inilah', 40000.00, 1, 40000.00),
(22, 22, 2, 'apalah', 40000.00, 2, 80000.00),
(23, 23, 4, 'inilah', 40000.00, 1, 40000.00),
(24, 24, 3, 'roti enak', 50000.00, 1, 50000.00),
(25, 25, 3, 'roti enak', 50000.00, 1, 50000.00),
(26, 26, 3, 'roti enak', 50000.00, 1, 50000.00),
(27, 27, 4, 'inilah', 40000.00, 1, 40000.00),
(28, 27, 1, 'adalah pokoknya', 35000.00, 100, 3500000.00),
(29, 28, 4, 'inilah', 40000.00, 1, 40000.00),
(30, 29, 3, 'roti enak', 50000.00, 6, 300000.00),
(31, 30, 4, 'inilah', 40000.00, 1, 40000.00),
(32, 31, 4, 'inilah', 40000.00, 1, 40000.00),
(33, 32, 3, 'roti enak', 50000.00, 1, 50000.00),
(34, 33, 4, 'inilah', 40000.00, 1, 40000.00),
(35, 34, 4, 'inilah', 40000.00, 1, 40000.00),
(43, 42, 3, 'roti enak', 50000.00, 1, 50000.00),
(45, 44, 4, 'inilah', 40000.00, 1, 40000.00),
(46, 45, 12, 'adalah enak', 60000.00, 2, 120000.00),
(47, 46, 4, 'inilah', 40000.00, 1, 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payment` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `metode_nayar` varchar(50) DEFAULT NULL,
  `status_bayar` varchar(50) DEFAULT NULL,
  `bukti_bayar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id_payment`, `id_order`, `metode_nayar`, `status_bayar`, `bukti_bayar`) VALUES
(1, 1, 'cod', 'unpaid', ''),
(2, 2, 'ewallet', 'unpaid', ''),
(3, 3, 'ewallet', 'unpaid', ''),
(13, 13, 'cod', 'unpaid', ''),
(14, 14, 'ewallet', 'unpaid', ''),
(15, 15, 'transfer', 'unpaid', ''),
(16, 16, 'transfer', 'unpaid', ''),
(17, 17, 'ewallet', 'unpaid', ''),
(18, 18, 'cod', 'unpaid', ''),
(19, 19, 'cod', 'unpaid', ''),
(20, 20, 'cod', 'unpaid', ''),
(21, 21, 'cod', 'unpaid', ''),
(22, 22, 'ewallet', 'unpaid', ''),
(23, 23, 'transfer', 'unpaid', ''),
(24, 24, 'transfer', 'unpaid', ''),
(25, 25, 'transfer', 'unpaid', ''),
(26, 26, 'transfer', 'unpaid', ''),
(27, 27, 'ewallet', 'unpaid', ''),
(28, 28, 'cod', 'unpaid', ''),
(29, 29, 'transfer', 'unpaid', ''),
(30, 30, 'transfer', 'unpaid', ''),
(31, 31, 'transfer', 'unpaid', ''),
(32, 32, 'cod', 'paid', ''),
(33, 33, 'transfer', 'unpaid', ''),
(34, 34, 'ewallet', 'unpaid', ''),
(35, 35, 'transfer', 'paid', ''),
(36, 36, 'transfer', 'unpaid', ''),
(37, 37, 'transfer', 'unpaid', ''),
(38, 38, 'ewallet', 'unpaid', ''),
(39, 39, 'transfer', 'unpaid', ''),
(40, 40, 'transfer', 'unpaid', ''),
(41, 41, 'transfer', 'unpaid', ''),
(42, 42, 'ewallet', 'unpaid', ''),
(43, 43, 'transfer', 'unpaid', ''),
(44, 44, 'ewallet', 'paid', ''),
(45, 45, 'transfer', 'unpaid', ''),
(46, 46, 'transfer', 'paid', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `berat` varchar(50) DEFAULT NULL,
  `ketahanan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_produk`, `nama_produk`, `deskripsi`, `harga`, `stok`, `foto`, `berat`, `ketahanan`) VALUES
(1, 'adalah pokoknya', 'enak banget~~', 35000.00, 9, '/peppy_bakery/assets/img/products/prod_1780968587_947.jpg', '1000', 'luama pol'),
(2, 'apalah', 'adalah pokoknya', 40000.00, 0, '/peppy_bakery/assets/img/products/prod_1780968874_141.jpg', '3000', 'lama'),
(3, 'roti enak', 'ya begitulah', 50000.00, 8, '/peppy_bakery/assets/img/products/prod_1781506505_266.jpg', '3000', '4 hari'),
(4, 'inilah', 'itulah', 40000.00, 55, '/peppy_bakery/assets/img/products/prod_1782056325_906.jpg', '300', '5 hari'),
(12, 'adalah enak', 'jajaajajjaj', 60000.00, 9, '/peppy_bakery/assets/img/products/prod_1782186332_231.jpg', '400', '4 haru');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `tgl_review` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id_review`, `id_user`, `id_produk`, `rating`, `komentar`, `tgl_review`) VALUES
(1, 3, 2, 5, 'bagus sekali', '2026-06-09 20:12:30'),
(2, 3, 4, 5, 'enak', '2026-06-17 09:47:55'),
(3, 3, 4, 1, 'hambar', '2026-06-17 09:49:35'),
(4, 3, 3, 5, 'enak banget rotinya', '2026-06-17 09:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id_shipping` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `alamat_kirim` text NOT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `no_resi` varchar(100) DEFAULT NULL,
  `status_kirim` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id_shipping`, `id_order`, `alamat_kirim`, `kurir`, `no_resi`, `status_kirim`) VALUES
(1, 1, 'dasdsad', NULL, NULL, 'pending'),
(2, 2, 'dsadsda', NULL, NULL, 'pending'),
(3, 3, 'fdafewfew', NULL, NULL, 'pending'),
(4, 13, 'fdwfewf', NULL, NULL, 'pending'),
(5, 14, 'fewewf', NULL, NULL, 'pending'),
(6, 15, 'fewfew', NULL, NULL, 'pending'),
(7, 16, 'regfewgeger', NULL, NULL, 'pending'),
(8, 17, 'grregregewrgreg', NULL, NULL, 'pending'),
(9, 18, 'dfsfsdf', 'JNE', NULL, 'pending'),
(10, 19, 'fewfewf', 'GoSend', 'PBK-GOSEND-832178510', 'pending'),
(11, 20, 'dwdqdqw', 'SiCepat', 'PBK-SICEPAT-731067831', 'pending'),
(12, 21, 'vdsavdsadfvs', 'SiCepat', 'PBK-SICEPAT-614248408', 'pending'),
(13, 22, 'dwadwas', 'J&T', 'PBK-JT-663237549', 'pending'),
(14, 23, 'dwdadsdsads', 'JNE', 'PBK-JNE-198086644', 'pending'),
(15, 24, 'erwrwqrew', 'GrabExpress', 'PBK-GRABEXPRESS-557647398', 'pending'),
(16, 25, 'rgetwer', 'GrabExpress', 'PBK-GRABEXPRESS-105414805', 'pending'),
(17, 26, 'tretrewter', 'GoSend', 'PBK-GOSEND-492525514', 'pending'),
(18, 27, 'efaferte', 'SiCepat', 'PBK-SICEPAT-772494370', 'pending'),
(19, 28, 'sadsad', 'J&T', 'PBK-JT-875611219', 'pending'),
(20, 29, 'dsadsad', 'GrabExpress', 'PBK-GRABEXPRESS-310443269', 'pending'),
(21, 30, 'wqdqwd', 'SiCepat', 'PBK-SICEPAT-746542549', 'pending'),
(22, 31, 'fenwoibfoewg', 'GoSend', 'PBK-GOSEND-499174684', 'pending'),
(23, 32, 'fejwjibgew', 'JNE', 'PBK-JNE-127399053', 'pending'),
(24, 33, 'efwfrewf', 'GoSend', 'PBK-GOSEND-833731189', 'pending'),
(25, 34, 'rtetretwr', 'SiCepat', 'PBK-SICEPAT-505581412', 'pending'),
(26, 35, 'fdasfsfds', 'J&T', 'PBK-JT-437383498', 'pending'),
(27, 36, 'dadqwe', 'J&T', 'PBK-JT-230793202', 'pending'),
(28, 37, 'dwadwa', 'SiCepat', 'PBK-SICEPAT-233718306', 'pending'),
(29, 38, 'qwdwqedwq', 'JNE', 'PBK-JNE-811001584', 'pending'),
(30, 39, 'frerew', 'SiCepat', 'PBK-SICEPAT-721203772', 'pending'),
(31, 40, 'fsafawe', 'GoSend', 'PBK-GOSEND-198190036', 'pending'),
(32, 41, 'dsadas', 'GoSend', 'PBK-GOSEND-161493083', 'pending'),
(33, 42, 'fnjweibgijer', 'GoSend', 'PBK-GOSEND-849744881', 'pending'),
(34, 43, 'adalah pokoknya', 'JNE', 'PBK-JNE-872807657', 'pending'),
(35, 44, 'fewoifew', 'GoSend', 'PBK-GOSEND-666328765', 'pending'),
(36, 45, 'disini dekat kok', 'J&T', 'PBK-JT-578201768', 'pending'),
(37, 46, 'yuuggc', 'SiCepat', 'PBK-SICEPAT-853126452', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `no_hp`, `alamat`, `role`, `created_at`) VALUES
(1, 'fauziadnan', 'fauzi@gmail.com', '$2y$10$nHNi3CUvkLpcIiMnnuYH0.uJ2n8R9zAZct.vULz5O78ABYKTd9XX6', NULL, NULL, 'customer', '2026-06-09 01:26:13'),
(2, 'admin1', 'admin1@gmail.com', '$2y$10$9jATJ8E7KCPSLRlTW5YttuNco0wbUl7Vl20hFtCYIp1Yd0bfzrlQq', NULL, NULL, 'admin', '2026-06-09 01:27:43'),
(3, 'adnan', 'adnan@gmail.com', '$2y$10$u8Btcv4LkSqlOc.FZFzMPOXgcBP77w2z34RAy4BuKsvCk9JINQKGC', NULL, NULL, 'customer', '2026-06-09 11:44:08'),
(4, 'fauziadnan', 'nugraha@gmail.com', '$2y$10$fxdJVVB6Wivdp3elW1e2PeA0W4nKOXJJYax9gTehWZItcRxa2eBSa', '091227101516', 'tegalampel', 'customer', '2026-06-10 11:04:08'),
(5, 'fauziadnan', 'adnan2@gmail.com', '$2y$10$B/vvA8Ywj.YakECcSCeBLOghl0pqD1FH3o74awpBvGMawQOhUEcci', '08122710156', 'dfasfesfesfes', 'customer', '2026-06-11 02:40:06'),
(6, 'fauziadnan nugraha', 'saputra@gmail.com', '$2y$10$1Er3FdJMQ2cqToK0nYim9Oq/nOjConlKH0/ufIV5y81/BLrLKHJdC', '081227101516', 'adalah pokoknya', 'customer', '2026-06-16 03:01:38'),
(7, 'paijo', 'paijo@gmail.com', '$2y$10$Bm7Xhh6/FitGwjbKxp61tOsnIy1Ze9E9QCt1.9dm7BDoN14/1t28m', '543543', 'fdafrafrea', 'customer', '2026-06-21 15:42:39'),
(8, 'akuuunn', 'akun@gmail.com', '$2y$10$hoGUy2arFKJXnwyixIsq/.ndOs5u19eXCxeJHZ20jPATGUNO/vIMW', '0999888', 'fsbfbwubufew', 'customer', '2026-06-23 03:37:48'),
(9, 'gahaahaah', 'gana@gmail.com', '$2y$10$/lFFIzMWcZms0RlLy8YPlOMEKfzBb.7fFCDYa5Zr9ThHhIpGVEhye', '89065736', 'adnsbduias', 'customer', '2026-06-23 03:50:16'),
(10, 'dahaaa', 'daha@gmail.com', '$2y$10$p6vjDTOPxBmVgkCPpaX37u/nbLYbwg4lXq.A4TYiTB6vcSNEcZIX6', '98990', 'fnjsfnew', 'customer', '2026-06-23 04:03:50'),
(11, 'adalah pokoknya', 'cihuy@gmail.com', '$2y$10$yOjqRSfeltUD9Nj3KVBDl.ZXowhDmAyHqa8PTGa.9TcDmnr6BIqGG', '0998877666', 'disana jauh banget', 'customer', '2026-06-23 04:30:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD UNIQUE KEY `id_order` (`id_order`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id_shipping`),
  ADD UNIQUE KEY `id_order` (`id_order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id_shipping` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `products` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `products` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
