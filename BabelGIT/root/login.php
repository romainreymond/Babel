<?php 
require('./config.php');
$general->logged_in_protect();
if (isset($_POST['submit'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
    if (empty($username) === true || empty($password) === true) {
        $errors[] = 'Désolé, mais nous avons besoin de votre nom d\'utilisateur et de votre mot de passe.';
    }
    else if ($users->user_exists($username) === false) {
		$errors[] = 'Désolé ce nom d\'utilisateur n\'exite pas.';
	}
    else {
        $login = $users->login($username, $password);
		if ($login === false) {
			$errors[] = 'Désolé, le nom d\'utlisateur ou le mot de passe est incorrect.';
		}
        else {
            if (isset($_POST['remember'])) {
                $users->onlogin($login);
            }
            session_regenerate_id(true);// destroying the old session id and creating a new one
			// username/password is correct and the login method of the $users object returns the user's id, which is stored in $login.
 			$_SESSION['id'] =  $login; // The user's id is now set into the user's session  in the form of $_SESSION['id']
			$_SESSION['admin'] = intval($users->is_admin($login));
			$_SESSION['admin_connect'] = 0;
			#Redirect the user to home.php.
            header('Location: ' . VIEW_IND);
            exit();
		}
	}
} 
?>
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="fr"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="fr"> <!--<![endif]-->
    <head>
    	<meta http-equiv="X-UA-Compatible" content="IE=8">
        <meta charset="utf-8">
        <title>Authentification - Intra</title>
        <link rel="icon" type="image/png" href="<?php print DIR_IMG; ?>favicon.png" />
        <link rel="stylesheet" type="text/css" href="<?php print DIR_CSS; ?>font-awesome-4.0.3/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php print DIR_CSS; ?>style.css">
    </head>
    <body class="login">
        <div class="logo">
            <h1>Portail <span>SFR SI</span></h1>
        </div>
        <div class="content">
            <?php
                if (isset($_GET['success']) && empty($_GET['success'])) {
                    echo '<p style="color: #090;">Merci de vous être enregistrer.<br>Vous pouvez maintenant vous connecter.</p>';
                }
                if(empty($errors) === false) {
                    echo '<div style="color: #f00;">' . implode('</p><p>', $errors) . '</div>';
                }
            ?>
            <form class="login-form" action="" method="post">
                <h3>Connectez-vous à votre compte</h3>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Nom d'utilisateur</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control" type="text" name="username" placeholder="Nom d'utilisateur" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control" type="password" name="password" placeholder="Mot de passe">
                    </div>
                </div>
                <div class="form-actions">
                    <div style="margin-top: 8px;display: inline-block;">
                        <input id="check" type="checkbox" name="remember">
                        <label for="check" class="checkbox">Garder ma session active</label>
                    </div>
                    <button type="submit" name="submit" class="btn blue pull-right">
                    Connexion <i class="fa fa-sign-in"></i>
                    </button>
                </div>
                <div class="create-account">
                    <p>
                        Vous n'avez pas encore de compte ?<br>
                        <a href="register.php" id="register-btn">Créer un compte.</a>
                    </p>
                </div>
            </form>
        </div>
    </body>
</html>
