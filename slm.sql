-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `user` text NOT NULL,
  `time` text NOT NULL,
  `log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_from` int(11) NOT NULL,
  `video_to` int(11) NOT NULL,
  `option_name` text NOT NULL,
  `frequency` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `video_from` (`video_from`),
  KEY `video_to` (`video_to`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `options_ibfk_1` FOREIGN KEY (`video_from`) REFERENCES `videos` (`video_id`),
  CONSTRAINT `options_ibfk_2` FOREIGN KEY (`video_to`) REFERENCES `videos` (`video_id`),
  CONSTRAINT `options_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_name` text NOT NULL,
  `module_id` int(11) NOT NULL,
  `video_start` int(11) DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `module_id` (`module_id`),
  KEY `video_start` (`video_start`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`),
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`video_start`) REFERENCES `videos` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_Username` text NOT NULL,
  `User_Password` text NOT NULL,
  `User_Role` text NOT NULL,
  `User_Status` text NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `video_link` text NOT NULL,
  `video_text` text NOT NULL,
  PRIMARY KEY (`video_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2017-11-18 06:07:48
