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

-- Dumping data for table giftcards.admin: ~1 rows (approximately)
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`id`, `fname`, `lname`, `email`, `pwd`, `role`, `created`) VALUES
	(3, 'Yung', 'Cet', 'young.cet@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '2022-09-25 18:34:22');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Dumping structure for table giftcards.giftcards
CREATE TABLE IF NOT EXISTS `giftcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  `qty_sold` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userid` (`staff_id`),
  KEY `id` (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table giftcards.giftcards: ~2 rows (approximately)
/*!40000 ALTER TABLE `giftcards` DISABLE KEYS */;
INSERT INTO `giftcards` (`id`, `seller_id`, `staff_id`, `admin_id`, `title`, `price`, `qty`, `qty_sold`, `status`, `created`) VALUES
	(4, 3, 3, 0, 0, 300, 1, 2, '', '2022-09-27 14:29:03'),
	(5, 4, 2, 0, 0, 120, 28, 1, '', '2022-09-27 15:27:54');
/*!40000 ALTER TABLE `giftcards` ENABLE KEYS */;

-- Dumping structure for table giftcards.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table giftcards.roles: ~0 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

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

-- Dumping data for table giftcards.seller: ~2 rows (approximately)
/*!40000 ALTER TABLE `seller` DISABLE KEYS */;
INSERT INTO `seller` (`id`, `staff_id`, `fname`, `lname`, `email`, `pwd`, `role`, `created`) VALUES
	(3, 3, 'Mo', 'Ray', 'test@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'seller', '2022-09-27 14:11:37'),
	(4, 2, 'Ray', 'Ray', 'ray@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'seller', '2022-09-27 15:27:19');
/*!40000 ALTER TABLE `seller` ENABLE KEYS */;

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

-- Dumping data for table giftcards.seller_giftcards: ~5 rows (approximately)
/*!40000 ALTER TABLE `seller_giftcards` DISABLE KEYS */;
INSERT INTO `seller_giftcards` (`id`, `seller_id`, `title`, `description`, `price`, `status`, `created`) VALUES
	(7, 3, 'netflix', 'my description', 100, 'sold', '2022-09-27 14:31:06'),
	(8, 3, 'google', 'my description', 200, 'sold', '2022-09-27 14:31:38'),
	(9, 3, 'amazon', 'my description', 300, 'active', '2022-09-27 14:33:21'),
	(10, 4, 'google', 'google gift card', 120, 'sold', '2022-09-27 23:34:30'),
	(11, 4, 'netflix', 'my description', 300, 'active', '2022-09-27 23:45:16');
/*!40000 ALTER TABLE `seller_giftcards` ENABLE KEYS */;

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

-- Dumping data for table giftcards.staff: ~2 rows (approximately)
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` (`id`, `admin_id`, `fname`, `lname`, `email`, `pwd`, `role`, `created`) VALUES
	(2, 3, 'Cedric', 'Maenetja', 'cmaenetja@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'staff', '0000-00-00 00:00:00'),
	(3, 3, 'Mo', 'Ray', 'youngkce@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'staff', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
