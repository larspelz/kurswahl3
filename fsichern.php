<?php

session_start();

include 'dbconnect.inc.php';

include 'auth.inc.php';

// erstmal alles löschen, falls vorhanden
mysql_query ("DELETE FROM fach");

$cnt=$_POST['count'];

for ($i=0;$i<cnt;$i++) {

	mysql_query("insert into fach (kurz,lang,fachgr,ord,kannLK1,kannLK2,kannPF3,kannPF4,kann5PK,kannGK,semwaehlbar) values".
		"('".$_POST['K'.$i]."','".$_POST['L'.$i]."','".$_POST['G'.$i]."','".$_POST['O'.$i]."',".
		"'".$_POST['A'.$i]."','".$_POST['B'.$i]."','".$_POST['C'.$i]."','".$_POST['D'.$i]."',".
		"'".$_POST['E'.$i]."','".$_POST['F'.$i]."','".$_POST['w'.$i]."')");

}

?>