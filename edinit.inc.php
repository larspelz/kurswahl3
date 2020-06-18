<?php
	
	include 'getconfig.inc.php';
   $tpref=gettableprefix();
	
   function loadFach($sel,$tpref) {
      $res=mysql_query ('SELECT kurz,lang,ord,semwaehlbar FROM '.$tpref.'fach WHERE '.$sel.' ORDER BY ord');
	  if (!$res) echo 'loadFach fail: '.$sel;
      $ret=array();
      while ($data=mysql_fetch_assoc($res)) {
         $ret[]=$data;
/*		 // Umlaute berichtigen
		 $ret[count($ret)-1]['lang']=htmlentities($ret[count($ret)-1]['lang']);*/
      }
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
   $res=mysql_query('SELECT fachkurz,sem FROM '.$tpref.'waehlt WHERE snr=\''.$uid.'\'');
   while ($data=mysql_fetch_assoc($res)) {
	  $fach_wahl[$data['fachkurz']][]=$data['sem'];
   }

   // Prüfungsfächerwahl laden
   $res=mysql_query('SELECT fachkurz,pf FROM '.$tpref.'waehltpf WHERE snr=\''.$uid.'\'');
   $fach_pf=array();
   for ($l=0;$l<8;$l++) $fach_pf[$l]='';
   if ($res) {
      while ($data=mysql_fetch_assoc($res)) {
	     $fach_pf[$data['pf']]=$data['fachkurz'];
      }
   }
   
?>