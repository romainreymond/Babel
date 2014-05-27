<?php 

require('./config.php');
if (isset($_GET['admin']) && isset($_SESSION['admin'])) {
	if ($_GET['admin'] == '1' && $_SESSION['admin'] == 1) {
		$_SESSION['admin_connect'] = 1;
		if (isset($_GET['url'])) {
			header('Location: ' . $_GET['url']);
			exit();
		}
	}
	else if ($_GET['admin'] == '0' && $_SESSION['admin'] == 1) {
		$_SESSION['admin_connect'] = 0;
		if (isset($_GET['url'])) {
			header('Location: ' . $_GET['url']);
			exit();
		}

	}
}
function getFeed($feed_url) {
	
	$content = file_get_contents($feed_url);
	$x = new SimpleXmlElement($content);
	
	echo "<ul>";
	foreach($x->channel->item as $entry) {
		echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
	}
	echo "</ul>";
}
$_GET['page'] = "home";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Babel 2 <small>Vos news</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <a href="index.php">Accueil</a> 
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 blog-page">
						<h1>Posts Recents</h1>
                        <div class="row">
							<?php $post->aff_new_post(); ?>
                        </div>
                    </div>
<!--
					<div class="col-md-4">
						<?php //getFeed("https://news.google.com/news/feeds?cf=all&ned=fr&hl=fr&output=rss"); ?>
					</div>
-->
                </div>
            </div>
        </div>
<?php include(VIEW_FOOTER); ?>