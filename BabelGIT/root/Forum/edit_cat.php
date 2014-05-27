<?php 
require('../config.php');
$block = $forum->get_block($_GET['block_id']);
if (($_SESSION['admin'] != 1) || ($block['id'] != $_GET['block_id'])) {
	header('Location: ' . DIR_FORUM);
	exit;
}

//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if (($_POST['title'] == '') || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un titre.';
    }
    if (empty($errors)) {
        if ($forum->update_block($block['id'], $_POST['title'])) {
            header('Location: ' . DIR_FORUM);
			exit;
        }
    }
}
$_GET['page'] = "forum";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Catégorie <small>Modifiez une catégorie</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Edit catégorie";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre catégorie</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" value="<?php echo $block['title']; ?>">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn blue pull-right">Modifier <i class="fa fa-check"></i>
                            </button>            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include(VIEW_FOOTER); ?>