<?php

	include 'dbconnect.inc.php';
	
	//include 'auth.inc.php';
	
	//header('Content-type: text/plain');
	//header('Content-Disposition: attachment; filename="lf2oxs.sql"');
	
	$data=array();
	$snrs=array();
	$pf={1,2,7};
	
	foreach ($pf as $p) {
		res=mysql_query("SELECT snr,fachkurz FROM waehltpf WHERE pf=$p");
		$entry=mysql_fetch_assoc($res);
		$snr=$entry['snr'];
		if (!in_array($snr,$snrs)) $snrs[]=$snr;
		if (!isset ($data[snr])) $data[$snr]=array();
		$data[$snr][$p]=$entry['fachkurz'];
	}
	
	foreach ($snrs as $snr) {
		$entry=$data[$snr];
		echo "UPDATE laufb1213_2 SET LF1='$entry[1]',LF2='$entry[2]',LF3='$entry[7]' WHERE SNummer=$snr;\n";
	}
	
?>