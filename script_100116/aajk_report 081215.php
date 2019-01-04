<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
define('FPDF_FONTPATH', 'font/');
include_once "includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');
include "includes/fu6106.php";
require('includes/code39.php');
function datediff($time1, $time2, $precision = 6) {
	// If not numeric then convert texts to unix timestamps
if (!is_int($time1)) {	$time1 = strtotime($time1);	}
if (!is_int($time2)) {	$time2 = strtotime($time2);	}

	// If time1 is bigger than time2
	// Then swap time1 and time2
if ($time1 > $time2) {	$ttime = $time1;
	$time1 = $time2;
	$time2 = $ttime;
}

	// Set up intervals and diffs arrays
	$intervals = array('year','month','day','hour','minute','second');
	$diffs = array();

	// Loop thru all intervals
foreach ($intervals as $interval) {
	// Create temp time from time1 and interval
	$ttime = strtotime('+1 ' . $interval, $time1);
	// Set initial values
	$add = 1;
	$looped = 0;
	// Loop until temp time is smaller than time2
	while ($time2 >= $ttime) {
		// Create new temp time from time1 and interval
		$add++;
		$ttime = strtotime("+" . $add . " " . $interval, $time1);
		$looped++;
	}

	$time1 = strtotime("+" . $looped . " " . $interval, $time1);
	$diffs[$interval] = $looped;
}

	$count = 0;
	$times = array();
	// Loop thru all diffs
foreach ($diffs as $interval => $value) {
	// Break if we have needed precission
	if ($count >= $precision) {
		break;
	}
	// Add value and interval
	// if value is bigger than 0
	if ($value >= 0) {
		// Add s if value is not 1
		if ($value != 1) {
			$interval .= "s";
		}
		// Add value and interval to times array
		//$times[] = $value . " " . $interval;	// DEFAULT
		$times[] = $value;
		$count++;
	}
}

	// Return string with times
	//return implode(", ", $times);	// DEFAULT
	return implode(",", $times);
}
function duitterbilang($value)
{
	$orro = number_format($value, 0, ',', '');
	return $orro;
}
$futgl = date("d M Y");
$keyb = mysql_fetch_array(mysql_query('SELECT id, id_cost, nm_user FROM pengguna WHERE id="'.$_REQUEST['s'].'"'));
switch ($_REQUEST['er']) {
	case "_kwitansi":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['idn'].'"'));
		$metcost = mysql_fetch_array(mysql_query('SELECT id, name, address, printlogo FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));

if ($keyb['id_cost']=="") {
	if ($metcost['printlogo']=="Y") {
		$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
	}else{

	}

}else{
	$pdf->Image('image/adonai_64.gif',10,5);
	$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
	$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
}


		if ($met['dn_status']=="unpaid") {	$headtagihan = $pdf->Text(97, 30,'KWITANSI');	}	else{	$headtagihan = $pdf->Text(88, 30,'TAGIHAN PREMI');	}
		$pdf->SetFont('helvetica','B',12);
		$headtagihan;
		$pdf->Code39(87, 31, $met['dn_kode']);
		//$pdf->Text(90, 40,$met['dn_kode']);
		$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_nopol'].'" AND id_cost="'.$met['id_cost'].'"'));
/* 150601
		$metpeserta = mysql_fetch_array(mysql_query('SELECT id_cost,
															id_polis,
															SUM(IF ((premi + ext_premi) < '.$metpolis['min_premium'].', '.$metpolis['min_premium'].', ROUND(premi, 0))) as tpremi,
															SUM(ext_premi) as sum_expremi,
															SUM(ROUND(totalpremi, 0)) as netttotalpremi,
															id_dn
													FROM fu_ajk_peserta
													WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'" AND id_dn="'.$met['id'].'" AND status_peserta IS NULL GROUP BY id_dn'));

		$_metdiskon = $metpeserta['tpremi'] * $metpolis['discount'] / 100;
		$_metstotal = $metpeserta['tpremi'] - $_metdiskon;
		$_mettotal = $_metstotal + $metpeserta['sum_expremi'] + $metpolis['adminfee'];
*/


		$metpeserta = mysql_fetch_array(mysql_query('SELECT id_cost,
															id_polis,
															ROUND(SUM(premi), 2) as premipokok,
															ROUND(SUM(ext_premi), 2) as sum_expremi,
															ROUND(SUM(cmp), 2) as sum_cmp,
															ROUND(SUM(premi), 2) + ROUND(SUM(ext_premi), 2) + ROUND(SUM(cmp), 2) as totalpremi,
															id_dn
													FROM fu_ajk_peserta
													WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'" AND id_dn="'.$met['id'].'" AND (status_peserta IS NULL OR status_peserta !="Batal") AND del IS NULL GROUP BY id_dn'));

		$_metdiskon = $metpeserta['premipokok'] * $metpolis['discount'] / 100;
		$_subtotal = $metpeserta['premipokok'] + $metpeserta['sum_expremi'] - $_metdiskon;

		$pdf->SetFont('helvetica','',10);
		$pdf->Text(15, 50,'Terima Dari');		$pdf->Text(50, 50,': '.$metcost['name'].' (Cabang : '.$met['id_cabang'].')');
		$pdf->Text(15, 55,'Uang sejumlah');		$pdf->SetFont('helvetica','I',9);$pdf->Text(50, 55,':'.mametbilang(duitterbilang($metpeserta['totalpremi'])).' rupiah', 0);


		$jumlah = $met['j_dl'] + 1;
		$dlupdate = mysql_query('UPDATE fu_ajk_dn SET j_dl="'.$jumlah.'" WHERE id="'.$_REQUEST['idn'].'"');
		$kenalohh = 'USER : '.$keyb['nm_user'].'| Cost : '.$metcost['name'].'| DN : '. $metdnnya['dn_kode'] .'| Datetime : '. $datelog.' '.$timelog.'| IP : '. $alamat_ip.'| Nama Komputer : '.$nama_host.'| Browser : '.$useragent;
		$dlkey = mysql_query('INSERT INTO ajk_dl_logger SET dl_data="'.$kenalohh.'"');

		$pdf->setFillColor(255,255,255);
		$pdf->setFont('helvetica','',10);
		$pdf->setY(60);	$pdf->setX(14);
		//$_metdiskon = $metpeserta['tpremi'] * $metpolis['discount'] / 100;
		$pdf->cell(30,7,'Nama Produk',0,0,'L',1);			$pdf->cell(80,7,': '.$metpolis['nmproduk'].'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);
		$pdf->cell(32,7,'Premi Pokok',0,0,'L',1);			$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($metpeserta['premipokok']),0,0,'R',1);

		$pdf->setY(67);	$pdf->setX(14);
		$pdf->cell(30,7,'No. Kontrak',0,0,'L',1);			$pdf->cell(80,7,': '.$metpolis['nokontrak'].'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);
		$pdf->cell(32,7,'Extra Premi',0,0,'L',1);				$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($metpeserta['sum_expremi']),0,0,'R',1);

		$pdf->setY(74);	$pdf->setX(14);
		//$_metstotal = $metpeserta['tpremi'] - $_metdiskon;
		$pdf->cell(30,7,'Tgl Kontrak',0,0,'L',1);			$pdf->cell(80,7,': '._convertDate($metpolis['polis_start']).'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);
		$pdf->cell(32,7,'Diskon',0,0,'L',1);				$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($_metdiskon),0,0,'R',1);

		$pdf->SetFont('helvetica','',10);
		$pdf->setY(81);	$pdf->setX(14);
		$pdf->cell(30,7,'Tanggal DN',0,0,'L',1);			$pdf->cell(80,7,': '._convertDate($met['tgl_createdn']).'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(32,7,'Subtotal',0,0,'L',1);				$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($_subtotal),0,0,'R',1);

		$pdf->setY(88);	$pdf->setX(14);
		$pdf->SetFont('helvetica','B',10);
		$tanggalplus=date('Y-m-d',strtotime($met['tgl_createdn']."+ ".$metpolis['jtempo']." day"));
		$pdf->cell(30,7,'Tanggal WPC',0,0,'L',1);			$pdf->cell(80,7,': '._convertDate($tanggalplus).'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);		$pdf->SetFont('helvetica','',10);

		$pdf->cell(32,7,'Charge Min. Premi',0,0,'L',1);		$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($metpeserta['sum_cmp']),0,0,'R',1);

		$pdf->setY(95);	$pdf->setX(14);
		$pdf->cell(30,7,' ',0,0,'L',1);						$pdf->cell(80,7,'');	$pdf->cell(1,7,'',0,0,'L',1);		$pdf->SetFont('helvetica','',10);
		$pdf->cell(32,7,'Biaya Adm.',0,0,'L',1);			$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($metpolis['adminfee']),0,0,'R',1);

		$pdf->setY(102);	$pdf->setX(14);
		$pdf->cell(30,7,' ',0,0,'L',1);						$pdf->cell(80,7,'');	$pdf->cell(1,7,'',0,0,'L',1);		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(32,7,'Total',0,0,'L',1);					$pdf->cell(10,7,': Rp',0,0,'L',1);	$pdf->cell(25,7,duit($metpeserta['totalpremi']),0,0,'R',1);


		$pdf->SetFont('helvetica','',10);
		$pdf->setY(110);	$pdf->setX(14);
		if ($tanggalplus > $datelog) {
		$pdf->cell(30,7,'',0,0,'L',1);				$pdf->cell(80,7,'',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);	$pdf->cell(75,7,'Jakarta, '.$futgl.'',0,0,'L',1);
		}else{
		$pdf->cell(30,7,'Keterangan',0,0,'L',1);	$pdf->cell(80,7,': ',0,0,'L',1);	$pdf->cell(1,7,'',0,0,'L',1);	$pdf->cell(75,7,'Jakarta, '.$futgl.'',0,0,'L',1);
		}
		$pdf->SetFont('helvetica','B',10);
		$pdf->setY(115);	$pdf->setX(14);
if ($tanggalplus > $datelog OR $met['dn_status']=="paid") {
	$pdf->cell(111,7,'',0,0,'L',1);	$pdf->cell(75,7,'PT. ADONAI PIALANG ASURANSI',0,0,'L',1);
}else{
//	$pdf->cell(92,7,'BILA TERJADI KLAIM, DATA TIDAK BISA DIPROSES',1,0,'L',1);	$pdf->cell(19,7,'',0,0,'L',1);	$pdf->cell(75,7,'PT. ADONAI PIALANG ASURANSI',0,0,'L',1); tidak diaktifkan untuk sementara
}

		$pdf->setY(125);	$pdf->setX(14);
		$pdf->cell(110,7,'',0,0,'L',1);	$pdf->cell(76,7,'',0,0,'L',1);

		$pdf->SetFont('helvetica','',9);
		$pdf->Text(15, 130,"Pembayaran dapat dilakukan pada account berikut :");
		$pdf->Text(15, 134,"a/c");	$pdf->Text(35, 134,": ".$metpolis['rek_1']."");
		//$pdf->Text(15, 138,"a/n");	$pdf->Text(35, 138,": ADONAI PIALANG ASURANSI, PT");
		$pdf->Text(15, 138,"Bank");		$pdf->Text(35, 138,': '.$metpolis['bank_1']);
		//$metalamatnya = explode(",",$metcost['address']);
		$pdf->Text(15, 142,"Cabang");	$pdf->Text(35, 142,': '.$metpolis['cabang_1'].'');

		$pdf->setY(146);	$pdf->setX(14);
		$pdf->SetFont('helvetica','I',8);
		$pdf->Cell(110,7,'Kwitansi ini merupakan bukti pembayaran yang sah.'.$ls_verion,0,0,'L');

		//$pdf->Text(15, 146,"");			$pdf->Text(36, 150,''. $metalamatnya[1]);
		//$pdf->Text(15, 146,"");			$pdf->Text(36, 154,''. $metalamatnya[2]);
/*
   $pdf->SetFont('helvetica','',9);
   $pdf->setY(126);	$pdf->setX(14);
   $pdf->cell(110,7,'Pembayaran dapat dilakukan pada account berikut :',0,0,'L',1);	$pdf->cell(76,7,'',0,0,'L',1);

   $pdf->setY(132);	$pdf->setX(14);
   $pdf->cell(20,7,'A / C',0,0,'L',1);		$pdf->cell(166,7,': 1000660431',0,0,'L',1);

   $pdf->setY(138);	$pdf->setX(14);
   $pdf->cell(20,7,'A / N',0,0,'L',1);		$pdf->cell(166,7,': ADONAI PIALANG ASURANSI, PT',0,0,'L',1);

   $pdf->setY(144);	$pdf->setX(14);
   $pdf->cell(20,7,'Bank',0,0,'L',1);		$pdf->cell(166,7,': '. $metcost['name'] ,0,0,'L',1);

   $pdf->setY(150);	$pdf->setX(14);
   $pdf->cell(20,7,'Cabang',0,0,'L',1);		$pdf->cell(166,7,': '. htmlentities($metcost['address']) ,0,0,'L',1);
*/
		$pdf->Output("DN_".$met['dn_kode'].".pdf","I");
		//$pdf->Output();
		;
		break;

	case "_kwipeserta":
		$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['idn'].'"'));
		$met_asuransi = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_asuransi WHERE id="'.$met['id_as'].'"'));
		$met_ttd = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ttd WHERE id_cost="'.$met['id_cost'].'" AND id_as="'.$met['id_as'].'"'));
		$metcost = mysql_fetch_array(mysql_query('SELECT id, name, address, logobank FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
		$metpolis = mysql_fetch_array(mysql_query('SELECT id, id_cost, nopol, nmproduk, min_premium FROM fu_ajk_polis WHERE id="'.$met['id_nopol'].'" AND id_cost="'.$met['id_cost'].'"'));

		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();


		//$pdf->Image('ajk_file/_ttd/'.$metcost['logobank'].'',10,20);
if ($keyb['id_cost']=="") {
	if ($met_asuransi['printlogo']=="Y") {
	$pdf->Image('ajk_file/_ttd/'.$met_asuransi['logo_asuransi'].'',10,20);
	$pdf->Image('image/adonai_64.gif',250,20);
	}else{
	}
}else{
	$pdf->Image('ajk_file/_ttd/'.$met_asuransi['logo_asuransi'].'',10,20);
	$pdf->Image('image/adonai_64.gif',250,20);


}
		//$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		//$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
		$pdf->SetFont('helvetica','B',12);
		$pdf->Text(110, 30,'DAFTAR PESERTA ASURANSI KUMPULAN');


		$pdf->SetFont('helvetica','',12);
		//$pdf->Text(15, 35,'Nomor Polis');		$pdf->Text(15, 35,': '.$metdn['nopol']);

if ($metpolis['nmproduk']=="") {	$metproduknya = $metpolis['nopol'];	}else{	$metproduknya = $metpolis['nmproduk'];	}
		$pdf->SetFont('Arial','',8);
		$pdf->Text(10,45, 'Nama Produk');		$pdf->Text(50,45, ': '.$metproduknya);
		$pdf->Text(10,50, 'Nama Perusahaan');	$pdf->Text(50,50, ': '.$metcost['name']);
		$pdf->Text(10,55, 'Alamat');			$pdf->Text(50,55, ': '.$metcost['address']);
		$pdf->Text(10,60, 'Nomor Debit Note');	$pdf->Text(50,60, ': '.$met['dn_kode']);
		$pdf->Text(10,65, 'Cabang');			$pdf->Text(50,65, ': '.$met['id_cabang']);
		$pdf->SetFont('Arial','B',9);
		$pdf->Text(10,70, $met_asuransi['name']);
		$y_axis1 = 73;

		$pdf->setFillColor(233,233,233);
		$pdf->setY($y_axis1);
		$pdf->setX(10);

		$pdf->cell(8,6,'No',1,0,'C',1);
		$pdf->cell(20,6,'ID Peserta',1,0,'C',1);
		$pdf->cell(55,6,'Nama',1,0,'C',1);
		$pdf->cell(18,6,'Tgl Lahir',1,0,'C',1);
		$pdf->cell(8,6,'Usia',1,0,'C',1);
		$pdf->cell(20,6,'Plafond',1,0,'C',1);
		$pdf->cell(10,6,'JK.W',1,0,'C',1);
		$pdf->cell(18,6,'Tgl.Akad',1,0,'C',1);
		$pdf->cell(18,6,'Tgl.Akhir',1,0,'C',1);
		$pdf->cell(15,6,'Rate',1,0,'C',1);
		$pdf->cell(18,6,'Premi',1,0,'C',1);
		$pdf->cell(11,6,'EM(%)',1,0,'C',1);
		$pdf->cell(17,6,'Ext.Premi',1,0,'C',1);
//		$pdf->cell(15,6,'T.Rate',1,0,'C',1);		150601
		$pdf->cell(15,6,'C M P',1,0,'C',1);
		$pdf->cell(25,6,'T.Premi',1,0,'C',1);

$peserta = mysql_query('SELECT *, ROUND(premi, 2) AS premi,
								  ROUND(ext_premi, 2) as sum_expremi,
								  ROUND(cmp, 2) as sum_cmp,
								  ROUND(premi, 2) + ROUND(ext_premi, 2) + ROUND(cmp, 2) as totalpremi
						FROM fu_ajk_peserta WHERE id_dn = "'.$met['id'].'" AND id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'" AND (status_peserta IS NULL OR status_peserta !="Batal") AND del IS NULL');
while ($metpeserta = mysql_fetch_array($peserta))
{
	$metpesertaratenya = $metpeserta['kredit_tenor'];
	if ($metpeserta['type_data']=="SPK") {
	$mametrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metpeserta['id_cost'].'" AND id_polis="'.$metpeserta['id_polis'].'" AND tenor="'.$metpesertaratenya.'" AND usia="'.$metpeserta['usia'].'" AND status="baru"'));
	}else{
	$mametrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metpeserta['id_cost'].'" AND id_polis="'.$metpeserta['id_polis'].'" AND tenor="'.$metpeserta['kredit_tenor'].'" AND status="baru"'));
	}

	if ($metpeserta['type_data']!="SPK") {
		$dataemnya = duit($metpeserta['ext_premi'] / $metpeserta['premi'] * 100) ;
	}else{
		$met_spk = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$metpeserta['id_cost'].'" AND id_polis="'.$metpeserta['id_polis'].'" AND spak="'.$metpeserta['spaj'].'" AND (status="Aktif" OR status="Realisasi") AND del IS NULL'));
		$dataemnya = duit($met_spk['ext_premi']);
	}
