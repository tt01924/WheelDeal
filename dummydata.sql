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
INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`) VALUES
('Mountain Bike - Rockrider', 'This Mountain Bike - Rockrider is perfect for trail adventures. It features a durable frame and reliable components, ensuring a smooth ride on rugged terrains. The bike is designed for both beginners and experienced riders who love exploring off-road paths.', '2024-10-01 18:00:00', '2024-12-10 18:00:00', 200, 350, 'Used - Good Condition', 'image_uploads/btwin-rockrider-500-mountain-bike-rockrider-500-mountain-bike-2017_2-large.jpg', 'mountain bike, Rockrider, trails', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Road Bike - Cannondale Synapse', 'The Road Bike - Cannondale Synapse is engineered for speed and comfort. It is ideal for long-distance rides and races, featuring a lightweight frame and advanced gear system. This bike offers excellent performance on paved roads.', '2024-12-05 18:00:00', '2024-12-15 18:00:00', 400, 800, 'Used - Excellent Condition', 'image_uploads/cannondale-u-synapse-2_127678.jpg', 'road bike, Cannondale, race', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Cycling Helmet - Giro Air', 'The Cycling Helmet - Giro Air provides top-notch safety with its aerodynamic design and lightweight construction. It features advanced ventilation to keep you cool and comfortable during intense rides. Perfect for both casual and competitive cyclists.', '2024-12-10 18:00:00', '2024-12-20 18:00:00', 20, 50, 'Brand New', 'image_uploads/244324_7510_XL.jpg', 'helmet, safety, accessories', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Clipless Pedals - Shimano SPD', 'These Clipless Pedals - Shimano SPD are designed for efficiency and control. They offer a secure connection between your shoes and the bike, enhancing power transfer and stability. Ideal for both road and mountain biking enthusiasts.', '2024-12-12 18:00:00', '2024-12-22 18:00:00', 45, 100, 'Used - Excellent Condition', 'image_uploads/61zmTWjA0RL._AC_SL1200_.jpg', 'pedals, Shimano, parts', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Cycling Gloves - Full Finger', 'These Cycling Gloves - Full Finger are crafted for comfort and protection. They feature a breathable fabric and padded palms to reduce hand fatigue during long rides. Suitable for all weather conditions, providing excellent grip and control.', '2024-12-15 18:00:00', '2024-12-25 18:00:00', 20, 40, 'Brand New', 'image_uploads/6550b97c71887240a509b04fe02bb89333439556f161e4f0b6716.jpg', 'gloves, apparel, full finger', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Hybrid Bike - Trek FX', 'The Hybrid Bike - Trek FX combines the best of road and mountain bikes. It is perfect for commuting and leisure rides, offering a comfortable upright position and versatile performance on various terrains. A great choice for urban cyclists.', '2024-12-20 18:00:00', '2024-12-30 18:00:00', 500, 700, 'Used - Good Condition', 'image_uploads/trek-fx-2-women-s_10361909.jpg', 'hybrid bike, Trek, commute', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Mountain Bike Tire - Maxxis High Roller', 'The Mountain Bike Tire - Maxxis High Roller is designed for aggressive trail riding. It features a robust tread pattern for superior grip and control on loose and rocky surfaces. Ideal for riders seeking performance and durability.', '2024-12-25 18:00:00', '2025-01-04 18:00:00', 30, 60, 'Brand New', 'image_uploads/716RhaTRWdL._AC_SL1500_.jpg', 'tire, Maxxis, parts', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Cycling Jersey - Reflective', 'The Cycling Jersey - Reflective is designed for visibility and comfort. It features reflective elements to enhance safety during low-light conditions. Made from breathable fabric, it ensures a comfortable fit for long rides.', '2024-12-30 18:00:00', '2025-01-09 18:00:00', 60, 120, 'Brand New', 'image_uploads/71pxvQeyNML._AC_SX679_.jpg', 'jersey, reflective, apparel', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Electric Bike - Specialized Turbo Vado', 'The Electric Bike - Specialized Turbo Vado offers a powerful and smooth ride. It features a high-capacity battery and efficient motor, making it perfect for urban commuting and long-distance travel. Experience the future of cycling with this advanced e-bike.', '2024-12-31 18:00:00', '2025-01-10 18:00:00', 2000, 2500, 'Used - Excellent Condition', 'image_uploads/specialized-turbo-vado-5-0-igh_15096144.jpg', 'electric bike, Specialized, urban', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Bike Light - USB Rechargeable', 'The Bike Light - USB Rechargeable is essential for night riding. It offers bright illumination and a long-lasting battery, ensuring visibility and safety. Easily rechargeable via USB, it is a convenient accessory for any cyclist.', '2025-01-05 18:00:00', '2025-01-15 18:00:00', 25, 50, 'Brand New', 'image_uploads/51fPcqtPr5L._AC_SL1000_.jpg', 'light, USB, accessories', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories')),
('Kids Bike - Raleigh MXR', 'The Kids Bike - Raleigh MXR is perfect for young riders. It features a sturdy frame and easy-to-use brakes, providing a safe and enjoyable riding experience. Ideal for beginners, it encourages outdoor fun and exercise.', '2025-01-10 18:00:00', '2025-01-20 18:00:00', 100, 150, 'Used - Good Condition', 'image_uploads/raleighKidsBike.jpg', 'kids bike, Raleigh, beginner', (SELECT userId FROM `User` WHERE username = 'freerider'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Bikes')),
('Cycling Shorts - Padded', 'These Cycling Shorts - Padded are designed for comfort and performance. They feature a padded insert to reduce chafing and enhance comfort during long rides. Made from breathable fabric, they ensure a snug fit and freedom of movement.', '2025-01-12 18:00:00', '2025-01-22 18:00:00', 30, 60, 'Brand New', 'image_uploads/paddedShorts.jpg', 'shorts, apparel, padded', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Apparel')),
('Mountain Bike Frame - Aluminum', 'The Mountain Bike Frame - Aluminum is lightweight yet strong, perfect for building a custom bike. It offers excellent durability and performance on challenging trails. Ideal for enthusiasts looking to upgrade or build their own mountain bike.', '2025-01-14 18:00:00', '2025-01-24 18:00:00', 150, 300, 'Used - Excellent Condition', 'image_uploads/mtb-frame.jpg', 'frame, mountain bike, parts', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Parts')),
('Bike Bell - Classic', 'The Bike Bell - Classic is a timeless accessory for any bicycle. It features a loud and clear ring, ensuring you are heard by pedestrians and other cyclists. Easy to install, it adds a touch of nostalgia to your ride.', '2025-01-16 18:00:00', '2025-01-26 18:00:00', 10, 20, 'Brand New', 'image_uploads/bike-bell.jpg', 'bell, accessories, classic', (SELECT userId FROM `User` WHERE username = 'freerider'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories')),
('Water Bottle Holder - Adjustable', 'The Water Bottle Holder - Adjustable is a versatile accessory for any bike. It securely holds bottles of various sizes, ensuring hydration on the go. Made from durable materials, it is easy to install and adjust.', '2025-01-18 18:00:00', '2025-01-28 18:00:00', 15, 30, 'Brand New', 'image_uploads/water-bottle-holder.jpeg', 'holder, accessories, adjustable', (SELECT userId FROM `User` WHERE username = 'freeride'), (SELECT categoryId FROM `ItemCategory` WHERE categoryName = 'Accessories'));

-- Insert dummy data for Bid table
INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`) VALUES
(360, '2024-11-01 12:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Mountain Bike - Rockrider%')),
(820, '2024-11-02 14:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Cannondale%')),
(55, '2024-11-03 16:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT itemId FROM `Item` WHERE title LIKE '%Helmet%')),
(100, '2024-11-03 17:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Pedals%')),
(65, '2024-11-04 10:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Gloves%')),
(520, '2024-11-05 11:00:00', (SELECT userId FROM `User` WHERE username = 'cyclelover'), (SELECT itemId FROM `Item` WHERE title LIKE '%Trek%')),
(60, '2024-11-05 12:00:00', (SELECT userId FROM `User` WHERE username = 'urbanrider'), (SELECT itemId FROM `Item` WHERE title LIKE '%Tire%')),
(120, '2024-11-06 13:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT itemId FROM `Item` WHERE title LIKE '%Jersey%')),
(50, '2024-11-07 15:00:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT itemId FROM `Item` WHERE title LIKE '%Light%')),
(160, '2024-11-08 16:00:00', (SELECT userId FROM `User` WHERE username = 'trailtamer'), (SELECT itemId FROM `Item` WHERE title LIKE '%Kids Bike%'));

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
INSERT INTO `SellerRating` (`rating`, `comment`, `timeStamp`, `sellerId`, `buyerId`) VALUES
(5, 'Excellent seller! Quick communication and smooth transaction.', '2024-11-01 14:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'cyclelover')),
(4, 'Bike was as described, but shipping took longer than expected.', '2024-11-02 10:30:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(3, 'Item condition was acceptable, but could have been better.', '2024-11-03 16:15:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'trailtamer')),
(5, 'Great seller! Highly recommend for premium bike parts.', '2024-11-04 11:45:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(2, 'Poor communication. Item arrived late and not as described.', '2024-11-05 12:20:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'cyclelover')),
(5, 'Fantastic transaction. Seller provided excellent service!', '2024-11-06 09:50:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'urbanrider')),
(4, 'Item was great, but the packaging could be improved.', '2024-11-07 14:35:00', (SELECT userId FROM `User` WHERE username = 'mountainmaverick'), (SELECT userId FROM `User` WHERE username = 'trailtamer')),
(5, 'Very satisfied with the purchase. Highly recommended!', '2024-11-08 10:00:00', (SELECT userId FROM `User` WHERE username = 'bikeenthusiast'), (SELECT userId FROM `User` WHERE username = 'trailtamer'));