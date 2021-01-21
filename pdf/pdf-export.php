<?php

error_reporting(E_ALL & ~E_NOTICE);

include '../auth.inc.php';
include '../tools.inc.php';
include '../getconfig.inc.php';
$tpref=gettableprefix();

// korrigiert die String-Kodierung f�r die PDF-Anzeige (UTF8 -> Windows)
function encfix($str) {
	return mb_convert_encoding($str,'CP1252','UTF8');
}

// liefert einen Array mit den Indizes des Auftretens der $needle im $haystack (Array)
function occur($needle,$haystack) {
	$idx=array();
	foreach ($haystack as $k => $v) {
		if ($v==$needle) $idx[]=$k;
	}
	return $idx;
}

// erzeugt eine Tabellenzelle mit einem Punkt oder einer Beschriftung
function doCell ($p,$val,$x,$y) {
	if (is_string($val)) {
		$p->SetXY($x-2,$y+2);
		$p->Cell(0,0,$val);
		return;
	}
	if ($val!=0) {
		if ($val==1) {
			$p->image('imgs/dot.gif',$x,$y,3,3);
		} else {
			$p->SetXY($x-2,$y+2);
			if ($val==2) {
   				$p->Cell(0,0,'PRS');
   			} else {
   				$p->Cell(0,0,'BLL');
   			}
   		}
	}
}

// füllt eine Tabellenzeile mit Punkten oder Beschriftungen (benutzt doCell)
function doBlock ($p,$num,$names,$xofs,$yofs,$tw,$th) {
	global $tpref;
	
	$pf=array();
	$res=DB::get_assoc('SELECT fachkurz,pf FROM '.$tpref."waehltpf WHERE snr='$num'");
	foreach ($res as $r) {
		$pf[$r['pf']]=$r['fachkurz'];
	}

	$gk=array();
	for ($i=0;$i<count($names);$i++) {
		$gk[$names[$i]]=array();
	}
	$res=DB::get_assoc('SELECT fachkurz,sem FROM '.$tpref."waehlt WHERE snr='$num'");
	foreach ($res as $r) {
		$gk[$r['fachkurz']][]=$r['sem'];
	}

	for($b=0;$b<count($names);$b++){

		for ($t=0;$t<8;$t++) {
			$data=0;
			$pfnum=occur($names[$b],$pf);
			switch ($t) {
				case 0:
					// Leistungskurse
					// auswerten, welches PF gew�hlt wurde
					if (in_array(1,$pfnum)) $data='1. LK';
					if (in_array(2,$pfnum)) $data='2. LK';
					if (in_array(7,$pfnum)) $data='3. LK';					
					break;
				case 1:
					// 3. PF
					if (in_array(3,$pfnum)) $data=1;
					break;
				case 2:
					// 4. PF
					if (in_array(4,$pfnum)) $data=1;
					break;
				case 3:
					// 5. PK
					if (in_array(5,$pfnum))
						if (in_array('PRS',$pf))
							$data=2;
						else
							$data=3;
					break;
				case 4:
				case 5:
				case 6:
				case 7:
					// vier Punkte f�r jede Zeile, in der ein Pr�fungsfach gew�hlt wurde
					if ($pfnum!=false) $data=1;
					// GK-Semester
					if (array_key_exists($names[$b],$gk)) {
						// normale Belegung
						if (in_array ($t-3,$gk[$names[$b]])) $data=1;
						// Befreiung (z.B. Sport)
						if (in_array (0,$gk[$names[$b]])) $data='befreit';
					}
					break;
				default:
			}
			doCell ($p,$data,$xofs+$t*$tw,$yofs+$b*$th);
		}
	}
}

// erzeugt komplette Tabellenzeilen f�r die Seminarkurse
function doBlockAndCellSem ($p,$num,$xofs,$yofs,$tw,$th) {
	global $tpref;
	
	$sql='SELECT DISTINCT w.fachkurz as fk,f.lang as lg FROM '.$tpref.'waehlt w, '.
		$tpref."fach f WHERE w.fachkurz=f.kurz AND f.fachgr='ZK' AND w.snr='$num' ORDER BY f.ord";
	//echo $sql;
	$skurz=array('HDR');
	$slang=array('Sem. u. Zus.-Kurse');
	$res=DB::get_assoc($sql);
	if (!$res) return 0;
	foreach ($res as $d) {
		$skurz[]=$d['fk'];
		$slang[]=encfix($d['lg']);
	}
	for ($i=0;$i<count($skurz);$i++) {
		// Zeilen beschriften
		$p->setXY($xofs,$yofs+$i*$th);
		if ($skurz[$i]=='HDR') {
			$p->SetFont('Arial','B',10);
		} else {
			$p->SetFont('Arial','',10);
		}
    	$p->Cell(160,5,$slang[$i],0,2,'L',1);
    	// Auswahl eintragen
		if ($skurz[$i]=='HDR') continue;
		$wahl=DB::get_list('SELECT sem FROM '.$tpref."waehlt WHERE snr='$num' AND fachkurz='$skurz[$i]' ORDER BY sem");
		$wb=DB::get_value('SELECT semwaehlbar FROM '.$tpref."fach WHERE kurz='$skurz[$i]'");
		$waehlbar=explode(',',$wb);
		if (in_array('11',$waehlbar)) {
			if (in_array(1,$wahl) && !in_array(2,$wahl)) {
				doCell ($p,'ein',$xofs+7*$tw-2,$yofs+$i*$th+1);
				doCell ($p,'Sem.',$xofs+8*$tw-2,$yofs+$i*$th+1);
				continue;
			}
		}
		if (in_array('22',$waehlbar)) {
			if (in_array('1',$wahl) && !in_array('3',$wahl)) {
				doCell ($p,'zwei',$xofs+7*$tw-2,$yofs+$i*$th+1);
				doCell ($p,'Sem.',$xofs+8*$tw-2,$yofs+$i*$th+1);
				continue;
			}
		}
		foreach ($wahl as $w) {
			doCell ($p,1,$xofs+($w+6)*$tw-2,$yofs+$i*$th+1);
		}
	}
	return count($skurz);
}

