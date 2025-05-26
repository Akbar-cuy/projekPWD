-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 05:00 AM
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
-- Database: `pockandroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `user_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 5, 'GAMERS GANTENG IDAMAN', '2025-05-18 04:30:08', '2025-05-18 04:30:08'),
(2, 2, 'STREAMER BESAR', '2025-05-18 14:35:27', '2025-05-18 14:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `campaign_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `message` text DEFAULT NULL,
  `donated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_id`, `campaign_id`, `amount`, `message`, `donated_at`) VALUES
(6, 1, 1, 100000.00, 'apa', '2025-05-18 05:48:34'),
(7, 6, 1, 100000.00, 'ngemis blok', '2025-05-18 14:23:38'),
(8, 5, 2, 5000.00, 'sad', '2025-05-18 14:39:49'),
(9, 5, 2, 25000.00, 'sdf', '2025-05-18 14:56:58'),
(10, 5, 2, 25000.00, 'jmkm', '2025-05-18 15:50:08'),
(11, 5, 2, 25000.00, 'sadasd', '2025-05-18 16:05:33'),
(12, 5, 2, 25000.00, 'mnmmk', '2025-05-24 09:43:23'),
(13, 5, 2, 100000.00, 'sadds', '2025-05-24 09:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `status_payment` enum('on progress','failed','expired','success') NOT NULL DEFAULT 'on progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `donation_id`, `transaction_id`, `payment_method`, `paid_amount`, `paid_at`, `status_payment`, `created_at`, `updated_at`) VALUES
(3, 6, 'trx_682974b278b66', 'QRIS', 100000.00, NULL, 'success', '2025-05-18 05:48:34', '2025-05-18 06:07:28'),
(4, 7, 'trx_6829ed6aecf3d', 'LinkAja', 100000.00, NULL, 'success', '2025-05-18 14:23:38', '2025-05-18 14:23:44'),
(5, 8, 'trx_6829f135a0fe8', 'QRIS', 5000.00, NULL, 'success', '2025-05-18 14:39:49', '2025-05-18 14:39:51'),
(6, 9, 'trx_6829f53a79113', 'QRIS', 25000.00, NULL, 'success', '2025-05-18 14:56:58', '2025-05-18 14:57:13'),
(9, 10, 'trx_682a01b090119', 'QRIS', 25000.00, NULL, 'on progress', '2025-05-18 15:50:08', '2025-05-18 15:50:08'),
(11, 11, 'trx_682a054d36fc8', 'QRIS', 25000.00, NULL, 'success', '2025-05-18 16:05:33', '2025-05-18 16:05:34'),
(12, 12, 'trx_683194bb1f6c0', 'LinkAja', 25000.00, NULL, 'success', '2025-05-24 09:43:23', '2025-05-24 09:43:24'),
(13, 13, 'trx_683197c56c62d', 'QRIS', 100000.00, NULL, 'success', '2025-05-24 09:56:21', '2025-05-24 09:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `created_at`, `update_at`) VALUES
(1, 'damarganteng@gmail.com', 'marzgho', 'kecebanget123', '2025-05-16 06:11:22', '2025-05-16 06:11:22'),
(2, 'akbargaming@gmail.com', 'lawliet', 'gamersabis123', '2025-05-16 06:11:22', '2025-05-16 06:11:22'),
(3, 'akusudahgedhe@gmail.com', 'sudah', 'gedhe123', '2025-05-16 07:32:48', '2025-05-16 07:32:48'),
(4, 'apaaja@gmail.com', 'marzyt', 'damar123', '2025-05-16 07:40:10', '2025-05-16 07:40:10'),
(5, 'user@example.com', 'Okk', '1234567', '2025-05-17 04:04:27', '2025-05-17 04:04:27'),
(6, 'ganteng@gmail.com', 'pdsa', '123456', '2025-05-18 14:22:43', '2025-05-18 14:22:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `donation_id` (`donation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
