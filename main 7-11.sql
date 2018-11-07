-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2018 at 09:13 PM
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
(16, 'احمد', '0909090909', 'mohammed4bs@gmail.com', 'Cairo    nbnm'),
(17, 'محمد', '010', 'mohammed4bs@gmail.com', 'Cairo'),
(18, 'محمد ابراهيم', '01021000068', 'mohammed4bs@gmail.com', 'Cairo'),
(19, 'محمد عباس', '01021000068', 'mohammed4bs@gmail.com', 'Cairo');

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
(7, 'الحدائق'),
(8, 'الندي'),
(10, 'الزهراء');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `client_id` int(11) NOT NULL,
  `contract_kind` int(11) NOT NULL DEFAULT '0',
  `total_space` double DEFAULT NULL,
  `contract_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`contract_id`, `description`, `client_id`, `contract_kind`, `total_space`, `contract_date`) VALUES
(3, 'new contract', 18, 0, 3.1736111111111, '2018-11-07'),
(4, 'new contract', 19, 0, 1.0434027777778, '2010-02-02');

-- --------------------------------------------------------

--
-- Table structure for table `contract_units`
--

CREATE TABLE `contract_units` (
  `contract_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `unit_space` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contract_units`
--

INSERT INTO `contract_units` (`contract_id`, `unit_id`, `unit_space`) VALUES
(3, 67, 2.0885416666667),
(3, 68, 1.0850694444444),
(4, 69, 1.0434027777778);

-- --------------------------------------------------------

--
-- Table structure for table `elec`
--

CREATE TABLE `elec` (
  `elec_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `prev_reading` int(11) NOT NULL,
  `prev_reading_date` date NOT NULL,
  `current_reading` int(11) NOT NULL,
  `current_reading_date` date NOT NULL,
  `rate` double NOT NULL DEFAULT '1.45',
  `elec_balance` int(11) NOT NULL,
  `billed_to_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `elec`
--

INSERT INTO `elec` (`elec_id`, `contract_id`, `prev_reading`, `prev_reading_date`, `current_reading`, `current_reading_date`, `rate`, `elec_balance`, `billed_to_date`) VALUES
(1, 3, 0, '2018-11-07', 0, '0000-00-00', 1.45, 0, '0000-00-00'),
(2, 4, 0, '2018-11-07', 0, '0000-00-00', 1.45, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `maint`
--

CREATE TABLE `maint` (
  `maint_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `balance` int(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `billed_to_date` date NOT NULL,
  `maint_fee` int(11) DEFAULT '750'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `maint`
--

INSERT INTO `maint` (`maint_id`, `contract_id`, `balance`, `start_date`, `end_date`, `billed_to_date`, `maint_fee`) VALUES
(3, 3, 0, '2018-11-07', '2018-12-07', '0000-00-00', 750),
(4, 4, 780, '2018-11-07', '2018-12-07', '2018-11-07', 750);

-- --------------------------------------------------------

--
-- Table structure for table `main_invoices`
--

CREATE TABLE `main_invoices` (
  `invoice_id` int(11) NOT NULL,
  `maint_id` int(11) NOT NULL,
  `is_paid` tinyint(4) NOT NULL DEFAULT '0',
  `date_paid` date NOT NULL,
  `invoice_fee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(18, 7, 'الريف البلجيكي'),
(19, 8, 'الريف السويسري');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `reef_id` int(11) NOT NULL,
  `space_f` int(11) NOT NULL,
  `space_q` int(11) NOT NULL,
  `space_s` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `reef_id`, `space_f`, `space_q`, `space_s`) VALUES
(67, '11ج', 18, 2, 2, 3),
(68, '12أ', 18, 1, 2, 1),
(69, '55أ', 19, 1, 1, 1);

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
(1, 'test', '123', 'Mohammed', '1021000068', 1);

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
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `contract_client` (`client_id`);

--
-- Indexes for table `contract_units`
--
ALTER TABLE `contract_units`
  ADD PRIMARY KEY (`contract_id`,`unit_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- Indexes for table `elec`
--
ALTER TABLE `elec`
  ADD PRIMARY KEY (`elec_id`),
  ADD KEY `elec_contract` (`contract_id`);

--
-- Indexes for table `maint`
--
ALTER TABLE `maint`
  ADD PRIMARY KEY (`maint_id`),
  ADD KEY `maint_contracts` (`contract_id`);

--
-- Indexes for table `main_invoices`
--
ALTER TABLE `main_invoices`
  ADD PRIMARY KEY (`invoice_id`);

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
  ADD KEY `unit_to_reef` (`reef_id`);

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
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `elec`
--
ALTER TABLE `elec`
  MODIFY `elec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `maint`
--
ALTER TABLE `maint`
  MODIFY `maint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `main_invoices`
--
ALTER TABLE `main_invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reefs`
--
ALTER TABLE `reefs`
  MODIFY `reef_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contract_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contract_units`
--
ALTER TABLE `contract_units`
  ADD CONSTRAINT `contractID_conunits` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unitID_conunits` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `elec`
--
ALTER TABLE `elec`
  ADD CONSTRAINT `elec_contract` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maint`
--
ALTER TABLE `maint`
  ADD CONSTRAINT `maint_contracts` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reefs`
--
ALTER TABLE `reefs`
  ADD CONSTRAINT `reef_con` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `unit_to_reef` FOREIGN KEY (`reef_id`) REFERENCES `reefs` (`reef_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
