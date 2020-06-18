<?php

$dbname='kurswahl';
$dbhost='localhost';
$dbuser='root';
$dbpass='';

$link=mysql_connect($dbhost,$dbuser,$dbpass);
mysql_set_charset('utf8');

mysql_select_db($dbname,$link);

// in case there's no session yet, don't try to find out the table prefix
if (isset($_SESSION['user'])) {
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
}

?>
