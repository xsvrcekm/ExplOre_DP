<?php

    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: registration/login.php'); }
    
    require_once('/header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();
    
    $current_user = $_SESSION['memberID'];
    
    $rank = [3 => 4,
             4 => 5,
             5 => 6,
             6 => 7,
             7 => 8,
             8 => 9,
             9 => 10,
             10 => 11,
             11 => 12,
             12 => 13,
             13 => 14,
             14 => 15,
             15 => 16,
             16 => 17,
             17 => 4,
             18 => 4,
             19 => 4
             ];

    ?>
    <div id="rank">
        <h2> Ahoj aktuálne sa nachádzaš na mieste č.<?php echo $rank[$current_user] ?></h2>
        <p>
            Pre tých ktorý budú najaktívnejší sú pripravené nasledovné ceny: <br />
            <strong>1. cena</strong><br />
            <img src="/ExplORe_DP/app/assets/images/beer.jpg" alt="explore" width="200" height="200"><br />
            <strong>2. - 3. cena</strong><br />
            <img src="/ExplORe_DP/app/assets/images/tshirt.png" alt="explore" width="500" height="260"><br />
        </p>
    </div>
    <?php
    
    $conn->close();
    
    require_once('footer.php');
    
?>