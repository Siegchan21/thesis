-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2024 at 11:59 AM
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
-- Database: `databasesched`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcourses`
--

CREATE TABLE `tblcourses` (
  `courseID` int(11) NOT NULL,
  `courseName` varchar(100) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcourses`
--

INSERT INTO `tblcourses` (`courseID`, `courseName`) VALUES
(3, 'CAFA'),
(4, 'Automotive'),
(5, 'CCS');

-- --------------------------------------------------------

--
-- Table structure for table `tblgen`
--

CREATE TABLE `tblgen` (
  `genID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `instructorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblinstructors`
--

CREATE TABLE `tblinstructors` (
  `instructorID` int(11) NOT NULL,
  `instructorName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `instructorRole` varchar(50) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblinstructors`
--

INSERT INTO `tblinstructors` (`instructorID`, `instructorName`, `instructorRole`, `courseID`) VALUES
(91, 'SIR BAGS', 'Instructor', 5),
(92, 'Sir Archi', 'Bumbero', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblload`
--

CREATE TABLE `tblload` (
  `subTypeID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `instructorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblrooms`
--

CREATE TABLE `tblrooms` (
  `roomID` int(11) NOT NULL,
  `roomName` varchar(50) NOT NULL,
  `roomType` enum('LEC','LAB') NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrooms`
--

INSERT INTO `tblrooms` (`roomID`, `roomName`, `roomType`, `courseID`) VALUES
(2, 'try', 'LEC', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblschedule`
--

CREATE TABLE `tblschedule` (
  `scheduleID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsections`
--

CREATE TABLE `tblsections` (
  `sectionID` int(11) NOT NULL,
  `sectionName` varchar(50) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsections`
--

INSERT INTO `tblsections` (`sectionID`, `sectionName`, `courseID`) VALUES
(1, 'try', 3),
(2, '4A', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `studentID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `gradeLevel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `subjectID` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL,
  `subjectType` enum('LEC','LAB') NOT NULL,
  `instructorID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`subjectID`, `subjectName`, `subjectType`, `instructorID`, `courseID`) VALUES
(26, '1', 'LEC', 91, 3),
(27, '2', 'LEC', 91, 3),
(28, '3', 'LAB', 91, 3),
(29, '4', 'LEC', 91, 3),
(30, '5', 'LAB', 91, 3),
(31, '6', 'LEC', 91, 3),
(32, '7', 'LEC', 91, 3),
(33, '8', 'LAB', 91, 3),
(34, 'optimus prime', 'LAB', 92, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `position` enum('Admin','Student','Instructor') NOT NULL,
  `admissionNum` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userID`, `firstName`, `middleName`, `lastName`, `birthday`, `position`, `admissionNum`, `username`, `password`) VALUES
(0, 'Admin', 'Admin', 'Admin', '2001-01-01', 'Admin', '00001', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcourses`
--
ALTER TABLE `tblcourses`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `tblgen`
--
ALTER TABLE `tblgen`
  ADD PRIMARY KEY (`genID`),
  ADD KEY `showcors` (`courseID`),
  ADD KEY `showinstr` (`instructorID`),
  ADD KEY `showroom` (`roomID`),
  ADD KEY `showsec` (`sectionID`),
  ADD KEY `showsu` (`subjectID`);

--
-- Indexes for table `tblinstructors`
--
ALTER TABLE `tblinstructors`
  ADD PRIMARY KEY (`instructorID`),
  ADD KEY `nanututuru` (`courseID`);

--
-- Indexes for table `tblload`
--
ALTER TABLE `tblload`
  ADD PRIMARY KEY (`subTypeID`),
  ADD KEY `sabjek` (`subjectID`),
  ADD KEY `tuturu` (`instructorID`);

--
-- Indexes for table `tblrooms`
--
ALTER TABLE `tblrooms`
  ADD PRIMARY KEY (`roomID`),
  ADD KEY `anongroom` (`courseID`);

--
-- Indexes for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `course` (`courseID`),
  ADD KEY `room` (`roomID`),
  ADD KEY `sub` (`subjectID`);

--
-- Indexes for table `tblsections`
--
ALTER TABLE `tblsections`
  ADD PRIMARY KEY (`sectionID`),
  ADD KEY `kursmudi` (`courseID`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `section` (`sectionID`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`subjectID`),
  ADD KEY `manuru` (`instructorID`),
  ADD KEY `kursu` (`courseID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcourses`
--
ALTER TABLE `tblcourses`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblgen`
--
ALTER TABLE `tblgen`
  MODIFY `genID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblinstructors`
--
ALTER TABLE `tblinstructors`
  MODIFY `instructorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tblload`
--
ALTER TABLE `tblload`
  MODIFY `subTypeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrooms`
--
ALTER TABLE `tblrooms`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsections`
--
ALTER TABLE `tblsections`
  MODIFY `sectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblgen`
--
ALTER TABLE `tblgen`
  ADD CONSTRAINT `showcors` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `showinstr` FOREIGN KEY (`instructorID`) REFERENCES `tblinstructors` (`instructorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `showroom` FOREIGN KEY (`roomID`) REFERENCES `tblrooms` (`roomID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `showsec` FOREIGN KEY (`sectionID`) REFERENCES `tblsections` (`sectionID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `showsu` FOREIGN KEY (`subjectID`) REFERENCES `tblsubjects` (`subjectID`) ON UPDATE CASCADE;

--
-- Constraints for table `tblinstructors`
--
ALTER TABLE `tblinstructors`
  ADD CONSTRAINT `nanututuru` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON UPDATE CASCADE;

--
-- Constraints for table `tblload`
--
ALTER TABLE `tblload`
  ADD CONSTRAINT `sabjek` FOREIGN KEY (`subjectID`) REFERENCES `tblsubjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tuturu` FOREIGN KEY (`instructorID`) REFERENCES `tblinstructors` (`instructorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblrooms`
--
ALTER TABLE `tblrooms`
  ADD CONSTRAINT `anongroom` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD CONSTRAINT `course` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room` FOREIGN KEY (`roomID`) REFERENCES `tblrooms` (`roomID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub` FOREIGN KEY (`subjectID`) REFERENCES `tblsubjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblsections`
--
ALTER TABLE `tblsections`
  ADD CONSTRAINT `kursmudi` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD CONSTRAINT `section` FOREIGN KEY (`sectionID`) REFERENCES `tblsections` (`sectionID`) ON UPDATE CASCADE;

--
-- Constraints for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD CONSTRAINT `kursu` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manuru` FOREIGN KEY (`instructorID`) REFERENCES `tblinstructors` (`instructorID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
