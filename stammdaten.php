<?php

include 'auth.inc.php';

if (!$isadmin) {
	header( 'Location: index.php' );
}

include 'dbconnect.inc.php';
include 'header.inc.php';
include 'menu.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

if ($uid!="") {
	// $uid is set in header.inc.php
	$res=mysql_query('SELECT name,vorname,prof1,prof2,klasse,pw,kursadd,mail FROM '.$tpref.'schueler WHERE snr='.$uid);
	$data=mysql_fetch_assoc($res);
} else {
	$uid="";
	$data=array();
	$data['name']="";
	$data['vorname']="";
	$data['prof1']="";
	$data['prof2']="";
	$data['klasse']="";
	$data['pw']="";
	$data['kursadd']="0";
	$data['mail']="";
}

?>
<div id="debug"></div>
<form name="sdata">
<table>
<tr><td>Nummer</td><td><input type="text" name="snr" value="<?php echo $uid;?>" onChange="change();"></td></tr>
<tr><td>Name</td><td><input type="text" name="name" value="<?php echo $data['name'];?>" onChange="change();"></td></tr>
<tr><td>Vorname</td><td><input type="text" name="vorname" value="<?php echo $data['vorname'];?>" onChange="change();"></td></tr>
<tr><td>E-Mail</td><td><input type="text" name="mail" value="<?php echo $data['mail'];?>" onChange="change();"></td></tr>
<tr><td>Prof. 1</td><td><input type="text" name="prof1" value="<?php echo $data['prof1'];?>" onChange="change();"></td></tr>
<tr><td>Prof. 2</td><td><input type="text" name="prof2" value="<?php echo $data['prof2'];?>" onChange="change();"></td></tr>
<tr><td>Klasse</td><td><input type="text" name="klasse" value="<?php echo $data['klasse'];?>" onChange="change();"></td></tr>
<tr><td>Kursanz. +/-</td><td><input type="text" name="kursadd" value="<?php echo $data['kursadd'];?>" onChange="change();"></td></tr>
</table>
<br>

<input type="hidden" name="oldsnr" value="<?php echo $uid;?>">
</form>

<?php include 'footer.inc.php'; ?>