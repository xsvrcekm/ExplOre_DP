<?php

    require('../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: ./app/views/registration/login.php'); }

    require_once('header.php');
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../controllers/configDB.php');

    $conn = get_connection();
    
    $chr_map = array(
        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
        "\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
        "\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
        "\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
        "\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark

        // Regular Unicode     // U+0022 quotation mark (")
                               // U+0027 apostrophe     (')
        "\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
        "\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
        "\xE2\x80\x98" => "'", // U+2018 left single quotation mark
        "\xE2\x80\x99" => "'", // U+2019 right single quotation mark
        "\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
        "\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9C" => '"', // U+201C left double quotation mark
        "\xE2\x80\x9D" => '"', // U+201D right double quotation mark
        "\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
        "\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
        );
    $chr = array_keys  ($chr_map); // but: for efficiency you should
    $rpl = array_values($chr_map); // pre-calculate these two arrays
    
    $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
                            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
                            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
                            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ľ' => 'l', 'Ľ' => 'L', 'ď' => 'd', 'Ď' => 'D', 'ť' => 't', 'Ť' => 'T',
                            'č' => 'c', 'Č' => 'C', 'ĺ' => 'l', 'Ĺ' => 'L', 'ň' => 'n', 'Ň' => 'N');
    
    $stop_words = file('../../stop_words.txt');
    
    $sql = "SELECT id, content FROM articles AS a WHERE a.key_words IS NULL ";
    $result = $conn->query($sql);
    
    while($article = $result->fetch_assoc()) {
        $aid = $article['id'];
        $input = $article['content'];
        
        $input = strip_tags($input);    // delete html tags
        $input = str_replace($chr, $rpl, html_entity_decode($input, ENT_QUOTES, "UTF-8"));  // replace MS characters
        $input = preg_replace('/[!@#$%^&*()-=_+|;\':",.<>?\']+/i', ' ', $input);    // replace punctuation
        $input = preg_replace('/[0-9]+/i', '', $input);     // replace numeric values
        // replace stop words
        foreach($stop_words as $word){
            $word = preg_replace('/\s+/','',$word);
            $input = str_replace(' '.$word.' ', ' ', $input);
        }
        
        $input = strtr( $input, $unwanted_array );      // replace slovak special characters
        $input = strtolower($input);    // to lowercase
        
        $tokens = preg_split("/[\s,]+/", $input);
        $keywords = [];
        foreach($tokens as $token){
            if(array_key_exists($token,$keywords)){
                $keywords[$token]++;
            }else {
                $keywords[$token] = 1;
            }
        }
        
        arsort($keywords);
        $i = 10;
        foreach($keywords as $key => $value){
            if($i > 0){
                echo $key."->".$value."<br />";
            }
            $i--;
        }
        echo "-------------------------<br />";
    } 
    
    $conn->close();
    
    require_once('footer.php');
?>