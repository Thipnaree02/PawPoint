<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    try {
        $connextdb = new PDO("mysql:host=$servername;dbname=db_website; charset=utf8", $username, $password);
        // set the PDO error mode to exception
        $connextdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>