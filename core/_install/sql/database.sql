-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2014 at 05:33 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nutmouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `bd_admin_user`
--

CREATE TABLE IF NOT EXISTS `bd_admin_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_active` tinyint(1) NOT NULL,
  `user_created` datetime NOT NULL,
  `user_lastlogin` datetime DEFAULT NULL,
  `user_language` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bd_frontend_content`
--

CREATE TABLE IF NOT EXISTS `bd_frontend_content` (
  `content_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_author` varchar(255) DEFAULT NULL,
  `content_created` datetime NOT NULL,
  `content_lastchanged` datetime NOT NULL,
  `content_published` datetime NOT NULL,
  `content_active` tinyint(1) NOT NULL,
  `content_template_inner` varchar(255) DEFAULT NULL,
  `content_template_outer` varchar(255) DEFAULT NULL,
  `content_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `content_url` (`content_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bd_frontend_content`
--

INSERT INTO `bd_frontend_content` (`content_id`, `content_author`, `content_created`, `content_lastchanged`, `content_published`, `content_active`, `content_template_inner`, `content_template_outer`, `content_url`) VALUES
(1, NULL, '2014-02-14 23:55:00', '2014-02-14 23:55:00', '2014-02-14 23:55:00', 1, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `bd_frontend_element`
--

CREATE TABLE IF NOT EXISTS `bd_frontend_element` (
  `element_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `element_content_id` bigint(20) unsigned NOT NULL,
  `element_type` varchar(255) NOT NULL,
  `element_language` bigint(20) unsigned NOT NULL,
  `element_title` varchar(255) DEFAULT NULL,
  `element_text` text NOT NULL,
  `element_created` datetime NOT NULL,
  `element_lastchanged` datetime NOT NULL,
  `element_sortkey` bigint(20) unsigned DEFAULT NULL,
  `element_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bd_frontend_element`
--

INSERT INTO `bd_frontend_element` (`element_id`, `element_content_id`, `element_type`, `element_language`, `element_title`, `element_text`, `element_created`, `element_lastchanged`, `element_sortkey`, `element_active`) VALUES
(1, 1, 'html', 1, NULL, '<h1>Dies ist ein DB Test</h1>\r\n<p>Dieser Text kommt aus der Datenbank...</p>', '2014-02-14 23:56:11', '2014-02-14 23:56:11', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bd_frontend_meta`
--

CREATE TABLE IF NOT EXISTS `bd_frontend_meta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `meta_content_id` bigint(20) unsigned NOT NULL,
  `meta_language` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bd_frontend_meta`
--

INSERT INTO `bd_frontend_meta` (`meta_id`, `meta_content_id`, `meta_language`, `meta_key`, `meta_value`) VALUES
(1, 1, 1, 'title', 'Testseite');

-- --------------------------------------------------------

--
-- Table structure for table `bd_sys_config`
--

CREATE TABLE IF NOT EXISTS `bd_sys_config` (
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  `config_input_type` varchar(255) NOT NULL,
  `confog_input_options` text NOT NULL,
  PRIMARY KEY (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bd_sys_language`
--

CREATE TABLE IF NOT EXISTS `bd_sys_language` (
  `language_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language_iso` char(2) NOT NULL,
  `language_label` varchar(255) NOT NULL,
  `language_frontend_active` tinyint(1) NOT NULL,
  `language_backend_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `language_label` (`language_label`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bd_sys_language`
--

INSERT INTO `bd_sys_language` (`language_id`, `language_iso`, `language_label`, `language_frontend_active`, `language_backend_active`) VALUES
(1, 'DE', 'Deutsch', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bd_sys_locale`
--

CREATE TABLE IF NOT EXISTS `bd_sys_locale` (
  `locale_key` varchar(255) NOT NULL,
  `locale_language` bigint(20) unsigned NOT NULL,
  `locale_text` int(11) NOT NULL,
  `locale_lastchanged` datetime NOT NULL,
  PRIMARY KEY (`locale_key`,`locale_language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
