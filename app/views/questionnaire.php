<?php

    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ./app/views/registration/login.php'); }

    require_once('header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();
    
    echo "<form action='questionnaireDone.php' method='post'>";
    
    $sql = "SELECT * FROM articles WHERE id < 50";
    $result = $conn->query($sql);
    while($article = $result->fetch_assoc()) {
        $aid = $article['id'];
        $title = $article['title'];
        $content = $article['content'];
        
        echo "<div id=\"articles\">";
            if (preg_match('%(<img[^>]*>)%i', $content, $regs)) {
                //echo "<a href=\"./app/views/article.php?aid=$aid\">";
                echo "<div id=\"articles_img\">" . $regs[1] . "</div>";
                //echo "</a>";
            } else {
                echo "NOTHING IMAGE<br /><br />";
                //echo $row['content'];
            }

            echo "<div id=\"articles_text\">";
            //echo "<a href=\"./app/views/article.php?aid=$aid\">";
            echo "<div id=\"articles_title\"><h4>" . $title . "</h4></div>";
            //echo "</a>";

            if (preg_match_all('%(<p[^>]*>.*?</p>)%i', $content, $regs)) {
                $cont = "";
                $reg = $regs[0];
                foreach ($reg as $c) {
                    $cont .= $c;
                }
                preg_replace('%>,<%', '><', $cont);
                echo "<div id=\"articles_description\"><p>" . $cont . "</p></div>";
            } else {
                echo "NOTHING PEREX<br /><br />";
            }
            
            echo "</div>";
            
            echo "<div id='questionnaire'>";
                echo "<label for='check'> &nbsp; Tento článok sa mi páči &nbsp; </label>";
                echo "<input id='check' type='checkbox' name='$aid' value='value'>";
            echo "</div>";

            echo "<hr>";

        echo "</div>";
    }
    echo "<div id=\"submit\"> <br />";
    echo "<input type='submit' value='Odoslať formulár' class=\"btn btn-primary btn-block btn-md\">";
    echo "<br /></div>";
    
    echo "</form>";

    $conn->close();
    
    require_once('footer.php');
?>