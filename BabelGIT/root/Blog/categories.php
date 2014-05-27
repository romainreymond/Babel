<?php 
require('../config.php');
$_GET['page'] = "blog";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Catégories <small><?php echo $_GET['cat']; ?></small></h3>
                        <ul class="page-breadcrumb breadcrumb">
                            <li class="btn-group">
                                <button type="button" class="btn blue" onclick="location.href='new_post.php'">
                                    <i class="fa fa-plus-square"></i>
                                    <span>Nouveau Post</span>
                                </button>
							</li>
							<?php 
							$pagetitle = $_GET['cat'];
							include(INC_BDCB);
							?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 blog-page">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
									echo '<h1>'. $_GET['cat'] .'</h1>';
                                    $arr = $post->get_posts_cat($_GET['cat']);
                                    if (isset($arr)) {
                                        foreach ($arr as $row) {
                                            if ($row['img_url'] != 'none') {
                                                $post->aff_post_img($row);
                                            }
                                            else {
                                                $post->aff_post_noimg($row);
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php include(VIEW_FOOTER); ?>
