-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `sih`;
CREATE DATABASE `sih` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sih`;

DROP TABLE IF EXISTS `action`;
CREATE TABLE `action` (
  `actionid` int(10) NOT NULL AUTO_INCREMENT,
  `taskid` int(10) NOT NULL,
  `imgid1` int(10) NOT NULL,
  `imgid2` int(10) NOT NULL,
  `h` int(3) NOT NULL,
  `v` int(3) NOT NULL,
  `status` int(3) NOT NULL,
  `result` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`actionid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `chash` varchar(40) NOT NULL,
  `last_active` varchar(15) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `client_action`;
CREATE TABLE `client_action` (
  `caid` bigint(30) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) NOT NULL,
  `aid` bigint(20) NOT NULL,
  `started` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `stopped` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`caid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `outputs`;
CREATE TABLE `outputs` (
  `did` bigint(20) NOT NULL AUTO_INCREMENT,
  `taskid` bigint(20) NOT NULL,
  `imgid1` bigint(20) NOT NULL,
  `imgid2` bigint(20) NOT NULL,
  `actid` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isfinal` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pending`;
CREATE TABLE `pending` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) NOT NULL,
  `oname` varchar(100) NOT NULL,
  `name_hash` varchar(33) NOT NULL,
  `time` int(10) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `staged` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `staged`;
CREATE TABLE `staged` (
  `stage_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) NOT NULL,
  `img_id` bigint(20) NOT NULL,
  `h_splits` bigint(20) NOT NULL DEFAULT '9',
  `v_splits` bigint(20) NOT NULL DEFAULT '9',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`stage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `taskid` int(10) NOT NULL AUTO_INCREMENT,
  `oname` varchar(500) NOT NULL,
  `hashname` varchar(32) NOT NULL,
  `created` int(10) NOT NULL,
  `completed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2018-03-31 02:17:25