// gemeinsam genutzte Größeneinstellungen
$tw=14; 	// Spaltenbreite
$th=6;  	// Zeilenhöhe
$mgl=10;	// linker Rand
$taby=25;	// vertikale Ausrichtung der Tabelle (oberer Rand)
$tofs=-10;	// vertikale Ausrichtung des Namensfeldes

define('FPDF_INSTALLDIR', 'fpdf16');

if(!defined('FPDF_FONTPATH')){
	define('FPDF_FONTPATH', 'fpdf16/font/');
}
include('fpdf16/fpdf.php');
include '../dbinterface.inc.php';

DB::connect();

$pdf=new FPDF();

// Sortierung festlegen
$sort='Name';
if (isset ($_GET['sort'])) {
	$t=$_GET['sort'];
	if ($t=='numr') $sort='sNummer';
	if ($t=='name') $sort='Name';
	if ($t=='vorn') $sort='Vorname';
}

// ist Ausdruck eines speziellen Schülers gewünscht?
if (isset($_GET['num'])) {
	$sNummern[0]=$_GET['num'];
} else {
	// nein: alle Schüler ausdrucken
	$sNummern=DB::get_list('SELECT snr FROM '.$tpref.'schueler ORDER BY klasse,name,vorname');
}

for($x=0;$x<count($sNummern);$x++){

	$infos=DB::get_assoc_row('SELECT snr,vorname,name,klasse FROM '.$tpref."schueler WHERE snr='$sNummern[$x]'" );

	$pdf->AddPage();
	$pdf->SetFont('Arial','B',14);
	$pdf->SetXY(10+$mgl,10);
	$pdf->Cell(0,0,encfix('Kurswahl für die gymnasiale Oberstufe'));

	$imgsize = getimagesize('imgs/hmbldt-logo.jpg');
	$width= $imgsize[0];
	$pdf->image('imgs/hmbldt-logo.jpg',170,6,$width/5);

	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(10+$mgl,30+$tofs);
	$pdf->Cell(0,0,'Name: '.encfix($infos['vorname']).' '.encfix($infos['name']));
	$pdf->SetXY(80+$mgl,30+$tofs);
	if ($infos['klasse']!='XXX') {
		$pdf->Cell(0,0,'Klasse: '.$infos['klasse']);
	} else {
		$pdf->Cell(0,0,'Klasse: Auslandsaufenthalt');
	}

	$pdf->SetXY(10+$mgl,34+$tofs);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,0,encfix('Schülernummer: '.$infos['snr']));

	// Fächer laden
	$erstesAF = array('Erstes Aufg.-Feld');
	$fachkurzAF1 = array();
	$res=DB::get_assoc('SELECT kurz,lang FROM '.$tpref.'fach WHERE ord<200 ORDER BY ord');
	foreach ($res as $r) {
		$erstesAF[]=encfix($r['lang']);
		$fachkurzAF1[]=$r['kurz'];
	}

	$zweitesAF = array('Zweites Aufg.-Feld');
	$fachkurzAF2 = array();
	$res=DB::get_assoc('SELECT kurz,lang FROM '.$tpref.'fach WHERE ord>199 AND ord<300 ORDER BY ord');
	foreach ($res as $r) {
		$zweitesAF[]=encfix($r['lang']);
		$fachkurzAF2[]=$r['kurz'];
	}

	$drittesAF = array('Drittes Aufg.-Feld');
	$fachkurzAF3 = array();
	$res=DB::get_assoc('SELECT kurz,lang FROM '.$tpref.'fach WHERE ord>299 AND ord<400 ORDER BY ord');
	foreach ($res as $r) {
		$drittesAF[]=encfix($r['lang']);
		$fachkurzAF3[]=$r['kurz'];
	}

	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(230,230,230);

	// Tabellenzeilen für 1. AF beschriften
	for($i=0;$i<count($erstesAF);$i++){
		if($i==0){
			$pdf->SetFont('Arial','B',10);
		}else{
			$pdf->SetFont('Arial','',10);
		}
		$pdf->setXY(10+$mgl,10+$taby+$i*$th);
		$pdf->Cell(160,5,$erstesAF[$i],0,2,'L',1);
	}

	// Tabellenzeilen für 2. AF beschriften
	for($l=0;$l<count($zweitesAF);$l++){
		if($l==0){
			$pdf->SetFont('Arial','B',10);
		}else{
			$pdf->SetFont('Arial','',10);
		}
		$pdf->setXY(10+$mgl,($i+1)*$th+7+$taby+$l*$th);
		$pdf->Cell(160,5,$zweitesAF[$l],0,2,'L',1);
	}

	// Tabellenzeilen für 3. AF beschriften
	for($m=0;$m<count($drittesAF);$m++){
		if($m==0){
			$pdf->SetFont('Arial','B',10);
		}else{
			$pdf->SetFont('Arial','',10);
		}
		$pdf->setXY(10+$mgl,($l+1)*$th+4+$taby+$m*$th+($i+1)*$th);
		$pdf->Cell(160,5,$drittesAF[$m],0,2,'L',1);
	}

	$imgsize = getimagesize('imgs/dot.gif');
	$width= $imgsize[0];
	$height= $imgsize[1];

	// gewählte Kurse markieren

	// Zeilen mit Punkten für 1. AF füllen

	$pdf->SetFont('Arial','',10);
	doBlock ($pdf,$sNummern[$x],$fachkurzAF1,49+$mgl,$taby+17,$tw,$th);

	// Zeilen mit Punkten für 2. AF füllen
	doBlock ($pdf,$sNummern[$x],$fachkurzAF2,49+$mgl,(count($fachkurzAF1)+2)*$th+$taby+14,$tw,$th);

	// Zeilen mit Punkten für 3. AF füllen
	doBlock ($pdf,$sNummern[$x],$fachkurzAF3,49+$mgl,(count($fachkurzAF2)+2)*$th+(count($fachkurzAF1)+2)*$th+$taby+11,$tw,$th);

	$starty =(count($fachkurzAF1)+2)*$th+(count($fachkurzAF2)+2)*$th+$taby+18;

	// Seminarkurse eintragen
	$semcount=doBlockAndCellSem ($pdf,$sNummern[$x],10+$mgl,$starty+$taby+$th*2,$tw,$th);

	// Spaltenbeschriftung und Spaltentrennlinien erzeugen
	$text = array('LK','3.PF','4.PF','5.PK','1.Sem','2.Sem','3.Sem','4.Sem');

	for($i=0;$i<count($text);$i++){
		$pdf->setXY(45+$i*$tw+$mgl,$taby+4);
		$pdf->Cell(20,$starty+5+$semcount*$th+($semcount>0?3:0),'','L',2,'L',0);
		$pdf->setXY(37+$i*$tw+$mgl,$taby+2);
		$pdf->Cell(20,10,$text[$i],0,'L',0);
	}

	$pdf->SetFont('Arial','',10);
	$pdf->setXY(10+$mgl,231);
	$pdf->Cell(100,5,"Anzahl gewählter Kurse: ".countcourses($sNummern[$x],$tpref),0,2,'L',0);

	// gewählte Sportkurse eintragen
	$sql=DB::get_assoc('SELECT k.langname as lg, w.lstufe as ls FROM '.$tpref.'waehltsp w, '.$tpref.'sportkurs k '.
		"WHERE w.kuerzel = k.kuerzel AND w.snr='$sNummern[$x]' ORDER BY w.sem");
	$sp_kurse=encfix("Gewünschte Sportkurse: ");
	$m=0;
	$sk=array();
	foreach ($sql as $temp) {
		$sk[$m][0]=$temp['lg'];
		$sk[$m][1]=$temp['ls'];
		$m++;
	}
	for ($m=0;$m<count($sk);$m++) {
		$sp_kurse=$sp_kurse.$sk[$m][0];
		if ($sk[$m][1]>1) $sp_kurse=$sp_kurse." (Stufe 2)";
		if ($m<count($sk)-1) $sp_kurse=$sp_kurse.", ";
	}
	
	$pdf->SetXY(10+$mgl,180);

	$pdf->setXY(10+$mgl,236);
	$pdf->Cell(100,5,$sp_kurse,0,2,'L',0);

	// Unterschriftenfeld erzeugen
	$yofs=-5;
	$pdf->setXY(10+$mgl,250+$yofs);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(100,5,'Berlin, den '.date('j.n.Y'),0,2,'L',0);
	
	$pdf->setXY(80+$mgl,250+$yofs);
	$pdf->Cell(100,5,'Unterschrift Schüler/in _____________________________',0,2,'L',0);

	$pdf->setXY(58+$mgl,260+$yofs);
	$pdf->Cell(100,5,'Unterschrift Erziehungsberechtigter _____________________________',0,2,'L',0);

	$pdf->SetFont('Arial','',8);
	$pdf->setXY(10+$mgl,265+$yofs);
	$pdf->MultiCell(0,4,encfix(getsetting('pdf_footer',$tpref)));
	
}

$pdf->Output();

?>