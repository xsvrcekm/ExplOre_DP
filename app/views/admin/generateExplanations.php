<?php
    /*Page to generate explanations to recommended articles*/

    require('../../controllers/registration/configDBLogin.php');
    if(!$user->is_logged_in()){ header('Location: registration/login.php'); }

    require_once('../header.php');
    
    $mid = $_GET['mid'];
    
    ini_set('error_log', 'tmp/php_error.log');

    $date = date("Y-m-d h:m:s");
    $file = __FILE__;
    $level = "error";

    include('../../controllers/configDB.php');

    $conn = get_connection();
    
    if (rand(1, 10) < 6) {  // select which of the explanation technique will be at first place in list
        $next = "content";
    } else {
        $next = "collaborative";
    }

    // get viewed and recommended articles for specific user
    $sql = "SELECT memberID, viewed_articles, recommended_articles FROM members WHERE memberID = '$mid'";
    $result = $conn->query($sql);
    
    while($user = $result->fetch_assoc()) {     // ITERATE THROUGH USER (IT IS JUST ONE)
        $explanation = "";
        
        $uid = $user['memberID'];   // get user ID

        $recommended = explode(",", $user['recommended_articles']); // get user recommended articles
        $viewed = explode(",", $user['viewed_articles']);           // get user viewed/readed articles
        
        $viekw = getKW($viewed,$conn);      // get key-words for all viewed articles
        $reckw = getKW($recommended,$conn); // get key-words for all recommended articles
        
        if(count($recommended) > 1){    // if user has at least one recommendation
            $ir = 0;
            foreach($recommended as $rec_art){      // ITERATE THROUGH RECOMMENDED ARTICLES
                if(!empty($rec_art)){
                    $rkw = divideKW($reckw[$ir],0);     // get array of key words
                    $rkwf = divideKW($reckw[$ir],1);    // get array of occurences of key-words

                    if(strcmp($next,"content") == 0){   // if we start the list with content explanations
                        $best = contentBased($viewed, $viekw, $reckw, $rkw, $rkwf, $rec_art, $conn);
                        $next = "collaborative";
                        $explanation .= "a:".$best.",";
                    }else {     // if we start the list with collaborative explanations
                        $best = collaborationBased($viewed, $viekw, $reckw, $rkw, $rkwf, $rec_art, $conn);
                        $next = "content";
                        if(strcmp($best,"NULL") == 0) { // if we can not find user who reads recommended articles, we use content explanation
                            $best = contentBased($viewed, $viekw, $reckw, $rkw, $rkwf, $rec_art, $conn);
                            $next = "collaborative";
                            $explanation .= "a:".$best.",";
                        }else {
                            $explanation .= "u:".$best.",";
                        }
                    }
                }
                echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br />";
                $ir++;
            }
        }
        echo "EXPLANATION: ".$explanation."<br />";
        insertExplanation($explanation, $uid, $conn);
        echo "<br />++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br />";
    }
    
    /*This function update user model with explanations*/
    function insertExplanation($explanation, $uid, $conn) {
        $sql = "UPDATE members SET explanations='$explanation' WHERE memberID='$uid'";   
        if ($conn->query($sql) === TRUE) {
            echo "UPDATE of explanations is succesful";
        } else {
            $message = "[{$date}] [{$file}] [{$level}] Error while updating article explanations, {$sql} ; {$conn->error}".PHP_EOL;
            error_log($message);
            echo "ERROR while updating explanations";
        }
    }
    
    /*This function compute collaborative based explanations*/
    function collaborationBased($viewed, $viekw, $reckw, $rkw, $rkwf, $rec_art, $conn) {
        
        $usersWhoReads = findUsersWhoReadsArticle($rec_art, $conn);
        $bestCS = -1;
        $bestCSuserID = "NULL";
        
        foreach($usersWhoReads as $uwr){
            echo "<br />USER: ".$uwr."<br />";
            $userViewedArticles = findViewedArticles($uwr, $conn);
            $uvaKW = getKWFromVeiewedArticles($userViewedArticles, $conn);
            
            $ukw = divideKW($uvaKW,0);
            $ukwf = divideKW($uvaKW,1);
            
            $vectorKW = mergeArrays($rkw, $ukw);

            $recvec = createFrequencyVector($vectorKW, $rkw, $ukw, $rkwf, $ukwf, 0);
            $usevec = createFrequencyVector($vectorKW, $rkw, $ukw, $rkwf, $ukwf, 1);

            if (count($recvec) == count($usevec)) {   //same length
                $cs = computeCosineSimilarity($recvec, $usevec);
                if($cs > $bestCS){
                    $bestCS = $cs;
                    $bestCSuserID = $uwr;
                }
                echo "<br />COSINE: " . $cs . "<br />"; 
            }
            
        }
        echo "<br />BEST COSINE: " . $bestCS . " | " . $bestCSuserID . "<br />";
        return $bestCSuserID;
    }
    
    /*This function try to find user who reads particular articles*/
    function findUsersWhoReadsArticle($aid, $conn) {
        
        $sql = "SELECT memberID, viewed_articles FROM members";
        $result = $conn->query($sql);
        $users = [];
        $i = 0;
        while ($row = $result->fetch_assoc()){
            $uva = $row['viewed_articles'];
            $uid = $row['memberID'];
           
            if (strpos($uva, ','.$aid.',') || (substr($uva, 0, strlen($aid)+1) === $aid.',')) {
                $users[$i] = $uid;
                $i++;
            }
        }
        return $users;
    }
    
    /*This function find all viewed articles for specific user*/
    function findViewedArticles($uwr, $conn) {
        $sql = "SELECT viewed_articles FROM members WHERE memberID = '$uwr'";
        $va = $conn->query($sql)->fetch_assoc()['viewed_articles'];
        
        return explode(",", $va);
    }
    
    /*This function get all key-words for specific array of articles readed by one user - User model*/
    function getKWFromVeiewedArticles($userViewedArticles, $conn) {
        $userkw = "";
        
        foreach($userViewedArticles as $uwa) {
            $sql = "SELECT key_words FROM articles WHERE id = '$uwa'";
            $result = $conn->query($sql)->fetch_assoc()['key_words'];
            $userkw .= $result;
        }
        return $userkw;
    }
    
    /*This function compute content based explanations*/
    function contentBased($viewed, $viekw, $reckw, $rkw, $rkwf, $rec_art, $conn) {
        $bestCS = -1;
        $bestCSarticleID = "NULL";
        $iv = 0;
        foreach($viewed as $vie_art) {      //VIEWED
            if (!empty($vie_art)) {
                $vkw = divideKW($viekw[$iv], 0);
                $vkwf = divideKW($viekw[$iv], 1);

                $vectorKW = mergeArrays($rkw, $vkw);

                $recvec = createFrequencyVector($vectorKW, $rkw, $vkw, $rkwf, $vkwf, 0);
                $vievec = createFrequencyVector($vectorKW, $rkw, $vkw, $rkwf, $vkwf, 1);

                if (count($recvec) == count($vievec)) {   //same length
                    if (sameTopic($rec_art, $vie_art, $conn)) {
                        $recvec[count($recvec)] = 5;
                        $vievec[count($vievec)] = 5;
                    }
                    $cs = computeCosineSimilarity($recvec, $vievec);
                    if($cs > $bestCS) {
                        $bestCS = $cs;
                        $bestCSarticleID = $vie_art;
                    }
                    echo "<br />COSINE: " . $cs . "<br />";
                }
            }
            $iv++;
        }
        echo "<br />BEST COSINE: " . $bestCS . " | " . $bestCSarticleID . "<br />";
        return $bestCSarticleID;
    }
    
    /*This function find key-words for specific articles*/
    function getKW($articles,$conn) {
        $j = 0;
        $key_words = [];
        foreach($articles as $art){      //VIEWED
            $sql = "SELECT key_words FROM articles WHERE id = '$art'";
            $kw = $conn->query($sql)->fetch_assoc()['key_words'];
            if(!empty($kw)){
                $key_words[$j] = $kw;
            }
            $j++;
        } 
        return $key_words;
    }
    
    /* This function return key-words or occurences of key-words according to variable sign
     * 0 for key-words, 1 for occurences
     */
    function divideKW($stringKW,$sign) {
        $array = explode(",", $stringKW);
        $j = 0;
        $kw = [];
        foreach($array as $a) {
            if(!empty($a)){
                $kw[$j] = explode(":", $a)[$sign];
            }
            $j++;
        }
        return $kw;
    }
    
    /*This function merge two arrays into one without duplicates*/
    function mergeArrays($rkw,$vkw) {
        $vector = $vkw;
        $vkwLength = count($vkw);
        $rkwLength = count($rkw);
        $j = $vkwLength;
        $i = 0;
        
        //echo $vkwLength." | ".$rkwLength."<br />";
        
        for($i = 0 ; $i < $rkwLength ; $i++) {
            if(!in_array($rkw[$i],$vector)) {
                $vector[$j] = $rkw[$i];
                $j++;
            }
        }
        return $vector;
    }
    
    /*This function creates frequency vector*/
    function createFrequencyVector($vector,$rkw,$vkw,$rkwf,$vkwf,$sign) {
        $i = 0;
        foreach($vector as $vec){
            //echo $vec.", ";
            $rpos = array_search($vec,$rkw);
            $vpos = array_search($vec,$vkw);
            if($rpos !== false){
                $reckwf[$i] = $rkwf[$rpos];
            }else {
                $reckwf[$i] = 0;
            }
            if($vpos !== false){
                $viekwf[$i] = $vkwf[$vpos];
            }else {
                $viekwf[$i] = 0;
            }
            $i++;
        }
        
        if($sign == 0){
            return $reckwf;
        }
        return $viekwf;
    }
    
    /*This function compute CosineSimilarity from two vectors*/
    function computeCosineSimilarity($vec1, $vec2) {
        $dotProduct = 0.0;
        $magnitude1 = 0.0;
        $magnitude2 = 0.0;
        $cosineSimilarity = 0.0;
        $i;
        
        for($i = 0 ; $i < count($vec1) ; $i++ ) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $magnitude1 += pow($vec1[$i],2);
            $magnitude2 += pow($vec2[$i],2);
        }
        
        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);
        
        if($magnitude1 !== 0.0 && $magnitude2 != 0.0) {
            $cosineSimilarity = $dotProduct / ($magnitude1 * $magnitude2);
        }else {
            return 0.0;
        }
        return $cosineSimilarity;
    }
    
    /*This function finds if the two articles have the same topic*/
    function sameTopic($article1, $article2, $conn) {
        $sql = "SELECT topic FROM articles WHERE id = '$article1'";
        $topic1 = $conn->query($sql)->fetch_assoc()['topic'];
        
        $sql = "SELECT topic FROM articles WHERE id = '$article2'";
        $topic2 = $conn->query($sql)->fetch_assoc()['topic'];
        
        //echo $topic1." | ".$topic2."<br />";
        
        if(strcmp($topic1,$topic2) !== 0){
            return false;
        }
        return true;
    }
    
    $conn->close();
    
    require_once('../footer.php');
?>