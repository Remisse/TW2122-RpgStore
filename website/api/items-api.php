<?php
    require_once("../bootstrap.php");

    $parameters = array_merge(array("limit" => 10), $_GET);
    // Prepping search terms for use with MySQL's MATCH AGAINST
    if (isset($parameters["search"])) {
        $temp = htmlspecialchars_decode($parameters["search"]);
        $temp = str_replace(" ", " +", $temp);
        $parameters["search"] = "+".$temp;
    }

    $items = $dbh->getItems($parameters);
    prepareItemsForAPI($items);

    header("Content-Type: application/json");
    echo json_encode($items);
?>
