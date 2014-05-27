<?php 
require('../config.php');
if (isset($_GET['del'])) {
	$forum->delete_com($_GET['del']);
}
$topic = $forum->get_topic($_GET['topic_id']);
if (!isset($topic['id']) || ($topic['parent_id'] != $_GET['parent_id'])) {
	header('Location: ' . DIR_FORUM);
	exit;
}
//Si la form du commentaire est valider
if (isset($_POST['submit'])){
    //verification basic
    if (empty($_POST['content'])) {
        $errors[] = 'Remplissez le contenu.';
    }
    if (empty($errors)) {
        if ($forum->add_comment($_POST['content'], $_SESSION['id'], $topic['id'])) {
            header('Location: ' . DIR_FORUM . $topic['parent_id'] . '/' . $topic['id'] . '#comments');
        }
    }
}
$_GET['page'] = "forum";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Forum <small>Le forum SI</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
							<?php 
							if ($topic['author_id'] == $_SESSION['id'] || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
								echo '<li class="btn-group">';
								echo '<button type="button" class="btn yellow" onclick="javascript:edittopic(\'' . $topic['parent_id'] . '\', \'' . $topic['id'] . '\')">';
								echo '<span>Modifier</span>';
								echo '</button>';
								echo '<button type="button" class="btn red" onclick="javascript:deltopic(\'' . $topic['id'] . '\')">';
								echo '<span>Supprimer</span>';
								echo '</button>';
								echo '</li>';
							}
							$pagetitle = $topic['title'];
							include(INC_BDCB);
							?>
                        </ul>
                    </div>
                </div>
				<div class="row">
                	<div class="forum-page col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="forum-block blue">
									<div class="block-title">
										<div class="title">
											<?php echo $topic['title']; ?>
										</div>
									</div>
									<?php $forum->aff_topic($topic); ?>
								</div>
								<?php $forum->aff_comments($topic['id']); ?>
								<form class="forum-block blue" action="" method="post">
									<div class="block-title">
										<div class="title">
											Commentaires
										</div>
										<button type="submit" name="submit" class="btn blue pull-right">
											Poster <i class="fa fa-comment"></i>
										</button>
									</div>
									<textarea name='content' style="background-color: #ffffff; background-image: none; resize: none; width:100%; height: 100%;"></textarea>
								</form>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
<script type="text/javascript" src="<?php print DIR_JS; ?>ckeditor/ckeditor.js"></script>
<script language="JavaScript" type="text/javascript">
	CKEDITOR.replace( 'content', {
		uiColor: '#ffffff'
    });
    function deltopic(id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre topic ?")) {
            window.location.href = '<?php echo DIR_FORUM; ?>forum.php?delpost=' + id + '&user_id=<?php echo $_SESSION['id']; ?>';
        }
    }
	function delcom(com_id) {
		if (confirm("Etes-vous sûr de vouloir supprimer votre commentaire ?")) {
			window.location.href = '<?php echo DIR_FORUM . $_GET['parent_id'] . '/' . $_GET['topic_id']; ?>/' + com_id + '#comments';
		}
	}
	function edittopic(parent_id, id) {
        window.location.href = '<?php echo DIR_FORUM; ?>' + parent_id + '/' + id + '/Edit';
    }
</script>
<?php include(VIEW_FOOTER); ?>