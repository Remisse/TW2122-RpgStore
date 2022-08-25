<?php
    require_once("../bootstrap.php");

    $categories = $dbh->getMainCategories();

    header("Content-Type: application/json");
    echo json_encode($categories);
?>
