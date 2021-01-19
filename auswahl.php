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

   include 'dbinterface.inc.php';
   include 'auth.inc.php';
   include 'getconfig.inc.php';
   $tpref=gettableprefix();

   if ($isadmin) {
   		if (isset($_GET['snr'])) {
			$uid=$_GET['snr'];
		} else {
			header( 'Location: studlist.php' );
			exit();
		}

   }    
   
   DB::connect();

   include 'header.inc.php';
   include 'menu.inc.php';
   
   include 'edinit.inc.php';

?>
   <TABLE BORDER="1" CELLPADDING="3" CELLSPACING="3">
    <!-- 1. Zeile: Kurszaehler -->
	<tr><td colspan="4">
		<?php
			include 'tools.inc.php';
			echo '<div id="info">Kursanzahl: '.countcourses($uid,$tpref).'</div>';
		?>

	</td>
	</tr>
    <!-- 2. Zeile: Pruefungsfaecher -->
	<tr>
      <td valign="top" colspan="4">
        <b>Pr&uuml;fungsf&auml;cher</b>
	  </td></tr><tr><td colspan="5" align="center">
        <form name="pf">
		<input type="hidden" name="snr" value="<?php echo $uid; ?>">
          <table cellpadding ="2" width="100%"> <!-- TODO: Better style for this table -->
            <tr>
				<td align="center">1. Leistungskurs</td>
				<td align="center">2. Leistungskurs</td>
				<td align="center">3. Pr&uuml;fungsfach</td>
				<td align="center">4. Pr&uuml;fungsfach</td>
				<td align="center">5. Pr&uuml;fungs-<br>komponente</td>
				<td align="center">Dritter<br>Leistungskurs</td>
			</tr>
			<tr>

              <td align="center">
				<select name="lk1" onChange="changepf('lk1');">
                <option value="no">
                  -- Bitte w&auml;hlen! --
                </option>

				<?php
					writeOptionsPF($fach_lk1,$fach_pf,1);
				?>

              </select></td>

              <td align="center">
				<select name="lk2" onChange="changepf('lk2');">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

				<?php
					writeOptionsPF($fach_lk2,$fach_pf,2);
				?>

              </select></td>

              <td align="center">
				<select name="pf3" onChange="changepf('pf3');">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

				<?php
					writeOptionsPF($fach_pf3,$fach_pf,3);
				?>

              </select></td>

              <td align="center">
				<select name="pf4" onChange="changepf('pf4');">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

				<?php
					writeOptionsPF($fach_pf4,$fach_pf,4);
				?>

				</select></td>
			<td  align="center">
			<table> <!-- Beginn 5PK Auswahl -->

			<?php
				$prs='';
				$bll='';
				if ($fach_pf[6]=='BLL') {
					$bll='checked';
				} else {
					$prs='checked';
				}
			?>
			<tr>
			<td align="center">
			<input type="radio" name="pk5typ" value="BLL" onChange="change();" <?php echo ($bll); ?>>BLL
			<input type="radio" name="pk5typ" value="PRS" onChange="change();" <?php echo ($prs); ?>>Pr&auml;sentation
			</td></tr>
				<tr><td align="center">
			<select name="pk5" onChange="changepf('pk5');">
                <option selected value="no">
                  -- Bitte w&auml;hlen! --
                </option>

				<?php
					writeOptionsPF($fach_pk5,$fach_pf,5);
				?>

              </select>
	      </td></tr>
	     </table> <!-- Ende 5PK Auswahl -->
            </td>

			<td align="center">
				<select id="lk3" onChange="changepf('lk3');">
                <option selected value="no">
                  M&ouml;chte ich nicht!
                </option>

				<?php
					writeOptionsPF($fach_lk3,$fach_pf,7);
				?>

              </select></td>
           </tr>
          </table> <!-- Ende Pruefungsfach-Eingabeformular -->
        </form>

      </td><!-- 3. Zeile: Grundkurse -->
	</tr>
	<tr>
      <td valign="top" colspan="4">
        <b>Grundkurse</b>
	  </td>
	</tr>
	<tr>
	<td valign="top">
        <form name="gk">
          <table cellpadding ="3"><tr>

		<?php
		  $oo='';
		  for ($i=0;$i<count($fach_gk);$i++) {

			// bei Wechsel des Aufgabenfeldes (ord[0])
			// Tabelle beenden und neue öffnen
		    $o=$fach_gk[$i]['ord'][0];
			if (($i>0) && ($o!=$oo)) echo '</tr></table></td>'.
					'<td valign="top"><table cellpadding="3"><tr>';
			$oo=$o;

			// Bestimmung der Zellen-Hintergrundfarbe
			$bgcolor=getAFcolor($o);

			// Erzeugen der Zelle mit Fachname ---
			echo '<td id="C'.$fach_gk[$i]['kurz'].'" bgcolor="'.
				//($fach_gk[$i]['kurz']=='SP' || $fach_gk[$i]['kurz']=='ST'?'#FFCCAA':$bgcolor)
				$bgcolor.
				'">'.$fach_gk[$i]['lang'].'</td>';

			// Erzeugen des Auswahlfeldes --------

			// deaktiviertes Feld erzeugen, falls als PF gewählt
			echo '<td><select id="G'.$fach_gk[$i]['kurz'].'"'.
					        ' name="G'.$fach_gk[$i]['ord'].'"'.
							' onChange="changegk(\''.$fach_gk[$i]['lang'].'\','.
							'\''.$fach_gk[$i]['kurz'].'\');">';

			if (array_key_exists($fach_gk[$i]['kurz'],$fach_wahl)) {
				$sem=$fach_wahl[$fach_gk[$i]['kurz']]; // $sem ist ein Array!
			} else {
				unset($sem);
			}

			// Wählbarkeit und ausgewählte Option des Faches auswerten
			$options=array();
			$waehlbar=explode(',',$fach_gk[$i]['semwaehlbar']);

			// Falls keine Wahl vorliegt, "kein" als ausgewählte Option einfügen
			// sonst als wählbare Option einfügen
			if (!isset($sem))
				$options[]='<option selected value="no">kein</option>';
			else
				$options[]='<option value="no">kein</option>';

			// Optionen erzeugen, gewählte Option (falls vorhanden) voreinstellen
			for ($k=0;$k<count($waehlbar);$k++) {
				// HACK: Wie kann das hier verallgemeinern?
				// 1+2 Semester gewählt
				if ($waehlbar[$k]=='12' ) {
					if ( isset($sem) && in_array('1',$sem) && !in_array('4',$sem) ) {
					     $options[]='<option selected value="12">1. & 2. Sem.</option>';
					} else {
					     $options[]='<option value="12">1. & 2. Sem.</option>';
					}
				}
				// 3+4 Semester gewählt
				if ($waehlbar[$k]=='34' ) {
					if ( isset($sem) && in_array('4',$sem) && !in_array('1',$sem) ) {
					     $options[]='<option selected value="34">3. & 4. Sem.</option>';
					} else {
					     $options[]='<option value="34">3. & 4. Sem.</option>';
					}
				}
				// alle Semester gewählt
				if ($waehlbar[$k]=='44' ) {
					if ( isset($sem) && in_array('1',$sem) && in_array('4',$sem) ) {
					     $options[]='<option selected value="44">1.-4. Sem.</option>';
					} else {
					     $options[]='<option value="44">1.-4. Sem.</option>';
					}
				}
				// für zwei Semester gewählt
				if ($waehlbar[$k]=='22' ) {
					if ( isset($sem) && in_array('1',$sem) && in_array('2',$sem) ) {
					     $options[]='<option selected value="22">zwei Sem.</option>';
					} else {
					     $options[]='<option value="22">zwei Sem.</option>';
					}
				}
				// für ein Semester gewählt
				if ($waehlbar[$k]=='11' ) {
					if ( isset($sem) && in_array('1',$sem) && !in_array('2',$sem) ) {
					     $options[]='<option selected value="11">ein Sem.</option>';
					} else {
					     $options[]='<option value="11">ein Sem.</option>';
					}
				}
				// 2+3 Semester gewählt
				if ($waehlbar[$k]=='23' ) {
					if ( isset($sem) && in_array('2',$sem) && !in_array('1',$sem) ) {
					     $options[]='<option selected value="23">2. & 3. Sem.</option>';
					} else {
					     $options[]='<option value="23">2. & 3. Sem.</option>';
					}
				}
				// einzelnes Semester gewählt
				for ($l=1;$l<5;$l++) {
					if ($waehlbar[$k]==$l ) {
						if ( isset($sem) && in_array($l,$sem) && count($sem)<2 ) {
							$options[]='<option selected value="'.$l.'">'.$l.'. Sem.</option>';
						} else {
							$options[]='<option value="'.$l.'">'.$l.'. Sem.</option>';
						}
					}
				}
				// Befreiung = 0
				if ($waehlbar[$k]=='0' ) {
					if ( isset($sem) && in_array('0',$sem) ) {
						$options[]='<option selected value="0">befreit</option>';
					} else {
						$options[]='<option value="0">befreit</option>';
					}
				}
			}
			for ($k=0;$k<count($options);$k++) {
				echo $options[$k]."\n";
			}
	        echo'</select></td></tr>';
		  }
		?>
          </table>
        </form>
      </td>

	  </tr>
  </table>

  <div id="debug"></div>
<br><br><br>
<?php

include 'footer.inc.php';

?>