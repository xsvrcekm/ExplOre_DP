<!DOCTYPE html>
<!--
Martin Svrček
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>ExplORe</h1>
        <?php
            include('app/models/Model.php');
            include('app/controllers/Controller.php');
            include('app/views/View.php');
            include('app/articles/GetArticlesFromSME.php');

            $model = new Model();
            $controller = new Controller($model);
            $view = new View($controller, $model);
            echo $view->output();
            
            $getArt = new GetArticlesFromSME();
            $articles = $getArt->run();
            
            foreach ($articles as $article)
            {
                echo $article->title;
                echo "<br />";
            }
            
        ?>
    </body>
</html>