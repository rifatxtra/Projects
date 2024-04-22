-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2024 at 07:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `User_ID` int(11) NOT NULL,
  `First_Name` varchar(255) DEFAULT NULL,
  `Last_Name` varchar(255) DEFAULT NULL,
  `User_Name` varchar(255) DEFAULT NULL,
  `Phone_No` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`User_ID`, `First_Name`, `Last_Name`, `User_Name`, `Phone_No`, `Email`, `Password`) VALUES
(1, 'Md. Rashedul', 'Islam', 'Rifat104', '01845878722', 'Kop@gmail.com', '$2y$10$qcKeDYlwmur7kKEkuOtpcOFW8/nT.rf6ZmPox5b4zPiJLm0Vq9ocq');

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `u_id` int(255) DEFAULT NULL,
  `btc` float(10,5) DEFAULT NULL,
  `eth` float(10,5) DEFAULT NULL,
  `sol` float(10,5) DEFAULT NULL,
  `bnb` float(10,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`u_id`, `btc`, `eth`, `sol`, `bnb`) VALUES
(6, 119.00000, 100.00000, 100.00000, 110.00000),
(4, 167.87856, 459.50912, 504.23499, 548.64001),
(7, 4.00000, 0.00000, 1.00000, 1.00000),
(-1, 0.00000, 945.00000, 0.00000, 0.00000),
(8, 0.00000, 0.00000, 0.00000, 0.00000);

-- --------------------------------------------------------

--
-- Table structure for table `conversion_history`
--

