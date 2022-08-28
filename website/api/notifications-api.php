<?php
    require_once("../bootstrap.php");

    $notifications = $dbh->getUnreadNotificationsAndMarkAsRead(Session::id());

    header("Content-Type: application/json");
    echo json_encode($notifications);
?>
