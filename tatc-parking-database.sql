-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2023 at 06:05 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tatcparking`
--
CREATE DATABASE IF NOT EXISTS `tatcparking` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tatcparking`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` char(11) NOT NULL,
  `prefix_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `adminPos_id` int(11) NOT NULL,
  `password` varchar(20) NOT NULL,
  `img` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `admin_position`
--

CREATE TABLE `admin_position` (
  `adminPos_id` int(11) NOT NULL,
  `adminPos_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `an_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(500) DEFAULT NULL,
  `file` mediumblob DEFAULT NULL,
  `an_date` datetime NOT NULL,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `class_level`
--

CREATE TABLE `class_level` (
  `level_id` int(11) NOT NULL,
  `level_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(11) NOT NULL,
  `district_name_th` varchar(100) NOT NULL,
  `district_name_en` varchar(100) NOT NULL,
  `province_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `geographies`
--

CREATE TABLE `geographies` (
  `geograph_id` int(11) NOT NULL,
  `geograph_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `major_id` int(11) NOT NULL,
  `major_name` varchar(50) NOT NULL,
  `dept_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `noti_id` int(11) NOT NULL,
  `std_id` char(11) DEFAULT NULL,
  `personnel_id` varchar(20) DEFAULT NULL,
  `vehicle_id` varchar(20) DEFAULT NULL,
  `notiType_id` int(11) NOT NULL,
  `notiDate` date NOT NULL,
  `notiTime` time NOT NULL,
  `isRead` char(1) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `admin_receive_status` char(1) NOT NULL,
  `img` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `notification_type`
--

CREATE TABLE `notification_type` (
  `notiType_id` int(11) NOT NULL,
  `details` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `parking_card`
--

CREATE TABLE `parking_card` (
  `parkingCard_id` int(11) NOT NULL,
  `regis_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `isReceived` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `personnel_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `prefix`
--

CREATE TABLE `prefix` (
  `prefix_id` int(11) NOT NULL,
  `prefix_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` int(2) NOT NULL,
  `province_name` varchar(150) NOT NULL,
  `provinceName_th_short` varchar(10) NOT NULL,
  `provinceName_en` varchar(150) NOT NULL,
  `geograph_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `regis_id` int(11) NOT NULL,
  `std_id` char(11) DEFAULT NULL,
  `personnel_id` varchar(20) DEFAULT NULL,
  `vehicle_id` varchar(20) NOT NULL,
  `copyOf_VehicleRegis` mediumblob NOT NULL,
  `copyOf_ownerIdCard` mediumblob NOT NULL,
  `copyOf_userIdCard` mediumblob NOT NULL,
  `img_front` mediumblob DEFAULT NULL,
  `img_side` mediumblob DEFAULT NULL,
  `img_back` mediumblob DEFAULT NULL,
  `pdfSignature` mediumblob DEFAULT NULL,
  `VehicleRegis_filename` varchar(255) DEFAULT NULL,
  `ownerIdCard_filename` varchar(255) DEFAULT NULL,
  `userIdCard_filename` varchar(255) DEFAULT NULL,
  `front_filename` varchar(255) DEFAULT NULL,
  `side_filename` varchar(255) DEFAULT NULL,
  `back_filename` varchar(255) DEFAULT NULL,
  `pdfSign_filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `registration_status`
--

CREATE TABLE `registration_status` (
  `regisStatus_id` int(11) NOT NULL,
  `regisStatus_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `registration_status_detail`
--

CREATE TABLE `registration_status_detail` (
  `regis_id` int(11) NOT NULL,
  `regisStatus_id` int(11) NOT NULL,
  `notpermitreason_id` int(11) DEFAULT NULL,
  `rsd_date` date NOT NULL,
  `rsd_time` time NOT NULL,
  `comment` varchar(100) DEFAULT NULL COMMENT '- not approved\r\n- prepare to print sticker\r\n- sticker printed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `std_id` char(11) NOT NULL,
  `prefix_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `level_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `img` mediumblob NOT NULL,
  `province_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `subdistrict_id` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `subdistrict`
--

CREATE TABLE `subdistrict` (
  `subDistrict_id` varchar(6) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `subDistrictName_th` varchar(100) NOT NULL,
  `subDistrictName_en` varchar(100) NOT NULL,
  `district_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `system` varchar(255) NOT NULL,
  `is_enable` char(1) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` varchar(20) NOT NULL,
  `province_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `vehiType_id` int(11) NOT NULL,
  `vehiBrand_id` int(11) NOT NULL,
  `isOwner` char(1) NOT NULL,
  `vehiModel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `vehicle_brand`
--

CREATE TABLE `vehicle_brand` (
  `vehiBrand_id` int(11) NOT NULL,
  `vehiBrand_name` varchar(100) DEFAULT NULL,
  `orther` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type`
--

CREATE TABLE `vehicle_type` (
  `vehiType_id` int(11) NOT NULL,
  `vehiType_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `prefix_id` (`prefix_id`),
  ADD KEY `adminPos_id` (`adminPos_id`);

--
-- Indexes for table `admin_position`
--
ALTER TABLE `admin_position`
  ADD PRIMARY KEY (`adminPos_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`an_id`);

--
-- Indexes for table `class_level`
--
ALTER TABLE `class_level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `geographies`
--
ALTER TABLE `geographies`
  ADD PRIMARY KEY (`geograph_id`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`noti_id`),
  ADD KEY `std_id` (`std_id`),
  ADD KEY `notiType_id` (`notiType_id`),
  ADD KEY `vehicle_id` (`vehicle_id`) USING BTREE,
  ADD KEY `personnel_id` (`personnel_id`) USING BTREE;

--
-- Indexes for table `notification_type`
--
ALTER TABLE `notification_type`
  ADD PRIMARY KEY (`notiType_id`);

--
-- Indexes for table `parking_card`
--
ALTER TABLE `parking_card`
  ADD PRIMARY KEY (`parkingCard_id`),
  ADD KEY `regis_id` (`regis_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`personnel_id`);

--
-- Indexes for table `prefix`
--
ALTER TABLE `prefix`
  ADD PRIMARY KEY (`prefix_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`),
  ADD KEY `geograph_id` (`geograph_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`regis_id`),
  ADD KEY `std_id` (`std_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `personnel_id` (`personnel_id`) USING BTREE;

--
-- Indexes for table `registration_status`
--
ALTER TABLE `registration_status`
  ADD PRIMARY KEY (`regisStatus_id`);

--
-- Indexes for table `registration_status_detail`
--
ALTER TABLE `registration_status_detail`
  ADD PRIMARY KEY (`regis_id`,`regisStatus_id`),
  ADD KEY `regis_id` (`regis_id`),
  ADD KEY `regisStatus_id` (`regisStatus_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`std_id`),
  ADD KEY `prefix_id` (`prefix_id`),
  ADD KEY `level_id` (`level_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `major_id` (`major_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `subdistrict_id` (`subdistrict_id`);

--
-- Indexes for table `subdistrict`
--
ALTER TABLE `subdistrict`
  ADD PRIMARY KEY (`subDistrict_id`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`system`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `vehiType_id` (`vehiType_id`),
  ADD KEY `vehiBrand_id` (`vehiBrand_id`);

--
-- Indexes for table `vehicle_brand`
--
ALTER TABLE `vehicle_brand`
  ADD PRIMARY KEY (`vehiBrand_id`);

--
-- Indexes for table `vehicle_type`
--
ALTER TABLE `vehicle_type`
  ADD PRIMARY KEY (`vehiType_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `an_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `noti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `parking_card`
--
ALTER TABLE `parking_card`
  MODIFY `parkingCard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `regis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`prefix_id`) REFERENCES `prefix` (`prefix_id`),
  ADD CONSTRAINT `admin_ibfk_2` FOREIGN KEY (`adminPos_id`) REFERENCES `admin_position` (`adminPos_id`);

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`);

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `major_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`std_id`) REFERENCES `student` (`std_id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`),
  ADD CONSTRAINT `notification_ibfk_3` FOREIGN KEY (`notiType_id`) REFERENCES `notification_type` (`notiType_id`),
  ADD CONSTRAINT `notification_ibfk_4` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`personnel_id`);

--
-- Constraints for table `parking_card`
--
ALTER TABLE `parking_card`
  ADD CONSTRAINT `fk_regis_id` FOREIGN KEY (`regis_id`) REFERENCES `registration` (`regis_id`);

--
-- Constraints for table `province`
--
ALTER TABLE `province`
  ADD CONSTRAINT `province_ibfk_1` FOREIGN KEY (`geograph_id`) REFERENCES `geographies` (`geograph_id`);

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`std_id`) REFERENCES `student` (`std_id`),
  ADD CONSTRAINT `registration_ibfk_2` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`personnel_id`),
  ADD CONSTRAINT `registration_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`);

--
-- Constraints for table `registration_status_detail`
--
ALTER TABLE `registration_status_detail`
  ADD CONSTRAINT `registration_status_detail_ibfk_1` FOREIGN KEY (`regis_id`) REFERENCES `registration` (`regis_id`),
  ADD CONSTRAINT `registration_status_detail_ibfk_2` FOREIGN KEY (`regisStatus_id`) REFERENCES `registration_status` (`regisStatus_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`prefix_id`) REFERENCES `prefix` (`prefix_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `class_level` (`level_id`),
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`),
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`major_id`) REFERENCES `major` (`major_id`);

--
-- Constraints for table `subdistrict`
--
ALTER TABLE `subdistrict`
  ADD CONSTRAINT `subdistrict_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`),
  ADD CONSTRAINT `vehicle_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `color` (`color_id`),
  ADD CONSTRAINT `vehicle_ibfk_3` FOREIGN KEY (`vehiType_id`) REFERENCES `vehicle_type` (`vehiType_id`),
  ADD CONSTRAINT `vehicle_ibfk_4` FOREIGN KEY (`vehiBrand_id`) REFERENCES `vehicle_brand` (`vehiBrand_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
