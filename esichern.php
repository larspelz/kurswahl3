<?php

session_start();

include 'dbinterface.inc.php';

DB::connect();

include 'auth.inc.php';

include 'getconfig.inc.php';
$tpref=gettableprefix();

//$uid=$_SESSION['user'];
$uid=$_POST['snr'];

// erstmal alles löschen, falls vorhanden
DB::query ('DELETE FROM '.$tpref."waehltpf WHERE snr='$uid'");
DB::query ('DELETE FROM '.$tpref."waehlt WHERE snr='$uid'");

// Prüfungsfächer einfügen
$pf=array('lk1','lk2','pf3','pf4','pk5','pk5typ','lk3');
for ($i=0;$i<count($pf);$i++) {
    $fach=$_POST[$pf[$i]];
    DB::query('INSERT INTO '.$tpref."waehltpf (snr,fachkurz,pf) VALUES ('$uid','$fach',".($i+1).")");
}

// Fächer laden
$ret=DB::get_assoc ('SELECT ord,kurz FROM '.$tpref.'fach WHERE kannGK=1 ORDER BY ord');

for ($i=0;$i<count($ret);$i++) {
    $sem=explode(',',$_POST[$ret[$i]['ord']]);
    $kurz=$ret[$i]['kurz'];
    for ($k=0;$k<4;$k++) {
        if ($sem[$k]=='1') DB::query('INSERT INTO '.$tpref."waehlt (snr,fachkurz,sem) VALUES ('$uid','$kurz',($k+1))");
    }
}

// kwfehler aktualisieren
DB::query ('UPDATE '.$tpref."schueler SET kwfehler=100 WHERE snr='$uid'");

include 'tools.inc.php';
echo countcourses($uid,$tpref);

?>
