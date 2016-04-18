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

    echo "<a href='/ExplORe_DP/app/views/deleteNoContent.php'><input id='delete' type='button' value='Delete NoContent articles'/></a>";
    
    echo"<br />";
    
    echo "<a href='/ExplORe_DP/app/views/extractKeyWords.php'><input id='extract' type='button' value='Extract Key Words'/></a>";
    
    echo"<br />";
    
    echo "<a href='/ExplORe_DP/app/views/generateExplanations.php'><input id='generate' type='button' value='Generate Explanations'/></a>";
    
    echo "<hr>";
    
    $conn->close();
    
    require_once('footer.php');
    
?>