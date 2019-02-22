-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2017 at 09:44 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slm`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `user` text NOT NULL,
  `time` text NOT NULL,
  `log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`user`, `time`, `log`) VALUES
('muhammad', '2017-11-22 15:23:29', 'logged in'),
('muhammad', '2017-11-22 15:25:31', 'logged in'),
('muhammad', '2017-11-23 14:03:08', 'logged in'),
('muhammad', '2017-11-30 10:58:11', 'logged in'),
('student', '2017-11-30 12:17:39', 'logged in'),
('student', '2017-11-30 12:50:43', 'logged in'),
('muhammad', '2017-11-30 12:56:27', 'logged in'),
('student', '2017-11-30 13:00:52', 'logged in'),
('muhammad', '2017-11-30 13:29:48', 'logged in'),
('student', '2017-11-30 13:55:38', 'logged in'),
('student', '2017-11-30 14:35:59', 'logged in'),
('student', '2017-11-30 14:38:32', 'logged in'),
('muhammad', '2017-11-30 14:54:21', 'logged in'),
('student', '2017-11-30 15:18:22', 'logged in'),
('muhammad', '2017-11-30 15:22:07', 'logged in'),
('student', '2017-11-30 15:36:55', 'logged in');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `module_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_name`) VALUES
(1, 'PTY1002');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` int(11) NOT NULL,
  `video_from` int(11) NOT NULL,
  `video_to` int(11) NOT NULL,
  `option_name` text NOT NULL,
  `frequency` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `video_from`, `video_to`, `option_name`, `frequency`, `question_id`) VALUES
(3, 4, 5, 'Do one more sensation test', 10, 3),
(4, 5, 6, 'Prepare the cold pack with wet towels', 7, 3),
(5, 6, 7, 'The patient feels cold initially but will get used to the temperature', 6, 3),
(6, 7, 8, 'Add one more layer of wet towel', 3, 3),
(7, 8, 9, 'Lighter Skin Colour', 2, 3),
(8, 8, 10, 'After the treatment the skin is in pink colour', 0, 3),
(9, 7, 11, 'Remove a layer of the wet towel', 3, 3),
(10, 4, 12, 'Proceed With Treatment', 13, 3),
(11, 5, 13, 'Prepare the cold pack with dry towels', 1, 3),
(16, 12, 14, 'Continue', 14, 3),
(17, 13, 14, 'Continue 2', 1, 3),
(20, 6, 15, 'The patient will not feel cold initially but will gradually feel it', 1, 3),
(21, 15, 14, 'Continue 3', 1, 3),
(22, 11, 14, 'Continue 4', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_name` text NOT NULL,
  `module_id` int(11) NOT NULL,
  `video_start` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_name`, `module_id`, `video_start`) VALUES
(3, 'Hot and Cold Therapy', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `questions_end`
--
-- NEW TABLE
CREATE TABLE `questions_end` (
  `questions_end_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions_end`
--

INSERT INTO `questions_end` (`questions_end_id`, `question_id`, `video_id`) VALUES
(1, 3, 9),
(2, 3, 10),
(3, 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `User_Username` text NOT NULL,
  `User_Password` text NOT NULL,
  `User_Role` text NOT NULL,
  `User_Status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `User_Username`, `User_Password`, `User_Role`, `User_Status`) VALUES
(1, 'muhammad', '$2a$04$L5sxKxNt4GwTWZOIIVW0s.Dd08SqtmB67uErInMwYhaNlzMYqEih.', 'admin', 'active'),
(2, 'student', '$2a$04$L5sxKxNt4GwTWZOIIVW0s.Dd08SqtmB67uErInMwYhaNlzMYqEih.', 'student', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `video_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `video_link` text NOT NULL,
  `video_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`video_id`, `question_id`, `video_link`, `video_text`) VALUES
(4, 3, '0eMbbyDLRB4', 'How Do You Proceed?'),
(5, 3, '5q3BOQRilh0', 'Sensation Testing'),
(6, 3, 'U7Cr1TMmdz0', 'Cold Pack w/ Towels'),
(7, 3, 'DGKwJ4wYgYY', 'Asses Patient\'s core temperature'),
(8, 3, 'eY9wK6n-KhQ', 'Addition of Layered towel'),
(9, 3, 'Re_S8M1C39g', 'Lighter Skin Color'),
(10, 3, 'w61Cls04Y7o', 'Best Assessment!'),
(11, 3, 'za18oJ3o3MM', 'Wrong Skin Color!'),
(12, 3, 'W-0rCfUs1m0', 'Wrong to Proceed with Treatment!'),
(13, 3, 'RoJZxvW_izU', 'Wrong to Prepare Cold Pack First!'),
(14, 3, 'ZU85e2QqIsA', 'You are so done'),
(15, 3, '9zbutfuQ7bo', 'Wrong Deduction!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `video_from` (`video_from`),
  ADD KEY `video_to` (`video_to`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `video_start` (`video_start`);

--
-- Indexes for table `questions_end`
--
ALTER TABLE `questions_end`
  ADD PRIMARY KEY (`questions_end_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `questions_end`
--
ALTER TABLE `questions_end`
  MODIFY `questions_end_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`video_from`) REFERENCES `videos` (`video_id`),
  ADD CONSTRAINT `options_ibfk_2` FOREIGN KEY (`video_to`) REFERENCES `videos` (`video_id`),
  ADD CONSTRAINT `options_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`video_start`) REFERENCES `videos` (`video_id`);

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
