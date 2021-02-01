-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2021 at 10:00 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fcp_babcock`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulletin`
--

CREATE TABLE `bulletin` (
  `bulletinID` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `startingYear` int(11) NOT NULL,
  `endingYear` int(11) NOT NULL,
  `gradRequirements` longtext NOT NULL,
  `status` int(2) NOT NULL,
  `addedBY` int(11) NOT NULL,
  `date_added` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bulletin`
--

INSERT INTO `bulletin` (`bulletinID`, `department_id`, `startingYear`, `endingYear`, `gradRequirements`, `status`, `addedBY`, `date_added`) VALUES
(1, 1, 2015, 2019, 'Minimum requirement of 169 credits for Computer Science is                  needed for the award of the B.Sc. degree. Direct entry candidates                  may earn less than the stipulated credits.                  The distribution of the credit requirement by level is as follows:', 1, 1, '2021-01-22 16:05:08'),
(2, 1, 2019, 2022, '', 0, 1, '2021-01-22 07:37:08'),
(10, 1, 2001, 2021, 'Jswjusjuswjsj', 0, 1, '2021-01-22 07:30:09'),
(11, 1, 2001, 2021, 'Jswjusjuswjsj', 0, 1, '2021-01-22 07:30:12'),
(12, 1, 2001, 2021, 'Jswjusjuswjsj', 0, 1, '2021-01-22 07:30:13'),
(14, 1, 2001, 2021, 'Jdsjdjdjd', 0, 1, '2021-01-22 07:32:54');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseID` int(11) NOT NULL,
  `bulletin` int(11) NOT NULL,
  `title` text NOT NULL,
  `code` varchar(15) NOT NULL,
  `description` longtext NOT NULL,
  `department` int(11) NOT NULL DEFAULT 1,
  `courseType` int(11) NOT NULL,
  `unit` int(3) NOT NULL,
  `level` int(3) NOT NULL,
  `semester` int(2) NOT NULL,
  `assignedLecturer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `bulletin`, `title`, `code`, `description`, `department`, `courseType`, `unit`, `level`, `semester`, `assignedLecturer`) VALUES
(1, 1, 'Citizenship Orientation', 'GEDS 001', '', 1, 1, 0, 100, 1, 'Someone'),
(2, 1, 'Intro. To Philosophy of Christian Education', 'GEDS 101', '', 1, 1, 2, 100, 1, ''),
(3, 1, 'Use of Library and study skills', 'GEDS 105', '', 1, 1, 2, 100, 1, ''),
(4, 1, 'Health Principles', 'GEDS 112', '', 1, 1, 2, 100, 2, ''),
(6, 1, 'Introduction to General Psychology', 'GEDS 107', '', 1, 1, 2, 100, 1, ''),
(7, 1, 'Life and Teachings of Christ', 'GEDS 122', '', 1, 1, 2, 100, 2, ''),
(8, 1, 'Communication in English I', 'GEDS 131', '', 1, 1, 2, 100, 1, ''),
(9, 1, 'Communication in English II', 'GEDS 132', '', 1, 1, 2, 100, 2, ''),
(10, 1, 'Nigeria People in a Global Culture', 'GEDS 134', '', 1, 1, 2, 100, 2, ''),
(11, 1, 'Introduction to Computer Science( Programming  in C', 'COSC 101', '', 1, 2, 3, 100, 1, ''),
(12, 1, 'Introduction to Programming  in C++', 'COSC 102', '', 1, 2, 3, 100, 2, ''),
(14, 1, 'General Mathematics I', 'MATH 101', '', 1, 3, 3, 100, 1, ''),
(15, 1, 'General Mathematics II', 'MATH 102', '', 1, 3, 3, 100, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `coursetypes`
--

CREATE TABLE `coursetypes` (
  `ctypeID` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coursetypes`
--

INSERT INTO `coursetypes` (`ctypeID`, `name`) VALUES
(1, 'GENERAL EDUCATION COURSES'),
(2, '                      CORE COURSES'),
(3, 'ELECTIVE COURSES'),
(4, '                 INTERNATIONAL CERTIFICATION SUPPORT');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `duration` int(1) NOT NULL,
  `bulletin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `name`, `duration`, `bulletin`) VALUES
(1, 'BSc Computer Science', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `message` text NOT NULL,
  `link` text NOT NULL,
  `fromID` varchar(50) NOT NULL,
  `toID` varchar(50) NOT NULL,
  `status` int(2) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationID`, `message`, `link`, `fromID`, `toID`, `status`, `date_added`) VALUES
