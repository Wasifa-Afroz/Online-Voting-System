<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = mysqli_connect('localhost', 'root', '', 'shopping mart');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}