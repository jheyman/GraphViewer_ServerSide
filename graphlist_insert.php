<?php

    if (!isset($_SERVER["HTTP_HOST"])) {
        parse_str($argv[1], $_REQUEST);
    }

    // Set timezone
    date_default_timezone_set('Europe/Paris');

 try {
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:graphlist.sqlite3');

    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO graphlist (dataId, timestamp, value) 
                VALUES (:dataId, :timestamp, :value)";

    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':dataId', $dataId);
    $stmt->bindParam(':timestamp', $timestamp);
    $stmt->bindParam(':value', $value);

    // Set values to bound variables
    $dataId =  $_REQUEST['dataId'];
    $value = $_REQUEST['value'];    
    $timestamp = date('Y-m-d H:i:s');

    // Execute statement
    $stmt->execute();

    // echoing JSON response
    echo json_encode("insert OK");

    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
