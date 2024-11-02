SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


DROP DATABASE IF EXISTS WheelDeal;

CREATE DATABASE WheelDeal;

USE WheelDeal;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 29, 2024 at 04:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `WheelDeal`
--

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

CREATE TABLE `Address` (
  `addressId` int(11) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `county` varchar(50) NOT NULL,
  `postcode` varchar(8) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `bidId` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `timeStamp` datetime NOT NULL,
  `userId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE `Item` (
  `itemId` int(11) NOT NULL,
  `description` varchar(400) NOT NULL,
  `endTime` datetime NOT NULL,
  `reservePrice` decimal(10,0) NOT NULL,
  `itemCondition` enum('Brand New','Used - Excellent Condition','Used - Good Condition','Used - Worn Condition','Damaged/Broken') NOT NULL,
  `image` varchar(500) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `userId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ItemCategory`
--

CREATE TABLE `ItemCategory` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(20) NOT NULL,
  `categoryDescription` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phoneNumber` varchar(50) NOT NULL,
  `userType` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `WatchList`
--

CREATE TABLE `WatchList` (
  `watchListId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `WatchListEntry`
--

CREATE TABLE `WatchListEntry` (
  `watchListEntryId` int(11) NOT NULL,
  `watchListId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`addressId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `Bid`
--
ALTER TABLE `Bid`
  ADD PRIMARY KEY (`bidId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `Item`
--
ALTER TABLE `Item`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `ItemCategory`
--
ALTER TABLE `ItemCategory`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD PRIMARY KEY (`watchListId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `WatchListEntry`
--
ALTER TABLE `WatchListEntry`
  ADD PRIMARY KEY (`watchListEntryId`),
  ADD KEY `watchListId` (`watchListId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Address`
--
ALTER TABLE `Address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `Bid`
--
ALTER TABLE `Bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`itemId`) REFERENCES `Item` (`itemId`) ON DELETE CASCADE;

--
-- Constraints for table `Item`
--
ALTER TABLE `Item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `ItemCategory` (`categoryId`);

--
-- Constraints for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `WatchListEntry`
--
ALTER TABLE `WatchListEntry`
  ADD CONSTRAINT `watchlistentry_ibfk_1` FOREIGN KEY (`watchListId`) REFERENCES `WatchList` (`watchListId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;