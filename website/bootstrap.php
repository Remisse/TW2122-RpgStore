<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    session_start();

    require_once("db/database.php");
    require_once("util/functions.php");
    require_once("util/cartmanager.php");
    require_once("util/sessionmanager.php");

    $dbh = new DatabaseHelper("localhost", "root", "root", "rpgstore", "utf8mb4");

    $cartCount = Cart::countAll();
    $notificationsCount = $dbh->getUnreadNotificationsCount(Session::id());

    define("UPLOAD_DIR", "upload/");
?>
