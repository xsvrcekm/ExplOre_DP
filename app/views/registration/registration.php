<?php 
require('../../controllers/registration/configDBLogin.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: app/views/categories/titulka.php'); }

//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Používateľské meno je príliš krátke.';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Rovnaké používateľské meno už niekto používa.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Heslo je príliš krátke.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Potvrdzovacie heslo je príliš krátke.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Heslá sa nezhodujú.';
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Prosím zadajte platnú e-mailovú adresu';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Zadaná e-mailová adresa sa už používa.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			/*$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at demo site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();*/

			//redirect to index page
			header('Location: registration.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

require('../header.php');
?>


<div class="registration-container">

    <form role="form" method="post" action="" autocomplete="off">
        <h2>Registrácia</h2>
        <p>Máte už svoj účet? <a href='login.php'>Prihláste sa.</a></p>
        <hr>

        <?php
        //check for any errors
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<p class="bg-danger">' . $error . '</p>';
            }
        }

        //if action is joined show sucess
        if (isset($_GET['action']) && $_GET['action'] == 'joined') {
            echo "<h2 class='bg-success'>Registrácia prebehla úspešne, počkajte kým váš účet bude aktivovaný administrátorom.</h2>";
        }
        ?>

        <div class="form-group">
            <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Používateľské meno" value="<?php if (isset($error)) {
            echo $_POST['username'];
        } ?>" tabindex="1">
        </div>
        <div class="form-group">
            <input type="email" name="email" id="email" class="form-control input-lg" placeholder="E-mailová adresa" value="<?php if (isset($error)) {
            echo $_POST['email'];
        } ?>" tabindex="2">
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Heslo" tabindex="3">
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Potvrdenie hesla" tabindex="4">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div id="registration-submit" class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Registrovať" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
        </div>
    </form>

</div>

<?php
require('../footer.php');
?>

