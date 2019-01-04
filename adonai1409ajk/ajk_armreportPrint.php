<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright AJK ONLINE 2013
// ----------------------------------------------------------------------------------
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');

// koneksi ke mysql
include "../includes/fu6106.php";
switch ($_REQUEST['armreport']) {
	case "datepayment":
		// function untuk membuat header file excel
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

		HeaderingExcel('ARM-'.$_REQUEST['tgl1'].'-'.$_REQUEST['tgl2'].'-byDatePayment.xls');

		// membuat workbook baru
		$workbook = new Workbook("");
		// membuat worksheet ke-1 (data laki-laki)
		$worksheet1 =& $workbook->add_worksheet('PAID');

		// setting format header tabel data
		$format =& $workbook->add_format();
		$format->set_align('vcenter');
		$format->set_color('white');
		$format->set_bold();
		//$format->set_italic();
		$format->set_pattern();
		$format->set_fg_color('orange');

		// membuat header tabel dengan format
		$worksheet1->set_row(0, 15);
		$worksheet1->set_column(0, 0, 30);	$worksheet1->write_string(0, 0, "NAMA PERUSAHAAN", $format);
		$worksheet1->set_column(0, 1, 12);	$worksheet1->write_string(0, 1, "NO. POLIS", $format);
		$worksheet1->set_column(0, 2, 10);	$worksheet1->write_string(0, 2, "NOMOR DN", $format);
		$worksheet1->set_column(0, 3, 10);	$worksheet1->write_string(0, 3, "TANGGAL DN", $format);
		$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "DATE PAYMENT", $format);
		$worksheet1->set_column(0, 5, 10);	$worksheet1->write_string(0, 5, "STATUS PAID", $format);
		$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "PREMI", $format);
		$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "NOMOR CN", $format);
		$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "NILAI CN", $format);
		$worksheet1->set_column(0, 9, 10);	$worksheet1->write_string(0, 9, "NETT PREMI", $format);
		$worksheet1->set_column(0, 10, 10);	$worksheet1->write_string(0, 10, "STATUS DATA", $format);
		$worksheet1->set_column(0, 11, 15);	$worksheet1->write_string(0, 11, "REGIONAL", $format);
		$worksheet1->set_column(0, 12, 15);	$worksheet1->write_string(0, 12, "AREA", $format);
		$worksheet1->set_column(0, 13, 20);	$worksheet1->write_string(0, 13, "CABANG", $format);

		$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
		$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
		// membuat header file excel dan nama filenya

		//DN PAID
if ($_REQUEST['cat'])								{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat']){
	$cekregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}
