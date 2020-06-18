<?php
include 'dbconnect.inc.php';
include 'auth.inc.php';
include 'getconfig.inc.php';
include 'header.inc.php';
$tpref=gettableprefix();

if (!$isadmin) {
	header( 'Location: index.php' );
}

function passwort() {
   $ziffern = "bcdfghjkmnpqrstvwxz23456789WRTZPLKJHGFDSXCVBNM?!%#";
   $passwort="";
   for($i = 0; $i < 6; $i++) {
      $passwort .= substr($ziffern,(rand()%(strlen ($ziffern))), 1);
   }
   return $passwort;
}

if (isset($_GET['mode']) && $_GET['mode']=='gen') {

	// create new passwords
   $snr=array();
   $res=mysql_query('SELECT snr FROM '.$tpref.'schueler WHERE realpw=\'\' ORDER BY snr');
   while ($data=mysql_fetch_row($res)) {
      $snr[]=$data[0];
   }

   foreach ($snr as $n) {
      $pw=passwort();
      mysql_query('UPDATE '.$tpref."schueler SET realpw='$pw',pw='".sha1($pw)."',kwfehler=100 WHERE snr=$n");
   }
}

if (isset($_GET['mode']) && $_GET['mode']=='unlock') {
   // restore old passwords
   $res=mysql_query('SELECT snr,realpw FROM '.$tpref.'schueler WHERE pw=\'\'');
   while ($data=mysql_fetch_assoc($res)) {
      mysql_query('UPDATE '.$tpref."schueler SET pw='".sha1($data['realpw'])."',kwfehler=100 WHERE snr=".$data['snr']);
   }
}

if (isset($_GET['mode']) && $_GET['mode']=='lock') {
	mysql_query('UPDATE '.$tpref."schueler SET pw=''");
}

if (isset($_GET['mode']) && ($_GET['mode']=='list' || $_GET['mode']=='gen')) {
   $res=mysql_query('SELECT snr,name,vorname,realpw,klasse FROM '.$tpref.'schueler ORDER BY snr');
   while ($data=mysql_fetch_assoc($res)) {
      echo $data['snr'].';'.$data['name'].';'.$data['vorname'].';'.$data['realpw'].';'.$data['klasse'].'<br>';
   }
}
include 'footer.inc.php';
?>