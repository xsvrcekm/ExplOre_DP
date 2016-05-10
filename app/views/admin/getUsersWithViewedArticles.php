<?php
    /*Page show every user with his viewed/readed articles*/

    require('../../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: registration/login.php'); }
    
    require_once('../header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../../controllers/configDB.php');

    $conn = get_connection();

    $sql = "SELECT memberID, viewed_articles FROM members";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $id = $row['memberID'];
        $va = explode(",",$row['viewed_articles']);
        
        foreach($va as $aid) {
            if(!empty($aid)) {
                echo $id.",".$aid."<br />";
            }
        }
    }
    
    $conn->close();
    
    require_once('../footer.php');
    
?>