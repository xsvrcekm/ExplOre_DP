<?php

    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ./app/views/registration/login.php'); }
    
    require_once('header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();

    $sql = "DELETE FROM articles WHERE content = 'NoContent';";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        $message = "[{$date}] [{$file}] [{$level}] Error while deleting articles, {$sql} ; {$conn->error}".PHP_EOL;
        error_log($message);
        echo "Error deleting record: " . $conn->error;
    }
    
    $conn->close();
    
    require_once('footer.php');
    
?>