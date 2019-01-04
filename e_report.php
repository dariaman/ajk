<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
include_once "includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');

include "includes/fu6106.php";
function KonDecRomawi($angka){
	$hsl = "";
	if($angka<1||$angka>3999){
		$hsl = "Batas Angka 1 s/d 3999";
	}else{
		while($angka>=1000){
			$hsl .= "M";
			$angka -= 1000;
		}
		if($angka>=500){
			if($angka>500){
				if($angka>=900){
					$hsl .= "CM";
					$angka-=900;
				}else{
					$hsl .= "D";
					$angka-=500;
				}
			}
		}
		while($angka>=100){
			if($angka>=400){
				$hsl .= "CD";
				$angka-=400;
			}else{
				$angka-=100;
			}
		}
		if($angka>=50){
			if($angka>=90){
				$hsl .= "XC";
				$angka-=90;
			}else{
				$hsl .= "L";
				$angka-=50;
			}
		}
		while($angka>=10){
			if($angka>=40){
				$hsl .= "XL";
				$angka-=40;
			}else{
				$hsl .= "X";
				$angka-=10;
			}
		}
		if($angka>=5){
			if($angka==9){
				$hsl .= "IX";
				$angka-=9;
			}else{
				$hsl .= "V";
				$angka-=5;
			}
		}
		while($angka>=1){
			if($angka==4){
				$hsl .= "IV";
				$angka-=4;
			}else{
				$hsl .= "I";
				$angka-=1;
			}
		}
	}
	return ($hsl);
}
function bulan($bln){
	$bulan = $bln;
Switch ($bulan){
	case 1 : $bulan="Januari";
		Break;
	case 2 : $bulan="Februari";
		Break;
	case 3 : $bulan="Maret";
		Break;
	case 4 : $bulan="April";
		Break;
	case 5 : $bulan="Mei";
		Break;
	case 6 : $bulan="Juni";
		Break;
	case 7 : $bulan="Juli";
		Break;
	case 8 : $bulan="Agustus";
		Break;
	case 9 : $bulan="September";
		Break;
	case 10 : $bulan="Oktober";
		Break;
	case 11 : $bulan="November";
		Break;
	case 12 : $bulan="Desember";
		Break;
}
	return $bulan;
}
$q = mysql_fetch_array(mysql_query('SELECT id, id_cost, wilayah, cabang FROM pengguna WHERE id="'.$_REQUEST['deb'].'"'));
//CEK DATA CABANG CENTRAL;
$cekCentral = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = mysql_query('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = mysql_query('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';

}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
}
//CEK DATA CABANG CENTRAL;

switch ($_REQUEST['er']) {
	case "eL_peserta":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {	HeaderingExcel('Laporan_'.$stringcost.'.xls');	}else{	HeaderingExcel('Laporan_'.$stringcost.'.xls');	}

$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan Peserta');

$format =& $workbook->add_format();
$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');

$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
$fjudul1 =& $workbook->add_format();	$fjudul1->set_align('vcenter');	$fjudul1->set_align('right');	$fjudul1->set_bold();

$worksheet1->merge_cells(0, 0, 0, 15);	$worksheet1->write_string(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
$worksheet1->merge_cells(1, 0, 1, 15);	$worksheet1->write_string(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
$worksheet1->merge_cells(2, 0, 2, 15);	$worksheet1->write_string(2, 0, strtoupper($met_c['name']), $fjudul);

$worksheet1->set_row(4, 15);
$worksheet1->set_column(4, 0, 5);	$worksheet1->write_string(4, 0, "NO", $format);
$worksheet1->set_column(4, 1, 15);	$worksheet1->write_string(4, 1, "NOMOR DN", $format);
$worksheet1->set_column(4, 2, 15);	$worksheet1->write_string(4, 2, "TGL DN", $format);
$worksheet1->set_column(4, 3, 15);	$worksheet1->write_string(4, 3, "ID PESERTA", $format);
$worksheet1->set_column(4, 4, 15);	$worksheet1->write_string(4, 4, "NAMA DEBITUR", $format);
$worksheet1->set_column(4, 5, 10);	$worksheet1->write_string(4, 5, "CABANG", $format);
$worksheet1->set_column(4, 6, 10);	$worksheet1->write_string(4, 6, "TGL LAHIR", $format);
$worksheet1->set_column(4, 7, 5);	$worksheet1->write_string(4, 7, "USIA", $format);
$worksheet1->set_column(4, 8, 10);	$worksheet1->write_string(4, 8, "PLAFOND", $format);
$worksheet1->set_column(4, 9, 5);	$worksheet1->write_string(4, 9, "JK.W", $format);
$worksheet1->set_column(4, 10, 15);	$worksheet1->write_string(4, 10, "MULAI ASURANSI", $format);
$worksheet1->set_column(4, 11, 15);	$worksheet1->write_string(4, 11, "AKHIR ASURANSI", $format);
$worksheet1->set_column(4, 12, 10);	$worksheet1->write_string(4, 12, "RATE TUNGGAL", $format);
$worksheet1->set_column(4, 13, 15);	$worksheet1->write_string(4, 13, "EM (%)", $format);
$worksheet1->set_column(4, 14, 15);	$worksheet1->write_string(4, 14, "TOTAL RATE", $format);
$worksheet1->set_column(4, 15, 15);	$worksheet1->write_string(4, 15, "TOTAL PREMI", $format);

if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_peserta.id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])	{	$duaa = 'AND fu_ajk_peserta.id_polis ="' . $_REQUEST['subcat'] . '"';	}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paid'])		{	$empt = 'AND fu_ajk_peserta.status_bayar ="' . $_REQUEST['paid'] . '"';	}
if ($_REQUEST['status'])	{	$lima = 'AND fu_ajk_peserta.status_aktif ="' . $_REQUEST['status'] . '"';	}
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
								$enam = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
							}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
								$tujuh = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
							}
if ($_REQUEST['tgldn1'])		{	$delapan = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'" ';	}							
$baris = 5;
$er_data = mysql_query('SELECT fu_ajk_dn.dn_kode,
															 fu_ajk_dn.tgl_createdn,
															 fu_ajk_peserta.id_peserta,
															 fu_ajk_peserta.nama,
															 fu_ajk_peserta.cabang,
															 fu_ajk_peserta.tgl_lahir, 
															 fu_ajk_peserta.usia, 
															 fu_ajk_peserta.kredit_jumlah, 
															 fu_ajk_peserta.kredit_tenor, 
															 fu_ajk_peserta.kredit_tgl, 
															 fu_ajk_peserta.kredit_akhir, 
															 fu_ajk_peserta.ratebank, 
															 fu_ajk_peserta.ext_premi, 
															 fu_ajk_peserta.totalpremi
												FROM fu_ajk_peserta 
															LEFT JOIN fu_ajk_dn 
															on fu_ajk_dn.id = fu_ajk_peserta.id_dn 
												WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND fu_ajk_peserta.del IS NULL ORDER BY kredit_tgl ASC');
while ($mamet = mysql_fetch_array($er_data))
{
//$cekdatadn = mysql_fetch_array(mysql_query('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
/*
if ($mamet['type_data']=="SPK") {
	$mettenornya = $mamet['kredit_tenor'] / 12;
	$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
}else{
	$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));
}
*/
if ($mamet['ext_premi']==0) {	$mametrate_ext = '';	}else{	$mametrate_ext = $mamet['ext_premi'];	}
//$mettotalrate = $cekdataret['rate'] * (1 + $mametrate_ext / 100);
	$mettotalrate = $mamet['ratebank'] * (1 + $mametrate_ext / 100);

	$worksheet1->write_string($baris, 0, ++$no);
	//$worksheet1->write_string($baris, 1, $cekdatadn['dn_kode']);
	//$worksheet1->write_string($baris, 2, _convertDate($cekdatadn['tgl_createdn']));
	$worksheet1->write_string($baris, 1, $mamet['dn_kode']);
	$worksheet1->write_string($baris, 2, _convertDate($mamet['tgl_createdn']));	
	$worksheet1->write_string($baris, 3, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 4, $mamet['nama']);
	$worksheet1->write_string($baris, 5, $mamet['cabang']);
	$worksheet1->write_string($baris, 6, _convertDate($mamet['tgl_lahir']));
	$worksheet1->write_string($baris, 7, $mamet['usia']);
	$worksheet1->write_string($baris, 8, duit($mamet['kredit_jumlah']), $fjudul1);
	$worksheet1->write_string($baris, 9, $mamet['kredit_tenor']);
	$worksheet1->write_string($baris, 10, _convertDate($mamet['kredit_tgl']));
	$worksheet1->write_string($baris, 11, _convertDate($mamet['kredit_akhir']));
	//$worksheet1->write_string($baris, 12, $cekdataret['rate']);
	$worksheet1->write_string($baris, 12, $mamet['ratebank']);
	$worksheet1->write_string($baris, 13, $mametrate_ext);
	$worksheet1->write_string($baris, 14, $mettotalrate);
	$worksheet1->write_string($baris, 15, duit($mamet['totalpremi']), $fjudul1);
	$baris++;

$tPlafond += $mamet['kredit_jumlah'];
$tTotalPremi += $mamet['totalpremi'];
}

$worksheet1->merge_cells($baris, 0, $baris, 7);		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);
$worksheet1->write_string($baris, 8, duit($tPlafond), $fjudul1);
$worksheet1->merge_cells($baris, 9, $baris, 14);	$worksheet1->write_string($baris, 9, "", $fjudul);
$worksheet1->write_string($baris, 15, duit($tTotalPremi), $fjudul1);

