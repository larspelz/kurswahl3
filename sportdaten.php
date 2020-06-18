<?php

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="sportkurse.csv"');

include 'dbconnect.inc.php';
include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

// Schülernummern laden
$id=mysql_query('SELECT DISTINCT sNummer FROM '.$tpref.'waehltsp');
$snr=array();
while($data = mysql_fetch_assoc($id)) {
	$snr[]=$data['sNummer'];
}

// Ausgabezeilen erzeugen
foreach ($snr as $n) {
	
	$id=mysql_query('SELECT kuerzel,lstufe FROM '.$tpref.'waehltsp WHERE sNummer='.$n.
		" AND kuerzel NOT IN ('ORC','JAZ','CHR','EX1','EX2','EX3','EX4','EXC')");
	
	$kurse=array();
	while ($data=mysql_fetch_assoc($id)) {
		$kurse[]='S'.$data['lstufe'].$data['kuerzel'].'1';
	}

	$kursname='S'.$data[2].$data[1].'1';
	
	// Erzeugen der Ausgabezeile
	$arr = array($n,'','','SP','12','','','','','',implode('~',$kurse),'','');
	for($i=0;$i<12;$i++){
		echo "\"".$arr[$i]."\";";
	}
	echo "\"".$arr[12]."\"\n";
}

?>



