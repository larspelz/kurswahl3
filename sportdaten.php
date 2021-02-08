<?php

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="sportkurse.csv"');

include 'dbinterface.inc.php';
DB::connect();

include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

// Schülernummern laden
$snr=DB::get_list('SELECT DISTINCT snr FROM '.$tpref.'waehltsp');

// Ausgabezeilen erzeugen
foreach ($snr as $n) {
	
	$infos=DB::get_assoc('SELECT kuerzel,lstufe FROM '.$tpref."waehltsp WHERE snr='$n'".
		" AND kuerzel NOT IN ('ORC','JAZ','CHR','EX1','EX2','EX3','EX4','EXC')");
	
	$kurse=array();
	foreach ($infos as $data) {
		$kurse[]='S'.$data['lstufe'].$data['kuerzel'].'1';
	}
	
	// Erzeugen der Ausgabezeile
	$arr = array($n,'','','SP','12','','','','','',implode('~',$kurse),'','');
	for($i=0;$i<12;$i++){
		echo "\"".$arr[$i]."\";";
	}
	echo "\"".$arr[12]."\"\n";
}

?>



