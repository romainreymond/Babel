<?php 
require('../config.php');
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if (($_POST['title'] == 'Titre') || ($_POST['title'] == '') || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un titre.';
    }
    if (empty($_POST['content'])) {
        $errors[] = 'Remplissez le contenu.';
    }
    if (empty($errors)) {
        $date = date('Y-m-d');
        $author_id = $_SESSION['id'];
		$parent_id = $_GET['parent_id'];
        if ($forum->add_topic($parent_id, $author_id, $_POST['title'], $_POST['content'], $date)) {
            header('Location: ' . DIR_FORUM . $parent_id . '/');
        }
    }
}
$_GET['page'] = "forum";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Topic <small>Créez un topic</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Nouveau Topic";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre nouveau topic</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" onfocus="if(this.value=='Titre')this.value='';" onblur="if(this.value=='')this.value='Titre';" <?php if(isset($_POST['title'])){echo 'value="' . $_POST['title']. '"';}else{echo 'value="Titre"';} ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <textarea name="content"><?php if(isset($_POST['content'])){echo $_POST['content'];} ?></textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn blue pull-right">Poster <i class="fa fa-check"></i>
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