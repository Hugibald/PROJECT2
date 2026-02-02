<?php
// Resticts Guests and Users
session_start();
if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
    header("Location: login.php?restricted=true");
    exit;
}

if (isset($_SESSION["user"])) {
    header("Location: userprofile.php");
    exit;
}
