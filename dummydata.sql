-- Insert dummy data for User table
INSERT INTO `User` (`username`, `password`, `email`, `phoneNumber`, `userType`) VALUES
('cyclelover', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'cyclelover@example.com', '1234567890', 'buyer'),
('bikeenthusiast', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'bikeenthusiast@example.com', '2345678901', 'seller'),
('urbanrider', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'urbanrider@example.com', '3456789012', 'buyer'),
('mountainmaverick', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'mountainmaverick@example.com', '4567890123', 'seller'),
('trailtamer', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'trailtamer@example.com', '5678901234', 'buyer');

-- Insert dummy data for Address table
INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`)
SELECT '123 Bike Lane', 'Cycleville', 'CountyPedal', '111111', userId FROM User WHERE username = 'cyclelover';

INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`)
SELECT '456 Trail Road', 'BikeCity', 'CountyWheel', '222222', userId FROM User WHERE username = 'bikeenthusiast';

INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`)
SELECT '789 Gear Avenue', 'Velotown', 'CountyRide', '333333', userId FROM User WHERE username = 'urbanrider';

INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`)
SELECT '1010 Spoke Street', 'Hilltop', 'CountyClimb', '444444', userId FROM User WHERE username = 'mountainmaverick';

INSERT INTO `Address` (`street`, `city`, `county`, `postcode`, `userId`)
SELECT '2020 Chain Court', 'Parkside', 'CountyTrail', '555555', userId FROM User WHERE username = 'trailtamer';

-- Insert dummy data for ItemCategory table
INSERT INTO `ItemCategory` (`categoryName`, `categoryDescription`) VALUES
('Bikes', 'Various types of bicycles for different terrains and purposes'),
('Accessories', 'Bike accessories like helmets, gloves, and lights'),
('Parts', 'Bike parts such as tires, pedals, and seats'),
('Apparel', 'Clothing for cyclists, from casual to performance wear');

