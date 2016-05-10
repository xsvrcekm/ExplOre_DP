
    <div id="welcome">
        <h2> Vitaj na stránke projektu <i>ExplORe</i> !</h2>
        <img src="/ExplORe_DP//app/assets/images/favicon.ico" alt="explore" width="50" height="50">
        <p> Tento systém vznikol na Fakulte Informatiky a Informačných Technológií ako súčasť diplomovej práce.
            Obsahom tohto systému sú novinové články, ktoré si jednotlivý používatelia môžu čítať rovnako
            ako na klasických stránkach rôznych spravodajských denníkov. <br /> 
            Ak chceš byť súčasťou tohto projektu stačí ak si budeš prezerať a čítať tieto články.
            Veľmi nám tak pomôžeš pri našej práci.
            Pre prístup k samotným novinovým článkom sa však budeš musieť najprv registrovať.
            V prípade akýchkoľvek otázok nás môžeš kontaktovať na email: <i>domena@domena.sk</i>.</p>
        <div id="registration-submit" class="col-xs-6 col-md-6">
            <a href='/ExplORe_DP//app/views/registration/registration.php'><input type="button" name="SignUp" value="Registrácia" class="btn btn-primary btn-block btn-md"></a>
            <br />
            <a href='/ExplORe_DP//app/views/registration/login.php'>Prihlásenie</a>
        </div>
        <p>
            <?php
            /*$duplicates = "a,a,b,b,c";
            $seperator = ',';
            $unique = uniqueStrs($seperator, $duplicates);
            echo count($duplicates).'<br />';
            echo count($unique).'<br />';
            
            echo $unique;
            echo '<br />';
            
            // removes duplicate substrings between the seperator
            function uniqueStrs($seperator, $str) {
                // convert string to an array using ',' as the seperator
                $str_arr = explode($seperator, $str);
                // remove duplicate array values
                $result = array_unique($str_arr);
                // convert array back to string, using ',' to glue it back
                $unique_str = implode(',', $result);
                // return the unique string
                return $unique_str;
            }*/
            ?>
        </p>
    </div>  