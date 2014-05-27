<?php 
require('../config.php');
/*
	On verifie si la variable de pagination est set.
*/
if (!(isset($_GET['pagenum']))) { 
	$pagenum = 1;
}
else {
	$pagenum = intval($_GET['pagenum']);
}

$rows = $post->get_nb_post();
if ($rows == 0) {
	header('Location: ' . VIEW_IND);
}
$page_rows = 5;
$last = ceil($rows/$page_rows);
if ($pagenum < 1) {
	$pagenum = 1;
}
else if ($pagenum > $last) {
	$pagenum = $last;
}
$max = 'LIMIT ' . ($pagenum - 1) * $page_rows .',' .$page_rows;
if (isset($_GET['delpost']) && isset($_GET['user_id'])) {
    if (($_GET['user_id'] == $_SESSION['id']) || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
        $post->delpost($_GET['delpost']);
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
                            <li class="btn-group">
                                <button type="button" class="btn blue" onclick="location.href='new_post.php'">
                                    <i class="fa fa-plus-square"></i>
                                    <span>Nouveau Post</span>
                                </button>
							</li>
							<?php 
							$pagetitle = "News";
							include(INC_BDCB);
							?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 blog-page">
                        <div class="row">
                            <div class="col-md-9">
                                <h1>Les dernières nouvelles</h1>
                                <?php
                                    $arr = $post->get_posts_max($max);
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
                            <div class="col-md-3">
                                <h2>Categories</h2>
								<div class="top-tags">
									<?php $post->aff_categories(); ?>
								</div>
								<h2>Archives</h2>
								<div class="archives-list">
									<?php
									$rows = $post->get_date_arr();
									$by_year = array();
									$year_counts = array();
									foreach ($rows as $row){
										if (!isset($by_year[$row['year']][$row['month']])) {
											$by_year[$row['year']][$row['month']] = 0;
										}
										if (!isset($year_counts[$row['year']])) {
											$year_counts[$row['year']] = 0;
										}
										$by_year[$row['year']][$row['month']]++;
										$year_counts[$row['year']]++;
									}
									setlocale(LC_ALL, 'fr_FR', 'French', 'French_France.1252');
									# most recent year first!
									krsort($by_year);
									echo '<ul class="hierarchy">';
									foreach ($by_year as $year => $months){
										if ($year == date('Y')) {
											echo '<li class="archives-date expanded">';
										}
										else {
											echo '<li class="archives-date collapse">';
										}
										echo '<a class="toggle" onclick="return toggle(this)">';
										echo '<span class="zippy"></span>';
										echo '</a>';
										echo '<a href="' . DIR_BLOG . $year . '/" class="archives-link">' . $year . '</a>';
										echo '<span class="counter"> (' . $year_counts[$year] . ')</span>';
										echo '<ul class="hierarchy">';
										ksort($months);
										foreach ($months as $month => $num) {
											echo '<li class="posts">';
											echo '<a href="' . DIR_BLOG . $year . '/' . $month . '/" class="archives-link">' . ucfirst(utf8_encode(strftime("%B", mktime(0,0,0,$month,1,$year)))) . '</a>';
											echo '<span class="counter"> (' . $num . ')</span>';
											echo '</li>';
										}
										echo '</ul>';
										echo '</li>';
									}
									?>
								</div>
                            </div>
                        </div>
						<ul class="pagination pull-right">
							<?php
							/* Affiche la fleche de retour d'une page en arrière */
							if ($pagenum > 1) {
								echo '<li>';
								echo '<a href="' . DIR_BLOG . ($pagenum - 1) . '"><i class="fa fa-angle-left"></i></a>';
								echo '</li>';
							}
							/* Affiche la première page */
							if (($pagenum - 3) <= 1) {
								$first = 1;
							}
							else {
								echo '<li>';
								echo '<a href="' . DIR_BLOG . '1">1</a>';
								echo '</li>';
								$first = $pagenum - 3;
							}
							/* Affiche 5 pages avec comme centre la page de l'utilisateur */
							if (($pagenum + 3) >= $last) {
								for($i=$first; $i <= $last; $i++) {
									if($pagenum==$i) {
										echo '<li class="act">';
										echo '<a href="#">'.$i.'</a>';
										echo '</li>';
									}
									else {
										echo '<li>';
										echo '<a href="' . DIR_BLOG . $i . '">'.$i.'</a>';
										echo '</li>';
									}
								}
							}
							else {
								for($i=$first; $i <= ($pagenum + 3); $i++) {
									if($pagenum==$i) {
										echo '<li class="act">';
										echo '<a href="#">'.$i.'</a>';
										echo '</li>';
									}
									else {
										echo '<li>';
										echo '<a href="' . DIR_BLOG . $i . '">'.$i.'</a>';
										echo '</li>';
									}
								}
								echo '<li>';
								echo '<a href="' . DIR_BLOG . $last . '">'.$last.'</a>';
								echo '</li>';
							}
							/* Affiche la fleche pour aller une page en avant */
							if ($pagenum < $last) {
								echo '<li>';
								echo '<a href="' . DIR_BLOG . ($pagenum + 1) . '"><i class="fa fa-angle-right"></i></a>';
								echo '</li>';
							}
							?>
						</ul>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
    function toggle( eThis ){
        if( eThis.parentNode.className == 'archives-date collapse' ){
            eThis.parentNode.className = 'archives-date expanded';
        }
		else{
			eThis.parentNode.className = 'archives-date collapse';
        }
        return false;
    }
</script>
<?php include(VIEW_FOOTER); ?>
