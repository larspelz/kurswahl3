<?php

function setextra($kurz,$set,$snr,$tpref,$sem) {
	$sql='DELETE FROM '.$tpref."waehltsp WHERE snr='$snr' AND sem=$sem";
	//echo $sql.'<br>';
	$res=DB::query($sql);
	$kurz=DB::esc($kurz);
	if ($set) {
		$sql='INSERT INTO '.$tpref."waehltsp (snr,kuerzel,sem) VALUES ('$snr','$kurz',$sem)";
		//echo $sql.'<br>';
		DB::query($sql);
	}
}

function getextra($snr,$tpref,$sem) {
	$res=DB::get_value_or_false('SELECT kuerzel FROM '.$tpref."waehltsp WHERE snr='$snr' AND sem=$sem");
	if (is_string($res)) {
		return $res;
	} else {
		return 'no';
	}
}

session_start();

include 'dbinterface.inc.php';

DB::connect();

include 'auth.inc.php';
include 'getconfig.inc.php';
$tpref=gettableprefix();

// load course description from db
$kuerzel=array();
$langname=array();
$hat2ls=array();
$res=DB::get_assoc('SELECT kuerzel,langname,hat2ls FROM '.$tpref.'sportkurs');
$k=0;
foreach ($res as $data) {
	$kuerzel[$k]=$data['kuerzel'];
	$langname[$k]=$data['langname'];
	$hat2ls[$k]=$data['hat2ls'];
	$k++;
}

// load student information
if ($isadmin) {
	if (isset ($_POST['num'])) {
		// admin issued a save
		$uid=DB::esc($_POST['num']);
	} else {
		// admin first look
		if (isset($_GET['num'])) $uid=DB::esc($_GET['num']);
	}
} else {
	// user loop
	$uid=$_SESSION['user'];
}

include 'header.inc.php';
include 'menu.inc.php';

// process eventual POST data
if (isset ($_POST['data'])) {
	for ($i=1;$i<6;$i++){
		$kurs=$_POST['k'.$i];
		$ls=$_POST['ls'.$i];
		$res=DB::get_value_or_false('SELECT * from '.$tpref."waehltsp WHERE snr='$uid' AND sem=$i");
		$sql='';
		if (is_string($res)) {
			// entry already present, needs UPDATE
			$sql='UPDATE '.$tpref."waehltsp SET kuerzel='$kurs', lstufe=$ls WHERE snr='$uid' AND sem=$i";
		} else {
			// entry is not present, needs INSERT
			$sql='INSERT INTO '.$tpref."waehltsp (snr,kuerzel,sem,lstufe) VALUES ('$uid','$kurs',$i,$ls)";
		}
		DB::query($sql);
	}
	if ($_POST['ski']=='y') {
		setextra('SK',true,$uid,$tpref,6);
	} else {
		setextra('SK',false,$uid,$tpref,6);
	}
	
	if ($_POST['exchg']!='no') {
		setextra($_POST['exchg'],true,$uid,$tpref,10);
	} else {
		setextra('EXC',false,$uid,$tpref,10);
	}

	if ($_POST['ens']!='no') {
		setextra($_POST['ens'],true,$uid,$tpref,11);
	} else {
		setextra($_POST['ens'],false,$uid,$tpref,11);
	}
	
	if ($_POST['chi']!='no') {
		setextra($_POST['chi'],true,$uid,$tpref,12);
	} else {
		setextra($_POST['chi'],false,$uid,$tpref,12);
	}
	
}
?>
<center>
<b><font color="red">Bitte klicken Sie nach dem Eintragen auf Speichern!</font></b><br><br>
<form action="sportwahl.php" name="f" method="POST">
<div style="border: 2px solid #ff0000; padding:5px; display:table;width:auto;">
<span style="position:relative; top:-10px; margin-left: 20px; background-color:#fff;padding:0 10px;" >
Sportkurse
</span>