(1, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 08:53:16'),
(3, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 13:58:07'),
(4, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 14:00:39'),
(5, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 14:04:00'),
(6, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 14:06:31'),
(7, 'Oghenekaro Israel Brume just sent His Scores', 'transcript.php?id=19/0341', '19/0341', '1', 1, '2021-01-27 15:15:47'),
(9, 'Your Result Was Accepted', 'transcript.php', '1', '19/0341', 1, '2021-01-27 15:34:30');

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `remarkID` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `matNO` text NOT NULL,
  `remark` longtext NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `remarks`
--

INSERT INTO `remarks` (`remarkID`, `type`, `matNO`, `remark`, `userID`) VALUES
(1, 1, '19/0341', 'Test', 1),
(3, 1, '19/0341', 'Okay', 1),
(4, 1, '19/0341', 'Test 1', 1),
(5, 1, '19/0341', 'Something sha', 1),
(6, 2, '19/0341', 'Testing 1 2', 1),
(7, 2, '19/0341', 'Testing 1 2                        3', 1),
(8, 1, '19/0341', 'Check your failed courses and resend', 1),
(9, 1, '19/0341', 'Noting', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `matNo` varchar(50) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `middleName` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `level` int(4) NOT NULL,
  `bulletin` int(11) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`matNo`, `lastName`, `firstName`, `middleName`, `department_id`, `level`, `bulletin`, `status`) VALUES
('19/0341', 'Oghenekaro', 'Israel', 'Brume', 1, 300, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transcript`
--

CREATE TABLE `transcript` (
  `TransID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `score` int(3) NOT NULL,
  `matNO` text NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transcript`
--

INSERT INTO `transcript` (`TransID`, `level`, `semester`, `courseID`, `score`, `matNO`, `status`) VALUES
(1, 100, 1, 1, 100, '19/0341', 1),
(2, 100, 1, 2, 100, '19/0341', 1),
(3, 100, 1, 3, 100, '19/0341', 1),
(4, 100, 1, 6, 100, '19/0341', 1),
(5, 100, 1, 8, 100, '19/0341', 1),
(6, 100, 1, 11, 100, '19/0341', 1),
(7, 100, 1, 14, 100, '19/0341', 1),
(8, 100, 3, 7, 100, '19/0341', 1),
(9, 100, 3, 12, 0, '19/0341', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transcripttemp`
--

CREATE TABLE `transcripttemp` (
  `TransID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `score` int(3) NOT NULL,
  `matNO` text NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transcripttemp`
--

INSERT INTO `transcripttemp` (`TransID`, `level`, `semester`, `courseID`, `score`, `matNO`, `status`) VALUES
(1, 100, 1, 1, 100, '19/0341', 1),
(2, 100, 1, 2, 100, '19/0341', 1),
(3, 100, 1, 3, 100, '19/0341', 1),
(4, 100, 1, 6, 100, '19/0341', 1),
(5, 100, 1, 8, 100, '19/0341', 1),
(6, 100, 1, 11, 100, '19/0341', 1),
(7, 100, 1, 14, 100, '19/0341', 1),
(8, 100, 2, 7, 8, '19/0341', 0),
(9, 100, 2, 12, 8, '19/0341', 0),
(10, 100, 2, 10, 8, '19/0341', 0),
(11, 100, 2, 4, 8, '19/0341', 0),
(12, 100, 2, 9, 8, '19/0341', 0),
(13, 100, 2, 15, 8, '19/0341', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `fullname` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `usr` varchar(255) NOT NULL,
  `pwd` text NOT NULL,
  `log_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`fullname`, `department_id`, `uid`, `usr`, `pwd`, `log_status`) VALUES
('Nzewata Jerry', 1, 1, 'mrjerry', '$2y$10$uz650wMdh1CftgmqGpTOcu8dQPpsAtXDxkpc2UFn8UXsvibbWSF12', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bulletin`
--
ALTER TABLE `bulletin`
  ADD PRIMARY KEY (`bulletinID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `coursetypes`
--
ALTER TABLE `coursetypes`
  ADD PRIMARY KEY (`ctypeID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`);

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
  ADD PRIMARY KEY (`remarkID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`matNo`);

--
-- Indexes for table `transcript`
--
ALTER TABLE `transcript`
  ADD PRIMARY KEY (`TransID`);

--
-- Indexes for table `transcripttemp`
--
ALTER TABLE `transcripttemp`
  ADD PRIMARY KEY (`TransID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bulletin`
--
ALTER TABLE `bulletin`
  MODIFY `bulletinID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `coursetypes`
--
ALTER TABLE `coursetypes`
  MODIFY `ctypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `remarkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transcript`
--
ALTER TABLE `transcript`
  MODIFY `TransID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transcripttemp`
--
ALTER TABLE `transcripttemp`
  MODIFY `TransID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
