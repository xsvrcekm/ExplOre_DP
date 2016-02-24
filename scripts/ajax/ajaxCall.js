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
                        var headline = $(res.responseText).find('body').text();
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
                $.getJSON("http://query.yahooapis.com/v1/public/yql?"+
                            "q=select%20*%20from%20html%20where%20url%3D%22"+
                            encodeURIComponent(url)+
                            "%22&format=xml'&callback=?",
                  function(data){
                    // this function gets the data from the successful 
                    // JSON-P call
                    alert("doAjax 1");
                    // if there is data, filter it and render it out
                    if(data.results[0]){
                      alert("doAjax 2");
                      var data = filterData(data.results[0]);
                      //container.html(data);
                      $("#target").html(data);
                      //alert(data);
                    // otherwise tell the world that something went wrong
                    } else {
                      alert("doAjax 3");
                      var errormsg = '<p>Error: can not load the page.</p>';
                      //container.html(errormsg);
                      alert(errormsg);
                    }
                  }
                )
                .done(function() {
                  alert( "second success" );
                })
                .fail(function() {
                  alert( "error" );
                })
                .always(function() {
                  alert( "complete" );
                });
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