<?php 
	if (!$isadmin) { echo ("
		<br>Bitte w&auml;hlen Sie <b>vier</b> Sportkurse, die Sie belegen m&ouml;chten,
		sowie <b>einen</b> Kurs als Alternative.");
	}
	
  	// load student's choice from db
  	$res=DB::get_assoc('SELECT kuerzel,sem,lstufe FROM '.$tpref."waehltsp WHERE snr='$uid'");
  	foreach ($res as $data) {
  		$wahl[$data['sem']]=$data['kuerzel'];
  		$lstufe[$data['sem']]=$data['lstufe'];
  	}

  	// build form content
?>
<table border="1" cellpadding="7">
<tr><td>1. Kurs</td><td>2. Kurs</td><td>3. Kurs</td><td>4. Kurs</td><td>Alternative</td></tr>
<tr>
<?php
	// build drop down boxes
	for ($i=1;$i<6;$i++) {
		echo '<td><select name="k'.$i.'" id="k'.$i.'" onblur="check('.$i.');">'."\n".
		     '<option value="none">Auswahl!</option>';
		for ($k=0;$k<count($kuerzel);$k++) {
			// check whether student selected this course before
			if (isset($wahl[$i])) {
				if ($wahl[$i]==$kuerzel[$k]) {
					$sel='selected';
				} else {
					$sel='';
				}
			}
			if (!isset($sel)) $sel='';
			echo '<option '.$sel.' value="'.$kuerzel[$k].'">'.$langname[$k].'</option>'."\n";
		}
	echo '</select><br>
	Leistungsstufe:<br><select name="ls'.$i.'" id="ls'.$i.'" onblur="check('.$i.');">
	<option>1</option>
	<option';
		// detect chosen grading level
		if (isset($lstufe)) 
			if ($lstufe[$i]=='2') echo 'selected';
	echo '>2</option></select></td>';
	}
?>
</tr>
<tr><td colspan="5">
M&ouml;chten Sie an der Skifahrt teilnehmen? &nbsp;&nbsp;
<select name="ski" id="ski">
<option value="n">Nein</option>
<?php 
	echo '<option ';
	if (getextra($uid,$tpref,6)=='SK') echo 'selected';
   echo ' value="y">Ja</option>';
?>
</select>
</td></tr></table>

<br>Bitte beachten:<br>
Der Sportkurs <b>Rudern</b> ist mit der Teilnahme an Wochenendterminen verbunden.<br>Teilnehmer m&uuml;ssen
mindestens &uuml;ber das Schwimmabzeichen Bronze (Freischwimmer) verf&uuml;gen.<br>
<b>Externe Sportarten</b> k&ouml;nnen gew&auml;hlt werden, doch die Pl&auml;tze m&uuml;ssen selbst organisiert werden.<br>
Der Sportkurs <b>Klettern</b> findet an ausgew&auml;hlten Terminen am Samstag statt. Es ist ein Unkostenbeitrag zu entrichten.

</div> <!-- Sportkurswahl Ende -->
<table><tr><td>
<div style="border: 2px solid darkblue; padding:5px; display:table; width:auto;">
<span style="position:relative; top:-10px; margin-left: 20px; background-color:#fff;padding:0 10px;" >
Auslandsaufenthalt
</span>
<br>In welchen Semestern haben Sie vor ins Ausland <br>zu gehen? <select name="exchg">
<option value="no">kein</option>
<option value="EX1"
<?php
	if (getextra($uid,$tpref,10)=='EX1') echo 'selected';
?>
>nur im 1. Semester</option>
<option value="EX2"
<?php
	if (getextra($uid,$tpref,10)=='EX2') echo 'selected';
?>
>nur im 2. Semester</option>
<option value="EX3"
<?php
	if (getextra($uid,$tpref,10)=='EX3') echo 'selected';
?>
>im 1. & 2. Semester</option>
<option value="EX4"
<?php
	if (getextra($uid,$tpref,10)=='EX4') echo 'selected';
?>
>sp&auml;ter als 2. Semester</option>
</select>
</div>

</td><td>

<div style="border: 2px solid darkblue; padding:5px; display:table; width:auto;">
<span style="position:relative; top:-10px; margin-left: 20px; background-color:#fff;padding:0 10px;" >
Musik-AGs
</span>
<br>Falls Sie möchten, können Sie hier Ihre<br> Teilnahme an einer der Musik-AGs wählen: <select name="ens">
<option value="no">keine AG-Teilnahme</option>
<?php $ens=getextra($uid,$tpref,11); ?>
<option value="ORC"
<?php
	if ($ens=='ORC') echo 'selected';
?>
>Orchester</option>
<option value="JAZ"
<?php
	if ($ens=='JAZ') echo 'selected';
?>
>Jazz-Band</option>
<option value="CHR"
<?php
	if ($ens=='CHR') echo 'selected';
?>
>Chor</option>
</select>

</div>
</td></tr><tr><td>
<div style="border: 2px solid darkblue; padding:5px; display:table; width:auto;">
<span style="position:relative; top:-10px; margin-left: 20px; background-color:#fff;padding:0 10px;" >
Leistungskurs Chinesisch
</span>
<br>Falls ein Leistungskurs Chinesisch eingerichtet wird,<br>m&ouml;chten Sie dann daran teilnehmen?
<?php $chi=getextra($uid,$tpref,12); ?>
<select name="chi">
<option value="no" 
<?php
	if ($chi=='no') echo 'selected';
?>>-- Ausw&auml;hlen --</option>
<option value="CHY" 
<?php
	if ($chi=='CHY') echo 'selected';
?>>Ja</option>
<option value="CHN" 
<?php
	if ($chi=='CHN') echo 'selected';
?>>Nein</option>
</select>
</div>
</td><td>&nbsp;
</td></tr></table>
<input type="hidden" name="data" value="nothing">
<?php if ($isadmin) echo '<input type="hidden" name="num" value="'.$uid.'">' ?>
</form>
</center>
</body>
</body>
</html>