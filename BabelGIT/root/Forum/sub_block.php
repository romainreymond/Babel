<?php 
require('../config.php');
$sub_block = $forum->get_sub_block($_GET['block_id']);
if (!isset($sub_block['id'])) {
	header('Location: ' . DIR_FORUM);
	exit;
}
$_GET['page'] = "forum";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Forum <small>Le forum SI</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
							<li class="btn-group">
                                <button type="button" class="btn blue" onclick="location.href='Nouveau_Topic'">
                                    <i class="fa fa-plus-square"></i>
                                    <span>Nouveau Topic</span>
                                </button>
							</li>
                                <?php 
                                $pagetitle = $sub_block['title'];
                                include(INC_BDCB);
                                ?>
                        </ul>
                    </div>
                </div>
				<div class="row">
                	<div class="forum-page col-md-12">
						<div class="row">
							<div class="col-md-9">
								<div class="forum-block blue">
									<div class="block-title">
										<div class="title">
											<?php echo $sub_block['title']; ?>
										</div>
										<?php
										if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
											echo '<i class="pull-right fa fa-times-circle" style="color: black; cursor: pointer;" onclick="javascript:delsubblock(\'' . $sub_block['id']. '\')"></i>';
											echo '<i class="pull-right fa fa-pencil-square" style="color: black; cursor: pointer;" onclick="javascript:editsubblock(\'' . $sub_block['id']. '\')"></i>';
										}
										?>
									</div>
									<?php $forum->aff_topics($_GET['block_id']); ?>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
<script language="JavaScript" type="text/javascript">
    function delsubblock(sub_block_id) {
        if (confirm("Etes-vous sûr de vouloir supprimer votre sous-block ?")) {
            window.location.href = '<?php echo DIR_FORUM; ?>forum.php?delsubblock=' + sub_block_id;
        }
    }
	function editsubblock(sub_block_id) {
		window.location.href = '<?php echo DIR_FORUM . $_GET['block_id']; ?>/' + sub_block_id + '/Edit_Sous_Block';
	}
</script>
<?php include(VIEW_FOOTER); ?>