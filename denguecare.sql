-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 01:26 PM
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
-- Database: `denguecare`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `physician_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `physician_id`, `appointment_date`, `created_at`) VALUES
(1, 5, 1, '2025-05-22 17:08:00', '2025-05-06 19:08:39'),
(2, 5, 1, '2025-05-22 17:08:00', '2025-05-06 19:08:54'),
(3, 5, 2, '2025-05-07 19:26:00', '2025-05-06 19:26:07'),
(4, 5, 8, '2025-05-06 08:00:00', '2025-05-07 11:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `blood_banks`
--

CREATE TABLE `blood_banks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_banks`
--

INSERT INTO `blood_banks` (`id`, `name`, `address`, `contact`, `latitude`, `longitude`) VALUES
(1, 'Bangladesh Red Crescent Blood Bank', '7/5 Aurongzeb Road, Mohammadpur, Dhaka 1207', '+880-2-9116563', 23.758900, 90.364800),
(2, 'Quantum Blood Bank', '31/1, Shahid Smrity School Road, Mirpur, Dhaka 1216', '+880-2-8056782', 23.798800, 90.353700),
(3, 'Sandhani Blood Bank', 'Dhaka Medical College, Dhaka 1000', '+880-2-9668690', 23.725000, 90.398000),
(4, 'Badhan Blood Bank', 'TSC, Dhaka University, Dhaka 1000', '+880-2-9661900', 23.734200, 90.392500),
(5, 'Transfusion Medicine Dept., BSMMU', 'Shahbag, Dhaka 1000', '+880-2-55165600', 23.739000, 90.394900);

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `user_name`, `blood_group`, `hospital_id`, `request_date`, `status`) VALUES
(1, 'Feroz', 'A+', 1, '2025-05-07 11:11:54', 'Pending'),
(2, 'Feroz Mahmud Sheikh', 'B-', 1, '2025-05-07 13:17:48', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `blood_stock`
--

CREATE TABLE `blood_stock` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `units_available` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_stock`
--

INSERT INTO `blood_stock` (`id`, `hospital_id`, `blood_group`, `units_available`) VALUES
(1, 1, 'A+', 10),
(2, 1, 'A-', 5),
(3, 1, 'B+', 8),
(4, 1, 'B-', 3),
(5, 1, 'AB+', 4),
(6, 1, 'AB-', 2),
(7, 1, 'O+', 12),
(8, 1, 'O-', 6),
(9, 2, 'A+', 15),
(10, 2, 'A-', 7),
(11, 2, 'B+', 10),
(12, 2, 'B-', 4),
(13, 2, 'AB+', 6),
(14, 2, 'AB-', 3),
(15, 2, 'O+', 18),
(16, 2, 'O-', 8),
(17, 3, 'A+', 12),
(18, 3, 'A-', 6),
(19, 3, 'B+', 9),
(20, 3, 'B-', 2),
(21, 3, 'AB+', 5),
(22, 3, 'AB-', 1),
(23, 3, 'O+', 15),
(24, 3, 'O-', 7),
(25, 4, 'A+', 8),
(26, 4, 'A-', 4),
(27, 4, 'B+', 7),
(28, 4, 'B-', 3),
(29, 4, 'AB+', 3),
(30, 4, 'AB-', 2),
(31, 4, 'O+', 10),
(32, 4, 'O-', 5),
(33, 5, 'A+', 20),
(34, 5, 'A-', 9),
(35, 5, 'B+', 12),
(36, 5, 'B-', 5),
(37, 5, 'AB+', 7),
(38, 5, 'AB-', 4),
(39, 5, 'O+', 22),
(40, 5, 'O-', 10);

-- --------------------------------------------------------

--
-- Table structure for table `dengue_demographic_data`
--

CREATE TABLE `dengue_demographic_data` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `nid_birth_cert` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `division` enum('Dhaka','Chattogram','Rajshahi','Khulna','Barisal','Sylhet','Rangpur','Mymensingh') NOT NULL,
  `district` varchar(100) NOT NULL,
  `upazila` varchar(100) NOT NULL,
  `union_ward` varchar(100) NOT NULL,
  `residence_type` enum('Urban','Rural') NOT NULL,
  `symptom_onset_date` date DEFAULT NULL,
  `diagnosis_date` date DEFAULT NULL,
  `dengue_type` enum('Dengue Fever','Dengue Hemorrhagic Fever','Severe Dengue') NOT NULL,
  `hospitalized` enum('Yes','No') NOT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `travel_history` text DEFAULT NULL,
  `symptoms` text NOT NULL,
  `previous_dengue` enum('Yes','No','Dont Know') NOT NULL,
  `previous_dengue_year` int(11) DEFAULT NULL,
  `previous_dengue_type` varchar(100) DEFAULT NULL,
  `stagnant_water` enum('Yes','No') NOT NULL,
  `mosquito_protection` enum('Yes','No') NOT NULL,
  `outcome` enum('Recovered','Under Treatment','Deceased') NOT NULL,
  `deceased_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dengue_demographic_data`
