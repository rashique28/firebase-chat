-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2021 at 01:25 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `firebase_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_record`
--

CREATE TABLE `chat_record` (
  `chat_uuid` varchar(36) NOT NULL,
  `user_1_uuid` varchar(36) NOT NULL,
  `user_2_uuid` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chat_record`
--

INSERT INTO `chat_record` (`chat_uuid`, `user_1_uuid`, `user_2_uuid`) VALUES
('b9050192-d21c-11eb-a845-ac220bc4122e', '60a88a2c-d21c-11eb-a845-ac220bc4122e', '50d6529f-d21c-11eb-a845-ac220bc4122e');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `fullname`, `email`, `username`, `password`) VALUES
(1, '50d6529f-d21c-11eb-a845-ac220bc4122e', 'Rashique Khan', '', 'rashique', '$2y$08$9QWVuXFBzhzHQRaWodlDGek0HOjr2RmvJssdlImHwWmP2dgWQ6BiG'),
(2, '60a88a2c-d21c-11eb-a845-ac220bc4122e', 'Ayan Khan', '', 'ayan', '$2y$08$7I/yXSMhXp1yIsW/Mwdz4OB8DtnN0iqL3p8DUSi3M4J6l2VuQC3Ne'),
(3, '596cbd77-d21e-11eb-a845-ac220bc4122e', 'Ankit Kumavat', '', 'ankit', '$2y$08$g/R2cVY7xO7yXUcJGSD1eeXFME/q01.3vHmoUJE9vAHAu2i1iLVcu'),
(4, '65f7c630-d21e-11eb-a845-ac220bc4122e', 'Sachin Sharma', '', 'sachin', '$2y$08$GJXaRr9iUXxzBEPqqn0dy.cfrBpw5PiFpiE86fQrs2qBCaLx1EPMe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_record`
--
ALTER TABLE `chat_record`
  ADD UNIQUE KEY `chat_uuid` (`chat_uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`,`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