$workbook->close();
		;
		break;

	case "eR_peserta":
$pdf=new FPDF('L','mm','legal');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$met_customer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	$pdf->Image('image/adonai_64.gif',325,10, 20, 15);
	$pdf->Text(150, 15,'DAFTAR PESERTA ASURANSI JIWA KUMPULAN REGULER');
	$pdf->Text(170, 20,'PERIODE '._convertDate($_REQUEST['tgl1']).' s/d '._convertDate($_REQUEST['tgl2']).'');
	$pdf->Text(178, 25,$met_customer['name']);


	$pdf->setFont('Arial','',9);
	$pdf->setFillColor(233,233,233);
	$y_axis1 = 30;
	$pdf->setY($y_axis1);
	$pdf->setX(10);

	$pdf->cell(8,6,'No',1,0,'C',1);
	$pdf->cell(30,6,'Produk',1,0,'C',1);
	$pdf->cell(20,6,'ID Peserta',1,0,'C',1);
	$pdf->cell(50,6,'Nama',1,0,'C',1);
	$pdf->cell(18,6,'Tgl Lahir',1,0,'C',1);
	$pdf->cell(8,6,'Usia',1,0,'C',1);
	$pdf->cell(20,6,'Plafond',1,0,'C',1);
	$pdf->cell(10,6,'JK.W',1,0,'C',1);
	$pdf->cell(18,6,'Tgl.Akad',1,0,'C',1);
	$pdf->cell(18,6,'Tgl.Akhir',1,0,'C',1);
	$pdf->cell(15,6,'Rate',1,0,'C',1);
	$pdf->cell(18,6,'Premi',1,0,'C',1);
	$pdf->cell(10,6,'EM(%)',1,0,'C',1);
	$pdf->cell(15,6,'Ext.Premi',1,0,'C',1);
	$pdf->cell(15,6,'T.Rate',1,0,'C',1);
	$pdf->cell(25,6,'T.Premi',1,0,'C',1);
	$pdf->cell(35,6,'Cabang',1,0,'C',1);

if ($_REQUEST['cat'])		{	$satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])	{	$duaa = 'AND id_polis ="' . $_REQUEST['subcat'] . '"';	}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paid'])		{	$empt = 'AND status_bayar ="' . $_REQUEST['paid'] . '"';	}
if ($_REQUEST['status'])	{	$lima = 'AND status_aktif ="' . $_REQUEST['status'] . '"';	}

if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}


//$er_data = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del IS NULL ORDER BY kredit_tgl ASC');
//echo('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kTenor FROM fu_ajk_peserta WHERE id_dn !="" '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' AND del is NULL ORDER BY kredit_tgl ASC');
$er_data = mysql_query('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kTenor FROM fu_ajk_peserta WHERE id_dn !="" '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$tujuh.' AND del is NULL ORDER BY kredit_tgl ASC');
while ($mamet = mysql_fetch_array($er_data))
{
	$cekdatadn = mysql_fetch_array(mysql_query('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
if ($mamet['type_data']=="SPK") {
	$mettenornya = $mamet['kTenor'] / 12;
	$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
	$metSPK_EM = mysql_fetch_array(mysql_query('SELECT spak, ext_premi FROM fu_ajk_spak  WHERE spak="'.$mamet['spaj'].'"'));
	$mametrate_ext_ = $metSPK_EM['ext_premi'];
}else{
	$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));
	$mametrate_ext_ ='';
}

$cekProduk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id ="'.$mamet['id_polis'].'"'));

//if ($mamet['ext_premi']==0) {	$mametrate_ext = '';	}else{	$mametrate_ext = $mamet['ext_premi'];	}
	$mettotalrate = $cekdataret['rate'] * (1 + $mametrate_ext_ / 100);
	$cell[$i][0] = $cekProduk['nmproduk'];
	$cell[$i][1] = $mamet['id_peserta'];
	$cell[$i][2] = $mamet['nama'];
	$cell[$i][3] = _convertDate($mamet['tgl_lahir']);
	$cell[$i][4] = $mamet['usia'];
	$cell[$i][5] = duit($mamet['kredit_jumlah']);
	$cell[$i][6] = $mamet['kredit_tenor'];
	$cell[$i][7] = _convertDate($mamet['kredit_tgl']);
	$cell[$i][8] = _convertDate($mamet['kredit_akhir']);
	$cell[$i][9] = $cekdataret['rate'];
	$cell[$i][10] = duit($mamet['premi']);
	$cell[$i][11] = $mametrate_ext_;
	$cell[$i][12] = duit($mamet['ext_premi']);
	$cell[$i][13] = $mettotalrate;
	$cell[$i][14] = duit($mamet['totalpremi']);
	$cell[$i][15] = $mamet['cabang'];
	$i++;

}
	$pdf->Ln();

for($j<1;$j<$i;$j++)
{	$pdf->cell(8,6,$j+1,1,0,'C');
	$pdf->cell(30,6,$cell[$j][0],1,0,'C');
	$pdf->cell(20,6,$cell[$j][1],1,0,'C');
	$pdf->cell(50,6,$cell[$j][2],1,0,'L');
	$pdf->cell(18,6,$cell[$j][3],1,0,'C');
	$pdf->cell(8,6,$cell[$j][4],1,0,'C');
	$pdf->cell(20,6,$cell[$j][5],1,0,'R');
	$pdf->cell(10,6,$cell[$j][6],1,0,'C');
	$pdf->cell(18,6,$cell[$j][7],1,0,'C');
	$pdf->cell(18,6,$cell[$j][8],1,0,'C');
	$pdf->cell(15,6,$cell[$j][9],1,0,'C');
	$pdf->cell(18,6,$cell[$j][10],1,0,'R');
	$pdf->cell(10,6,$cell[$j][11],1,0,'C');
	$pdf->cell(15,6,$cell[$j][12],1,0,'R');
	$pdf->cell(15,6,$cell[$j][13],1,0,'C');
	$pdf->cell(25,6,$cell[$j][14],1,0,'R');
	$pdf->cell(35,6,$cell[$j][15],1,0,'L');

	$pdf->Ln();
}
$pdf->Output("LAPORAN_PESERTA.pdf","I");
		;
		break;

	case "eL_dn":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['tgl']==$_REQUEST['tgl']) {	HeaderingExcel('Laporan_DN_'.$stringcost.'.xls');	}else{	HeaderingExcel('Laporan_'.$stringcost.'.xls');	}

$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan Debit Note');

$format =& $workbook->add_format();
$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');

$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
$fjudul1 =& $workbook->add_format();	$fjudul1->set_align('vcenter');	$fjudul1->set_align('right');	$fjudul1->set_bold();

$worksheet1->merge_cells(0, 0, 0, 8);	$worksheet1->write_string(0, 0, "DAFTAR DEBIT NOTE ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
$worksheet1->merge_cells(1, 0, 1, 8);	$worksheet1->write_string(1, 0, "PERIODE "._convertDate($_REQUEST['tgl'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
$worksheet1->merge_cells(2, 0, 2, 8);	$worksheet1->write_string(2, 0, strtoupper($met_c['name']), $fjudul);

$worksheet1->set_row(4, 15);
$worksheet1->set_column(4, 0, 5);	$worksheet1->write_string(4, 0, "NO", $format);
$worksheet1->set_column(4, 1, 15);	$worksheet1->write_string(4, 1, "NOMOR DN", $format);
$worksheet1->set_column(4, 2, 15);	$worksheet1->write_string(4, 2, "TGL DN", $format);
$worksheet1->set_column(4, 3, 15);	$worksheet1->write_string(4, 3, "J.Data", $format);
$worksheet1->set_column(4, 4, 15);	$worksheet1->write_string(4, 4, "Total Premi", $format);
$worksheet1->set_column(4, 5, 10);	$worksheet1->write_string(4, 5, "Pembayaran", $format);
$worksheet1->set_column(4, 6, 10);	$worksheet1->write_string(4, 6, "TGL Bayar", $format);
$worksheet1->set_column(4, 7, 5);	$worksheet1->write_string(4, 7, "Regional", $format);
$worksheet1->set_column(4, 8, 10);	$worksheet1->write_string(4, 8, "Cabang", $format);

if ($_REQUEST['tgl'])		{	$satu = 'AND tgl_createdn BETWEEN "'.$_REQUEST['tgl'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paiddata'])	{	$duaa = 'AND dn_status = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
								$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
							}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
								$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
							}

$baris = 5;
$er_data = mysql_query('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_nopol="'.$_REQUEST['subcat'].'" AND id_cost="'.$_REQUEST['cat'].'" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del IS NULL ORDER BY tgl_createdn ASC');
while ($mamet = mysql_fetch_array($er_data))
{
$metpeserta = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, id_dn, COUNT(nama) AS jNama FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'" AND id_polis="'.$_REQUEST['subcat'].'" AND id_dn="'.$mamet['id'].'" GROUP BY id_dn'));
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['dn_kode']);
	$worksheet1->write_string($baris, 2, _convertDate($mamet['tgl_createdn']));
	$worksheet1->write_string($baris, 3, $metpeserta['jNama'].' Peserta');
	$worksheet1->write_string($baris, 4, duit($mamet['totalpremi']), $fjudul1);
	$worksheet1->write_string($baris, 5, $mamet['dn_status']);
	$worksheet1->write_string($baris, 6, $mamet['tgl_dn_paid']);
	$worksheet1->write_string($baris, 7, $mamet['id_regional']);
	$worksheet1->write_string($baris, 8, $mamet['id_cabang']);
	$baris++;
$tTotalPeserta += $metpeserta['jNama'];
$tTotalPremi += $mamet['totalpremi'];
}
$worksheet1->merge_cells($baris, 0, $baris, 2);		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);
$worksheet1->write_string($baris, 3, duit($tTotalPeserta), $fjudul1);
$worksheet1->write_string($baris, 4, duit($tTotalPremi), $fjudul1);

