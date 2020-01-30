<?php
ob_start();

try {
    $conn = new PDO("mysql:dbname=boogle;host=localhost","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>