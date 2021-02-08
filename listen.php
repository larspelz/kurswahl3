<?php

	include 'dbinterface.inc.php';
	DB::connect();

	include 'auth.inc.php';
	include 'header.inc.php';
	include 'menu.inc.php';
	
	?>
<div id="cont">
<ul id="mu">
<b>PDF</b><br><br>
<li><a href="pdf/pdf-export.php">Wahlliste als PDF</a></li>
<br><br><b>CSV</b><br><br>
<li><a href="export.php?semno=1">Untis: 1. Semester</a></li>
<li><a href="export.php?semno=2">Untis: 2. Semester</a></li>
<li><a href="export.php?semno=3">Untis: 3. Semester</a></li>
<li><a href="export.php?semno=4">Untis: 4. Semester</a></li><br><br>
<li><a href="sportdaten.php">Untis: Sportkurse</a></li>
<li><a href="exportpf.php?table=laufb1314_2">Untis: Pr&uuml;fungsf&auml;cher</a></li><br><br>
<li><a href="semdiff.php?sem=12">Excel: Differenzliste Sem. 1/2</a>
<li><a href="semdiff.php?sem=23">Excel: Differenzliste Sem. 2/3</a>
<li><a href="semdiff.php?sem=34">Excel: Differenzliste Sem. 3/4</a></li><br><br>
</ul>
<br><br>
</div>
<?php include 'footer.inc.php'; ?>