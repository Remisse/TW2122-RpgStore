<?php
    require_once("../bootstrap.php");

    $notifications["orders"] = $dbh->getUnreadOrderNotificationsAndMarkAsRead(Session::id());
    $notifications["items"] = $dbh->getUnreadItemNotificationsAndMarkAsRead(Session::id());

    header("Content-Type: application/json");
    echo json_encode($notifications);
?>
