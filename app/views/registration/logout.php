<?php 
require('../../controllers/registration/configDBLogin.php');

//logout
$user->logout(); 

//logged in return to index page
header('Location: ../../../index.php');
exit;
?>