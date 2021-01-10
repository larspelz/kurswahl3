<?php
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
}

if($authresult == true) {
	$userinfo = $oidc->requestUserInfo();
	echo "IServ-Authentifizierung erfolgreich. Userinfo: <br />\n";
	var_dump_pre($userinfo); 
	exit;
}

function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}