$workbook->close();
		;
		break;

	case  "eR_DN":
	$pdf=new FPDF('P','mm','A3');
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$met_customer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	$pdf->Image('image/adonai_64.gif',255,10, 20, 15);
	$pdf->Text(100, 15,'DAFTAR DEBIT NOTE ASURANSI JIWA KUMPULAN REGULER');
	$pdf->Text(120, 20,'PERIODE '._convertDate($_REQUEST['tgl1']).' s/d '._convertDate($_REQUEST['tgl2']).'');
	$pdf->Text(130, 25,$met_customer['name']);

	$pdf->setFont('Arial','',9);
	$pdf->setFillColor(233,233,233);
	$y_axis1 = 30;
 	$pdf->setY($y_axis1);
	$pdf->setX(10);

$cekCentral = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = mysql_query('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabangDN .= ' OR fu_ajk_dn.id_cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = mysql_query('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentralDN .= 'OR (fu_ajk_dn.id_cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentralDN = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentralDN.')';

}else{
	if ($metCentralCabangDN=="") {
		$metCabangCentralDN = 'AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentralDN = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabangDN.')';
	}
}
		//CEK DATA CABANG CENTRAL;

if ($_REQUEST['tgl1'])		{	$satu = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paiddata'] == "paid") {
	$duaa = 'AND (fu_ajk_dn.dn_status = "paid" OR fu_ajk_dn.dn_status = "Lunas")';
}elseif ($_REQUEST['paiddata'] == "unpaid") {
	$duaa = 'AND fu_ajk_dn.dn_status = "unpaid"';
}elseif ($_REQUEST['paiddata'] == "Kurang Bayar") {
	$duaa = 'AND fu_ajk_dn.dn_status = "Kurang Bayar"';
}else{	}

if ($_REQUEST['subcat'])	{	$tiga = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['cat'])		{	$empat = 'AND fu_ajk_dn.id_client = "'.$_REQUEST['cat'].'"';	}

/*
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
}
*/

	$pdf->cell(8,6,'No',1,0,'C',1);
	$pdf->cell(40,6,'Produk',1,0,'C',1);
	$pdf->cell(35,6,'Debit Note',1,0,'C',1);
	$pdf->cell(20,6,'Tanggal DN',1,0,'C',1);
	$pdf->cell(20,6,'Debitur',1,0,'C',1);
	$pdf->cell(30,6,'Total Premi',1,0,'C',1);
	$pdf->cell(20,6,'Status',1,0,'C',1);
	$pdf->cell(22,6,'Tgl Pelunasan',1,0,'C',1);
	$pdf->cell(65,6,'Cabang',1,0,'C',1);

//$er_data = mysql_query('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empat.' '.$metCabangCentral.' AND del IS NULL ORDER BY tgl_createdn ASC');
$er_data = mysql_query('SELECT
fu_ajk_dn.id,
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_polis.nmproduk,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.dn_total,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.id_cabang
FROM fu_ajk_dn
INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_dn.id != "" AND fu_ajk_dn.id_cost = "'.$q['id_cost'].'"  '.$metCabangCentralDN.' '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id
ORDER BY fu_ajk_dn.tgl_createdn DESC, fu_ajk_dn.id_nopol DESC');
while ($mamet = mysql_fetch_array($er_data))
{
$metpeserta = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, id_dn, COUNT(nama) AS jNama FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'" AND id_dn="'.$mamet['id'].'" GROUP BY id_dn'));
	$cell[$i][0] = $mamet['nmproduk'];
	$cell[$i][1] = $mamet['dn_kode'];
	$cell[$i][2] = _convertDate($mamet['tgl_createdn']);
	$cell[$i][3] = $metpeserta['jNama'];
	$cell[$i][4] = duit($mamet['totalpremi']);
	$cell[$i][5] = $mamet['dn_status'];
	$cell[$i][6] = _convertDate($mamet['tgl_dn_paid']);
	$cell[$i][7] = $mamet['id_cabang'];
	$i++;
	$tTotalPeserta += $metpeserta['jNama'];
	$tTotalPremi += $mamet['totalpremi'];

}	$pdf->Ln();
for($j<1;$j<$i;$j++)
{	$pdf->cell(8,6,$j+1,1,0,'C');
	$pdf->cell(40,6,$cell[$j][0],1,0,'C');
	$pdf->cell(35,6,$cell[$j][1],1,0,'C');
	$pdf->cell(20,6,$cell[$j][2],1,0,'C');
	$pdf->cell(20,6,$cell[$j][3],1,0,'C');
	$pdf->cell(30,6,$cell[$j][4],1,0,'R');
	$pdf->cell(20,6,$cell[$j][5],1,0,'C');
	$pdf->cell(22,6,$cell[$j][6],1,0,'C');
	$pdf->cell(65,6,$cell[$j][7],1,0,'L');
	$pdf->Ln();
}
$pdf->cell(103,6,'Total',1,0,'C',1);
$pdf->cell(20,6,$tTotalPeserta,1,0,'C',1);
$pdf->cell(30,6,duit($tTotalPremi),1,0,'R',1);
$pdf->cell(107,6,'' ,1,0,'R',1);

$pdf->Output("LAPORAN_DEBITNOTE.pdf","I");
	;
	break;

	case "eL_spk":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
HeaderingExcel('Laporan_SPK_'._convertDate($_REQUEST['tgl1']).'_sd_'._convertDate($_REQUEST['tgl2']).'.xls');
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan SPK');

$worksheet1->merge_cells(0, 0, 0, 22);	$worksheet1->write_string(0, 0, "KONFIRMASI PREMIUM CALCULATION - AJK KONSORSIUM", $fjudul, 1);
$worksheet1->merge_cells(1, 0, 1, 22);	$worksheet1->write_string(1, 0, strtoupper($met_c['name']), $fjudul);

$format =& $workbook->add_format();
$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');
$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();

		$worksheet1->set_row(2, 15);
		$worksheet1->set_column(2, 0, 5);	$worksheet1->write_string(2, 0, "NO", $format);
		$worksheet1->set_column(2, 1, 15);	$worksheet1->write_string(2, 1, "NAMA DEBITUR", $format);
		$worksheet1->set_column(2, 2, 15);	$worksheet1->write_string(2, 2, "CABANG", $format);
		$worksheet1->set_column(2, 3, 15);	$worksheet1->write_string(2, 3, "NO. SPK", $format);
		$worksheet1->set_column(2, 4, 15);	$worksheet1->write_string(2, 4, "TGL PEMERIKSAAN", $format);
		$worksheet1->set_column(2, 5, 15);	$worksheet1->write_string(2, 5, "TGL TERIMA SPK", $format);
		$worksheet1->set_column(2, 6, 15);	$worksheet1->write_string(2, 6, "TGL LAHIR", $format);
		$worksheet1->set_column(2, 7, 15);	$worksheet1->write_string(2, 7, "USIA AWAL ", $format);
		$worksheet1->set_column(2, 8, 15);	$worksheet1->write_string(2, 8, "USIA AKHIR", $format);
		$worksheet1->set_column(2, 9, 15);	$worksheet1->write_string(2, 9, "PLAFOND", $format);
		$worksheet1->set_column(2, 10, 10);	$worksheet1->write_string(2, 10, "TENOR", $format);
		$worksheet1->set_column(2, 11, 10);	$worksheet1->write_string(2, 11, "TB", $format);
		$worksheet1->set_column(2, 12, 10);	$worksheet1->write_string(2, 12, "BB", $format);
		$worksheet1->set_column(2, 13, 15);	$worksheet1->write_string(2, 13, "SISTOLIK", $format);
		$worksheet1->set_column(2, 14, 15);	$worksheet1->write_string(2, 14, "DIASTOLIK", $format);
		$worksheet1->set_column(2, 15, 15);	$worksheet1->write_string(2, 15, "NADI", $format);
		$worksheet1->set_column(2, 16, 15);	$worksheet1->write_string(2, 16, "PERNAFASAN", $format);
		$worksheet1->set_column(2, 17, 15);	$worksheet1->write_string(2, 17, "GULA DARAH", $format);
		$worksheet1->set_column(2, 18, 15);	$worksheet1->write_string(2, 18, "ITEM MEROKOK", $format);
		$worksheet1->set_column(2, 19, 15);	$worksheet1->write_string(2, 19, "ITEM PERTANYAAN", $format);
		$worksheet1->set_column(2, 20, 15);	$worksheet1->write_string(2, 20, "CATATAN SKS", $format);
		$worksheet1->set_column(2, 21, 15);	$worksheet1->write_string(2, 21, "STATUS", $format);
		$worksheet1->set_column(2, 22, 15);	$worksheet1->write_string(2, 22, "ANALISA DOKTER", $format);

if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat'])	{	$dua = 'AND fu_ajk_spak.id_polis = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND fu_ajk_spak_form.tgl_periksa BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}

