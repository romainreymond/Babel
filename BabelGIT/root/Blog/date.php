<?php 
header('Content-Type: text/html; charset=utf-8');
require('../config.php');
$_GET['page'] = "blog";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">News <small>Par date</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
                            <li class="btn-group">
                                <button type="button" class="btn blue" onclick="location.href='new_post.php'">
                                    <i class="fa fa-plus-square"></i>
                                    <span>Nouveau Post</span>
                                </button>
							</li>
							<?php 
							$pagetitle = 'Date';
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
									if (isset($_GET['y']) && isset($_GET['m']) && isset($_GET['d'])) {
										$date = $_GET['y'] . '-' . $_GET['m'] . '-' . $_GET['d'];
										echo '<h1>'. $_GET['d'].'/'.$_GET['m'].'/'.$_GET['y'].'</h1>';
										$arr = $post->get_posts_date($date);
									}
									else if (isset($_GET['y']) && isset($_GET['m'])) {
										setlocale(LC_ALL, 'fr_FR', 'French', 'French_France.1252');
										echo '<h1>' . ucfirst(utf8_encode(strftime("%B", mktime(0,0,0,$_GET['m'],1,$_GET['y'])))) . '</h1>';
										$arr = $post->get_posts_by_month($_GET['y'], $_GET['m']);
									}
									else if (isset($_GET['y'])) {
										echo '<h1>' . $_GET['y'] . '</h1>';
										$arr = $post->get_posts_by_year($_GET['y']);
									}
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
