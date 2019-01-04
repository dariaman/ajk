<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
require('fpdf.php');
include_once "includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');

include "includes/fu6106.php";
$futgl = date("d M Y");
$futglojk = date("d-m-Y");
$futglreas = date("ymd");

function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
HeaderingExcel('Rekonsel_Peserta_'.$_REQUEST['tanggal3'].'-'.$_REQUEST['tanggal4'].'.xls');

// membuat workbook baru
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Data Peserta Rekonsel');

$format =& $workbook->add_format();
$format->set_align('vcenter');
$format->set_align('center');
$format->set_color('white');
$format->set_bold();
$format->set_pattern();
$format->set_fg_color('orange');

// membuat header tabel dengan format
$worksheet1->set_row(0, 15);
$worksheet1->set_column(0, 0, 5);	$worksheet1->write_string(0, 0, "NO", $format);
$worksheet1->set_column(0, 1, 10);	$worksheet1->write_string(0, 1, "SPAJ", $format);
$worksheet1->set_column(0, 2, 12);	$worksheet1->write_string(0, 2, "POLIS", $format);
$worksheet1->set_column(0, 3, 10);	$worksheet1->write_string(0, 3, "DEBIT NOTE", $format);
$worksheet1->set_column(0, 4, 20);	$worksheet1->write_string(0, 4, "TGL DN", $format);
$worksheet1->set_column(0, 5, 10);	$worksheet1->write_string(0, 5, "ID PESERTA", $format);
$worksheet1->set_column(0, 6, 30);	$worksheet1->write_string(0, 6, "NAMA", $format);
$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "GENDER", $format);
$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "DOB", $format);
$worksheet1->set_column(0, 9, 5);	$worksheet1->write_string(0, 9, "USIA", $format);
$worksheet1->set_column(0, 10, 10);	$worksheet1->write_string(0, 10, "AWAL KREDIT", $format);
$worksheet1->set_column(0, 11, 5);	$worksheet1->write_string(0, 11, "TENOR", $format);
$worksheet1->set_column(0, 12, 10);	$worksheet1->write_string(0, 12, "AKHIR KREDIT", $format);
$worksheet1->set_column(0, 13, 20);	$worksheet1->write_string(0, 13, "U P", $format);
$worksheet1->set_column(0, 14, 15);	$worksheet1->write_string(0, 14, "PREMI", $format);
$worksheet1->set_column(0, 15, 10);	$worksheet1->write_string(0, 15, "ADM", $format);
$worksheet1->set_column(0, 16, 10);	$worksheet1->write_string(0, 16, "ext_PREMI", $format);
$worksheet1->set_column(0, 17, 20);	$worksheet1->write_string(0, 17, "TOTAL PREMI", $format);
$worksheet1->set_column(0, 18, 10);	$worksheet1->write_string(0, 18, "PAID/UNPAID", $format);
$worksheet1->set_column(0, 19, 10);	$worksheet1->write_string(0, 19, "STATUS", $format);
$worksheet1->set_column(0, 20, 10);	$worksheet1->write_string(0, 20, "CREDIT NOTE", $format);
$worksheet1->set_column(0, 21, 30);	$worksheet1->write_string(0, 21, "REGIONAL", $format);
$worksheet1->set_column(0, 22, 30);	$worksheet1->write_string(0, 22, "AREA", $format);
$worksheet1->set_column(0, 23, 30);	$worksheet1->write_string(0, 23, "CABANG", $format);

// membuat header file excel dan nama filenya

$tgl1 = explode("/", $_REQUEST['tanggal3']);	$tglawal1 = $_REQUEST['tanggal3'];
$tgl2 = explode("/", $_REQUEST['tanggal4']);	$tglawal2 = $_REQUEST['tanggal4'];
if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['tanggal3'])		{	$dua = 'AND vkredit_tgl BETWEEN "'.$tglawal1.'" AND "'.$tglawal2.'" ';	}

$el = mysql_query('SELECT * FROM fu_ajk_peserta_rekonsel WHERE id != "" '.$satu.' '.$dua.' ORDER BY vkredit_tgl DESC, id_dn DESC');
$baris = 1;
while ($mamet = mysql_fetch_array($el))
{
	$metpolisnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_polis'].'"'));

	if ($mamet['status_bayar']==0) {	$statusnya = 'Unpaid';	}else{	$statusnya = 'Paid';	}

	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['client']);
	$worksheet1->write_string($baris, 2, $metpolisnya['nopol']);
	$worksheet1->write_string($baris, 3, $mamet['id_dn']);
	$worksheet1->write_string($baris, 4, $mamet['tgl_createdn']);
	$worksheet1->write_string($baris, 5, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 6, $mamet['nama']);
	$worksheet1->write_string($baris, 7, $mamet['gender']);
	$worksheet1->write_string($baris, 8, $mamet['tgl_lahir']);
	$worksheet1->write_string($baris, 9, $mamet['usia']);
	$worksheet1->write_string($baris, 10, $mamet['kredit_tgl']);
	$worksheet1->write_number($baris, 11, $mamet['kredit_tenor']);
	$worksheet1->write_string($baris, 12, $mamet['kredit_akhir']);
	$worksheet1->write_number($baris, 13, $mamet['kredit_jumlah']);
	$worksheet1->write_number($baris, 14, $mamet['premi']);
	$worksheet1->write_number($baris, 15, $mamet['biaya_adm']);
	$worksheet1->write_number($baris, 16, $mamet['ext_premi']);
	$worksheet1->write_number($baris, 17, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 18, $statusnya);
	$worksheet1->write_string($baris, 19, $mamet['status_aktif']);
	$worksheet1->write_string($baris, 20, $mamet['id_klaim']);
	$worksheet1->write_string($baris, 21, $mamet['regional']);
	$worksheet1->write_string($baris, 22, $mamet['area']);
	$worksheet1->write_string($baris, 23, $mamet['cabang']);
	$baris++;
}
$workbook->close();

?>