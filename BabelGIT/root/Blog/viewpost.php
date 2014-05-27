<?php 
require('../config.php');
$row = $post->postdata($_GET['url']);
if (!isset($row['id'])) {
	header('Location: ' . DIR_BLOG);
	exit;
}
if (isset($_GET['del'])) {
	$post->del_com($_GET['del'], $row['id']);
}
$general->update_notifs($row['id'], $_SESSION['id']);
//Si la form du commentaire est valider
if (isset($_POST['submit'])){
    //verification basic
    if (empty($_POST['content']) || $_POST['content'] == 'Entrez un commentaire...' || (strlen($_POST['content']) > 0 && strlen(trim($_POST['content'])) == 0)) {
        $errors[] = 'Remplissez le contenu.';
    }
    if (empty($errors)) {
		$content = nl2br($_POST['content']);
        if ($post->add_comment($content, $_SESSION['id'], $row['id'])) {
			if ($row['author_id'] != $_SESSION['id']) {
				$general->add_notif($row['author_id'], $_SESSION['id'], $row['id'], "COM");
			}
			header('Location: ' . DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $row['url'] . '#comments');
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
                        <h3 class="page-title">Blog <small>Toutes les news SI</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
								<?php
								if (($row['author_id'] == $_SESSION['id']) || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
									echo '<li class="btn-group">';
									echo '<button type="button" class="btn yellow" onclick="javascript:editpost(\'' . $row['id'] . '\')">';
									echo '<span>Modifier</span>';
									echo '</button>';
									echo '<button type="button" class="btn red" onclick="javascript:delpost(\'' . $row['id'] . '\')">';
									echo '<span>Supprimer</span>';
									echo '</button>';
									echo '</li>';
									
								}
                                $pagetitle = (strlen($row['title']) > 50) ? mb_substr($row['title'],0,50, "utf-8").'...' : $row['title'];
                                include(INC_BDCB);
                                ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 blog-page">
                        <div class="row">
                            <div class="col-md-9">
                                <?php
									$usrdata = $users->userdata($row['author_id']);
									$post_url = DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $row['url'];
                                    echo '<h1>' . $row['title'] . '</h1>';
                                    echo '<div class="blog-tag-data">';
                                    if ($row['img_url'] != 'none') {
										echo '<div class="frame2">';
                                        echo '<img src="' . $row['img_url'] . '"/>';
										echo '</div>';
                                    }
									echo '<ul class="list-inline">';
        							echo '<li>';
									echo '<i class="fa fa-user"></i><a href="#"> ' . $usrdata['prenom'] . ' ' .$usrdata['nom'] . '</a>';
									echo '</li>';
									echo '<li>';
									echo '<i class="fa fa-calendar"></i>';
									echo '<a href="'. DIR_BLOG . str_replace("-", "/", $row['date_posted']) .'/"> ' . $post->echo_date($row['date_posted']) . '</a>';
									echo '</li>';
									echo '<li>';
									echo '<i class="fa fa-comments"></i>';
									echo '<a href="' . $post_url . '#comments"> ' . $post->nb_comments($row['id']) . ' Commentaires</a>';
									echo '</li>';
									echo '</li>';
									echo '<ul class="list-inline blog-tags">';
									echo '<li>';
									echo '<i class="fa fa-tags"></i> ';
									$post->get_tags($row['id']);
									echo '</li>';
									echo '</ul>';
									echo '</div>';
                                    echo '<div class="blog-content">' . $row['content'] . '</div><hr>';
                                    echo '<div><h3 id="comments">Commentaires</h3></div>';
									$arr = $post->get_comments($row['id']);
									$i = 0;
									if (!empty($arr)) {
										foreach ($arr as $row2) {
											echo '<div style="margin-left: 2%; margin-bottom: 10px;">';
											if ($i != 0) {
												echo '<hr>';
											}
											$i++;
											$usrdata2 = $users->userdata($row2['user_id']);
											echo '<div class="blog-tag-data">';
											echo '<ul class="list-inline">';
											echo '<li>';
											echo '<i class="fa fa-user"></i><a href="#comments"> ' . $usrdata2['nom'] . ' ' .$usrdata2['prenom'] . '</a>';
											echo '</li>';
											echo '<li>';
											echo '<i class="fa fa-calendar"></i>';
											echo '<a href="#comments"> ' . $post->echo_date($row2['comment_date']) . '</a>';
											echo '</li>';
											echo '</ul>';
											if (($row2['user_id'] == $_SESSION['id']) || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
												echo '<i class="del pull-right fa fa-times-circle" onclick="javascript:delcom(\'' . $row2['id']. '\')"></i>';
											}
											echo '</div><p>';
											echo $row2['content'];
											echo '</p></div>';
										}
									}
									else {
										echo '<p>Soyez le premier à commenter ce post !</p>';
									}
                                ?>
								<hr>
								<h3>Laisser un commentaire</h3>
								<form action="#comments" method="post">
									<div style="border-left: 2px solid #35aa47; margin-left: 5%; width:90%; height: 120px;">
									<textarea name='content' style="background-color: #ffffff; border: 1px solid #e5e5e5; background-image: none; resize: none; width:100%; height: 100%;" onfocus="if(this.value=='Entrez un commentaire...')this.value='';" onblur="if(this.value=='')this.value='Entrez un commentaire...';">Entrez un commentaire...</textarea>
									</div>
									<button type="submit" name="submit" class="btn blue pull-right" style="margin-right: 5%">
										Poster <i class="fa fa-comment"></i>
									</button>
								</form>
                            </div>
                            <div class="col-md-3">
                                <h1>Top news</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script language="JavaScript" type="text/javascript">
    function delpost(id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre post ?")) {
            window.location.href = '<?php echo DIR_BLOG; ?>blog_page.php?delpost=' + id + '&user_id=<?php echo $_SESSION['id']?>';
        }
    }
	function delcom(com_id) {
		if (confirm("Etes-vous sûr de vouloir supprimer votre commentaire ?")) {
			window.location.href = '<?php echo $post_url; ?>/' + com_id + '#comments';
		}
	}
    function editpost(id) {
        window.location.href = '<?php echo DIR_BLOG; ?>Edit/' + id;
    }
</script>
<?php include(VIEW_FOOTER); ?>
