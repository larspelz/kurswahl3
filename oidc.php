<?php

session_start();
if (isset($_SESSION['user'])) {
	session_destroy();
	session_start();
}

require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

include 'config.oidc.php';

$oidc = new OpenIDConnectClient(OIDCConfig::$host,OIDCConfig::$clientid,OIDCConfig::$secret);

$oidc->setResponseTypes(array('code'));
$oidc->providerConfigParam(array('auth_endpoint'=>OIDCConfig::$auth_endpoint,'token_endpoint'=>OIDCConfig::$token_endpoint,'userinfo_endpoint'=>OIDCConfig::$uinfo_endpoint));
$oidc->setRedirectURL(OIDCConfig::$redirect);
$oidc->addAuthParam(array('response_mode' => 'form_post'));
$oidc->addScope('profile groups');

try {
	$authresult = $oidc->authenticate();
} catch(Exception $e) {
	echo "Fehler bei der IServ-Authentifizierung: <br />\n".$e;
	exit;
}

if($authresult == true) {
	$userinfo = $oidc->requestUserInfo();
	//echo "IServ-Authentifizierung erfolgreich.<br>";
	$grps=$userinfo->groups;
	$admin=false;
	$jg=false;
	$user=$userinfo->preferred_username;
	
	foreach ($grps as $g) {
		if ($g->act=='admins') $admin=true;
		if (substr($g->act,0,9)=='klasse.10') $tgrp=substr($g->act,6,3);
	}
	if ($admin || isset($tgrp)) {
		if (!$admin) {
			// check whether student exists and create one if not available
			/*include 'dbinterface.inc.php';
			include 'getconfig.inc.php';
			$tpref=gettableprefix();
			DB::connect();
			$dbuser=DB::get_value_or_false("SELECT snr FROM ".$tpref."schueler WHERE snr='".$user."'");
			if (!$dbuser) {
				$famname=$userinfo->family_name;
				$givname=$userinfo->given_name;
				DB::query("INSERT INTO ".$tpref."schueler (snr,name,vorname,prof1,prof2,klasse,kwfehler,kursadd) VALUES 
			}*/
		}
		$_SESSION['school']='hum';
		$_SESSION['year']=date('y');
		if ($admin) $_SESSION['admin']='yes';
		$_SESSION['user']=$user;
		if ($admin)
			header( 'Location: studlist.php' );
		else 
			header( 'Location: auswahl.php' );
		exit;
	} else {
		echo 'Du hast keinen Zugriff auf das Kurswahlmodul.';
	}
//	var_dump_pre($userinfo); 
	exit;
}

function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}

