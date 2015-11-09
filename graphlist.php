<?php
     
	// used for testing from command line, e.g. php graphlist.php 'delay=-1 hour'
     if (!isset($_SERVER["HTTP_HOST"])) {
        parse_str($argv[1], $_REQUEST);
    }

    // Set timezone
    date_default_timezone_set('Europe/Paris');
 
  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/
 
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:graphlist.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    /************************************************
    * Select all data from file db messages table   *
    *************************************************/
    $delay =  $_REQUEST['delay'];

	$query = "SELECT * FROM graphlist WHERE  timestamp > datetime('now','localtime', :delay)";
    $stmt = $file_db->prepare($query);
    $stmt->bindParam(':delay', $delay);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    if ($rows) {
    
    $response["items"]   = array();
    
    foreach ($rows as $row) {
        $item             = array();
        $item["dataId"] = $row["dataId"];
        $item["timestamp"] = $row["timestamp"];
        $item["value"]    = $row["value"];
        
        //fill our response JSON data array
        array_push($response["items"], $item);
    }

    date_default_timezone_set('Europe/Paris');
    $date = date('Y-m-d H:i:s');
    $response["current_datetime"] = $date;
    
    // echoing JSON response
    echo json_encode($response);
}

//print(json_encode($result->fetchAll()));


// Need this next line  since doing multiple PDO operations in a single functions
// without this line, the next request on file_db results in error "SQLSTATE[HY000]: General error: 6 database table is locked"
unset($result); 
 
    /**************************************
    * Drop tables                         *
    **************************************/
 
    // Drop table messages from file db
    //$file_db->exec("DROP TABLE shoppinglist");
    // Drop table messages from memory db
   // $memory_db->exec("DROP TABLE messages");
 
 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    $file_db = null;
    // Close memory db connection
    //$memory_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>
