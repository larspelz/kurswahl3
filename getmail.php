<?php

include 'auth.inc.php';

/*if ($isadmin) {
	header( 'Location: studlist.php' );
}*/

include 'dbconnect.inc.php';
include 'getconfig.inc.php';

$tpref=gettableprefix();

$uid=$_SESSION['user'];

if (isset($_POST['mail'])) {
	include 'tools.inc.php';
	$mail=$_POST['mail'];
	if (!check_email_address($mail)) 
		$err='Fehler bei der Angabe der E-Mail-Adresse!';
	else {
		$mail=mysql_real_escape_string($mail);
		mysql_query('UPDATE '.$tpref."schueler SET mail='$mail' WHERE snr=$uid");
		header('Location: auswahl.php');
	}
}

include 'header.inc.php';
include 'menu.inc.php';

?>
<br><br>
<?php if (isset($err)) echo ('<font color="red">'.$err.'</font>'); ?>
<br>
<b>Bitte aktualisieren Sie Ihre E-Mail-Adresse!</b><br><br>
<form method="POST">
E-Mail-Adresse: <input type="text" name="mail">
<input type="submit" value="Eintragen">
</form>

<?

include 'footer.inc.php';

?>