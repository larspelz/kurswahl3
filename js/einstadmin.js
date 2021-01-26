axobj = false;
saved=true;

if (typeof XMLHttpRequest != 'undefined') {
    axobj = new XMLHttpRequest();
}

if (!axobj) {
    try {
        axobj = new ActiveXObject("Msxml2.XMLHTTP");
    } catch(ex) {
        try {
            axobj = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(ex) {
            axobj = null;
        }
    }
}

function encodeForm() {
	frm=document.forms['settings'];
	fdata="pdf_footer="+frm.pdf_footer.value;
	//fdata+="&sys_motd="+frm.sys_motd.value;
	//fdata+="&sys_school="+frm.sys_school.value;
	return fdata;
}

// AJAX Sendefunktion
function settingsave() {
	axobj.open('POST','einstsichern.php');
	params=encodeForm();
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:#002288;">Speichert...</b>';
	axobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	axobj.setRequestHeader("Content-length", params.length);
	axobj.setRequestHeader("Connection", "close");
    axobj.onreadystatechange = hresp;
    axobj.send(params);
	
	saved=true;
	
    return false;
}

// AJAX Antwort-Betreuer
function hresp() {
	if (axobj.readyState == 4) {
        document.getElementById('savenotify').innerHTML = '<b style="background-color:#DDDDDD;color:green;">Einstellungen gespeichert.</b>';
	} else {
		document.getElementById('savenotify').innerHTML = '<b style="background-color:#DDDDDD;color:yellow;">Fehler.</b>';
	}
}

function change() {
	saved=false;
	document.getElementById('savenotify').innerHTML='<b style="background-color:#DDDDDD;color:red;">Einstellungen nicht gespeichert!</b>';
}