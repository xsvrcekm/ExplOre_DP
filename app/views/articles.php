<?php 

    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";
    
    include('app/controllers/configDB.php');
    $conn = get_connection();
    
    $topics = array("domov", "svet", "sport", "kultura", "tech", "cestovanie", "auto", "ekonomika");
    
    $u = "Tento článok ti bol odporučený, pretože ho čítal aj tebe podobný používateľ: ";
    $a = "Tento článok ti bol odporučený, pretože je podobný článku, ktorý si čítal v minulosti a jeho názov je: <br />";
    $k = "Tento článok ti bol odporučený, pretože máš rád nasledujúcu tému: ";
    $explanation;
    
    //SELECT recommended articles id
    $current_user = $_SESSION['memberID'];
    $sql = "SELECT recommended_articles, explanations FROM members AS m WHERE m.memberID = '$current_user' ";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rec_art_ids = explode(",", $row['recommended_articles']);
        $explanations = explode(",", $row['explanations']);
        $i = 0;
        for($i = 0 ; ($i < 10)&&($i < count($rec_art_ids)) ; $i++) {
            $rec_art_id = $rec_art_ids[$i];
            
            //SELECT article
            $sql = "SELECT * FROM articles AS a WHERE a.id = '$rec_art_id' ";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                $id = $row['id'];
                
                if(!empty($explanations[$i])){
                    $expl = explode(":",$explanations[$i]);
                    $sql = "SELECT title FROM articles AS a WHERE a.id = '$expl[1]' ";
                    $art = $conn->query($sql)->fetch_assoc();

                    if ($expl[0] == 'u') {
                        $sql = "SELECT username FROM members AS m WHERE m.memberID = '$expl[1]' ";
                        $result = $conn->query($sql);
                        $explanation = "<div id=\"articles_strip_one\">" . $u . "<a href = \"app/views/userpage.php?uid=$expl[1]\">" . $result->fetch_assoc()['username'] . "</a>" . "." . "</div>";
                    } elseif ($expl[0] == 'a') {
                        $explanation = "<div id=\"articles_strip_two\">" . $a . "<a href = \"app/views/article.php?aid=$expl[1]\"> \"" . $art['title'] . "\"</a>" . "." . "</div>";
                    } else {
                        $explanation = "<div id=\"articles_strip_one\">" . $k . $expl[1] . "." . "</div>";
                    }

                    echo "<div id=\"articles_strip\">";
                        echo $explanation;
                        echo "<div id=\"articles_strip_category\">";
                            if(in_array($row['topic'], $topics)){
                                echo "<img src='./app/assets/images/categories/".$row['topic'].".png' alt='sport_logo' width='52' height='71'>";
                            }else {
                                echo "<img src='./app/assets/images/categories/ine.png' alt='sport_logo' width='52' height='71'>"; 
                            }
                        echo "</div>";
                    echo "</div>";
                }
                echo "<div id=\"articles\">";
                    if (preg_match('%(<img[^>]*>)%i', $row['content'], $regs)) {
                        echo "<a href=\"./app/views/article.php?aid=$id\">";
                            echo "<div id=\"articles_img\">".$regs[1]."</div>";
                        echo "</a>";
                    } else {
                        echo "<a href=\"/app/views/article.php?aid=$id\">";
                            echo "<div id=\"articles_img\">"."<img src=\"/app/assets/images/article_img.jpg\" width=\"501\">"."</div>";
                        echo "</a>";
                    }
                    
                    echo "<div id=\"articles_text\">";
                        echo "<a href=\"./app/views/article.php?aid=$id\">";
                            echo "<div id=\"articles_title\"><h4>".$row['title']."</h4></div>";
                        echo "</a>";

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
    }
    
    $conn->close();
?>