if ($_REQUEST['tgl1']!='' AND $_REQUEST['tgl2']!='')		{	$tiga= 'AND tgl_dn_paid BETWEEN \''.$_REQUEST['tgl1'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
//if ($_REQUEST['armstatus'])			{	$empat = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';		}

$query = 'SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' AND dn_status="paid"';
$hasil = mysql_query($query);
$baris = 1;
while ($mamet = mysql_fetch_array($hasil))
{
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
	// TAMPILKAN CN BILA ADA//
$dncnnya = mysql_fetch_array(mysql_query('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(fu_ajk_cn.total_claim) AS tclaim,
fu_ajk_cn.type_claim
FROM
fu_ajk_cn
WHERE
fu_ajk_cn.id_dn = "'.$mamet['dn_kode'].'" AND id_cost="'.$mamet['id_cost'].'"
GROUP BY
fu_ajk_cn.id_dn'));
if ($dncnnya['id_dn']==$mamet['dn_kode']) {
	$cnnomor = $dncnnya['id_cn'];
	$cnpremi = $dncnnya['tclaim'];
	$statuscn = $dncnnya['type_claim'];
}else{
	$cnnomor = '';
	$cnpremi = '';
	$statuscn = 'Inforce';
}
	// TAMPILKAN CN BILA ADA//
	$netpremi = $mamet['totalpremi'] - $dncnnya['tclaim'];

	$worksheet1->write_string($baris, 0, $metcostumer['name']);
	$worksheet1->write_string($baris, 1, $metpolis['nopol']);
	$worksheet1->write_string($baris, 2, $mamet['dn_kode']);
	$worksheet1->write_string($baris, 3, $mamet['tgl_createdn']);
	$worksheet1->write_string($baris, 4, $mamet['tgl_dn_paid']);
	$worksheet1->write_string($baris, 5, $mamet['dn_status']);
	$worksheet1->write_number($baris, 6, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 7, $cnnomor);
	$worksheet1->write_string($baris, 8, $cnpremi);
	$worksheet1->write_number($baris, 9, $netpremi);
	$worksheet1->write_string($baris, 10, $statuscn);
	$worksheet1->write_string($baris, 11, $mamet['id_regional']);
	$worksheet1->write_string($baris, 12, $mamet['id_area']);
	$worksheet1->write_string($baris, 13, $mamet['id_cabang']);
	$baris++;
	$mametpaid +=$mamet['totalpremi'];
}
$workbook->close();
		;
		break;

	case "dateprocess":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

		HeaderingExcel('ARM-'.$_REQUEST['tgl3'].'-'.$_REQUEST['tgl4'].'-byDateProcess.xls');

		// membuat workbook baru
		$workbook = new Workbook("");
		// membuat worksheet ke-1 (data laki-laki)
		$worksheet1 =& $workbook->add_worksheet('PAID');

		// setting format header tabel data
		$format =& $workbook->add_format();
		$format->set_align('vcenter');
		$format->set_color('white');
		$format->set_bold();
		//$format->set_italic();
		$format->set_pattern();
		$format->set_fg_color('orange');

		// membuat header tabel dengan format
		$worksheet1->set_row(0, 15);
		$worksheet1->set_column(0, 0, 20);	$worksheet1->write_string(0, 0, "NOMOR DN", $format);
		$worksheet1->set_column(0, 1, 12);	$worksheet1->write_string(0, 1, "TANGGAL DN", $format);
		$worksheet1->set_column(0, 2, 15);	$worksheet1->write_string(0, 2, "NO. POLIS", $format);
		$worksheet1->set_column(0, 3, 30);	$worksheet1->write_string(0, 3, "NAMA PERUSAHAAN", $format);
		$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "STATUS", $format);
		$worksheet1->set_column(0, 5, 10);	$worksheet1->write_string(0, 5, "PREMI", $format);
		$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "DATE PAYMENT", $format);
		$worksheet1->set_column(0, 7, 15);	$worksheet1->write_string(0, 7, "REGIONAL", $format);
		$worksheet1->set_column(0, 8, 15);	$worksheet1->write_string(0, 8, "AREA", $format);
		$worksheet1->set_column(0, 9, 20);	$worksheet1->write_string(0, 9, "CABANG", $format);

		$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
		$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
		// membuat header file excel dan nama filenya

		//DN PAID
if ($_REQUEST['cat'])								{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat']){
	$cekregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}

if ($_REQUEST['tgl3']!='' AND $_REQUEST['tgl4']!='')		{	$tiga= 'AND DATE_FORMAT(update_time,"%Y-%m-%d") BETWEEN \''.$_REQUEST['tgl3'].'\' AND \''.$_REQUEST['tgl4'].'\'';	}
		//if ($_REQUEST['armstatus'])			{	$empat = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';		}

		$query = 'SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL';
		$hasil = mysql_query($query);
		$baris = 1;
while ($mamet = mysql_fetch_array($hasil))
{
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

	$worksheet1->write_string($baris, 0, $mamet['dn_kode']);
	$worksheet1->write_string($baris, 1, $mamet['tgl_createdn']);
	$worksheet1->write_string($baris, 2, $metpolis['nopol']);
	$worksheet1->write_string($baris, 3, $metcostumer['name']);
	$worksheet1->write_string($baris, 4, $mamet['dn_status']);
	$worksheet1->write_number($baris, 5, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 6, $mamet['tgl_dn_paid']);
	$worksheet1->write_string($baris, 7, $mamet['id_regional']);
	$worksheet1->write_string($baris, 8, $mamet['id_area']);
	$worksheet1->write_string($baris, 9, $mamet['id_cabang']);
	$baris++;
	$mametpaid +=$mamet['totalpremi'];
}
		$workbook->close();
		;
		break;
	default:
		;
// function untuk membuat header file excel
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

HeaderingExcel('ARM-'.$_REQUEST['tgl'].'-'.$_REQUEST['tgl2'].'.xls');

// membuat workbook baru
$workbook = new Workbook("");
// membuat worksheet ke-1 (data laki-laki)
$worksheet1 =& $workbook->add_worksheet('PAID');

// setting format header tabel data
$format =& $workbook->add_format();
$format->set_align('vcenter');
$format->set_color('white');
$format->set_bold();
//$format->set_italic();
$format->set_pattern();
$format->set_fg_color('orange');

// membuat header tabel dengan format
$worksheet1->set_row(0, 15);
$worksheet1->set_column(0, 0, 30);	$worksheet1->write_string(0, 0, "NAMA PERUSAHAAN", $format);
$worksheet1->set_column(0, 1, 10);	$worksheet1->write_string(0, 1, "NO. POLIS", $format);
$worksheet1->set_column(0, 2, 12);	$worksheet1->write_string(0, 2, "NOMOR DN", $format);
$worksheet1->set_column(0, 3, 15);	$worksheet1->write_string(0, 3, "TANGGAL DN", $format);
$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "STATUS", $format);
$worksheet1->set_column(0, 5, 10);	$worksheet1->write_string(0, 5, "PREMI", $format);
$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "DATE PAYMENT", $format);
$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 7, "jPESERTA", $format);
$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 8, "NOMOR CN", $format);
$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 9, "NILAI CN", $format);
$worksheet1->set_column(0, 10, 15);	$worksheet1->write_string(0, 10, "REGIONAL", $format);
$worksheet1->set_column(0, 11, 15);	$worksheet1->write_string(0, 11, "AREA", $format);
$worksheet1->set_column(0, 12, 20);	$worksheet1->write_string(0, 12, "CABANG", $format);
$worksheet1->set_column(0, 13, 10);	$worksheet1->write_string(0, 13, "USER", $format);
$worksheet1->set_column(0, 14, 20);	$worksheet1->write_string(0, 14, "TGL INPUT", $format);

