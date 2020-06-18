<?php
   
   function writeOptionsPF($faecher,$beleg,$pfnr) {
      for ($i=0;$i<count($faecher);$i++) {
	     $sel='';
		 if (isset($beleg) && $beleg[$pfnr]==$faecher[$i]['kurz']) {
		    $sel='selected';
		 }
         echo '<option value="'.$faecher[$i]['kurz'].'" '.$sel.'>'.$faecher[$i]['lang'].'</option>';
      }
   }
   
   // Farbdefinitionen für farbliche Absetzung der Aufgabenfelder in der Grundkurswahl
   function getAFcolor ($which) {
		switch ($which) {
				case 1: return '#FFC39F';
				case 2: return '#C0FFC0';
				case 3: return '#C0C0FF';
				case 4: return '#FFFFC0';
				default: return 'white';
			}
   }

include 'auth.inc.php';

if (!$isadmin) {
	header( 'Location: index.php' );
}

$uid=$_GET['num'];
if (!isset($uid)) {
	header( 'Location: index.php' );
}

include 'dbconnect.inc.php';
include 'header.inc.php';
include 'menu.inc.php';
include 'edinit.inc.php';

?>
<TABLE BORDER="1" CELLPADDING="5" CELLSPACING="3">
      <!-- 1. Spalte: Pruefungsfaecher -->
	<tr>
      <td valign="top">
        <b>Pr&uuml;fungsf&auml;cher</b>

        <form name="pf">
		<input type="hidden" name="snr" value="<?php echo $uid; ?>">
          <table cellpadding ="5">
            <tr>
              <td>1. Leistungskurs</td>

              <td><select name="lk1" onChange="change();">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

		<?php
		   writeOptionsPF($fach_lk1,$fach_pf,1);
		?>

              </select></td>
            </tr>

            <tr>
              <td>2. Leistungskurs</td>

              <td><select name="lk2" onChange="change();">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

		<?php
		   writeOptionsPF($fach_lk2,$fach_pf,2);
		?>

              </select></td>
            </tr>

<tr>
              <td>3. Pr&uuml;fungsfach</td>

              <td><select name="pf3" onChange="change();">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

		<?php
		   writeOptionsPF($fach_pf3,$fach_pf,3);
		?>

              </select></td>
            </tr>

<tr>
              <td>4. Pr&uuml;fungsfach</td>

              <td><select name="pf4" onChange="change();">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

		<?php
		   writeOptionsPF($fach_pf4,$fach_pf,4);
		?>

              </select></td>
            </tr>

            <tr>
              <td>5. Pr&uuml;fungskomponente</td><td>
		<table>

<?php
		$prs='';
		$bll='';
		if ($fach_pf[6]=='BLL') {
		    $bll=' checked';
		} else {
			$prs=' checked';
		}
?>
		<tr><td><input type="radio" name="pk5typ" value="BLL" onChange="change();" <?php echo ($bll); ?>>BLL
		<input type="radio" name="pk5typ" value="PRS" onChange="change();" <?php echo ($prs); ?>>Pr&auml;sentation
		</td></tr>
	        <tr><td>
		<select name="pk5" onChange="change();">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

		<?php
		   writeOptionsPF($fach_pk5,$fach_pf,5);
		?>

              </select>
	      </td></tr></table>
		  
		  <tr>
		  <td><b>3. Leistungskurs</b></td>
		  <td>
				<select id="lk3" onChange="change();">
                <option selected value="no">
                  M&ouml;chte ich nicht!
                </option>

				<?php
					writeOptionsPF($fach_lk3,$fach_pf,7);
				?>

              </select></td>
		  </tr>
            </td>
            </tr>
			<tr><td><div id="counter">Kursanzahl: 
			<?php
				include 'tools.inc.php';
				echo countcourses($uid,$tpref);
			?>
			</div></td></tr>
          </table>
        </form>
		
</td><td><!-- 2. Spalte: Grundkurse -->

<form name="gk">
<b>Fachwahlen (ohne Pr&uuml;fungsf&auml;cher!)</b>
<table>
<?php
	for ($i=0;$i<count($fach_gk);$i++) {
		$bgcolor=getAFcolor($fach_gk[$i]['ord'][0]);

		echo '<tr><td bgcolor="'.$bgcolor.
			'">'.$fach_gk[$i]['lang'].'</td>';
			
		// gewählte Semester in $sem speichern
		if ((count($fach_wahl)>0) && (array_key_exists($fach_gk[$i]['kurz'],$fach_wahl))) {
				$sem=$fach_wahl[$fach_gk[$i]['kurz']]; // $sem ist ein Array!
			} else {
				$sem=array();
			}
		for ($k=1;$k<5;$k++) {
			echo '<td><input type="checkbox" name="'.$k.'G'.$fach_gk[$i]['ord'].'" onChange="change();"';
			if (in_array(''.$k,$sem)) echo ' checked>'; else echo '>';
		}
		echo '</tr>'."\n";
	}
?>				
</table>
</td></tr></table>
</form>

<?php include 'footer.inc.php'; ?>