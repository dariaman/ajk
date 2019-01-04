<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// copyright 2014
// ----------------------------------------------------------------------------------
include "includes/fu6106.php";
include_once "includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
switch ($_REQUEST['r']) {
	case "peserta":
		$metcost = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
		$metreg = mysql_fetch_array(mysql_query('SELECT fu_ajk_regional.id, fu_ajk_regional.name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
		$metarea = mysql_fetch_array(mysql_query('SELECT fu_ajk_area.id, fu_ajk_area.name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
		$metcabang = mysql_fetch_array(mysql_query('SELECT fu_ajk_cabang.id, fu_ajk_cabang.name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));

		HeaderingExcel('Laporan_Peserta.xls');
		// membuat workbook baru
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($metcost['name']);

		$formatjudul =& $workbook->add_format();	$formatjudul->set_bold();	$formatjudul->set_align('center');

		$formattitle =& $workbook->add_format();	$formattitle->set_align('center');		$formattitle->set_color('black');		$formattitle->set_bold();		$formattitle->set_pattern();	$formattitle->set_fg_color('white');
		$formattitle1 =& $workbook->add_format();	$formattitle1->set_color('black');		$formattitle1->set_pattern();			$formattitle1->set_fg_color('white');
		$formattitletgl =& $workbook->add_format();	$formattitletgl->set_align('center');	$formattitletgl->set_color('black');	$formattitletgl->set_fg_color('white');
		$format =& $workbook->add_format();			$format->set_align('vcenter');			$format->set_align('center');			$format->set_color('white');			$format->set_bold();		$format->set_pattern();			$format->set_fg_color('orange');
		$format1 =& $workbook->add_format();		$format1->set_align('right');
		$format2 =& $workbook->add_format();		$format2->set_align('center');

		$tgl1 = explode("-", $_REQUEST['start_ins']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
		$tgl2 = explode("-", $_REQUEST['end_ins']);		$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

if ($_REQUEST['nopol'])				{	$satu = 'AND id_polis = "' . $_REQUEST['nopol'] . '"';	}
if ($_REQUEST['id_reg'])			{	$dua = 'AND regional = "' . $metreg['name'] . '"';	}
if ($_REQUEST['id_area'])			{	$tiga = 'AND area = "' . $metarea['name'] . '"';	}
if ($_REQUEST['id_cabang'])			{	$empat = 'AND cabang = "' . $metcabang['name'] . '"';	}
if ($_REQUEST['speserta'])			{	$lima = 'AND status_aktif = "' . $_REQUEST['speserta'] . '"';	}
if ($_REQUEST['sdata'])				{	$enam = 'AND status_peserta = "' . $_REQUEST['sdata'] . '"';	}
if ($_REQUEST['Rpembayaran']=="3")	{	$tujuh = 'AND status_bayar != "2"';	}else{	$tujuh = 'AND status_bayar = "'.$_REQUEST['Rpembayaran'].'"';	}
if ($_REQUEST['start_ins'])			{	$delapan = 'AND vkredit_tgl BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}

$worksheet1->set_column(0, 0, 20);	$worksheet1->merge_cells(0,0,0,20);	$worksheet1->write_string(0,0,'PT. RECAPITAL (APLIKASI AJK ONLINE)', $formatjudul);
$worksheet1->set_column(1, 0, 20);	$worksheet1->merge_cells(1,0,1,20);	$worksheet1->write_string(1,0,'LAPORAN PESERTA'. $metcost['name'], $formatjudul);
$worksheet1->set_column(2, 0, 20);	$worksheet1->merge_cells(2,0,2,20);	$worksheet1->write_string(2,0,'Periode '. _convertDate($_REQUEST['start_ins']).' s/d '._convertDate($_REQUEST['end_ins']), $formatjudul);

$worksheet1->set_row(5, 15);
$worksheet1->set_column(5, 0, 5);	$worksheet1->write_string(5, 0, "NO", $format);
$worksheet1->set_column(5, 1, 15);	$worksheet1->write_string(5, 1, "POLIS", $format);
$worksheet1->set_column(5, 2, 10);	$worksheet1->write_string(5, 2, "SPAJ", $format);
$worksheet1->set_column(5, 3, 20);	$worksheet1->write_string(5, 3, "ID PESERTA", $format);
$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "NAMA", $format);
$worksheet1->set_column(5, 5, 5);	$worksheet1->write_string(5, 5, "GENDER", $format);
$worksheet1->set_column(5, 6, 5);	$worksheet1->write_string(5, 6, "DOB", $format);
$worksheet1->set_column(5, 7, 5);	$worksheet1->write_string(5, 7, "USIA", $format);
$worksheet1->set_column(5, 8, 20);	$worksheet1->write_string(5, 8, "START.INS", $format);
$worksheet1->set_column(5, 9, 10);	$worksheet1->write_string(5, 9, "END.INS", $format);
$worksheet1->set_column(5, 10, 10);	$worksheet1->write_string(5, 10, "UP", $format);
$worksheet1->set_column(5, 11, 10);	$worksheet1->write_string(5, 11, "PREMI", $format);
$worksheet1->set_column(5, 12, 20);	$worksheet1->write_string(5, 12, "DN", $format);
$worksheet1->set_column(5, 13, 20);	$worksheet1->write_string(5, 13, "CN", $format);
$worksheet1->set_column(5, 14, 10);	$worksheet1->write_string(5, 14, "NILAI", $format);
$worksheet1->set_column(5, 15, 10);	$worksheet1->write_string(5, 15, "TYPE", $format);
$worksheet1->set_column(5, 16, 10);	$worksheet1->write_string(5, 16, "PEMBAYARAN", $format);
$worksheet1->set_column(5, 17, 10);	$worksheet1->write_string(5, 17, "TGL BAYAR", $format);
$worksheet1->set_column(5, 18, 10);	$worksheet1->write_string(5, 18, "STATUS", $format);
$worksheet1->set_column(5, 19, 20);	$worksheet1->write_string(5, 19, "REGIONAL", $format);
$worksheet1->set_column(5, 20, 20);	$worksheet1->write_string(5, 20, "AREA", $format);
$worksheet1->set_column(5, 21, 20);	$worksheet1->write_string(5, 21, "CABANG", $format);

$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND id_dn!="" AND id_cost="'.$_REQUEST['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del IS NULL ORDER BY vkredit_tgl DESC');
$baris = 6;
while ($mamet = mysql_fetch_array($el))
{
	$Rpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id = "'.$mamet['id_polis'].'"'));

/*
if ($mamet['id_klaim']=="") {
	$rahmadcn = '';
	$rahmadcnnilai = '';
	$rahmadcntype = '';
}
else{
	$metdatacn = mysql_fetch_array(mysql_query('SELECT id_cn, total_claim, type_claim FROM fu_ajk_cn WHERE id_cn="'.$mamet['id_klaim'].'"'));
	$rahmadcn = $mamet['id_klaim'];
	$rahmadcnnilai = duit($metdatacn['total_claim']);
	$rahmadcntype = $metdatacn['type_claim'];
}
if ($rahmadcnnilai < 0) {		$nilaicnnya = 0;	}else{	$nilaicnnya=$rahmadcnnilai;	}
*/

$cekdn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'" AND del IS NULL'));
$cekdncn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'" AND id_peserta="'.$mamet['id_peserta'].'" AND del IS NULL'));
if ($cekdncn['total_claim'] <= 0) {	$nilaicnnya = 0;	}	else	{	$nilaicnnya = duit($cekdncn['total_claim']);	}
$cekcnpeserta = mysql_fetch_array(mysql_query('SELECT id_klaim, id_cost FROM fu_ajk_peserta WHERE id_klaim="'.$cekdncn['id_cn'].'" AND id_cost="'.$cekdncn['id_cost'].'" AND del IS NULL'));

if ($mamet['status_bayar'] == 0) {		$statusbayar = "Unpaid";	}else{	$statusbayar = "Paid";	}
	$worksheet1->write_number($baris, 0, ++$no, $format2);
	$worksheet1->write_string($baris, 1, $Rpolis['nopol'], $format2);
	$worksheet1->write_string($baris, 2, $mamet['spaj'], $format2);
	$worksheet1->write_string($baris, 3, $mamet['id_peserta'], $format2);
	$worksheet1->write_string($baris, 4, $mamet['nama']);
	$worksheet1->write_string($baris, 5, $mamet['gender']);
	$worksheet1->write_string($baris, 6, $mamet['tgl_lahir']);
	$worksheet1->write_string($baris, 7, $mamet['usia']);
	$worksheet1->write_string($baris, 8, $mamet['kredit_tgl'], $format2);
	$worksheet1->write_string($baris, 9, $mamet['kredit_akhir']);
	$worksheet1->write_string($baris, 10, duit($mamet['kredit_jumlah']), $format1);
	$worksheet1->write_string($baris, 11, duit($mamet['totalpremi']), $format1);
	$worksheet1->write_string($baris, 12, $mamet['id_dn'], $format1);
	$worksheet1->write_string($baris, 13, $cekcnpeserta['id_klaim'], $format1);
	$worksheet1->write_string($baris, 14, $nilaicnnya, $format1);
	$worksheet1->write_string($baris, 15, $mamet['status_peserta'], $format1);
	$worksheet1->write_string($baris, 16, $statusbayar);
	$worksheet1->write_string($baris, 17, $cekdn['tgl_dn_paid']);
	$worksheet1->write_string($baris, 18, $mamet['status_aktif']);
	$worksheet1->write_string($baris, 19, $mamet['regional']);
	$worksheet1->write_string($baris, 20, $mamet['area']);
	$worksheet1->write_string($baris, 21, $mamet['cabang']);
	$baris++;
}
$workbook->close();
		;
		break;

	case "pesertacn":
$metreg = mysql_fetch_array(mysql_query('SELECT fu_ajk_regional.id, fu_ajk_regional.name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
$metarea = mysql_fetch_array(mysql_query('SELECT fu_ajk_area.id, fu_ajk_area.name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
$metcabang = mysql_fetch_array(mysql_query('SELECT fu_ajk_cabang.id, fu_ajk_cabang.name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));
$metcost = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));

HeaderingExcel('Laporan_CN.xls');
// membuat workbook baru
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet($metcost['name']);

$formatjudul =& $workbook->add_format();	$formatjudul->set_bold();	$formatjudul->set_align('center');

$formattitle =& $workbook->add_format();	$formattitle->set_align('center');		$formattitle->set_color('black');		$formattitle->set_bold();		$formattitle->set_pattern();	$formattitle->set_fg_color('white');
$formattitle1 =& $workbook->add_format();	$formattitle1->set_color('black');		$formattitle1->set_pattern();			$formattitle1->set_fg_color('white');
$formattitletgl =& $workbook->add_format();	$formattitletgl->set_align('center');	$formattitletgl->set_color('black');	$formattitletgl->set_fg_color('white');
$format =& $workbook->add_format();			$format->set_align('vcenter');			$format->set_align('center');			$format->set_color('white');			$format->set_bold();		$format->set_pattern();			$format->set_fg_color('orange');
$format1 =& $workbook->add_format();		$format1->set_align('right');
$format2 =& $workbook->add_format();		$format2->set_align('center');

$tgl1 = explode("-", $_REQUEST['createcn1']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
$tgl2 = explode("-", $_REQUEST['createcn2']);		$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

if ($_REQUEST['nopol'])				{	$satu = 'AND id_nopol = "' . $_REQUEST['nopol'] . '"';	}
if ($_REQUEST['id_reg'])			{	$dua = 'AND id_regional = "' . $metreg['name'] . '"';	}
if ($_REQUEST['id_area'])			{	$tiga = 'AND id_area = "' . $metarea['name'] . '"';	}
if ($_REQUEST['id_cabang'])			{	$empat = 'AND id_cabang = "' . $metcabang['name'] . '"';	}
if ($_REQUEST['sdata'])				{	$lima = 'AND type_claim = "' . $_REQUEST['sdata'] . '"';	}
if ($_REQUEST['createcn1'])			{	$enam = 'AND tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}

		$worksheet1->set_column(0, 0, 14);	$worksheet1->merge_cells(0,0,0,14);	$worksheet1->write_string(0,0,'PT. RECAPITAL (APLIKASI AJK ONLINE)', $formatjudul);
		$worksheet1->set_column(1, 0, 14);	$worksheet1->merge_cells(1,0,1,14);	$worksheet1->write_string(1,0,'LAPORAN DATA CREDIT NOTE (CN) '. $metcost['name'], $formatjudul);
		$worksheet1->set_column(2, 0, 14);	$worksheet1->merge_cells(2,0,2,14);	$worksheet1->write_string(2,0,'Periode CN '. _convertDate($_REQUEST['createcn1']).' s/d '._convertDate($_REQUEST['createcn2']), $formatjudul);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 5);	$worksheet1->write_string(5, 0, "NO", $format);
		$worksheet1->set_column(5, 1, 15);	$worksheet1->write_string(5, 1, "POLIS", $format);
		$worksheet1->set_column(5, 2, 10);	$worksheet1->write_string(5, 2, "ID (LAMA)", $format);
		$worksheet1->set_column(5, 3, 20);	$worksheet1->write_string(5, 3, "NAMA", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "DOB", $format);
		$worksheet1->set_column(5, 5, 20);	$worksheet1->write_string(5, 5, "U P", $format);
		$worksheet1->set_column(5, 6, 10);	$worksheet1->write_string(5, 6, "D N", $format);
		$worksheet1->set_column(5, 7, 10);	$worksheet1->write_string(5, 7, "C N", $format);
		$worksheet1->set_column(5, 8, 10);	$worksheet1->write_string(5, 8, "NILAI", $format);
		$worksheet1->set_column(5, 9, 10);	$worksheet1->write_string(5, 9, "TYPE", $format);
		$worksheet1->set_column(5, 10, 10);	$worksheet1->write_string(5, 10, "ID (BARU)", $format);
		$worksheet1->set_column(5, 11, 10);	$worksheet1->write_string(5, 11, "DN (BARU)", $format);
		$worksheet1->set_column(5, 12, 10);	$worksheet1->write_string(5, 12, "UP (BARU)", $format);
		$worksheet1->set_column(5, 13, 10);	$worksheet1->write_string(5, 13, "REGIONAL", $format);
		$worksheet1->set_column(5, 14, 10);	$worksheet1->write_string(5, 14, "CABANG", $format);

		$mamet = mysql_query('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$_REQUEST['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY tgl_createcn DESC');
		$baris = 6;
		while ($erpeserta = mysql_fetch_array($mamet)) {
			$metpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id_cost="'.$erpeserta['id_cost'].'" AND id="'.$erpeserta['id_nopol'].'"'));
			$metpeserta = mysql_fetch_array(mysql_query('SELECT id_dn, id_klaim, id_peserta, nama, tgl_lahir, kredit_jumlah, totalpremi FROM fu_ajk_peserta WHERE id_cost="'.$erpeserta['id_cost'].'" AND id_klaim="'.$erpeserta['id_cn'].'" AND id_polis="'.$erpeserta['id_nopol'].'"'));
			$metpeserta_new = mysql_fetch_array(mysql_query('SELECT id_cost, id_dn, id_peserta, kredit_jumlah FROM fu_ajk_peserta WHERE id_cost="'.$erpeserta['id_cost'].'" AND  id_dn="'.$erpeserta['id_dn'].'" AND id_peserta="'.$erpeserta['id_peserta'].'"'));

			if ($erpeserta['total_claim'] < 0) {		$nilaicnnya = 0;	}else{	$nilaicnnya=$erpeserta['total_claim'];	}
			$worksheet1->write_number($baris,0,++$no, $format2);
			$worksheet1->write_string($baris,1,$metpolis['nopol'], $format2);
			$worksheet1->write_string($baris,2,"'".$metpeserta['id_peserta'], $format2);
			$worksheet1->write_string($baris,3,$metpeserta['nama'], $formatitemsL);
			$worksheet1->write_string($baris,4,$metpeserta['tgl_lahir'], $format2);
			$worksheet1->write_string($baris,5,duit($metpeserta['kredit_jumlah']), $format1);
			$worksheet1->write_string($baris,6,$metpeserta['id_dn'], $format2);
			$worksheet1->write_string($baris,7,$erpeserta['id_cn'], $format2);
			$worksheet1->write_string($baris,8,duit($nilaicnnya), $format1);
			$worksheet1->write_string($baris,9,$erpeserta['type_claim'], $format2);
			$worksheet1->write_string($baris,10,"'".$metpeserta_new['id_peserta'], $format2);
			$worksheet1->write_string($baris,11,$metpeserta_new['id_dn'], $format2);
			$worksheet1->write_string($baris,12,duit($metpeserta_new['kredit_jumlah']), $format1);
			$worksheet1->write_string($baris,13,$erpeserta['id_regional'], $format2);
			$worksheet1->write_string($baris,14,$erpeserta['id_cabang'], $format1);
			$baris++;
		}
		$workbook->close();
		;
		break;

	case "pesertadn":
		$metreg = mysql_fetch_array(mysql_query('SELECT fu_ajk_regional.id, fu_ajk_regional.name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
		$metarea = mysql_fetch_array(mysql_query('SELECT fu_ajk_area.id, fu_ajk_area.name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
		$metcabang = mysql_fetch_array(mysql_query('SELECT fu_ajk_cabang.id, fu_ajk_cabang.name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));
		$metcost = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));

		HeaderingExcel('Laporan_DN.xls');
		// membuat workbook baru
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($metcost['name']);

		$formatjudul =& $workbook->add_format();	$formatjudul->set_bold();	$formatjudul->set_align('center');

		$formattitle =& $workbook->add_format();	$formattitle->set_align('center');		$formattitle->set_color('black');		$formattitle->set_bold();		$formattitle->set_pattern();	$formattitle->set_fg_color('white');
		$formattitle1 =& $workbook->add_format();	$formattitle1->set_color('black');		$formattitle1->set_pattern();			$formattitle1->set_fg_color('white');
		$formattitletgl =& $workbook->add_format();	$formattitletgl->set_align('center');	$formattitletgl->set_color('black');	$formattitletgl->set_fg_color('white');
		$format =& $workbook->add_format();			$format->set_align('vcenter');			$format->set_align('center');			$format->set_color('white');			$format->set_bold();		$format->set_pattern();			$format->set_fg_color('orange');
		$format1 =& $workbook->add_format();		$format1->set_align('right');
		$format2 =& $workbook->add_format();		$format2->set_align('center');

		$tgl1 = explode("-", $_REQUEST['createdn1']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
		$tgl2 = explode("-", $_REQUEST['createdn2']);	$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

		if ($_REQUEST['nopol'])				{	$satu = 'AND id_nopol = "' . $_REQUEST['nopol'] . '"';	}
		if ($_REQUEST['id_reg'])			{	$dua = 'AND id_regional = "' . $metreg['name'] . '"';	}
		if ($_REQUEST['id_area'])			{	$tiga = 'AND id_area = "' . $metarea['name'] . '"';	}
		if ($_REQUEST['id_cabang'])			{	$empat = 'AND id_cabang = "' . $metcabang['name'] . '"';	}
		if ($_REQUEST['createdn1'])			{	$lima = 'AND tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}
		if ($_REQUEST['Rpembayaran']=="3")	{	$enam = 'AND dn_status != "2"';	}else{	$enam = 'AND dn_status = "'.$_REQUEST['Rpembayaran'].'"';	}


		$worksheet1->set_column(0, 0, 8);	$worksheet1->merge_cells(0,0,0,8);	$worksheet1->write_string(0,0,'PT. RECAPITAL (APLIKASI AJK ONLINE)', $formatjudul);
		$worksheet1->set_column(1, 0, 8);	$worksheet1->merge_cells(1,0,1,8);	$worksheet1->write_string(1,0,'LAPORAN DATA DEBIT NOTE (DN) '. $metcost['name'], $formatjudul);
		$worksheet1->set_column(2, 0, 8);	$worksheet1->merge_cells(2,0,2,8);	$worksheet1->write_string(2,0,'Periode DN'. _convertDate($_REQUEST['createdn1']).' s/d '._convertDate($_REQUEST['createdn2']), $formatjudul);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 5);	$worksheet1->write_string(5, 0, "NO", $format);
		$worksheet1->set_column(5, 1, 15);	$worksheet1->write_string(5, 1, "POLIS", $format);
		$worksheet1->set_column(5, 2, 10);	$worksheet1->write_string(5, 2, "DN", $format);
		$worksheet1->set_column(5, 3, 20);	$worksheet1->write_string(5, 3, "PREMI", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "TGL DN", $format);
		$worksheet1->set_column(5, 5, 20);	$worksheet1->write_string(5, 5, "STATUS", $format);
		$worksheet1->set_column(5, 6, 10);	$worksheet1->write_string(5, 6, "TGL BAYAR", $format);
		$worksheet1->set_column(5, 7, 10);	$worksheet1->write_string(5, 7, "REGIONAL", $format);
		$worksheet1->set_column(5, 8, 10);	$worksheet1->write_string(5, 8, "CABANG", $format);

		$mamet = mysql_query('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_cost="'.$_REQUEST['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY tgl_createdn DESC');
		$baris = 6;
		while ($erpeserta = mysql_fetch_array($mamet)) {
			$metpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id_cost="'.$_REQUEST['id_cost'].'" AND id="'.$erpeserta['id_nopol'].'"'));
			$worksheet1->write_number($baris,0,++$no, $format2);
			$worksheet1->write_string($baris,1,$metpolis['nopol'], $format2);
			$worksheet1->write_string($baris,2,$erpeserta['dn_kode'], $format2);
			$worksheet1->write_string($baris,3,duit($erpeserta['totalpremi']), $format1);
			$worksheet1->write_string($baris,4,_convertDate($erpeserta['tgl_createdn']), $format2);
			$worksheet1->write_string($baris,5,$erpeserta['dn_status'], $format2);
			$worksheet1->write_string($baris,6,_convertDate($erpeserta['tgl_dn_paid']), $format2);
			$worksheet1->write_string($baris,7,$erpeserta['id_regional']);
			$worksheet1->write_string($baris,8,$erpeserta['id_cabang']);
			$baris++;
		}
		$workbook->close();
		;
		break;

	default:
		;
} // switch
function duit($value)
{
	$orro = number_format($value, 0, ',', '.');
	return $orro;
}

function _convertDate($date)
{
	if (empty($date))
		return null;

	$date = explode("-", $date);
	return
	$date[2] . '-' . $date[1] . '-' . $date[0];
}
?>
