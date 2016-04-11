function getArticleContent(aid) {
    var url = 'http://s.sme.sk/export/ma/?c=' + aid;
    var article;
    //alert("doAjax: " + url);
    // if it is an external URI
    if (url.match('^http')) {
        // call YQL
        $.getJSON("http://query.yahooapis.com/v1/public/yql?" +
                "q=select%20*%20from%20html%20where%20url%3D%22" +
                encodeURIComponent(url) +
                "%22&format=xml'&callback=?",
                function (data) {
                    // this function gets the data from the successful 
                    // JSON-P call
                    // if there is data, filter it and render it out
                    if (data.results[0]) {
                        article = filterData(data.results[0]);
                        //$("#target").html(article);

                        $("#invisible").html(article);
                        var node = document.getElementById('invisible');
                        updateArticleContent(node.innerHTML,aid);
                        
                        // otherwise tell the world that something went wrong
                    } else {
                        var errormsg = '<p>Error: can not load the page.</p>';
                        $("#target").html(errormsg);
                    }
                }
        )
        .done(function() {
            $("#invisible").text("");
        })
        .fail(function () {
            alert("error");
        });
        // if it is not an external URI
    } else {
        $('#target').load(url);
    }
}

// filter out some nasties
function filterData(data) {
    data = data.replace(/<body>/, '');
    data = data.replace(/<?\/body[^>]*>/g, '');
    data = data.replace(/[\r|\n]+/g, '');
    data = data.replace(/<!--[\S\s]*?-->/g, '');   //BEFORE: data = data.replace(/<--[\S\s]*?-->/g,'');

    data = data.replace(/<script\b[^>]*\/>/, '');
    data = data.replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm, '');
    data = data.replace(/<noscript\b[^>]*>([\s\S]*?)<\/noscript>/gm, '');

    var img_url = getImgUrl(data);

    //alert(typeof(String(img_url)) + " | " + typeof(data));
    //alert(img_url);
    
    data = data.replace(/<div[^>]*?class="top-foto-box"[^>]*?>/, '<img src="' + img_url + '" style="width:100%">');
    data = data.replace(/'/g,'"');
    data = data.replace(/\s\s+/g, ' ');

    return data;
}

function getImgUrl(data) {
    var img_url = String(data.match(/<div[^>]*?class="top-foto-box"[^>]*?>/));
    img_url = String(img_url.match(/background-image[^;]*?;/));
    img_url = String(img_url.match(/'[^']*?'/));
    img_url = img_url.substring(1, img_url.length - 1);
    return img_url;
}

function updateArticleContent(content, article_id) {

   $.ajax({
      url: 'app/views/updateArticleContent.php',
      type: 'post',
      data: 'content='+content+'&article_id='+article_id,
      /*success: function(output) 
      {
          alert('success, server says '+output);
      }, error: function()
      {
          alert('something went wrong, rating failed');
      }*/
   });

}