--

INSERT INTO `dengue_demographic_data` (`id`, `patient_id`, `full_name`, `age`, `gender`, `nid_birth_cert`, `contact_number`, `email`, `division`, `district`, `upazila`, `union_ward`, `residence_type`, `symptom_onset_date`, `diagnosis_date`, `dengue_type`, `hospitalized`, `hospital_name`, `travel_history`, `symptoms`, `previous_dengue`, `previous_dengue_year`, `previous_dengue_type`, `stagnant_water`, `mosquito_protection`, `outcome`, `deceased_date`, `created_at`, `blood_group`) VALUES
(1, 5, 'FEROZ MAHMUD', 25, 'Male', '414', '01872801865', '2030036@iub.edu.bd', 'Dhaka', 'pp', 'dd', 'dd', 'Urban', '2025-05-08', '2025-05-06', 'Dengue Fever', 'Yes', 'dd', 'No Travel', 'Muscle pain', 'Yes', 2015, 'nn', 'Yes', 'Yes', 'Recovered', NULL, '2025-05-06 18:19:17', 'A+'),
(2, 5, 'Faysal Ashik', 65, 'Male', '6969', '0171122000', 'ashik69@yahoo.com', 'Dhaka', 'Tongi', 'Dhaka', 'Turag River', 'Rural', '2025-05-16', '2025-05-15', 'Dengue Fever', 'Yes', 'AMZ', 'No Travel', 'Headache', 'No', NULL, NULL, 'Yes', 'Yes', 'Recovered', NULL, '2025-05-06 18:29:19', 'A+'),
(3, 5, 'Faysal Ashik', 65, 'Male', '6969', '0171122000', 'ashik69@yahoo.com', 'Dhaka', 'Tongi', 'Dhaka', 'Turag River', 'Rural', '2025-05-16', '2025-05-15', 'Dengue Fever', 'Yes', 'AMZ', 'No Travel', 'Headache', 'No', NULL, NULL, 'Yes', 'Yes', 'Recovered', NULL, '2025-05-06 18:37:26', 'A+'),
(4, 5, 'Fahim Mahmud', 10, 'Male', '1', '1', 'ssss@yahoo.com', 'Dhaka', 'a', 'a', 'a', 'Urban', '2025-05-09', '2025-05-02', 'Dengue Fever', 'Yes', 'a', 'No Travel', 'Muscle pain', 'No', NULL, NULL, 'No', 'Yes', 'Recovered', NULL, '2025-05-06 18:38:29', 'A+'),
(5, 5, 'ashik', 65, 'Male', '3', '3', 'Feroz.mahmudsheikh@yu.v', 'Dhaka', 'pp', 'dd', 'dd', 'Rural', '2025-05-09', '2025-05-09', 'Dengue Fever', 'No', NULL, 'No Travel', 'Rash, Vomiting', 'No', NULL, NULL, 'No', 'Yes', 'Recovered', NULL, '2025-05-06 19:22:14', 'B+'),
(6, 5, 'Feroz Mahmud Sheikh', 25, 'Male', '17478965', '1536225341', '2030036@iub.edu.bd', 'Dhaka', 'Dhaka', 'Wari', 'Narinda', 'Urban', '2025-05-12', '2025-05-16', 'Dengue Fever', 'Yes', 'Asgar Ali Hospital', 'No Travel', 'Headache, Muscle pain', 'No', NULL, NULL, 'No', 'Yes', 'Recovered', NULL, '2025-05-07 10:51:30', 'B-');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `division` varchar(50) NOT NULL,
  `dengue_specialized` tinyint(1) DEFAULT 0,
  `total_seats` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `location`, `division`, `dengue_specialized`, `total_seats`, `available_seats`) VALUES
