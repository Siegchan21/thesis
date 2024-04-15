-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2024 at 07:11 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
  `courseName` varchar(100) CHARACTER SET utf32 NOT NULL,
  `subjectID` int(11) NOT NULL,
  `instructorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblinstructors`
--

CREATE TABLE `tblinstructors` (
  `instructorID` int(11) NOT NULL,
  `instructorName` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblrooms`
--

CREATE TABLE `tblrooms` (
  `roomID` int(11) NOT NULL,
  `roomName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblsections`
--

CREATE TABLE `tblsections` (
  `sectionID` int(11) NOT NULL,
  `sectionName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `studentID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `gradeLevel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `subjectID` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL,
  `subjectType` enum('LEC','LAB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `role` enum('Admin','Student','Instructor') NOT NULL,
  `admissionNum` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcourses`
--
ALTER TABLE `tblcourses`
  ADD PRIMARY KEY (`courseID`),
  ADD KEY `subject` (`subjectID`),
  ADD KEY `instructor` (`instructorID`);

--
-- Indexes for table `tblinstructors`
--
ALTER TABLE `tblinstructors`
  ADD PRIMARY KEY (`instructorID`);

--
-- Indexes for table `tblrooms`
--
ALTER TABLE `tblrooms`
  ADD PRIMARY KEY (`roomID`);

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
  ADD PRIMARY KEY (`sectionID`);

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
  ADD PRIMARY KEY (`subjectID`);

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
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblinstructors`
--
ALTER TABLE `tblinstructors`
  MODIFY `instructorID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrooms`
--
ALTER TABLE `tblrooms`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsections`
--
ALTER TABLE `tblsections`
  MODIFY `sectionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcourses`
--
ALTER TABLE `tblcourses`
  ADD CONSTRAINT `instructor` FOREIGN KEY (`instructorID`) REFERENCES `tblinstructors` (`instructorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subject` FOREIGN KEY (`subjectID`) REFERENCES `tblsubjects` (`subjectID`) ON UPDATE CASCADE;

--
-- Constraints for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD CONSTRAINT `course` FOREIGN KEY (`courseID`) REFERENCES `tblcourses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room` FOREIGN KEY (`roomID`) REFERENCES `tblrooms` (`roomID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub` FOREIGN KEY (`subjectID`) REFERENCES `tblsubjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD CONSTRAINT `section` FOREIGN KEY (`sectionID`) REFERENCES `tblsections` (`sectionID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
