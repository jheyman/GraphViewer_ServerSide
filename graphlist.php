<?php
 
  // Set default timezone
  date_default_timezone_set('UTC');
 
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
    // 
    $result = $file_db->query('SELECT * FROM graphlist');

     $rows = $result->fetchAll();
    
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
