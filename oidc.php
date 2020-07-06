<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://humboldtschule-berlin.eu',
                                '99_3sy0fbelfy684cck4k4csw8cokkwgg4s84k8s0w8wkc8o4sw4w',
                                '3vdov2ptuuyocw0o044wg004ww8owo88w4cwsss0swscwkow8o');

$oidc->setResponseTypes(array('code'));
$oidc->providerConfigParam(array('auth_endpoint'=>'https://humboldtschule-berlin.eu/iserv/oauth/v2/auth','token_endpoint'=>'https://humboldtschule-berlin.eu/iserv/oauth/v2/token','userinfo_endpoint'=>'https://humboldtschule-berlin.eu/iserv/public/oauth/userinfo'));
$oidc->setRedirectURL('https://wolke7.spdns.org/kurswahl3/oidc.php');
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

