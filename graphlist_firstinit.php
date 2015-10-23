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
    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
 
    /**************************************
    * Create tables                       *
    **************************************/

    // Create table messages
    $file_db->exec("CREATE TABLE IF NOT EXISTS graphlist (
                    dataId TEXT,
                    timestamp DATETIME,
                    value FLOAT)");
 
 
    // Array with some test data to insert to database             
    
$items = array(
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-24 01:02:03',
                        'value' => 0.75),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-25 02:03:04',
                        'value' => 0.9),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-26 03:04:05',
                        'value' => 0.45),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-27 04:05:06',
                        'value' => 0.6),
                  array('dataId' => 'pingStatus',
                        'timestamp' => '2015-08-01 00:00:01',
                        'value' => 0),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-28 05:06:07',
                        'value' => 0.2),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-29 06:07:08',
                        'value' => 1.0),
                  array('dataId' => 'pingStatus',
                        'timestamp' => '2015-08-01 00:05:01',
                        'value' => 1),
                  array('dataId' => 'waterMeterLogger',
                        'timestamp' => '2015-08-30 07:08:09',
                        'value' => 0.72),
                  array('dataId' => 'pingStatus',
                        'timestamp' => '2015-08-01 00:06:01',
                        'value' => 0),
                  array('dataId' => 'pingStatus',
                        'timestamp' => '2015-08-01 00:15:01',
                        'value' => 1),
                );
  
    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO graphlist (dataId, timestamp, value) 
                VALUES (:dataId, :timestamp, :value)";
    $stmt = $file_db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':dataId', $dataId);
    $stmt->bindParam(':timestamp', $timestamp);
    $stmt->bindParam(':value', $value);

    // Loop thru all messages and execute prepared insert statement
    foreach ($items as $i) {
      // Set values to bound variables
      $dataId =  $i['dataId'];
      $timestamp = $i['timestamp'];
      $value = $i['value'];
 
      // Execute statement
      $stmt->execute();
    }

    // Select all data from file db messages table 
    $result = $file_db->query('SELECT * FROM graphlist');

     $rows = $result->fetchAll();
    
 if ($rows) {
    $response["items"]   = array();
    
    foreach ($rows as $row) {
        $item             = array();
        $item["dataId"] = $row["dataId"];
        $item["timestamp"] = $row["timestamp"];
        $item["value"]    = $row["value"];
        
        //update our repsonse JSON data
        array_push($response["items"], $item);
    }
    
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