//	$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
//	if (!$met_spk) {		$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);	}
//	else{		$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);	}

/*	26 Feb 2015
	$em = $metpeserta['premi'] * $met_spk['ext_premi'] / 100;
	$totalpreminya = $metpeserta['premi'] + $em;
*/
	//JML KARAKTER
	if (strlen($metpeserta['id_peserta'] <= 8)) { $idpesertanya = substr($metpeserta['id_peserta'],11);	}else{	$idpesertanya = $metpeserta['id_peserta'];}
	$cell[$i][0] = $idpesertanya;
	$cell[$i][1] = $metpeserta['nama'];
	$cell[$i][2] = _convertDate($metpeserta['tgl_lahir']);
	$cell[$i][3] = $metpeserta['usia'];
	$cell[$i][4] = duit($metpeserta['kredit_jumlah']);
	$cell[$i][5] = $metpeserta['kredit_tenor'];
	$cell[$i][6] = _convertDate($metpeserta['kredit_tgl']);
	$cell[$i][7] = _convertDate($metpeserta['kredit_akhir']);
	$cell[$i][8] = $mametrate['rate'];
	$cell[$i][9] = duit($metpeserta['premi']);
	$cell[$i][10] = $dataemnya;
	$cell[$i][11] = duit($metpeserta['sum_expremi']);
