<?php

session_start();

include 'dbconnect.inc.php';

include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

//$uid=$_SESSION['user'];
$uid=$_POST['snr'];

// erstmal alles löschen, falls vorhanden
mysql_query ('DELETE FROM '.$tpref."waehltpf WHERE snr=$uid");
mysql_query ('DELETE FROM '.$tpref."waehlt WHERE snr=$uid");

// Prüfungsfächer einfügen
$pf=array('lk1','lk2','pf3','pf4','pk5','pk5typ','lk3');
$pfdata=array();
for ($i=0;$i<count($pf);$i++) {
	$fach=$_POST[$pf[$i]];
	mysql_query('INSERT INTO '.$tpref."waehltpf (snr,fachkurz,pf) VALUES ($uid,'$fach',".($i+1).')');
	if (($i<4)||($i==6)) $pfdata[]=$fach;
}

// Fächer laden
$res=mysql_query ('SELECT ord,kurz FROM '.$tpref.'fach WHERE kannGK=1 ORDER BY ord');
$ret=array();
while ($data=mysql_fetch_assoc($res)) {
    $ret[]=$data;
}

for ($i=0;$i<count($ret);$i++) {
	$sem=$_POST[$ret[$i]['ord']];
	$kurz=$ret[$i]['kurz'];

	// überflüssigerweise übertragene Prüfungsfächer nicht in Grundkurse eintragen
	if (in_array($kurz,$pfdata)) continue;

	if ($sem=='no') continue;
	if ($sem=='12') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ($uid,'$kurz',1),($uid,'$kurz',2)");
	}
	if ($sem=='34') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ($uid,'$kurz',3),($uid,'$kurz',4)");
	}
	if ($sem=='44') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ".
		 	"($uid,'$kurz',1),($uid,'$kurz',2),($uid,'$kurz',3),($uid,'$kurz',4)");
	}
	if ($sem=='11') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ".
		 	"($uid,'$kurz',1)");
	}
	if ($sem=='22') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ".
		 	"($uid,'$kurz',1),($uid,'$kurz',2)");
	}
	if ($sem=='23') {
		mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ($uid,'$kurz',2),($uid,'$kurz',3)");
	}
	// enthält "Befreiung = 0"
	for ($l=0;$l<5;$l++) {
		if ($sem==$l) {
			mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ($uid,'$kurz',$l)");
		}
	}
}

// kwfehler aktualisieren
//$res=mysql_query ("UPDATE schueler SET kwfehler=100 WHERE snr=$uid");

// Wahl prüfen und Fehler zurückmelden
include 'checkfunc.inc.php';

$tmp=array();
$tmp[]=$uid;
$err=check ($tmp,'stud',$tpref);

// Kurse zählen
//include 'tools.inc.php';
echo countcourses($uid,$tpref);
if ($err!="") echo ','.$err;

?>