(1, 'Dhaka Medical College Hospital', 'Dhaka', 'Dhaka', 1, 150, 30),
(2, 'Square Hospital Ltd', 'Dhaka', 'Dhaka', 1, 80, 14),
(3, 'United Hospital', 'Gulshan', 'Dhaka', 1, 100, 20),
(4, 'Apollo Hospital Dhaka', 'Bashundhara', 'Dhaka', 0, 90, 10),
(5, 'Evercare Hospital', 'Dhaka', 'Dhaka', 1, 120, 25),
(6, 'Chittagong Medical College Hospital', 'Chittagong', 'Chattogram', 1, 140, 35),
(7, 'Chittagong General Hospital', 'Chittagong', 'Chattogram', 1, 100, 19),
(8, 'Max Hospital Chattogram', 'Chittagong', 'Chattogram', 0, 70, 12),
(9, 'Imperial Hospital', 'Pahartali', 'Chattogram', 1, 85, 15),
(10, 'Rajshahi Medical College Hospital', 'Rajshahi', 'Rajshahi', 1, 130, 25),
(11, 'Rajshahi City Hospital', 'Rajshahi', 'Rajshahi', 0, 60, 10),
(12, 'Islami Bank Hospital Rajshahi', 'Laxmipur', 'Rajshahi', 1, 90, 18),
(13, 'Khulna Medical College Hospital', 'Khulna', 'Khulna', 1, 110, 22),
(14, 'Khulna Diagnostic Center', 'Khulna', 'Khulna', 0, 50, 8),
(15, 'Gazi Medical Hospital', 'Khulna', 'Khulna', 1, 75, 15),
(16, 'Barisal Sher-e-Bangla Medical College', 'Barisal', 'Barisal', 1, 100, 20),
(17, 'Barisal General Hospital', 'Barisal', 'Barisal', 0, 80, 10),
(18, 'Sylhet MAG Osmani Medical College', 'Sylhet', 'Sylhet', 1, 120, 25),
(19, 'Sylhet Women’s Medical College', 'Sylhet', 'Sylhet', 0, 60, 10),
(20, 'North East Medical College Hospital', 'Sylhet', 'Sylhet', 1, 90, 18),
(21, 'Rangpur Medical College Hospital', 'Rangpur', 'Rangpur', 1, 110, 20),
(22, 'Prime Medical College Hospital', 'Rangpur', 'Rangpur', 0, 70, 12),
(23, 'Mymensingh Medical College Hospital', 'Mymensingh', 'Mymensingh', 1, 130, 30),
(24, 'Community Based Medical College', 'Mymensingh', 'Mymensingh', 0, 80, 15),
(25, 'Delta Health Care Mymensingh', 'Mymensingh', 'Mymensingh', 1, 95, 20);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_bookings`
--

CREATE TABLE `hospital_bookings` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `seat_type` varchar(50) NOT NULL,
  `booking_date` date NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_bookings`
--

