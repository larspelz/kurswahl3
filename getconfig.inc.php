<?php
if (! function_exists('getsetting'))
{
	function getsetting($key,$pfx) {
		$res=mysql_query("SELECT wert FROM ".$pfx."einstellungen WHERE schluessel='$key'");
		$val="";
		if (mysql_num_rows($res)!=0) {
			$data=mysql_fetch_assoc($res);
			$val=$data['wert'];
		}
		return $val;
	}
	
	function gettableprefix() {
		return strtolower($_SESSION['school']).'_'.$_SESSION['year'].'_';
	}
}
?>