<div id="layout">
    <div id="invisible" style="display:none;">
    </div>
    <h1>ExplORe</h1>
    <?php
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('app/controllers/configDB.php');
    include('app/controllers/getArticlesFromSME.php');

    $conn = get_connection();

    $getArt = new GetArticlesFromSME();
    $articles = $getArt->run(); // get the articles information from RSS

    $n = 0;
    $newArticlesIDs = [];
    
    echo "<a href='/ExplORe_DP/app/views/admin/adminOther.php'><input id='other' type='button' value='Other Actions'/></a>";
    
    echo "<hr>";

    // iterate through articles
    foreach ($articles as $article) {
        $author = $article->children('dc', true)->creator;
        $aid = explode("/", $article->link)[4];
        $topic = explode(".", (explode("/", $article->link)[5]))[0];

        // output
        echo "Titulok: $article->title <br />";
        echo "Tema: $topic <br />";
        echo "Autor: $author <br />";
        echo "Link: $article->link <br />";
        echo "Dátum vydania: $article->pubDate <br />";
        echo "Opis: $article->description <br />";
        echo "Guid: $article->guid <br />";
        echo "<input id='clickMe' type='button' value='Show article' onclick='showArticleContent($aid)' /><br />";

        //SELECT
        $sql = "SELECT sme_id FROM articles AS a WHERE a.sme_id = '$aid' ";
        $result = $conn->query($sql);
        //if Article is not in database
        if (!($result->num_rows > 0)) {

            $newArticlesIDs[$n] = $aid;
            $n++;

            echo "Article is NOT in database. <br />";

            //INSERT the article itno db
            $sql = "INSERT INTO articles (sme_id, title, topic, author, description, content, link)
                    VALUES ('$aid', '$article->title', '$topic', '$author', '$article->description', 'NoContent', '$article->link')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully. <br />";
            } else {
                $message = "[{$date}] [{$file}] [{$level}] Error while inserting article, {$sql} ; {$conn->error}".PHP_EOL;
                error_log($message);
                echo "Error: " . $sql . "<br />" . $conn->error . "<br />";
            }
        } else {
            echo "Article is already in database. <br />";
        }

        echo "-----------------------------------------------------------------------------------";
        echo "<br />";
    }

    // get content of articles
    foreach ($articles as $article) {
        $aid = explode("/", $article->link)[4];
        $articleContent;

        // get content only for new articles
        if (in_array($aid, $newArticlesIDs)) {
            ?>
            <script type="text/javascript">
                var articleID = <?php echo $aid; ?>;
                getArticleContent(articleID);
            </script>
            <?php
        }
    }
    
    $conn->close();
    ?>
    <div id="target">
    </div>
</div>