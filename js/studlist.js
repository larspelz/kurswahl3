function deletestudent(num) {
	if (confirm('Wirklich?')) {
		window.location='studlist.php?del='+num;
	} else {
		return false;
	}
}