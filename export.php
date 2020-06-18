<?php

	include 'dbconnect.inc.php';
	
	include 'auth.inc.php';
	
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="kurswahl.csv"');
	
	// Grundkurse exportieren
	
	if (isset($_GET['semno'])) $semno=$_GET['semno']; else $semno=1;
	
	$res=mysql_query('SELECT snr,fachkurz FROM '.$tpref.'waehlt WHERE sem='.$semno." AND (NOT fachkurz='SP')");
	
	// Jahrgang setzen
	if ($semno<3) $jg=12; else $jg=13;
	
	while ($data=mysql_fetch_assoc($res)) {
	
		// Bei Seminarkursen muss kein G vorangestellt werden
		$kursname=$data['fachkurz'];
		$kursname='G'.$kursname;
		
		$arr = array($data['snr'],'',$kursname.'1',$data['fachkurz'],$jg,'',$data['snr'],'','','','','','');
		for($i=0;$i<12;$i++){
			echo "\"".$arr[$i]."\";";
		}
		echo "\"".$arr[12]."\"\n";
	}
	// Prüfungsfächer exportieren, werden immer für 4 Semester gewählt und sind nicht in
	// waehlt-Tabelle gespeichert
	// TODO: auch bei Folgesemestern exportieren?
	
	$res=mysql_query('SELECT snr,fachkurz,pf FROM '.$tpref.'waehltpf WHERE pf IN (1,2,3,4,7)');
	
	while ($data=mysql_fetch_assoc($res)) {
		$kursname='G'.$data['fachkurz'].'1';
		if ($data['pf']==1 || $data['pf']==2 || $data['pf']==7) {
			if ($data['fachkurz']=='no') continue;
			$kursname[0]='L';
		}
		$arr = array($data['snr'],'',$kursname,$data['fachkurz'],$jg,'',$data['snr'],'','','','','','');
		for($i=0;$i<12;$i++){
			echo "\"".$arr[$i]."\";";
		}
		echo "\"".$arr[12]."\"\n";
	}

?>