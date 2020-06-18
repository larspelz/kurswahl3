
saved=false;

var axobj = false;

pffields= ['lk1','lk2','pf3','pf4','lk3'];

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

function gkelem(str) {
	e=document.forms['gk'].elements[str];
	if (e==null) alert (str);
	return e;
}

function encodeForms() {
	fdata=new Array();
	pff=document.forms['pf'];
	fdata.push("snr="+pff.snr.value);
	fdata.push("lk1="+pff.lk1.value);
	fdata.push("lk2="+pff.lk2.value);
	fdata.push("pf3="+pff.pf3.value);
	fdata.push("pf4="+pff.pf4.value);
	fdata.push("pk5="+pff.pk5.value);
	fdata.push("pk5typ="+getCheckedValue(pff.elements['pk5typ']));
	fdata.push("lk3="+pff.lk3.value);

	// Array "fach" wird automatisch in header.inc.php erzeugt und enthält die Ordnungsnummern der Fächer
	for (i=0;i<fach.length;i++) {
		sel=new Array();
		for (k=1;k<5;k++) {
			field=k+"G"+fach[i];
			sel.push(gkelem(field).checked?'1':'0');
		}
		fdata.push(fach[i]+"="+sel.join(','));
	}
	return fdata.join('&');
}

// AJAX Sendefunktion
function save() {
	if (saved) return false;
	saved=true;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:green;">Kurswahl wird gespeichert!</b>';
	axobj.open('POST','esichern.php');
	params=encodeForms();
	//alert(params);
	axobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	axobj.setRequestHeader("Content-length", params.length);
	axobj.setRequestHeader("Connection", "close");
    axobj.onreadystatechange = handleContent;
    axobj.send(params);
    return false;
}

// AJAX Antwort-Betreuer (response handler)
function handleContent() {
	if (axobj.readyState == 4) {
        document.getElementById('savenotify').innerHTML = '<b style="background-color:#DDDDDD;color:green;">Kurswahl ist gespeichert!</b>';
		document.getElementById('counter').innerHTML='Kursanzahl: '+axobj.responseText;
    }
}

function sports(uid) {
if (uid=="") {
   window.location = "sportwahl.php";
} else {
   window.location = "sportwahl.php?num="+uid;
}
}

function contains (array,value) {
	for (p=0;p<array.length;p++) {
		if (array[p]==value) return true;
	}
	return false;
}

function pfelem(str) {
	return document.forms['pf'].elements[str];
}

function change() {
	saved=false;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:red;">Kurswahl ist nicht gespeichert!</b>';

}