$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
// membuat header file excel dan nama filenya

//DN PAID
if ($_REQUEST['cat'])								{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat']){
	$cekregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}
if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')		{	$tiga= 'AND tgl_createdn BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
if ($_REQUEST['tgl3']!='' AND $_REQUEST['tgl4']!='')	{	$lima='AND tgl_dn_paid BETWEEN \''.$_REQUEST['tgl3'].'\' AND \''.$_REQUEST['tgl4'].'\'';	}
if ($_REQUEST['armstatus'])			{	$empat = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';		}

$query = 'SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid"';
$hasil = mysql_query($query);
$baris = 1;
while ($mamet = mysql_fetch_array($hasil))
{
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
	$metdnpeserta = mysql_num_rows(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));
	//DATA CN//
	$metcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$mamet['dn_kode'].'"'));
	//DATA CN//
	$worksheet1->write_string($baris, 0, $metcostumer['name']);
	$worksheet1->write_string($baris, 1, $metpolis['nopol']);
	$worksheet1->write_string($baris, 2, $mamet['dn_kode']);
	$worksheet1->write_string($baris, 3, $mamet['tgl_createdn']);
	$worksheet1->write_string($baris, 4, $mamet['dn_status']);
	$worksheet1->write_number($baris, 5, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 6, $mamet['tgl_dn_paid']);
	$worksheet1->write_string($baris, 7, $metdnpeserta);
	$worksheet1->write_string($baris, 8, $metcn['id_cn']);
	$worksheet1->write_string($baris, 9, $metcn['total_claim']);
	$worksheet1->write_string($baris, 10, $mamet['id_regional']);
	$worksheet1->write_string($baris, 11, $mamet['id_area']);
	$worksheet1->write_string($baris, 12, $mamet['id_cabang']);
	$worksheet1->write_string($baris, 13, $mamet['update_by']);
	$worksheet1->write_string($baris, 14, $mamet['update_time']);
	$baris++;
	$mametpaid +=$mamet['totalpremi'];
}

