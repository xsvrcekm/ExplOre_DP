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
    
    if (rand(0, 1) == 1) {
        $next = "content";
    } else {
        $next = "collaborative";
    }

    $sql = "SELECT memberID, viewed_articles, recommended_articles FROM members";
    $result = $conn->query($sql);
    while($user = $result->fetch_assoc()) {     //USER
        $uid = $user['memberID'];

        $recommended = explode(",", $user['recommended_articles']);
        $viewed = explode(",", $user['viewed_articles']);
        
        foreach($recommended as $rec_art){      //RECOMMENDED
            echo $uid.": ".$rec_art."<br />";
        }
        
        if(strcmp($next,"content") == 0){
            
            
            
        }else {
            
        }
    }
    
    $conn->close();
    
    require_once('footer.php');
?>