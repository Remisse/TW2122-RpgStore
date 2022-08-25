<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    require_once("db/database.php");
    require_once("util/functions.php");

    $dbh = new DatabaseHelper("localhost", "root", "root", "rpgstore", "utf8mb4");

    define("UPLOAD_DIR", "./upload/");
?>
