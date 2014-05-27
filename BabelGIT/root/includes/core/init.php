<?php 
#starting the users session
if (session_id() == '') {
	session_start();
}
require_once 'connect/database.php';
require_once 'classes/users.php';
require_once 'classes/general.php';
require_once 'classes/bcrypt.php';
require_once 'classes/blog_post.php';
require_once 'classes/forum.php';
require_once 'classes/links.php';
$users = new Users($db);
$general = new General($users, $db);
$bcrypt = new Bcrypt();
$post = new Posts($db, $users, $general);
$forum = new Forum($db, $users);
$link = new Link($db, $users);
$errors = array();
?>