//	$cell[$i][12] = $metTrate;		150601
	$cell[$i][12] = duit($metpeserta['sum_cmp']);
	$cell[$i][13] = duit($metpeserta['totalpremi']);
	$i++;
/* 150306
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalPremi += $metpeserta['premi'];
	$totalexPremi += $metpeserta['ext_premi'];
	$totalTPremi += $metpeserta['totalpremi'];
*/
}$pdf->Ln();
		$li_initial = 9;
		for($j<1;$j<$i;$j++)
		{	$pdf->cell(8,6,$j+1,1,0,'C');
			$pdf->cell(20,6,$cell[$j][0],1,0,'C');
			$pdf->cell(55,6,$cell[$j][1],1,0,'L');
			$pdf->cell(18,6,$cell[$j][2],1,0,'L');
			$pdf->cell(8,6,$cell[$j][3],1,0,'C');
			$pdf->cell(20,6,$cell[$j][4],1,0,'R');
			$pdf->cell(10,6,$cell[$j][5],1,0,'C');
			$pdf->cell(18,6,$cell[$j][6],1,0,'C');
			$pdf->cell(18,6,$cell[$j][7],1,0,'C');
			$pdf->cell(15,6,$cell[$j][8],1,0,'C');
			$pdf->cell(18,6,$cell[$j][9],1,0,'R');
			$pdf->cell(11,6,$cell[$j][10],1,0,'C');
			$pdf->cell(17,6,$cell[$j][11],1,0,'R');
			$pdf->cell(15,6,$cell[$j][12],1,0,'C');
			$pdf->cell(25,6,$cell[$j][13],1,0,'R');
			$pdf->Ln();


			if($j==$li_initial){
				$pdf->AddPage();
				$pdf->cell(8,6,'No',1,0,'C',1);
				$pdf->cell(20,6,'ID Peserta',1,0,'C',1);
				$pdf->cell(55,6,'Nama',1,0,'C',1);
				$pdf->cell(18,6,'Tgl Lahir',1,0,'C',1);
				$pdf->cell(8,6,'Usia',1,0,'C',1);
				$pdf->cell(20,6,'Plafond',1,0,'C',1);
				$pdf->cell(10,6,'JK.W',1,0,'C',1);
				$pdf->cell(18,6,'Tgl.Akad',1,0,'C',1);
				$pdf->cell(18,6,'Tgl.Akhir',1,0,'C',1);
				$pdf->cell(15,6,'Rate',1,0,'C',1);
				$pdf->cell(18,6,'Premi',1,0,'C',1);
				$pdf->cell(11,6,'EM(%)',1,0,'C',1);
				$pdf->cell(17,6,'Ext.Premi',1,0,'C',1);
				$pdf->cell(15,6,'C M P',1,0,'C',1);
				$pdf->cell(25,6,'T.Premi',1,0,'C',1);
				$pdf->Ln();
				$li_initial = $li_initial + 15;
			}


		}
$pesertaSUM = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, id_dn, status_peserta, del,
							   ROUND(SUM(kredit_jumlah), 2) AS plafond,
							   ROUND(SUM(premi), 2) AS premi,
							   ROUND(SUM(ext_premi), 2) AS sum_expremi,
							   ROUND(SUM(cmp), 2) AS sum_cmp,
							   ROUND(SUM(premi), 2) + ROUND(SUM(ext_premi), 2) + ROUND(SUM(cmp), 2) as totalpremi
						FROM fu_ajk_peserta WHERE id_dn = "'.$met['id'].'" AND id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'"  AND (status_peserta IS NULL OR status_peserta !="Batal") AND del IS NULL
						GROUP BY id_dn'));
		$pdf->setFont('Arial','B',9);
		$pdf->cell(101,6,'Total',1,0,'C');
		$pdf->cell(28,6,duit($pesertaSUM['plafond']),1,0,'R');
		$pdf->cell(79,6,duit($pesertaSUM['premi']),1,0,'R');
		$pdf->cell(28,6,duit($pesertaSUM['sum_expremi']),1,0,'R');
		$pdf->cell(40,6,duit($pesertaSUM['totalpremi']),1,0,'R');
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setFont('Arial','',9);
		$pdf->MultiCell(200,6,'Catatan :',0,'L');//	$pdf->cell(75,6,'Jakarta, '.$futgl.'',0,0,'L');$pdf->Ln();
		$pdf->MultiCell(200,6,'Bukti Konfirmasi ini merupakan dokumen elektronik, sehingga cukup menggunakan cap dan tanda tangan elektronik.',0,'L');//	$pdf->cell(75,6,$met_asuransi['name'],0,0,'L');		$pdf->Ln();
		//$pdf->Image('ajk_file/_ttd/'.$met_ttd['img_ttd'].'',200,40);	$pdf->Ln();
		//$pdf->cell(190,6,'',1,0,'L');	$pdf->cell(50,6,$met_ttd['nama'],1,0,'C');	$pdf->Ln();
		$pdf->cell(200,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,'Jakarta, '.date("d F Y",strtotime($futgl)).'', 0, 0, 'L');
		$pdf->Ln();
		$pdf->cell(200,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,$met_asuransi['name'], 0, 0, 'L');
		$pdf->Ln();
		$pdf->Image('ajk_file/_ttd/'.$met_ttd['img_ttd'].'',210);
		//	$pdf->Ln();
		$pdf->cell(205,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,$met_ttd['nama'], 0, 0, 'C');
		$pdf->Output("PESERTA_".$met['dn_kode'].".pdf","I");
		//$pdf->Output();
		;
		break;

	case "_erKlaim":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');

$met_klaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idC'].'"'));
$met_klaim_ = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_cn="'.$met_klaim['id_cn'].'" AND id_peserta="'.$met_klaim['id_peserta'].'"'));
$met_klaim_polis = mysql_fetch_array(mysql_query('SELECT id, id_cost, nopol, nmproduk, bank_2, cabang_2, rek_2 FROM fu_ajk_polis WHERE id="'.$met_klaim['id_nopol'].'" AND id_cost="'.$met_klaim['id_cost'].'"'));
$met_klaim_peserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_polis="'.$met_klaim['id_nopol'].'" AND id_peserta="'.$met_klaim['id_peserta'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_klaim="'.$met_klaim['id'].'" AND status_peserta="Death"'));
$met_klaim_dn = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_nopol="'.$met_klaim['id_nopol'].'" AND id="'.$met_klaim['id_dn'].'"'));
if ($met_klaim['confirm_claim']!="Approve(paid)") {	$headtagihan = $pdf->Text(88, 30,'KWITANSI KLAIM');	}
else{	$headtagihan = $pdf->Text(83, 30,'PEMBAYARAN KLAIM');	}
//$mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim_['tgl_klaim']);
$mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
$usiapolis = explode(",", $mets);
		$pdf->SetFont('helvetica','B',12);
		$headtagihan;
		$pdf->Code39(87, 31, $met_klaim['id_cn']);
		$pdf->SetFont('helvetica','B',10);
		$metcost = mysql_fetch_array(mysql_query('SELECT id, name, address FROM fu_ajk_costumer WHERE id="'.$met_klaim['id_cost'].'"'));
		$pdf->Text(10, 50,$metcost['name']);
		$pdf->SetFont('helvetica','',10);
		$pdf->Text(10, 55,'ID Peserta');		$pdf->Text(40, 55,': '.$met_klaim_peserta['id_peserta']);
		$pdf->Text(10, 60,'Nama');				$pdf->Text(40, 60,': '.$met_klaim_peserta['nama']);
		$pdf->Text(10, 65,'Tanggal Lahir');		$pdf->Text(40, 65,': '._convertDate($met_klaim_peserta['tgl_lahir']));
		$pdf->Text(10, 70,'Usia');				$pdf->Text(40, 70,': '.$met_klaim_peserta['usia'].' Tahun');
		$pdf->Text(10, 75,'Plafond');			$pdf->Text(40, 75,': Rp. '.duit($met_klaim_peserta['kredit_jumlah']));
		$pdf->Text(10, 80,'Tanggal Akad');		$pdf->Text(40, 80,': '._convertDate($met_klaim_peserta['kredit_tgl']));
		$pdf->Text(10, 85,'Tanggal Akhir');		$pdf->Text(40, 85,': '._convertDate($met_klaim_peserta['kredit_akhir']));
		$pdf->Text(10, 90,'Tenor');				$pdf->Text(40, 90,': '.$met_klaim_peserta['kredit_tenor'].' Bulan');
		$pdf->Text(10, 95,'Tempat Meninggal');	$pdf->Text(40, 95,': '.$met_klaim_['tempat_meninggal']);

		$pdf->Text(135, 55,'Nama Produk');		$pdf->Text(165, 55,': '.$met_klaim_polis['nmproduk']);
		$pdf->Text(135, 60,'Debit Note');		$pdf->Text(165, 60,': '.$met_klaim_dn['dn_kode']);
		$pdf->Text(135, 65,'Credit Note');		$pdf->Text(165, 65,': '.$met_klaim['id_cn']);
		$pdf->Text(135, 70,'Status Bayar');		$pdf->Text(165, 70,': '.strtoupper($met_klaim_dn['dn_status']));
		$pdf->Text(135, 75,'Tgl Pembayaran');	$pdf->Text(165, 75,': '._convertDate($met_klaim_dn['tgl_byr_dn']));
		$pdf->Text(135, 80,'Nilai Klaim');		$pdf->Text(165, 80,': Rp. '.duit($met_klaim['total_claim']));
		$pdf->Text(135, 85,'Tanggal Klaim');	$pdf->Text(165, 85,': '._convertDate($met_klaim['tgl_claim']));
		$pdf->Text(135, 90,'Usia Polis');		$pdf->Text(165, 90,': '.$usiapolis[0].' Tahun '.$usiapolis[1].' Bulan '.$usiapolis[2].' Hari');

		$y_axis1 = 98;
		$y_initial = 98;
		$pdf->setFont('Arial','',9);

		$pdf->setFillColor(233,233,233);
		$pdf->setY($y_axis1);
		$pdf->setX(10);

		$pdf->cell(8,6,'No',1,0,'C',1);
		$pdf->cell(165,6,'Nama Dokumen',1,0,'C',1);
		$pdf->cell(20,6,'Status',1,0,'C',1);
		$no = 0;
		$row = 6;
		$y = $y_initial + $row;
