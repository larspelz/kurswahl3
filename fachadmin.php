<?php

function doCheckbox($name,$val) {
	return '<td align="center" class="admin"><input type="checkbox" name="'.$name.'" '.($val!='0'?'checked':'').'></td>';
}

function doInput($name,$val,$size) {
	return '<td class="admin"><input type="text" name="'.$name.'" value="'.$val.'" size="'.$size.'"></td>';
}

function doLine($add,$index,$data) {
	echo '<tr>';
		if ($add)
			echo '<td class="admin"><a href="#" onClick="add();">Add</a></td>';
		else
			echo '<td class="admin"><a href="#" onClick="delete('.$index.');">Del</a></td>';
		echo doInput('K'.$index,(!$add?$data['kurz']:''),4).
			doInput('L'.$index,(!$add?$data['lang']:''),15).
			doInput('O'.$index,(!$add?$data['ord']:''),4).
			doInput('G'.$index,(!$add?$data['fachgr']:''),4).
			doCheckbox('A'.$index,(!$add?$data['kannLK1']:'0')).
			doCheckbox('B'.$index,(!$add?$data['kannLK2']:'0')).
			doCheckbox('C'.$index,(!$add?$data['kannPF3']:'0')).
			doCheckbox('D'.$index,(!$add?$data['kannPF4']:'0')).
			doCheckbox('E'.$index,(!$add?$data['kann5PK']:'0')).
			doCheckbox('F'.$index,(!$add?$data['kannGK']:'0')).
			doInput('W'.$index,(!$add?$data['semwaehlbar']:''),6);
		echo '</tr>'."\n";
}

include 'auth.inc.php';

if (!$isadmin) {
	header( 'Location: index.php' );
}

include 'dbconnect.inc.php';

include 'header.inc.php';
include 'menu.inc.php';

$res=mysql_query('SELECT kurz,lang,fachgr,ord,kannLK1,kannLK2,kannPF3,kannPF4,kann5PK,kannGK,semwaehlbar FROM fach ORDER BY ord');

?>
<form name="subj">
<table cellpadding=3 class="admin"><tr>
<th class="admin">&nbsp;</th>
<th class="admin">K&uuml;rzel</th>
<th class="admin">Name</th>
<th class="admin">Nr</th>
<th class="admin">FachGr</th>
<th class="admin">1.LK</th>
<th class="admin">2.LK</th>
<th class="admin">3.PF</th>
<th class="admin">4.PF</th>
<th class="admin">5.PK</th>
<th class="admin">GK</th>
<th class="admin">Semester</th>
</tr>

<?php
$id=0;
doLine(true,$id,'');
$id++;
while ($line=mysql_fetch_assoc($res)) {
	doLine(false,$id,$line);
	$id++;
}
echo '<input type="hidden" name="count" value="'.$id.'">';
?>

</table>
</form>

<?php

include 'footer.inc.php';

?>