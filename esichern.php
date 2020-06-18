<?php

session_start();

include 'dbconnect.inc.php';

include 'auth.inc.php';

include 'getconfig.inc.php';
$tpref=gettableprefix();

//$uid=$_SESSION['user'];
$uid=$_POST['snr'];

// erstmal alles löschen, falls vorhanden
mysql_query ('DELETE FROM '.$tpref.'waehltpf WHERE snr='.$uid);
mysql_query ('DELETE FROM '.$tpref.'waehlt WHERE snr='.$uid);

// Prüfungsfächer einfügen
$pf=array('lk1','lk2','pf3','pf4','pk5','pk5typ','lk3');
for ($i=0;$i<count($pf);$i++) {
    $fach=$_POST[$pf[$i]];
    mysql_query('INSERT INTO '.$tpref."waehltpf (snr,fachkurz,pf) VALUES ($uid,'$fach',".($i+1).")");
}

// Fächer laden
$res=mysql_query ('SELECT ord,kurz FROM '.$tpref.'fach WHERE kannGK=1 ORDER BY ord');
$ret=array();
while ($data=mysql_fetch_assoc($res)) {
    $ret[]=$data;
}

for ($i=0;$i<count($ret);$i++) {
    $sem=explode(',',$_POST[$ret[$i]['ord']]);
    $kurz=$ret[$i]['kurz'];
    for ($k=0;$k<4;$k++) {
        if ($sem[$k]=='1') mysql_query ('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ($uid,'$kurz',($k+1))");
    }
}

// kwfehler aktualisieren
$res=mysql_query ('UPDATE '.$tpref.'schueler SET kwfehler=100 WHERE snr='.$uid);

include 'tools.inc.php';
echo countcourses($uid,$tpref);

?>
