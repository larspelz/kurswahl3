<?php

include 'dbconnect.inc.php';

$data=array();
$res=mysql_query("SELECT snr,realpw FROM schueler");
while ($row=mysql_fetch_row($res)) {
	$data[]=$row;
}
for ($i=0;$i<count($data);$i++) {
	$q="UPDATE schueler SET pw='".sha1($data[$i][1])."' WHERE snr=".$data[$i][0];
	mysql_query($q);
}

?>