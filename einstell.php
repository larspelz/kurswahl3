<?php

include 'auth.inc.php';

if (!$isadmin) {
	header( 'Location: index.php' );
}

include 'dbinterface.inc.php';

DB::connect();

include 'header.inc.php';
include 'menu.inc.php';
include 'getconfig.inc.php';

$pdf_footer=getsetting('pdf_footer',$tpref);
$sys_motd=getsetting('sys_motd',$tpref);

?>
<form name="settings">
	<b>Text f&uuml;r PDF-Fu&szlig;zeile</b> <br />
	<textarea name="pdf_footer" cols="60" rows="4" onkeydown="change();"><?php echo $pdf_footer;?></textarea><br /><br />
</form>
<?php

include 'footer.inc.php';

?>