$cekCentral = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = mysql_query('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang_SPK .= ' OR fu_ajk_spak_form.cabang ="'.$cekCentral__['id'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = mysql_query('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.id AS idcab, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral_SPK .= 'OR (fu_ajk_spak_form.cabang ="'.$cekCentral__['idcab'].'")';
	}
	$metCabangCentral_SPK = 'AND (fu_ajk_spak_form.cabang ="'.$cekCentral__['idcab'].'" '.$metCabangCentral_SPK.')';

}else{
	$met_cab_ = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
	if ($metCentralCabang_SPK=="") {
		$metCabangCentral_SPK = 'AND fu_ajk_spak_form.cabang ="'.$met_cab_['id'].'"';
	}else{
		$metCabangCentral_SPK = 'AND (fu_ajk_spak_form.cabang ="'.$met_cab_['id'].'" '.$metCentralCabang_SPK.')';
	}
}
		//CEK DATA CABANG CENTRAL;

$baris = 3;
//$er_data = mysql_query('SELECT * FROM fu_ajk_spak_form WHERE fu_ajk_spak_form.id !="" '.$satu.' '.$duaa.' ORDER BY fu_ajk_spak_form.tgl_periksa DESC');
$er_data = mysql_query('SELECT
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak.spak,
fu_ajk_spak.input_date AS spkInput,
fu_ajk_spak_form.input_date,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.plafond,
(fu_ajk_spak_form.tenor * 12) AS tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.catatan,
fu_ajk_spak.`status` AS statusSPK,
fu_ajk_spak_form.kesimpulan,
fu_ajk_spak_form.tgl_periksa
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL  AND fu_ajk_spak_form.del is NULL '.$metCabangCentral_SPK.' '.$satu.' '.$dua.' '.$tiga.'
ORDER BY fu_ajk_spak_form.tgl_periksa ASC, fu_ajk_spak.id_polis ASC');
while ($mamet = mysql_fetch_array($er_data))
{
//$nomorspak = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE id="'.$mamet['idspk'].'"'));
//$tgl_terima_spak = explode(" ", $nomorspak['input_date']);
$tgl_terima_spak = explode(" ", $mamet['spkInput']);

if ($mamet['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
	//$umur = ceil(((strtotime($mamet['tgl_asuransi']) - strtotime($mamet['dob'])) / (60*60*24*365.2425)));									// FORMULA USIA
	//$umur_last = $umur + $met_['tenor'];
	$tolik = explode("/", $mamet['tekanandarah']);

if (is_numeric($mamet['cabang'])) {
	$metCbgSPK = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'" '));
	$metCbgSPK_ = $metCbgSPK['name'];
}else{
	$metCbgSPK_ = $mamet['cabang'];
}

	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['nama']);
	$worksheet1->write_string($baris, 2, $metCbgSPK_);
	$worksheet1->write_string($baris, 3, $mamet['spak']);
	$worksheet1->write_string($baris, 4, $mamet['tgl_periksa']);
	$worksheet1->write_string($baris, 5, $tgl_terima_spak[0]);
	$worksheet1->write_string($baris, 6, $mamet['dob']);
	$worksheet1->write_string($baris, 7, $mamet['nama']);
	$worksheet1->write_string($baris, 8, $mamet['nama']);
	$worksheet1->write_string($baris, 9, $mamet['plafond']);
	$worksheet1->write_string($baris, 10, $mamet['tenor']);
	$worksheet1->write_string($baris, 11, $mamet['tinggibadan']);
	$worksheet1->write_string($baris, 12, $mamet['beratbadan']);
	$worksheet1->write_string($baris, 13, $tolik[0]);
	$worksheet1->write_string($baris, 14, $tolik[1]);
	$worksheet1->write_string($baris, 15, $mamet['nadi']);
	$worksheet1->write_string($baris, 16, $mamet['pernafasan']);
	$worksheet1->write_string($baris, 17, $mamet['guladarah']);
	$worksheet1->write_string($baris, 18, $pertanyaan6);
	$worksheet1->write_string($baris, 19, $mamet['ket6']);
	$worksheet1->write_string($baris, 20, $mamet['catatan']);
	$worksheet1->write_string($baris, 21, $mamet['statusSPK']);
	$worksheet1->write_string($baris, 22, $mamet['kesimpulan']);
	$baris++;
}
$workbook->close();
		;
		break;

	case "eR_CN":
	$pdf=new FPDF('P','mm','A3');
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$met_customer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	$pdf->Image('image/adonai_64.gif',260,10, 20, 15);
	$pdf->Text(100, 15,'DAFTAR PESERTA ASURANSI JIWA KUMPULAN REGULER');
	$pdf->Text(120, 20,'PERIODE '._convertDate($_REQUEST['tgl1']).' s/d '._convertDate($_REQUEST['tgl2']).'');
	$pdf->Text(128, 25,$met_customer['name']);

	$pdf->setFont('Arial','',8);
	$pdf->setFillColor(233,233,233);
	$y_axis1 = 30;
	$pdf->setY($y_axis1);
	$pdf->setX(10);

	$pdf->cell(8,6,'No',1,0,'C',1);
	$pdf->cell(30,6,'Debit Note',1,0,'C',1);
	$pdf->cell(30,6,'Credit Note',1,0,'C',1);
	$pdf->cell(16,6,'Tanggal CN',1,0,'C',1);
	$pdf->cell(18,6,'ID Peserta',1,0,'C',1);
	$pdf->cell(50,6,'Nama',1,0,'C',1);
	$pdf->cell(18,6,'Premi',1,0,'C',1);
	$pdf->cell(20,6,'Nilai CN',1,0,'C',1);
	$pdf->cell(16,6,'Tgl Bayar',1,0,'C',1);
	$pdf->cell(15,6,'Klaim',1,0,'C',1);
	$pdf->cell(50,6,'Cabang',1,0,'C',1);

if ($_REQUEST['tgl1'])		{	$satu = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['idpolis'])	{	$duaa = 'AND id_nopol = "'.$_REQUEST['idpolis'].'"';	}
if ($_REQUEST['typeklaim'])	{	$tiga = 'AND type_claim = "'.$_REQUEST['typeklaim'].'"';	}

/* 160318
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
}
*/
//$er_data = mysql_query('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_nopol="'.$_REQUEST['subcat'].'" AND id_cost="'.$_REQUEST['cat'].'" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del IS NULL ORDER BY tgl_createcn ASC');

