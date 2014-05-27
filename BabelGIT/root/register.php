<?php
require('./config.php');
$general->logged_in_protect();
# if form is submitted
if (isset($_POST['submit'])) {
	if(empty($_POST['username']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['password']) || empty($_POST['email'])) {
		$errors[] = 'Tous les champs sont requis.';
	}
    else {
        
        #validating user's input with functions that we will create next
        if ($users->user_exists($_POST['username']) === true) {
            $errors[] = 'Ce nom d\'utilisateur est déjà pris.';
        }
        if(!ctype_alnum($_POST['username'])){
            $errors[] = 'Entrez un nom d\'utilisateur avec uniquement des caractères alphanumériques.';	
        }
        if (strlen($_POST['password']) < 6){
            $errors[] = 'Votre mot de passe doit contenir au moins 6 caractères.';
        }
        else if (strlen($_POST['password']) > 18){
            $errors[] = 'Votre mot de passe doit contenir moins de 18 caractères.';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Entrez un email valide.';
        }
        else if ($users->email_exists($_POST['email']) === true) {
            $errors[] = 'Cet email existe déjà.';
        }
	}
 	if(empty($errors) === true){
		$username 	= htmlentities($_POST['username'], null, "UTF-8");
        $nom        = htmlentities($_POST['nom'], null, "UTF-8");
        $prenom     = htmlentities($_POST['prenom'], null, "UTF-8");
		$password 	= $_POST['password'];
		$email 		= htmlentities($_POST['email'], null, "UTF-8");
		$users->register($username, $nom, $prenom, $password, $email);
		header('Location: login.php?success');
		exit();
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="fr"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="fr"> <!--<![endif]-->
    <head>	
	<meta http-equiv="X-UA-Compatible" content="IE=8">
        <meta charset="utf-8">
        <title>Créer un compte - Intra</title>
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
                if(empty($errors) === false) {
                    echo '<div style="color: #f00;">' . implode('</p><p>', $errors) . '</div>';
                }
            ?>
            <form class="register-form" action="" method="post">
                <h3>Enregistrez-vous</h3>
                <p>Entrez vos informations personnelles:</p>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Nom</label>
                    <div class="input-icon">
                        <i class="fa fa-font"></i>
                        <input class="form-control" type="text" name="nom" placeholder="Nom" value="<?php if(isset($_POST['nom'])) echo htmlentities($_POST['nom']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Prénom</label>
                    <div class="input-icon">
                        <i class="fa fa-bold"></i>
                        <input class="form-control" type="text" name="prenom" placeholder="Prénom" value="<?php if(isset($_POST['prenom'])) echo htmlentities($_POST['prenom']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">E-mail</label>
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control" type="text" name="email" placeholder="E-mail" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']); ?>">
                    </div>
                </div>
                <p>Remplissez les détails de votre compte:</p>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Nom d'utilisateur</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Nom d'utilisateur" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>">
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
                    <button type="submit" name="submit" class="btn blue pull-right">
                    Enregistrer <i class="fa fa-sign-in"></i>
                    </button>            
                </div>
                <div class="create-account">
                    <p>
                        Vous avez déjà un compte ?<br>
                        <a href="login.php" id="lohin-btn">Connectez-vous.</a>
                    </p>
                </div>
            </form>
        </div>
    </body>
</html>
