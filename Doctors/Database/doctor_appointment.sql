-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2025 at 12:49 PM
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
-- Database: `doctor_appointment`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `patient_name` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('Pending','Approved','Cancelled') NOT NULL DEFAULT 'Pending',
  `cancellation_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `appointment_date`, `appointment_time`, `created_at`, `patient_name`, `doctor_id`, `email`, `phone`, `status`, `cancellation_reason`) VALUES
(3, 9, '2024-10-17', '21:00:00', '2024-10-16 13:03:37', 'User1', 3, 'user@gmail.com', '1234567899', 'Approved', NULL),
(4, 2, '2024-10-17', '20:20:00', '2024-10-16 13:49:36', 'User2', 4, 'user2@gmail.com', '1236549877', 'Approved', NULL),
(5, 17, '2024-05-16', '12:00:00', '2024-10-17 02:47:02', 'Dobariya Nayan', 4, 'Nayan@gmail.com', '1236549877', 'Approved', NULL),
(6, 9, '2024-10-18', '10:42:00', '2024-10-17 03:10:44', 'User1', 4, 'user@gmail.com', '1234567899', 'Cancelled', NULL),
(7, 18, '2024-12-04', '23:00:00', '2024-12-03 06:13:07', 'Sanjay', 4, 'sanjay@gmail.com', '1234567899', 'Approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'Manish', 'manish@gmail.com', '123456789876', 'how to use', 'i don\'t know how to use this site', '2024-10-12 06:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `user_id`, `phone`, `email`, `gender`, `experience_years`, `address`, `image`) VALUES
(3, 'Peter', 'Therapist', 10, '123456543334', 'Peter@gmail.com', '', 10, 'London', '1.png'),
(4, 'Dr. John Smith', 'Cardiologist', 11, '9876543210', 'JohnSmith@gmail.com', '', 15, 'USA', '2.png'),
(5, 'Dr. Jane Doe', 'Pediatrician', 12, '123456543334', 'JaneDoe@gmail.com', '', 8, 'California', '3.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','doctor','user') NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `created_at`, `role`, `password`) VALUES
(2, 'Sahil', 'sahil@gmail.com', '9876543210', '2024-10-16 09:00:15', 'admin', '$2y$10$qORbm3DC26S4zWfeKdl3gOxY11zfZfJWl6qVoKbSa4WWoWs068aPS'),
(9, 'User', 'user@gmail.com', '1234567899', '2024-10-16 09:25:36', 'user', '$2y$10$44Pqjv8e27Xs242bSu6O8ul9MoFjSDvNZOInPVubJhSPqXX5NxlFe'),
(10, 'Peter', 'Peter@gmail.com', '123456543334', '2024-10-16 12:32:54', 'doctor', '$2y$10$9o2W4isLgB7xiXO1c.vAsOpbCyhkDb0XyKHmvw4UkHHy1jtlwKuOW'),
(11, 'Dr. John Smith', 'JohnSmith@gmail.com', '9876543210', '2024-10-16 12:54:19', 'doctor', '$2y$10$lcIvB591wItX6PYXOFxaM.FWygmW2YrRP/ROV6CnrUqlxTZXFh.6K'),
(12, 'Dr. Jane Doe', 'JaneDoe@gmail.com', '123456543334', '2024-10-16 12:56:34', 'doctor', '$2y$10$HkcThctYlpF/.WGRLq2eo.7Dfz0Pp9je52ECfh5Rp/l0L.5aesROi'),
(13, 'Userhaha', 'user2@gmail.com', '1236549877', '2024-10-16 13:47:51', 'user', '$2y$10$MYvWUjrt3X1THy6no8RYHeqSdkEac3rO1haxwfdrb2s0yYpFjLNtS'),
(15, 'Mahir', 'Mahir@gmail.com', '8866523590', '2024-10-16 16:36:16', 'admin', '$2y$10$dO1w6.3tTWiuKobNj0e3HOtImI7uaWsJaFIGqlJktJ3MFz6GFlfmO'),
(17, 'Nayan', 'nayan@gmail.com', '8866523591', '2024-10-17 02:46:08', 'user', '$2y$10$42lURn3CaYv1uqM1LfGum.aSzDPvOzkYB/CD8Km1Ol617tWUil4gC'),
(18, 'Sanjay patel', 'sanjay@gmail.com', '1234567899', '2024-12-03 06:11:57', 'user', '$2y$10$PC5yg/7pY.TdKBNDtAOXQ.Lq/KYwJQ9FxM/FBpp/A9AalYQ/Chkl6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