$cekCentral = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = mysql_query('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabangCN .= ' OR fu_ajk_cn.id_cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = mysql_query('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentralCN .= 'OR (fu_ajk_cn.id_cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentralCN = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentralCN.')';

}else{
	if ($metCentralCabangCN=="") {
		$metCabangCentralCN = 'AND fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentralCN = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabangCN.')';
	}
}
		//CEK DATA CABANG CENTRAL;
$er_data = mysql_query('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$_REQUEST['cat'].'" '.$metCabangCentralCN.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL ORDER BY tgl_createcn DESC, id_nopol DESC');
while ($mamet = mysql_fetch_array($er_data))
{

if ($mamet['type_claim']=="Death") {
	$_typeklaimnya = "Meninggal";
}else{
	$_typeklaimnya = $mamet['type_claim'];
}

	$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND id_klaim="'.$mamet['id'].'"'));
	$metpesertadn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id_cost="'.$mamet['id_cost'].'" AND id_nopol="'.$mamet['id_nopol'].'" AND id="'.$mamet['id_dn'].'"'));
	$cell[$i][0] = $metpesertadn['dn_kode'];
	$cell[$i][1] = $mamet['id_cn'];
	$cell[$i][2] = _convertDate($mamet['tgl_createcn']);
	$cell[$i][3] = $metpeserta['id_peserta'];
	$cell[$i][4] = $metpeserta['nama'];
	$cell[$i][5] = duit($metpeserta['totalpremi']);
	$cell[$i][6] = duit($mamet['total_claim']);
	$cell[$i][7] = _convertDate($mamet['tgl_byr_claim']);
	$cell[$i][8] = $_typeklaimnya;
	$cell[$i][9] = $mamet['id_cabang'];
	$i++;
}	$pdf->Ln();
for($j<1;$j<$i;$j++)
{	$pdf->cell(8,6,$j+1,1,0,'C');
	$pdf->cell(30,6,$cell[$j][0],1,0,'C');
	$pdf->cell(30,6,$cell[$j][1],1,0,'C');
	$pdf->cell(16,6,$cell[$j][2],1,0,'C');
	$pdf->cell(18,6,$cell[$j][3],1,0,'C');
	$pdf->cell(50,6,$cell[$j][4],1,0,'L');
	$pdf->cell(18,6,$cell[$j][5],1,0,'R');
	$pdf->cell(20,6,$cell[$j][6],1,0,'R');
	$pdf->cell(16,6,$cell[$j][7],1,0,'C');
	$pdf->cell(15,6,$cell[$j][8],1,0,'C');
	$pdf->cell(50,6,$cell[$j][9],1,0,'L');
	$pdf->Ln();
}
	$pdf->Output("LAPORAN_CREDITNOTE.pdf","I");
		;
		break;

case "eL_pinjaman_peserta":
	function HeaderingExcel($filename) {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$filename" );
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
	}								
	
	HeaderingExcel('Laporan_Nomor_Pinjaman.xls');	
	
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Laporan Nomor Pinjaman');

	$format =& $workbook->add_format();
	$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');

	$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
	$fjudul1 =& $workbook->add_format();	$fjudul1->set_align('vcenter');	$fjudul1->set_align('right');	$fjudul1->set_bold();

	
	$worksheet1->write_string(0, 0, "LAPORAN NOMOR PINJAMAN",$fjudul);
	$worksheet1->merge_cells(0, 0, 0, 17);	
	//$worksheet1->write_string(1, 0, $_REQUEST['jnspinjaman']);
	//$worksheet1->set_row(2, 13);
	$worksheet1->set_column(2, 0, 5);		$worksheet1->write_string(2, 0, "NO", $format);
	$worksheet1->set_column(2, 1, 5);		$worksheet1->write_string(2, 1, "PRODUK", $format);
	$worksheet1->set_column(2, 2, 15);	$worksheet1->write_string(2, 2, "NOMOR DN", $format);
	$worksheet1->set_column(2, 3, 15);	$worksheet1->write_string(2, 3, "Tanggal DN", $format);
	$worksheet1->set_column(2, 4, 15);	$worksheet1->write_string(2, 4, "NO REG", $format);
	$worksheet1->set_column(2, 5, 15);	$worksheet1->write_string(2, 5, "NAMA DEBITUR", $format);
	$worksheet1->set_column(2, 6, 15);	$worksheet1->write_string(2, 6, "SPK/SKKT", $format);
	$worksheet1->set_column(2, 7, 10);	$worksheet1->write_string(2, 7, "CABANG", $format);
	$worksheet1->set_column(2, 8, 10);	$worksheet1->write_string(2, 8, "TGL LAHIR", $format);
	$worksheet1->set_column(2, 9, 5);		$worksheet1->write_string(2, 9, "USIA", $format);
	$worksheet1->set_column(2, 10, 10);	$worksheet1->write_string(2, 10, "PLAFOND", $format);
	$worksheet1->set_column(2, 11, 15);	$worksheet1->write_string(2, 11, "MULAI ASURANSI", $format);
	$worksheet1->set_column(2, 12, 5);	$worksheet1->write_string(2, 12, "JK.W", $format);			
	$worksheet1->set_column(2, 13, 15);	$worksheet1->write_string(2, 13, "PREMI", $format);
	$worksheet1->set_column(2, 14, 15);	$worksheet1->write_string(2, 14, "EM", $format);
	$worksheet1->set_column(2, 15, 15);	$worksheet1->write_string(2, 15, "TOTAL PREMI", $format);
	$worksheet1->set_column(2, 16, 15);	$worksheet1->write_string(2, 16, "NO PINJAMAN", $format);
	$worksheet1->set_column(2, 17, 15);	$worksheet1->write_string(2, 17, "SUMBER DANA", $format);
	
	$q = mysql_fetch_array(mysql_query("SELECT * FROM pengguna WHERE nm_user='".$_REQUEST['user']."'"));

	if($_REQUEST['jnspinjaman'] == 'outstanding'){
		$jnspinjaman = " and ifnull(nopinjaman,'') = ''";	
	}elseif($_REQUEST['jnspinjaman'] == 'notblank'){
		$jnspinjaman = " and ifnull(nopinjaman,'') != ''";	
	}else{
		$jnspinjaman = "";
	}


	if($_REQUEST['tglakad1'] != '' and $_REQUEST['tglakad2'] != ''){
		$date = " and kredit_tgl BETWEEN '".$_REQUEST['tglakad1']."' and '".$_REQUEST['tglakad2']."'";
	}else{
		$date = "";
	}

	//cek cabang central
	$qcabang = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_cabang where name = '".$q['cabang']."' and del is NULL"));
	$qcabangcntrl = "SELECT name FROM fu_ajk_cabang where centralcbg = '".$qcabang['id']."' and del is NULL";

	if($_REQUEST['selectcabang'] != ''){
		if($_REQUEST['selectcabang'] == 'all'){
			if(mysql_num_rows(mysql_query($qcabangcntrl))>1){
				$cabang = " (fu_ajk_peserta.cabang in(".$qcabangcntrl.") OR fu_ajk_peserta.cabang = '".$q['cabang']."')";
			}else{
				$cabang = " fu_ajk_peserta.cabang = '".$q['cabang']."'";
			}
		}else{
			$cabang = " fu_ajk_peserta.cabang = '".$_REQUEST['selectcabang']."'";
		}				
	}else{
		$cabang = " fu_ajk_peserta.cabang = '".$q['cabang']."'";
	}

	if($_REQUEST['spaj'] != ''){
		$spak = " AND fu_ajk_peserta.spaj = '".$_REQUEST['spaj']."'";
	}else{
		$spak = "";
	}

	$query = "SELECT fu_ajk_dn.dn_kode,
									 fu_ajk_dn.tgl_createdn,
									 fu_ajk_peserta.id_peserta,
									 fu_ajk_peserta.nama,
									 fu_ajk_peserta.cabang,
									 fu_ajk_peserta.spaj,
									 fu_ajk_peserta.tgl_lahir,
									 fu_ajk_peserta.usia,
									 fu_ajk_peserta.kredit_jumlah,
									 fu_ajk_peserta.kredit_tgl,
									 fu_ajk_peserta.kredit_tenor,
									 fu_ajk_peserta.premi,
									 fu_ajk_peserta.ext_premi,
									 fu_ajk_peserta.totalpremi,
									 fu_ajk_peserta.nopinjaman,
									 fu_ajk_peserta.sumberdana,
									 fu_ajk_polis.nmproduk
						FROM fu_ajk_peserta
								 INNER JOIN fu_ajk_dn
								 ON fu_ajk_dn.id = fu_ajk_peserta.id_dn		 
								 INNER JOIN fu_ajk_polis
								 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
						WHERE fu_ajk_peserta.del is NULL and
									fu_ajk_dn.del is NULL and
									".$cabang."
									".$jnspinjaman."
									".$spak."
									".$date."";

	/*$worksheet1->write_string(1, 0, $cabang);
	$worksheet1->write_string(1, 1, $jnspinjaman);
	$worksheet1->write_string(1, 2, $spak);
	$worksheet1->write_string(1, 3, $date);*/
	$baris = 3;
	$er_data = mysql_query($query."ORDER BY tgl_createdn DESC");

	while ($mamet = mysql_fetch_array($er_data)){
		$worksheet1->write_string($baris, 0, ++$no);
		$worksheet1->write_string($baris, 1, $mamet['nmproduk']);
		$worksheet1->write_string($baris, 2, $mamet['dn_kode']);
		$worksheet1->write_string($baris, 3, _convertDate($mamet['tgl_createdn']));
		$worksheet1->write_string($baris, 4, $mamet['id_peserta']);
		$worksheet1->write_string($baris, 5, $mamet['nama']);
		$worksheet1->write_string($baris, 6, $mamet['spaj']);
		$worksheet1->write_string($baris, 7, $mamet['cabang']);
		$worksheet1->write_string($baris, 8, _convertDate($mamet['tgl_lahir']));
		$worksheet1->write_number($baris, 9, $mamet['usia']);
		$worksheet1->write_number($baris, 10, $mamet['kredit_jumlah'], $fjudul1);
		$worksheet1->write_string($baris, 11, _convertDate($mamet['kredit_tgl']));
		$worksheet1->write_number($baris, 12, $mamet['kredit_tenor']);		
		$worksheet1->write_number($baris, 13, round($mamet['premi']), $fjudul1);
		$worksheet1->write_number($baris, 14, round($mamet['ext_premi']), $fjudul1);
		$worksheet1->write_number($baris, 15, round($mamet['totalpremi']), $fjudul1);
		$worksheet1->write_string($baris, 16, $mamet['nopinjaman']);
		$worksheet1->write_string($baris, 17, $mamet['sumberdana']);		
		$baris++;
	}
	$workbook->close();
	;
break;

case "eL_rmf":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

	$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {	HeaderingExcel('Laporan_'.$stringcost.'_RMF.xls');	}else{	HeaderingExcel('Laporan_'.$stringcost.'_RMF.xls');	}

	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Laporan RMF');

	$format =& $workbook->add_format();
	$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');

	$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
	$fjudul1 =& $workbook->add_format();	$fjudul1->set_align('vcenter');	$fjudul1->set_align('right');	$fjudul1->set_bold();

	$worksheet1->merge_cells(0, 0, 0, 15);	$worksheet1->write_string(0, 0, "LAPORAN RISK MANAGEMENT FUND (RMF) PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
	$worksheet1->merge_cells(1, 0, 1, 15);	$worksheet1->write_string(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
	$worksheet1->merge_cells(2, 0, 2, 15);	$worksheet1->write_string(2, 0, strtoupper($met_c['name']), $fjudul);

if ($_REQUEST['cat'])		{	$satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])	{	$duaa = 'AND id_polis ="' . $_REQUEST['subcat'] . '"';	}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paid'])		{	$empt = 'AND status_bayar ="' . $_REQUEST['paid'] . '"';	}
if ($_REQUEST['status'])	{	$lima = 'AND status_aktif ="' . $_REQUEST['status'] . '"';	}
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}

	$met_rmf_paid = mysql_query('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, premi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="1" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_paid_ = mysql_fetch_array($met_rmf_paid)) {
	$_metrmfnya_paid += ROUND($met_rmf_paid_['rmf']);
}

	$met_rmf_unpaid = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_bayar="0" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_unpaid_ = mysql_fetch_array($met_rmf_unpaid)) {
	$cek_rate_RMF = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_rmf_unpaid_['id_cost'].'" AND id_polis="'.$met_rmf_unpaid_['id_polis'].'" AND tenor="'.$met_rmf_unpaid_['kredit_tenor'].'"'));
	$mametRMF = $met_rmf_unpaid_['kredit_jumlah'] * $cek_rate_RMF['rate'] / 1000;
	$_metrmfnya_unpaid += ROUND($mametRMF);
}
	$metRMFtotal = $_metrmfnya_paid + $_metrmfnya_unpaid;

	$worksheet1->merge_cells(4, 0, 4, 1);	$worksheet1->write_string(4, 0, "PAID", $fjudul, 1);	$worksheet1->merge_cells(4, 2, 4, 2);	$worksheet1->write_string(4, 2, duit($_metrmfnya_paid), $fjudul1, 1);
	$worksheet1->merge_cells(5, 0, 5, 1);	$worksheet1->write_string(5, 0, "UNPAID", $fjudul, 1);	$worksheet1->merge_cells(5, 2, 5, 2);	$worksheet1->write_string(5, 2, duit($_metrmfnya_unpaid), $fjudul1, 1);
	$worksheet1->merge_cells(6, 0, 6, 1);	$worksheet1->write_string(6, 0, "TOTAL", $fjudul, 1);	$worksheet1->merge_cells(6, 2, 6, 2);	$worksheet1->write_string(6, 2, duit($metRMFtotal), $fjudul1, 1);

	$worksheet1->set_row(8, 13);
	$worksheet1->set_column(8, 0, 5);	$worksheet1->write_string(8, 0, "NO", $format);
	$worksheet1->set_column(8, 1, 15);	$worksheet1->write_string(8, 1, "NOMOR DN", $format);
	$worksheet1->set_column(8, 2, 15);	$worksheet1->write_string(8, 2, "TGL DN", $format);
	$worksheet1->set_column(8, 3, 15);	$worksheet1->write_string(8, 3, "ID PESERTA", $format);
	$worksheet1->set_column(8, 4, 15);	$worksheet1->write_string(8, 4, "NAMA DEBITUR", $format);
	$worksheet1->set_column(8, 5, 10);	$worksheet1->write_string(8, 5, "CABANG", $format);
	$worksheet1->set_column(8, 6, 10);	$worksheet1->write_string(8, 6, "TGL LAHIR", $format);
	$worksheet1->set_column(8, 7, 5);	$worksheet1->write_string(8, 7, "USIA", $format);
	$worksheet1->set_column(8, 8, 10);	$worksheet1->write_string(8, 8, "PLAFOND", $format);
	$worksheet1->set_column(8, 9, 5);	$worksheet1->write_string(8, 9, "JK.W", $format);
	$worksheet1->set_column(8, 10, 15);	$worksheet1->write_string(8, 10, "MULAI ASURANSI", $format);
	$worksheet1->set_column(8, 11, 15);	$worksheet1->write_string(8, 11, "AKHIR ASURANSI", $format);
	$worksheet1->set_column(8, 12, 15);	$worksheet1->write_string(8, 12, "STATUS", $format);
	$worksheet1->set_column(8, 13, 15);	$worksheet1->write_string(8, 13, "TOTAL PREMI", $format);
	$worksheet1->set_column(8, 14, 15);	$worksheet1->write_string(8, 14, "RMF (paid)", $format);
	$worksheet1->set_column(8, 15, 15);	$worksheet1->write_string(8, 15, "RMF (unpaid)", $format);


