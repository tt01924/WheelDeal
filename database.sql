SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

DROP DATABASE IF EXISTS WheelDeal;

CREATE DATABASE WheelDeal;

USE WheelDeal;


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 07, 2024 at 12:35 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20


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
  `addressId` int NOT NULL,
  `street` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `county` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `postcode` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `bidId` int NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `timeStamp` datetime NOT NULL,
  `userId` int NOT NULL,
  `itemId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Bid`
--

INSERT INTO `Bid` (`bidId`, `amount`, `timeStamp`, `userId`, `itemId`) VALUES
(1, 150, '2024-11-06 08:00:00', 1, 1),
(2, 100, '2024-11-06 09:00:00', 2, 2),
(3, 950, '2024-11-06 10:00:00', 3, 3),
(4, 150, '2024-11-06 08:00:00', 1, 1),
(5, 100, '2024-11-06 09:00:00', 2, 2),
(6, 950, '2024-11-06 10:00:00', 3, 3),
(7, 150, '2024-11-06 08:00:00', 1, 1),
(8, 100, '2024-11-06 09:00:00', 2, 2),
(9, 950, '2024-11-06 10:00:00', 3, 3),
(10, 150, '2024-11-06 08:00:00', 1, 1),
(11, 100, '2024-11-06 09:00:00', 2, 2),
(12, 950, '2024-11-06 10:00:00', 3, 3),
(13, 150, '2024-11-06 08:00:00', 1, 1),
(14, 100, '2024-11-06 09:00:00', 2, 2),
(15, 950, '2024-11-06 10:00:00', 3, 3),
(16, 150, '2024-11-06 08:00:00', 1, 1),
(17, 100, '2024-11-06 09:00:00', 2, 2),
(18, 950, '2024-11-06 10:00:00', 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Item`
--

CREATE TABLE `Item` (
  `itemId` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` varchar(400) NOT NULL,
  `itemCondition` varchar(50) NOT NULL,
  `tags` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `startPrice` decimal(10,0) NOT NULL,
  `reservePrice` decimal(10,0) DEFAULT NULL,
  `itemImage` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Item`
--

INSERT INTO `Item` (`itemId`, `title`, `details`, `itemCondition`, `tags`, `startPrice`, `reservePrice`, `itemImage`) VALUES
(1, 'Title123', 'Details123', '', '', 0, 0, ''),
(2, 'Title123', 'Details123', 'Used', '', 0, 0, ''),
(3, 'Title123', 'Details123', 'Used', 'bike, trail', 0, 0, ''),
(4, 'Title123', 'Details123', 'Used', NULL, 0, 0, ''),
(5, 'Title123', 'Details123', 'Used', NULL, 6, 0, ''),
(6, 'Title123', 'Details123', 'Used', NULL, 6, 3, ''),
(7, 'Title123', 'Details123', 'Used', NULL, 6, 3, ''),
(8, 'Title123', 'Details123', 'Used', NULL, 6, 3, ''),
(9, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(10, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(11, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(12, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(13, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(14, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(15, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(16, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(17, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(18, 'Title123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(19, 'Skiing123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(20, 'Running123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png'),
(21, 'Swimming123', 'Details123', 'Used', NULL, 6, 3, '/Pictures123/bike.png');

-- --------------------------------------------------------

--
-- Table structure for table `ItemCategory`
--

CREATE TABLE `ItemCategory` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `categoryDescription` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ItemCategory`
--

INSERT INTO `ItemCategory` (`categoryId`, `categoryName`, `categoryDescription`) VALUES
(1, 'Electronics', 'Gadgets, devices, and electronic equipment'),
(2, 'Furniture', 'Home and office furniture'),
(3, 'Books', 'Various genres of books for all ages');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `phoneNumber` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `userType` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userId`, `username`, `password`, `email`, `phoneNumber`, `userType`) VALUES
(1, 'john_doe', 'password123', 'john@example.com', '555-1234', 'buyer'),
(2, 'jane_smith', 'password456', 'jane@example.com', '555-5678', 'seller'),
(3, 'alice_johnson', 'password789', 'alice@example.com', '555-9876', 'buyer');

-- --------------------------------------------------------

--
-- Table structure for table `WatchList`
--

CREATE TABLE `WatchList` (
  `watchListId` int NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `WatchListEntry`
--

CREATE TABLE `WatchListEntry` (
  `watchListEntryId` int NOT NULL,
  `watchListId` int NOT NULL
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
  ADD PRIMARY KEY (`itemId`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `addressId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Bid`
--
ALTER TABLE `Bid`
  MODIFY `bidId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Item`
--
ALTER TABLE `Item`
  MODIFY `itemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ItemCategory`
--
ALTER TABLE `ItemCategory`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `WatchList`
--
ALTER TABLE `WatchList`
  MODIFY `watchListId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WatchListEntry`
--
ALTER TABLE `WatchListEntry`
  MODIFY `watchListEntryId` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Address`
--
ALTER TABLE `Address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `Bid`
--
ALTER TABLE `Bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `WatchList`
--
ALTER TABLE `WatchList`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `WatchListEntry`
--
ALTER TABLE `WatchListEntry`
  ADD CONSTRAINT `watchlistentry_ibfk_1` FOREIGN KEY (`watchListId`) REFERENCES `WatchList` (`watchListId`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