-- Insert dummy data for Item table
INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Mountain Bike - Rockrider',
    'Perfect for trail adventures with durable frame and reliable components Mountain Bike - Rockrider',
    '2024-10-01 18:00:00',
    '2024-12-10 18:00:00',
    200,
    350,
    'Used - Good Condition',
    'image_uploads/btwin-rockrider-500-mountain-bike-rockrider-500-mountain-bike-2017_2-large.jpg',
    'mountain bike, Rockrider, trails',
    (SELECT userId FROM User WHERE username = 'mountainmaverick'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Bikes');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Road Bike - Cannondale Synapse',
    'Road Bike - Cannondale Synapse',
    '2024-12-05 18:00:00',
    '2024-12-15 18:00:00',
    400,
    800,
    'Used - Excellent Condition',
    'image_uploads/cannondale-u-synapse-2_127678.jpg',
    'road bike, Cannondale, race',
    (SELECT userId FROM User WHERE username = 'bikeenthusiast'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Bikes');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Cycling Helmet - Giro Air',
    'Cycling Helmet - Giro Air',
    '2024-12-10 18:00:00',
    '2024-12-20 18:00:00',
    20,
    50,
    'Brand New',
    'image_uploads/244324_7510_XL.jpg',
    'helmet, safety, accessories',
    (SELECT userId FROM User WHERE username = 'urbanrider'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Accessories');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Clipless Pedals - Shimano SPD',
    'Clipless Pedals - Shimano SPD',
    '2024-12-12 18:00:00',
    '2024-12-22 18:00:00',
    45,
    100,
    'Used - Excellent Condition',
    'image_uploads/61zmTWjA0RL._AC_SL1200_.jpg',
    'pedals, Shimano, parts',
    (SELECT userId FROM User WHERE username = 'bikeenthusiast'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Parts');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Cycling Gloves - Full Finger',
    'Cycling Gloves - Full Finger',
    '2024-12-15 18:00:00',
    '2024-12-25 18:00:00',
    20,
    40,
    'Brand New',
    'image_uploads/6550b97c71887240a509b04fe02bb89333439556f161e4f0b6716.jpg',
    'gloves, apparel, full finger',
    (SELECT userId FROM User WHERE username = 'trailtamer'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Apparel');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Hybrid Bike - Trek FX',
    'Hybrid Bike - Trek FX',
    '2024-12-20 18:00:00',
    '2024-12-30 18:00:00',
    500,
    700,
    'Used - Good Condition',
    'image_uploads/trek-fx-2-women-s_10361909.jpg',
    'hybrid bike, Trek, commute',
    (SELECT userId FROM User WHERE username = 'bikeenthusiast'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Bikes');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Mountain Bike Tire - Maxxis High Roller',
    'Mountain Bike Tire - Maxxis High Roller',
    '2024-12-25 18:00:00',
    '2025-01-04 18:00:00',
    30,
    60,
    'Brand New',
    'image_uploads/716RhaTRWdL._AC_SL1500_.jpg',
    'tire, Maxxis, parts',
    (SELECT userId FROM User WHERE username = 'cyclelover'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Parts');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Cycling Jersey - Reflective',
    'Cycling Jersey - Reflective',
    '2024-12-30 18:00:00',
    '2025-01-09 18:00:00',
    60,
    120,
    'Brand New',
    'image_uploads/71pxvQeyNML._AC_SX679_.jpg',
    'jersey, reflective, apparel',
    (SELECT userId FROM User WHERE username = 'trailtamer'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Apparel');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Electric Bike - Specialized Turbo Vado',
    'Electric Bike - Specialized Turbo Vado',
    '2024-12-31 18:00:00',
    '2025-01-10 18:00:00',
    2000,
    2500,
    'Used - Excellent Condition',
    'image_uploads/specialized-turbo-vado-5-0-igh_15096144.jpg',
    'electric bike, Specialized, urban',
    (SELECT userId FROM User WHERE username = 'mountainmaverick'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Bikes');

INSERT INTO `Item` (`title`, `description`, `timeCreated`, `endTime`, `startPrice`, `reservePrice`, `itemCondition`, `image`, `tags`, `userId`, `categoryId`)
SELECT
    'Bike Light - USB Rechargeable',
    'Bike Light - USB Rechargeable',
    '2025-01-05 18:00:00',
    '2025-01-15 18:00:00',
    25,
    50,
    'Brand New',
    'image_uploads/51fPcqtPr5L._AC_SL1000_.jpg',
    'light, USB, accessories',
    (SELECT userId FROM User WHERE username = 'urbanrider'),
    (SELECT categoryId FROM ItemCategory WHERE categoryName = 'Accessories');

-- Insert dummy data for Bid table
INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    360,
    '2024-11-01 12:00:00',
    (SELECT userId FROM User WHERE username = 'cyclelover'),
    (SELECT itemId FROM Item WHERE description LIKE '%Mountain Bike - Rockrider%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    820,
    '2024-11-02 14:00:00',
    (SELECT userId FROM User WHERE username = 'urbanrider'),
    (SELECT itemId FROM Item WHERE description LIKE '%Cannondale%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    55,
    '2024-11-03 16:00:00',
    (SELECT userId FROM User WHERE username = 'bikeenthusiast'),
    (SELECT itemId FROM Item WHERE description LIKE '%Helmet%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    50,
    '2024-11-03 17:00:00',
    (SELECT userId FROM User WHERE username = 'cyclelover'),
    (SELECT itemId FROM Item WHERE description LIKE '%Pedals%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    65,
    '2024-11-04 10:00:00',
    (SELECT userId FROM User WHERE username = 'trailtamer'),
    (SELECT itemId FROM Item WHERE description LIKE '%Gloves%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    520,
    '2024-11-05 11:00:00',
    (SELECT userId FROM User WHERE username = 'cyclelover'),
    (SELECT itemId FROM Item WHERE description LIKE '%Trek%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    35,
    '2024-11-05 12:00:00',
    (SELECT userId FROM User WHERE username = 'urbanrider'),
    (SELECT itemId FROM Item WHERE description LIKE '%Tire%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    70,
    '2024-11-06 13:00:00',
    (SELECT userId FROM User WHERE username = 'bikeenthusiast'),
    (SELECT itemId FROM Item WHERE description LIKE '%Jersey%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    2100,
    '2024-11-06 14:00:00',
    (SELECT userId FROM User WHERE username = 'trailtamer'),
    (SELECT itemId FROM Item WHERE description LIKE '%Electric%');

INSERT INTO `Bid` (`amount`, `timeStamp`, `userId`, `itemId`)
SELECT
    30,
    '2024-11-07 15:00:00',
    (SELECT userId FROM User WHERE username = 'mountainmaverick'),
    (SELECT itemId FROM Item WHERE description LIKE '%Light%');

-- Insert dummy data for WatchList table
INSERT INTO `WatchList` (`userId`)
SELECT userId FROM User WHERE username = 'cyclelover';

INSERT INTO `WatchList` (`userId`)
SELECT userId FROM User WHERE username = 'bikeenthusiast';

INSERT INTO `WatchList` (`userId`)
SELECT userId FROM User WHERE username = 'urbanrider';

INSERT INTO `WatchList` (`userId`)
SELECT userId FROM User WHERE username = 'mountainmaverick';

INSERT INTO `WatchList` (`userId`)
SELECT userId FROM User WHERE username = 'trailtamer';

-- Insert dummy data for WatchListEntry table
INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'cyclelover')),
    (SELECT itemId FROM Item WHERE description LIKE '%Mountain Bike - Rockrider%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'cyclelover')),
    (SELECT itemId FROM Item WHERE description LIKE '%Cannondale%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'bikeenthusiast')),
    (SELECT itemId FROM Item WHERE description LIKE '%Helmet%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'bikeenthusiast')),
    (SELECT itemId FROM Item WHERE description LIKE '%Pedals%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'urbanrider')),
    (SELECT itemId FROM Item WHERE description LIKE '%Gloves%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'urbanrider')),
    (SELECT itemId FROM Item WHERE description LIKE '%Trek%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'mountainmaverick')),
    (SELECT itemId FROM Item WHERE description LIKE '%Tire%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'mountainmaverick')),
    (SELECT itemId FROM Item WHERE description LIKE '%Jersey%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'trailtamer')),
    (SELECT itemId FROM Item WHERE description LIKE '%Electric%');

INSERT INTO `WatchListEntry` (`watchListId`, `itemId`)
SELECT
    (SELECT watchListId FROM WatchList WHERE userId = (SELECT userId FROM User WHERE username = 'trailtamer')),
    (SELECT itemId FROM Item WHERE description LIKE '%Light%');