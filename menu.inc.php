<?php
function getStudName ($u,$tpref) {
	if ($u=="") return "";
	$data=DB::get_assoc_row('SELECT name, vorname FROM '.$tpref."schueler WHERE snr='$u'");
	return $data['vorname'].' '.$data['name'];
}

function item($text,$url) {
	return '<li><a href="'.$url.'">'.$text.'</a></li>'."\n";
}

function itemtx($text) {
	return '<li><b>'.$text.'</b></li>'."\n";
}

function itemtxcl($text,$col) {
	return '<li><b style="color:'.$col.'">'.$text.'</b></li>'."\n";
}

function itemjs($text,$onclick) {
	return '<li><a href="#" onClick="'.$onclick.'">'.$text.'</a></li>'."\n";
}

?>
<ul id="mu">
<?php
	include 'getconfig.inc.php';
   $tpref=gettableprefix();

	if ($isadmin) {
		if(stristr($_SERVER['REQUEST_URI'],"studlist.php")) {
			//echo itemtx('Sch&uuml;lerliste');
			//echo item('Ausgabe','#');
			//echo '<ul id="mu">'.
			/*echo item('Wahlliste als PDF','pdf/pdf-export.php').
				item('CSV: 1. Semester','export.php?semno=1').
				item('CSV: 2. Semester','export.php?semno=2').
				item('CSV: 3. Semester','export.php?semno=3').
				item('CSV: 4. Semester','export.php?semno=4').
				item('CSV: Sportkurse','sportdaten.php').
				item('CSV: Pr&uuml;fungsf&auml;cher','exportpf.php')
				;*/
			echo item('Exporte und Listen','listen.php');
			//	'</ul>';
			/*echo item('Stammdaten','#');
			echo '<ul id="mu">'.*/
			echo item('Neuer Sch&uuml;ler','stammdaten.php');
			echo item('Einstellungen','einstell.php');
			/*	item('F&auml;cher','fachadmin.php').
				
				echo '</ul>';
			echo item('Pr&uuml;fen','#');
			echo '<ul id="mu">'.*/
			echo item('Alle pr&uuml;fen','check.php');
			//	'</ul>';
		}

		if(stristr($_SERVER['REQUEST_URI'],"stammdaten.php")) {
			if ($uid!="") {
				echo itemtx('Sie bearbeiten: '.getStudName($uid,$tpref));
			} else {
				echo itemtx('Neuer Sch&uuml;ler');
			}
			echo item('Zur&uuml;ck','studlist.php#'.$uid);
			echo itemjs ('Speichern','save();');
			echo '<li id="savenotify" style="width:320px;" align="center">'.
				'<b style="background-color:#DDDDDD;color:green;">keine &Auml;nderungen</b></li>'."\n";
			/*echo item('Aufgaben','#');
			echo '<ul id="mu">'.*/
			echo itemjs('Passwort erneuern','pwchange('.$uid.');').
				item('Zugang aktivieren','#');
				//'</ul>';
		}
		
		if(stristr($_SERVER['REQUEST_URI'],"fachadmin.php")) {
			echo item('F&auml;cherliste','#').
				item('Zur&uuml;ck','studlist.php').
				itemjs('Speichern','subjsave();');
		}
		
		if(stristr($_SERVER['REQUEST_URI'],"listen.php")) {
			echo item('Exporte und Listen','#').
				item('Zur&uuml;ck','studlist.php');
		}
	
		if(stristr($_SERVER['REQUEST_URI'],"einstell.php")) {
			echo itemtxcl('Einstellungen','black');
			echo '<ul id="mu">'.
				item('Zur&uuml;ck','studlist.php');
			echo '<li id="savenotify" style="width:320px;" align="center">'.
				'<b style="background-color:#DDDDDD;color:green;">Einstellungen gespeichert.</b>'.
				'</li>'."\n";
			echo itemjs('Speichern','settingsave();');
		}
	}

	if(stristr($_SERVER['REQUEST_URI'],"auswahl.php")
		|| stristr($_SERVER['REQUEST_URI'],"edit.php")) {
		if ($isadmin) {
			echo itemtx('Sie bearbeiten: '.getStudName($uid,$tpref));
			echo item('Zur&uuml;ck','studlist.php#'.$uid);
		} else {
			echo itemtx('Willkommen '.getStudName($uid,$tpref));
		}

		echo '<li id="savenotify" style="width:320px;" align="center">'.
    	   '<b style="background-color:#DDDDDD;color:green;">Kurswahl ist gespeichert.</b>'.
    	   '</li>'."\n";
		echo itemjs('Speichern','save();');

		if ($isadmin) {
			echo item ('Ausland, Sport, Mail, AGs','sportwahl.php?num='.$uid);
			echo item ('PDF erstellen','pdf/pdf-export.php?num='.$uid);
		} else {
			echo item ('Ausland, Sport, Mail, AGs','sportwahl.php');
		}
    }

    if(stristr($_SERVER['REQUEST_URI'],"sportwahl.php")) {
		if ($isadmin) {
			echo itemtx('Sie bearbeiten: '.getStudName($uid,$tpref));
			echo item('Zur&uuml;ck','studlist.php#'.$uid);
		} else {
			echo itemtx('Willkommen '.getStudName($uid,$tpref));
		}

		echo itemjs('Speichern','checkFinal();');

		if ($isadmin) {
			echo item('Kurswahl','auswahl.php?snr='.$uid);
		} else {
			echo item('Kurswahl','auswahl.php');
		}
    }
	
	/*if(stristr($_SERVER['REQUEST_URI'],"auswahl.php")) {
		echo itemjs('Abmelden',"close('BLA');");
	} else {
		echo item('Abmelden','logout.php');
	}*/
	echo item ('Abmelden','logout.php');

?>
</ul>
<br /><br />
