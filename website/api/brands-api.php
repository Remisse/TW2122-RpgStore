<?php
    require_once("../bootstrap.php");

    $brands = $dbh->getBrands();

    header("Content-Type: application/json");
    echo json_encode($brands);
?>
