<?php 
require('../config.php');
if (isset($_GET['admin']) && $_GET['admin'] == '1' && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
	$links = $link->get_admin_link($_GET['link_id']);
}
else {
	$links = $link->get_link($_GET['link_id']);
}
if(!isset($links['id']) || ($_GET['link_id'] != $links['id'])) {
	header('Location: ' . DIR_LINK);
	exit;
}
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if (($_POST['title'] == '') || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un nom de lien.';
    }
    if (($_POST['link'] == '') || (strlen($_POST['link']) > 0 && strlen(trim($_POST['link'])) == 0)) {
        $errors[] = 'Entrez un lien.';
    }
	if (($_POST['usage'] == '') || (strlen($_POST['link']) > 0 && strlen(trim($_POST['link'])) == 0)) {
		$usage = 'none';
    }
	else {
		$usage = $_POST['usage'];
	}
    if (empty($errors)) {
		if (isset($_GET['admin']) && $_GET['admin'] == '1' && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
			if ($link->update_admin_link($links['id'], $_POST['title'], $_POST['link'], $usage)) {
				header('Location: ' . DIR_LINK);
				exit;
			}
		}
        if ($link->update_link($links['id'], $_POST['title'], $_POST['link'], $usage)) {
            header('Location: ' . DIR_LINK);
        }
    }
}
$_GET['page'] = "links";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Liens <small>Modifiez un lien</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Modification de lien";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
			<div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Modifiez votre lien</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" value="<?php echo $links['title']; ?>">
                            </div>
                        </div>
						<div class="form-group">
							<div class="input-icon">
								<input class="form-control" autocomplete="off" type="text" name="link" value="<?php echo $links['url']; ?>">
							</div>
                        </div>
						<div class="form-group">
							<div class="input-icon">
								<input class="form-control" autocomplete="off" type="text" name="usage" value="<?php if ($links['usage'] != 'none') {echo $links['usage'];} ?>">
							</div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn blue pull-right">Ajouter <i class="fa fa-check"></i>
                            </button>            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include(VIEW_FOOTER); ?>