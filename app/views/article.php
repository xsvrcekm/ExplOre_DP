<?php

    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ./app/views/registration/login.php'); }
    
    require_once('header.php');
   
    $id = $_GET['aid'];
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();
    
    // log click on this article
    $current_user = $_SESSION['memberID'];
    $sql = "SELECT viewed_articles FROM members AS m WHERE m.memberID = '$current_user' ";
    $result = $conn->query($sql);
    $va = $result->fetch_assoc()['viewed_articles'];
    if (!strpos($va, ','.$id.',') && (substr($va, 0, strlen($id)+1) !== $id.',')) {
        $va = $va.$id.",";
        $sql = "UPDATE members SET viewed_articles='$va' WHERE memberID='$current_user'";   
        if ($conn->query($sql) === TRUE) {
            //echo "UPDATE succesful";
        } else {
            $message = "[{$date}] [{$file}] [{$level}] Error while updating article viewed_articles, {$sql} ; {$conn->error}".PHP_EOL;
            //echo $message;
            error_log($message);
        }
    }
    
    // show content of article
    $sql = "SELECT title, content FROM articles AS a WHERE a.id = '$id' ";
    $result = $conn->query($sql);    
    while($row = $result->fetch_assoc()) {
        $content = preg_replace('%>,<%', '><', $row['content']);
        echo "<div id=\"article\">";
            echo "<h2>".$row['title']."</h2>";
            echo "<p>".$content."</p>";
        echo "</div>";
    }
    
    $conn->close();
    
    require_once('footer.php');
?>