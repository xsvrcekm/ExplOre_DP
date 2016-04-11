<!DOCTYPE html>
<!--
Martin Svrček
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>ExplORe</title>
        <link rel="shortcut icon" href="/ExplORe_DP/app/assets/images/favicon.ico" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/ExplORe_DP/app/assets/styles/basicStyle.css">
        
        <script type="text/javascript" language="javascript" src="/ExplORe_DP/library/jQuery/jquery-1.12.2.js"></script>
        <script src='app/assets/scripts/articles/getArticle.js'></script>
        <script>
            $(document).ready(function(){
                // Underline actual item of main menu
                var fileName = location.pathname.split("/").slice(-1);
                var pageName = String(fileName).slice(0,-4);
                var lis = document.getElementsByTagName("li");
                for (var i = 0; i < lis.length; i++) {
                    if((lis[i].id) == pageName){
                        $(lis[i]).addClass('active').siblings().removeClass('active');
                    }
                }
            });
        </script>
    </head>
    <body>
        <div id="base">
            <div id="header">
                <div id="headerstrip">
                    <div id="headerlogo">
                        <a href="/ExplORe_DP/index.php"> <img src="/ExplORe_DP/app/assets/images/explore_logo.png" alt="explore_logo" width="125" height="35"> </a>
                    </div>
                    <?php
                        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                            echo "<a href='/ExplORe_DP/app/views/registration/logout.php'>";
                        }else{
                            echo "<a href='/ExplORe_DP/app/views/registration/login.php'>";
                        }
                    ?>
                        <div id="headersignin">
                            <?php
                                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                    echo "<p>ODHLÁSENIE</p>";
                                }else{
                                    echo "<p>PRIHLÁSENIE</p>";
                                }
                            ?>
                            <img src="/ExplORe_DP/app/assets/images/lock.png" alt="signIn_logo" width="20" height="27">
                        </div>
                    </a>
                </div>
            </div>
            <div id="mainmenu">
                <nav>
                <ul>
                    <li id="titulka" >
                        <a href="/ExplORe_DP/app/views/categories/titulka.php">&nbsp;TITULKA</a>
                    </li>
                    <li id="domov" >
                        <a href="/ExplORe_DP/app/views/categories/domov.php">&nbsp;DOMOV</a>
                    </li>
                    <li id="svet" >
                        <a href="/ExplORe_DP/app/views/categories/svet.php">&nbsp;SVET</a>
                    </li>
                    <li id="sport" >
                        <a href="/ExplORe_DP/app/views/categories/sport.php">&nbsp;ŠPORT</a>
                    </li>
                    <li id="tech" >
                        <a href="/ExplORe_DP/app/views/categories/tech.php">&nbsp;TECH</a>
                    </li>
                    <li id="kultura" >
                        <a href="/ExplORe_DP/app/views/categories/kultura.php">&nbsp;KULTÚRA</a>
                    </li>
                    <li id="ekonomika" >
                        <a href="/ExplORe_DP/app/views/categories/ekonomika.php">&nbsp;EKONOMIKA</a>
                    </li>
                    <li id="komentare" >
                        <a href="/ExplORe_DP/app/views/categories/komentare.php">&nbsp;KOMENTÁRE</a>
                    </li>
                </ul>
                </nav>
            </div>
            <hr>
            <div id="content">