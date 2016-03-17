            function getArticleContent(aid){
              var url = 'http://s.sme.sk/export/ma/?c=' + aid;
              var article;
              //alert("doAjax: " + url);
              // if it is an external URI
              if(url.match('^http')){
                // call YQL
                $.getJSON("http://query.yahooapis.com/v1/public/yql?"+
                            "q=select%20*%20from%20html%20where%20url%3D%22"+
                            encodeURIComponent(url)+
                            "%22&format=xml'&callback=?",
                  function(data){
                    // this function gets the data from the successful 
                    // JSON-P call
                    // if there is data, filter it and render it out
                    if(data.results[0]){
                      article = filterData(data.results[0]);
                      $("#target").html(article);
                    // otherwise tell the world that something went wrong
                    } else {
                      var errormsg = '<p>Error: can not load the page.</p>';
                      $("#target").html(errormsg);
                    }
                  }
                )
                .fail(function() {
                  alert( "error" );
                });
              // if it is not an external URI
              } else {
                $('#target').load(url);
              }
            }
            // filter out some nasties
            function filterData(data){
              data = data.replace(/<body>/,'');
              data = data.replace(/<?\/body[^>]*>/g,'');
              data = data.replace(/[\r|\n]+/g,'');
              data = data.replace(/<!--[\S\s]*?-->/g,'');   //BEFORE: data = data.replace(/<--[\S\s]*?-->/g,'');
              
              data = data.replace(/<script\b[^>]*\/>/,'');
              data = data.replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,''); 
              data = data.replace(/<noscript\b[^>]*>([\s\S]*?)<\/noscript>/gm,'');

              return data;
            }