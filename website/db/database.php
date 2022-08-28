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

    public function getMainCategories() {
        $stmt = $this->pdo->query(
            "SELECT categoryid, categoryname ".
            "FROM category ".
            "WHERE categorysuper IS NULL"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBrands(int $n = -1) {
        $query = 
            "SELECT brandid, brandname, brandpopularity ".
            "FROM brand ".
            "ORDER BY brandpopularity ASC";
        if ($n > 0) {
            $query.=" LIMIT :n";
        }
        $stmt = $this->pdo->prepare($query);

        if ($n > 0) {
            $stmt->execute(array("n" => $n));
        } else {
            $stmt->execute();
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
                    UNION ALL 
                    SELECT cat.* 
                    FROM cte ct, category cat 
                    WHERE cat.categorysuper = ct.categoryid) ".
                $commonQueryPart.
                "JOIN cte ON cte.categoryid = itemcategory 
                WHERE TRUE";
        } else {
            $query = 
                $commonQueryPart.
                "JOIN category ON categoryid = itemcategory 
                WHERE TRUE";
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
            WHERE itemid = :id"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemName(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemname 
            FROM item 
            WHERE itemid = :id"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getItemStock(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemstock 
            FROM item 
            WHERE itemid = :id"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getLatestItems(int $n = -1) {
        $query = 
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, itemstock, brandshortname ".
            "FROM item LEFT OUTER JOIN brand ON itembrand = brandid ".
            "ORDER BY iteminsertiondate DESC";
        if ($n > 0) {
            $query.=" LIMIT :n";
        }
        $stmt = $this->pdo->prepare($query);

        if ($n > 0) {
            $stmt->execute(array("n" => $n));
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomItemsOnSale(int $n = 1) {
        if ($n <= 0) {
            return array();
        }
        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS pricediscount, itemstock, brandshortname ".
            "FROM item LEFT OUTER JOIN brand ON itembrand = brandid ".
            "WHERE itemdiscount > 0.0 ".
            "AND itemstock > 0 ".
            "ORDER BY RAND() ".
            "LIMIT :n"
        );
        $stmt->execute(array("n" => $n));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemPrices(array $ids) {
        $friendly_ids = toSQLFriendlyIds($ids);

        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemprice - CAST(itemprice * itemdiscount AS UNSIGNED) AS amount ".
            "FROM item ".
            "WHERE FIND_IN_SET(CAST(itemid AS CHAR), :itemgroup)"
        );
        $stmt->execute(array("itemgroup" => $friendly_ids));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkLogin(string $email) {
        $query = 
            "SELECT userid, email, name, password ".
            "FROM user ".
            "WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array("email" => $email));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertOrder(int $userid, array $items, string $messageToAdmins) {
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
        }

        // Notify all admins about the new order
        $stmt4 = $this->pdo->prepare(
            "INSERT INTO ordernotification (user, `order`, message) 
            SELECT userid, :orderid, :msg 
            FROM user, `admin` WHERE userid = `admin`.user"
        );
        $stmt4->execute(array("orderid" => $orderid, "msg" => $messageToAdmins));

        return $this->pdo->commit();
    }

    public function getUserDetails($userid) {
        $stmt = $this->pdo->prepare("SELECT userid, email, name, billingaddress FROM user WHERE userid = :userid");
        $stmt->execute(array("userid" => $userid));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertClient($email, $password, $name) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare("INSERT INTO user (email, password, name) VALUES (:email, :password, :name)");
        $stmt->execute(array("email" => $email, "password" => $password, "name" => $name));

        $userid = $this->pdo->lastInsertId();
        $stmt2 = $this->pdo->prepare("INSERT INTO client VALUES (:userid)");
        $stmt2->execute(array("userid" => $userid)); 

        return $this->pdo->commit();
    }

    public function isEmailAvailable($email) {
        $stmt = $this->pdo->prepare("SELECT email FROM user WHERE email = :email");
        $stmt->execute(array("email" => $email));

        return count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 0;
    }

    public function isUserAdmin($userid) {
        $stmt = $this->pdo->prepare("SELECT user FROM admin WHERE user = :userid");
        $stmt->execute(array("userid" => $userid));

        return count($stmt->fetchAll(PDO::FETCH_ASSOC)) == 1;
    }

    public function getUnreadNotificationsCount($userid) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS n FROM ordernotification WHERE user = :userid AND `read` = false");
        $stmt->execute(array("userid" => $userid));

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }

    public function getUnreadNotificationsAndMarkAsRead($userid) {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare(
            "SELECT notificationid, `order`, message 
            FROM ordernotification 
            WHERE user = :userid 
            AND `read` = false");
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
}
?>
