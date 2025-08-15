-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2025 at 04:36 PM
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
-- Database: `lab_automation`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(100) NOT NULL,
  `revision` varchar(50) DEFAULT NULL,
  `manufacturing_number` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `revision`, `manufacturing_number`, `status`, `name`, `created_at`) VALUES
(1122334456, 'Eaque cupiditate dui', 'Impedit in eu ut ex', '734', 'Completed', 'Shad Simon', '2025-08-15 12:16:48'),
(1122334457, 'Dolorem tenetur reru', 'Numquam cillum modi ', '379', 'Completed', 'Quintessa Buckner', '2025-08-15 12:20:41'),
(1122334458, 'Eu reiciendis dolore', 'Consequat Qui cupid', '797', 'Re-Making', 'Lunea Stephens', '2025-08-15 14:19:51'),
(1122334459, 'Neque neque nemo ips', 'Quae est ut aut et q', '494', 'Completed', 'MacKenzie Britt', '2025-08-15 14:24:44');

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `testing_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `testing_type` varchar(100) NOT NULL,
  `result` enum('Pass','Fail') NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('Pending','Approved','Re-Making') DEFAULT 'Pending',
  `tester_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testing`
--

INSERT INTO `testing` (`testing_id`, `product_id`, `testing_type`, `result`, `remarks`, `status`, `tester_name`, `created_at`) VALUES
(21, 1122334456, 'Short Circuit', 'Pass', 'Quia dolor reprehend', '', 'Marshall Fernandez', '2025-08-15 12:17:03'),
(22, 1122334457, 'Mechanical Endurance', 'Fail', 'Ut ut libero volupta', '', 'Brennan Haynes', '2025-08-15 12:22:29'),
(23, 1122334458, 'Temperature Rise', 'Fail', 'Voluptatibus tempor ', 'Re-Making', 'Darrel Rocha', '2025-08-15 14:20:30'),
(24, 1122334459, 'Dielectric Strength', 'Pass', 'Rem voluptas delectu', 'Pending', 'Magee Owens', '2025-08-15 14:25:11'),
(25, 1122334459, 'Dielectric Strength', 'Fail', 'Amet ratione omnis ', '', 'Gavin Brady', '2025-08-15 14:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manufacturer','tester') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'mudassir_ali', '25d55ad283aa400af464c76d713c07ad', 'admin', '2025-08-09 09:14:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`testing_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1122334460;

--
-- AUTO_INCREMENT for table `testing`
--
ALTER TABLE `testing`
  MODIFY `testing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `testing`
--
ALTER TABLE `testing`
  ADD CONSTRAINT `testing_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