$baris = 9;
$er_data = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del IS NULL ORDER BY kredit_tgl ASC');
while ($mamet = mysql_fetch_array($er_data))
{
	$cekdatadn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
	$cekdataret = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$dua.' AND tenor="'.$mamet['kredit_tenor'].'"'));

	/*NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
	   $met_rmf = mysql_fetch_array(mysql_query('SELECT id, id_cost, rmf FROM fu_ajk_polis WHERE id_cost="'.$mamet['id_cost'].'" AND id="'.$mamet['id_polis'].'"'));		//NILAI RMF
	   $er_rmf = $mamet['totalpremi'] * $met_rmf['rmf']/100;
	   NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101*/

if ($mamet['status_bayar']=="1") {
	$metrmfnya_paid = ROUND($mamet['rmf']);
	$metrmfnya_unpaid = '';
	$met_bayar = "PAID";
}else{
	$metrmfnya_paid ='';
	$cek_rate_RMF = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'"'));
	$metrmfnya_unpaid = ROUND($mamet['kredit_jumlah']) * $cek_rate_RMF['rate'] / 1000;
	$met_bayar = "UNPAID";
}
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $cekdatadn['dn_kode']);
	$worksheet1->write_string($baris, 2, $cekdatadn['tgl_createdn']);
	$worksheet1->write_string($baris, 3, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 4, $mamet['nama']);
	$worksheet1->write_string($baris, 5, $mamet['cabang']);
	$worksheet1->write_string($baris, 6, _convertDate($mamet['tgl_lahir']));
	$worksheet1->write_string($baris, 7, $mamet['usia']);
	$worksheet1->write_string($baris, 8, duit($mamet['kredit_jumlah']), $fjudul1);
	$worksheet1->write_string($baris, 9, $mamet['kredit_tenor']);
	$worksheet1->write_string($baris, 10, _convertDate($mamet['kredit_tgl']));
	$worksheet1->write_string($baris, 11, _convertDate($mamet['kredit_akhir']));
	$worksheet1->write_string($baris, 12, $met_bayar);
	$worksheet1->write_string($baris, 13, duit(round($mamet['totalpremi'])), $fjudul1);
	$worksheet1->write_string($baris, 14, duit($metrmfnya_paid), $fjudul1);
	$worksheet1->write_string($baris, 15, duit($metrmfnya_unpaid), $fjudul1);
	$baris++;

	$tTotalPlafond += $mamet['kredit_jumlah'];
	$tTotalPremi += $mamet['totalpremi'];
	$tTotalPRM_paid += $metrmfnya_paid;
	$tTotalPRM_unpaid += $metrmfnya_unpaid;
}
$worksheet1->merge_cells($baris, 0, $baris, 7);		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);
$worksheet1->write_string($baris, 8, duit($tTotalPlafond), $fjudul1);
$worksheet1->merge_cells($baris, 9, $baris, 12);		$worksheet1->write_string($baris, 9, "", $fjudul);
$worksheet1->write_string($baris, 13, duit($tTotalPremi), $fjudul1);
$worksheet1->write_string($baris, 14, duit($tTotalPRM_paid), $fjudul1);
$worksheet1->write_string($baris, 15, duit($tTotalPRM_unpaid), $fjudul1);
$workbook->close();
	;
	break;

case "memocn":
	$pdf=new FPDF('P','mm','A4');
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('helvetica','B',10);
	$pdf->Text(95, 15,'MEMORANDUM');
	$metIDmemo = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cnmemo WHERE id="'.$_REQUEST['idMemo'].'"'));
	$metPDFfile = 'MEMORANDUM_CN_'.$metIDmemo['creatememo'].'_'.$metIDmemo['cabangmemo'];
	$met_Filememo = mysql_query('UPDATE fu_ajk_cnmemo SET filememo="'.$metPDFfile.'" WHERE id="'.$_REQUEST['idMemo'].'"');

	//$idmemonya = $metIDmemo['id'] + 1;
	$tglMemo = explode("-", $metIDmemo['creatememo']);
	$pdf->Text(85, 19,'NO.'.$metIDmemo['kodememo'].'');

	$jMetMemo = mysql_fetch_array(mysql_query('SELECT COUNT(fu_ajk_cn.id_peserta) AS jData FROM fu_ajk_cn WHERE fu_ajk_cn.tgl_createcn = "'.$metIDmemo['creatememo'].'" AND fu_ajk_cn.id_cabang = "'.$metIDmemo['cabangmemo'].'" GROUP BY fu_ajk_cn.tgl_createcn, fu_ajk_cn.id_cabang'));
	$pdf->SetFont('helvetica','',10);
	$pdf->Text(75, 25,'Kepada');	$pdf->Text(90, 25,':');		$pdf->Text(93, 25,'Bag. Adm Kredit/Sundries');
	$pdf->Text(75, 29,'Dari');		$pdf->Text(90, 29,':');		$pdf->Text(93, 29,'Bag. Asuransi');
	$pdf->Text(75, 33,'Perihal');	$pdf->Text(90, 33,':');		$pdf->Text(93, 33,'Refund Asuransi ADONAI '.$jMetMemo['jData'].' DEB '.$metIDmemo['cabangmemo']);
	$pdf->Text(75, 37,'Tanggal');	$pdf->Text(90, 37,':');		$pdf->Text(93, 37,$tglMemo[2].' '.bulan($tglMemo[1]).' '.$tglMemo[0]);
	$pdf->Line(10, 40, 200, 40);
	$pdf->Ln(33);
	$pdf->MultiCell(0,4,'Sehubungan dengan telah disetujuinya Refund Asuransi dari PT. ADONAI untuk debitur atas nama '.$jMetMemo['jData'].' DEB '.$metIDmemo['cabangmemo'].' maka dengan ini kami mohon bantuannya untuk transaksi sbb:',0,'L');