/*
$met_dok = mysql_query('SELECT
fu_ajk_dokumenklaim_bank.id,
fu_ajk_dokumenklaim_bank.id_bank,
fu_ajk_dokumenklaim_bank.id_dok,
fu_ajk_dokumenklaim.nama_dok
FROM
fu_ajk_dokumenklaim_bank
INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id WHERE id_bank="'.$met_klaim['id_cost'].'"');
while ($met_dok_ = mysql_fetch_array($met_dok)) {
	$met_dokumen = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_pes="'.$met_klaim['id_peserta'].'" AND dokumen="'.$met_dok_['id'].'"'));

if ($met_dokumen['dokumen']) {	$chekdokumen = 'Ada';	}else{$chekdokumen = 'Tidak Ada';	}
	$no++;
	$pdf->setY($y);
	$pdf->setX(10);
	$pdf->cell(8,6,$no,1,0,'C');
	$pdf->cell(165,6,$met_dok_['nama_dok'],1,0);
	$pdf->cell(20,6,$chekdokumen,1,0,'C');

	$y = $y + $row;
}
*/
$metDok = mysql_query('SELECT
fu_ajk_dokumenklaim_bank.id,
fu_ajk_dokumenklaim_bank.id_bank,
fu_ajk_dokumenklaim_bank.id_produk,
fu_ajk_dokumenklaim.nama_dok
FROM
fu_ajk_dokumenklaim_bank
LEFT JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
WHERE
fu_ajk_dokumenklaim_bank.id_bank = "'.$met_klaim['id_cost'].'" AND
fu_ajk_dokumenklaim_bank.id_produk = "'.$met_klaim['id_nopol'].'"');
while ($metDok_ = mysql_fetch_array($metDok)) {
	$namaDok = mysql_fetch_array(mysql_query('SELECT *, IF (nama_dokumen IS NULL, "Tidak ada", "Ada") AS dataKlaim FROM fu_ajk_klaim_doc WHERE id_cost="'.$metDok_['id_bank'].'" AND id_pes="'.$met_klaim['id_peserta'].'" AND dokumen="'.$metDok_['id'].'"'));
	$cell[$i][0] = $metDok_['nama_dok'];
	$cell[$i][1] = $namaDok['dataKlaim'];
	$i++;
}$pdf->Ln();
for($j<1;$j<$i;$j++)
{	$pdf->cell(8,6,$j+1,1,0,'C');
	$pdf->cell(165,6,$cell[$j][0],1,0);
	$pdf->cell(20,6,$cell[$j][1],1,0);
	$pdf->Ln();
}

	$pdf->SetFont('helvetica','',10);
	$pdf->cell(30,7,'Keterangan',0,0,'L',0);		$pdf->cell(80,7,': ',0,0,'L',0);	$pdf->cell(1,7,'',0,0,'L',0);	$pdf->cell(75,7,'Jakarta, '.$futgl.'',0,0,'L',0);
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',10);
	$pdf->cell(111,7,'',0,0,'L',0);	$pdf->cell(75,7,'PT. ADONAI PIALANG ASURANSI',0,0,'L',0);

	$pdf->Ln();
	$pdf->SetFont('helvetica','',9);
	$pdf->cell(40,5,"Pembayaran dapat dilakukan pada account berikut :");$pdf->Ln();
	$pdf->cell(45,4,"A / C");	$pdf->cell(40,4,': '.$met_klaim_polis['rek_2']);$pdf->Ln();
	$pdf->cell(45,4,"A / N");	$pdf->cell(45,4,': '.$metcost['name']);$pdf->Ln();
	$pdf->cell(45,4,"Bank");	$pdf->cell(45,4,': '. $met_klaim_polis['bank_2']);$pdf->Ln();
	$pdf->cell(45,4,"Cabang");	$pdf->cell(45,4,': '. $met_klaim_polis['cabang_2']);$pdf->Ln();

	$pdf->Output();
	;
	break;

	case "_eRefund":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');

$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idC'].'"'));
if ($met['confirm_claim']=="Approve(paid)") {	$headtagihan = $pdf->Text(80, 30,'PEMBAYARAN REFUND');	}
else{	$headtagihan = $pdf->Text(85, 30,'KWITANSI REFUND');	}

if ($met['tgl_byr_claim'] =="") {	$tglbayarCN = ' -';	}
else{	$tglbayarCN = _convertDate($met['tgl_byr_claim']);	}

		$pdf->SetFont('helvetica','B',12);
		$headtagihan;
		$pdf->Code39(87, 31, $met['id_cn']);
		//$pdf->Text(90, 40,$met['dn_kode']);


		$pdf->SetFont('helvetica','',10);

		$pdf->Text(15, 50,'Terima Dari');		$pdf->Text(50, 50,': PT. ADONAI PIALANG ASURANSI' );
		$pdf->Text(15, 55,'Uang Sejumlah');		$pdf->SetFont('helvetica','I',10);$pdf->Text(50, 55,':'.mametbilang($met['total_claim']).' rupiah', 0);

		$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
		$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_nopol'].'" AND id_cost="'.$met['id_cost'].'"'));
		$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'" AND id_klaim="'.$met['id'].'" AND id_dn="'.$met['id_dn'].'" AND id_peserta="'.$met['id_peserta'].'"'));

		//MA-J//
