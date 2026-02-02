<?php
// Restricts loged in users (admins/users)
session_start();

if (isset($_SESSION["user"])) {
    header("Location: userprofile.php");
    exit;
}

if (isset($_SESSION["admin"])) {
    header("Location: dashboard.php");
    exit;
}
