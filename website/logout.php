<?php
    require_once("bootstrap.php");

    Session::logout();

    header("location: index.php");
?>