/*
$awal = explode ("-", $metpeserta['kredit_tgl']);		$hari = $awal[2];	$bulan = $awal[1];		$tahun = $awal[0];
		$akhir = explode ("-", $met['tgl_claim']);				$hari2 = $akhir[2];	$bulan2 = $akhir[1];	$tahun2 = $akhir[0];
		$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
		$sisahr=floor($jhari);
		$sisabulan =ceil($sisahr / 30.4375);
*/
		//MASA ASURANSI BERJALAN
		$met_Date = datediff($met['tgl_claim'], $metpeserta['kredit_tgl']);
		$met_Date_ = explode(",", $met_Date);
		if ($met_Date_[0] < 0) {	$thnbln = '';	}	else	{	$thnbln = $met_Date_[0] * 12;	}
		$maj_ = $met_Date_[1] + $thnbln;
		//MASA ASURANSI BERJALAN
		//MA-J//

		//MA-S//
		$masisa = $metpeserta['kredit_tenor'] - $maj_;
		//MA-S//

		$pdf->setFillColor(255,255,255);
		$pdf->setFont('helvetica','',10);

		$pdf->setY(60);		$pdf->setX(14);	$pdf->cell(35,6,'Nama Perusahaan',0,0,'L',1);		$pdf->cell(85,6,': '.$metcostumer['name'].'',0,0,'L',1);
		$pdf->setY(66);		$pdf->setX(14);	$pdf->cell(35,6,'Nama Produk',0,0,'L',1);			$pdf->cell(85,6,': '.$metpolis['nmproduk'].'',0,0,'L',1);
		$pdf->setY(72);		$pdf->setX(14);	$pdf->cell(35,6,'Cabang',0,0,'L',1);				$pdf->cell(85,6,': '.strtoupper($metpeserta['cabang']).'',0,0,'L',1);

		$pdf->setY(82);		$pdf->setX(14);	$pdf->cell(35,6,'ID Peserta',0,0,'L',1);			$pdf->cell(85,6,': '.$metpeserta['id_peserta'].'',0,0,'L',1);
		$pdf->setY(88);		$pdf->setX(14);	$pdf->cell(35,6,'Nama',0,0,'L',1);					$pdf->cell(85,6,': '.strtoupper($metpeserta['nama']).'',0,0,'L',1);
		$pdf->setY(94);		$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Akad',0,0,'L',1);			$pdf->cell(85,6,': '._convertDate($metpeserta['kredit_tgl']).'',0,0,'L',1);
		$pdf->setY(100);	$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Akhir',0,0,'L',1);			$pdf->cell(85,6,': '._convertDate($metpeserta['kredit_akhir']).'',0,0,'L',1);
		$pdf->setY(106);	$pdf->setX(14);	$pdf->cell(35,6,'Jangka Waktu',0,0,'L',1);			$pdf->cell(80,6,': '.$metpeserta['kredit_tenor'].' Bulan',0,0,'L',1);			$pdf->cell(30,7,'Tanggal Bayar CN',0,0,'L',1);		$pdf->cell(30,7,':' .$tglbayarCN,0,0,'L',1);
		$pdf->setY(112);	$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Refund',0,0,'L',1);		$pdf->cell(80,6,': '._convertDate($met['tgl_claim']).'',0,0,'L',1);				$pdf->cell(30,7,'Plafond',0,0,'L',1);				$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(20,7,duit($metpeserta['kredit_jumlah']),0,0,'R',1);
		$pdf->setY(118);	$pdf->setX(14);	$pdf->cell(35,6,'Masa Asuransi',0,0,'L',1);			$pdf->cell(80,6,': '.$maj_.' Bulan',0,0,'L',1);									$pdf->cell(30,7,'Total Premi',0,0,'L',1);			$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(20,7,duit($metpeserta['totalpremi']),0,0,'R',1);
		$pdf->setY(124);	$pdf->setX(14);	$pdf->cell(35,6,'Sisa Masa Asuransi',0,0,'L',1);	$pdf->cell(80,6,': '.$masisa.' Bulan',0,0,'L',1);								$pdf->SetFont('helvetica','B',10);$pdf->cell(30,7,'Jumlah Refund',0,0,'L',1);		$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(20,7,duit($met['total_claim']),0,0,'R',1);	$pdf->setFont('helvetica','',10);

		$pdf->setY(150);	$pdf->setX(14);

		$pdf->SetFont('helvetica','',9);
		$pdf->Text(15, 140,"Pembayaran dapat dilakukan pada account berikut :");				$pdf->Text(135, 140,'Jakarta, '.$futgl);
		$pdf->Text(15, 145,"A / C");	$pdf->Text(35, 145,': '.$metpolis['rek_2']);			$pdf->SetFont('helvetica','B',10);		$pdf->Text(135, 145,$metcostumer['name']);	$pdf->SetFont('helvetica','',10);
		$pdf->Text(15, 150,"A / N");	$pdf->Text(35, 150,': '.$metcostumer['name']);
		$pdf->Text(15, 155,"Bank");		$pdf->Text(35, 155,': '. $metpolis['bank_2']);
		$metalamatnya = explode(",",$metcostumer['address']);
		$pdf->Text(15, 160,"Cabang");	$pdf->Text(35, 160,': '.$metpolis['cabang_2']);
		$pdf->Text(15, 165,"");			$pdf->Text(36, 165,''. $metalamatnya[1]);
		$pdf->Text(15, 170,"");			$pdf->Text(36, 170,''. $metalamatnya[2]);

		$pdf->Output();
		;
		break;

	case "_eBatal":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');

		$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idC'].'"'));
if ($met['confirm_claim']=="Approve(paid)") {	$headtagihan = $pdf->Text(80, 30,'PEMBAYARAN BATAL');	}
else{	$headtagihan = $pdf->Text(88, 30,'KWITANSI BATAL');	}

if ($met['tgl_byr_claim'] =="") {	$tglbayarCN = ' -';	}
else{	$tglbayarCN = _convertDate($met['tgl_byr_claim']);	}

		$pdf->SetFont('helvetica','B',12);
		$headtagihan;
		$pdf->Code39(87, 31, $met['id_cn']);
		//$pdf->Text(90, 40,$met['dn_kode']);


		$pdf->SetFont('helvetica','',10);

		$pdf->Text(15, 50,'Terima Dari');		$pdf->Text(50, 50,': PT. ADONAI PIALANG ASURANSI' );
		$pdf->Text(15, 55,'Uang Sejumlah');		$pdf->SetFont('helvetica','I',10);$pdf->Text(50, 55,':'.mametbilang($met['total_claim']).' rupiah', 0);

		$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
		$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_nopol'].'" AND id_cost="'.$met['id_cost'].'"'));
		$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_nopol'].'" AND id_klaim="'.$met['id'].'" AND id_dn="'.$met['id_dn'].'" AND id_peserta="'.$met['id_peserta'].'"'));

		//MA-J//
