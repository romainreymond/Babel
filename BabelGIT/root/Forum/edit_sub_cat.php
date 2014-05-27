<?php 
require('../config.php');
$sub_block = $forum->get_sub_block($_GET['block_id']);
if (($_SESSION['admin'] != 1) || ($sub_block['id'] != $_GET['sub_block_id'])) {
	header('Location: ' . DIR_FORUM . $_GET['block_id'] . '/');
	exit;
}

//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if (($_POST['title'] == '') || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un titre.';
    }
    if (empty($errors)) {
        if ($forum->update_sub_block($sub_block['id'], $_POST['title'])) {
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
                    <h3 class="page-title">Sous-catégorie <small>Modifiez une sous-catégorie</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Modification sous-catégorie";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre sous-catégorie</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" value="<?php echo $sub_block['title']; ?>">
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