-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1

-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_list`
--

CREATE TABLE `admin_list` (
  `admin_id` int(11) NOT NULL,
  `fullname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_list`
--

INSERT INTO `admin_list` (`admin_id`, `fullname`, `username`, `password`, `type`, `status`, `date_created`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1, 1, '2022-02-10 07:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `deduction_list`
--

CREATE TABLE `deduction_list` (
  `id` int(30) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deduction_list`
--

INSERT INTO `deduction_list` (`id`, `payroll_id`, `name`, `amount`, `date_created`) VALUES
(5, 2, 'Cash Advance', 1500, '2022-02-10 08:01:37'),
(6, 2, 'SSS', 300, '2022-02-10 08:01:37'),
(7, 2, 'HMDF', 100, '2022-02-10 08:01:37'),
(8, 2, 'PhilHealth', 379, '2022-02-10 08:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `department_list`
--

CREATE TABLE `department_list` (
  `department_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `status` text NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department_list`
--

INSERT INTO `department_list` (`department_id`, `name`, `status`, `date_created`) VALUES
(1, 'Information Technology', '1', '2022-02-10 07:38:48'),
(2, 'Human Resource Dept', '1', '2022-02-10 07:39:04'),
(3, 'Marketing', '1', '2022-02-10 07:39:09'),
(4, 'Graphic Design', '1', '2022-02-10 07:39:22'),
(5, 'Engineering', '1', '2022-02-10 07:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `designation_list`
--

CREATE TABLE `designation_list` (
  `designation_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `status` text NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `designation_list`
--

INSERT INTO `designation_list` (`designation_id`, `name`, `status`, `date_created`) VALUES
(1, 'Manager', '1', '2022-02-10 07:39:36'),
(2, 'Sr. Developer', '1', '2022-02-10 07:39:45'),
(3, 'Jr. Developer', '1', '2022-02-10 07:39:51'),
(4, 'Full Stack Developer', '1', '2022-02-10 07:39:57'),
(5, 'Supervisor', '1', '2022-02-10 07:40:02'),
(6, 'Project Manager', '1', '2022-02-10 07:40:11'),
(7, 'Operation Manager', '1', '2022-02-10 07:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `earning_list`
--

CREATE TABLE `earning_list` (
  `id` int(30) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `taxable` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `earning_list`
--

INSERT INTO `earning_list` (`id`, `payroll_id`, `name`, `amount`, `taxable`, `date_created`) VALUES
(3, 2, 'Earnings 1', 1000, 1, '2022-02-10 08:01:37'),
(4, 2, 'Non-Taxable Earnings 1', 2000, 0, '2022-02-10 08:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `employee_list`
--

CREATE TABLE `employee_list` (
  `employee_id` int(11) NOT NULL,
  `code` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `middlename` text DEFAULT NULL,
  `gender` text NOT NULL,
  `dob` text NOT NULL,
  `email` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `monthly_salary` double NOT NULL DEFAULT 0,
  `status` text NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_list`
--

INSERT INTO `employee_list` (`employee_id`, `code`, `firstname`, `lastname`, `middlename`, `gender`, `dob`, `email`, `contact`, `address`, `department_id`, `designation_id`, `monthly_salary`, `status`, `date_created`) VALUES
(1, '23140623', 'Johnny', 'Smith', 'D', 'Male', '1990-06-23', 'jsmith@sample', '09123456789', 'This is a sample Address only', 5, 4, 25000, '1', '2022-02-10 07:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_list`
--

CREATE TABLE `payroll_list` (
  `payroll_id` int(11) NOT NULL,
  `payroll_type` int(11) NOT NULL DEFAULT 1,
  `employee_id` int(11) NOT NULL,
  `payroll_month` varchar(20) NOT NULL,
  `monthly_rate` double NOT NULL DEFAULT 0,
  `daily_rate` double NOT NULL DEFAULT 0,
  `hourly` double NOT NULL DEFAULT 0,
  `per_minute` double NOT NULL DEFAULT 0,
  `no_present` double NOT NULL DEFAULT 0,
  `no_absences` double NOT NULL DEFAULT 0,
  `late_undertime` double NOT NULL DEFAULT 0,
  `ot_min` double NOT NULL DEFAULT 0,
  `gross_income` double NOT NULL DEFAULT 0,
  `taxable_income` double NOT NULL DEFAULT 0,
  `nontaxable_income` double NOT NULL DEFAULT 0,
  `total_deduction` double NOT NULL DEFAULT 0,
  `total_earnings` double NOT NULL DEFAULT 0,
  `withholding_tax` double NOT NULL DEFAULT 0,
  `net_pay` double NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_list`
--

INSERT INTO `payroll_list` (`payroll_id`, `payroll_type`, `employee_id`, `payroll_month`, `monthly_rate`, `daily_rate`, `hourly`, `per_minute`, `no_present`, `no_absences`, `late_undertime`, `ot_min`, `gross_income`, `taxable_income`, `nontaxable_income`, `total_deduction`, `total_earnings`, `withholding_tax`, `net_pay`, `date_created`) VALUES
(2, 2, 1, '2022-01', 25000, 1136.364, 142.046, 2.367, 11, 0, 15, 480, 16600.659, 14600.659, 2000, 2279, 3000, 2920.132, 14321.659, '2022-02-10 07:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `settings_list`
--

CREATE TABLE `settings_list` (
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tax_table_list`
--

CREATE TABLE `tax_table_list` (
  `tax_id` int(11) NOT NULL,
  `payroll_type` int(11) NOT NULL DEFAULT 1,
  `range_from` double NOT NULL DEFAULT 0,
  `range_to` double NOT NULL DEFAULT 0,
  `fixed_tax` double NOT NULL DEFAULT 0,
  `percentage_over` double NOT NULL DEFAULT 0,
  `effective_date` date NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tax_table_list`
--

INSERT INTO `tax_table_list` (`tax_id`, `payroll_type`, `range_from`, `range_to`, `fixed_tax`, `percentage_over`, `effective_date`, `date_created`) VALUES
(1, 2, 0, 10417, 0, 0, '2022-01-01', '2022-02-10 07:43:16'),
(2, 1, 0, 20833, 0, 0, '2022-01-01', '2022-02-10 07:43:30'),
(3, 1, 20834, 33332, 0, 20, '2022-01-01', '2022-02-10 07:43:54'),
(4, 2, 10418, 166660, 0, 20, '2022-01-01', '2022-02-10 07:44:10'),
(5, 1, 33333, 66666, 2500, 25, '2022-01-01', '2022-02-10 07:44:31'),
(6, 2, 16667, 33332, 1250, 25, '2022-01-01', '2022-02-10 07:44:50'),
(7, 2, 0, 8000, 500, 5, '2022-01-01', '2022-02-10 07:45:24'),
(8, 1, 0, 18000, 1000, 10, '2022-01-01', '2022-02-10 07:45:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_list`
--
ALTER TABLE `admin_list`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `deduction_list`
--
ALTER TABLE `deduction_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_id` (`payroll_id`);

--
-- Indexes for table `department_list`
--
ALTER TABLE `department_list`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `designation_list`
--
ALTER TABLE `designation_list`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `earning_list`
--
ALTER TABLE `earning_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_id` (`payroll_id`);

--
-- Indexes for table `employee_list`
--
ALTER TABLE `employee_list`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `department_id` (`department_id`,`designation_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `payroll_list`
--
ALTER TABLE `payroll_list`
  ADD PRIMARY KEY (`payroll_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tax_table_list`
--
ALTER TABLE `tax_table_list`
  ADD PRIMARY KEY (`tax_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_list`
--
ALTER TABLE `admin_list`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deduction_list`
--
ALTER TABLE `deduction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department_list`
--
ALTER TABLE `department_list`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `designation_list`
--
ALTER TABLE `designation_list`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `earning_list`
--
ALTER TABLE `earning_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_list`
--
ALTER TABLE `employee_list`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_list`
--
ALTER TABLE `payroll_list`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_table_list`
--
ALTER TABLE `tax_table_list`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deduction_list`
--
ALTER TABLE `deduction_list`
  ADD CONSTRAINT `deduction_list_ibfk_1` FOREIGN KEY (`payroll_id`) REFERENCES `payroll_list` (`payroll_id`) ON DELETE CASCADE;

--
-- Constraints for table `earning_list`
--
ALTER TABLE `earning_list`
  ADD CONSTRAINT `earning_list_ibfk_1` FOREIGN KEY (`payroll_id`) REFERENCES `payroll_list` (`payroll_id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_list`
--
ALTER TABLE `employee_list`
  ADD CONSTRAINT `employee_list_ibfk_1` FOREIGN KEY (`designation_id`) REFERENCES `designation_list` (`designation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_list_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department_list` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_list`
--
ALTER TABLE `payroll_list`
  ADD CONSTRAINT `payroll_list_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee_list` (`employee_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
