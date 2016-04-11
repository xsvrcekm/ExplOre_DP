<?php
    session_start();
    require_once('app/views/header.php');
?>    
<p><applet code="javaApplet.class" width="300" height="200">
</applet></p>
<?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        require_once('app/views/layout.php');
    }else{
        require_once('app/views/welcome.php');
    }
    
    require_once('app/views/footer.php');
?>