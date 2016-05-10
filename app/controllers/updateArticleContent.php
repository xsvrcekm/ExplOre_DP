<?php
    function get_connection() {
        $servername = "localhost";
        $username = "martin";
        $password = "Sv/11-m+T";
        $dbname = "explore";
        
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
            $message = "[{$date}] [{$file}] [{$level}] Error loading character set utf8: %s <br />, {$connect->error}".PHP_EOL;
            error_log($message);
            exit();
        }

        //$conn->close();
        return $connect;
    }
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";
    
    $article_id = $_POST['article_id'];
    $content = $_POST['content'];
    
    //include('app/controllers/configDB.php');
    $conn = get_connection();
    
    // SQL query
    $sql = "UPDATE articles SET content='$content' WHERE sme_id='$article_id'";  
    
    // run the query
    if ($conn->query($sql) === TRUE) {
        //echo "***".$article_id."Content updated successfully.***".$content;
    } else {
        $message = "[{$date}] [{$file}] [{$level}] Error while updating article content, {$sql} ; {$conn->error}".PHP_EOL;
        error_log($message);
        //echo "***Error: " . $sql . "<br />" . $conn->error . "<br />***";
    }
    
?>