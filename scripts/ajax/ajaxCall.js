            function doAjax(url){
              // if it is an external URI
              alert("doAjax: " + url);
              if(url.match('^http')){
                alert("doAjax HTTP");
                // call YQL
                $.getJSON('http://s.sme.sk/r-rss/20102258/plus.sme.sk/kto-prerusi-premiersku-snuru-roberta-fica.html',
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