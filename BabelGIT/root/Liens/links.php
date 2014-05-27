<?php 
require('../config.php');
if (isset($_GET['del'])) {
	if (isset($_GET['admin']) && $_GET['admin'] == '1' && (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
		$link->delete_admin_link($_GET['del']);
	}
	else {
		$link->delete_link($_GET['del']);
	}
}
$_GET['page'] = "links";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Liens <small>Votre table de liens utiles</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
                            <li class="btn-group">
                                <button type="button" class="btn blue" onclick="location.href='new_link.php'">
                                    <i class="fa fa-plus-square"></i>
                                    <span> Nouveau Lien Perso</span>
                                </button>
                                <?php 
								if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
									echo '<button type="button" class="btn green" onclick="location.href=\'new_link.php?admin=1\'">';
									echo '<i class="fa fa-plus-square"></i>';
									echo '<span> Nouveau Lien Admin</span>';
									echo '</button>';
									echo '</li>';
								}
                                $pagetitle = "Vos Liens";
                                include(INC_BDCB);
                                ?>
                        </ul>
                    </div>
                </div>
                <div class="link-page col-md-12">
                    <div class="row">
						<div class="col-md-12">
							<?php
							$link->aff_all_admin_links();
							$link->aff_all_links($_SESSION['id']);
							?>
						</div>
					</div>
                </div>
            </div>
        </div>
<script language="JavaScript" type="text/javascript">
    function dellink(id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre post ?")) {
            window.location.href = '<?php echo DIR_LINK; ?>links.php?del=' + id ;
        }
    }
	function editlink(id) {
		window.location.href = '<?php echo DIR_LINK; ?>' + id ;
    }
	function deladminlink(id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre post ?")) {
            window.location.href = '<?php echo DIR_LINK; ?>links.php?admin=1&del=' + id ;
        }
    }
	function editadminlink(id) {
		window.location.href = '<?php echo DIR_LINK; ?>admin/' + id ;
    }
</script>
<?php include(VIEW_FOOTER); ?>