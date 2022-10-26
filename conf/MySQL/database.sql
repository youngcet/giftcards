-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2022 at 06:04 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `giftcards`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'admin',
  `profile_img` varchar(500) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `email`, `pwd`, `role`, `profile_img`, `created`) VALUES
(5, 'Admin', 'User', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'src/assets/images/users/5_admin_profile.jpg', '2022-10-23 13:18:56');

-- --------------------------------------------------------

--
-- Table structure for table `giftcards`
--

CREATE TABLE `giftcards` (
  `id` int(11) NOT NULL,
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
  `card_number` varchar(16) NOT NULL,
  `expiry_date` varchar(15) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `giftcards`
--

INSERT INTO `giftcards` (`id`, `seller_id`, `staff_id`, `admin_id`, `title`, `description`, `price`, `qty`, `qty_sold`, `status`, `color`, `card_number`, `expiry_date`, `created`) VALUES
(7, 6, 8, 0, 'Google', 'some description', '100', 500, 500, '', '#ec1818', '0699-1479-6364-0', '', '2022-10-23 16:50:08'),
(8, 6, 8, 0, 'Amazon', 'some description', '50', 200, 200, '', '#e4d20c', '8938-8397-2869-3', '2022-12-30', '2022-10-24 01:05:01'),
(9, 6, 8, 0, 'Netflix', 'some description', '50', 300, 0, '', '#f00505', '8205-9672-9319-8', '2023-01-01', '2022-10-24 14:06:08'),
(10, 9, 11, 0, 'Birthday', 'some description', '50', 400, 0, '', '#15f419', '2194-5372-4024-1', '2022-12-23', '2022-10-24 16:14:38');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` varchar(200) NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `profile_img` varchar(500) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `text`, `is_read`, `profile_img`, `created`) VALUES
(7, 0, 'Staff User', 'Assigned you to Netflix gift card', 0, '', '2022-10-24 14:06:08'),
(8, 6, 'Staff User', 'Assigned you to Netflix gift card', 0, 'src/assets/images/users/user.png', '2022-10-24 14:14:45'),
(9, 9, 'Cedric Maenetja', 'Assigned you to Birthday gift card', 0, 'src/assets/images/users/user.png', '2022-10-24 16:14:38');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `fname` varchar(70) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(150) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'seller',
  `profile_img` varchar(500) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `staff_id`, `fname`, `lname`, `email`, `pwd`, `role`, `profile_img`, `created`) VALUES
(6, 8, 'Seller', 'User', 'seller@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'seller', 'src/assets/images/users/6_seller_Peter_Griffin_29.jpg', '2022-10-23 15:54:45'),
(8, 8, 'Seller', 'User2', 'seller2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'seller', 'src/assets/images/users/8_seller_YVnW7ve.jpg', '2022-10-24 14:47:59'),
(9, 11, 'Peter', 'Griffin', 'pgriffin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'seller', '', '2022-10-24 16:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `seller_giftcards`
--

CREATE TABLE `seller_giftcards` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(70) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'staff',
  `profile_img` varchar(500) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `admin_id`, `fname`, `lname`, `email`, `pwd`, `role`, `profile_img`, `created`) VALUES
(8, 5, 'Staff', 'User', 'staff@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'staff', 'src/assets/images/users/8_staff_Bane_TDKR3.jpg', '0000-00-00 00:00:00'),
(10, 5, 'Yung', 'Cet', 'young.cet@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'staff', '', '2022-10-24 14:57:15'),
(11, 5, 'Cedric', 'Maenetja', 'cmaenetja@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'staff', 'src/assets/images/users/11_staff_Audi-RS3-Sportback-Header-1.jpg', '2022-10-24 15:56:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `id` (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `email_3` (`email`);

--
-- Indexes for table `giftcards`
--
ALTER TABLE `giftcards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`staff_id`),
  ADD KEY `id` (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `card_number` (`card_number`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `seller_giftcards`
--
ALTER TABLE `seller_giftcards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `giftcards`
--
ALTER TABLE `giftcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `seller_giftcards`
--
ALTER TABLE `seller_giftcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
