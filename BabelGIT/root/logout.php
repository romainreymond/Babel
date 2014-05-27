<?php
require('./config.php');
if (isset($_GET['deluser'])) {
    if ($_GET['deluser'] == $_SESSION['id']) {
        $users->delete_user($_SESSION['id']);
    }
}
setcookie('auth', null, -1, '/'); 
session_destroy();
header('Location: login.php');
?>
