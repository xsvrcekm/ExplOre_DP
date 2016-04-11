<?php
    require('../../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ../../views/registration/login.php'); }

    require_once('../header.php');

    require_once('../footer.php');
?>