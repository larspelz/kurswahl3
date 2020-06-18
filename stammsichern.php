<?php

function snrExists($snr,$tp) {

	$res=mysql_query("SELECT snr FROM ".$tp."schueler WHERE snr=$snr");
	if (mysql_num_rows($res)>0) return true; else return false;

}

session_start();

include 'dbconnect.inc.php';

include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

if (isset ($_POST['mode'])) $mode=mysql_real_escape_string($_POST['mode']); // no mode set = $mode stays unset
if (isset ($_POST['snr'])) $snr=mysql_real_escape_string($_POST['snr']); else { echo "NOSNR"; exit(0); }
if (isset ($_POST['oldsnr'])) $oldsnr=mysql_real_escape_string($_POST['oldsnr']); else $oldsnr="";

if (isset($mode)) {
	if ($mode="pw") {
		$pwd=mysql_real_escape_string($_POST['pw']);
		$pwdhash=sha1($_POST['pw']);
		$sql='UPDATE '.$tpref."schueler SET pw='$pwdhash',realpw='$pwd' WHERE snr=$snr";
		$res=mysql_query($sql);
		echo ("PWDOK");
		exit();
	}

	if ($mode="up") {
	}

	if ($mode="down") {
	}
	
	exit();
}

//$name=iconv ('UTF-8','Windows-1250',$_POST['name']);
//$vorname=iconv ('UTF-8','Windows-1250',$_POST['vorname']);
$name=mysql_real_escape_string($_POST['name']);
$vorname=mysql_real_escape_string($_POST['vorname']);
$prof1=mysql_real_escape_string($_POST['prof1']);
$prof2=mysql_real_escape_string($_POST['prof2']);
$klasse=mysql_real_escape_string($_POST['klasse']);
$kursadd=mysql_real_escape_string($_POST['kursadd']);
$mail=mysql_real_escape_string($_POST['mail']);
$err=array();

include 'tools.inc.php';
if (!check_email_address($mail)) {
	$err[]='MAILFAIL';
	$mail='';
}

if ($snr == $oldsnr) {
	// simple update
	$sql='UPDATE '.$tpref."schueler SET name='$name', vorname='$vorname',  ".
		"prof1='$prof1', prof2='$prof2', klasse='$klasse', kursadd='$kursadd', ".
		"mail='$mail' WHERE snr=$snr";
	$res=mysql_query($sql);
	} else {
		if (snrExists($snr,$tpref)) {
			$err[]='SNRFAIL';
		} else {
			// new entry
			if ($oldsnr=="") {
				$sql='INSERT INTO '.$tpref.'schueler (snr,name,vorname,prof1,prof2,klasse,kursadd,mail,pw,realpw,kwfehler) '.
					"VALUES ($snr,'$name','$vorname', ".
					"'$prof1','$prof2','$klasse','$kursadd','$mail','','','')";
				$res=mysql_query($sql);
			} else {
			// student id was changed
				$res=mysql_query('UPDATE '.$tpref."schueler SET name='$name', vorname='$vorname',  ".
					"prof1='$prof1', prof2='$prof2', klasse='$klasse',kursadd='$kursadd', ".
					"mail='$mail',snr=$snr WHERE snr=$oldsnr");
				$res=mysql_query('UPDATE '.$tpref."waehlt SET snr=$snr WHERE snr=$oldsnr");
				$res=mysql_query('UPDATE '.$tpref."waehltpf SET snr=$snr WHERE snr=$oldsnr");
				$res=mysql_query('UPDATE '.$tpref."waehltsp SET sNummer=$snr WHERE sNummer=$oldsnr");
			}
		}
	}
echo (implode($err,';'));
?>