CREATE TABLE `conversion_history` (
  `id` int(11) NOT NULL,
  `from` varchar(50) NOT NULL,
  `to` varchar(50) NOT NULL,
  `sent` float NOT NULL,
  `received` float NOT NULL,
  `fee` float NOT NULL,
  `uid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `conversion_history`
--

INSERT INTO `conversion_history` (`id`, `from`, `to`, `sent`, `received`, `fee`, `uid`, `date`) VALUES
(1, 'bnb', 'btc', 10, 0.0693084, 0.5, 4, '2024-02-23 18:00:00'),
(2, 'btc', 'bnb', 1, 144.283, 0.05, 4, '2024-03-01 18:00:00'),
(3, 'btc', 'bnb', 1, 144.283, 0.05, 4, '2024-03-01 18:00:00'),
(4, 'sol', 'btc', 10, 0.0221192, 0.5, 4, '2024-03-06 18:00:00'),
(5, 'sol', 'btc', 10, 0.0221192, 0.5, 4, '2024-03-06 18:00:00'),
(6, 'btc', 'bnb', 1, 144.283, 0.05, 4, '2024-03-09 18:00:00'),
(7, 'btc', 'sol', 1, 452.096, 0.05, 4, '2024-03-09 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_address`
--

CREATE TABLE `deposit_address` (
  `coin` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `min_amount` double DEFAULT NULL,
  `bonus` double DEFAULT NULL,
  `withdrawal_fees` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deposit_address`
--

INSERT INTO `deposit_address` (`coin`, `address`, `min_amount`, `bonus`, `withdrawal_fees`) VALUES
('btc', 'dfsjdsf', 5, 1, 1),
('eth', 'dfsjdsf', 1, 1, 1),
('sol', 'dfsjdsf', 5, 1, 1),
('bnb', 'dfsjdsf', 5, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `deposit_history`
--

CREATE TABLE `deposit_history` (
  `id` int(11) NOT NULL,
  `coin` varchar(255) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `txnid` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `stats` varchar(255) DEFAULT NULL,
  `bonus` double DEFAULT NULL,
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deposit_history`
--

INSERT INTO `deposit_history` (`id`, `coin`, `uid`, `txnid`, `amount`, `stats`, `bonus`, `date`) VALUES
(10, 'BTC', 4, 'ffd', 1000, 'pendingsucc', 100, '2024-03-13'),
(11, '', 4, '', 0, 'pending', 0, '2024-03-13'),
(12, '', 4, '', 0, 'pending', 0, '2024-03-13'),
(13, '', 4, '', 0, 'pending', 0, '2024-03-13'),
(14, '', 4, '', 0, 'pending', 0, '2024-03-13'),
(15, '', 4, 'csxd', 3, 'pending', 0, '2024-03-13'),
(16, '', 4, 'csxd', 3, 'pending', 0, '2024-03-13'),
(17, '', 4, '4', 3, 'rejected', 0, '2024-03-13'),
(18, '', 4, 'hvhvv', 1, 'pending', 0, '2024-03-14'),
(19, '', 4, 'ff', 10000, 'pending', 0, '2024-03-14'),
(20, '', 4, '3333', 20000, 'pending', 0, '2024-03-14'),
(21, '', 4, '4rffr', 1000, 'pending', 0, '2024-03-14'),
(22, '', 4, 'bh', 55, 'pending', 0, '2024-03-14'),
(23, '', 4, 'bhbbdshs', 5, 'pending', 0, '2024-03-17'),
(24, 'btc', 4, '6', 1, 'pending', 0, '2024-03-17'),
(25, '', 4, '332', 5, 'pending', 0, '2024-03-17'),
(26, 'eth', 4, 'dxx5', 455, 'rejected', 4.55, '2024-03-17'),
(27, 'btc', 4, 'dsjkkn', 100, 'approved', 1, '2024-03-17'),
(28, 'btc', 4, 'hsdhufsduhfds', 500, 'approved', 5, '2024-03-20'),
(29, 'btc', 4, 'fdsgdgf', 100, 'rejected', 1, '2024-03-20'),
(30, 'eth', 4, 'dsds', 2, 'approved', 0.02, '2024-03-20'),
(31, 'bnb', 4, 'ghhj', 2, 'approved', 0.02, '2024-03-20');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `coin_id` int(255) DEFAULT NULL,
  `coin_name` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `fees` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`coin_id`, `coin_name`, `price`, `fees`) VALUES
(1, 'btc', 51918.7, 0.02),
(2, 'eth', 2827.96, 0.08),
(3, 'sol', 114.84, 0.0002),
(4, 'bnb', 359.84, 0.0001);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `txn_id` int(11) NOT NULL,
  `sender` int(255) DEFAULT NULL,
  `coin` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `receiver` int(255) DEFAULT NULL,
  `fees` float DEFAULT NULL,
  `transaction_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`txn_id`, `sender`, `coin`, `amount`, `receiver`, `fees`, `transaction_date`) VALUES
(2, 4, 'btc', 1, 6, 0.5, '2024-02-14'),
(3, 6, 'btc', 1, 4, 0.5, '2024-02-14'),
(4, 4, 'btc', 10, 6, 0.5, '2024-02-14'),
(5, 5, 'BTC', 308.81, 0, 5.98, '2024-02-21'),
(6, 4, 'BTC', 530.34, 0, 4.57, '2024-02-21'),
(7, 6, 'BTC', 89.63, 0, 3.73, '2024-02-21'),
(8, 5, 'BTC', 851.08, 0, 4.74, '2024-02-21'),
(9, 6, 'BTC', 666.24, 0, 8.78, '2024-02-21'),
(10, 5, 'BTC', 320.45, 0, 4.3, '2024-02-21'),
(11, 4, 'BTC', 647.49, 0, 6.75, '2024-02-21'),
(12, 5, 'BTC', 146.94, 0, 4.31, '2024-02-21'),
(13, 6, 'BTC', 273.27, 0, 2.27, '2024-02-21'),
(14, 4, 'BTC', 894.18, 0, 5.26, '2024-02-21'),
(20, 4, 'BTC', 374.52, 0, 0.21, '2024-02-21'),
(21, 6, 'BTC', 853.71, 0, 3.18, '2024-02-21'),
(22, 4, 'BTC', 185.13, 0, 8.43, '2024-02-21'),
(23, 5, 'BTC', 760.33, 0, 8.29, '2024-02-21'),
(24, 6, 'BTC', 828.19, 0, 5.52, '2024-02-21'),
(25, 4, 'BTC', 725.77, 0, 7.99, '2024-02-21'),
(26, 6, 'BTC', 700.61, 0, 0.44, '2024-02-21'),
(27, 4, 'BTC', 453.38, 0, 9.16, '2024-02-21'),
(28, 4, 'BTC', 354.11, 0, 1.09, '2024-02-21'),
(29, 5, 'BTC', 87.8, 0, 9.9, '2024-02-21'),
(35, 4, 'BTC', 10.5, 5, 1.25, '2024-02-21'),
(36, 4, 'ETH', 20.3, 5, 2.75, '2024-02-21'),
(37, 4, 'BTC', 15.7, 5, 1.95, '2024-02-21'),
(38, 4, 'ETH', 8.2, 5, 0.75, '2024-02-21'),
(39, 4, 'BTC', 12.6, 5, 1.35, '2024-02-21'),
(40, 4, 'BTC', 14.9, 5, 1.65, '2024-02-21'),
(41, 4, 'ETH', 23.7, 5, 2.95, '2024-02-21'),
(42, 4, 'BTC', 17.4, 5, 1.45, '2024-02-21'),
(43, 4, 'ETH', 9.8, 5, 0.85, '2024-02-21'),
(44, 4, 'BTC', 13.2, 5, 1.25, '2024-02-21'),
(45, 4, 'BTC', 10.5, 5, 1.25, '2024-02-21'),
(46, 4, 'ETH', 20.3, 5, 2.75, '2024-02-21'),
(47, 4, 'BTC', 15.7, 5, 1.95, '2024-02-21'),
(48, 4, 'ETH', 8.2, 5, 0.75, '2024-02-21'),
(49, 4, 'BTC', 12.6, 5, 1.35, '2024-02-21'),
(50, 4, 'BTC', 14.9, 5, 1.65, '2024-02-21'),
(51, 4, 'ETH', 23.7, 5, 2.95, '2024-02-21'),
(52, 4, 'BTC', 17.4, 5, 1.45, '2024-02-21'),
(53, 4, 'ETH', 9.8, 5, 0.85, '2024-02-21'),
(54, 4, 'BTC', 13.2, 5, 1.25, '2024-02-21'),
(55, 4, 'BTC', 10.5, 5, 1.25, '2024-02-21'),
(56, 4, 'ETH', 20.3, 5, 2.75, '2024-02-21'),
(57, 4, 'BTC', 15.7, 5, 1.95, '2024-02-21'),
(58, 4, 'ETH', 8.2, 5, 0.75, '2024-02-21'),
(59, 4, 'BTC', 12.6, 5, 1.35, '2024-02-21'),
(60, 4, 'BTC', 14.9, 5, 1.65, '2024-02-21'),
(61, 4, 'ETH', 23.7, 5, 2.95, '2024-02-21'),
(62, 4, 'BTC', 17.4, 5, 1.45, '2024-02-21'),
(63, 4, 'ETH', 9.8, 5, 0.85, '2024-02-21'),
(64, 4, 'BTC', 13.2, 5, 1.25, '2024-02-21'),
(65, 4, 'btc', 1, 6, 0.005, '2024-02-24'),
(66, 4, 'bnb', 10, 6, 0.05, '2024-02-24'),
(67, 4, 'btc', 1, 7, 0.005, '2024-02-27'),
(68, 4, 'bnb', 34, 1, 0.17, '2024-03-10'),
(69, 4, 'eth', 1, 5, 0.005, '2024-03-12'),
(70, 4, 'btc', 1, 7, 0.005, '2024-03-12'),
(71, 4, 'btc', 1, 7, 0.005, '2024-03-12'),
(72, 4, 'btc', 1, 7, 0.005, '2024-03-12'),
(73, 4, 'sol', 1, 7, 0.005, '2024-03-12'),
(74, 4, 'bnb', 1, 7, 0.005, '2024-03-12'),
(75, 4, 'eth', 900, -1, 4.5, '2024-03-20'),
(76, 4, 'eth', 45, -1, 0.225, '2024-03-20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `First_Name` varchar(255) DEFAULT NULL,
  `Last_Name` varchar(255) DEFAULT NULL,
  `User_Name` varchar(255) DEFAULT NULL,
  `Phone_No` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `ban_status` varchar(255) DEFAULT 'unbaned'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `First_Name`, `Last_Name`, `User_Name`, `Phone_No`, `Email`, `Password`, `ban_status`) VALUES
(1, 'md rashedul', 'islam', 'dnjnd344', '18458787', '1officialririfat@gmail.com', '$2y$10$y5G9W5kUfVMn0iD4/rZ36OQAaVsYIlbBQRzrKNCIc1MH7Gml0Y3mG', 'ban'),
(2, 'kop', 'kops', 'kop', '2147483647', 'kopsa2s@gmail.com', '$2y$10$kIo.7grnqcHs20IWgb7dCuLJk3WxM3/zhWalca2EP9E6Q1pOeOSsa', 'ban'),
(3, 'kop', 'kops', 'kop3', '123426746', 'kopsa2s@gmail.com', '$2y$10$82CKNXYwOm87noJfQsPZouaUtaZfCZjz7vz4d.ApGgmFBP4rtr0tO', 'ban'),
(4, 'Rifat', 'mia', 'Rifat104', '1845878722', 'dsbsdh@gmail.com', '$2y$10$KikkUw3OQsl911P66987Ieq4tl5pRUquWFu4tU0aX7cc/DsgVPppe', 'unban'),
(5, 'mr obaidul', 'kade', 'obi12', '1511616457', 'hjabd@gmail.com', '$2y$10$IS.uzrMVtYvr96gTcn.BEOPuQqepniamjA9B4TEx8Trn7./pHN2Sm', 'unban'),
(6, 'sfjksfd', 'dsfhksfd', 'kopasamsu', '1234569872', 'sdmhfdm@gmail.com', '$2y$10$m6A5zvuRqKCqhNCK7SlGi.4aPs6Z8S3D2fA6eZMRGGfx3XrOr.b4W', 'unban'),
(7, 'Obaidul', 'haque', 'obaidulsaiki', '1883440377', 'saki.obidul@gmail.com', '$2y$10$KUDfCGmJCyrSjWcyzx3.DOTaWbFTilIEBEbAH124KxrvjQpPSxozG', 'ban'),
(8, 'md rashedul', 'islam', 'asdfg', '01614618770', '1officialririfat@gmail.com', '$2y$10$5YHgiClCQF7pe1jh763fouciNRIfcJ2V8FMUVWZ4wExhjXfZws.tm', 'unbaned');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_history`
--

CREATE TABLE `withdrawal_history` (
  `withdrawal_id` int(11) NOT NULL,
  `coin` varchar(50) NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `address` varchar(255) NOT NULL,
  `withdrawal_status` varchar(50) NOT NULL,
  `uid` int(11) NOT NULL,
  `withdrawal_date` date DEFAULT NULL,
  `fees` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `withdrawal_history`
--

INSERT INTO `withdrawal_history` (`withdrawal_id`, `coin`, `amount`, `address`, `withdrawal_status`, `uid`, `withdrawal_date`, `fees`) VALUES
(1, 'Bitcoin', 1.00000000, 'fdfghhgfhfg', 'approved', 4, NULL, NULL),
(2, 'Ethereum', 2.00000000, 'fdfrfds', 'approved', 4, '0000-00-00', NULL),
(3, 'Bitcoin', 1.00000000, 'vgjgvjhh', 'pending', 4, '0000-00-00', NULL),
(4, 'eth', 22.00000000, 'dsd', 'pending', 4, '2024-03-09', 0),
(5, 'eth', 50.00000000, 'djnknjdf', 'pending', 4, '2024-03-09', 0),
(6, 'btc', 1.00000000, 'hjhv', 'pending', 4, '2024-03-09', 0),
(7, 'btc', 1.00000000, 'gvjv', 'pending', 4, '2024-03-09', 0),
(8, 'sol', 4.00000000, 'hb', 'pending', 4, '2024-03-09', 0),
(9, 'btc', 1.00000000, '5', 'pending', 4, '2024-03-09', 0.05),
(10, 'sol', 50.00000000, 'hjh', 'pending', 4, '2024-03-09', 0.05),
(11, 'btc', 1.00000000, '432', 'pending', 4, '2024-03-10', 0.05),
(12, 'eth', 1.00000000, 'bhsdcjshd', 'pending', 4, '2024-03-17', 0.05),
(13, 'eth', 1.00000000, 'bhsdcjshd', 'approved', 4, '2024-03-18', 0.05),
(14, 'eth', 400.00000000, '34342', 'rejected', 4, '2024-03-20', 0.05);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `conversion_history`
--
ALTER TABLE `conversion_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_history`
--
ALTER TABLE `deposit_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`txn_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `withdrawal_history`
--
ALTER TABLE `withdrawal_history`
  ADD PRIMARY KEY (`withdrawal_id`),
  ADD KEY `uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `conversion_history`
--
ALTER TABLE `conversion_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `deposit_history`
--
ALTER TABLE `deposit_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `txn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `withdrawal_history`
--
ALTER TABLE `withdrawal_history`
  MODIFY `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `withdrawal_history`
--
ALTER TABLE `withdrawal_history`
  ADD CONSTRAINT `withdrawal_history_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
