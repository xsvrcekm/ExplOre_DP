<?php
    session_start();
    require_once('app/views/header.php');
   
    //echo "Ahoj: ".exec('java javaApplet');

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        require_once('app/views/layout.php');
        //require_once('app/views/articles.php');
    }else{
        require_once('app/views/welcome.php');
    }
    
    require_once('app/views/footer.php');
?>