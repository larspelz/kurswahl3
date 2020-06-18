<?php

if (!session_id()) {
	session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
	header( 'Location: index.php' );
}

// TODO: Schule und Jahrgang aus $_SESSION laden

if (isset($_SESSION['admin'])) {
	$isadmin=true;
} else {
	$isadmin=false;
}

?>