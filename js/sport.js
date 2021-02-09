function logout() {
window.location = "logout.php";
}

	function elem(str) {
		return document.forms['f'].elements[str];
	}

	function elemv(str) {
		return document.forms['f'].elements[str].value;
	}

	function kursnam(str) {
		return kurs=elem(str).options[elem(str).selectedIndex].text;
	}

	function check(which) {
		console.log('ACKÄ');
		let valid=true;
		let partner=0;
		for (let i=1;i<6;i++) {
			// hack for strings not being evaluated in FF
			let kw='k'+which;
			let ki='k'+i;
			let lw='ls'+which;
			let li='ls'+i;

			// same index is ignored
			if (i==which) continue;

			// 'none' is ignored
		   	if (elemv(ki)=='none') continue;

		   	if (elemv(ki)==elemv(kw)) {
			   	// found a course that was chosen two times
				
				// handle courses which can be selected only once
		   		if (elem(kw).value=='KL') {// FIXME: DIRTY hack!
		   			alert (unescape('Der Kurs Klettern kann nur einmal belegt werden.'));
		   			elem(kw).selectedIndex=0;
		   			elem(lw).selectedIndex=0;
		   			elem(kw).focus();
		   			valid=false;
		   			break;
		   		}

		   		// current course hasn't got a partner yet
		   		if (partner==0) {
					partner=i;
					console.log('lw '+elem(lw).selectedIndex);
					console.log('li '+elem(li).selectedIndex);   
		   			// check whether current course hasn't got level 2 grading selected yet
		   			if (elem(lw).selectedIndex!=1) {
		   				// check whether partner hasn't got level 2 grading selected
		   				if (elem(li).selectedIndex!=1) {
		   					// alert student to choose level 2 grading for current course
		   					alert (unescape('Sie haben den Kurs '+kursnam('k'+i)+' bereits einmal ausgew%E4hlt.\n'+
		   						'Es wird die h%F6here Leistungsstufe f%FCr diesen Kurs gew%E4hlt.'));
		   					elem(lw).selectedIndex=1;
		   					elem(lw).focus();
		   					valid=false;
						}
					}
					if (elem(lw).selectedIndex==1 && elem(li).selectedIndex==1) {
						alert (unescape('Sie haben f%FCr den Kurs '+kursnam('k'+i)+' bereits bereits \n die hohe Leistungsstufe ausgew%E4hlt.\n'+
							'Es wird die geringere\nLeistungsstufe f%FCr diesen Kurs gew%E4hlt.'));
						elem(lw).selectedIndex=0;
						elem(lw).focus();
						valid=false;
					}
		   		} else {
		   			// course was selected more than two times
		   			alert (unescape('Sie k%F6nnen einen Kurs nicht\nmehr als zweimal belegen ('+kursnam(ki)+').'));
		   			elem(kw).selectedIndex=0;
		   			elem(lw).selectedIndex=0;
		   			elem(kw).focus();
		   			valid=false;
		   			break;
		   		}
		   	}
		}
		return valid;
	}

	function checkFinal() {
		for (let i=1;i<6;i++) {
			if (!check(i)) return;
		}
		let dat=new Object();
		for (let i=1;i<6;i++) {
			// HACK for strings not being evaluated in FF
			let ki='k'+i;
			let li='ls'+i;
			// check whether this course is different from the default
			elemki=elemv(ki);
			if (elemki=='none') {
				alert(unescape('Bitte f%FCnf Sportkurse ausw%E4hlen!'));
				elem(ki).focus();
				return;
			}
			// count course occurrence
			if (dat[elemki]) {
				if (dat[elemki]=='1') dat[elemki]='2';
			} else {
				dat[elemki]='1';
			}
		}
		for (let i=1;i<6;i++) {
			// HACK for strings not being evaluated in FF
			let ki='k'+i;
			let li='ls'+i;
			let elemki=elemv(ki);
			// check whether all single courses have level 1 grading
			if (dat[elemki]=='1') {
				if (elem(li).selectedIndex==1) {
					alert(unescape('Der Kurs '+kursnam(ki)+' wurde nur einmal gew%E4hlt \n'+
						'und kann daher nur in der 1. Leistungsstufe \n'+
						'belegt werden.'));
					elem(li).selectedIndex=0;
					elem(li).focus();
					return;
				}
			}
			/*// TODO: check whether double courses have level 1 + level 2 grading
			if (dat[elemki]=='2') {

			}*/
		}
		//alert ('Submit');
		document.forms.f.submit();
	}