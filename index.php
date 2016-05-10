<?php
    session_start();
    require_once('app/views/header.php');

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        $current_user = $_SESSION['memberID'];
        if($current_user == "1"){   // Admin Interface
            require_once('app/views/admin.php');
        }else {     // Normal user interface with articles
            require_once('app/views/articles.php');
            /* Questionnaire before experiment:
             * echo "<div id='registration-submit' class='col-xs-6 col-md-6'>";
             * echo "<h4>Vyplň prosím ťa tento krátky formulár.</h4> <p>Tvojou úlohou bude označiť články, ktoré sa ti páčia a ktoré by si si rád prečítal. Pri každom článku je zaškrtávacie políčko <i>Tento článok sa mi páči</i>. Po vyplnení formulára stlač tlačidlo <i>Odoslať formulár</i>.</p> <br /><br />";
             * echo "<a href='./app/views/questionnaire.php'><input id='questionnaire' type='button' value='Vyplniť formulár' class='btn btn-primary btn-block btn-md'/></a>";
             * echo "</div>";
             */
        }
    }else{
        require_once('app/views/welcome.php');
    }
    
    require_once('app/views/footer.php');
?>