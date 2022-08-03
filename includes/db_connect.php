<?php
$conn = new mysqli("localhost", "root", "root", "dbfp");
if ($conn->connect_error) {
    die("DB Connect Failed: " . $conn->connect_error);
}
mysqli_query($conn, "SET NAMES 'UTF8'");
