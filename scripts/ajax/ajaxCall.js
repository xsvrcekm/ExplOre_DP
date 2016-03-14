            function doAjax(aid){
                var url = 'http://s.sme.sk/export/ma/?c=' + aid;
              // if it is an external URI
              alert("doAjax: " + url);
              if(url.match('^http')){
                //alert("doAjax HTTP");
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
                      //alert(JSON.stringify(data));
                      var data = filterData(data.results[0]);
                      //container.html(data);
                      //$("#target").html(encodeURIComponent(url));
                      $("#target").html(data);
                    // otherwise tell the world that something went wrong
                    } else {
                      var errormsg = '<p>Error: can not load the page.</p>';
                      //container.html(errormsg);
                      $("#target").html(errormsg);
                    }
                  }
                )
                .done(function() {
                  //alert( "second success" );
                })
                .fail(function() {
                  alert( "error" );
                })
                .always(function() {
                  //alert( "complete" );
                });
              // if it is not an external URI, use Ajax load()
              } else {
                alert("doAjax NOT HTTP");
                $('#target').load(url);
              }
            }
            // filter out some nasties
            function filterData(data){
              /*data = data.replace(/<body>/,'');
              data = data.replace(/<?\/body[^>]*>/g,'');*/
              data = data.replace(/[\r|\n]+/g,'');
              data = data.replace(/<!--[\S\s]*?-->/g,'');   //BEFORE: data = data.replace(/<--[\S\s]*?-->/g,'');
              data = data.replace(/<noscript[^>]*>[\S\s]*?<\/noscript>/g,'');
              data = data.replace(/<script[^>]*>[\S\s]*?<\/script>/g,'');
              data = data.replace(/<script.*\/>/,'');
              return data;
            }