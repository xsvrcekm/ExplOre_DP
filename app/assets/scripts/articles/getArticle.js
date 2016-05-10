/*This function gets Article Content and show it on page*/
function showArticleContent(aid) {
    var url = 'http://s.sme.sk/export/ma/?c=' + aid;
    var article;
    // if it is an external URI
    if (url.match('^http')) {
        // call YQL
        $.getJSON("http://query.yahooapis.com/v1/public/yql?" +
                "q=select%20*%20from%20html%20where%20url%3D%22" +
                encodeURIComponent(url) +
                "%22&format=xml'&callback=?",
                function (data) {
                    // if there is data, filter it and render it out
                    if (data.results[0]) {
                        article = filterData(data.results[0]);  // filter the not imprtant html entities of articles 
                        $("#target").text(article); // show article on page
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

/*This function gets Article Content and store it into database*/
function getArticleContent(aid) {
    var url = 'http://s.sme.sk/export/ma/?c=' + aid;
    var article;
    // if it is an external URI
    if (url.match('^http')) {
        // call YQL
        $.getJSON("http://query.yahooapis.com/v1/public/yql?" +
                "q=select%20*%20from%20html%20where%20url%3D%22" +
                encodeURIComponent(url) +
                "%22&format=xml'&callback=?",
                function (data) {
                    // if there is data, filter it and render it out
                    if (data.results[0]) {
                        
                        article = filterData(data.results[0]);  // filter the not imprtant html entities of articles 
                        $("#invisible").html(article);
                        var node = document.getElementById('invisible');    // get the html content of article
                        updateArticleContent(node.innerHTML,aid);   // update article with content
                        
                    // otherwise tell that something went wrong
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
            alert("error in updating content of article");  // admin interface
        });
    // if it is not an external URI
    } else {
        $('#target').load(url);
    }
}

/*Function filter out some nasties, html entities*/
function filterData(data) {
    data = data.replace(/<body>/, '');
    data = data.replace(/<?\/body[^>]*>/g, '');
    data = data.replace(/[\r|\n]+/g, '');
    data = data.replace(/<!--[\S\s]*?-->/g, '');   //BEFORE: data = data.replace(/<--[\S\s]*?-->/g,'');

    data = data.replace(/<script\b[^>]*\/>/, '');
    data = data.replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm, '');
    data = data.replace(/<noscript\b[^>]*>([\s\S]*?)<\/noscript>/gm, '');

    var img_url = getImgUrl(data);
    
    var content = ""; 
    data = data.match(/<p\b[^>]*>([\s\S]*?)<\/p>/gm);
    for (var i = 0; i < data.length; i++) {
        data[i] = data[i].replace (/&#{0,1}[a-z0-9]+;/ig, ' ');
        content += data[i];
    }
    
    if(img_url.startsWith("http")){ // if we found img URL
        content = '<img src="' + img_url + '" width="500">' + content;
    }else { // if we can not found img URL, we use generic one
        content = '<img src="/app/assets/images/article_img.jpg" width="501">' + content;
    }

    return content;
}

/*This function get IMG url from sme div element*/
function getImgUrl(data) {
    var img_url;
    img_url = String(data.match(/<div[^>]*?class="top-foto-box"[^>]*?>/));
    img_url = String(img_url.match(/background-image[^;]*?;/));
    img_url = String(img_url.match(/'[^']*?'/));
    img_url = img_url.substring(1, img_url.length - 1);
    return img_url;
}

/*This function update Article Content with ajax call on php page*/
function updateArticleContent(content, article_id) {
 
   content = content.replace (/&#{0,1}[a-z0-9]+;/ig, ' ');

   $.ajax({
      url: 'app/controllers/updateArticleContent.php',
      type: 'post',
      data: 'content='+content+'&article_id='+article_id,
      success: function(output) 
      {
          //alert('success, server says '+output+' KONIEC');
      }, error: function()
      {
          //alert('something went wrong, rating failed');
      }
   });

}