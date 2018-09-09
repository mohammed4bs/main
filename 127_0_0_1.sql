-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2018 at 04:27 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `main`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `client_name`, `phone`, `email`, `address`) VALUES
(1, 'محمد عباس', '010245', 'aaaaaaaaaaa@mm.com', 'asdf نمتيسشنمب'),
(2, 'احمد حسين', '012', 'a@a.com', 'aaf  fsdf'),
(3, 'محمد ابراهيم', '010210000', 'mohammed4bs@gmail.com', 'نمتتنا ؤبلابلا بؤلاب'),
(4, 'عباس', '020', 'adf@asdf', 'asdfsdfa asdf'),
(5, 'ahmed', '010', 'asdf@afsdf', 'gjkghjk dfjhfd'),
(6, 'محمد ابراهيم', '012', 'mohammed4bs@gmail.com', 'ششش'),
(7, 'عباس', '012333', 'abbas@yahoo.com', 'Abbas el akad'),
(9, 'asdfasdf', '01021000068', 'mohammed4bs@gmail.com', 'Cairo'),
(10, 'fdsaf', '01021000068', 'mohammed4bs@gmail.com', 'Cairo'),
(11, 'احمد حلمي', '012245', 'ahmed@ahmed.com', 'akjdf 1asdf asdfasdfhgfdh gdfjghd'),
(12, 'احمد حلمي', '012245', 'ahmed@ahmed.com', 'akjdf 1asdf asdfasdfhgfdh gdfjghd'),
(13, 'Mohammed Helmy', '01145', 'a@a.com', 'asdfj dasf456asf asdf sadf45646'),
(14, 'احمد حلمي', '01021000068', 'mohammed4bs@gmail.com', 'Cairo');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`) VALUES
(2, 'اعمار'),
(1, 'الريف');

-- --------------------------------------------------------

--
-- Table structure for table `reefs`
--

CREATE TABLE `reefs` (
  `reef_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `reef_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reefs`
--

INSERT INTO `reefs` (`reef_id`, `company_id`, `reef_name`) VALUES
(5, 1, 'الريف الفرنساوي'),
(6, 2, 'الريف السويسري');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `reef_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `reef_id`) VALUES
(3, '21ب', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `phone`, `group_id`) VALUES
(1, 'test', '123', 'Mohammed Helmy', '010210000', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `company_name` (`company_name`);

--
-- Indexes for table `reefs`
--
ALTER TABLE `reefs`
  ADD PRIMARY KEY (`reef_id`),
  ADD KEY `reef_con` (`company_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `unit_reef` (`reef_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reefs`
--
ALTER TABLE `reefs`
  MODIFY `reef_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reefs`
--
ALTER TABLE `reefs`
  ADD CONSTRAINT `reef_con` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `unit_reef` FOREIGN KEY (`reef_id`) REFERENCES `reefs` (`reef_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
