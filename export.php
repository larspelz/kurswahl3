<?php

	function arr2csv($arr) {
		for($i=0;$i<count($arr);$i++)
			$arr[$i]= '"'.$arr[$i].'"';
		
		return implode (';',$arr)."\n";
	}

	include 'dbinterface.inc.php';
	DB::connect();
	
	include 'auth.inc.php';
	
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="kurswahl.csv"');
	
	// Grundkurse exportieren
	
	if (isset($_GET['semno'])) $semno=$_GET['semno']; else $semno=1;
	
	#$res=DB::get_assoc('SELECT snr,fachkurz FROM '.$tpref.'waehlt WHERE sem='.$semno." AND (NOT fachkurz='SP')");
    $res=DB::get_assoc('SELECT w.snr AS snr,fachkurz,klasse,oxs FROM '.$tpref.'waehlt w JOIN '.$tpref.'schueler s ON s.snr=w.snr WHERE sem='.$semno." AND (NOT fachkurz='SP')");
	
	// Jahrgang setzen
	#if ($semno<3) $jg=12; else $jg=13;
	
	foreach ($res as $data) {
	
		// Bei Seminarkursen muss kein G vorangestellt werden
		$kursname=$data['fachkurz'];
		$kursname='G'.$kursname;
		
		$arr = array($data['oxs'],'',$kursname.'1',$data['fachkurz'],$data['klasse'],'',$data['snr'],'','','','','','');
		echo arr2csv($arr);
	}
	// Prüfungsfächer exportieren, werden immer für 4 Semester gewählt und sind nicht in
	// waehlt-Tabelle gespeichert
	// TODO: auch bei Folgesemestern exportieren?
	
	#$res=DB::get_assoc('SELECT snr,fachkurz,pf FROM '.$tpref.'waehltpf WHERE pf IN (1,2,3,4,7)');
    $res=DB::get_assoc('SELECT w.snr AS snr,fachkurz,klasse,oxs,pf FROM '.$tpref.'waehltpf w JOIN '.$tpref.'schueler s ON s.snr=w.snr WHERE pf IN (1,2,3,4,7)');
	
	foreach ($res as $data) {
		$kursname='G'.$data['fachkurz'].'1';
		if ($data['pf']==1 || $data['pf']==2 || $data['pf']==7) {
			if ($data['fachkurz']=='no') continue;
			$kursname[0]='L';
		}
		$arr = array($data['oxs'],'',$kursname,$data['fachkurz'],$data['klasse'],'',$data['snr'],'','','','','','');
		echo arr2csv($arr);
	}

?>