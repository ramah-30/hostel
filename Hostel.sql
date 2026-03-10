-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2025 at 07:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Hostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `statuses` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `property_id`, `student_id`, `booking_date`, `amount`, `statuses`) VALUES
(1, 1, 1, '2025-11-16 23:33:18', 75000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `method` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Pending','Success','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `Id` int(11) NOT NULL,
  `Owner_id` int(11) NOT NULL,
  `Names` varchar(100) NOT NULL,
  `Locations` varchar(100) NOT NULL,
  `Types` varchar(50) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Available_rooms` int(11) NOT NULL,
  `Amenities` text DEFAULT NULL,
  `Descriptions` varchar(255) DEFAULT NULL,
  `Statuses` varchar(20) DEFAULT 'available',
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Image1` varchar(255) DEFAULT NULL,
  `Image2` varchar(255) DEFAULT NULL,
  `Image3` varchar(255) DEFAULT NULL,
  `Image4` varchar(255) DEFAULT NULL,
  `approval` varchar(60) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`Id`, `Owner_id`, `Names`, `Locations`, `Types`, `Price`, `Available_rooms`, `Amenities`, `Descriptions`, `Statuses`, `Created_at`, `Image1`, `Image2`, `Image3`, `Image4`, `approval`) VALUES
(1, 2, 'Kigamboni Hostel', 'Ferry Kigamboni,Dar es Salaam', 'Apartment', 75000.00, 6, 'Wi-Fi,Water,Electricity,Security', 'Vyumba vipo sita(6)\r\nMwanafunzii analipa jumla ya 300000 kwa semester.\r\nWanakaa watu wanne(4) kila chumba.\r\nKaribuni sana', 'available', '2025-11-16 19:42:49', 'img_691a29397a5dd6.51474839.jpg', 'img_691a29397ab377.66964489.jpg', 'img_691a29397abd07.01816396.jpg', 'img_691a29397ac3c2.56920120.jpg', 'Approved'),
(2, 2, 'New Girls Hostel', 'Ferry Kigamboni,Dar es Salaam', 'Apartment', 90000.00, 20, 'Wi-Fi,Water,Electricity,Security', 'Mtu mmoja 360,000/= per semester, Mtu mmoja+kabati na meza 600,000/= per semester,', 'available', '2025-11-16 19:49:35', 'img_691a2acf8a7da6.53298397.jpg', 'img_691a2acf8aa482.04822237.jpg', 'img_691a2acf8aaec4.77333648.jpg', 'img_691a2acf8ab870.27921423.jpg', 'Approved'),
(3, 2, 'Nuru&#039;s Hostel', 'Kigamboni, Dar es salaam', 'Apartment', 75000.00, 4, 'Wi-Fi,Water,Electricity,Security', 'Mwanafunzi analipa 300,000/= per semester, wanakaa wanne(4) kila Chumba', 'available', '2025-11-16 19:57:53', 'img_691a2cc1b9b997.25279846.jpg', 'img_691a2cc1b9e718.97853442.jpg', 'img_691a2cc1b9f0a5.18933732.jpg', '', 'Approved'),
(4, 2, 'Jacob House', 'Chadibwa Kigamboni, Dar es salaam', 'Apartment', 3000000.00, 3, 'Wi-Fi,Water,Electricity,Security,Parking', 'Nyumba in sebule, Jiko, Master 1 na Vyumba 2.', 'available', '2025-11-16 20:09:23', 'img_691a2f73293601.62641798.jpg', 'img_691a2f73295aa4.58972359.jpg', 'img_691a2f73296061.98059228.jpg', '', 'Approved'),
(5, 2, 'Zebra Apartments', 'Kibada Kigamboni, Dar es salaam', 'Apartment', 600000.00, 2, 'Wi-Fi,Water,Electricity,Security,Parking,Furnished', 'Apartment in Maste bedroom, Chumba 1 self, Sebule, Jikon na choo public.', 'available', '2025-11-16 20:12:42', 'img_691a303a109bd8.46987347.jpg', 'img_691a303a10bc32.29348488.jpg', '', '', 'Approved'),
(6, 2, 'House', 'Kisiwani Kigamboni, Dar es salaam', 'Shared Room', 150000.00, 2, 'Water,Electricity,Security', 'Vyumba vipo 2 vyote self, umeme na maji kila mtu anajitegemea.', 'available', '2025-11-16 20:22:50', 'img_691a329a20a1c6.74072150.jpg', 'img_691a329a20c612.65736295.jpg', 'img_691a329a20d125.90124565.jpg', '', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `Userid` int(11) NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNumber` varchar(30) NOT NULL,
  `Roles` enum('Student','House Owner','Admin') DEFAULT 'Student',
  `Pwd` varchar(255) NOT NULL,
  `Dateofregister` date NOT NULL DEFAULT curdate(),
  `Nation_Id` varchar(30) DEFAULT NULL,
  `Code` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Userid`, `Firstname`, `Lastname`, `Email`, `PhoneNumber`, `Roles`, `Pwd`, `Dateofregister`, `Nation_Id`, `Code`) VALUES
(1, 'Rama', 'Yusah', 'ramahfx3@gmail.com', '0674487114', 'Student', '$2y$10$C/ckh3/z525xGd/6x1QaSu/qjqpMovBjRRqWLxGnDiDXvpuAGCY.i', '2025-11-16', NULL, 0),
(2, 'Juma', 'Hamis', 'softwaredev668@gmail.com', '0674487114', 'House Owner', '$2y$10$gUMgZXNwwzCE8K4wUxzp4OHyaeemDnfZw7itYndxLDbsRspn5A2Ae', '2025-11-16', '12345678912345678900', 0),
(3, 'Admin', 'Admin', 'admin@gmail.com', '0674487114', 'Admin', '$2y$10$cW9lVpB3812TMW024d7gYeC1ZvTUc43FIm4/Xd006g4NoB6Ygdk4m', '2025-11-16', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `Userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `Users` (`Userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
