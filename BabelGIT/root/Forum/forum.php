<?php 
require('../config.php');
if (isset($_GET['delblock']) && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
	$forum->delete_block($_GET['delblock']);
}
if (isset($_GET['delsubblock']) && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
	$forum->delete_sub_block($_GET['delsubblock']);
}
if (isset($_GET['delpost']) && isset($_GET['user_id'])) {
    if (($_GET['user_id'] == $_SESSION['id']) || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
        $forum->delete_topic($_GET['delpost']);
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
							if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
								echo '<li class="btn-group">';
								echo '<button type="button" class="btn blue" onclick="location.href=\'Nouvelle_Categorie\'">';
								echo '<i class="fa fa-plus-square"></i>';
								echo '<span>Nouvelle Catégorie</span>';
								echo '</button>';
								echo '</li>';
							}
							$pagetitle = "Forum";
							include(INC_BDCB);
							?>
                        </ul>
                    </div>
                </div>
				<div class="row">
                	<div class="forum-page col-md-12">
						<div class="row">
							<div class="col-md-9">
								<?php $forum->aff_all_blocks(); ?>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
<script language="JavaScript" type="text/javascript">
    function delblock(block_id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre block ?")) {
            window.location.href = '<?php echo DIR_FORUM; ?>forum.php?delblock=' + block_id;
        }
    }
	function editblock(block_id) {
		window.location.href = '<?php echo DIR_FORUM; ?>' + block_id + '/Edit';
	}
    function newsubblock(block_id) {
        window.location.href = '<?php echo DIR_FORUM; ?>' + block_id + '/Nouvelle_Sous_Categorie';
    }
</script>
<?php include(VIEW_FOOTER); ?>