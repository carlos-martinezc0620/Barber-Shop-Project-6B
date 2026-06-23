<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'barbershop_tsw', 3307);
if ($conn->connect_error) {
    echo 'ERROR: ' . $conn->connect_error . PHP_EOL;
} else {
    echo 'OK connected' . PHP_EOL;
    $res = $conn->query("SHOW TABLES");
    while ($row = $res->fetch_array()) echo $row[0] . PHP_EOL;
}
