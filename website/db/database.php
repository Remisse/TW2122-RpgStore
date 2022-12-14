<?php
class DatabaseHelper {
    private $pdo;

    public function __construct(string $hostname, string $username, string $password, string $dbname, string $charset) {
        $dsn = "mysql:host=$hostname;dbname=$dbname;charset=$charset";
        try {
            $this->pdo = new PDO($dsn, $username, $password, array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getCategories() {
        $stmt = $this->pdo->query(
            "SELECT categoryid, categoryname 
            FROM category 
            ORDER BY categorysuper, categoryid ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMainCategories() {
        $stmt = $this->pdo->query(
            "SELECT categoryid, categoryname 
            FROM category 
            WHERE categorysuper IS NULL"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBrands(int $n = -1) {
        $query = 
            "SELECT brandid, brandname, brandpopularity 
            FROM brand 
            ORDER BY brandpopularity ASC";

        $stmt = null;
        if ($n > 0) {
            $query .= " LIMIT :n";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array("n" => $n));
        } else {
            $stmt = $this->pdo->query($query);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItems(array $parameters) {
        $commonQueryPart = 
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, itemstock, categoryname, brandshortname 
            FROM item LEFT OUTER JOIN brand ON itembrand = brandid ";
        $query = "";
        
        if (isset($parameters["categoryid"])) {
            $query = 
                "WITH RECURSIVE cte AS (
                    SELECT * 
                    FROM category 
                    WHERE categoryid = :categoryid 
                    UNION 
                    SELECT cat.* 
                    FROM cte cte2, category cat 
                    WHERE cat.categorysuper = cte2.categoryid) ".
                $commonQueryPart.
                "JOIN cte ON cte.categoryid = itemcategory 
                WHERE deleted = false";
        } else {
            $query = 
                $commonQueryPart.
                "JOIN category ON categoryid = itemcategory 
                WHERE deleted = false";
        }

        foreach ($parameters as $param => $_) {
            switch ($param) {
                case "search":
                    /*
                     * MATCH does not allow grouping columns from different tables.
                     * Also, ':search' can appear only once, it seems, so the search string must be duplicated.
                     */
                    $query .= " AND (MATCH (item.itemname) AGAINST (:search IN BOOLEAN MODE) OR MATCH (brand.brandname) AGAINST (:search_dup IN BOOLEAN MODE))";
                    $parameters["search_dup"] = $parameters["search"];
                    break;
                case "brandid":
                    $query .= " AND brandid = :brandid";
                    break;
                case "itemgroup":
                    $query .= " AND FIND_IN_SET(CAST(itemid AS CHAR), :itemgroup)";
                    $parameters["itemgroup"] = toSQLFriendlyIds($parameters["itemgroup"]);
                    break;
            }
        }
        if (isset($parameters["limit"])) {
            $query .= " LIMIT :limit";
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($parameters);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT item.*, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, categoryname, brandname, brandshortname 
            FROM item LEFT OUTER JOIN brand ON itembrand = brandid 
            JOIN category ON itemcategory = categoryid 
            WHERE itemid = :id 
            AND deleted = false"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemName(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemname 
            FROM item 
            WHERE itemid = :id 
            AND deleted = false"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getItemStock(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemstock 
            FROM item 
            WHERE itemid = :id 
            AND deleted = false"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getItemImage(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemimg 
            FROM item 
            WHERE itemid = :id 
            AND deleted = false"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getLatestItems(int $n = -1) {
        $query = 
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, itemstock, brandshortname 
            FROM item LEFT OUTER JOIN brand ON itembrand = brandid 
            WHERE deleted = false 
            ORDER BY iteminsertiondate DESC";

        $stmt = null;
        if ($n > 0) {
            $query .= " LIMIT :n";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array("n" => $n));
        } else {
            $stmt = $this->pdo->query($query);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomItemsOnSale(int $n = 1) {
        if ($n <= 0) {
            return array();
        }
        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, itemstock, brandshortname 
            FROM item LEFT OUTER JOIN brand ON itembrand = brandid 
            WHERE itemdiscount > 0.0 
            AND deleted = false 
            AND itemstock > 0 
            ORDER BY RAND() 
            LIMIT :n"
        );
        $stmt->execute(array("n" => $n));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemPrices(array $ids) {
        $friendly_ids = toSQLFriendlyIds($ids);

        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS amount 
            FROM item 
            WHERE FIND_IN_SET(CAST(itemid AS CHAR), :itemgroup) 
            AND deleted = false"
        );
        $stmt->execute(array("itemgroup" => $friendly_ids));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemColumnNames() {
        $stmt = $this->pdo->query(
            "SELECT COLUMN_NAME 
            FROM information_schema.columns 
            WHERE TABLE_SCHEMA = 'rpgstore' 
            AND TABLE_NAME = 'item'"
        );

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDeletedItems($n = -1) {
        $query = 
            "SELECT itemid, itemname, itemprice, brandshortname, categoryname 
            FROM item 
            LEFT OUTER JOIN brand ON itembrand = brandid 
            JOIN category ON categoryid = itemcategory 
            WHERE deleted = true";

        $stmt = null;
        if ($n > 0) {
            $query .= " LIMIT :n";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array("n" => $n));
        } else {
            $stmt = $this->pdo->query($query);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkLogin(string $email) {
        $query = 
            "SELECT userid, email, name, password 
            FROM user 
            WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array("email" => $email));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertOrder(int $userid, array $items, string $orderMsg, string $itemAvailabilityMsg) {
        if (count($items) == 0) {
            throw new InvalidArgumentException("Items array is empty");
        }

        $this->pdo->beginTransaction();

        // Create empty order
        $stmt = $this->pdo->prepare("INSERT INTO `order` SET `user` = :userid, `creationdate` = CURDATE()");
        $stmt->execute(array("userid" => $userid));

        // Fill the order with all items from the cart
        $orderid = $this->pdo->lastInsertId();
        foreach ($items as $item) {
            $stmt2 = $this->pdo->prepare("INSERT INTO `order_has_item` (`order`, item, qty, unitprice) VALUES (:orderid, :itemid, :itemqty, :amount)");
            $stmt2->execute(array(
                "orderid" => $orderid,
                "itemid" => $item["itemid"],
                "itemqty" => $item["cartqty"],
                "amount" => $item["amount"])
            );

            // Update each item's stock
            $stmt3 = $this->pdo->prepare("UPDATE item SET itemstock = itemstock - :cartqty WHERE itemid = :itemid");
            $stmt3->execute(array("cartqty" => $item["cartqty"], "itemid" => $item["itemid"]));

            // Notify all admins of any out-of-stock items
            $stmt4 = $this->pdo->prepare(
                "INSERT INTO itemnotification (user, item, message) 
                SELECT userid, itemid, :msg 
                FROM user, item, `admin` 
                WHERE userid = `admin`.user
                AND itemid = :itemid"
            );
            $stmt4->execute(array("msg" => $itemAvailabilityMsg, "itemid" => $item["itemid"]));
        }

        // Notify all admins about the new order
        $stmt5 = $this->pdo->prepare(
            "INSERT INTO ordernotification (user, `order`, message) 
            SELECT userid, :orderid, :msg 
            FROM user, `admin` WHERE userid = `admin`.user"
        );
        $stmt5->execute(array("orderid" => $orderid, "msg" => $orderMsg));

        return $this->pdo->commit();
    }

    public function getUserDetails(int $userid) {
        $stmt = $this->pdo->prepare("SELECT userid, email, name, billingaddress FROM user WHERE userid = :userid");
        $stmt->execute(array("userid" => $userid));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertClient(string $email, string $password, string $name) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("INSERT INTO user (email, password, name) VALUES (:email, :password, :name)");
        $stmt->execute(array("email" => $email, "password" => $password, "name" => $name));

        $userid = $this->pdo->lastInsertId();
        $stmt2 = $this->pdo->prepare("INSERT INTO client VALUES (:userid)");
        $stmt2->execute(array("userid" => $userid)); 

        return $this->pdo->commit();
    }

    public function isEmailAvailable(string $email) {
        $stmt = $this->pdo->prepare("SELECT email FROM user WHERE email = :email");
        $stmt->execute(array("email" => $email));

        return count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 0;
    }

    public function isUserAdmin(int $userid) {
        $stmt = $this->pdo->prepare("SELECT user FROM admin WHERE user = :userid");
        $stmt->execute(array("userid" => $userid));

        return count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 1;
    }

    public function updateUserEmail(int $userid, string $email) {
        $stmt = $this->pdo->prepare(
            "UPDATE IGNORE user SET email = :email WHERE userid = :userid"
        );
        $stmt->execute(array("email" => $email, "userid" => $userid));

        return $stmt->rowCount() == 1;
    }

    public function updateUserPassword(int $userid, string $password) {
        $stmt = $this->pdo->prepare(
            "UPDATE user SET password = :password WHERE userid = :userid"
        );
        $stmt->execute(array("password" => $password, "userid" => $userid));

        return $stmt->rowCount() == 1;
    }

    public function updateUserBillingAddress(int $userid, string $address) {
        $stmt = $this->pdo->prepare(
            "UPDATE user SET billingaddress = :address WHERE userid = :userid"
        );
        $stmt->execute(array("address" => $address, "userid" => $userid));

        return $stmt->rowCount() == 1;
    }

    public function getUnreadNotificationsCount(int $userid) {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(n1 + n2) FROM (SELECT COUNT(*) as n1 FROM ordernotification WHERE user = :userid AND `read` = false) t1,
                                      (SELECT COUNT(*) as n2 FROM itemnotification WHERE user = :userid_dup AND `read` = false) t2"
        );
        $stmt->execute(array("userid" => $userid, "userid_dup" => $userid));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getUnreadOrderNotificationsAndMarkAsRead(int $userid) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare(
            "SELECT notificationid, `order` as orderid, message 
            FROM ordernotification 
            WHERE user = :userid 
            AND `read` = false"
        );
        $stmt->execute(array("userid" => $userid));

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $this->pdo->prepare(
            "UPDATE ordernotification 
            SET `read` = true 
            WHERE user = :userid 
            AND `read` = false");
        $stmt2->execute(array("userid" => $userid));

        $this->pdo->commit();
        return $results;
    }

    public function getUnreadItemNotificationsAndMarkAsRead(int $userid) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare(
            "SELECT notificationid, item as itemid, itemname, brandshortname, message 
            FROM itemnotification 
            JOIN item ON item = itemid 
            LEFT OUTER JOIN brand ON itembrand = brandid  
            WHERE user = :userid 
            AND item = itemid 
            AND `read` = false"
        );
        $stmt->execute(array("userid" => $userid));

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $this->pdo->prepare(
            "UPDATE itemnotification 
            SET `read` = true 
            WHERE user = :userid 
            AND `read` = false"
        );
        $stmt2->execute(array("userid" => $userid));

        $this->pdo->commit();
        return $results;
    }

    public function insertItem(array $parameters) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO item 
            SET itemname = :itemname, 
                itemdescription = :itemdescription, 
                itemimg = :itemimg, 
                iteminsertiondate = CURDATE(), 
                itemprice = :itemprice, 
                itemdiscount = :itemdiscount, 
                itemstock = :itemstock, 
                itemcategory = :itemcategory, 
                itembrand = :itembrand, 
                itemcreator = :itemcreator, 
                itempublisher = :itempublisher"
        );
        
        return $stmt->execute($parameters);
    }

    public function updateItem(array $parameters) {
        $stmt = $this->pdo->prepare(
            "UPDATE item 
            SET itemname = :itemname, 
                itemdescription = :itemdescription, 
                itemimg = :itemimg, 
                iteminsertiondate = CURDATE(), 
                itemprice = :itemprice, 
                itemdiscount = :itemdiscount, 
                itemstock = :itemstock, 
                itemcategory = :itemcategory, 
                itembrand = :itembrand, 
                itemcreator = :itemcreator, 
                itempublisher = :itempublisher 
            WHERE itemid = :itemid"
        );

        return $stmt->execute($parameters);
    }

    public function markItemAsDeleted(int $itemid) {
        $stmt = $this->pdo->prepare("UPDATE item SET deleted = true WHERE itemid = :itemid");

        return $stmt->execute(array("itemid" => $itemid));
    }

    public function restoreDeletedItem(int $itemid) {
        $stmt = $this->pdo->prepare("UPDATE item SET deleted = false WHERE itemid = :itemid");

        return $stmt->execute(array("itemid" => $itemid));
    }

    public function getOrder(int $orderid) {
        $stmt = $this->pdo->prepare(
            "SELECT user, o2.orderid, creationdate, statusid, statusdescription, totalprice 
            FROM `order` o2, orderstatus, (SELECT `order` as orderid, SUM(qty * unitprice) as totalprice 
                                            FROM `order_has_item` 
                                            GROUP BY `order`) t 
            WHERE o2.orderid = t.orderid 
            AND o2.orderid = :orderid 
            AND status = statusid"
        );
        $stmt->execute(array("orderid" => $orderid));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrders(int $limit = -1) {
        $query = 
            "SELECT user, o2.orderid, creationdate, statusid, statusdescription, totalprice 
            FROM `order` o2, orderstatus, (SELECT `order` as orderid, SUM(qty * unitprice) as totalprice 
                                            FROM `order_has_item` 
                                            GROUP BY `order`) t 
            WHERE o2.orderid = t.orderid 
            AND status = statusid 
            ORDER BY user, creationdate, o2.orderid DESC";

        $stmt = null;
        if ($limit >= 0) {
            $query .= " LIMIT :limit";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array("limit" => $limit));
        } else {
            $stmt = $this->pdo->query($query);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersByUser(int $userid) {
        $stmt = $this->pdo->prepare(
            "SELECT o2.orderid, creationdate, statusid, statusdescription, totalprice
            FROM `order` o2, orderstatus, (SELECT `order` as orderid, SUM(qty * unitprice) as totalprice
                                            FROM `order_has_item`
                                            GROUP BY `order`) t
            WHERE user = :userid
            AND o2.orderid = t.orderid
            AND status = statusid
            ORDER BY creationdate, o2.orderid DESC"
        );
        $stmt->execute(array("userid" => $userid));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemsByOrder(int $orderid) {
        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemname, brandshortname, unitprice, qty 
            FROM order_has_item
            JOIN item ON itemid = item
            LEFT OUTER JOIN brand ON itembrand = brandid 
            WHERE `order` = :orderid"
        );
        $stmt->execute(array("orderid" => $orderid));

        return $stmt->fetchALl(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatusAndNotifyUser(int $orderid, int $statusid, string $message) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("UPDATE `order` SET status = :statusid WHERE orderid = :orderid");
        $stmt->execute(array("statusid" => $statusid, "orderid" => $orderid));

        $stmt2 = $this->pdo->prepare("SELECT user FROM `order` WHERE orderid = :orderid");
        $stmt2->execute(array("orderid" => $orderid));
        $userid = $stmt2->fetch(PDO::FETCH_COLUMN);

        $stmt3 = $this->pdo->prepare("INSERT INTO ordernotification SET user = :userid, `order` = :orderid, message = :message");
        $stmt3->execute(array("userid" => $userid, "orderid" => $orderid, "message" => $message));

        return $this->pdo->commit();
    }

    public function updateOrderStatusAndNotifyAdmins(int $userid, int $orderid, int $statusid, string $message) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("UPDATE `order` SET status = :statusid WHERE orderid = :orderid");
        $stmt->execute(array("statusid" => $statusid, "orderid" => $orderid));

        $stmt2 = $this->pdo->prepare(
            "INSERT INTO ordernotification (user, `order`, message)
            SELECT userid, :orderid, :message
            FROM user, admin
            WHERE user = userid");
        $stmt2->execute(array("orderid" => $orderid, "message" => $message));

        return $this->pdo->commit();
    }

    public function canViewOrder(int $userid, int $orderid) {
        $stmt = $this->pdo->prepare(
            "SELECT user 
            FROM `order` 
            WHERE orderid = :orderid 
            AND user = :userid 
            UNION
            SELECT user
            FROM admin
            WHERE user = :userid_dup"
        );
        $stmt->execute(array("userid" => $userid, "orderid" => $orderid, "userid_dup" => $userid));

        return count($stmt->fetch(PDO::FETCH_ASSOC)) > 0;
    }
}
?>