// DN UNPAID

$worksheet2 =& $workbook->add_worksheet('UNPAID');

// membuat header tabel
$worksheet2->set_row(0, 15);
$worksheet2->set_column(0, 0, 20);	$worksheet2->write_string(0, 0, "NOMOR DN", $format);
$worksheet2->set_column(0, 1, 12);	$worksheet2->write_string(0, 1, "TANGGAL DN", $format);
$worksheet2->set_column(0, 2, 15);	$worksheet2->write_string(0, 2, "NO. POLIS", $format);
$worksheet2->set_column(0, 3, 30);	$worksheet2->write_string(0, 3, "NAMA PERUSAHAAN", $format);
$worksheet2->set_column(0, 4, 10);	$worksheet2->write_string(0, 4, "STATUS", $format);
$worksheet2->set_column(0, 5, 10);	$worksheet2->write_string(0, 5, "PREMI", $format);
$worksheet2->set_column(0, 6, 15);	$worksheet2->write_string(0, 6, "REGIONAL", $format);
$worksheet2->set_column(0, 7, 15);	$worksheet2->write_string(0, 7, "AREA", $format);
$worksheet2->set_column(0, 8, 20);	$worksheet2->write_string(0, 8, "CABANG", $format);

// menampilkan data unpaid
if ($_REQUEST['cat'])								{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat']){
	$cekregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}
if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')		{	$tiga= 'AND tgl_createdn BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
if ($_REQUEST['armstatus'])			{	$empat = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';		}

//$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
$query = 'SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.'  AND dn_status="unpaid"';
$hasil = mysql_query($query);
$baris = 1;
while ($mamet = mysql_fetch_array($hasil))
{
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

	$worksheet2->write_string($baris, 0, $mamet['dn_kode']);
	$worksheet2->write_string($baris, 1, $mamet['tgl_createdn']);
	$worksheet2->write_string($baris, 2, $metpolis['nopol']);
	$worksheet2->write_string($baris, 3, $metcostumer['name']);
	$worksheet2->write_string($baris, 4, $mamet['dn_status']);
	$worksheet2->write_number($baris, 5, $mamet['totalpremi']);
	$worksheet2->write_string($baris, 6, $mamet['id_regional']);
	$worksheet2->write_string($baris, 7, $mamet['id_area']);
	$worksheet2->write_string($baris, 8, $mamet['id_cabang']);
	$baris++;
	$unmametpaid +=$mamet['totalpremi'];
}

$worksheet3 =& $workbook->add_worksheet('SUMMARY');

$worksheet3->set_row(0, 15);
$worksheet3->merge_cells(0, 0, 0, 2);
$worksheet3->set_column(0, 0, 30);	$worksheet3->write_string(0, 0, "PT. BANK PUNDI INDONESIA, Tbk", $format);
$worksheet3->set_column(2, 0, 20);	$worksheet3->write_string(2, 0, "TOTAL TAGIHAN", $format);
$worksheet3->set_column(2, 1, 20);	$worksheet3->write_string(2, 1, "PAYMENT", $format);
$worksheet3->set_column(2, 2, 20);	$worksheet3->write_string(2, 2, "OS", $format);

$mametALL = $mametpaid + $unmametpaid;
$worksheet3->write_string(3, 0, $mametALL);
$worksheet3->write_string(3, 1, $mametpaid);
$worksheet3->write_string(3, 2, $unmametpaid);

$workbook->close();
} // switch

?>