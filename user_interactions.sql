-- CORE REQUIREMENTS 

-- 1. USER REGISTRATION - tested
INSERT INTO User (username, password, email, phoneNumber, userType)
VALUES (?, ?, ?, ?, ?);

-- 2. USER LOGIN - tested
SELECT * FROM User 
WHERE username = ? AND password = ?;

-- 3. AUCTION CREATION (SELLER)
INSERT INTO Item (description, endTime, reservePrice, itemCondition, image, tags, userId, categoryId)
VALUES (?, ?, ?, ?, ?, ?, ?, ?);

-- 4. ITEM SEARCH AND BROWSING
SELECT * FROM Item; -- to browse all items 

-- to look up an item
SELECT * FROM Item WHERE description LIKE CONCAT('%', ?, '%');

-- to filter by category
SELECT * FROM Item WHERE categoryId = ?;

-- to sort by ascending price 
SELECT * FROM Item ORDER BY reservePrice ASC;

-- to sort by end time
SELECT * FROM Item ORDER BY endTime ASC;

-- 5. BIDDING ON ITEMS (BUYER)
-- retrieve the current highest bid 
SELECT MAX(amount) AS highestBid FROM Bid WHERE itemId = ?;

-- 6. RECEIVE OUTCOME NOTIFICATION 
SELECT 
    i.itemId, 
    i.userId AS sellerId, 
    MAX(b.amount) AS highestBid, 
    b.userId AS highestBidder
FROM 
    Item i 
LEFT JOIN 
    Bid b ON i.itemId = b.itemId
WHERE 
    i.itemId = ? AND 
    i.endTime < NOW()
GROUP BY 
    i.itemId;

-- ADDITIONAL FEATURES (in project brief)
-- 7. WATCH AUCTIONS (BUYER) - adding an item to WatchList
INSERT INTO WatchList (userId) VALUES (?);
SET @watchListId = LAST_INSERT_ID();
INSERT INTO WatchListEntry (watchListId, itemId) VALUES (@watchListId, ?);

-- removing an item from WatchList
DELETE FROM WatchListEntry WHERE watchListId = ? AND itemId = ?;

-- 8. ITEM RECOMMENDATIONS (BUYER)
SELECT DISTINCT i.itemId, i.description, i.reservePrice, i.endTime
FROM Item i
JOIN Bid b ON i.itemId = b.itemId
WHERE b.userId IN (
    SELECT DISTINCT b2.userId
    FROM Bid b2
    WHERE b2.itemId IN (
        SELECT itemId
        FROM Bid
        WHERE userId = ?
    )
) AND i.itemId NOT IN (
    SELECT itemId
    FROM Bid
    WHERE userId = ?
);
