<?php
if (! function_exists('getsetting'))
{
	function getsetting($key,$pfx) {
		return DB::get_value("SELECT wert FROM ".$pfx."einstellungen WHERE schluessel='$key'");
	}
	
	function gettableprefix() {
		return strtolower($_SESSION['school']).'_'.$_SESSION['year'].'_';
	}
}
?>