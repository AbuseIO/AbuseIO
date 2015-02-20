SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `Customers` (
  `Code` varchar(80) NOT NULL DEFAULT '',
  `Name` varchar(255) NOT NULL,
  `Contact` varchar(255) NOT NULL,
  `AutoNotify` int(1) NOT NULL,
  `LastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Code`),
  KEY `Contact` (`Contact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Netblocks` (
  `begin_in` int(10) unsigned NOT NULL DEFAULT '0',
  `end_in` int(10) unsigned NOT NULL DEFAULT '0',
  `CustomerCode` varchar(80) NOT NULL DEFAULT '',
  `LastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`begin_in`,`end_in`),
  KEY `begin_in` (`begin_in`),
  KEY `end_in` (`end_in`),
  KEY `Code` (`CustomerCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Notes` (
  `ID` int(12) NOT NULL AUTO_INCREMENT,
  `ReportID` int(12) NOT NULL,
  `Timestamp` int(12) NOT NULL,
  `Submittor` varchar(255) NOT NULL,
  `Text` longtext NOT NULL,
  `LastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `ReportID` (`ReportID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Reports` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Source` varchar(50) NOT NULL,
  `IP` varchar(39) NOT NULL,
  `Domain` varchar(100) NOT NULL,
  `URI` varchar(100) NOT NULL,
  `FirstSeen` int(12) NOT NULL,
  `LastSeen` int(12) NOT NULL,
  `Information` longtext NOT NULL,
  `Class` varchar(50) NOT NULL,
  `CustomerCode` varchar(50) NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `CustomerContact` varchar(255) NOT NULL,
  `CustomerResolved` int(1) NOT NULL,
  `CustomerIgnored` int(1) NOT NULL,
  `AutoNotify` int(1) NOT NULL,
  `NotifiedCount` int(10) NOT NULL,
  `ReportCount` int(10) NOT NULL,
  `LastNotifyReportCount` int(10) NOT NULL,
  `LastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `IP` (`IP`),
  KEY `Source` (`Source`),
  KEY `Domain` (`Domain`),
  KEY `Class` (`Class`),
  KEY `CustomerCode` (`CustomerCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

