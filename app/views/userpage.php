<?php
    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ./app/views/registration/login.php'); }

    //session_start();

    require_once('header.php');
    
    $id = $_GET['uid'];
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();
    
    //$id = $_SESSION['uservar'];
    
    // log click on this article
    //$current_user = $_SESSION['memberID'];
    $sql = "SELECT * FROM members AS m WHERE m.memberID = '$id' ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['email'];
    $viewed_articles = explode(",", $row['viewed_articles']);
    
    echo "<h3>Používateľ <a>$username</a> ($email) čítal nasledovné články: </h3><br />";
    // show viewed articles
    foreach($viewed_articles as $aid) {
        //SELECT article
        $sql = "SELECT * FROM articles AS a WHERE a.id = '$aid' ";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];

            echo "<div id=\"articles\">";
            if (preg_match('%(<img[^>]*>)%i', $row['content'], $regs)) {
                echo "<div id=\"articles_img\">" . $regs[1] . "</div>";
                echo "</a>";
                
                echo "<div id=\"articles_text\">";
                echo "<div id=\"articles_title\"><h4>" . $row['title'] . "</h4></div>";
            }
            
            if (preg_match_all('%(<p[^>]*>.*?</p>)%i', $row['content'], $regs)) {
                $content = "";
                $reg = $regs[0];
                foreach ($reg as $c) {
                    $content .= $c;
                }
                preg_replace('%>,<%', '><', $content);
                echo "<div id=\"articles_description\"><p>" . $content . "</p></div>";
            }
            
            echo "</div>";

            echo "<hr>";

            echo "</div>";
        }
    }

    $conn->close();
    
    require_once('footer.php');
?>