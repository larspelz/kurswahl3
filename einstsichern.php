<?php

session_start();

include 'dbconnect.inc.php';

include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

foreach ($_POST as $key => $val) {
	$res=mysql_query('SELECT * FROM '.$tpref.'einstellungen WHERE schluessel=\''.$key.'\'');
	if (mysql_num_rows($res)!=0) {
		mysql_query ('UPDATE '.$tpref.'einstellungen SET wert=\''.$val.'\' WHERE schluessel=\''.$key.'\'');
	} else {
		mysql_query ('INSERT INTO '.$tpref.'einstellungen (schluessel,wert) VALUES '."('$key','$val')");
	}
}

?>