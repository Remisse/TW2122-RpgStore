<?php
    require_once("../bootstrap.php");

    if (!isset($_GET["type"])) {
        header("location: index.php");
    }

    $items = array();
    if ($_GET["type"] == "aside_latest") {
        $items = $dbh->getLatestItems(5);
    } else if ($_GET["type"] == "aside_sale") {
        $items = $dbh->getRandomItemsOnSale(5);
    }

    prepareItemsForAPI($items);

    header("Content-Type: application/json");
    echo json_encode($items);
?>
