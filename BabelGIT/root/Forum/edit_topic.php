<?php 
require('../config.php');
$topic = $forum->get_topic($_GET['topic_id']);
if (($topic['author_id'] != $_SESSION['id']) || ($_SESSION['admin'] == 0)) {
	if(!isset($topic['id']) || ($_GET['topic_id'] != $topic['id']) || ($_GET['parent_id'] != $topic['parent_id'])) {
		header('Location: ' . DIR_FORUM);
		exit;
	}
}
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if ($_POST['title'] == 'Titre' || $_POST['title'] == '') {
        $errors[] = 'Entrez un titre.';
    }
    if (empty($_POST['content'])) {
        $errors[] = 'Remplissez le contenu.';
    }
    if (empty($errors)) {
        $date = date('Y-m-d');
        if ($forum->update_topic($topic['id'], $_POST['title'], $_POST['content'], $date)) {
            header('Location: ' . DIR_FORUM . $topic['parent_id'] . '/' . $topic['id']);
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
                    <h3 class="page-title">Topic <small>Modifiez un topic</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Modification de topic";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre topic</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" value="<?php echo $topic['title']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <textarea name="content"><?php echo $topic['content']; ?></textarea>
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
<script type="text/javascript" src="<?php print DIR_JS; ?>ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'content');
</script>
<?php include(VIEW_FOOTER); ?>