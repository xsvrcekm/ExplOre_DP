<?php
    /*
     * Page to compute number of logins of users according to competition rules
     * For every login is 1 point
     * You can get max 3 points per day:
        * first point for login from 8:00 to 14:00
        * second point for login from 14:00 to 21:00
        * third point for login from 21:00 to 8:00 next day
     */

    require('../../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: registration/login.php'); }
    
    require_once('../header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../../controllers/configDB.php');

    $conn = get_connection();

    $sql = "SELECT uid, log_date FROM user_logs AS ul WHERE ul.log_action = 'login' ";
    $result = $conn->query($sql);
    $act_date = "NULL";
    $e = 0;
    $f = 0;
    $t = 0;
    $score = [1 => 0,
              2 => 0,
              3 => 0,
              4 => 0,
              5 => 0,
              6 => 0,
              7 => 0,
              8 => 0,
              9 => 0,
              10 => 0,
              11 => 0,
              12 => 0,
              13 => 0,
              14 => 0,
              15 => 0,
              16 => 0,
              17 => 0,
              18 => 0,
              19 => 0
             ];
    $users = [1 => "",
              2 => "",
              3 => "",
              4 => "",
              5 => "",
              6 => "",
              7 => "",
              8 => "",
              9 => "",
              10 => "",
              11 => "",
              12 => "",
              13 => "",
              14 => "",
              15 => "",
              16 => "",
              17 => "",
              18 => "",
              19 => ""
             ];

    while($res = $result->fetch_assoc()){
        $uid = $res['uid'];
        $log_date = $res['log_date'];
        $users[$uid] .= $log_date.",";
    }
    
    foreach($users as $uid => $logs) {
        $log_dates = explode(",", $logs);
        
        foreach($log_dates as $log_date) {
            if(!empty($log_date)) {
                //echo $uid.": ".$log_date."<br />";
                $date = explode(" ", $log_date)[0];
                $time = explode(" ", $log_date)[1];

                if(strcmp($act_date,$date) !== 0) {
                    $act_date = $date;
                    $e = 0;
                    $f = 0;
                    $t = 0;
                }

                if(strcmp($act_date,$date) === 0) {
                    $hour = intval(explode(":", $time)[0]);
                    if($hour >= 8 && $hour < 14 && $e === 0) {  // morning login
                        $score[$uid] += 1;
                        $e = 1;
                    }else if($hour >= 14 && $hour < 21 && $f === 0) {   // afternoon login
                        $score[$uid] += 1;
                        $f = 1;
                    }else if((($hour >= 21) || ($hour >= 0 && $hour < 8)) && $t === 0) {    // evening login
                        $score[$uid] += 1;
                        $t = 1;
                    }
                }
            }
        }
    }
    
    rsort($score);  // sort results according to score from the highest
    foreach($score as $k => $v) {
        echo $k.": ".$v."<br />";
    }
    
    $conn->close();
    
    require_once('../footer.php');
    
?>