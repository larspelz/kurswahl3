<?php

session_start();

include 'dbinterface.inc.php';

DB::connect();

include 'auth.inc.php';

$tpref=gettableprefix();

foreach ($_POST as $key => $val) {
	$res=DB::get_value_or_false('SELECT * FROM '.$tpref.'einstellungen WHERE schluessel=\''.$key.'\'');
	if ($res!=false) {
		DB::query ('UPDATE '.$tpref.'einstellungen SET wert=\''.$val.'\' WHERE schluessel=\''.$key.'\'');
	} else {
		DB::query ('INSERT INTO '.$tpref.'einstellungen (schluessel,wert) VALUES '."('$key','$val')");
	}
}

?>