/*
   $awal = explode ("-", $metpeserta['kredit_tgl']);		$hari = $awal[2];	$bulan = $awal[1];		$tahun = $awal[0];
   $akhir = explode ("-", $met['tgl_claim']);				$hari2 = $akhir[2];	$bulan2 = $akhir[1];	$tahun2 = $akhir[0];
   $jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
   $sisahr=floor($jhari);
   $sisabulan =ceil($sisahr / 30.4375);
*/
		//MASA ASURANSI BERJALAN
		$met_Date = datediff($met['tgl_claim'], $metpeserta['kredit_tgl']);
		$met_Date_ = explode(",", $met_Date);
		if ($met_Date_[0] < 0) {	$thnbln = '';	}	else	{	$thnbln = $met_Date_[0] * 12;	}
		$maj_ = $met_Date_[1] + $thnbln;
		//MASA ASURANSI BERJALAN
		//MA-J//

		//MA-S//
		$masisa = $metpeserta['kredit_tenor'] - $maj_;
		//MA-S//

		$pdf->setFillColor(255,255,255);
		$pdf->setFont('helvetica','',10);

		$pdf->setY(60);		$pdf->setX(14);	$pdf->cell(35,6,'Nama Perusahaan',0,0,'L',1);		$pdf->cell(85,6,': '.$metcostumer['name'].'',0,0,'L',1);
		$pdf->setY(66);		$pdf->setX(14);	$pdf->cell(35,6,'Nama Produk',0,0,'L',1);			$pdf->cell(85,6,': '.$metpolis['nmproduk'].'',0,0,'L',1);
		$pdf->setY(72);		$pdf->setX(14);	$pdf->cell(35,6,'Cabang',0,0,'L',1);				$pdf->cell(85,6,': '.strtoupper($metpeserta['cabang']).'',0,0,'L',1);

		$pdf->setY(82);		$pdf->setX(14);	$pdf->cell(35,6,'ID Peserta',0,0,'L',1);			$pdf->cell(85,6,': '.$metpeserta['id_peserta'].'',0,0,'L',1);
		$pdf->setY(88);		$pdf->setX(14);	$pdf->cell(35,6,'Nama',0,0,'L',1);					$pdf->cell(85,6,': '.strtoupper($metpeserta['nama']).'',0,0,'L',1);
		$pdf->setY(94);		$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Akad',0,0,'L',1);			$pdf->cell(85,6,': '._convertDate($metpeserta['kredit_tgl']).'',0,0,'L',1);
		$pdf->setY(100);	$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Akhir',0,0,'L',1);			$pdf->cell(85,6,': '._convertDate($metpeserta['kredit_akhir']).'',0,0,'L',1);
		$pdf->setY(106);	$pdf->setX(14);	$pdf->cell(35,6,'Jangka Waktu',0,0,'L',1);			$pdf->cell(85,6,': '.$metpeserta['kredit_tenor'].' Bulan',0,0,'L',1);			$pdf->cell(27,7,'Tanggal Bayar',0,0,'L',1);		$pdf->cell(30,7,':' .$tglbayarCN,0,0,'L',1);
		$pdf->setY(112);	$pdf->setX(14);	$pdf->cell(35,6,'Tanggal Refund',0,0,'L',1);		$pdf->cell(85,6,': '._convertDate($met['tgl_claim']).'',0,0,'L',1);				$pdf->cell(27,7,'Plafond',0,0,'L',1);			$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(30,7,duit($metpeserta['kredit_jumlah']),0,0,'R',1);
		$pdf->setY(118);	$pdf->setX(14);	$pdf->cell(35,6,'Masa Asuransi',0,0,'L',1);			$pdf->cell(85,6,': '.$maj_.' Bulan',0,0,'L',1);									$pdf->cell(27,7,'Total Premi',0,0,'L',1);		$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(30,7,duit($metpeserta['totalpremi']),0,0,'R',1);
		$pdf->setY(124);	$pdf->setX(14);	$pdf->cell(35,6,'Sisa Masa Asuransi',0,0,'L',1);	$pdf->cell(85,6,': '.$masisa.' Bulan',0,0,'L',1);								$pdf->SetFont('helvetica','B',10);$pdf->cell(27,7,'Jumlah Refund',0,0,'L',1);		$pdf->cell(10,7,': Rp',0,0,'L',0);	$pdf->cell(30,7,duit($met['total_claim']),0,0,'R',1);	$pdf->setFont('helvetica','',10);

		$pdf->setY(150);	$pdf->setX(14);

		$pdf->SetFont('helvetica','',9);
		$pdf->Text(15, 140,"Pembayaran dapat dilakukan pada account berikut :");				$pdf->Text(135, 140,'Jakarta, '.$futgl);
		$pdf->Text(15, 145,"A / C");	$pdf->Text(35, 145,': '.$metpolis['rek_2']);			$pdf->SetFont('helvetica','B',10);		$pdf->Text(135, 145,'PT. ADONAI PIALANG ASURANSI');	$pdf->SetFont('helvetica','',10);
		$pdf->Text(15, 150,"A / N");	$pdf->Text(35, 150,': '.$metcostumer['name']);
		$pdf->Text(15, 155,"Bank");		$pdf->Text(35, 155,': '. $metpolis['bank_2']);
		$metalamatnya = explode(",",$metcostumer['address']);
		$pdf->Text(15, 160,"Cabang");	$pdf->Text(35, 160,': '.$metpolis['cabang_2']);
		$pdf->Text(15, 165,"");			$pdf->Text(36, 165,''. $metalamatnya[1]);
		$pdf->Text(15, 170,"");			$pdf->Text(36, 170,''. $metalamatnya[2]);

		$pdf->Output();
		;
		break;

	case "_spk":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
		$pdf->SetFont('helvetica','B',12);	$pdf->Text(60, 30,'SURAT PEMERIKSAAN KESEHATAN "SPK"');
		$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE id="'.$_REQUEST['ids'].'"'));
		$metFormSPK = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$met['id'].'" AND del IS NULL'));
		if ($metFormSPK['jns_kelamin']=="M") {	$gender = "Laki-Laki";	}else{	$gender = "Perempuan";	}
		$met_costumer = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
		$met_polis = mysql_fetch_array(mysql_query('SELECT id, nmproduk, min_premium FROM fu_ajk_polis WHERE id="'.$met['id_polis'].'"'));

		$metdata_form = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$met['id'].'" AND del IS NULL'));
		//$mettotalpremibayangan = $metdata_form['x_premi'] + ($metdata_form['x_premi'] * $met['ext_premi'] / 100);

		//start 150908 modify by satrya
		session_register('usernama');
		$qruser = mysql_fetch_array(mysql_query('SELECT id_cost, status FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
		$statususer = $qruser['status'];
		//end

if (is_numeric($metdata_form['input_by'])) {
	$met_User = mysql_fetch_array(mysql_query('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$metdata_form['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $metFormSPK['dokter'];
}

if (is_numeric($metdata_form['cabang'])) {
	$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$metdata_form['cabang'].'"'));
	$inputcabang = $met_Cabang['name'];
}else{
	$inputcabang = $metFormSPK['cabang'];
}

		$pdf->SetFont('helvetica','B',12);	$pdf->Text(95, 35,'SPK. '.$met['spak']);
		$pdf->SetFont('helvetica','B',9);
		$pdf->Text(10, 45,'Nama Perusahaan');	$pdf->Text(52, 45,': '.$met_costumer['name']);
		$pdf->Text(10, 50,'Nama Produk');		$pdf->Text(52, 50,': '.$met_polis['nmproduk']);
		$pdf->Text(10, 55,'Upload User');		$pdf->Text(52, 55,': '.strtoupper($inputby_met));


		$pdf->SetFont('helvetica','U',9);
		$pdf->Text(10, 65,'DATA NASABAH');

		$pdf->SetFont('helvetica','',9);

if ($metdata_form['filefotodebitursatu']!="") {
			$mamet_photonya = '../ajkmobilescript/'.$metdata_form['filefotodebitursatu'];

			//$mamet_ttddebitur = '../ajkmobilescript/'.$metdata_form['filettddebitur'];		$pdf->Image($mamet_ttddebitur,15,250,40,35);
			if ($metdata_form['filettddebitur']!="") {
			$mamet_ttddebitur = '../ajkmobilescript/'.$metdata_form['filettddebitur'];		$pdf->Image($mamet_ttddebitur,15,250,40,35);
			}else{
			$pdf->Text(15, 270,'Tidak Ada TTD Debitur');
			}


			//$mamet_ttdmarketing = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];	$pdf->Image($mamet_ttdmarketing,65,250,40,35);
			if ($metdata_form['filettdmarketing']!="") {
			$mamet_ttdmarketing = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];		$pdf->Image($mamet_ttdmarketing,65,250,40,35);
			}else{
				$pdf->Text(60, 270,'Tidak Ada TTD Marketing');
			}



			if ($metdata_form['filettddokter']!="") {
			$mamet_ttddokter = '../ajkmobilescript/'.$metdata_form['filettddokter'];		$pdf->Image($mamet_ttddokter,115,250,40,35);
			}else{
			$pdf->Text(115, 250,'Tidak Ada TTD Dokter');
			}


		}else{
			$mamet_photonya = 'image/non-user.png';
		}


		$pdf->Text(10, 70,'Nama Nasabah');		$pdf->Text(52, 70,': '.strtoupper($metFormSPK['nama']));		$pdf->Image($mamet_photonya,150,40,40,40);
		$pdf->Text(10, 75,'Jenis Kelamin');		$pdf->Text(52, 75,': '.$gender);
		$pdf->Text(10, 80,'Tanggal Lahir');		$pdf->Text(52, 80,': '._convertDate($metFormSPK['dob']));
		$pdf->Text(10, 85,'Usia');				$pdf->Text(52, 85,': '.duit($metFormSPK['x_usia']).' Tahun');
		$pdf->Text(10, 90,'Alamat');			$pdf->Text(52, 90,': '.nl2br($metFormSPK['alamat']));
		$pdf->Text(10, 95,'Pekerjaan');			$pdf->Text(52, 95,': '.$metFormSPK['pekerjaan']);

		//start 150908 modify by satrya
		if ($qruser['id_cost']=="") {
			$pdf->SetFont('helvetica','U',9);
			$pdf->Text(10, 105,'DATA PERNYATAAN');
			$pdf->SetFont('helvetica','',9);

			$pdf->SetY(107);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan.' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket1']!="") { $keterangan_1 = ', '.$metFormSPK['ket1'];	}else{	$keterangan_1 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan1'].''.$keterangan_1.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan.' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket2']!="") { $keterangan_2 = ', '.$metFormSPK['ket2'];	}else{	$keterangan_2 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan2'].''.$keterangan_2.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda menderita HIV/AIDS?' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket3']!="") { $keterangan_3 = ', '.$metFormSPK['ket3'];	}else{	$keterangan_3 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan3'].''.$keterangan_3.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya?' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket4']!="") { $keterangan_4 = ', '.$metFormSPK['ket4'];	}else{	$keterangan_4 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan4'].''.$keterangan_4.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Khusus untuk Wanita, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan?' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket5']!="") { $keterangan_5 = ', '.$metFormSPK['ket5'];	}else{	$keterangan_5 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan5'].''.$keterangan_5.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda seorang perokok? Jika "Ya" berapa batang perhari?' ,0,'L');
			$pdf->SetX(9);
			if ($metFormSPK['ket6']!="") { $keterangan_6 = ', '.$metFormSPK['ket6'];	}else{	$keterangan_6 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan6'].''.$keterangan_6.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','U',9);
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA CEK MEDICAL' ,0,'L');

			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);	$pdf->Cell(36,4,'Tanggal Periksa' ,0,'L');			$pdf->Cell(36,4,': ' ._convertDate($metFormSPK['tgl_periksa']) ,0,'l');
			$pdf->Ln();

			$pdf->SetX(10);	$pdf->Cell(37,5, 'Tinggi dan Berat Badan' ,1,0,'C');
			$pdf->Cell(37,5,'Tekanan Darah' ,1,0,'C');
			$pdf->Cell(37,5,'Nadi' ,1,0,'C');
			$pdf->Cell(38,5,'Pernafasan' ,1,0,'C');
			$pdf->Cell(38,5,'Gula Darah' ,1,0,'C');
			$pdf->Ln();
			$pdf->SetX(10);	$pdf->Cell(37,5,$metFormSPK['tinggibadan'].'/'.$metFormSPK['beratbadan'].'' ,1,0,'C');
			$pdf->Cell(37,5,$metFormSPK['tekanandarah'] ,1,0,'C');
			$pdf->Cell(37,5,$metFormSPK['nadi'] ,1,0,'C');
			$pdf->Cell(38,5,$metFormSPK['pernafasan'] ,1,0,'C');
			$pdf->Cell(38,5,$metFormSPK['guladarah'] ,1,0,'C');
			$pdf->Ln();
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'Kesimpulan : '.$metFormSPK['kesimpulan'].'' ,0,'L');
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'Catatan : '.$metFormSPK['catatan'].'' ,0,'L');


			$pdf->Ln();
			$pdf->SetFont('helvetica','U',9);
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA PEMINJAMAN ASURANSI' ,0,'L');
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(10);
			$pdf->Cell(35,5,'Plafond' ,1,0,'C');
			$pdf->Cell(25,5,'Tanggal Akad' ,1,0,'C');
			$pdf->Cell(20,5,'Tenor' ,1,0,'C');
			$pdf->Cell(25,5,'Tanggal Akhir ' ,1,0,'C');
			$pdf->Cell(30,5,'Premi' ,1,0,'C');
			$pdf->Cell(22,5,'Ext. Premi (%)' ,1,0,'C');
			$pdf->Cell(30,5,'Total Premi' ,1,0,'C');

			$pdf->Ln();
			if ($met['status']=="Aktif" OR $met['status']=="Realisasi") {
				if ($met['ext_premi']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM '.$met['ext_premi'].'%  ('.$met['ket_ext'].')';	}


				//if ($metFormSPK['tenor'] > 12) {	$tenorSPK_ = $metFormSPK['tenor'] / 12;	}	else{	$tenorSPK_ = $metFormSPK['tenor'];	}	REVISI 061015

				//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $tenorSPK_ . '"')); // RATE PREMI	REVISI 061015
				$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '"')); // RATE PREMI
				$premi = ROUND($metFormSPK['plafond'] * $cekrate['rate'] / 1000);

				$metStatusPremi = $premi;
				$metExtPremi = ROUND($premi * $met['ext_premi'] / 100);	//HITUNG EXTRA PREMI
				$mettotalpremibayangan = $premi + $metExtPremi;
				if ($mettotalpremibayangan < $met_polis['min_premium']) {
					$mettotalpremibayangan_ = $met_polis['min_premium'];
				}else{
					$mettotalpremibayangan_ = $mettotalpremibayangan;
				}
				$pdf->Cell(35,5, duit($metFormSPK['plafond']) ,1,0,'C');
				$pdf->Cell(25,5,_convertDate($metFormSPK['tgl_asuransi']) ,1,0,'C');
				$pdf->Cell(20,5,$metFormSPK['tenor'] ,1,0,'C');
				$pdf->Cell(25,5,_convertDate($metFormSPK['tgl_akhir_asuransi']) ,1,0,'C');
				$pdf->Cell(30,5,duit($metStatusPremi) ,1,0,'C');
				$pdf->Cell(22,5,duit($met['ext_premi']).'%' ,1,0,'C');
				$pdf->Cell(30,5,duit($mettotalpremibayangan_) ,1,0,'C');
			}else{
				$met_ket_EM = $met['keterangan'];
				$pdf->Cell(35,5,'',1,0,'C');
				$pdf->Cell(25,5,'',1,0,'C');
				$pdf->Cell(20,5,'',1,0,'C');
				$pdf->Cell(25,5,'',1,0,'C');
				$pdf->Cell(30,5,'',1,0,'C');
				$pdf->Cell(22,5,'',1,0,'C');
				$pdf->Cell(30,5,'',1,0,'C');

			}

			$pdf->Ln();
			$pdf->Ln();
			//if ($met['ket_ext']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM'. $met['ket_ext'];	}
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Keterangan' ,0,'L');			$pdf->Cell(36,4,': ' .$met_ket_EM ,0,'1');
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Cabang' ,0,'L');				$pdf->Cell(36,4,': ' .$inputcabang ,0,'1');
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Input User' ,0,'L');			$pdf->Cell(36,4,': ' .strtoupper($inputby_met) ,0,'1');
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Tanggal Input' ,0,'L');		$pdf->Cell(36,4,': ' .$metFormSPK['input_date'] ,0,'1');

		//end 150908 modify by satrya
		//$met_tglinputnya = explode(" ",$metFormSPK['input_date']);

		//KONDISI MENAMPILKAN CATATAN USIA APABILA AKAN NAIK USIA SISA 1 BULAN LAGI
		//if ($met_tglinputnya[0] <= $datelog) {
		//}else{
		$pdf->Ln();
		$mets = datediff($metFormSPK['tgl_asuransi'], $metFormSPK['dob']);
		$cekbulan = explode(",", $mets);
		if ($cekbulan[1] >= 6 ) {	$umur = $cekbulan[0] + 1;	}else{	$umur = $cekbulan[0];	}
		if ($cekbulan[1] == 5) {
			$sisahari = 30 - $cekbulan[2];
			$sisathn = $cekbulan[0] + 1;
			$blnnnya ='Mohon mengajukan Deklarasi kurang dari '.$sisahari.' hari, sebelum usia akan bertambah menjadi '.$sisathn.' tahun';
			$pdf->SetX(9);	$pdf->Cell(50,4, 'Catatan : ' ,0,'L');	$pdf->Cell(36,4,'' ,0,'1');
			$pdf->Cell(36,4,$blnnnya ,0,'1');
		}else{
			$blnnnya ='';
		}

		//echo $mets.'<br />';
		}else{

			$pdf->SetY(107);
			$pdf->SetX(9);
			if ($statususer=="" OR $statususer=="STAFF") {
				$pdf->Ln();
				$pdf->SetFont('helvetica','U',9);
				$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA PEMINJAMAN ASURANSI',0,'L');
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(10);
				$pdf->Cell(35,5,'Plafond' ,1,0,'C');
				$pdf->Cell(25,5,'Tanggal Akad' ,1,0,'C');
				$pdf->Cell(20,5,'Tenor' ,1,0,'C');
				$pdf->Cell(25,5,'Tanggal Akhir ' ,1,0,'C');
				$pdf->Cell(30,5,'Premi' ,1,0,'C');
				$pdf->Cell(22,5,'Ext. Premi (%)' ,1,0,'C');
				$pdf->Cell(30,5,'Total Premi' ,1,0,'C');

				$pdf->Ln();
				if ($met['status']=="Aktif" OR $met['status']=="Realisasi") {
					if ($met['ext_premi']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM '.$met['ext_premi'].'%  ('.$met['ket_ext'].')';	}

					$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '"')); // RATE PREMI
					$premi = ROUND($metFormSPK['plafond'] * $cekrate['rate'] / 1000);

					$metStatusPremi = $premi;
					$metExtPremi = ROUND($premi * $met['ext_premi'] / 100);	//HITUNG EXTRA PREMI
					$mettotalpremibayangan = $premi + $metExtPremi;
					if ($mettotalpremibayangan < $met_polis['min_premium']) {
						$mettotalpremibayangan_ = $met_polis['min_premium'];
					}else{
						$mettotalpremibayangan_ = $mettotalpremibayangan;
					}
					$pdf->Cell(35,5, duit($metFormSPK['plafond']) ,1,0,'C');
					$pdf->Cell(25,5,_convertDate($metFormSPK['tgl_asuransi']) ,1,0,'C');
					$pdf->Cell(20,5,$metFormSPK['tenor'] ,1,0,'C');
					$pdf->Cell(25,5,_convertDate($metFormSPK['tgl_akhir_asuransi']) ,1,0,'C');
					$pdf->Cell(30,5,duit($metStatusPremi) ,1,0,'C');
					$pdf->Cell(22,5,duit($met['ext_premi']).'%' ,1,0,'C');
					$pdf->Cell(30,5,duit($mettotalpremibayangan_) ,1,0,'C');
				}else{
					$met_ket_EM = $met['keterangan'];
					$pdf->Cell(35,5,'',1,0,'C');
					$pdf->Cell(25,5,'',1,0,'C');
					$pdf->Cell(20,5,'',1,0,'C');
					$pdf->Cell(25,5,'',1,0,'C');
					$pdf->Cell(30,5,'',1,0,'C');
					$pdf->Cell(22,5,'',1,0,'C');
					$pdf->Cell(30,5,'',1,0,'C');
				}

				$pdf->Ln();
				$pdf->Ln();
				//if ($met['ket_ext']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM'. $met['ket_ext'];	}
				$pdf->SetX(9);	$pdf->Cell(37,4, 'Keterangan' ,0,'L');			$pdf->Cell(36,4,': ' .$met_ket_EM ,0,'1');
				$pdf->SetX(9);	$pdf->Cell(37,4, 'Cabang' ,0,'L');				$pdf->Cell(36,4,': ' .$inputcabang ,0,'1');
				$pdf->SetX(9);	$pdf->Cell(37,4, 'Input User' ,0,'L');			$pdf->Cell(36,4,': ' .strtoupper($inputby_met) ,0,'1');
				$pdf->SetX(9);	$pdf->Cell(37,4, 'Tanggal Input' ,0,'L');		$pdf->Cell(36,4,': ' .$metFormSPK['input_date'] ,0,'1');

				//end 150908 modify by satrya
				//$met_tglinputnya = explode(" ",$metFormSPK['input_date']);

				//KONDISI MENAMPILKAN CATATAN USIA APABILA AKAN NAIK USIA SISA 1 BULAN LAGI
				//if ($met_tglinputnya[0] <= $datelog) {
				//}else{
				$pdf->Ln();
				$mets = datediff($metFormSPK['tgl_asuransi'], $metFormSPK['dob']);
				$cekbulan = explode(",", $mets);
				if ($cekbulan[1] >= 6 ) {	$umur = $cekbulan[0] + 1;	}else{	$umur = $cekbulan[0];	}
				if ($cekbulan[1] == 5) {
					$sisahari = 30 - $cekbulan[2];
					$sisathn = $cekbulan[0] + 1;
					$blnnnya ='Mohon mengajukan Deklarasi kurang dari '.$sisahari.' hari, sebelum usia akan bertambah menjadi '.$sisathn.' tahun';
					$pdf->SetX(9);	$pdf->Cell(50,4, 'Catatan : ' ,0,'L');	$pdf->Cell(36,4,'' ,0,'1');
					$pdf->Cell(36,4,$blnnnya ,0,'1');
				}else{
					$blnnnya ='';
				}
			}elseif ($statususer=="ASURANSI") {
				$pdf->SetFont('helvetica','U',9);
				$pdf->Text(10, 105,'DATA PERNYATAAN');
				$pdf->SetFont('helvetica','',9);

				$pdf->SetY(107);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan.' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket1']!="") { $keterangan_1 = ', '.$metFormSPK['ket1'];	}else{	$keterangan_1 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan1'].''.$keterangan_1.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan.' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket2']!="") { $keterangan_2 = ', '.$metFormSPK['ket2'];	}else{	$keterangan_2 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan2'].''.$keterangan_2.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Apakah anda menderita HIV/AIDS?' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket3']!="") { $keterangan_3 = ', '.$metFormSPK['ket3'];	}else{	$keterangan_3 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan3'].''.$keterangan_3.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya?' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket4']!="") { $keterangan_4 = ', '.$metFormSPK['ket4'];	}else{	$keterangan_4 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan4'].''.$keterangan_4.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Khusus untuk Wanita, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan?' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket5']!="") { $keterangan_5 = ', '.$metFormSPK['ket5'];	}else{	$keterangan_5 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan5'].''.$keterangan_5.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Apakah anda seorang perokok? Jika "Ya" berapa batang perhari?' ,0,'L');
				$pdf->SetX(9);
				if ($metFormSPK['ket6']!="") { $keterangan_6 = ', '.$metFormSPK['ket6'];	}else{	$keterangan_6 = '';	}
				$pdf->SetFont('helvetica','B',8);
				$pdf->MultiCell(195,4,'Jawaban : '.$metFormSPK['pertanyaan6'].''.$keterangan_6.'' ,0,'L');

				$pdf->Ln();
				$pdf->SetFont('helvetica','U',9);
				$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA CEK MEDICAL' ,0,'L');

				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);	$pdf->Cell(36,4,'Tanggal Periksa' ,0,'L');			$pdf->Cell(36,4,': ' ._convertDate($metFormSPK['tgl_periksa']) ,0,'l');
				$pdf->Ln();

				$pdf->SetX(10);	$pdf->Cell(37,5, 'Tinggi dan Berat Badan' ,1,0,'C');
				$pdf->Cell(37,5,'Tekanan Darah' ,1,0,'C');
				$pdf->Cell(37,5,'Nadi' ,1,0,'C');
				$pdf->Cell(38,5,'Pernafasan' ,1,0,'C');
				$pdf->Cell(38,5,'Gula Darah' ,1,0,'C');
				$pdf->Ln();
				$pdf->SetX(10);	$pdf->Cell(37,5,$metFormSPK['tinggibadan'].'/'.$metFormSPK['beratbadan'].'' ,1,0,'C');
				$pdf->Cell(37,5,$metFormSPK['tekanandarah'] ,1,0,'C');
				$pdf->Cell(37,5,$metFormSPK['nadi'] ,1,0,'C');
				$pdf->Cell(38,5,$metFormSPK['pernafasan'] ,1,0,'C');
				$pdf->Cell(38,5,$metFormSPK['guladarah'] ,1,0,'C');
				$pdf->Ln();
				$pdf->SetX(9);	$pdf->MultiCell(195,4,'Kesimpulan : '.$metFormSPK['kesimpulan'].'' ,0,'L');
				$pdf->SetX(9);	$pdf->MultiCell(195,4,'Catatan : '.$metFormSPK['catatan'].'' ,0,'L');
			}else{
				echo '<script language="Javascript">window.location="login.php?op=logout"</script>';
			}
		}

		$met_namafilenya = str_replace(" ","_" , $metFormSPK['nama']);
		$pdf->Output("SPK_".$met['spak']."_".$met_namafilenya.".pdf","I");
		;
		break;

	case "cekbilang":
	function HeaderingExcel($filename) {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$filename" );
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
	}
	HeaderingExcel('Cek_Data_Terbilang.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Cek Data Terbilang');

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
	$worksheet1->set_column(0, 1, 12);	$worksheet1->write_string(0, 1, "Debitnote", $format);
	$worksheet1->set_column(0, 2, 12);	$worksheet1->write_string(0, 2, "Premi", $format);
	$worksheet1->set_column(0, 3, 12);	$worksheet1->write_string(0, 3, "Terbilang", $format);

	// membuat header file excel dan nama filenya
	$metcekterbilang = mysql_query('SELECT id, dn_kode, ROUND(totalpremi) AS tpremi FROM fu_ajk_dn WHERE del IS NULL ORDER BY id DESC');
	$baris = 1;
