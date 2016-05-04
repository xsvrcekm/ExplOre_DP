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
    
    $sql = "SELECT memberID FROM members WHERE memberID > 2";
    $members = $conn->query($sql);
    
    while($member = $members->fetch_assoc()) {
        
    echo $member['memberID']."----------------------------------------<br />";

    $mid = $member['memberID'];
        
    $sql = "SELECT * FROM user_logs WHERE uid = '$mid'";
    $result = $conn->query($sql);
    
    $previousDate = "2016-04-18 08:00:01";
    $previousPartOfDay = "morning";
    $realpos = 0;
    
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $action = $row['log_action'];
        $uid = $row['uid'];
        $aid = $row['aid'];
        $pos = $row['pos'];
        $exp = $row['explanations'];
        $date = $row['log_date'];
        
        if($date > (explode(" ", $date)[0] . " 08:00:00")) {    // this day
            $actDate = $date;
        }else {     // previous day
            $actDate = strtotime($date . ' -1 day');
            $actDate = date('Y-m-d H:i:s', $actDate);
        }
        $morning = explode(" ", $actDate)[0] . " 08:00:00";
        $afternoon = explode(" ", $actDate)[0] . " 14:00:00";
        $evening = explode(" ", $actDate)[0] . " 21:00:00";
        $nextmorning = strtotime($morning . ' +1 day');
        $nextmorning = date('Y-m-d H:i:s', $nextmorning);
        
        //echo $date." | ".$morning." | ".$afternoon." | ".$evening." | ".$nextmorning."<br />";
        
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
                if(explode(" ", $actDate)[0] != explode(" ", $previousDate)[0]) {
                    $realpos = 0;
                    $previousDate = $actDate;
                }
                
                if($date > $morning && $date < $afternoon) {
                    if($previousPartOfDay == "morning") {
                        $realpos++;
                    }else {
                        $realpos = 1;
                    }
                    $previousPartOfDay = "morning";
                }else if($date > $afternoon && $date < $evening) {
                    if($previousPartOfDay == "afternoon") {
                        $realpos++;
                    }else {
                        $realpos = 1;
                    }
                    $previousPartOfDay = "afternoon";
                }else if($date > $evening && $date < $nextmorning) {
                    if($previousPartOfDay == "evening") {
                        $realpos++;
                    }else {
                        $realpos = 1;
                    }
                    $previousPartOfDay = "evening";
                }
                
                $explanations = explode(",", $exp);
                $explanation = $explanations[$position-1];
                echo $aid.";".($position+$realpos-1).";".$explanation;
            }else {
                echo $aid.";"."NULL".";"."NULL";
            }
        }else {
            echo "NULL".";"."NULL".";"."NULL";
        }
        echo ";".$date."<br />";
    }
    }
    
    $conn->close();
    
    require_once('../footer.php');
    
?>