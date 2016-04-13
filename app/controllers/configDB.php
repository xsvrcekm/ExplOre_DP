<?php
/**
 * Configuraton file for initializing database
 */
function get_connection() {
    $servername = "localhost";
    $username = "martin";
    $password = "Sv/11-m+T";
    $dbname = "explore";
     
    //  $servername = "eu-cdbr-azure-west-d.cloudapp.net";
    //  $username = "b26346e2d51b96";
    //  $password = "8bb504a6";
    //  $dbname = "exploreDB"; 

    // Create connection
    $connect = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($connect->connect_error) {
        $message = "[{$date}] [{$file}] [{$level}] Connection failed: {$connect->connect_error}".PHP_EOL;
        error_log($message);
        die("Connection failed: " . $connect->connect_error);
    }
    //echo "Connected successfully <br />";

    // change character set to utf8
    if (!$connect->set_charset("utf8")) {
        //printf("Error loading character set utf8: %s <br />", $connect->error);
        $message = "[{$date}] [{$file}] [{$level}] Error loading character set utf8: %s <br />, {$connect->error}".PHP_EOL;
        error_log($message);
        exit();
    }

    //$conn->close();
    return $connect;
}

?>

