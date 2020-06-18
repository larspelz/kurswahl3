<?php

class Container {
	public $snr;
	public $fk;
	public $name;
	public $vorname;
}

	include 'dbconnect.inc.php';
	
	include 'auth.inc.php';
	
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="kurswahl.csv"');
	header('Content-Encoding: UTF8');
	
	if (isset($_GET['sem'])) $semno=$_GET['sem']; else $semno='12';
	
	$sql='SELECT s.snr,s.name,s.vorname,t.fachkurz FROM ('.
		'SELECT snr,fachkurz,COUNT(fachkurz) AS cnt FROM '.$tpref.'waehlt '.
		'WHERE sem IN ('.$semno[0].','.$semno[1].') GROUP BY snr,fachkurz) AS t, '.$tpref.'schueler s WHERE cnt<2 and s.snr=t.snr ORDER BY snr';
	//echo $sql;
	$res=mysql_query($sql);
	$base=array();
	while ($data=mysql_fetch_assoc($res)) {
		$c=new Container();
		$c->snr=$data['snr'];
		$c->fk=$data['fachkurz'];
		$c->name=$data['name'];
		$c->vorname=$data['vorname'];
		$base[]=$c;
	}
	
	$sem=array();
	foreach ($base as $c) {
		$snr=$c->snr;
		$fkurz=$c->fk;
		$name=$c->name;
		$vorname=$c->vorname;
		$res=mysql_query("SELECT sem FROM ".$tpref."waehlt WHERE sem IN (".$semno[0].",".$semno[1].") AND snr=$snr AND fachkurz='$fkurz' ORDER BY sem");
		$data=mysql_fetch_assoc($res);
		echo $snr.','.$name.','.$vorname.',';
		if ($data['sem']==$semno[0]) echo $fkurz.',_'; else echo '_,'.$fkurz;
		echo "\n";
	}
	
	/*
	SELECT sema.snr, sema.fachkurz AS sem1, semb.fachkurz AS sem2, sema.sem as x1, semb.sem as x2

FROM waehlt AS sema

LEFT JOIN waehlt AS semb

ON (sema.snr = semb.snr)

WHERE sema.sem = 1 AND semb.sem = 2

	*/
	
?>