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
    
    $current_user = $_SESSION['memberID'];
    
    $id = 1;
    $viewed_articles = "";
    while( $id <= 50 ){
        if(isset($_POST[$id])){
            $sql = "SELECT viewed_articles FROM members AS m WHERE m.memberID = '$current_user' ";
            $result = $conn->query($sql);
            $va = $result->fetch_assoc()['viewed_articles'];
            if (!strpos($va, ','.$id.',') && (substr($va, 0, strlen($id)+1) !== $id.',')) {
                $va = $va.$id.",";
                $sql = "UPDATE members SET viewed_articles='$va' WHERE memberID='$current_user'";   
                if ($conn->query($sql) === TRUE) {
                    echo "<h3>Ďakujem ti, tvoj formulár bol spracovaný.</h3>";
                } else {
                    $message = "[{$date}] [{$file}] [{$level}] Error while updating member viewed_articles, {$sql} ; {$conn->error}".PHP_EOL;
                    error_log($message);
                    echo "<h3>Bohužial formulár sa nepodarilo spracovať.</h3>";
                }
            }
        }
        $id++;
    }
    
    $conn->close();
    
    require_once('footer.php');
?>