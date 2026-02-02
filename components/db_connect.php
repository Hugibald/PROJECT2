<?php

$hostname = "localhost";
$username = "verenamadercodef_project2";
$password = "CF_Project2";
$dbname = "verenamadercodef_project2";

$connect = mysqli_connect($hostname, $username, $password, $dbname);

if (!$connect) {
    die("Connection failed:" . mysqli_connect_error());
}
