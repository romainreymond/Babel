<?php
require_once INC_INIT;
$general->logged_out_protect();
$user = $users->userdata($_SESSION['id']);
$nom = $user['nom'];
$prenom = $user['prenom'];
$general->del_notifs($_SESSION['id']);
?>
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="fr"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="fr"> <!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <meta charset="utf-8">
    <title>Babel 2</title>
    <link rel="icon" type="image/png" href="<?php print DIR_IMG; ?>favicon.png" />
    <link rel="stylesheet" type="text/css" href="<?php print DIR_CSS; ?>font-awesome-4.0.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php print DIR_CSS; ?>style.css">
    <link rel="stylesheet" type="text/css" href="<?php print DIR_JS; ?>select2/select2.css">
    <script type='text/javascript' src='<?php print DIR_JS; ?>jquery-git.js'></script>
    <script type='text/javascript' src='<?php print DIR_JS; ?>jquery.cookie.js'></script>
    <script type='text/javascript' src='<?php print DIR_JS; ?>succinct.min.js'></script>
    <script type="text/javascript" src="<?php print DIR_JS; ?>app.js"></script>
    <script type="text/javascript" src="<?php print DIR_JS; ?>select2/select2.js"></script>
	<script type="text/javascript">
		function toggle_notif( eThis ){
			if( eThis.parentNode.className == 'dd-div' ){
				eThis.parentNode.className = 'dd-div open';
			}
			else{
				eThis.parentNode.className = 'dd-div';
			}
			return false;
		}
		$(document).ready(function(){
			$(".page-container").click(function(){
				if ($('.dd-div').hasClass('open')) {
					$(".dd-div").removeClass('open');
				}
			});
		});
	</script>
</head>
<body class="page-header-fixed">
    <div class="header navbar-inverse navbar-fixed-top">
        <a class="navbar-brand" href="<?php print VIEW_IND; ?>">
            <img src="<?php print DIR_IMG; ?>logo.png" alt="logo" class="img-responsive" />
        </a>
		<div class="pull-right">
			<?php
				$notifs = $general->get_notifs($_SESSION['id']);
				echo '<div class="dd-div">';
				echo '<a href="#" class="dd-toggle" onclick="return toggle_notif(this)">';
				echo '<i class="fa fa-tasks"></i>';
				$nb = $general->get_nb_new_notifs($_SESSION['id']);
				if ($nb > 0) {
					echo '<span class="badge">' . $nb . '</span>';
				}
				echo '</a>';
				echo '<ul class="dropdown">';
				if (count($notifs) == 0) {
					echo '<li><a style="cursor: default;">Vous n\'avez aucune notifications</a></li>';
				}
				else {
					$i = 0;
					foreach ($notifs as $notif) {
						if ($i == 6) {
							break;
						}
						$blog_post = $post->get_data($notif['ref_id']);
						if ($notif['seen'] == '0') {
							echo '<li class="new">';
						}
						else {
							echo '<li>';
						}
						if ($notif['type'] == 'COM') {
							echo '<a href="' .  DIR_BLOG . str_replace("-", "/", $blog_post['date_posted']) . '/' . $blog_post['url'] . '#comments"><span class="span-icon"><i class="fa fa-bell-o"></i></span>Vous avez un nouveau commentaire</a>';
							$i++;
						}
						echo '</li>';
					}
				}
				echo '<li class="last"><a>Toutes les notifications<i class="fa fa-arrow-circle-o-right pull-right"></i></a></li>';
				echo '</ul>';
				echo '</div>';
			?>
			<div class="dd-div">
				<span class="username"><?php echo $prenom, ' ', $nom; ?></span>
			</div>
		</div>
    </div>
    <div class="page-container">
        <?php include(VIEW_SIDEBAR); ?>