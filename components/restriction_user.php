<?php
// Restricts Guests and Admins
session_start();
if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
    header("Location: login.php?restricted=true");
    exit;
}

if (isset($_SESSION["admin"])) {
    header("Location: dashboard.php");
    exit;
}
