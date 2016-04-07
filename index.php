<?php
    session_start();
    require_once('app/views/header.php');
?>   

    <div id="welcome">
        <h2> Vitaj na stránke projektu <i>ExplORe</i> !</h2>
        <img src="/ExplORe_DP/app/assets/images/favicon.ico" alt="explore" width="50" height="50">
        <p> Hlavným princípom tohto systému je personalizovaným spôsobom odporúčať novinové články, ktoré by sa ti mohli páčiť.
            Takto sa ľahšie dostaneš k obsahu, ktorý ťa zaujíma a nebudeš si musieť prezerať všetky články.
            Zároveň sa tento systém snaží priblíiť ti celý proces odporúčania aby si ho lepšie pochopil.
            Pre prístup k samotným novinovým článkom sa však budeš musieť najprv registrovať.
            V prípade akýchkoľvek otázok nás môžeš kontaktovať na email: <i>domena@domena.sk</i>.
        </p>
        <div id="registration-submit" class="col-xs-6 col-md-6">
            <a href='/ExplORe_DP/app/views/registration/registration.php'><input type="button" name="SignUp" value="Registrácia" class="btn btn-primary btn-block btn-md"></a>
            <br />
            <a href='/ExplORe_DP/app/views/registration/login.php'>Prihlásenie</a>
        </div>
    </div>
         
<?php    
    require_once('app/views/footer.php');
?>