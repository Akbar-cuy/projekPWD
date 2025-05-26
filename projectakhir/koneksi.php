<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $db_name = "pockandroll";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
    die('Koneksi gagal: ' . $mysqli->connect_error);
}
?>