-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2013 at 01:06 AM
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
-- Table structure for table `acl_group`
--

CREATE TABLE IF NOT EXISTS `acl_group` (
  `description` varchar(255) NOT NULL DEFAULT '',
  `id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  KEY `IDX_UNIQUEID` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acl_group`
--

INSERT INTO `acl_group` (`description`, `id`, `name`) VALUES
('total access', 1, 'administrator'),
('Operator', 2, 'Operator'),
('EXTENSION USER', 3, 'EXTENSION'),
('RECEPTION', 6, 'RECEPTION'),
('SALES', 8, 'SALES'),
('OPS', 9, 'OPS'),
('MANAGER', 10, 'MANAGER');

-- --------------------------------------------------------

--
-- Table structure for table `acl_membership`
--

CREATE TABLE IF NOT EXISTS `acl_membership` (
  `id` int(10) NOT NULL DEFAULT '0',
  `id_user` int(10) NOT NULL DEFAULT '0',
  `id_group` int(10) NOT NULL DEFAULT '0',
  KEY `IDX_UNIQUEID` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acl_membership`
--

INSERT INTO `acl_membership` (`id`, `id_user`, `id_group`) VALUES
(1, 1, 1),
(2, 2, 4),
(4, 4, 9),
(5, 3, 6),
(6, 5, 9),
(7, 6, 9),
(8, 7, 9),
(10, 9, 8),
(11, 8, 8),
(12, 10, 10),
(13, 11, 10),
(14, 12, 8),
(15, 13, 8),
(16, 14, 8),
(17, 15, 8),
(18, 16, 8),
(19, 17, 8),
(20, 18, 8),
(21, 19, 8),
(22, 20, 8),
(23, 21, 8),
(24, 22, 8);

-- --------------------------------------------------------

--
-- Table structure for table `acl_user`
--

CREATE TABLE IF NOT EXISTS `acl_user` (
  `id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `md5_password` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(255) NOT NULL DEFAULT '',
  KEY `IDX_UNIQUEID` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acl_user`
--

INSERT INTO `acl_user` (`id`, `name`, `description`, `md5_password`, `extension`) VALUES
(1, 'admin', 'admin', '59d963ed8a22f3a022972343cc260129', ''),
(2, 'reports', 'reports', '0d107d09f5bbe40cade3de5c71e9e9b7', ''),
(3, 'Reception', 'Reception', '81dc9bdb52d04dc20036dbd8313ed055', '500'),
(4, 'Frances', 'Frances', '81dc9bdb52d04dc20036dbd8313ed055', '501'),
(5, 'Sarah', 'Sarah', '81dc9bdb52d04dc20036dbd8313ed055', '502'),
(6, 'Orla', 'Orla', '81dc9bdb52d04dc20036dbd8313ed055', '503'),
(7, 'Preyaz', 'Preyaz', '81dc9bdb52d04dc20036dbd8313ed055', '505'),
(8, 'User', 'User', '81dc9bdb52d04dc20036dbd8313ed055', '506'),
(9, 'Dale', 'Dale', '81dc9bdb52d04dc20036dbd8313ed055', '507'),
(10, 'Tyron', 'Tyron', '81dc9bdb52d04dc20036dbd8313ed055', '508'),
(11, 'Larry', 'Larry', '81dc9bdb52d04dc20036dbd8313ed055', '509'),
(12, 'Dave', 'Dave', '81dc9bdb52d04dc20036dbd8313ed055', '510'),
(13, 'Matilda', 'Matilda', '81dc9bdb52d04dc20036dbd8313ed055', '512'),
(14, 'User', 'User', '81dc9bdb52d04dc20036dbd8313ed055', '513'),
(15, 'Samantha', 'Samantha', '81dc9bdb52d04dc20036dbd8313ed055', '514'),
(16, 'Renee', 'Renee', '81dc9bdb52d04dc20036dbd8313ed055', '515'),
(17, 'Tim', 'Tim', '81dc9bdb52d04dc20036dbd8313ed055', '518'),
(18, 'Kourtney', 'Kourtney', '81dc9bdb52d04dc20036dbd8313ed055', '519'),
(19, 'Rhiannon', 'Rhiannon', '81dc9bdb52d04dc20036dbd8313ed055', '504'),
(21, '516', '516', '81dc9bdb52d04dc20036dbd8313ed055', '516'),
(20, 'Blake', 'Blake', '81dc9bdb52d04dc20036dbd8313ed055', '511'),
(22, 'Damien', 'Damien', '81dc9bdb52d04dc20036dbd8313ed055', '517');

-- --------------------------------------------------------

--
-- Table structure for table `rotation_config`
--

CREATE TABLE IF NOT EXISTS `rotation_config` (
  `call_today` int(10) NOT NULL,
  `call_this_week` int(10) NOT NULL,
  `call_this_month` int(10) NOT NULL,
  `team_today` int(10) NOT NULL,
  `team_this_week` int(10) NOT NULL,
  `team_this_month` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rotation_config`
--

INSERT INTO `rotation_config` (`call_today`, `call_this_week`, `call_this_month`, `team_today`, `team_this_week`, `team_this_month`) VALUES
(1, 0, 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
