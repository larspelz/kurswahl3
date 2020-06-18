<?php
$pdfff = new pf();
class pf{
function __construct(){
	define('FPDF_INSTALLDIR', 'fpdf16');

	if(!defined('FPDF_FONTPATH')){ 
		define('FPDF_FONTPATH', 'fpdf16/font/');
		}
	include('fpdf16/fpdf.php');

	$p = new FPDF();

	$p->AddPage();
	$p->SetFont('Arial','BUI',16);
	$p->Cell(40,10,'Kurswahl');
	$p->SetFont('Arial','',14);
	$imgsize = getimagesize('imgs/hmbldt-logo.jpg');
	$width= $imgsize[0];
	$height= $imgsize[1];
	$p->Cell(40,10,'Das haben Sie gewählt:');
	$p->image('imgs/hmbldt-logo.jpg',165,11,$height/4,$with/4);
	$p->Output();
	}
}
?>