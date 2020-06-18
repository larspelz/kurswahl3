<?php

	include 'auth.inc.php';

	if (!$isadmin) {
		header( 'Location: index.php' );
	}

	include 'dbconnect.inc.php';
	include 'checkfunc.inc.php';
	include 'getconfig.inc.php';
	$tpref=gettableprefix();
	
	$snr=array();
	if (isset($_GET['num'])) {
		// einzelnen Schueler bearbeiten
		$snr[]=$_GET['num'];
	} else {
		// alle Schueler bearbeiten
		$res=mysql_query('SELECT snr FROM '.$tpref.'schueler');
		while ($data=mysql_fetch_assoc($res)) {
			$snr[]=$data['snr'];
		}
	}
	
	$err=check($snr,'db',$tpref);
	
	header( 'Location: studlist.php' );

?>