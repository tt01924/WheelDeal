-- Insert dummy data for User table
INSERT INTO `User` (`userId`, `username`, `password`, `email`, `phoneNumber`, `userType`) VALUES
(1, 'cyclelover', 'password1', 'cyclelover@example.com', '1234567890', 'buyer'),
(2, 'bikeenthusiast', 'password2', 'bikeenthusiast@example.com', '2345678901', 'seller'),
(3, 'urbanrider', 'password3', 'urbanrider@example.com', '3456789012', 'buyer'),
(4, 'mountainmaverick', 'password4', 'mountainmaverick@example.com', '4567890123', 'seller'),
(5, 'trailtamer', 'password5', 'trailtamer@example.com', '5678901234', 'buyer');

-- Insert dummy data for Address table
INSERT INTO `Address` (`addressId`, `street`, `city`, `county`, `postcode`, `userId`) VALUES
(1, '123 Bike Lane', 'Cycleville', 'CountyPedal', '111111', 1),
(2, '456 Trail Road', 'BikeCity', 'CountyWheel', '222222', 2),
(3, '789 Gear Avenue', 'Velotown', 'CountyRide', '333333', 3),
(4, '1010 Spoke Street', 'Hilltop', 'CountyClimb', '444444', 4),
(5, '2020 Chain Court', 'Parkside', 'CountyTrail', '555555', 5);

-- Insert dummy data for ItemCategory table
INSERT INTO `ItemCategory` (`categoryId`, `categoryName`, `categoryDescription`) VALUES
(1, 'Bikes', 'Various types of bicycles for different terrains and purposes'),
(2, 'Accessories', 'Bike accessories like helmets, gloves, and lights'),
(3, 'Parts', 'Bike parts such as tires, pedals, and seats'),
(4, 'Apparel', 'Clothing for cyclists, from casual to performance wear');

-- Insert dummy data for Item table
INSERT INTO `Item` (`itemId`, `description`, `endTime`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`) VALUES
(1, 'Mountain Bike - Rockrider', '2024-12-01 18:00:00', 350, 'Used - Good Condition', 'mtb1.jpg', 'mountain bike, Rockrider, trails', 4, 1),
(2, 'Road Bike - Cannondale Synapse', '2024-12-05 18:00:00', 800, 'Used - Excellent Condition', 'roadbike1.jpg', 'road bike, Cannondale, race', 2, 1),
(3, 'Cycling Helmet - Giro Air', '2024-12-10 18:00:00', 50, 'Brand New', 'helmet1.jpg', 'helmet, safety, accessories', 3, 2),
(4, 'Clipless Pedals - Shimano SPD', '2024-12-12 18:00:00', 45, 'Used - Excellent Condition', 'pedals1.jpg', 'pedals, Shimano, parts', 2, 3),
(5, 'Cycling Gloves - Full Finger', '2024-12-15 18:00:00', 20, 'Brand New', 'gloves1.jpg', 'gloves, apparel, full finger', 5, 4),
(6, 'Hybrid Bike - Trek FX', '2024-12-20 18:00:00', 500, 'Used - Good Condition', 'hybridbike1.jpg', 'hybrid bike, Trek, commute', 2, 1),
(7, 'Mountain Bike Tire - Maxxis High Roller', '2024-12-25 18:00:00', 30, 'Brand New', 'tire1.jpg', 'tire, Maxxis, parts', 1, 3),
(8, 'Cycling Jersey - Reflective', '2024-12-30 18:00:00', 60, 'Brand New', 'jersey1.jpg', 'jersey, reflective, apparel', 5, 4),
(9, 'Electric Bike - Specialized Turbo Vado', '2024-12-31 18:00:00', 2000, 'Used - Excellent Condition', 'ebike1.jpg', 'electric bike, Specialized, urban', 4, 1),
(10, 'Bike Light - USB Rechargeable', '2025-01-05 18:00:00', 25, 'Brand New', 'light1.jpg', 'light, USB, accessories', 3, 2);

-- Insert dummy data for Bid table
INSERT INTO `Bid` (`bidId`, `amount`, `timeStamp`, `userId`, `itemId`) VALUES
(1, 360, '2024-11-01 12:00:00', 1, 1),
(2, 820, '2024-11-02 14:00:00', 3, 2),
(3, 55, '2024-11-03 16:00:00', 2, 3),
(4, 50, '2024-11-03 17:00:00', 1, 4),
(5, 65, '2024-11-04 10:00:00', 5, 5),
(6, 520, '2024-11-05 11:00:00', 1, 6),
(7, 35, '2024-11-05 12:00:00', 3, 7),
(8, 70, '2024-11-06 13:00:00', 2, 8),
(9, 2100, '2024-11-06 14:00:00', 5, 9),
(10, 30, '2024-11-07 15:00:00', 4, 10);

-- Insert dummy data for WatchList table
INSERT INTO `WatchList` (`watchListId`, `userId`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Insert dummy data for WatchListEntry table
INSERT INTO `WatchListEntry` (`watchListEntryId`, `watchListId`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 1),
(7, 2),
(8, 3),
(9, 4),
(10, 5);