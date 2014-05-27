<?php 
require('../config.php');
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if (($_POST['title'] == 'Nom du lien') || ($_POST['title'] == '') || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un nom de lien.';
    }
    if (($_POST['link'] == 'Lien') || ($_POST['link'] == '') || (strlen($_POST['link']) > 0 && strlen(trim($_POST['link'])) == 0)) {
        $errors[] = 'Entrez un lien.';
    }
	if (($_POST['usage'] == 'Usage')) {
		$usage = 'none';
    }
	else {
		$usage = $_POST['usage'];
	}
    if (empty($errors)) {
		if (isset($_GET['admin']) && $_GET['admin'] == '1' && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
			if ($link->add_admin_link($_POST['title'], $_POST['link'], $usage)) {
				header('Location: ' . DIR_LINK);
				exit;
			}
		}
        if ($link->add_link($_SESSION['id'], $_POST['title'], $_POST['link'], $usage)) {
            header('Location: ' . DIR_LINK);
			exit;
        }
    }
}
$_GET['page'] = "links";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Liens <small>Créez un lien</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Nouveau Lien";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
			<div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre nouveau lien</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" onfocus="if(this.value=='Nom du lien')this.value='';" onblur="if(this.value=='')this.value='Nom du lien';" <?php if(isset($_POST['title'])){echo 'value="' . $_POST['title']. '"';}else{echo 'value="Nom du lien"';} ?>>
                            </div>
                        </div>
						<div class="form-group">
							<div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="link" onfocus="if(this.value=='Lien')this.value='';" onblur="if(this.value=='')this.value='Lien';" <?php if(isset($_POST['link'])){echo 'value="' . $_POST['link']. '"';}else{echo 'value="Lien"';} ?>>
							</div>
                        </div>
						<div class="form-group">
							<div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="usage" onfocus="if(this.value=='Usage')this.value='';" onblur="if(this.value=='')this.value='Usage';" <?php if(isset($_POST['usage'])){echo 'value="' . $_POST['usage']. '"';}else{echo 'value="Usage"';} ?>>
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