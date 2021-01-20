<?php

	include 'auth.inc.php';

	if (!$isadmin) {
		header( 'Location: index.php' );
	}

	include 'dbinterface.inc.php';
	include 'checkfunc.inc.php';
	include 'getconfig.inc.php';
	$tpref=gettableprefix();

	DB::connect();	
	
	$snr=array();
	if (isset($_GET['num'])) {
		// einzelnen Schueler bearbeiten
		$snr[]=$_GET['num'];
	} else {
		// alle Schueler bearbeiten
		$snr=DB::get_list('SELECT snr FROM '.$tpref.'schueler');
	}
	
	$err=check($snr,'stud',$tpref); // TODO: mode=db!
	echo $err;
	//header( 'Location: studlist.php' );
	
?>