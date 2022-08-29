<?php
    require_once("../bootstrap.php");

    $categories = array();
    if (isset($_GET["type"])) {
        switch ($_GET["type"]) {
            case "main":
                $categories = $dbh->getMainCategories();
                break;
            case "all":
                $categories = $dbh->getCategories();
                break;
        }
    }

    header("Content-Type: application/json");
    echo json_encode($categories);
?>
