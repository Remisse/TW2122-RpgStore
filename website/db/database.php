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
        $query = 
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemstock, brandshortname ".
            "FROM item LEFT OUTER JOIN brand ON itembrand = brandid ".
            "JOIN item_has_category ON item = itemid ".
            "JOIN category ON category = categoryid ".
            "WHERE TRUE";
        foreach ($parameters as $param => $value) {
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
                case "categoryid":
                    $query .= " AND categoryid = :categoryid";
                    break;
                case "itemgroup":
                    $query .= " AND FIND_IN_SET(CAST(itemid AS CHAR), :itemgroup)";
            }
        }
        if (isset($parameters["limit"])) {
            $query .= " LIMIT :limit";
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($parameters);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemDetails(int $id) {
        $stmt = $this->pdo->prepare(
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemstock, itemcreator, itempublisher, brandshortname ".
            "FROM item LEFT OUTER JOIN brand ON itembrand = brandid ".
            "WHERE itemid = :id"
        );
        $stmt->execute(array("id" => $id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatestItems(int $n = -1) {
        $query = 
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemstock, brandshortname ".
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
            "SELECT itemid, itemname, itemimg, itemprice, itemdiscount, itemstock, brandshortname ".
            "FROM item LEFT OUTER JOIN brand ON itembrand = brandid ".
            "WHERE itemdiscount > 0.0 ".
            "AND itemstock > 0 ".
            "ORDER BY RAND() ".
            "LIMIT :n"
        );
        $stmt->execute(array("n" => $n));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
