<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php 
include 'dbconnect.inc.php';
include 'getconfig.inc.php'; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="kwstyle.css" type="text/css" rel="stylesheet" />
<title>Kurswahl
<?php 
	if ((isset($_SESSION['user'])||isset($_SESSION['admin'])) && !isset ($_SESSION['logfail'])) {
		$res=mysql_query("SELECT name FROM kurswahl_schule WHERE schulnr='".$_SESSION['school']."'");
		if ($res) $name=mysql_fetch_assoc($res);
		$schoolname=$name['name'];
	} else
		$schoolname='Online';
	echo $schoolname;
?></title>

<?php

	// richtige Javascript-Dateien laden
	if (isset($isadmin) && $isadmin) { // in auth.php.inc gesetzt
		echo '<script type="text/javascript" src="js/admin.js"></script>'."\n";
	}
	if(stristr($_SERVER['REQUEST_URI'],"auswahl.php")) {
		echo '<script type="text/javascript" src="js/auswahl.js"></script>'."\n";
	}
	if(stristr($_SERVER['REQUEST_URI'],"edit.php")) {
		echo '<script type="text/javascript" src="js/edit.js"></script>'."\n";
	}
	if(stristr($_SERVER['REQUEST_URI'],"sportwahl.php")) {
    	echo '<script type="text/javascript" src="js/sport.js"></script>'."\n";
    }
	if(stristr($_SERVER['REQUEST_URI'],"stammdaten.php")) {
    	echo '<script type="text/javascript" src="js/stamm.js"></script>'."\n";
		if (isset($_GET['num'])) $uid=$_GET['num']; else $uid="";
    }
	if(stristr($_SERVER['REQUEST_URI'],"studlist.php")) {
    	echo '<script type="text/javascript" src="js/studlist.js"></script>'."\n";
	}
	if(stristr($_SERVER['REQUEST_URI'],"fachadmin.php")) {
		echo '<script type="text/javascript" src="js/fachadmin.js"></script>'."\n";
	}
	if(stristr($_SERVER['REQUEST_URI'],"einstell.php")) {
		echo '<script type="text/javascript" src="js/einstadmin.js"></script>'."\n";
	}

	// Javascript-Daten initialisieren
	if(stristr($_SERVER['REQUEST_URI'],"auswahl.php") || stristr($_SERVER['REQUEST_URI'],"edit.php")) {
		// Fächer-Array dynamisch erzeugen
		echo '<script type="text/javascript">';
		$tpref=gettableprefix();
		$sql='SELECT ord FROM '.$tpref.'fach WHERE kannGK=1 ORDER BY ord';
		$res=mysql_query ($sql);
    	while ($data=mysql_fetch_row($res)) $dt[]=$data[0];
		echo 'fach=new Array(';
    	for ($i=0;$i<count($dt)-1;$i++) {
    		echo '"'.$dt[$i].'",';
    	}
    	echo '"'.$dt[count($dt)-1].'");';
    	// Nachschauen, ob schon eine gespeicherte Kurswahl existiert (kwfehler>0)
    	$res=mysql_query ('SELECT kwfehler FROM '.$tpref.'schueler WHERE snr='.$uid);
    	$kwf=mysql_fetch_row($res);
    	if ($kwf>0) echo 'saved=true;';
    	echo '</script>';
    }

	echo '</head>';

	$btag="<body>";
	if(stristr($_SERVER['REQUEST_URI'],"auswahl.php")) {
		$btag= '<body onLoad="setup();">';
	} 
	echo $btag;
?>
<div id="header">
<font size="5" face="Arial, Helvectica">Kurswahlsystem
<?php
	echo ' '.$schoolname;
?></font><font size="3"> Version 2018</font> 
<?php
	if (isset($isadmin) && $isadmin) echo '<font color="darkred">Administration</font>';
?>
</div>