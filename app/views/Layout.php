<!DOCTYPE html>
<!--
Martin Svrček
-->
<html>
    <head>
        <script type="text/javascript" language="javascript" src="library/jQuery/jquery-1.12.1.js"></script>
        <script src='scripts/articles/getArticle.js'></script>
        <script type='text/javascript'>
            function showArticle(aid) {
                getArticleContent(aid);
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
    </head>
    <body>
        <h1>ExplORe</h1>
        <?php
            $servername = "localhost";
            $username = "martin";
            $password = "Sv/11-m+T";
            $dbname = "explore";
            /*$servername = "eu-cdbr-azure-west-d.cloudapp.net";
            $username = "b26346e2d51b96";
            $password = "8bb504a6";
            $dbname = "exploreDB";*/

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            echo "Connected successfully <br />";
            
            /* change character set to utf8 */
            if (!$conn->set_charset("utf8")) {
                printf("Error loading character set utf8: %s <br />", $conn->error);
                exit();
            } else {
                printf("Current character set: %s <br />", $conn->character_set_name());
            }
            
            //$conn->close();
        ?>
        <?php
            include('app/articles/GetArticlesFromSME.php');
            
            $getArt = new GetArticlesFromSME();
            $articles = $getArt->run();
            
            foreach ($articles as $article)
            {
                $author = $article->children('dc', true)->creator;
                $aid = explode("/",$article->link)[4];
                $topic = explode(".",(explode("/",$article->link)[5]))[0];
                
                echo "Titulok: $article->title <br />";
                echo "Tema: $topic <br />";
                echo "Autor: $author <br />";
                echo "Link: $article->link <br />";
                echo "Dátum vydania: $article->pubDate <br />";
                echo "Opis: $article->description <br />";
                echo "Guid: $article->guid <br />";
                echo "<input id='clickMe' type='button' value='Show article' onclick='getArticleContent($aid)' /><br />";
                
                //SELECT
                $sql = "SELECT sme_id FROM articles AS a WHERE a.sme_id = '$aid' ";
                $result = $conn->query($sql);
                //if Article is not in database
                if (!($result->num_rows > 0)) {
                    echo "Article is NOT in database. <br />";

                    //INSERT the article itno db
                    $sql = "INSERT INTO articles (sme_id, title, topic, author, description, content, link)
                    VALUES ('$aid', '$article->title', '$topic', '$author', '$article->description', 'Content ...', '$article->link')";

                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully. <br />";
                    } else {
                        echo "Error: " . $sql . "<br />" . $conn->error . "<br />";
                    }
                }else{
                    echo "Article is already in database. <br />";
                }

                echo "-----------------------------------------------------------------------------------";
                echo "<br />";
            }
            $conn->close();
            $url = '20116595';
            //$url = 'http://news.bbc.co.uk';
            //echo "<script type='text/javascript'>getArticleContent('$url');</script>";
        ?>
        
        <div id="target">
        </div>

    </body>
</html>