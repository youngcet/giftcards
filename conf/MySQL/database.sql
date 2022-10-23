-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for giftcards
CREATE DATABASE IF NOT EXISTS `giftcards` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `giftcards`;

-- Dumping structure for table giftcards.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'admin',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_2` (`email`),
  KEY `id` (`id`),
  KEY `email` (`email`),
  KEY `email_3` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;


-- Dumping structure for table giftcards.giftcards
CREATE TABLE IF NOT EXISTS `giftcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  `qty_sold` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `color` varchar(7) NOT NULL,
  `expiry_date` varchar(15) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userid` (`staff_id`),
  KEY `id` (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;


-- Dumping structure for table giftcards.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Dumping structure for table giftcards.seller
CREATE TABLE IF NOT EXISTS `seller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `fname` varchar(70) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(150) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'seller',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_2` (`email`),
  KEY `staff_id` (`staff_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;



-- Dumping structure for table giftcards.seller_giftcards
CREATE TABLE IF NOT EXISTS `seller_giftcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;


-- Dumping structure for table giftcards.staff
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'staff',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `email_2` (`email`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
