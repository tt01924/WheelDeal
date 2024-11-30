-- Insert dummy data for User table
INSERT INTO `User` (`username`, `password`, `email`, `phoneNumber`, `userType`) VALUES
('cyclelover', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'cyclelover@example.com', '1234567890', 'buyer'),
('bikeenthusiast', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'bikeenthusiast@example.com', '2345678901', 'seller'),
('urbanrider', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'urbanrider@example.com', '3456789012', 'buyer'),
('mountainmaverick', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'mountainmaverick@example.com', '4567890123', 'seller'),
('trailtamer', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'trailtamer@example.com', '5678901234', 'buyer'),
('tim', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'tim@ucl.uk', '5678901234', 'buyer'),
('todd', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'todd@ucl.uk', '5678901234', 'buyer'),
('peace', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'peace@ucl.uk', '5678901234', 'buyer'),
('james', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'james@ucl.uk', '5678901234', 'buyer'),
('freerider', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'freerider@example.com', '6789012345', 'seller');

-- Insert dummy data for Address table
INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`) VALUES
('123 Bike Lane', 'Cycleville', 'CountyPedal', '111111', (SELECT userId FROM `User` WHERE username = 'cyclelover')),
('456 Trail Road', 'BikeCity', 'CountyWheel', '222222', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast')),
('789 Gear Avenue', 'Velotown', 'CountyRide', '333333', (SELECT userId FROM `User` WHERE username = 'urbanrider')),
('1010 Spoke Street', 'Hilltop', 'CountyClimb', '444444', (SELECT userId FROM `User` WHERE username = 'mountainmaverick')),
('2020 Chain Court', 'Parkside', 'CountyTrail', '555555', (SELECT userId FROM `User` WHERE username = 'trailtamer'));

-- Insert dummy data for ItemCategory table
INSERT INTO `ItemCategory` (`categoryName`, `categoryDescription`) VALUES
('Bikes', 'Various types of bicycles for different terrains and purposes'),
('Accessories', 'Bike accessories like helmets, gloves, and lights'),
('Parts', 'Bike parts such as tires, pedals, and seats'),
('Apparel', 'Clothing for cyclists, from casual to performance wear');

-- Insert dummy data for Item table
INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `userId`, `categoryId`) VALUES
('Mountain Bike - Rockrider', 'Great for trail adventures, this Mountain Bike - Rockrider has a sturdy frame and reliable parts. It''s suitable for both beginners and those with some experience who enjoy off-road biking.', '2024-10-01 18:00:00', '2024-12-10 18:00:00', 180, 200, 'Used - Good Condition', 'image_uploads/btwin-rockrider-500-mountain-bike-rockrider-500-mountain-bike-2017_2-large.jpg', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Road Bike - Cannondale Synapse', 'This Road Bike - Cannondale Synapse is built for speed and comfort, making it great for long rides. It has a lightweight frame and smooth gear system, perfect for paved roads.', '2024-12-05 18:00:00', '2024-12-15 18:00:00', 350, 400, 'Used - Excellent Condition', 'image_uploads/cannondale-u-synapse-2_127678.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Road Bike (Ended)', 'This Road Bike - Cannondale Synapse is built for speed and comfort, making it great for long rides. It has a lightweight frame and smooth gear system, perfect for paved roads.', '2024-10-05 18:00:00', '2024-10-15 18:00:00', 350, 400, 'Used - Excellent Condition', 'image_uploads/cannondale-u-synapse-2_127678.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Cycling Helmet - Giro Air', 'Stay safe with the Cycling Helmet - Giro Air. It''s lightweight and has good ventilation to keep you cool. Suitable for both casual and serious cyclists.', '2024-12-10 18:00:00', '2024-12-20 18:00:00', 15, 20, 'Brand New', 'image_uploads/244324_7510_XL.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Clipless Pedals - Shimano SPD', 'These Clipless Pedals - Shimano SPD help you pedal more efficiently. They connect your shoes to the bike for better power and control, great for road and mountain biking.', '2024-12-12 18:00:00', '2024-12-22 18:00:00', 30, 45, 'Used - Excellent Condition', 'image_uploads/61zmTWjA0RL._AC_SL1200_.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Cycling Gloves - Full Finger', 'These full-finger cycling gloves are comfortable and protective. They have breathable fabric and padded palms to help reduce hand fatigue on long rides.', '2024-12-15 18:00:00', '2024-12-25 18:00:00', 10, 20, 'Brand New', 'image_uploads/6550b97c71887240a509b04fe02bb89333439556f161e4f0b6716.jpg', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Hybrid Bike - Trek FX', 'The Hybrid Bike - Trek FX is a mix of road and mountain bikes, ideal for commuting and leisure. It offers a comfortable ride on different terrains, perfect for city cyclists.', '2024-12-20 18:00:00', '2024-12-30 18:00:00', 450, 500, 'Used - Good Condition', 'image_uploads/trek-fx-2-women-s_10361909.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Mountain Bike Tire - Maxxis High Roller', 'This Mountain Bike Tire - Maxxis High Roller is great for tough trails. It has a strong tread for good grip on loose surfaces, perfect for those who want durability.', '2024-12-25 18:00:00', '2025-01-04 18:00:00', 20, 30, 'Brand New', 'image_uploads/716RhaTRWdL._AC_SL1500_.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Cycling Jersey - Reflective', 'Stay visible with this Cycling Jersey - Reflective. It''s made from breathable fabric and has reflective parts for safety in low light, ensuring a comfy fit for long rides.', '2024-12-30 18:00:00', '2025-01-09 18:00:00', 50, 60, 'Brand New', 'image_uploads/71pxvQeyNML._AC_SX679_.jpg', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Electric Bike - Specialized Turbo Vado', 'The Electric Bike - Specialized Turbo Vado is great for city commuting. It has a strong battery and motor for a smooth ride, perfect for long distances.', '2024-12-31 18:00:00', '2025-01-10 18:00:00', 1800, 2000, 'Used - Excellent Condition', 'image_uploads/specialized-turbo-vado-5-0-igh_15096144.jpg', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Bike Light - USB Rechargeable', 'This USB Rechargeable Bike Light is a must for night rides. It''s bright and has a long-lasting battery, making it a handy accessory for any cyclist.', '2025-01-05 18:00:00', '2025-01-15 18:00:00', 5, 7, 'Brand New', 'image_uploads/51fPcqtPr5L._AC_SL1000_.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories')),
('Kids Bike - Raleigh MXR', 'The Kids Bike - Raleigh MXR is great for young riders. It has a strong frame and easy brakes, making it safe and fun for beginners.', '2025-01-10 18:00:00', '2025-01-20 18:00:00', 80, 100, 'Used - Good Condition', 'image_uploads/raleighKidsBike.jpg', (SELECT userId FROM `User` WHERE username = 'freerider'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Cycling Shorts - Padded', 'These padded cycling shorts are made for comfort. They help reduce chafing and are made from breathable fabric for a snug fit.', '2025-01-12 18:00:00', '2025-01-22 18:00:00', 5, 7, 'Brand New', 'image_uploads/paddedShorts.jpg', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Mountain Bike Frame - Aluminum', 'This Aluminum Mountain Bike Frame is lightweight and strong, perfect for building your own bike. It''s great for those looking to upgrade.', '2025-01-14 18:00:00', '2025-01-24 18:00:00', 120, 150, 'Used - Excellent Condition', 'image_uploads/mtb-frame.jpg', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Bike Bell - Classic', 'The Classic Bike Bell is a nice touch for any bike. It''s loud and easy to install, adding a bit of nostalgia to your ride.', '2024-10-10 18:00:00', '2024-11-10 18:00:00', 8, 10, 'Brand New', 'image_uploads/bike-bell.jpg', (SELECT userId FROM `User` WHERE username = 'freerider'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories')),
('Water Bottle Holder - Adjustable', 'This Adjustable Water Bottle Holder fits different bottle sizes and is easy to install. It''s a useful accessory for staying hydrated on the go.', '2025-01-18 18:00:00', '2025-01-28 18:00:00', 10, 15, 'Brand New', 'image_uploads/water-bottle-holder.jpeg', (SELECT userId FROM `User` WHERE username = 'freerider'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories'));

-- Insert dummy data for Bid table
INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`) VALUES
(360, '2024-11-01 12:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
(380, '2024-11-02 13:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
(400, '2024-11-02 13:20:00', (SELECT userId FROM `User` WHERE username = 'todd'), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
(420, '2024-11-02 13:40:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
(500, '2024-11-02 14:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Cannondale%')),
(30, '2024-11-03 16:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Giro Air%')),
(40, '2024-11-04 16:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Giro Air%')),
(10, '2024-11-03 17:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Pedals%')),
(25, '2024-11-04 17:00:00', (SELECT userId FROM `User` WHERE username = 'todd'), (SELECT itemId FROM `Item` WHERE title LIKE '%Pedals%')),
(15, '2024-11-04 10:00:00', (SELECT userId FROM `User` WHERE username = 'peace'), (SELECT itemId FROM `Item` WHERE title LIKE '%Gloves%')),
(20, '2024-11-04 10:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Gloves%')),
(25, '2024-11-04 10:00:00', (SELECT userId FROM `User` WHERE username = 'james'), (SELECT itemId FROM `Item` WHERE title LIKE '%Gloves%')),
(520, '2024-11-05 11:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Trek%')),
(600, '2024-11-06 11:00:00', (SELECT userId FROM `User` WHERE username = 'tim'), (SELECT itemId FROM `Item` WHERE title LIKE '%Trek%')),
(60, '2024-11-05 12:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Tire%')),
(30, '2024-11-06 13:00:00', (SELECT userId FROM `User` WHERE username = 'james'), (SELECT itemId FROM `Item` WHERE title LIKE '%Jersey%')),
(40, '2024-11-06 13:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Jersey%')),
(7, '2024-11-07 15:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Light%')),
(100, '2024-11-06 16:00:00', (SELECT userId FROM `User` WHERE username = 'tim'), (SELECT itemId FROM `Item` WHERE title LIKE '%Kids Bike%')),
(20, '2024-11-06 16:00:00', (SELECT userId FROM `User` WHERE username = 'todd'), (SELECT itemId FROM `Item` WHERE title LIKE '%Bell%')),
(25, '2024-11-07 16:00:00', (SELECT userId FROM `User` WHERE username = 'peace'), (SELECT itemId FROM `Item` WHERE title LIKE '%Bell%')),
(160, '2024-11-08 16:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Kids Bike%'));