/*
   $metPDFfile = 'MEMORANDUM_CN_'.$_REQUEST['tglCN'].'_'.$_REQUEST['cabCN'];
   $memomamet = mysql_query('INSERT INTO fu_ajk_cnmemo SET kodememo="'.$idmemonya.'/MEMO/ASR-SPA/'.KonDecRomawi($tglMemo[1]).'/'.substr($tglMemo[0],2).'",
   creatememo = "'.$_REQUEST['tglCN'].'",
   cabangmemo = "'.$_REQUEST['cabCN'].'",
   filememo = "'.$metPDFfile.'",
   input_by = "'.$_REQUEST['u'].'",
   input_date = "'.$futgl.'"');
*/
$metMemoData = mysql_query('SELECT fu_ajk_costumer.`name`,
										  fu_ajk_polis.nmproduk,
										  fu_ajk_polis.rek_1,
										  fu_ajk_cn.id_peserta,
										  fu_ajk_peserta.id,
										  fu_ajk_peserta.nama,
										  fu_ajk_dn.dn_kode,
										  fu_ajk_cn.tgl_claim,
										  fu_ajk_cn.type_claim,
										  fu_ajk_peserta.totalpremi,
										  fu_ajk_cn.total_claim
										  FROM fu_ajk_cn
										  LEFT JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
										  LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
										  LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
										  LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
										  WHERE fu_ajk_cn.del IS NULL AND fu_ajk_cn.tgl_createcn = "'.$metIDmemo['creatememo'].'" AND fu_ajk_cn.id_cabang = "'.$metIDmemo['cabangmemo'].'" ');
while ($metMemoData_ = mysql_fetch_array($metMemoData)) {
	$cell[$i][0] = $metMemoData_['nama'];
	$cell[$i][1] = $metMemoData_['total_claim'];
	$i++;
}$pdf->Ln();
for($j<1;$j<$i;$j++)
{
	$pdf->cell(100,5,$cell[$j][0],0,0,'L');
	$pdf->cell(30,5,duit($cell[$j][1]),0,0,'R');
	$tNilaiCN += $cell[$j][1];
	$pdf->Ln();
}
	$pdf->SetFont('helvetica','B',10);
	$pdf->cell(100,5);
	$pdf->cell(30,5,duit($tNilaiCN),'T',0,'R',0);$pdf->Ln();$pdf->Ln();

$metRekCabang = mysql_fetch_array(mysql_query('SELECT fu_ajk_rekening.id,
															 fu_ajk_rekening.rek_dn_cabang,
															 fu_ajk_rekening.rek_dn_cabang_name,
															 fu_ajk_rekening.rek_dn_nomor,
															 fu_ajk_rekening.rek_cn_cabang,
															 fu_ajk_rekening.rek_cn_cabang_name,
															 fu_ajk_rekening.rek_cn_nomor,
															 fu_ajk_rekening.pic_cab,
															 fu_ajk_rekening.pic_cab_jabatan,
															 fu_ajk_cabang.`name`,
															 fu_ajk_costumer.pic,
															 fu_ajk_costumer.pic2,
															 fu_ajk_costumer.rekdebet,
															 fu_ajk_costumer.rekdebet_an,
															 fu_ajk_costumer.rekcredit,
															 fu_ajk_costumer.rekcredit_an,
															 fu_ajk_costumer.picjabatan,
															 fu_ajk_costumer.picjabatan2
															 FROM fu_ajk_rekening
															 LEFT JOIN fu_ajk_costumer ON fu_ajk_rekening.id_cost = fu_ajk_costumer.id
															 LEFT JOIN fu_ajk_cabang ON fu_ajk_rekening.id_cost = fu_ajk_cabang.id_cost AND fu_ajk_rekening.cabang = fu_ajk_cabang.id
															 WHERE fu_ajk_rekening.id != "" AND fu_ajk_rekening.id_cost="'.$metIDmemo['idcost'].'" AND fu_ajk_cabang.name="'.$metIDmemo['cabangmemo'].'" AND fu_ajk_cabang.del IS NULL'));

	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,5,'DEBET','T',0,'L',0);	$pdf->cell(100,5,'REK NO. 1000660431','T',0,'L',0);		$pdf->SetFont('helvetica','B',10);	$pdf->cell(5,5,'Rp.','T',0,'L',0);	$pdf->cell(30,5,duit($tNilaiCN),'T',1,'R',0);

	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,3,' ',0,'L',0);			$pdf->cell(100,3,'PT. ADONAI PIALANG ASURANSI','',1,'L',0);$pdf->Ln();

	$pdf->cell(30,5,'KREDIT','',0,'L',0);	$pdf->cell(100,5,'REK KS NO. '.$metRekCabang['rekcredit'].'','',0,'L',0);		$pdf->SetFont('helvetica','B',10);	$pdf->cell(5,5,'Rp.','',0,'L',0);	$pdf->cell(30,5,duit($tNilaiCN),'',1,'R',0);
	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,3,' ',0,'L',0);			$pdf->cell(100,3,'('.$metRekCabang['rekcredit_an'].')','',1,'L',0);$pdf->Ln();

	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,5,'DEBET','T',0,'L',0);	$pdf->cell(100,5,'REK NO. '.$metRekCabang['rekdebet'].'','T',0,'L',0);		$pdf->SetFont('helvetica','B',10);	$pdf->cell(5,5,'Rp.','T',0,'L',0);	$pdf->cell(30,5,duit($tNilaiCN),'T',1,'R',0);

	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,3,' ',0,'L',0);			$pdf->cell(100,3,$metRekCabang['rekdebet_an'],'',1,'L',0);$pdf->Ln();

	$pdf->cell(30,5,'KREDIT','',0,'L',0);	$pdf->cell(100,5,'REK NO. '.$metRekCabang['rek_cn_nomor'].'','',0,'L',0);		$pdf->SetFont('helvetica','B',10);	$pdf->cell(5,5,'Rp.','',0,'L',0);	$pdf->cell(30,5,duit($tNilaiCN),'',1,'R',0);
	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,3,' ',0,'L',0);			$pdf->cell(100,3,'('.$metRekCabang['rek_cn_cabang_name'].')','',1,'L',0);$pdf->Ln();

	$pdf->cell(165,5,'','T',0,'L',0);$pdf->Ln();
	$pdf->MultiCell(0,4,'Demikian hal ini disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.',0,'L');
	$pdf->Ln(20);
	$pdf->cell(95,5,$metRekCabang['pic'],'',0,'L',0);			$pdf->cell(95,5,$metRekCabang['pic2'],'',1,'L',0);
	$pdf->cell(95,5,$metRekCabang['picjabatan'],'',0,'L',0);	$pdf->cell(95,5,$metRekCabang['picjabatan2'],'',1,'L',0);

	$metPDFfileDir = $metpath.''.$metPDFfile;
	$pdf->Output($metPDFfileDir.".pdf","F");
	$pdf->Output("MEMORANDUM _CN_".$_REQUEST['tglCN']."_".$_REQUEST['cabCN'].".pdf","I");
	;
	break;

	case "download_klaim":
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		
		if (isset($_REQUEST['nr'])) {	
			
			$q = mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="' . $_REQUEST['nr'] . '"'));	
		
		}
		if ($q['level'] == "99" OR $q['level'] == "6") { 
			$typedata = 'AND type_data="SPK"'; 
		} else { 
			$typedata = 'AND type_data!="SPK"'; 
		}
		
		HeaderingExcel('Data_Klaim.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet('Laporan Klaim');
	
		$format =& $workbook->add_format();
		$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');
	
		$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
		$fjudul1 =& $workbook->add_format();	$fjudul1->set_align('vcenter');	$fjudul1->set_align('right');	$fjudul1->set_bold();

		if ($_REQUEST['req']=="btl") {
			$typeKlaim = 'AND fu_ajk_cn.type_claim="Batal"';
			$typereq = "Pembatalan";
		}elseif ($_REQUEST['req']=="ref") {
			$typeKlaim = 'AND fu_ajk_cn.type_claim="Refund"';
			$typereq = "Refund";
		}else{
			$typeKlaim = 'AND fu_ajk_cn.type_claim="Death"';
			$typereq = "Meninggal";
		}
		$worksheet1->merge_cells(0, 0, 0, 15);	$worksheet1->write_string(0, 0, "DATA ".strtoupper($typereq)." ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
		//$worksheet1->merge_cells(1, 0, 1, 15);	$worksheet1->write_string(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
		//$worksheet1->merge_cells(2, 0, 2, 15);	$worksheet1->write_string(2, 0, strtoupper($met_c['name']), $fjudul);
	
		$worksheet1->set_row(4, 15);
		$worksheet1->set_column(4, 0, 5);	$worksheet1->write_string(4, 0, "NO", $format);
		$worksheet1->set_column(4, 1, 15);	$worksheet1->write_string(4, 1, "PRODUK", $format);
		$worksheet1->set_column(4, 2, 15);	$worksheet1->write_string(4, 2, "DEBIT NOTE", $format);
		$worksheet1->set_column(4, 3, 15);	$worksheet1->write_string(4, 3, "CREDIT NOTE", $format);
		$worksheet1->set_column(4, 4, 15);	$worksheet1->write_string(4, 4, "IDPESERTA", $format);
		$worksheet1->set_column(4, 5, 10);	$worksheet1->write_string(4, 5, "NAMA", $format);
		$worksheet1->set_column(4, 6, 10);	$worksheet1->write_string(4, 6, "TGL LAHIR", $format);
		$worksheet1->set_column(4, 7, 5);	$worksheet1->write_string(4, 7, "USIA", $format);
		$worksheet1->set_column(4, 8, 10);	$worksheet1->write_string(4, 8, "PLAFOND", $format);
		$worksheet1->set_column(4, 9, 5);	$worksheet1->write_string(4, 9, "JK.W", $format);
		$worksheet1->set_column(4, 10, 15);	$worksheet1->write_string(4, 10, "MULAI ASURANSI", $format);
		$worksheet1->set_column(4, 11, 15);	$worksheet1->write_string(4, 11, "AKHIR ASURANSI", $format);
		$worksheet1->set_column(4, 12, 10);	$worksheet1->write_string(4, 12, "TOTAL PREMI", $format);
		$worksheet1->set_column(4, 13, 15);	$worksheet1->write_string(4, 13, "TYPE", $format);
		$worksheet1->set_column(4, 14, 15);	$worksheet1->write_string(4, 14, "DOL", $format);
		$worksheet1->set_column(4, 15, 15);	$worksheet1->write_string(4, 15, "MA-J", $format);
		$worksheet1->set_column(4, 16, 15);	$worksheet1->write_string(4, 16, "MA-S", $format);
		$worksheet1->set_column(4, 17, 15);	$worksheet1->write_string(4, 17, "CABANG", $format);
		
		if ($_REQUEST['cat']) 		{	$satu = 'AND fu_ajk_cn.id_regional LIKE "%' . $_REQUEST['cat'] . '%"'; }
		if ($_REQUEST['idpolis']) 	{	$dua = 'AND fu_ajk_cn.id_nopol LIKE "%' . $_REQUEST['idpolis'] . '%"';	}
		if ($_REQUEST['typeclaim']) {	$tiga = 'AND fu_ajk_cn.type_claim LIKE "%' . $_REQUEST['typeclaim'] . '%"';	}
		if ($_REQUEST['metcn']) 	{	$empat = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['metcn'] . '%"'; }
		if ($_REQUEST['metdn']) 	{	$lima = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"';	}
		if ($_REQUEST['snama']) 	{	$enam = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['snama'] . '%"';	}
		if ($_REQUEST['tgl1']) 		{	$tujuh = 'AND fu_ajk_cn.tgl_createcn between "' . $_REQUEST['tgl1'] . '" and "' . $_REQUEST['tgl2'] . '"';	}
		
		
		$cekCentral = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
		$cekCentral_ = mysql_query('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
		while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
			$metCentralCabang .= ' OR fu_ajk_cn.id_cabang ="'.$cekCentral__['name'].'"';
		}
		
		if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {
			$data = mysql_query('SELECT fu_ajk_cn.id,
								   fu_ajk_cn.id_cn,
								   fu_ajk_costumer.name AS "perusahaan",
								   fu_ajk_polis.nmproduk,
								   fu_ajk_cn.id_peserta,
								   fu_ajk_cn.id_regional,
								   fu_ajk_cn.id_cabang,
								   fu_ajk_cn.type_claim,
								   fu_ajk_cn.tgl_claim,
								   fu_ajk_cn.total_claim,
								   fu_ajk_cn.tgl_byr_claim,
								   fu_ajk_cn.confirm_claim,
								   fu_ajk_cn.id_dn,
								   fu_ajk_dn.dn_kode,
								   fu_ajk_peserta.nama,
								   fu_ajk_peserta.tgl_lahir,
								   fu_ajk_peserta.usia,
								   fu_ajk_peserta.kredit_tgl,
								   fu_ajk_peserta.kredit_jumlah,
								   fu_ajk_peserta.kredit_tenor,
								   fu_ajk_peserta.kredit_akhir,
								   fu_ajk_peserta.totalpremi,
								   fu_ajk_peserta.status_aktif,
								   fu_ajk_peserta.status_peserta,
								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
								   fu_ajk_peserta.cabang
							FROM fu_ajk_cn
							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
							WHERE fu_ajk_cn.del IS NULL AND
								  fu_ajk_peserta.del IS NULL AND
								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
								  '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$tujuh.'
							ORDER BY fu_ajk_cn.input_date DESC');
			}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
				$cekCentral = mysql_query('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
										  FROM fu_ajk_regional
										  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
										  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
				while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
					$metCabangCentral .= 'OR (fu_ajk_cn.id_cabang ="'.$cekCentral__['cabang'].'")';
				}
				
				$metCabangCentral = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
				$data = mysql_query('SELECT fu_ajk_cn.id,
								   fu_ajk_cn.id_cn,
								   fu_ajk_costumer.name AS "perusahaan",
								   fu_ajk_polis.nmproduk,
								   fu_ajk_cn.id_peserta,
								   fu_ajk_cn.id_regional,
								   fu_ajk_cn.id_cabang,
								   fu_ajk_cn.type_claim,
								   fu_ajk_cn.tgl_claim,
								   fu_ajk_cn.total_claim,
								   fu_ajk_cn.tgl_byr_claim,
								   fu_ajk_cn.confirm_claim,
								   fu_ajk_cn.id_dn,
								   fu_ajk_dn.dn_kode,
								   fu_ajk_peserta.nama,
								   fu_ajk_peserta.tgl_lahir,
								   fu_ajk_peserta.usia,
								   fu_ajk_peserta.kredit_tgl,
								   fu_ajk_peserta.kredit_jumlah,
								   fu_ajk_peserta.kredit_tenor,
								   fu_ajk_peserta.kredit_akhir,
								   fu_ajk_peserta.totalpremi,
								   fu_ajk_peserta.status_aktif,
								   fu_ajk_peserta.status_peserta,
								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
								   fu_ajk_peserta.cabang
							FROM fu_ajk_cn
							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
							WHERE fu_ajk_cn.del IS NULL AND
								  fu_ajk_peserta.del IS NULL AND
								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
								  '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$tujuh.'
							ORDER BY fu_ajk_cn.input_date DESC');
			
		}else{
			if ($metCentralCabang=="") {
				$metCabangCentral = 'AND fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';
			}else{
				$metCabangCentral = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
			}
			$data = mysql_query('SELECT fu_ajk_cn.id,
								   fu_ajk_cn.id_cn,
								   fu_ajk_costumer.name AS "perusahaan",
								   fu_ajk_polis.nmproduk,
								   fu_ajk_cn.id_peserta,
								   fu_ajk_cn.id_regional,
								   fu_ajk_cn.id_cabang,
								   fu_ajk_cn.type_claim,
								   fu_ajk_cn.id_dn,
								   fu_ajk_cn.tgl_claim,
								   fu_ajk_cn.total_claim,
								   fu_ajk_cn.tgl_byr_claim,
								   fu_ajk_cn.confirm_claim,
								   fu_ajk_dn.dn_kode,
								   fu_ajk_peserta.nama,
								   fu_ajk_peserta.tgl_lahir,
								   fu_ajk_peserta.usia,
								   fu_ajk_peserta.kredit_tgl,
								   fu_ajk_peserta.kredit_jumlah,
								   fu_ajk_peserta.kredit_tenor,
								   fu_ajk_peserta.kredit_akhir,
								   fu_ajk_peserta.totalpremi,
								   fu_ajk_peserta.status_aktif,
								   fu_ajk_peserta.status_peserta,
								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
								   fu_ajk_peserta.cabang,
								   DATE_FORMAT(fu_ajk_cn.input_date,"%Y%m") as id_nya
							FROM fu_ajk_cn
							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
							WHERE fu_ajk_cn.del IS NULL AND
								  fu_ajk_peserta.del IS NULL AND
								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
								  '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$tujuh.'
							ORDER BY fu_ajk_cn.id DESC,  fu_ajk_cn.input_date DESC');
	}
	$no=0;
	$baris=5;
		while ($fudata = mysql_fetch_array($data)) {
			
			$awal = explode ("-", $fudata['kredit_tgl']);
			$hari = $awal[2];	$bulan = $awal[1];	$tahun = $awal[0];
			$akhir = explode ("-", $fudata['tgl_claim']);
			$hari2 = $akhir[2];	$bulan2 = $akhir[1];	$tahun2 = $akhir[0];
			$jhari = (mktime (0, 0, 0, $bulan2, $hari2, $tahun2) - mktime (0, 0, 0, $bulan, $hari, $tahun)) / 86400;
			$sisahr = floor($jhari);
			$sisabulan = ceil($sisahr / 30.4375);

			$masisa = $fudata['tenornya'] - $sisabulan;
			
			if(empty($fudata['id_cn'])){
				$id_cn='0000000000'.$fudata['id'];
				$id_cn='ACN'.$fudata['id_nya'].substr($id_cn, -10);
			}else{
				$id_cn=$fudata['id_cn'];
			}
			$worksheet1->write_string($baris, 0, ++$no);
			$worksheet1->write_string($baris, 1,  $fudata['nmproduk']);
			$worksheet1->write_string($baris, 2, substr($fudata['dn_kode'], 3));
			$worksheet1->write_string($baris, 3, $id_cn);
			$worksheet1->write_string($baris, 4, $fudata['id_peserta']);
			$worksheet1->write_string($baris, 5,  $fudata['nama']);
			$worksheet1->write_string($baris, 6, _convertDate($fudata['tgl_lahir']));
			$worksheet1->write_string($baris, 7, $fudata['usia']);
			$worksheet1->write_string($baris, 8, duit($fudata['kredit_jumlah']));
			$worksheet1->write_string($baris, 9, $fudata['kredit_tenor']);
			$worksheet1->write_string($baris, 10, _convertDate($fudata['kredit_tgl']));
			$worksheet1->write_string($baris, 11, _convertDate($fudata['kredit_akhir']));
			$worksheet1->write_string($baris, 12, duit($fudata['totalpremi']));
			$worksheet1->write_string($baris, 13,  $fudata['type_claim']);
			$worksheet1->write_string($baris, 14,  _convertDate($fudata['tgl_claim']));
			$worksheet1->write_string($baris, 15, $sisabulan);
			$worksheet1->write_string($baris, 16, $masisa);
			$worksheet1->write_string($baris, 17, $fudata['id_cabang']);
			$baris++;
			
		}
		$workbook->close();
		;
		break;
	

	default:
		;
} // switch
?>