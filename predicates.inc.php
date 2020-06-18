<?php

	/**
	 * @param $fach zu überprüfendes Fach
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @return 1 falls $fach in $pf[0..3] enthalten ist
	 */
	function isFachInPF14($fach,$pf) {
		for ($i=0;$i<4;$i++) {
			if ($pf[$i]==$fach) return 1;
		}
		return 0;
	}
	
	/**
	 * @param $fach zu überprüfendes Fach
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @return 1 falls $fach in $pf[0..4] enthalten ist
	 */
	function isFachInPF15($fach,$pf) {
		for ($i=0;$i<5;$i++) {
			if ($pf[$i]==$fach) return 1;
		}
		return 0;
	}
	
	/**
	 * @param $fach zu überprüfendes Fach
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @return 1 falls $fach in $pf[6] enthalten ist
	 */
	function isFachLK3($fach,$pf) {
		if ($pf[6]==$fach) return 1;
		return 0;
	}
	
	/**
	 * @param $fach zu überprüfendes Fach
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @return 1 falls $fach in $pf[2..3] enthalten ist
	 */
	function isFachInPF34($fach,$pf) {
		for ($i=2;$i<4;$i++) {
			if ($pf[$i]==$fach) return 1;
		}
		return 0;
	}

	/**
	 * @param $fs Liste der Fremdsprachenfächer
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @returns 1 falls ein Element aus $pf[0..4] in $fs enthalten ist
	 */
	function isFSInPF14($fs,$pf) {
		for ($i=0;$i<count($fs);$i++) {
			for ($j=0;$j<4;$j++) {
				if ($fs[$i]==$pf[$j]) return 1;
			}
		}
		return 0;
	}
	
	/**
	 * @param $fs Liste der Fremdsprachenfächer
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @returns 1 falls ein Element aus $fs in $pf[6] gefunden wird
	 */
	function isFSLK3($fs,$pf) {
		for ($i=0;$i<count($fs);$i++) {
			if ($fs[$i]==$pf[6]) return 1;
		}
		return 0;
	}

	/**
	 * @param $faecher Liste der Fächer eines Aufgabenfeldes
	 * @param $pf Liste der gewählten Prüfungsfächer
	 * @returns 1 falls ein Element aus $pf[0..4] in $faecher enthalten ist
	 */
	function isPF15fromAF($faecher,$pf) {
		foreach ($faecher as $fach) {
			for ($j=0;$j<5;$j++) {
				if ($fach==$pf[$j]) return 1;
			}
		}
		return 0;
	}

	/**
	 * @param $fach zu prüfendes Fach
	 * @param $sem Anzahl zu belegender Semester
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @returns 1 falls Fach in $sem Semestern belegt
	 */
	function isFachSem($fach,$sem,$gk,$pf) {
		if ($sem==4) {
			// in Prüfungsfächern belegt?
			foreach (array(0,1,2,3,4,6) as $i) {
				if (!isset($pf[$i])) continue;
				if ($pf[$i]==$fach) return 1;
			}
		}
		if (!isset($gk[$fach])) return 0;
		if (count($gk[$fach])==$sem) return 1; else return 0;
	}
	
	/**
	 * @param $fach zu prüfendes Fach
	 * @param $sem Anzahl zu belegender Semester
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @returns 1 falls Fach aus einem bestimmten Aufgabenfeld in $sem Semestern belegt
	 */
	function isFachFromAFSem($af2,$sem,$gk,$pf) {
		for ($i=0;$i<count($af2);$i++) {
			if ($af2[$i]=='GE') continue;
			if (isFachSem($af2[$i],$sem,$gk,$pf)==1) return 1;
		}
		return 0;
	}
	
	/**
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @param $f zu prüfendes Fach
	 * @returns 1 falls Fach $f in Semestern 3 und 4 belegt
	 */
	function isFach34($f,$gk) {
		if (!isset($gk[$f])) return 0;
		if (in_array('3',$gk[$f]) && in_array('4',$gk[$f])) return 1; else return 0;
	}

	/**
	 * @param $fs Liste der Fremdsprachen
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @returns 1 falls mindestens eine Fremdsprache für 4 Semester belegt
	 */
	function isFsSem4($fs,$gk) {
		for ($i=0;$i<count($fs);$i++) {
			if (array_key_exists($fs[$i],$gk))
				if (count($gk[$fs[$i]])==4) return 1;
		}
		return 0;
	}

	/**
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @returns 1 falls PH oder CH in min. zwei Semestern belegt
	 */
	function isPhOrChSem2($gk) {
		if (isset($gk['PH']) && (count($gk['PH'])>=2)) return 1;
		if (isset($gk['CH']) && (count($gk['CH'])>=2)) return 1;
		return 0;
	}

	/**
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @param $pf Liste der Prüfungsfächer
	 * @returns 1 falls PH oder CH in vier Semestern belegt
	 */
	function isPhOrChSem4($gk,$pf) {
		// in Prüfungsfächern?
		foreach (array(0,1,2,3,4,6) as $i) {
				if ($pf[$i]=='CH') return 1;
				if ($pf[$i]=='PH') return 1;
			}
		// in Grundkursen?
		if (isset($gk['PH']) && (count($gk['PH'])==4)) return 1;
		if (isset($gk['CH']) && (count($gk['CH'])==4)) return 1;
		return 0;
	}

	/**
	 * @param $kf Liste der Kunstfächer
	 * @param $gk Belegungsmatrix der Grundkurse
	 * @param $pf Prüfungsfächer
	 * @returns 1 falls Kunstfach min 2. Semester belegt?
	 */
	function isKFSem2($kf,$gk,$pf) {
		// in Prüfungsfächern? + LK3!
		for ($i=0;$i<count($kf);$i++) {
			foreach (array(0,1,2,3,4,6) as $j) {
				if ($kf[$i]==$pf[$j]) return 1;
			}
		}
		// Grundkursbelegung?
		for ($i=0;$i<count($kf);$i++) {
			if (!isset($gk[$kf[$i]])) continue;
			if (count($gk[$kf[$i]])>=2) return 1;
		}
		return 0;
	}

/**
* @param $gk Belegungsmatrix der Grundkurse
* @returns true, falls eine Sportbefreiung ausgewählt wurde
*/
function isNoSport($gk) {
	if (!array_key_exists('SP',$gk)) return false;
    return in_array(0,$gk['SP']);
}


?>