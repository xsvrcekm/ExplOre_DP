<?php
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";
    
    include('app/controllers/configDB.php');
    $conn = get_connection();
    
    //SELECT recommended articles id
    $current_user = $_SESSION['memberID'];
    $sql = "SELECT recommended_articles FROM members AS m WHERE m.memberID = '$current_user' ";
    $result = $conn->query($sql);
    
    $row = $result->fetch_assoc()['recommended_articles'];
    $rec_art_ids = explode(",", $row);

    foreach($rec_art_ids as $rec_art_id) {
        //SELECT article
        $sql = "SELECT * FROM articles AS a WHERE a.id = '$rec_art_id' ";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            echo "<div id=\"articles\">";
                if (preg_match('%(<img[^>]*>)%i', $row['content'], $regs)) {
                    echo "<a href=\"/ExplORe_DP/app/views/article.php?id=$id\">";
                    echo "<div id=\"articles_img\">".$regs[1]."</div>";
                    echo "</a>";
                } else {
                    echo "NOTHING IMAGE<br /><br />";
                    //echo $row['content'];
                }
                echo "<div id=\"articles_text\">";
                    echo "<div id=\"articles_title\"><h4>".$row['title']."</h4></div>";

                    if (preg_match_all('%(<p[^>]*>.*?</p>)%i', $row['content'], $regs)) {
                        $content = "";
                        $reg = $regs[0];
                        foreach($reg as $c){
                            $content .= $c;
                        }
                        preg_replace('%>,<%', '><', $content);
                        echo "<div id=\"articles_description\"><p>".$content."</p></div>";
                        //echo "<div id=\"articles_description\">".$row['content']."</div>";
                    } else {
                        echo "NOTHING PEREX<br /><br />";
                        //echo $row['content'];
                    }
                echo "</div>";
                echo "<hr>";
            echo "</div>";
        }
    }

    
    $conn->close();
?>