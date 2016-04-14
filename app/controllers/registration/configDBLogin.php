<?php
ob_start();
session_start();

//set timezone
date_default_timezone_set('Europe/London');

//database credentials
/*define('DBHOST','localhost');
define('DBUSER','martin');
define('DBPASS','Sv/11-m+T');
define('DBNAME','explore');*/

define('DBHOST','mysql.hostinger.co.uk');
define('DBUSER','u133754443_user');
define('DBPASS','yeka03zede');
define('DBNAME','u133754443_db');

//application address
define('DIR','http://explore-fiit.16mb.com/');
define('SITEEMAIL','noreply@domain.com');

try {
    //create PDO connection
    $db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);    //$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    //show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('user.php');
$user = new User($db);
?>
