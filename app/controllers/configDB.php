<?php
/**
 * Configuraton file for initializing database
 */

    $servername = "localhost";
    $username = "martin";
    $password = "Sv/11-m+T";
    $dbname = "explore";
    /* 
      $servername = "eu-cdbr-azure-west-d.cloudapp.net";
      $username = "b26346e2d51b96";
      $password = "8bb504a6";
      $dbname = "exploreDB"; 
     */

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully <br />";

    /* change character set to utf8 */
    if (!$conn->set_charset("utf8")) {
        printf("Error loading character set utf8: %s <br />", $conn->error);
        exit();
    } else {
        printf("Current character set: %s <br />", $conn->character_set_name());
    }

    //$conn->close();
?>

