<?php

include 'auth.inc.php';

if (!$isadmin) {
	header( 'Location: index.php' );
}

include 'dbinterface.inc.php';
DB::connect();
include 'getconfig.inc.php';
$tpref=gettableprefix();

// delete a student?
if (isset($_GET['del'])) {
	$dnum=$_GET['del'];
	DB::query('DELETE FROM '.$tpref.'waehlt WHERE snr='.$dnum);
	DB::query('DELETE FROM '.$tpref.'waehltpf WHERE snr='.$dnum);
	DB::query('DELETE FROM '.$tpref.'waehltsp WHERE snr='.$dnum);
	DB::query('DELETE FROM '.$tpref.'schueler WHERE snr='.$dnum);
}

include 'header.inc.php';
include 'menu.inc.php';
include 'tools.inc.php';

?>
<div id="cont">
<table cellpadding=3 class="admin"><tr>
<th class="admin"><a href="studlist.php?sort=nm">Account</a></th>
<th class="admin"><a href="studlist.php?sort=na">Name</a></th>
<th class="admin"><a href="studlist.php?sort=vn">Vorname</a></th>
<th class="admin"><a href="studlist.php?sort=kl">Klasse</a></th>
<th class="admin">Kurse</th>
<th class="admin">Bearbeiten</th>
<th class="admin">Stammdaten</th>
<th class="admin">PDF</th>
<?php
$errsel='';
if (isset($_GET['errsel'])) {
	$errsel=$_GET['errsel'];
	echo '<th class="admin">Fehler<br>nur '.$errsel.', <a href="studlist.php">reset</a></th></tr>';
	$errsel=" WHERE kwfehler LIKE '%".DB::esc($_GET['errsel'])."%' ";
} else {
	echo '<th class="admin">Fehler</th></tr>';
}

$order='name';
if (isset($_GET['sort'])) $s=$_GET['sort']; else $s='';

if ($s=='nm') $order='snr';
if ($s=='na') $order='name';
if ($s=='vn') $order='vorname';
if ($s=='kl') $order='klasse,name,vorname';

$res=DB::get_assoc ('SELECT snr, name, vorname, klasse, kwfehler, kursadd FROM '.$tpref.'schueler '.$errsel.' ORDER BY '.$order);

foreach ($res as $data) {
	$snr=$data['snr'];
	$ccount=countcourses($snr,$tpref);
	echo '<tr><td class="admin"><a name="'.$snr.'" href="auswahl.php?snr='.$snr.'">'.$snr.'</a></td>'.
		'<td class="admin">'.$data['name'].'</td>'.
		'<td class="admin">'.$data['vorname'].'</td>'.
		'<td class="admin">'.$data['klasse'].'</td>'.
		'<td class="admin" '.($data['kursadd']!=0?'style="background-color:#CCFFEE;"':'').
		'>'.$ccount.'</td>'.
		'<td class="admin" align="center"><a href="edit.php?num='.$snr.'">Bearbeiten</a></td>'.
		'<td class="admin" align="center"><a href="stammdaten.php?num='.$snr.'">Edit</a> '.
			'<a href="#" onClick="deletestudent('.$snr.');">Del</a></td>'.
		'<td class="admin"><a href="pdf/pdf-export.php?num='.$snr.'"><img border=0 src="pdf/imgs/pdf-icon.png" alt="" /></a></td>'.
		'<td class="admin" align="left">';
		if ($data['kwfehler']!='ok') {
			if ($data['kwfehler']=='100') echo '<img border=0 src="imgs/qm.png" alt="" />';
			else {
				echo '<img border=0 src="imgs/err.png" alt="" /> ';
				$err=explode(',',$data['kwfehler']);
				for ($l=0;$l<count($err);$l++) {
					echo '<a href="studlist.php?errsel='.trim($err[$l]).'">'.trim($err[$l]).'</a> ';
				}
			}
		} else echo '<img border=0 src="imgs/ok.png" alt="" />';
		echo "</td></tr>\n";
}
?>

</table>
</div>
<br><br><br>
<?php include 'footer.inc.php'; ?>