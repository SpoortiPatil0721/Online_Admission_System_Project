-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2025 at 01:01 PM
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
-- Database: `kle_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$B4lZEVZ8R5Npa12JzfYrAeMk1MgLTgP.a2hfgZaUyaLlFXHIiLtHG');

-- --------------------------------------------------------

--
-- Table structure for table `category_details`
--

CREATE TABLE `category_details` (
  `application_no` varchar(50) NOT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `physically_handicapped` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_details`
--

INSERT INTO `category_details` (`application_no`, `nationality`, `category`, `gender`, `dob`, `religion`, `physically_handicapped`, `created_at`, `updated_at`) VALUES
('', 'Indian', 'General', 'Male', '0221-03-21', 'Hindu', 'No', '2025-06-01 16:26:56', '2025-06-01 16:26:56'),
('', 'Indian', 'ST', 'Male', '0543-06-07', 'Hindu', 'No', '2025-06-02 16:09:27', '2025-06-02 16:09:27'),
('APP202505301399', 'Indian', 'OBC', 'Other', '0003-02-21', 'Jain', 'Yes', '2025-06-06 19:38:49', '2025-06-06 19:38:49'),
('APP202506165933', 'Indian', 'General', 'Female', '1969-05-06', 'Hindu', 'No', '2025-06-16 07:31:52', '2025-06-16 07:31:52'),
('APP202505308381', 'Indian', 'ST', 'Other', '0009-06-07', 'Christian', 'No', '2025-06-16 09:55:09', '2025-06-16 09:55:09'),
('APP202505289979', 'Indian', 'General', 'Male', '2233-02-21', 'Hindu', 'Yes', '2025-06-17 14:04:37', '2025-06-17 14:04:37'),
('APP202506208025', 'Indian', 'General', 'Male', '0033-02-21', 'Hindu', 'No', '2025-06-20 15:15:40', '2025-06-20 15:15:40'),
('APP202506244659', 'Indian', 'General', 'Female', '0021-06-28', 'Hindu', 'No', '2025-06-24 07:16:37', '2025-06-24 07:16:37'),
('APP202506245788', 'Indian', 'OBC', 'Female', '2003-08-12', 'Hindu', 'No', '2025-06-24 08:07:41', '2025-06-24 08:07:41'),
('APP202508271294', 'Indian', 'General', 'Female', '2003-03-21', 'Hindu', 'No', '2025-08-27 07:14:27', '2025-08-27 07:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `message`, `submitted_at`) VALUES
(1, 'xyx', 'xyz@gmail.com', '8147400007', 'i want to know about ur addmission process', '2025-05-27 07:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `guardian_address_details`
--

CREATE TABLE `guardian_address_details` (
  `id` int(11) NOT NULL,
  `application_no` varchar(20) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `father_phone` varchar(15) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(15) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `correspondence_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pin_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guardian_address_details`
--

INSERT INTO `guardian_address_details` (`id`, `application_no`, `father_name`, `father_occupation`, `father_phone`, `mother_name`, `mother_occupation`, `mother_phone`, `permanent_address`, `correspondence_address`, `city`, `state`, `pin_code`, `created_at`) VALUES
(1, 'APP202505301399', 'Kaluda d pil', 'EX-Militaran', '08147406687', 'renuka ennavar', 'Household', '08147400087', 'Bus Stand  colony , Haliyal', 'Bus Stand olony , Haliyal', 'UTTARA KANNADA', 'Karnataka', '581359', '2025-06-12 15:14:57'),
-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `application_no` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `application_no`, `title`, `message`, `created_at`) VALUES
(1, '', 'Holiday', 'Tom is holiday due to holi fest', '2025-06-17 23:57:35'),
(2, 'APP202505289979', 'Application Status Updated', 'Your application (APP202505289979) has been verified.', '2025-06-20 16:13:12'),
(3, 'APP202506208025', 'Application Status Updated', 'Your application (APP202506208025) has been verified.', '2025-06-20 20:52:21'),
(4, 'APP202505301399', 'Application Status Updated', 'Your application (APP202505301399) has been rejected.', '2025-06-20 20:52:35'),
(5, '', 'Notice', 'Admissions are closing Soon please verify ur documents .', '2025-06-20 20:53:29'),
(6, '', 'notice', 'admission will close soon ', '2025-06-23 00:18:12'),
(7, NULL, 'notice', 'admission will close soon ', '2025-06-23 00:29:20'),
(8, 'APP202505289979', 'student alert', 'ur 12th marksheet should be uploded soon ', '2025-06-23 00:30:35'),
(9, 'APP202506165933', 'Application Status Updated', 'Your application (APP202506165933) has been verified.', '2025-06-24 13:05:00'),
(10, 'APP202506244659', 'Application Status Updated', 'Your application (APP202506244659) has been verified.', '2025-06-24 13:05:09'),
(11, 'APP202506245788', 'Application Status Updated', 'Your application (APP202506245788) has been verified.', '2025-06-24 13:43:31'),
(12, 'APP202506165933', 'Application Status Updated', 'Your application (APP202506165933) has been verified.', '2025-08-26 17:29:35');

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `application_no` varchar(50) NOT NULL,
  `payment_mode` enum('upi','card') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_status` enum('paid','pending') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`application_no`, `payment_mode`, `transaction_id`, `payment_status`, `paid_at`, `created_at`) VALUES
('APP202505289979', 'upi', NULL, 'paid', '2025-06-17 14:21:50', '2025-06-17 14:21:50'),
('APP202505301399', 'upi', NULL, 'paid', '2025-06-16 15:23:35', '2025-06-13 11:04:30'),
('APP202505308381', 'upi', NULL, 'paid', '2025-06-16 10:00:11', '2025-06-16 10:00:11'),
('APP202506165933', 'upi', NULL, 'paid', '2025-06-16 07:35:47', '2025-06-16 07:35:47'),
('APP202506208025', 'upi', NULL, 'paid', '2025-06-20 15:18:37', '2025-06-20 15:18:37'),
('APP202506244659', 'upi', NULL, 'paid', '2025-06-24 07:32:49', '2025-06-24 07:32:49'),
('APP202506245788', 'upi', NULL, 'paid', '2025-06-24 08:11:35', '2025-06-24 08:11:35'),
('APP202508271294', 'upi', NULL, 'paid', '2025-08-27 07:19:48', '2025-08-27 07:19:48');

-- --------------------------------------------------------

--
-- Table structure for table `program_details`
--

CREATE TABLE `program_details` (
  `id` int(11) NOT NULL,
  `program_level` varchar(50) DEFAULT NULL,
  `stream` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `source_of_info` varchar(100) DEFAULT NULL,
  `tenth_board` varchar(100) DEFAULT NULL,
  `tenth_school` varchar(100) DEFAULT NULL,
  `tenth_year` year(4) DEFAULT NULL,
  `tenth_marksheet` varchar(255) DEFAULT NULL,
  `twelfth_board` varchar(100) DEFAULT NULL,
  `twelfth_college` varchar(100) DEFAULT NULL,
  `twelfth_year` year(4) DEFAULT NULL,
  `twelfth_received` enum('yes','no') DEFAULT NULL,
  `twelfth_marksheet` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `application_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_details`
--

INSERT INTO `program_details` (`id`, `program_level`, `stream`, `program`, `source_of_info`, `tenth_board`, `tenth_school`, `tenth_year`, `tenth_marksheet`, `twelfth_board`, `twelfth_college`, `twelfth_year`, `twelfth_received`, `twelfth_marksheet`, `submitted_at`, `application_no`) VALUES
(8, 'UG', 'computer', 'BCA', 'website', 'state board', 'kud', '2024', 'uploads/12 marks card.pdf', 'state', 'ccss', '2023', 'yes', 'uploads/12 marks card.pdf', '2025-06-12 14:01:57', 'APP202505301399'),
(11, 'UG', 'computer', 'BCA', 'social', 'State Board', 'KUD', '2022', 'uploads/1st sem marks .pdf', 'State Board', 'ABCD', '2023', 'yes', 'uploads/2nd sem mark.pdf', '2025-06-16 07:33:18', 'APP202506165933'),
(12, 'PG', 'computer', 'BCA', 'friend', 'state board', 'kud', '2022', 'uploads/3 sem result .pdf', 'state', 'ABCD', '2024', 'yes', 'uploads/4th sem result .pdf', '2025-06-16 09:56:10', 'APP202505308381'),
(15, 'PG', 'computer', 'BCA', 'ad', 'state board', 'kud', '2022', 'uploads/Spoorti Patil resume3.pdf', 'State Board', 'ABCD', '2023', 'no', '', '2025-06-17 14:05:16', 'APP202505289979'),
(16, 'UG', 'computer', 'BCA', 'ad', 'Qwert', 'VVdse', '2022', 'uploads/Study Certificate.pdf', 'Asdf', 'cvbn', '2024', 'no', '', '2025-06-20 15:16:47', 'APP202506208025'),
(17, 'PG', 'computer', 'BCA', 'social', 'state board', 'kle', '2022', 'uploads/10th marks card .pdf', 'State Board', 'vvdse', '2024', 'no', '', '2025-06-24 07:31:44', 'APP202506244659'),
(18, 'PG', 'computer', 'BCA', 'friend', 'state board', 'RNS Vidyaniketan School', '2024', 'uploads/3rd sem fee receipt .pdf', 'State Board', 'pc jabin science college', '2024', 'no', '', '2025-06-24 08:09:12', 'APP202506245788'),
(19, 'UG', 'computer', 'BCA', 'website', 'State Board', 'KUD', '2022', 'uploads/OBOT HEADING.pdf', 'state', 'ABCD', '2024', 'no', '', '2025-08-27 07:17:07', 'APP202508271294');

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_documents`
--

CREATE TABLE `uploaded_documents` (
  `id` int(11) NOT NULL,
  `application_no` varchar(50) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `aadhar_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploaded_documents`
--

INSERT INTO `uploaded_documents` (`id`, `application_no`, `photo_path`, `signature_path`, `aadhar_path`, `uploaded_at`) VALUES
(1, 'APP202505301399', 'uploads/photo_APP202505301399.jpg', 'uploads/signature_APP202505301399.jpg', 'uploads/aadhar_APP202505301399.pdf', '2025-06-12 14:52:32'),
(2, 'APP202506165933', 'uploads/photo_APP202506165933.jpg', 'uploads/signature_APP202506165933.jpg', 'uploads/aadhar_APP202506165933.pdf', '2025-06-16 07:33:38'),
(3, 'APP202506165933', 'uploads/photo_APP202506165933.jpg', 'uploads/signature_APP202506165933.jpg', 'uploads/aadhar_APP202506165933.pdf', '2025-06-16 07:34:07')
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `application_no` varchar(20) DEFAULT NULL,
  `application_status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `phone`, `password`, `gender`, `application_no`, `application_status`, `created_at`) VALUES
(27, 'Rakshata Kelasang', 'RAKSHATA', 'rakshata@gmail.com', '636000060', '$2y$10$ve9gKDUDitIlyhp0/Woz0OApqotnGa/OCaGVkJDVbiJUKKIASWLz.', 'Female', 'APP202506245788', 'Verified', '2025-06-24 08:06:09'),
(28, 'Prachi Patil', 'Prachi', 'prachi@gmail.com', '1234567890', '$2y$10$eut.dWQ0Yg18t/.xQ3VVqudkmLlgeTgVhtcBIntSXB7DZ3rLWx/ae', 'Female', 'APP202508271294', 'Pending', '2025-08-27 07:11:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guardian_address_details`
--
ALTER TABLE `guardian_address_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`application_no`);

--
-- Indexes for table `program_details`
--
ALTER TABLE `program_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploaded_documents`
--
ALTER TABLE `uploaded_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `application_no` (`application_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guardian_address_details`
--
ALTER TABLE `guardian_address_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `program_details`
--
ALTER TABLE `program_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `uploaded_documents`
--
ALTER TABLE `uploaded_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
