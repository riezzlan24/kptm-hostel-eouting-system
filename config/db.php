<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "outing_app";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn)
{
    die("Database connection failed.");
}

?>