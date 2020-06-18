
saved=false;

var axobj = false;

pffields= ['lk1','lk2','pf3','pf4','pk5','lk3'];
pfold=['no','no','no','no','no','no','no'];
pfcolors=['#FF0000','#FFC39F','#C0FFC0','#C0C0FF','#FFCCAA',''];

if (typeof XMLHttpRequest != 'undefined') {
    axobj = new XMLHttpRequest();
}

if (!axobj) {
    try {
        axobj = new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e) {
        try {
            axobj = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {
            axobj = null;
        }
    }
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function encodeForms() {
	pff=document.forms['pf'];
	fdata="";
	fdata+="snr="+pff.snr.value+"&";
	fdata+="lk1="+pff.lk1.value+"&";
	fdata+="lk2="+pff.lk2.value+"&";
	fdata+="pf3="+pff.pf3.value+"&";
	fdata+="pf4="+pff.pf4.value+"&";
	fdata+="pk5="+pff.pk5.value+"&";
	fdata+="pk5typ="+getCheckedValue(pff.elements['pk5typ'])+"&";
	fdata+="lk3="+pff.lk3.value+"&";

	// Array "fach" wird automatisch in header.inc.php erzeugt und enthält die Ordnungsnummern der Fächer
	// als belegt angezeigte Prüfungsfächer werden mit übertragen
	// und serverseitig ausgefiltert
	for (i=0;i<fach.length-1;i++) {
		field="G"+fach[i];
		fdata+=fach[i]+"="+gkelem(field).value+"&";
	}
	field="G"+fach[fach.length-1];
	fdata+=fach[fach.length-1]+"="+gkelem(field).value;
	//DEBUG: alert(fdata);
	return fdata;
}

// AJAX Sendefunktion
function save() {
	if (saved) return false;
//	if (!check()) return false;
	saved=true;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:#002288;">SPEICHERN</b>';
	axobj.open('POST','wsichern.php');
	params=encodeForms();
	axobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    axobj.onreadystatechange = handleContent;
    axobj.send(params);
    return false;
}

// AJAX Antwort-Betreuer (response handler)
function handleContent() {
	if (axobj.readyState == 4) {
        document.getElementById('savenotify').innerHTML = '<b style="background-color:#DDDDDD;color:green;">Kurswahl ist gespeichert.</b>';
		document.getElementById('info').innerHTML = decodeinfo(axobj.responseText);
//		alert(axobj.responseText);
    }
}

function info(wh) {
	if (wh=='cnt') alert('Es muessen 40 (ohne 3. LK) oder 37 (mit 3. LK) Kurse gewaehlt werden.');
	if (wh=='af2') alert('Es muss mindestens ein Pruefungsfach (1-5) aus dem Aufgabenfeld 2 gewaehlt werden.');
	if (wh=='af3') alert('Es muss mindestens ein Pruefungsfach (1-5) aus dem Aufgabenfeld 3 gewaehlt werden. Sport zaehlt nicht zum AF3!');
	if (wh=='pf') alert('Die Faecher Deutsch, Mathematik und eine Fremdsprache muessen in den '+
		'Pruefungsfaechern vertreten sein.');
	if (wh=='de4') alert('Deutsch muss vier Semester lang belegt werden.');
	if (wh=='ma4') alert('Mathematik muss vier Semester lang belegt werden.');
	if (wh=='fs4') alert('Eine Fremsprache muss vier Semester lang belegt werden.');
	if (wh=='nw') alert('Physik oder Chemie muss fuer vier Semester belegt werden. Alternativ kann Biologie '+
		'fuer vier Semester belegt werden, dann muss entweder Physik oder Chemie zusaetzlich fuer '+
		'zwei Semester belegt werden.');
	if (wh=='kf2') alert('Ein Kunstfach muss fuer zwei Semester belegt werden.');
	if (wh=='pks') alert('Es darf nur ein Fach aus Kunst, Musik und Sport im 3. und 4. Pruefungsfach sein.');
	if (wh=='prs') alert('Wenn das Referenzfach der Fuenften Pruefungskomponente ein Pruefungsfach ist, '+
		'muss eine BLL geschrieben werden.');
	if (wh=='rf4') alert('Das Referenzfach der Fuenften Pruefungskomponente muss fuer vier Semester '+
		'belegt werden.');
	if (wh=='wl4') alert('Wenn Wirtschaft das Referenzfach der 5.PK ist, muss es als Pruefungsfach '+
		'belegt werden.');
	if (wh=='spt') alert('Wenn Sport ein Pruefungsfach ist, dann muss auch Sport-Theorie belegt werden.');	
	if (wh=='nost') alert('Sport-Theorie darf nur belegt werden, wenn Sport ein Pruefungsfach ist.');
	if (wh=='nogk') alert('Es muessen Grundkurse gewaehlt werden.');
	if (wh=='nopf') alert('Die Pruefungsfaecher sind nicht vollstaendig belegt.');
	if (wh=='pw34') alert('Wenn Geschichte ein Pruefungsfach ist, dann muss PW im 3. und 4. Semester oder '+
		'anderes Fach aus dem Aufgabenfeld 2 fuer 4 Semester belegt werden.');
	if (wh=='ge34') alert('Geschichte muss mindestens im 3. und 4. Semester belegt werden.');
	if (wh=='wlp34') alert('Falls Wirtschaft als 3. oder 4. Pruefungsfach gewaehlt wird, muss es als 3.'+
		' Leistungskurs gewaehlt werden.');
	if (wh=='inp34') alert('Falls Informatik als 3. oder 4. Pruefungsfach gewaehlt wird, muss es als 3.'+
		' Leistungskurs gewaehlt werden.');
	if (wh=='nosp') alert('Bitte waehlen Sie Ihre gewuenschten Sportkurse im Sport-/Ausland-Menue!');
	if (wh=='spw') alert('Sie muessen vier Semester Sport belegen!');
}

function errtext(wh) {
	if (wh=='cnt') return 'Kursanzahl';
	if (wh=='af2') return 'PF aus AF2 fehlt';
	if (wh=='af3') return 'PF aus AF3 fehlt';
	if (wh=='pf') return 'DE, MA, FS in PF';
	if (wh=='de4') return 'DE muss 4 Sem';
	if (wh=='ma4') return 'MA muss 4 Sem';
	if (wh=='fs4') return 'FS muss 4 Sem';
	if (wh=='nw') return 'NatWiss Belegung';
	if (wh=='kf2') return 'KunstFach muss 2 Sem';
	if (wh=='pks') return 'Ku/Mu/Sport in PF';
	if (wh=='prs') return '5.PK Referenzfach';
	if (wh=='rf4') return '5.PK Referenzfach';
	if (wh=='wl4') return 'Wirtschaft als 5PK';
	if (wh=='spt') return 'Sport Theorie';
	if (wh=='nost') return 'Sport Theorie';
	if (wh=='nogk') return 'Grundkurse';
	if (wh=='nopf') return 'Pr&uuml;fungsf&auml;cher';
	if (wh=='pw34') return 'PW im 3.u.4. Sem.';
	if (wh=='ge34') return 'Geschichte';
	if (wh=='wlp34') return 'Wirtschaft muss 3.LK';
	if (wh=='inp34') return 'Informatik muss 3.LK';
	if (wh=='nosp') return 'Sportkurse';
	if (wh=='spw') return 'Sportbelegung';
	return wh;
}

function decodeinfo(res) {
	tokens=res.split(',');
	inf="Anzahl Kurse: "+tokens[0];
	if (tokens.length>1)
		inf+=' &nbsp;&nbsp;&nbsp;<b style="color:red;">Hinweise:</b>';
	else 
		inf+=' -- Danke, die Kurswahl scheint fehlerfrei zu sein.';
	for (a=1;a<tokens.length;a++) {
		//info+=' <a href="#" onClick="info(\''+tokens[a]+'\');">'+tokens[a]+'</a>';
		inf+=" <a href=\"#\" onClick=\"info(\'"+tokens[a]+"\');\">"+errtext(tokens[a])+'</a>&nbsp;&nbsp;&nbsp;';
	}
	return inf;
}

function pfelem(str) {
	return document.forms['pf'].elements[str];
}

function gkelem(str) {
	return document.forms['gk'].elements[str];
}

function getpfnum(which) {
	for (i=0;i<pffields.length;i++) {
		if (which==pffields[i]) return i;
	}
}

function restorecolor(num) {
  if (pfold[num]!='no') {
	if (document.getElementById("C"+pfold[num])==null) return;
	rcolor=pfcolors[document.getElementById("G"+pfold[num]).name[1]];
	document.getElementById("C"+pfold[num]).bgColor=rcolor;
	document.getElementById("G"+pfold[num]).value='no';
	document.getElementById("G"+pfold[num]).disabled=false;
  }
}

function changepf(which) {
	// f = fachkurz des Faches, das als PF gewählt wurde
	f=pfelem(which).value;
	num=getpfnum(which);
	if (f=='no') {
		ds=false; // flag zur Feststellung der Doppelbelegung
		if ((which=='pk5') || (which=='lk3')) {
			for (a=0;a<pffields.length;a++) {
				if (which==pffields[a]) continue;
				fachchk=pfelem(pffields[a]).value;
				if (pfold[num]==fachchk) {
					ds=true;
				}
			}
		}
		if (!ds) restorecolor(num);
		pfold[num]='no';
		change();
		return;
	}
	if (which!='pk5') {
		// prüfen, ob Prüfungsfach zweimal gewählt, dann löschen
		doublesel=false;
		for (a=0;a<pffields.length;a++) {
		
			if (pffields[a]=='pk5') continue; // 5. PF darf doppelt
			
				// 3. LK darf doppelt, falls sonst als 3. oder 4. PF gewählt
			if ((which=='lk3') && (pffields[a]=='pf3' || pffields[a]=='pf4')) continue;
			if ((which=='pf3') && pffields[a]=='lk3') continue; 
			if ((which=='pf4') && pffields[a]=='lk3') continue; 
			
			pfsel=pfelem(pffields[a]);
			if (which==pffields[a]) continue;
			if (f==pfsel.value) {
				doublesel=true;
				pfelem(which).value='no';
				// evtl. vorher deaktiviertes Auswahlfeld aktivieren, Farbe wiederherstellen
				restorecolor(num);
				pfold[num]='no';
			}		
		}
		if (doublesel) {
			alert ('Ein Pruefungsfach darf nicht doppelt belegt werden.');
			change();
			return;
		}
	}

	// GK-Auswahlfelder verändern falls vorhanden
	if (document.getElementById("C"+f)!=null && document.getElementById("G"+f)!=null) {
	   document.getElementById("C"+f).bgColor='#CCCCCC';
	   document.getElementById("G"+f).value='44';
	   document.getElementById("G"+f).disabled=true;
	}
	// vorher deaktiviertes Auswahlfeld aktivieren, Farbe wiederherstellen
	ds=false;
	if ((which=='pk5') || (which=='lk3')) {
		for (a=0;a<pffields.length;a++) {
			if (which==pffields[a]) continue;
			fachchk=pfelem(pffields[a]).value;
			if (pfold[num]==fachchk) {
				ds=true;
			}
		}
	}
	if (!ds) restorecolor(num);
	pfold[num]=f;
	change();
}

function changegk(lang,kurz) {
	change();
}

function change() {
	saved=false;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:red;">Kurswahl ist nicht gespeichert!</b>';
	/*cc=countcourses();
	document.getElementById('counter').innerHTML='Anzahl gew&auml;hlter Kurse: '+cc;
	*/
}

function setup() {
	for (i=0;i<pffields.length;i++) {
		f=pfelem(pffields[i]).value;
		if (f!='no') {
		   pfold[i]=f;
		   // GK-Auswahlfeld deaktivieren
		   if (document.getElementById("C"+f)!=null) {
			   document.getElementById("C"+f).bgColor='#CCCCCC';
			   document.getElementById("G"+f).value='44';
			   document.getElementById("G"+f).disabled=true;
		   }
		}
	}
}

function close(url) {
	if (!saved) {
		ok=confirm ("Die Kurswahl ist nicht gespeichert.\r\nWollen Sie die Seite trotzdem verlassen?");
		if (ok) document.href=url; else return false;
		//alert("Bitte die Kurswahl speichern \r\n oder den Browser schliessen!");
		//return false;
	}
	document.href=url;
	return true;
}