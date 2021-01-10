<?php

	session_start();

	include 'dbinterface.inc.php';
	DB::connect();

   if ($_POST) {
	  $uid=DB::esc ($_POST['uid']);
	  $pwd=DB::esc ($_POST['pwd']);
	  $pwd=hash("sha256", $pwd);
	  
	  $school=DB::esc ($_POST['school']);
	  $year=DB::esc ($_POST['year']);
	  if ($school!="") {
			$res=DB::check("SELECT schulnr FROM kurswahl_schule WHERE schulnr='$school'");
			if (!$res) unset($school);
	  }
	  
	  if ($year!="" && (isset($school))) {
			$res=DB::check("SELECT jahr FROM kurswahl_jahrgang WHERE jahr=$year AND schulnr='$school'");
			if ($res)
				unset ($year);
			else {
				$_SESSION['year']=$year;
				$_SESSION['school']=$school;
				include 'getconfig.inc.php';
				$tpref=gettableprefix();
			}
	  }

	  if (($uid!="") && ($pwd!=hash("sha256", "")) && (isset($school)) && (isset($year))) {
		 if (ctype_digit($uid)) {
			// es wurde eine Zahl eingegeben: Benutzer ist Schüler
			$sel='SELECT snr FROM '.$tpref."schueler WHERE snr=$uid AND pw='$pwd'";
			//echo $sel;
			$res=DB::get_value_or_false($sel);
			if ($res==$uid) {
				$_SESSION['user']=$uid;
				if (isset($_SESSION['logfail'])) unset ($_SESSION['logfail']);
				header ('Location: motd.php');
				exit();
			} else {
				$_SESSION['logfail']='-';
			} 
			
		 } else {
		 
		    // $uid ist String => Benutzer ist admin?
			$res=DB::get_value_or_false('SELECT login FROM '.$tpref."admin WHERE login='$uid' AND pass='$pwd'");
			if ($res==$uid) {
				$_SESSION['admin']=$uid;
				header ('Location: studlist.php');
				exit();
			} else {
				$_SESSION['logfail']='-';
			}
			
		 }

	  }

   }

   include 'header.inc.php';

?>
<br><br>
<?php 
	if (isset($_SESSION['logfail'])) echo "Benutzername oder Passwort falsch!";
	session_destroy();
 ?>
<br>
   Bitte melden Sie sich an:
   <form action="index.php" method="POST">
   <table><tr><td>
   Sch&uuml;lernummer:</td><td><input type="text" name="uid"></td>
   </tr><tr><td>
   Passwort:</td><td><input type="password" name="pwd"></td>
   </tr><tr><td>Schulnummer:</td><td>
   <?php
	if (!isset($school) && isset($_GET['school'])) $school=$_GET['school'];
	if (isset($school))
		echo $school.'<input type="hidden" name="school" value="'.$school.'">';
	else
		echo '<input type="text" name="school">';
   ?>
   </td></tr><tr><td>Jahrgang:</td><td>
   <?php
    if (!isset($year) && isset($_GET['year'])) $year=$_GET['year'];
	if (isset($year)) 
		echo $year.'<input type="hidden" name="year" value="'.$year.'">';
	else
		echo '<input type="text" name="year">';
   ?>
   </td></tr></table>
   <input type="submit" value="Anmelden">
   </form>
   
   </br>
   <a class="button" href="oidc.php"><img src="imgs/IServ_Logo_klein_RGB_clean.svg " width="64px" alt="" style="vertical-align:middle;" />&nbsp;&ndash;&nbsp;Login (HOS)</a>

<?php
   include 'footer.inc.php';

?>