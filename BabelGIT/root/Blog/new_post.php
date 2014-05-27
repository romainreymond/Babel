<?php 
require('../config.php');
$field_value = "Cliquez ici et selectionnez une image";
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if ($_POST['title'] == 'Titre' || $_POST['title'] == '' || (strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0)) {
        $errors[] = 'Entrez un titre.';
    } 
	if ($_POST['desc'] == 'Ajoutez une description...' || $_POST['desc'] == '' || (strlen($_POST['desc']) > 0 && strlen(trim($_POST['desc'])) == 0)) {
        $desc = 'none';
    }
	else {
		$desc = nl2br($_POST['desc']);
	}
    if ($_POST['url'] == $field_value) {
        $img_url = 'none';
    } else {
        $img_url = $_POST['url'];
    }
    if (empty($_POST['content'])) {
        $errors[] = 'Remplissez le contenu.';
    }
	if (empty($_POST['tags']) || $_POST['tags'] == 'Tags') {
		$errors[] = 'Selectionnez au moins un tag.';
	}
    if (empty($errors)) {
        $date = date('Y-m-d');
        $author_id = $_SESSION['id'];
        $url = $post->slug($_POST['title']);
        if ($post->add_post($_POST['title'], $url, $desc, $_POST['content'], $author_id, $date, $img_url, $_POST['tags'])) {
            header('Location: ' . DIR_BLOG);
        }
    }
}
$_GET['page'] = "blog";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Post <small>Créez une news</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Nouveau Post";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre nouvel article</h3>
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
                        <script type="text/javascript">
                        function openKCFinder(field) {
                            field.value = "<?php echo $field_value; ?>";
                            window.KCFinder = {
                                callBack: function(url) {
                                    field.value = url;
                                    window.KCFinder = null;
                                }
                            };
                            window.open('<?php echo HTTP_INC; ?>kcfinder/browse.php?type=images&langCode=fr', 'kcfinder_textbox',
                                        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
                                        'resizable=1, scrollbars=0, width=800, height=600');
                        }
                        </script>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" name="url" type="text" readonly="readonly" onclick="openKCFinder(this)" value="<?php echo $field_value; ?>" style="cursor:pointer" />
                            </div>
                        </div>
						<div class="form-group">
                            <div class="input-icon">
								<textarea class="form-control" name='desc' style="resize: none; width:100%; height: 100px;" onfocus="if(this.value=='Ajoutez une description...')this.value='';" onblur="if(this.value=='')this.value='Ajoutez une description...';">Ajoutez une description...</textarea>  
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <textarea name="content"><?php if(isset($_POST['content'])){echo $_POST['content'];} ?></textarea>
                            </div>
                        </div>
                        <input id="select" style="width: 90%" name="tags" <?php if(isset($_POST['tags'])){echo 'value="' . $_POST['tags']. '"';}else{echo 'value=""';} ?>>
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
	$("#select").select2({
		width: 'resolve',
		tags:[<?php echo $post->aff_tags(); ?>],
		tokenSeparators: [",", " "]});
    CKEDITOR.replace( 'content');
</script>
<?php include(VIEW_FOOTER); ?>
