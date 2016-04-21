<?php

    require('../../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: registration/login.php'); }
    
    require_once('../header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../../controllers/configDB.php');

    $conn = get_connection();

    $sql = "SELECT * FROM user_logs";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $action = $row['log_action'];
        $uid = $row['uid'];
        $aid = $row['aid'];
        $pos = $row['pos'];
        $exp = $row['explanations'];
        $date = $row['log_date'];
        
        echo $id.";".$action.";".$uid.";";
        
        if(!empty($aid) && !empty($pos) && !empty($exp)){
            $positions = explode(",", $pos);
            $position = 0;
            for($i = 0 ; $i < count($positions) ; $i++) {
                if(strcmp($positions[$i], $aid) === 0) {
                    $position = $i+1;
                }
            }
            if($position !== 0){
                $explanations = explode(",", $exp);
                $explanation = $explanations[$position-1];
                echo $aid.";".$position.";".$explanation;
            }else {
                echo $aid.";"."NULL".";"."NULL";
            }
        }else {
            echo "NULL".";"."NULL".";"."NULL";
        }
        echo "<br />";
    }
    
    $conn->close();
    
    require_once('../footer.php');
    
?>