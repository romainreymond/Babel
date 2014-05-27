<?php
$setting = new DomDocument(); //intanciation de la class DomDocument
$setting->load ($_SERVER["DOCUMENT_ROOT"].'/settings.xml');//chargement du document setting.xml





/* Absolute path */

define('DIR_BASE',      $_SERVER["DOCUMENT_ROOT"].'/');
define('HTTP_BASE',     '/');

/* inc */

define('DIR_INC',       DIR_BASE . 'includes/');

define('VIEW_HEADER',   DIR_INC . 'header.php');
define('VIEW_SIDEBAR',  DIR_INC . 'sidebar.php');
define('VIEW_FOOTER',   DIR_INC . 'footer.php');
define('INC_BDCB',   	DIR_INC . 'breadcrumbs.php');
define('DIR_KCF',       DIR_INC . 'kcfinder/');
define('DIR_CORE',      DIR_INC . 'core/');

define('INC_INIT',      DIR_CORE . 'init.php');

/* Resources */
define('HTTP_INC',      HTTP_BASE . 'includes/');

define('DIR_JS',        HTTP_INC . 'js/');
define('DIR_IMG',       HTTP_INC . 'img/');
define('DIR_FONT',      HTTP_INC . 'font/');
define('DIR_CSS',       HTTP_INC . 'css/');

/* pages */
define('VIEW_IND',      HTTP_BASE . 'index.php');
define('DIR_BLOG',      HTTP_BASE . 'Blog/');
define('DIR_LINK',      HTTP_BASE . 'Liens/');
define('DIR_SET',       HTTP_BASE . 'Parametres/');
define('DIR_FORUM',     HTTP_BASE . 'Forum/');



require_once INC_INIT;
?>
