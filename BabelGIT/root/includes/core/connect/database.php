<?php 
$setting = new DomDocument(); //intanciation de la class DomDocument
$setting->load (DIR_BASE.'settings.xml');//chargement du document setting.xml

//recupération de la valeur de host
$host= $setting->getElementsByTagName('host');
//recupération de la valeur de port
$port= $setting->getElementsByTagName('port');
//recupération de la valeur de username
$user= $setting->getElementsByTagName('username');
//recupération de la valeur de password
$pwd= $setting->getElementsByTagName('password');
//recupération de la valeur de dbname
$dbname= $setting->getElementsByTagName('dbname');

# We are storing the information in this config array that will be required to connect to the database.
$config = array(
	'host'		=> $host->item(0)->nodeValue,
	'port'		=> $port->item(0)->nodeValue,
	'username'	=> $user->item(0)->nodeValue,
	'password'	=> $pwd->item(0)->nodeValue,
	'dbname' 	=> $dbname->item(0)->nodeValue
);




#connecting to the database by supplying required parameters
try{
	$db = new PDO('mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}
#Setting the error mode of our db object, which is very important for debugging.
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
