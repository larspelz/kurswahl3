<?php
	
	include 'getconfig.inc.php';

//	DB::connect();	
	//echo DB::sqli(); 
	if (DB::sqli()==NULL) die ('edinit: no sqli');
	
   $tpref=gettableprefix();
	
   function loadFach($sel,$tpref) {
   	//if (!isset(DB::$sqli)) die ('load: no sqli');
      $ret=DB::get_assoc ('SELECT kurz,lang,ord,semwaehlbar FROM '.$tpref.'fach WHERE '.$sel.' ORDER BY ord');
	   //if (!$ret) echo 'loadFach fail: '.$sel.' '.$tpref;
      return $ret;
   }

   // Fächerlisten laden
   $fach_lk1=loadFach("kannLK1=1",$tpref);
   $fach_lk2=loadFach("kannLK2=1",$tpref);
   $fach_pf3=loadFach("kannPF3=1",$tpref);
   $fach_pf4=loadFach("kannPF4=1",$tpref);
   $fach_pk5=loadFach("kann5PK=1",$tpref);
   $fach_lk3=loadFach("(kannLK1=1 OR kannLK2=1) AND semwaehlbar LIKE '%44%'",$tpref);

   $fach_gk=loadFach("kannGK=1",$tpref);

   $fach_wahl=array();
   // Belegung der Grundkurse laden
   $temp=DB::get_assoc('SELECT fachkurz,sem FROM '.$tpref."waehlt WHERE snr='$uid'");
   //echo '<br>temp '.$temp.' '.gettype($temp).'<br>';
   if ($temp) {
	   foreach ($temp as $t) {
		  $fach_wahl[$t['fachkurz']][]=$t['sem'];
	   }
	}

   // Prüfungsfächerwahl laden
   $temp=DB::get_assoc('SELECT fachkurz,pf FROM '.$tpref."waehltpf WHERE snr='$uid'");
   $fach_pf=array();
   for ($l=0;$l<8;$l++) $fach_pf[$l]='';
   if ($res) {
      foreach ($temp as $t) {
	     $fach_pf[$t['pf']]=$t['fachkurz'];
      }
   }
   
?>