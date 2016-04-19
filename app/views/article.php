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
    
    $current_user = $_SESSION['memberID'];
    
    $sql = "SELECT viewed_articles, recommended_articles, explanations FROM members AS m WHERE m.memberID = '$current_user' ";
    $result = $conn->query($sql);
    $res = $result->fetch_assoc();
    $va = $res['viewed_articles'];
    $ra = $res['recommended_articles'];
    $expl = $res['explanations'];
    
    //LOGGING
    $sql = "INSERT INTO user_logs (log_action, uid, aid, pos, explanations)
                    VALUES ('click', '$current_user' ,'$id', '$ra', '$expl')";
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully. <br />";
    } else {
        $message = "[{$date}] [{$file}] [{$level}] Error while inserting article, {$sql} ; {$conn->error}" . PHP_EOL;
        error_log($message);
        //echo "Error: " . $sql . "<br />" . $conn->error . "<br />";
    }
    
    //delete from recommended
    $pos = strpos($ra, ','.$id.',');    //if it is in middle of row
    if($pos !== false) {
        $first = substr($ra, 0, $pos);
        $second = substr($ra, $pos+strlen($id)+1, strlen($ra));
        $ran = $first.$second;
        
        $sql = "UPDATE members SET recommended_articles='$ran' WHERE memberID='$current_user'";   
        if ($conn->query($sql) === TRUE) {
            //echo "UPDATE succesful";
        } else {
            $message = "[{$date}] [{$file}] [{$level}] Error while updating article recommended_articles, {$sql} ; {$conn->error}".PHP_EOL;
            //echo $message;
            error_log($message);
        }
    }
    if (substr($ra, 0, strlen($id)+1) === $id.',') {    //if it is at the begining of row
        $ran = substr($ra, strlen($id)+1, strlen($ra));
        
        $sql = "UPDATE members SET recommended_articles='$ran' WHERE memberID='$current_user'";   
        if ($conn->query($sql) === TRUE) {
            //echo "UPDATE succesful";
        } else {
            $message = "[{$date}] [{$file}] [{$level}] Error while updating article recommended_articles, {$sql} ; {$conn->error}".PHP_EOL;
            //echo $message;
            error_log($message);
        }
    }
    
    //delete explanations
    //find position
    $recart = explode(",", $ra);
    for($i = 0 ; $i < count($recart) ; $i++) {
        if (strcmp($recart[$i], $id) === 0) {
            $position = $i;
        }
    }
    //new explanation without that for this article
    $new_expl = "";
    $expls = explode(",", $expl);
    for($i = 0 ; $i < count($expls) ; $i++) {
        if($i !== $position && !empty($expls[$i])) {
            $new_expl .= $expls[$i].",";
        }
    }
    //update explanations
    $sql = "UPDATE members SET explanations='$new_expl' WHERE memberID='$current_user'";   
    if ($conn->query($sql) === TRUE) {
        //echo "UPDATE succesful";
    } else {
        $message = "[{$date}] [{$file}] [{$level}] Error while updating article recommended_articles, {$sql} ; {$conn->error}" . PHP_EOL;
        //echo $message;
        error_log($message);
    }

// log click on this article
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