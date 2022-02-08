<?php

	include 'dbinterface.inc.php';
	DB::connect();
	
	include 'auth.inc.php';
	
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="semdiff.csv"');
	header('Content-Encoding: UTF8');
	
	if (isset($_GET['sem'])) $semno=$_GET['sem']; else $semno='12';
	
	$sql='SELECT s.snr,s.oxs,s.name,s.vorname,t.fachkurz FROM ('.
		'SELECT snr,fachkurz,COUNT(fachkurz) AS cnt FROM '.$tpref.'waehlt '.
		'WHERE sem IN ('.$semno[0].','.$semno[1].') GROUP BY snr,fachkurz) AS t, '.$tpref.'schueler s WHERE cnt<2 and s.snr=t.snr ORDER BY snr';
	
	$persons=DB::get_assoc($sql);
	
	foreach ($persons as $p) {
		$snr=$p['snr'];
        $oxs=$p['oxs'];
		$fkurz=$p['fachkurz'];
		$name=$p['name'];
		$vorname=$p['vorname'];
		$data=DB::get_assoc_row("SELECT sem FROM ".$tpref."waehlt WHERE sem IN (".$semno[0].",".$semno[1].") AND snr='$snr' AND fachkurz='$fkurz' ORDER BY sem");
		echo $oxs.','.$name.','.$vorname.',';
		if ($data['sem']==$semno[0]) echo $fkurz.',_'; else echo '_,'.$fkurz;
		echo "\n";
	}
	
?>