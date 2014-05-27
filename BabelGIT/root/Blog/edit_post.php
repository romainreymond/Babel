<?php 
require('../config.php');
$row = $post->get_data($_GET['post_id']);
if (($row['author_id'] != $_SESSION['id']) || ($_SESSION['admin'] == 0)) {
	if(!isset($row['id']) || ($_GET['post_id'] != $row['id'])) {
		header('Location: ' . DIR_BLOG);
		exit;
	}
}
if ($row['img_url'] == 'none') {
    $field_value = "Cliquez ici et selectionnez une image";
} else {
    $field_value = $row['img_url'];
}
//Si la form est valider
if (isset($_POST['submit'])){
    //verification basic
    if ($_POST['title'] == 'Titre' || $_POST['title'] == '') {
        $errors[] = 'Entrez un titre.';
    }
	if ($_POST['desc'] == 'Ajoutez une description...' || $_POST['desc'] == '' || (strlen($_POST['desc']) > 0 && strlen(trim($_POST['desc'])) == 0)) {
        $desc = 'none';
    }
	else {
		$desc = nl2br($_POST['desc']);
	}
    if ($_POST['url'] == $row['img_url']) {
        $img_url = $row['img_url'];
    }
    else if ($_POST['url'] == $field_value) {
        $img_url = 'none';
    }
    else {
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
        $url = $post->slug($_POST['title']);
		$post->update_tags($_POST['tags'], $row['id']);
		$final_url = $post->update_post($row['id'], $_POST['title'], $url, $desc, $_POST['content'], $date, $img_url);
        if (isset($final_url)) {
            header('Location: ' . DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $final_url);
			exit;
        }
    }
}
$_GET['page'] = "blog";
include(VIEW_HEADER);
?>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title">Edit <small>Modifiez votre news</small></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <?php 
                        $pagetitle = "Modification de post";
                        include(INC_BDCB);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="new_post" action="" method="post">
                        <h3>Votre article</h3>
                        <?php
                        if(empty($errors) === false) {
                            echo '<div style="color: #f00;margin-bottom: 15px">' . implode('</p><p>', $errors) . '</div>';
                        }
                        ?>
                        <div class="form-group">
                            <div class="input-icon">
                                <input class="form-control" autocomplete="off" type="text" name="title" value="<?php echo $row['title']; ?>">
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
                                <input class="form-control" name="url" type="text" readonly="readonly" onclick="openKCFinder(this)"
                                   value="<?php echo $field_value; ?>" style="cursor:pointer" />
                            </div>
                        </div>
						<div class="form-group">
                            <div class="input-icon">
								<textarea class="form-control" name='desc' style="resize: none; width:100%; height: 100px;"><?php if ($row['desc'] != 'none') { echo $row['desc']; } ?></textarea>  
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon">
                                <textarea class="content" name="content"><?php echo $row['content']; ?></textarea>
                            </div>
                        </div>
                        <input id="select" style="width: 80%" name="tags">
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn blue pull-right">Enregistrer <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="<?php print DIR_JS; ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php print DIR_JS; ?>ckeditor/adapters/jquery.js"></script>
<script>
	$("#select").select2({
		width: 'resolve',
		tags:[<?php echo $post->aff_tags(); ?>],
		tokenSeparators: [",", " "]});
	<?php
	echo '	$("#select").select2("val",' . $post->aff_post_tags($row['id']) . ');';
	?>
	$('textarea.content').ckeditor();
</script>
<?php include(VIEW_FOOTER); ?>
