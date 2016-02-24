<!DOCTYPE html>
<!--
Martin Svrček
-->
<html>
    <head>
        <script type="text/javascript" language="javascript" src="library/jQuery/jquery-1.12.1.min.js"></script>
        <!--<script type="text/javascript" language="javascript" src="library/jQuery/jquery-1.12.1.js"></script>-->
        <script src='scripts/nepiJano/background.js'></script>
        <script src='scripts/nepiJano/nepijano.js'></script>
        <script src='scripts/ajax/ajaxCall.js'></script>
        <script type="text/javascript">
            
        </script>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>ExplORe</h1>
        <?php
            include('app/articles/GetArticlesFromSME.php');
            
            $getArt = new GetArticlesFromSME();
            $articles = $getArt->run();
            
            /*foreach ($articles as $article)
            {
                $author = $article->children('dc', true)->creator;
                
                echo "Titulok: $article->title <br />";
                echo "Autor: $author <br />";
                echo "Link: $article->link <br />";
                echo "Dátum vydania: $article->pubDate <br />";
                echo "Opis: $article->description <br />";
                echo "Guid: $article->guid <br />";
                echo "<br />";
                             
            }*/
            
            
            //echo "<script type='text/javascript'>doAjaxOne();</script>";
            //echo "<script type='text/javascript'>doAjaxTwo();</script>";
            //echo "<script type='text/javascript'>doAjaxThree();</script>";
            //$url = 'http://s.sme.sk/export/ma/?c=20102666';
            $url = 'http://s.sme.sk/r-rss/20102666/domov.sme.sk/korupcia-obera-deti-o-zdravotnu-starostlivost.html';
            //$url = 'http://domov.sme.sk/c/20102666/korupcia-obera-deti-o-zdravotnu-starostlivost.html';
            //$url = 'http://news.bbc.co.uk';
            echo "<script type='text/javascript'>doAjax('$url');</script>";
            
        ?>
        <div id="target">
            target
        </div>
    </body>
</html>