INSERT INTO `hospital_bookings` (`id`, `patient_id`, `hospital_id`, `seat_type`, `booking_date`, `created_at`) VALUES
(1, 5, 17, 'Private Cabin', '2025-05-20', '2025-05-07 13:29:40'),
(2, 5, 17, 'Private Cabin', '2025-05-20', '2025-05-07 13:29:45'),
(3, 5, 7, 'General Ward', '2025-05-12', '2025-05-07 15:01:33'),
(4, 5, 2, 'ICU', '2025-05-21', '2025-05-07 17:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `full_name`, `username`, `password`, `dob`, `gender`, `created_at`) VALUES
(1, 'FEROZ MAHMUD', 'feroz01', '$2y$10$7pwoABhc0niCMLJ1PpCp6un1tPmYXGKUj1LvUb027H0cPV3EIP5EG', '2004-06-16', 'Male', '2025-05-06 13:57:20'),
(2, 'Arham Hossain', 'arham1', '$2y$10$ep4bhujJdDA4Qm2ePsjkBeRZ7zzevc2y6isX0aq8G6gFQUIoQafwi', '2006-03-06', 'Male', '2025-05-06 14:15:41'),
(3, 'ASHIK', 'ashik1', '$2y$10$UlC9Wp3r9a2XReXhuUN.KuKpImzWo73dqyt7xpqPWvVrmwtTx1EuS', '2025-05-01', 'Male', '2025-05-06 14:22:00'),
(4, 'sumaiya m', 'sumaiya1', '$2y$10$MvEHfmtCVKo5GFeHjuPrROrjKJvR42MHSN34knwhsVEiueedYrX.i', '2025-05-08', 'Female', '2025-05-06 14:28:58'),
(5, 'paitent01', 'paitent001', '$2y$10$gqc.LAHtYcojoKKdb8.S0.nRdX9PozC6gQMYKSHgZpPbMqSACl/bK', '2025-05-01', 'Male', '2025-05-06 17:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `physicians`
--

CREATE TABLE `physicians` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `nid_no` varchar(50) NOT NULL,
  `bmdc_reg_no` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physicians`
--

INSERT INTO `physicians` (`id`, `full_name`, `gender`, `email`, `mobile`, `nid_no`, `bmdc_reg_no`, `username`, `password`, `created_at`) VALUES
(1, 'FEROZ MAHMUD', 'Male', 'burgerboy_sheikh2021@yahoo.com', '01872801865', 'aa', 'ssss', 'arham', '$2y$10$g8YnDo/nE4Jp3xQjOD4bJ.pGvC8xihX.Whd3tjQU9Y.4hQONikcj2', '2025-05-06 13:58:01'),
(2, 'ASHIK', 'Male', 'ssss@yahoo.com', '01872801865', 'sss', 'sss', 'feroz101', '$2y$10$jbS6l3xlNOhfoBL2jAXNO.ast..5X1lzPXDcwtMFlyUW0.Cj3BN4G', '2025-05-06 14:11:23'),
(8, 'ASHIK', 'Male', 'ashik69@yahoo.com', '01629303159', '777', '786', 'ashik1', '$2y$10$bokCyyJTOZLyyOda3vorreu76buYonbE65e/RDveVnlVDSA6A8qra', '2025-05-06 14:21:35'),
(9, 'mubashira', 'Female', 'm@gmail.com', '0187878524', '000', '111', 'm1', '$2y$10$xDJIxkKYF7ZbjJyiOVpO7.tafyAzLFDZZzzlaqLsPKXbAM2KBd.zG', '2025-05-06 14:29:30'),
(10, 'asa', 'Male', 'fahim20@gmail.com', '01872801865', '22', '22', 'm2', '$2y$10$73PF6ke6Ja33md3jjWIhT.i4VkrG2FUVtX1EGkmFbjhmIxoDu7JFe', '2025-05-06 17:36:30'),
(11, 'phy001', 'Male', 'phy001@gmail.com', '16242107', '1122', '1212', 'phy001', '$2y$10$AvM.HLdSKa0O8v8ZHSxirOtcJZ5hRRNGU1Ywx0JtMdVGx8YdyIrE2', '2025-05-06 17:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `patient_id`, `file_path`, `created_at`) VALUES
(1, 5, 'Uploads/681b083bb8022_Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-07 13:14:03'),
(2, 5, 'Uploads/681b084823386_Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-07 13:14:16'),
(3, 5, 'Uploads/681b087bb269b_Yellow_Warbler_crop_Isaac_Grant.jpg', '2025-05-07 13:15:07'),
(4, 5, 'Uploads/681b3e390199d_Screenshot_1.jpg', '2025-05-07 17:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `physician_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `time_slot` datetime NOT NULL,
  `status` enum('Booked','Available') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `symptom_assessments`
--

CREATE TABLE `symptom_assessments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `result` enum('Positive','Negative') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptom_assessments`
--

INSERT INTO `symptom_assessments` (`id`, `patient_id`, `name`, `dob`, `sex`, `result`, `created_at`) VALUES
(1, 5, 'FEROZ MAHMUD', '2025-05-01', 'Male', 'Positive', '2025-05-06 18:53:55'),
(2, 5, 'Ashik', '1999-11-20', 'Female', 'Negative', '2025-05-06 18:55:04'),
(3, 5, 'Ashik', '1999-11-20', 'Female', 'Negative', '2025-05-06 18:55:12'),
(4, 5, 'FEROZ MAHMUD', '2025-05-13', 'Male', 'Negative', '2025-05-06 19:24:06'),
(5, 5, 'FEROZ MAHMUD', '2025-05-13', 'Male', 'Negative', '2025-05-06 19:24:56'),
(6, 5, 'FEROZ MAHMUD', '2025-05-13', 'Male', 'Negative', '2025-05-06 19:25:06'),
(7, 5, 'FEROZ MAHMUD', '2025-05-13', 'Male', 'Negative', '2025-05-06 19:25:35'),
(8, 5, 'Feroz Mahmud Sheikh', '1999-08-24', 'Male', 'Positive', '2025-05-07 10:57:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `physician_id` (`physician_id`);

--
-- Indexes for table `blood_banks`
--
ALTER TABLE `blood_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_stock`
--
ALTER TABLE `blood_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dengue_demographic_data`
--
ALTER TABLE `dengue_demographic_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_bookings`
--
ALTER TABLE `hospital_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `physicians`
--
ALTER TABLE `physicians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nid_no` (`nid_no`),
  ADD UNIQUE KEY `bmdc_reg_no` (`bmdc_reg_no`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `physician_id` (`physician_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `symptom_assessments`
--
ALTER TABLE `symptom_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blood_banks`
--
ALTER TABLE `blood_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_stock`
--
ALTER TABLE `blood_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `dengue_demographic_data`
--
ALTER TABLE `dengue_demographic_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hospital_bookings`
--
ALTER TABLE `hospital_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `physicians`
--
ALTER TABLE `physicians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `symptom_assessments`
--
ALTER TABLE `symptom_assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`);

--
-- Constraints for table `dengue_demographic_data`
--
ALTER TABLE `dengue_demographic_data`
  ADD CONSTRAINT `dengue_demographic_data_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `hospital_bookings`
--
ALTER TABLE `hospital_bookings`
  ADD CONSTRAINT `hospital_bookings_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`physician_id`) REFERENCES `physicians` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `symptom_assessments`
--
ALTER TABLE `symptom_assessments`
  ADD CONSTRAINT `symptom_assessments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
