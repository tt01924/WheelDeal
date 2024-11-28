SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

DROP DATABASE IF EXISTS WheelDeal;

CREATE DATABASE WheelDeal;

USE WheelDeal;

SET time_zone = "+00:00";

-- Database: `WheelDeal`

-- --------------------------------------------------------

-- Table structure for table `Address`
CREATE TABLE `Address` (
  `addressId` int NOT NULL,
  `street` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `county` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `postcode` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `Bid`
CREATE TABLE `Bid` (
  `bidId` int NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `timeStamp` datetime NOT NULL,
  `userId` int NOT NULL,
  `itemId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `Item`
CREATE TABLE `Item` (
  `itemId` int NOT NULL,
  `userId` int NOT NULL,
  `categoryId` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(400) NOT NULL,
  `itemCondition` varchar(50) NOT NULL,
  `tags` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `reservePrice` decimal(10,0) DEFAULT NULL,
  `timeCreated` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `notificationSent` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table structure for table `ItemCategory`
CREATE TABLE `ItemCategory` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `categoryDescription` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `SellerRating`
CREATE TABLE `SellerRating` (
  `ratingId` int NOT NULL,
  `rating` int NOT NULL,
  `timeStamp` datetime NOT NULL,
  `buyerId` int NOT NULL,
  `sellerId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table structure for table `User`
CREATE TABLE `User` (
  `userId` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phoneNumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userType` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `WatchList`
CREATE TABLE `WatchList` (
  `watchListId` int NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `WatchListEntry`
CREATE TABLE `WatchListEntry` (
  `watchListEntryId` int NOT NULL,
  `watchListId` int NOT NULL,
  `itemId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Indexes for table `Address`
ALTER TABLE `Address`
  ADD PRIMARY KEY (`addressId`),
  ADD KEY `userId` (`userId`);


-- Indexes for table `Bid`
ALTER TABLE `Bid`
  ADD PRIMARY KEY (`bidId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `itemId` (`itemId`);


-- Indexes for table `Item`
ALTER TABLE `Item`
  ADD PRIMARY KEY (`itemId`);


-- Indexes for table `ItemCategory`
ALTER TABLE `ItemCategory`
  ADD PRIMARY KEY (`categoryId`);


-- Indexes for table `SellerRating`
ALTER TABLE `SellerRating`
  ADD PRIMARY KEY (`ratingId`),
  ADD KEY `buyerId` (`buyerId`),
  ADD KEY `sellerId` (`sellerId`);


-- Indexes for table `User`
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);


-- Indexes for table `WatchList`
ALTER TABLE `WatchList`
  ADD PRIMARY KEY (`watchListId`),
  ADD KEY `userId` (`userId`);


-- Indexes for table `WatchListEntry`
ALTER TABLE `WatchListEntry`
  ADD PRIMARY KEY (`watchListEntryId`),
  ADD KEY `watchListId` (`watchListId`);


-- AUTO_INCREMENT for table `Address`
ALTER TABLE `Address`
  MODIFY `addressId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `Bid`
ALTER TABLE `Bid`
  MODIFY `bidId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `Item`
ALTER TABLE `Item`
  MODIFY `itemId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `ItemCategory`
ALTER TABLE `ItemCategory`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `SellerRating`
ALTER TABLE `SellerRating`
  MODIFY `ratingId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `User`
ALTER TABLE `User`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `WatchList`
ALTER TABLE `WatchList`
  MODIFY `watchListId` int NOT NULL AUTO_INCREMENT;


-- AUTO_INCREMENT for table `WatchListEntry`
ALTER TABLE `WatchListEntry`
  MODIFY `watchListEntryId` int NOT NULL AUTO_INCREMENT;


-- Constraints for table `Address`
ALTER TABLE `Address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;


-- Constraints for table `Bid`
ALTER TABLE `Bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;


-- Constraints for table `SellerRating`
ALTER TABLE `SellerRating`
  ADD CONSTRAINT `sellerrating_ibfk_1` FOREIGN KEY (`buyerId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sellerrating_ibfk_2` FOREIGN KEY (`sellerId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;


-- Constraints for table `WatchList`
ALTER TABLE `WatchList`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;


-- Constraints for table `WatchListEntry`
ALTER TABLE `WatchListEntry`
  ADD CONSTRAINT `watchlistentry_ibfk_1` FOREIGN KEY (`watchListId`) REFERENCES `WatchList` (`watchListId`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;