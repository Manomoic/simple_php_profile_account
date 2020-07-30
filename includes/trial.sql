-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2019 at 11:10 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trial`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_add_users`
--

CREATE TABLE `tbl_add_users` (
  `id` int(255) NOT NULL,
  `firstname` varchar(255) COLLATE ascii_bin NOT NULL,
  `lastname` varchar(255) COLLATE ascii_bin NOT NULL,
  `email` varchar(255) COLLATE ascii_bin NOT NULL,
  `address` text COLLATE ascii_bin NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contacts`
--

CREATE TABLE `tbl_contacts` (
  `contact_id` int(255) NOT NULL,
  `id` int(255) NOT NULL,
  `email` varchar(255) COLLATE ascii_bin NOT NULL,
  `phone` varchar(255) COLLATE ascii_bin NOT NULL,
  `address` text COLLATE ascii_bin NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(255) NOT NULL,
  `name` varchar(100) COLLATE ascii_bin NOT NULL,
  `surname` varchar(100) COLLATE ascii_bin NOT NULL,
  `gender` varchar(10) COLLATE ascii_bin DEFAULT NULL,
  `address` text COLLATE ascii_bin,
  `email` varchar(255) COLLATE ascii_bin NOT NULL,
  `password` varchar(100) COLLATE ascii_bin NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `loggedin` datetime NOT NULL,
  `description` text COLLATE ascii_bin NOT NULL,
  `photo` varchar(255) COLLATE ascii_bin DEFAULT NULL,
  `status` enum('New','Updated','Logged In','Logged Out') COLLATE ascii_bin NOT NULL,
  `session` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_add_users`
--
ALTER TABLE `tbl_add_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contacts`
--
ALTER TABLE `tbl_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `user_id` (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_add_users`
--
ALTER TABLE `tbl_add_users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_contacts`
--
ALTER TABLE `tbl_contacts`
  MODIFY `contact_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `tbl_users` 
  ADD `phone` VARCHAR(20) NOT NULL AFTER `address`;
