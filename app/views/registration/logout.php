<?php 
require('../../controllers/registration/configDBLogin.php');

logLogout();

function logLogout() {
    include('../../controllers/configDB.php');
    $conn = get_connection();

    $current_user = $_SESSION['memberID'];

    //LOGGING
    $sql = "INSERT INTO user_logs (log_action, uid)
                            VALUES ('logout', '$current_user')";
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully. <br />";
    } else {
        $message = "[{$date}] [{$file}] [{$level}] Error while inserting article, {$sql} ; {$conn->error}" . PHP_EOL;
        error_log($message);
        //echo "Error: " . $sql . "<br />" . $conn->error . "<br />";
    }
}

//logout
$user->logout(); 

//logged in return to index page
header('Location: ../../../index.php');
exit;
?>