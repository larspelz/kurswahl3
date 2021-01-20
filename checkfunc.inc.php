<?php

include 'predicates.inc.php';
include 'tools.inc.php';

function check($snr,$mode,$tpref) {

	if ($mode!='db' && $mode!='export' && $mode!='stud') return;

    // Faecherinfo laden (siehe pdf-export.php)
	$af2=DB::get_list('SELECT kurz FROM '.$tpref.'fach WHERE ord>199 AND ord<300 ORDER BY ord');
	
	$af3=DB::get_list('SELECT kurz FROM '.$tpref.'fach WHERE ord>299 AND ord<400 AND kurz NOT IN (\'SP\',\'ST\') ORDER BY ord');
	
	$fs=DB::get_list('SELECT kurz FROM '.$tpref.'fach WHERE fachgr=\'FS\' ORDER BY ord');
	
	$nw=DB::get_list('SELECT kurz FROM '.$tpref.'fach WHERE fachgr=\'NW\' ORDER BY ord');
	
	$kf=DB::get_list('SELECT kurz FROM '.$tpref.'fach WHERE fachgr=\'KF\' ORDER BY ord');

	// Pruefungen durchfuehren
	foreach ($snr as $n) {
		// Fehlerliste vorbereiten
		$error=array();

		// Pruefungsfaecher laden
		$pf=DB::get_list('SELECT fachkurz FROM '.$tpref."waehltpf WHERE snr='$n' ORDER BY pf");

		if (count($pf)==0) $error[]='nopf';

		// andere Belegung laden
		// $gk [fachkurz] [semester], siehe auswahl.php
		$tmp=DB::get_assoc('SELECT fachkurz,sem FROM '.$tpref."waehlt WHERE snr='$n'");
		$gk=array();
		foreach ($tmp as $t) {
			$gk[$t['fachkurz']][]=$t['sem'];
		}
		if (count($gk)==0) $error[]='nogk';
		
		// Sportkurse laden (nur prüfen, ob welche vorhanden sind)
		$spcount=DB::get_value('SELECT COUNT(*) FROM '.$tpref."waehltsp WHERE snr='$n'");
		if ($spcount[0]<1) $error[]='nosp';

		// wenn alles richtig geladen...
		if (count ($error)==0 || $error[0]=='nosp') {
		
			for ($p=0;$p<5;$p++) {
				if ($pf[$p]=='no') {
					$error[]='nopf';
					break;
				}
			}

			// Kursanzahl prüfen
			$Pcnt=countcourses($n,$tpref);
			$kursadd=DB::get_value('SELECT kursadd FROM '.$tpref."schueler WHERE snr='$n'");
			
			if ($pf[6]!='no') {
				if ($Pcnt!=(37+$kursadd) && $Pcnt!=(38+$kursadd)) $error[]='cnt';
			} else {
				if ($Pcnt!=(40+$kursadd)) $error[]='cnt';
			}

			// Praedikate auswerten
			$Pde=isFachInPF14('DE',$pf);   // Pde
			$Pma=isFachInPF14('MA',$pf);  // Pma
			$Pfs=isFSInPF14($fs,$pf);	   // Pfs

			$Paf2=isPF15fromAF($af2,$pf);
			$Paf3=isPF15fromAF($af3,$pf);
			$Pbi=isFachSem('BI',4,$gk,$pf);
			$Pnw2=isPhOrChSem2($gk);
			$Pnw4=isPhOrChSem4($gk,$pf);
			$Pkf2=isKFSem2($kf,$gk,$pf);
			$Pku=isFachInPF34('KU',$pf);
			$Pmu=isFachInPF34('MU',$pf);
			$Psp=isFachInPF34('SP',$pf);
			$Psp4=isFachSem('SP',4,$gk,$pf);  // Sport belegt?
			$Prefpf=isFachInPF14($pf[4],$pf);  // 5PK-Ref-Fach in PF1-4?
			$Pref4=isFachSem($pf[4],4,$gk,$pf);  // 5PK-Ref-Fach 4 Semester belegt?
			$Pspp=isFachInPF15('SP',$pf); // Sport in Prüfungsfächern?
			$Pspt=isFachSem('ST',2,$gk,$pf); // Sport-Theorie gewählt?
			
			$Pgpf=isFachInPF15('GE',$pf);	// Geschichte in PF1-5?
			$Pgekl3=isFachLK3('GE',$pf); // Geschichte 3. LK?
			$Ppw34=isFach34('PW',$gk); // PW mindestens im 3. und 4. Sem?
			$Pfaf2=isFachFromAFSem($af2,4,$gk,$pf);
			$Pge34=isFach34('GE',$gk); // Geschichte im 3.4. Sem?

			if ($Pde+$Pma+$Pfs<2) $error[]='pf';	// 1)

			if ($Paf2==0) $error[]='af2';   // 2)
			if ($Paf3==0) $error[]='af3';	 // 2)

			if ($Pde==0 && isFachSem('DE',4,$gk,$pf)==0 && isFachLK3('DE',$pf)==0) $error[]='de4';  // 3)
			if ($Pma==0 && isFachSem('MA',4,$gk,$pf)==0 && isFachLK3('MA',$pf)==0) $error[]='ma4';  // 3)
			if ($Pfs==0 && isFsSem4($fs,$gk)==0 && isFSLK3($fs,$pf)==0) $error[]='fs4';      // 3)

			if ($Pnw4==0 && ($Pbi==0 || $Pnw2==0)) $error[]='nw'; // 4)

			if ($Pkf2==0) $error[]='kf2';	// 5
	
			if ($Pku+$Pmu+$Psp>1) $error[]='pks'; // 6
			//echo $Pku+$Pmu+$Psp;

			if (($Prefpf==1) && ($pf[5]=='PRS')) $error[]='prs';  // 7

			if (($Prefpf==0) && ($Pref4==0)) $error[]='rf4';  // 8
			//if (($Prefpf==0) && ($pf[4]=='WL')) $error[]='wl4';  // HACK für Wirtschaft, weil WL nicht als GK gewählt werden kann

			if (($Pspp==1) && ($Pspt==0)) $error[]='spt'; // wenn Sport in PF und kein ST gewählt
			
			if (($Psp4==0) && (!isNoSport($gk))) $error[]='spw'; // kein Sport gewählt
			
			//if (($Pspp==0) && ($Pspt==1)) $error[]='nost'; // wenn Sport nicht in PF und ST gewählt
			
			if ($Pgpf==0 && $Pgekl3==0) {
				if ($Pge34==0) $error[]='ge34';
			} else {
				if ($Ppw34+$Pfaf2<1) $error[]='pw34';
			}
			
			// falls WL oder IN als 3. Prüfungsfach gewählt wurden, muss
			// WL oder IN als 3. LK gewählt werden
			if (isFachInPF34('WL',$pf)==1 && isFachLK3('WL',$pf)==0) $error[]='wlp34';
			if (isFachInPF34('IN',$pf)==1 && isFachLK3('IN',$pf)==0) $error[]='inp34';

		} // if count($pf)

		if ($mode=='db' || $mode=='stud') {
			if (count($error)>0)
				DB::query ('UPDATE '.$tpref."schueler SET kwfehler='".implode(', ',$error)."' WHERE snr='$n'");
			else
				DB::query ('UPDATE '.$tpref."schueler SET kwfehler='ok' WHERE snr='$n'");
		}
	}

	if ($mode=='export') {
		return $error;
	}

	if ($mode=='stud') {
		return implode(',',$error);
	}
}