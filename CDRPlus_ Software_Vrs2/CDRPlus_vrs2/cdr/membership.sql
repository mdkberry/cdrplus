-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2013 at 08:08 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `membership`
--

-- --------------------------------------------------------

--
-- Table structure for table `rotation_config`
--

CREATE TABLE IF NOT EXISTS `rotation_config` (
  `call_today` varchar(255) DEFAULT NULL,
  `call_this_week` varchar(255) DEFAULT NULL,
  `call_this_month` varchar(255) DEFAULT NULL,
  `team_today` varchar(255) DEFAULT NULL,
  `team_this_week` varchar(255) DEFAULT NULL,
  `team_this_month` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rotation_config`
--

INSERT INTO `rotation_config` (`call_today`, `call_this_week`, `call_this_month`, `team_today`, `team_this_week`, `team_this_month`) VALUES
('1', '1', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `extlist` varchar(255) DEFAULT NULL,
  `fromdate` date DEFAULT NULL,
  `todate` date DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `fordisplay` varchar(10) NOT NULL DEFAULT 'no',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report_run` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `report_run_exec` varchar(10) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `mail`, `extlist`, `fromdate`, `todate`, `period`, `type`, `fordisplay`, `created`, `report_run`, `report_run_exec`) VALUES
(80, 'mdkberry@outlook.com', NULL, NULL, NULL, 'daily', 'detailed', 'no', '2013-07-02 07:22:50', '2013-07-02 13:59:59', 'no'),
(81, 'neil.lewis@gdsinternational.com', NULL, NULL, NULL, 'daily', 'callcount', 'no', '2013-07-08 06:24:37', '2013-07-08 13:59:59', 'no'),
(82, 'tony.spencer@gdsinternational.com', '623,613,618,604,607', NULL, NULL, 'daily', 'callcount', 'no', '2013-07-08 06:25:22', '2013-07-08 13:59:59', 'no'),
(83, 'oliver.smart@gdsinternational.com', NULL, NULL, NULL, 'daily', 'callcount', 'no', '2013-07-08 06:26:12', '2013-07-08 13:59:59', 'no'),
(84, 'nick.york@gdsinternational.com', '602,617,616', NULL, NULL, 'daily', 'callcount', 'no', '2013-07-08 06:30:03', '2013-07-08 13:59:59', 'no'),
(85, 'neil.lewis@gdsinternational.com', NULL, NULL, NULL, 'weekly', 'callcount', 'no', '2013-07-08 06:30:21', '2013-07-14 13:59:59', 'no'),
(86, 'neil.lewis@gdsinternational.com', NULL, NULL, NULL, 'monthly', 'callcount', 'no', '2013-07-08 06:30:42', '2013-07-31 13:59:59', 'no'),
(87, 'oliver.smart@gdsinternational.com', NULL, NULL, NULL, 'weekly', 'callcount', 'no', '2013-07-08 06:30:58', '2013-07-14 13:59:59', 'no'),
(88, 'oliver.smart@gdsinternational.com', NULL, NULL, NULL, 'monthly', 'callcount', 'no', '2013-07-08 06:31:15', '2013-07-31 13:59:59', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `extension` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user`, `group`, `extension`) VALUES
(3, 'Reception', 'Reception', '600'),
(4, 'User', 'Dept', '601'),
(5, 'Nick Y', 'IBL', '602'),
(6, 'Ollie', 'IBL', '603'),
(7, 'Jerome', 'Delegates', '604'),
(8, 'Boardroom', 'Boardroom', '605'),
(9, 'Mark J', 'BA', '606'),
(10, 'Joel A', 'Delegates', '607'),
(11, 'User', 'Dept', '608'),
(12, 'User', 'Dept', '609'),
(13, 'Nick R', 'Accounts', '610'),
(14, 'Daniel D', 'BA', '611'),
(15, 'Markus', 'IBL', '612'),
(16, 'Liz C', 'Delegates', '613'),
(17, 'Rob S', 'BA', '614'),
(18, 'Jane H', 'BA', '615'),
(19, 'Nick H', 'BA', '616'),
(20, 'Lachlan', 'BA', '617'),
(21, 'Shaun B', 'Delegates', '618'),
(22, 'Anita S', 'IBL', '619'),
(23, 'Kelly T', 'BA', '620'),
(24, 'User', 'Dept', '621'),
(25, 'User', 'Dept', '622'),
(26, 'Kristy M', 'Delegates', '623'),
(27, 'Amelia G', 'Events', '624'),
(28, 'Henry B', 'IBL', '625'),
(29, 'Lar', 'BA', '626'),
(30, 'User', 'Dept', '627'),
(31, 'User', 'Dept', '628'),
(32, 'User', 'Dept', '629');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
