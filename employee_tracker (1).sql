-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 17, 2025 at 01:00 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `regular_hours` decimal(5,2) DEFAULT NULL,
  `overtime_hours` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `time_in`, `time_out`, `regular_hours`, `overtime_hours`) VALUES
(1, 2, '2025-11-01', '08:00:00', '17:00:00', 8.00, 1.00),
(2, 2, '2025-11-02', '08:00:00', '17:00:00', 8.00, 1.00),
(3, 2, '2025-11-03', '08:00:00', '17:30:00', 8.00, 1.50),
(4, 2, '2025-11-04', '08:00:00', '17:00:00', 8.00, 1.00),
(5, 2, '2025-11-05', '08:15:00', '17:15:00', 8.00, 1.00),
(6, 2, '2025-11-06', '08:00:00', '16:45:00', 7.50, 0.50),
(7, 2, '2025-11-07', '08:00:00', '17:00:00', 8.00, 1.00),
(8, 2, '2025-11-08', '08:00:00', '17:30:00', 8.00, 1.50),
(9, 2, '2025-11-09', '08:00:00', '17:00:00', 8.00, 1.00),
(10, 2, '2025-11-10', '08:00:00', '17:00:00', 8.00, 1.00),
(11, 3, '2025-11-01', '08:15:00', '16:45:00', 7.50, 0.50),
(12, 3, '2025-11-02', '08:15:00', '17:00:00', 7.50, 0.50),
(13, 3, '2025-11-03', '08:15:00', '17:00:00', 7.50, 0.50),
(14, 3, '2025-11-04', '08:15:00', '16:45:00', 7.50, 0.50),
(15, 3, '2025-11-05', '08:15:00', '17:15:00', 8.00, 1.00),
(16, 3, '2025-11-06', '08:15:00', '17:00:00', 7.50, 0.50),
(17, 3, '2025-11-07', '08:15:00', '17:00:00', 7.50, 0.50),
(18, 3, '2025-11-08', '08:15:00', '17:15:00', 8.00, 1.00),
(19, 3, '2025-11-09', '08:15:00', '17:00:00', 7.50, 0.50),
(20, 3, '2025-11-10', '08:15:00', '16:45:00', 7.50, 0.50),
(21, 4, '2025-11-01', '09:00:00', '18:00:00', 8.00, 1.00),
(22, 4, '2025-11-02', '09:00:00', '18:00:00', 8.00, 1.00),
(23, 4, '2025-11-03', '09:00:00', '18:30:00', 8.00, 1.50),
(24, 4, '2025-11-04', '09:00:00', '18:00:00', 8.00, 1.00),
(25, 4, '2025-11-05', '09:00:00', '18:15:00', 8.00, 1.00),
(26, 4, '2025-11-06', '09:00:00', '18:00:00', 8.00, 1.00),
(27, 4, '2025-11-07', '09:00:00', '18:30:00', 8.00, 1.50),
(28, 4, '2025-11-08', '09:00:00', '18:00:00', 8.00, 1.00),
(29, 4, '2025-11-09', '09:00:00', '18:00:00', 8.00, 1.00),
(30, 4, '2025-11-10', '09:00:00', '18:00:00', 8.00, 1.00),
(31, 5, '2025-11-01', '08:00:00', '17:30:00', 8.00, 1.50),
(32, 5, '2025-11-02', '08:00:00', '17:30:00', 8.00, 1.50),
(33, 5, '2025-11-03', '08:00:00', '17:00:00', 8.00, 1.00),
(34, 5, '2025-11-04', '08:00:00', '17:30:00', 8.00, 1.50),
(35, 5, '2025-11-05', '08:00:00', '17:00:00', 8.00, 1.00),
(36, 5, '2025-11-06', '08:00:00', '17:30:00', 8.00, 1.50),
(37, 5, '2025-11-07', '08:00:00', '17:00:00', 8.00, 1.00),
(38, 5, '2025-11-08', '08:00:00', '17:30:00', 8.00, 1.50),
(39, 5, '2025-11-09', '08:00:00', '17:00:00', 8.00, 1.00),
(40, 5, '2025-11-10', '08:00:00', '17:30:00', 8.00, 1.50),
(41, 6, '2025-11-01', '08:30:00', '17:00:00', 7.50, 0.50),
(42, 6, '2025-11-02', '08:30:00', '17:00:00', 7.50, 0.50),
(43, 6, '2025-11-03', '08:30:00', '17:00:00', 7.50, 0.50),
(44, 6, '2025-11-04', '08:30:00', '17:00:00', 7.50, 0.50),
(45, 6, '2025-11-05', '08:30:00', '17:00:00', 7.50, 0.50),
(46, 6, '2025-11-06', '08:30:00', '17:00:00', 7.50, 0.50),
(47, 6, '2025-11-07', '08:30:00', '17:00:00', 7.50, 0.50),
(48, 6, '2025-11-08', '08:30:00', '17:00:00', 7.50, 0.50),
(49, 6, '2025-11-09', '08:30:00', '17:00:00', 7.50, 0.50),
(50, 6, '2025-11-10', '08:30:00', '17:00:00', 7.50, 0.50),
(51, 1, '2025-11-16', '09:00:00', '19:40:00', 8.00, 2.67);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `fullname`, `department`) VALUES
(1, 2, 'John Santos', 'IT'),
(2, 3, 'Maria Dela Cruz', 'HR'),
(3, 4, 'Mark Reyes', 'Marketing'),
(4, 5, 'Anna Lopez', 'IT'),
(5, 6, 'Kevin Ramos', 'Finance'),
(6, 7, 'Elaine Torres', 'Sales'),
(7, 8, 'Christian Bautista', 'Sales'),
(8, 9, 'Jenny Huang', 'IT'),
(9, 10, 'Pauline Mateo', 'HR'),
(10, 11, 'Francis Tan', 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`id`, `employee_id`, `month`, `score`) VALUES
(1, 1, '2025-01', 92),
(2, 2, '2025-01', 85),
(3, 3, '2025-01', 78),
(4, 4, '2025-01', 88),
(5, 5, '2025-01', 95),
(6, 6, '2025-01', 73),
(7, 7, '2025-01', 80),
(8, 8, '2025-01', 90),
(9, 9, '2025-01', 76),
(10, 10, '2025-01', 82);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` enum('admin','employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin'),
(2, 'emp1', '033836b6cedd9a857d82681aafadbc19', 'John Santos', 'employee'),
(3, 'emp2', '033836b6cedd9a857d82681aafadbc19', 'Maria Dela Cruz', 'employee'),
(4, 'emp3', '033836b6cedd9a857d82681aafadbc19', 'Mark Reyes', 'employee'),
(5, 'emp4', '033836b6cedd9a857d82681aafadbc19', 'Anna Lopez', 'employee'),
(6, 'emp5', '033836b6cedd9a857d82681aafadbc19', 'Kevin Ramos', 'employee'),
(7, 'emp6', '033836b6cedd9a857d82681aafadbc19', 'Elaine Torres', 'employee'),
(8, 'emp7', '033836b6cedd9a857d82681aafadbc19', 'Christian Bautista', 'employee'),
(9, 'emp8', '033836b6cedd9a857d82681aafadbc19', 'Jenny Huang', 'employee'),
(10, 'emp9', '033836b6cedd9a857d82681aafadbc19', 'Pauline Mateo', 'employee'),
(11, 'emp10', '033836b6cedd9a857d82681aafadbc19', 'Francis Tan', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `performance`
--
ALTER TABLE `performance`
  ADD CONSTRAINT `performance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
