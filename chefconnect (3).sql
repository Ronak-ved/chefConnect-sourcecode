-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 03:48 PM
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
-- Database: `chefconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `chefs`
--

CREATE TABLE `chefs` (
  `chef_id` int(100) NOT NULL,
  `chef_name` varchar(100) NOT NULL,
  `chef_email` varchar(100) NOT NULL,
  `chef_mobile` varchar(100) NOT NULL,
  `chef_password` varchar(100) NOT NULL,
  `chef_pic` varchar(100) DEFAULT NULL,
  `gender` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `charges` int(100) NOT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `speciality` varchar(100) NOT NULL,
  `bio` varchar(100) DEFAULT NULL,
  `chef_ratings` int(10) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chefs`
--

INSERT INTO `chefs` (`chef_id`, `chef_name`, `chef_email`, `chef_mobile`, `chef_password`, `chef_pic`, `gender`, `city`, `charges`, `experience`, `speciality`, `bio`, `chef_ratings`) VALUES
(9, 'Ronak Ved', 'rv07@gmail.com', '8692889947', '12345678', '', 'male', 'mulund', 300, '2 years', 'rajasthani', 'i love cooking', 4),
(10, 'rahul maharaj', 'rm75@gmail.com', '9756845637', '12345678', NULL, 'male', 'bhandup', 700, NULL, 'gujarati', NULL, 4),
(11, 'jamna lal', 'jamna@gmail.com', '9756845638', '12345678', NULL, 'male', 'Ghatkopar', 300, '5 years', 'Rajasthani', ' i love cooking so much', 4),
(12, 'raj singh', 'sr@gmail.com', '9756845632', '12345678', NULL, 'male', 'mulund', 400, NULL, 'gujarati', NULL, 4),
(13, 'Ramu Gupta', 'ramu@gmail.com', '8749374746', '12345678', NULL, 'male', 'Ghatkopar', 300, '2 years', 'Gujarati', '', 4),
(15, 'Lata Ved', 'lata@gmail.com', '7850049864', '12345678', NULL, 'female', 'Ghatkopar', 400, '5 years', 'Rajasthani', '', 4),
(16, 'Lalu ved', 'lalu@gmail.com', '9846736376', '12345678', NULL, 'male', 'bhandup', 444, NULL, 'gujarati', NULL, 4),
(17, 'Megu Meena', 'megu@gmail.com', '8936547235', '12345678', NULL, 'female', 'bhandup', 300, NULL, 'punjabi', NULL, 4),
(18, 'Bhanu Meena', 'bhanu@gmail.com', '8936547238', '12345678', NULL, 'Female', 'Ghatkopar', 400, '2 years', 'Gujarati', 'i have expertise in gujarati food', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `foodpref` varchar(100) NOT NULL,
  `user_pic` varchar(100) NOT NULL,
  `user_gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `mobile`, `email`, `password`, `city`, `foodpref`, `user_pic`, `user_gender`) VALUES
(51, 'Ronak Ved', '8692889947', 'ronak@gmail.com', '12345678', 'Bhandup', 'rajasthani', '', ''),
(52, 'jeevan sharma', '8953672894', 'jeeevanlal123@gmail.com', '12345678', 'mulund', 'rajasthani', '', ''),
(53, 'Ronak Ved', '8692889944', 'ronakv@gmail.com', '12345678', 'mulund', 'Rajasthani', '', 'Male'),
(54, 'Ronak ved', '8692889933', 'rlv@gmail.com', '12345678', 'ghatkopar', 'gujarati', '', ''),
(55, 'deva singh', '9756845637', 'ds@gmail.com', '12345678', 'mulund', 'Rajasthani', '', ''),
(56, 'Diljeet Singh', '8692889940', 'dsingh@gmail.com', '12345678', 'Bhandup', 'Punjabi', '', 'Male'),
(57, 'Manoj Tambe', '8465899752', 'mt@gmail.com', '12345678', 'Mulund', 'Maharashtrian', '', 'Male'),
(58, 'Pinka Ved', '9846723782', 'pinka@gmail.com', '12345678', 'Mulund', 'Rajasthani', '', 'Female'),
(59, 'Naina Sen', '9846723784', 'naina@gmail.com', '12345678', 'Bhandup', 'Rajasthani', '', 'Female'),
(60, 'Divya Sen', '9837645625', 'divya@gmail.com', '12345678', 'Ghatkopar', 'Maharashtrian', '', 'Female');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chefs`
--
ALTER TABLE `chefs`
  ADD PRIMARY KEY (`chef_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chefs`
--
ALTER TABLE `chefs`
  MODIFY `chef_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