while ($metcekterbilang_ = mysql_fetch_array($metcekterbilang)) {
	$worksheet1->write_number($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $metcekterbilang_['dn_kode']);
	$worksheet1->write_number($baris, 2, $metcekterbilang_['tpremi']);
	$worksheet1->write_string($baris, 3, mametbilang(duitterbilang($metcekterbilang_['tpremi'])));
	$baris++;
}
	$workbook->close();
	;
	break;

case "eL_ListSPK":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idB'].'"'));
	HeaderingExcel('Laporan_SPK.xls');
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Laporan SPK');

	$worksheet1->merge_cells(0, 0, 0, 21);	$worksheet1->write_string(0, 0, "LAPORAN DATA SPK", $fjudul, 1);
	$worksheet1->merge_cells(1, 0, 1, 21);	$worksheet1->write_string(1, 0, strtoupper($met_c['name']), $fjudul);

	$format =& $workbook->add_format();
	$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');
	$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();

	$worksheet1->set_row(2, 15);
	$worksheet1->set_column(2, 0, 5);	$worksheet1->write_string(2, 0, "NO", $format);
	$worksheet1->set_column(2, 1, 15);	$worksheet1->write_string(2, 1, "NAMA DEBITUR", $format);
	$worksheet1->set_column(2, 2, 15);	$worksheet1->write_string(2, 2, "CABANG", $format);
	$worksheet1->set_column(2, 3, 15);	$worksheet1->write_string(2, 3, "NO. SPK", $format);
	$worksheet1->set_column(2, 4, 15);	$worksheet1->write_string(2, 4, "TGL INPUT SPK", $format);
	$worksheet1->set_column(2, 5, 15);	$worksheet1->write_string(2, 5, "TGL APPROVE SPK", $format);
	$worksheet1->set_column(2, 6, 15);	$worksheet1->write_string(2, 6, "TGL LAHIR", $format);
	$worksheet1->set_column(2, 7, 10);	$worksheet1->write_string(2, 7, "USIA ", $format);
	$worksheet1->set_column(2, 8, 10);	$worksheet1->write_string(2, 8, "TGL AKHIR", $format);
	$worksheet1->set_column(2, 9, 15);	$worksheet1->write_string(2, 9, "PLAFOND", $format);
	$worksheet1->set_column(2, 10, 15);	$worksheet1->write_string(2, 10, "EM", $format);
	$worksheet1->set_column(2, 11, 5);	$worksheet1->write_string(2, 11, "TENOR", $format);
	$worksheet1->set_column(2, 12, 5);	$worksheet1->write_string(2, 12, "TB", $format);
	$worksheet1->set_column(2, 13, 5);	$worksheet1->write_string(2, 13, "BB", $format);
	$worksheet1->set_column(2, 14, 15);	$worksheet1->write_string(2, 14, "SISTOLIK", $format);
	$worksheet1->set_column(2, 15, 15);	$worksheet1->write_string(2, 15, "DIASTOLIK", $format);
	$worksheet1->set_column(2, 16, 15);	$worksheet1->write_string(2, 16, "NADI", $format);
	$worksheet1->set_column(2, 17, 15);	$worksheet1->write_string(2, 17, "PERNAFASAN", $format);
	$worksheet1->set_column(2, 18, 15);	$worksheet1->write_string(2, 18, "GULA DARAH", $format);
	$worksheet1->set_column(2, 19, 10);	$worksheet1->write_string(2, 19, "MEROKOK", $format);
	$worksheet1->set_column(2, 20, 15);	$worksheet1->write_string(2, 20, "JML ROKOK", $format);
	$worksheet1->set_column(2, 21, 15);	$worksheet1->write_string(2, 21, "CATATAN SKS", $format);
	$worksheet1->set_column(2, 22, 15);	$worksheet1->write_string(2, 22, "STATUS", $format);

