<!DOCTYPE html>
<!--
Martin Svrček
-->
<!--#1e8fd7;-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>ExplORe</title>
        <link rel="shortcut icon" href="app/assets/images/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="app/assets/styles/basicStyle.css">
        
        <script type="text/javascript" language="javascript" src="library/jQuery/jquery-1.12.2.js"></script>
        <script src='app/assets/scripts/articles/getArticle.js'></script>
        <script type='text/javascript'>
            function showArticle(aid) {
                getArticleContent(aid);
            }
        </script>
        <script>
        $(document).ready(function(){
            $("a").click(function(){
                $(this).parent().addClass('active').siblings().removeClass('active');
            });
        });
        </script>
    </head>
    <body>
        <div id="base">
            <div id="header">
                <div id="headerstrip">
                    <div id="headerlogo">
                        <img src="app/assets/images/explore_logo.png" alt="explore_logo" width="200" height="56">
                    </div>
                    <div id="headersignin">
                        <p style="float: left; margin-right: 20px;">PRIHLÁSENIE</p>
                        <img src="app/assets/images/lock.png" alt="signIn_logo" width="30" height="41">
                    </div>
                </div>
            </div>
            <div id="mainmenu">
                <nav>
                <ul>
                    <li class="active">
                        <a href="index.php">TITULKA</a>
                    </li>
                    <li>
                        <a href='app/views/categories/domov.php'>DOMOV</a>
                    </li>
                    <li>
                        <a href="app/views/categories/svet.php">SVET</a>
                    </li>
                    <li>
                        <a href="app/views/categories/sport.php">ŠPORT</a>
                    </li>
                    <li>
                        <a href="app/views/categories/tech.php">TECH</a>
                    </li>
                    <li>
                        <a href="app/views/categories/kultura.php">KULTÚRA</a>
                    </li>
                    <li>
                        <a href="app/views/categories/ekonomika.php">EKONOMIKA</a>
                    </li>
                    <li>
                        <a href="app/views/categories/komentare.php">KOMENTÁRE</a>
                    </li>
                </ul>
                </nav>
            </div>