
saved=true;

var xmlHttpObject = false;

// Überprüfen ob XMLHttpRequest-Klasse vorhanden und erzeugen von Objekte für IE7, Firefox, etc.
if (typeof XMLHttpRequest != 'undefined') {
    xmlHttpObject = new XMLHttpRequest();
}

// Wenn im oberen Block noch kein Objekt erzeugt, dann versuche XMLHTTP-Objekt zu erzeugen
// Notwendig für IE6 oder IE5
if (!xmlHttpObject) {
    try {
        xmlHttpObject = new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e) {
        try {
            xmlHttpObject = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {
            xmlHttpObject = null;
        }
    }
}

function encodeForms() {
	fdata=new Array();
	f=document.forms['sdata'];
	if (!isInt(f.snr.value)) {
		f.snr.focus();
		alert("Schuelernummer muss eine natuerliche Zahl sein!");
		f.snr.value="";
		return "";
	}
	fdata.push("snr="+f.snr.value);
	fdata.push("name="+f.name.value);
	fdata.push("vorname="+f.vorname.value);
	fdata.push("prof1="+f.prof1.value);
	fdata.push("prof2="+f.prof2.value);
	fdata.push("klasse="+f.klasse.value);
	fdata.push("oldsnr="+f.oldsnr.value);
	fdata.push("kursadd="+f.kursadd.value);
	fdata.push("mail="+f.mail.value);
	return fdata.join('&');
}

// AJAX Sendefunktion
function save() {
	if (saved) return false;
	params=encodeForms();
	if (params=="") return false;
	saved=true;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:yellow;">speichere...</b>';
	xmlHttpObject.open('POST','stammsichern.php');
	xmlHttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttpObject.onreadystatechange = handleContent;
    xmlHttpObject.send(params);
    return false;
}

function save_debug() {
	alert(encodeForms());
}

// AJAX Antwort-Betreuer (response handler)
function handleContent() {
	if (xmlHttpObject.readyState == 4) {
		var rt=xmlHttpObject.responseText;
		var ch=false;
		rt=rt.split(';');
		if (rt.indexOf("SNRFAIL")!=-1) {
			alert ("Schuelernummer existiert bereits!");
			ch=true;
		}
		if (rt.indexOf("MAILFAIL")!=-1) {
			alert ("E-Mail-Adresse ist fehlerhaft!");
			ch=true;
		}
		if (rt.indexOf("PWDOK")!=-1) 
			alert ("Passwort geaendert");
		if (ch) {
			change();
			return;
		}
		document.getElementById('savenotify').innerHTML = '<b style="background-color:#DDDDDD;color:green;">keine &Auml;nderungen</b>';
		//document.getElementById('debug').innerHTML = rt;
		// alte Schuelernummer aktualisieren
		f=document.forms['sdata'];
		f.oldsnr.value=f.snr.value;
    }
}

function contains (array,value) {
	for (p=0;p<array.length;p++) {
		if (array[p]==value) return true;
	}
	return false;
}

function pwchange(snr) {
	if (snr=="") {
		alert ("Bitte erst Schueler aufrufen!");
		return;
	}
	xmlHttpObject.open('POST','stammsichern.php');
	pw=pwgen();
	alert ("Das neue Passwort lautet: "+pw);
	params=new Array();
	params.push("mode=pw");
	params.push("snr="+snr);
	params.push("pw="+pw);
	p=params.join("&");
	xmlHttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttpObject.setRequestHeader("Content-length", p.length);
	xmlHttpObject.setRequestHeader("Connection", "close");
    xmlHttpObject.onreadystatechange = handleContent;
    xmlHttpObject.send(p);
    return false;
}

function pwgen() {
    var len = 8,
        chars = "abcdefghijknopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789",
        res = "";
    for (var i = 0, n = chars.length; i < len; ++i) {
        res += chars.charAt(Math.floor(Math.random() * n));
    }
    return res;
}

function isInt(n) {
   return n == parseInt(n,10) ;
}

function change() {
	saved=false;
	f=document.forms['sdata'];
	if (!isInt(f.snr.value)) {
		f.snr.focus();
		alert("Schuelernummer muss eine natuerliche Zahl sein!");
		f.snr.value="";
	} 
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:red;">&Auml;nderungen nicht gespeichert!</b>';
}