-- Insert dummy data for WatchList table
INSERT INTO `WatchList` (`userId`) VALUES
((SELECT userId FROM `User` WHERE username = 'cyclelover')),
((SELECT userId FROM `User` WHERE username = 'bikeenthusiast')),
((SELECT userId FROM `User` WHERE username = 'urbanrider')),
((SELECT userId FROM `User` WHERE username = 'mountainmaverick')),
((SELECT userId FROM `User` WHERE username = 'trailtamer'));

-- Insert dummy data for WatchListEntry table
INSERT INTO `WatchListEntry` (`watchListId`, `itemId`) VALUES
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'cyclelover')), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'cyclelover')), (SELECT itemId FROM `Item` WHERE title LIKE '%Cannondale%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'bikeenthusiast')), (SELECT itemId FROM `Item` WHERE title LIKE '%Helmet%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'bikeenthusiast')), (SELECT itemId FROM `Item` WHERE title LIKE '%Pedals%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'urbanrider')), (SELECT itemId FROM `Item` WHERE title LIKE '%Gloves%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'urbanrider')), (SELECT itemId FROM `Item` WHERE title LIKE '%Trek%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'mountainmaverick')), (SELECT itemId FROM `Item` WHERE title LIKE '%Tire%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'mountainmaverick')), (SELECT itemId FROM `Item` WHERE title LIKE '%Jersey%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'trailtamer')), (SELECT itemId FROM `Item` WHERE title LIKE '%Electric Bike%')),
((SELECT watchListId FROM `WatchList` WHERE userId = (SELECT userId FROM `User` WHERE username = 'trailtamer')), (SELECT itemId FROM `Item` WHERE title LIKE '%Light%'));

-- Insert dummy data for SellerRating table
INSERT INTO `SellerRating` (`rating`, `timeStamp`, `sellerId`, `buyerId`) VALUES
(5, '2024-11-01 14:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'cyclelover')),
(4, '2024-11-02 10:30:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(3, '2024-11-03 16:15:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'trailtamer')),
(5, '2024-11-04 11:45:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(2, '2024-11-05 12:20:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'cyclelover')),
(5, '2024-11-06 09:50:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(4, '2024-11-07 14:35:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'trailtamer')),
(5, '2024-11-08 10:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'trailtamer'));