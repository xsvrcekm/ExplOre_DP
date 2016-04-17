<?php
    session_start();
    require_once('app/views/header.php');
   
    //echo "Ahoj: ".exec("java javaApplet")." dnes.";

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        $current_user = $_SESSION['memberID'];
        if($current_user == "1"){
            require_once('app/views/admin.php');
        }else {
            require_once('app/views/articles.php');
        }
    }else{
        require_once('app/views/welcome.php');
    }
    
    require_once('app/views/footer.php');
?>