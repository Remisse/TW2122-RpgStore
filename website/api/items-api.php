<?php
    require_once("../bootstrap.php");

    $items = array();

    // If only the item id is specified, it means that item's page was opened.
    if (isset($_GET["id"]) && count($_GET) == 1) {
        $items = array($dbh->getItem($_GET["id"]));
    } else {
        $parameters = array_merge(array("limit" => 10), $_GET);
        if (isset($parameters["search"]) && $parameters["search"] !== "") {
            $temp = htmlspecialchars_decode($parameters["search"]);
            $temp = str_replace(" ", " +", $temp);
            $parameters["search"] = "+".$temp;
        }
        $items = $dbh->getItems($parameters);
    }
    prepareItemsForAPI($items);

    header("Content-Type: application/json");
    echo json_encode($items);
?>
