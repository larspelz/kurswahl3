<?php

include 'auth.inc.php';
include 'header.inc.php';
include 'getconfig.inc.php';

?>

<h3><font color="red">Nutzungshinweis</font></h3>
<table width="60%"><tr><td>
<?php echo getsetting('sys_motd',$tpref); ?>

<a href="auswahl.php">Weiter zur Kurswahl</a>
</td></tr></table>

<?php include 'footer.inc.php'; ?>