//if ($_REQUEST['tgl1'])		{	$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['tgl1'])		{
	if ($_REQUEST['tgl1'] == $_REQUEST['tgl2']) {
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tgl2'] ) ) ;;
		$newdate = date ( 'Y-m-d' , $PenambahanTgl );
		$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate.'" ';
	}else{
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tgl2'] ) ) ;;
		$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
		$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate2.'" ';

	}
}

if ($_REQUEST['st'])		{	$dua = 'AND fu_ajk_spak.status = "'.$_REQUEST['st'].'"';	}

	$baris = 3;
$er_data = mysql_query('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_spak.keterangan,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.id_cost = '.$_REQUEST['idB'].' AND fu_ajk_spak.update_by = "'.$_REQUEST['ispv'].'" '.$satu.' '.$dua.'
ORDER BY fu_ajk_spak.input_date DESC');
while ($mamet = mysql_fetch_array($er_data))
{

if ($mamet['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
	$tolik = explode("/", $mamet['tekanandarah']);

if (is_numeric($mamet['cabang'])) {
	$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'"'));
	$inputcabang = $met_Cabang['name'];
}else{
	$inputcabang = $mamet['cabang'];
}

	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['nama']);
	$worksheet1->write_string($baris, 2, $inputcabang);
	$worksheet1->write_string($baris, 3, $mamet['spak']);
	$worksheet1->write_string($baris, 4, $mamet['tglInput']);
	$worksheet1->write_string($baris, 5, $mamet['tglApproveSPV']);
	$worksheet1->write_string($baris, 6, $mamet['dob']);
	$worksheet1->write_number($baris, 7, $mamet['x_usia']);
	$worksheet1->write_string($baris, 8, $mamet['tgl_akhir_asuransi']);
	$worksheet1->write_number($baris, 9, $mamet['plafond']);
	$worksheet1->write_number($baris, 10, $mamet['ext_premi']);
	$worksheet1->write_number($baris, 11, $mamet['tenor']);
	$worksheet1->write_number($baris, 12, $mamet['tinggibadan']);
	$worksheet1->write_number($baris, 13, $mamet['beratbadan']);
	$worksheet1->write_number($baris, 14, $tolik[0]);
	$worksheet1->write_number($baris, 15, $tolik[1]);
	$worksheet1->write_number($baris, 16, $mamet['nadi']);
	$worksheet1->write_number($baris, 17, $mamet['pernafasan']);
	$worksheet1->write_number($baris, 18, $mamet['guladarah']);
	$worksheet1->write_string($baris, 19, $pertanyaan6);
	$worksheet1->write_string($baris, 20, $mamet['ket6']);
	$worksheet1->write_string($baris, 21, $mamet['catatan']);
	$worksheet1->write_string($baris, 22, $mamet['status']);
	$baris++;
}
	$workbook->close();
	;
	break;

	default:
	;
} // switch

?>