axobj = false;

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

function frmel(str) {
	ret=document.forms['subj'].elements[str];
	if (ret==null) alert (str);
	return ret;
}

function encodeCBs(frm,letter,count) {
	enc="";
	for (p=0;p<count;p++) {
		enc+=letter+p+"="+(frmel(""+letter+p).checked?'1':'0')+"&";
	}
	return enc;
}

function encodeInputs(frm,letter,count) {
	enc="";
	// TODO: encode HTML entities!
	for (p=0;p<count;p++) {
		enc+=letter+p+"="+frmel(""+letter+p).value+"&";
	}
	return enc;
}

function encodeForm() {
	alert ("BUP");
	ff=document.forms['subj'];
	cnt=ff.count.value-1;
	cbs=new Array('A','B','C','D','E','F');
	inputs=new Array('K','W','O','L','G');
	fdata="";
	for (a=0;a<cbs.length;a++) {
		fdata+=encodeCBs(ff,cbs[a],cnt);
	}
	for (a=0;a<inputs.length;a++) {
		fdata+=encodeInputs(ff,inputs[a],cnt);
	}
	fdata+="count="+cnt;
	alert(fdata);
	alert("BLA");
	return fdata;
}

// AJAX Sendefunktion
function subjsave() {
	axobj.open('POST','fsichern.php');
	params=encodeForm();
	axobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	axobj.setRequestHeader("Content-length", params.length);
	axobj.setRequestHeader("Connection", "close");
    axobj.onreadystatechange = hresp;
    //axobj.send(params);
    return false;
}

// AJAX Antwort-Betreuer
function hresp() {
}
