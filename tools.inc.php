<?php 

// Kurse von der DB zählen lassen
function countcourses($snr,$tpref) {
    $gkanz=DB::get_value('SELECT COUNT(fachkurz) FROM '.$tpref.'waehlt WHERE (NOT sem=0) AND snr='.$snr); // sem=0 0=> Sportbefreiung
	
	// pf=5 => 5. Prüfungskomponente, nicht mitzählen!
	// pf=6 => Eintrag für BLL oder Präsentation, nicht mitzählen!
    $pf=DB::get_list('SELECT fachkurz FROM '.$tpref.'waehltpf WHERE pf IN (1,2,3,4,7) AND NOT fachkurz=\'no\' AND snr='.$snr);
	if (count($pf)==0) return $gkanz;
	return $gkanz+count(array_unique($pf))*4;
}

function check_email_address($email) {
		// First, we check that there's one @ symbol, 
		// and that the lengths are right.
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters 
			// in one section or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&".	
				"'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i])) {
				return false;
			}
		}
		// Check if domain is IP. If not, 
		// it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}

?>