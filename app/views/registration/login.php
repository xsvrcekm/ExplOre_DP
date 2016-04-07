<?php
//include config
require_once('../../controllers/registration/configDBLogin.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: ../categories/titulka.php'); } 

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){
            $_SESSION['username'] = $username;
            header('Location: ../categories/titulka.php');
            exit;
	} else {
		$error[] = 'Zadali ste nesprávne používateľské meno alebo heslo alebo váš účet ešte nebol aktivovaný.';
	}

}//end if submit

require('../header.php'); 
?>

	
<div class="registration-container">

    <form role="form" method="post" action="" autocomplete="off">
        <h2>Prihlásenie</h2>
        <p>Ešte nemáte vytvorené konto? <a href='/ExplORe_DP/app/views/registration/registration.php'>Zaregistrujte sa tu!</a></p>
        <hr>

        <?php
        //check for any errors
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<p class="bg-danger">' . $error . '</p>';
            }
        }

        if (isset($_GET['action'])) {

            //check the action
            switch ($_GET['action']) {
                case 'active':
                    echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
                    break;
                case 'reset':
                    echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
                    break;
                case 'resetAccount':
                    echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
                    break;
            }
        }
        ?>

        <div class="form-group">
            <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Používateľské meno" value="<?php
            if (isset($error)) {
                echo $_POST['username'];
            }
            ?>" tabindex="1">
        </div>

        <div class="form-group">
            <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Heslo" tabindex="3">
        </div>

        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-9" style="width: 100%;">
                <p>Zabudli ste heslo? Pošlite nám e-mail na: <i>mato.svrcek@gmail.com</i></p>
            </div>
        </div>

        <hr>
        <div class="row">
            <div id="registration-submit" class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Prihlásiť sa" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
        </div>
    </form>

</div>


<?php 
//include header template
require('../footer.php'); 
?>
