<?php

	include 'dbconnect.inc.php';
	
	include 'auth.inc.php';
	
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	$tablename=$_GET['table'];
	if (!isset($tablename)) {
		echo 'Bitte Tabellenname (?table=) angeben!';
		exit(0);
	}
	
	header('Content-type: text/ascii');
	header('Content-Disposition: attachment; filename="pruefungsfaecher.txt"');
	
	$stud=array();
	$res=mysql_query ('SELECT snr FROM '.$tpref.'schueler');
	while ($data=mysql_fetch_assoc($res)) {
		$stud[]=$data['snr'];
	}
	
	$pf=array();
	for ($i=0;$i<count($stud);$i++) {
		$pf[$stud[$i]]=array();
		//echo $stud[$i].': ';
		$res=mysql_query('SELECT fachkurz,pf FROM '.$tpref.'waehltpf WHERE snr='.$stud[$i].' ORDER BY pf');
		$num=mysql_num_rows($res);
		for ($j=0;$j<$num;$j++) {
			$data=mysql_fetch_assoc($res);
			if (($data['pf']>=1 && $data['pf']<=5) || $data['pf']==7)
				//echo ' -- '.$data['pf'].': '.$data['fachkurz'];
				$pf[$stud[$i]][$data['pf']]=$data['fachkurz'];
			if ($data['pf']==6)
				if ($data['fachkurz']=='PRS')
					$pf[$stud[$i]][6]='5.F';				
				else
					$pf[$stud[$i]][6]=$data['fachkurz'];
		}
		//echo '<br>';
	}
	
	for ($i=0;$i<count($stud);$i++) {
		if (!isset($pf[$stud[$i]][1])) continue;
		echo 'UPDATE '.$tablename.' SET PF3=\''.$pf[$stud[$i]][3].'\','.
			' PF4=\''.$pf[$stud[$i]][4].'\','.
			' PF5=\''.$pf[$stud[$i]][5].'\','.
			' LF1=\''.$pf[$stud[$i]][1].'\','.			
			' LF2=\''.$pf[$stud[$i]][2].'\','.			
			' LF3=\''.$pf[$stud[$i]][7].'\','.
			' PF5Typ=\''.$pf[$stud[$i]][6].'\' WHERE SNummer='.$stud[$i].";\n";
	}
?>
