-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2016 at 11:35 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tcdcsforum`
--

-- --------------------------------------------------------

--
-- Table structure for table `courseyear`
--

CREATE TABLE IF NOT EXISTS `courseyear` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `numericalYear` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numericalYear` (`numericalYear`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courseyear`
--

INSERT INTO `courseyear` (`id`, `name`, `subtitle`, `numericalYear`) VALUES
(1, '1st Year', '1st Year', 1),
(2, '2nd Year', '2nd Year', 2),
(3, '3rd Year', '3rd year', 3),
(4, '4th Year', '4th Year', 4),
(5, '5th year', '5th year', 5);

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE IF NOT EXISTS `moderators` (
  `userID` varchar(32) NOT NULL,
  `yearModuleName` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`,`yearModuleName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateOfCreation` datetime NOT NULL,
  `lastEdited` datetime DEFAULT NULL,
  `creatorID` varchar(32) NOT NULL,
  `threadParentID` int(11) NOT NULL,
  `content` varchar(64000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creatorID` (`creatorID`),
  KEY `threadParentID` (`threadParentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateOfCreation` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `parentModuleID` varchar(255) NOT NULL,
  `creatorID` varchar(32) NOT NULL,
  `threadText` varchar(64000) NOT NULL,
  `lastEdited` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parentModuleID` (`parentModuleID`),
  KEY `creatorID` (`creatorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `usertitle` varchar(64) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yearmodule`
--

CREATE TABLE IF NOT EXISTS `yearmodule` (
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `courseYearID` int(11) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `courseYearID` (`courseYearID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `yearmodule`
--

INSERT INTO `yearmodule` (`name`, `subtitle`, `courseYearID`) VALUES
('BC', 'Broad Curriculum Modules ', 2),
('CS1003', 'Mathematics', 1),
('CS1010', 'Introduction to Programming', 1),
('CS1013', 'Programming Project I', 1),
('CS1021', 'Introduction to Computing I', 1),
('CS1022', 'Introduction to Computing II', 1),
('CS1025', 'Electrotechnology', 1),
('CS1026', 'Digital Logic Design', 1),
('CS1031', 'Telecommunications I ', 1),
('CS1081', 'Computers and Society ', 1),
('CS2010', 'Algorithms and Data Structures ', 2),
('CS2013', 'Programming Project II', 2),
('CS2014', 'Systems Programming ', 2),
('CS2016', 'Concurrent Systems & Operating Systems', 2),
('CS2021', 'Microprocessor Systems', 2),
('CS2022', 'Computer Architecture I', 2),
('CS2031', 'Telecommunications II', 2),
('CS3011', 'Symbolic Programming', 3),
('CS3012', 'Software Engineering ', 3),
('CS3013', 'Software Engineering Group Project ', 3),
('CS3016', 'Introduction to Functional Programming', 3),
('CS3021', 'Computer Architecture II', 3),
('CS3031', 'Advanced Telecommunications', 3),
('CS3041', 'Information Management II', 3),
('CS3061', 'Artificial Intelligence ', 3),
('CS3071', 'Compiler Design I', 3),
('CS3081', 'Computational Mathematics', 3),
('CS4051', 'Human Factors', 4),
('CS4081', 'Technology Entrepreneurship ', 4),
('CS4098', 'Group Computer Science Project', 4),
('CS4099', 'Final Year Project', 4),
('CS7091', 'Industrial/Research Lab Internship', 4),
('CS7091 - MCS', 'Industrial/Research Lab Internship', 5),
('CS7092', 'MCS Dissertation', 5),
('CS7094', 'Research Methods', 5),
('MA2C03', 'Discrete Mathematics ', 2),
('ST30009', 'Statistical Methods for Computer Science', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`creatorID`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`threadParentID`) REFERENCES `threads` (`id`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`parentModuleID`) REFERENCES `yearmodule` (`name`),
  ADD CONSTRAINT `threads_ibfk_2` FOREIGN KEY (`creatorID`) REFERENCES `user` (`username`);

--
-- Constraints for table `yearmodule`
--
ALTER TABLE `yearmodule`
  ADD CONSTRAINT `yearmodule_ibfk_1` FOREIGN KEY (`courseYearID`) REFERENCES `courseyear` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
