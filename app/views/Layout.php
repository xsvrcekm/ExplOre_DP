<!DOCTYPE html>
<!--
Martin Svrček
-->
<html>
    <head>
        <!--<script type="text/javascript" language="javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>-->
        <script type="text/javascript" language="javascript" src="library/jQuery/jquery-1.12.1.min.js"></script>
        <!--<script src="//library/jQuery/jquery-1.12.1.js"></script>-->
        <!--<script src='scripts/ajax/ajaxCall.js'></script>-->
        <script type="text/javascript">

            function doAjaxOne(){
              alert("doAjaxOne");
              $.ajax({                
                url: 'http://plus.sme.sk/c/20102258/kto-prerusi-premiersku-snuru-roberta-fica.html',
                type: 'GET',
                success: function(res) {
                    alert("ONEsuccess");
                    var headline = $(res.responseText).find('body').text();
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect. Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert('ONEerror' + msg);
                }
              });
            }
            
            function doAjaxTwo(){
                alert("doAjaxTwo");
                $.ajax({
                    type: 'GET',
                    url: 'http://s.sme.sk/r-rss/20102258/plus.sme.sk/kto-prerusi-premiersku-snuru-roberta-fica.html',
                    success: function(result){ 
                        alert("TWOsuccess");
                        $("#target").html(result);
                    },
                    error: function () {
                        alert('TWOerror');
                    }
                });
            }
            
            function doAjaxThree() {
                alert("doAjaxThree");
                $.ajax({
                    url: 'http://news.bbc.co.uk',
                    type: 'GET',
                    success: function(res) {
                        alert("THREEsuccess");
                        var headline = $(res.responseText).find('a.tsh').text();
                        alert(headline);
                    },
                    error: function (jqXHR,exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect. Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        alert('THREEerror' + msg);
                    }
                });
            }         
           
            function doAjax(url){
              // if it is an external URI
              alert("doAjax: " + url);
              if(url.match('^http')){
                alert("doAjax HTTP");
                // call YQL
                $.getJSON("http://s.sme.sk/r-rss/20102258/plus.sme.sk/kto-prerusi-premiersku-snuru-roberta-fica.html",
                  function(data){
                    // this function gets the data from the successful 
                    // JSON-P call
                    alert("doAjax 1");
                    // if there is data, filter it and render it out
                    if(data.results[0]){
                      alert("doAjax 2");
                      var data = filterData(data.results[0]);
                      //container.html(data);
                      alert(data);
                    // otherwise tell the world that something went wrong
                    } else {
                      alert("doAjax 3");
                      var errormsg = '<p>Error: can not load the page.</p>';
                      //container.html(errormsg);
                      alert(errormsg);
                    }
                  }
                );
              // if it is not an external URI, use Ajax load()
              } else {
                alert("doAjax NOT HTTP");
                $('#target').load(url);
              }
            }
            // filter out some nasties
            function filterData(data){
              data = data.replace(/<?\/body[^>]*>/g,'');
              data = data.replace(/[\r|\n]+/g,'');
              data = data.replace(/<--[\S\s]*?-->/g,'');
              data = data.replace(/<noscript[^>]*>[\S\s]*?<\/noscript>/g,'');
              data = data.replace(/<script[^>]*>[\S\s]*?<\/script>/g,'');
              data = data.replace(/<script.*\/>/,'');
              return data;
            }
            
        </script>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>ExplORe</h1>
        <?php
            /*include('app/models/Model.php');
            include('app/controllers/Controller.php');
            include('app/views/View.php');*/
            include('app/articles/GetArticlesFromSME.php');

            /*$model = new Model();
            $controller = new Controller($model);
            $view = new View($controller, $model);
            echo $view->output();*/
            
            $getArt = new GetArticlesFromSME();
            $articles = $getArt->run();
            
            foreach ($articles as $article)
            {
                $author = $article->children('dc', true)->creator;
                
                /*echo "Titulok: $article->title <br />";
                echo "Autor: $author <br />";
                echo "Link: $article->link <br />";
                echo "Dátum vydania: $article->pubDate <br />";
                echo "Opis: $article->description <br />";
                echo "Guid: $article->guid <br />";
                echo "<br />";*/
                             
            }
            //echo "<script type='text/javascript'>doAjaxOne();</script>";
            //echo "<script type='text/javascript'>doAjaxTwo();</script>";
            echo "<script type='text/javascript'>doAjaxThree();</script>";
            $url = 'http://s.sme.sk/r-rss/20102258/plus.sme.sk/kto-prerusi-premiersku-snuru-roberta-fica.html';
            //echo "<script type='text/javascript'>doAjax('$url');</script>";
            
        ?>
        <div id="target">
            target
        </div>
    </body>
</html>