<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
include_once "../includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');

include "../includes/fu6106.php";
$futgl = date("d M Y");
$futglojk = date("d-m-Y");
$futglreas = date("ymd");
switch ($_REQUEST['fu']) {
case "ajkpdfinvdn":
		$pdf=new FPDF('P','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$pdf->Image('../image/logo_adonai.gif',10,5);
		$pdf->SetFont('Arial','B',14);
		$pdf->Text(90, 30,'NOTA DEBET');

if ($_REQUEST['invmove']=="movemant") {
	$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metpeserta['id_polis'].'"'));
	$metdnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$metpeserta['id_dn'].'" AND del IS NULL'));
	$mettgldn = explode(" ", $metdnnya['input_time']);
	$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metdnnya['id_cost'].'"'));
	$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metpolis['benefit_type'].'"'));
	$peserta = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$metdnnya['dn_kode'].'" AND del IS NULL');
}else{
	$metdnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'" AND del IS NULL'));
	$mettgldn = explode(" ", $metdnnya['input_time']);
	$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metdnnya['id_cost'].'"'));
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metdnnya['id_nopol'].'"'));
	$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metpolis['benefit_type'].'"'));
	$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metdnnya['dn_kode'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND del IS NULL'));
	$peserta = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$metdnnya['dn_kode'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND del IS NULL');
	$Jumpeserta = mysql_num_rows($peserta);

}
/*
$pesertanew = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metdnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$pesertanew['id_dn'].'"'));
$mettgldn = explode(" ", $metdnnya['input_time']);

$pesertanewcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$pesertanew['id_cost'].'"'));
$pesertanewpol = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$pesertanew['id_polis'].'"'));
$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$pesertanewpol['benefit_type'].'"'));
*/
		$pdf->SetFont('Arial','B',10);	$pdf->Text(10,45, $metcost['name']);
		$pdf->SetFont('Arial','B',10);	$pdf->Text(10,50, 'Cabang ' .$metpeserta['cabang']);
		$pdf->SetFont('Arial','',10);	$pdf->Text(10,55, 'Nomor Polis');	$pdf->Text(40,55, ': '.$metpolis['nopol']);
		$pdf->SetFont('Arial','',10);	$pdf->Text(10,60, 'Nomor DN');	$pdf->Text(40,60, ': '.$metdnnya['dn_kode']);
		//$pdf->SetFont('Arial','',10);	$pdf->Text(10,65, 'Jenis Asuransi');	$pdf->Text(40,65, ': Asuransi Jiwa Kredit-'.$metmaster['msdesc']);
		$pdf->SetFont('Arial','',10);	$pdf->Text(10,65, 'Jenis Asuransi');	$pdf->Text(40,65, ': Asuransi Jiwa Kredit');
		$pdf->SetFont('Arial','',10);	$pdf->Text(135,55, 'Tanggal Efektif Polis');	$pdf->Text(177,55, ': '._convertDate($metpolis['polis_start']));
		$pdf->SetFont('Arial','',10);	$pdf->Text(135,60, 'Tanggal Nota DN');			$pdf->Text(177,60, ': '._convertDate($mettgldn[0]));

		$tanggalawal=$mettgldn[0];
		$tanggalplus=date('m-Y-d',strtotime($tanggalawal."+14 day"));
		$tanggalexp = explode("-", $tanggalplus);
		$tanggaljt = $tanggalexp[2].'-'.$tanggalexp[0].'-'.$tanggalexp[1];
		$pdf->SetFont('Arial','',10);	$pdf->Text(135,65, 'Tanggal Jatuh Tempo');		$pdf->Text(177,65, ': '.$tanggaljt);


		$pdf->SetFont('Arial','B',9);	$pdf->Text(10,75, 'DATA PESERTA ASURANSI JIWA KUMPULAN');
		$y_initial = 84;
		$y_axis1 = 78;

		$pdf->setFont('Arial','',7);

		$pdf->setFillColor(233,233,233);
		$pdf->setY($y_axis1);
		$pdf->setX(10);

		$pdf->cell(5,6,'No',1,0,'C',1);
		$pdf->cell(14,6,'ID Baru',1,0,'C',1);
		$pdf->cell(30,6,'Nama',1,0,'C',1);
		$pdf->cell(14,6,'DOB',1,0,'C',1);
		$pdf->cell(6,6,'Usia',1,0,'C',1);
		$pdf->cell(17,6,'Awal Asuransi',1,0,'C',1);
		$pdf->cell(7,6,'Tenor',1,0,'C',1);
		$pdf->cell(17,6,'Akhir Asuransi',1,0,'C',1);
		$pdf->cell(20,6,'UP',1,0,'C',1);
		$pdf->cell(16,6,'Premi',1,0,'C',1);
		$pdf->cell(14,6,'Disc',1,0,'C',1);
		$pdf->cell(12,6,'Ex Premi',1,0,'C',1);
		$pdf->cell(10,6,'Adm',1,0,'C',1);
		$pdf->cell(16,6,'Nilai DN',1,0,'C',1);


while ($metpeserta = mysql_fetch_array($peserta))
{
	//AKHIR ASURANSI
	$findmet="/";
	$fpos = stripos($metpeserta['kredit_tgl'], $findmet);
if ($fpos === false) {	$cektglnya = $metpeserta['kredit_tgl'];	}
else	{	$riweuh = explode("/", $metpeserta['kredit_tgl']);
			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
}
	$tanggalawal=$cektglnya;
	$tanggalplus=date('d-m-Y',strtotime($tanggalawal."+".$metpeserta['kredit_tenor']." Month")-1);
	//AKHIR ASURANSI

	$findmetnama="/";
	$fposnama = stripos($metpeserta['nama'], $findmetnama);
	if ($fposnama === false) {
		$ceknamanya = $metpeserta['nama'];
		if (strlen($metpeserta['nama'])>19) {
			$elmet = explode(" ", $metpeserta['nama']);
			$namaakhir = substr($elmet[2],0,1);
			$ceknamanya = $elmet[0].' '. $elmet[1].' '. $namaakhir;
		} else { $ceknamanya = $metpeserta['nama'];	}
	}
	else	{
		$riweuhnama = explode("/", $metpeserta['nama']);
		$ceknamanya = $riweuhnama[0];
	}

	//CEK FORMAT UMUR
	$findmet="/";
	$fpos = stripos($metpeserta['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $metpeserta['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$metpeserta['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $metpeserta['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("-", $metpeserta['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $metpeserta['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fu['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $metpeserta['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("/", $metpeserta['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}
	//CEK FORMAT UMUR

/*
$metdisc = $metpeserta['disc_premi'];			//DISC DARI POLIS
	$no++;
	$pdf->setY($y);
	$pdf->setX(10);
	$pdf->cell(5,6,$no,1,0,'C');
	$pdf->cell(14,6,$metpeserta['id_peserta'],1,0,'C');
	$pdf->cell(30,6,$ceknamanya,1,0,'L');
	$pdf->cell(14,6,$metpeserta['tgl_lahir'],1,0,'C');
	$pdf->cell(6,6,$umur,1,0,'C');
	$pdf->cell(17,6,$metpeserta['kredit_tgl'],1,0,'C');
	$pdf->cell(7,6,$metpeserta['kredit_tenor'],1,0,'C');
	$pdf->cell(17,6,$tanggalplus,1,0,'C');
	$pdf->cell(20,6,duit($metpeserta['kredit_jumlah']),1,0,'R');
	$pdf->cell(15,6,duit($metpeserta['premi']),1,0,'R');
	$pdf->cell(10,6,duit($metdisc),1,0,'R');
	$pdf->cell(14,6,duit($metpeserta['ext_premi']),1,0,'R');
	$pdf->cell(15,6,duit($metpeserta['biaya_adm']),1,0,'R');
	$pdf->cell(15,6,duit($metpeserta['totalpremi']),1,0,'R');
	$y = $y + $row;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalDisc += $metdisc;
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpeserta['biaya_adm'];
	$totalTPremi += $metpeserta['totalpremi'];
*/
	$cell[$i][0] = $metpeserta['id_peserta'];
	$cell[$i][1] = $ceknamanya;
	$cell[$i][2] = $metpeserta['tgl_lahir'];
	$cell[$i][3] = $metpeserta['usia'];
	$cell[$i][4] = $metpeserta['kredit_tgl'];
	$cell[$i][5] = $metpeserta['kredit_tenor'];
	$cell[$i][6] = $tanggalplus;
	$cell[$i][7] = duit($metpeserta['kredit_jumlah']);
	$cell[$i][8] = duit($metpeserta['premi']);
	$cell[$i][9] = duit($metpeserta['disc_premi']);
	$cell[$i][10] = duit($metpeserta['ext_premi']);
	$cell[$i][11] = duit($metpolis['adm_fee']);
	$cell[$i][12] = duit($metpeserta['totalpremi']);
	$i++;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalDisc += $metpeserta['disc_premi'];
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpolis['adm_fee'];
	$totalTPremi += $metpeserta['totalpremi'];

}	$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{
		//menampilkan data dari hasil query database
		$pdf->cell(5,6,$j+1,1,0,'C');
		$pdf->cell(14,6,$cell[$j][0],1,0,'C');
		$pdf->cell(30,6,$cell[$j][1],1,0,'L');
		$pdf->cell(14,6,$cell[$j][2],1,0,'C');
		$pdf->cell(6,6,$cell[$j][3],1,0,'C');
		$pdf->cell(17,6,$cell[$j][4],1,0,'C');
		$pdf->cell(7,6,$cell[$j][5],1,0,'C');
		$pdf->cell(17,6,$cell[$j][6],1,0,'C');
		$pdf->cell(20,6,$cell[$j][7],1,0,'R');
		$pdf->cell(16,6,$cell[$j][8],1,0,'R');
		$pdf->cell(14,6,$cell[$j][9],1,0,'R');
		$pdf->cell(12,6,$cell[$j][10],1,0,'R');
		$pdf->cell(10,6,$cell[$j][11],1,0,'R');
		$pdf->cell(16,6,$cell[$j][12],1,0,'R');
		$pdf->Ln();
	}
	//$pdf->setXY(10,$y + $i);
	$pdf->setFont('Arial','B',8);
	$pdf->cell(110,6,'Total',1,0,'C');
	$pdf->cell(20,6,duit($totalUp),1,0,'R');
	$pdf->cell(16,6,duit($totalStd),1,0,'R');
	$pdf->cell(14,6,duit($totalDisc),1,0,'R');
	$pdf->cell(12,6,duit($totalExt),1,0,'R');
	$pdf->cell(10,6,duit($totalAdm),1,0,'R');
	$pdf->cell(16,6,duit($totalTPremi),1,0,'R');

//	$metdnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'" AND del IS NULL'));
	$dncnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdnnya['dn_kode'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND del IS NULL'));
	if ($dncnnya['id_dn'] == $metdnnya['dn_kode'])
	{
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',7);
		$pdf->cell(5,6, 'No.', 1, 0, 'C');
		$pdf->cell(15,6, 'Type', 1, 0, 'C');
		$pdf->cell(30,6, 'DN Lama', 1, 0, 'C');
		$pdf->cell(14,6, 'ID Lama', 1, 0, 'C');
		$pdf->cell(50,6, 'Nama', 1, 0, 'C');
		$pdf->cell(20,6, 'UP Lama', 1, 0, 'C');
		$pdf->cell(30,6, 'Credit Note', 1, 0, 'C');
		$pdf->cell(15,6, 'Tgl CN', 1, 0, 'C');
		$pdf->cell(20,6, 'Nilai CN', 1, 0, 'C');
		$pdf->Ln();

	$cekdncn = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdnnya['dn_kode'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND type_claim !="Refund" AND del IS NULL');

	while ($metdncnnya = mysql_fetch_array($cekdncn)) {
	if ($metdncnnya['type_claim']=="Batal") {
	$namapesertacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metdncnnya['id_cn'].'" AND id_peserta="'.$metdncnnya['id_peserta'].'"'));
	}elseif ($metdncnnya['type_claim']=="Refund"){
	$namapesertacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metdncnnya['id_cn'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND id_peserta="'.$metdncnnya['id_peserta'].'"'));
	}else{
	$namapesertacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metdncnnya['id_cn'].'" AND id_cost="'.$metdncnnya['id_cost'].'"'));
	}


	$findmetnama="/";
	$fposnama = stripos($namapesertacn['nama'], $findmetnama);
	if ($fposnama === false) {
		$ceknamanya = $namapesertacn['nama'];
		if (strlen($namapesertacn['nama'])>19) {
			$elmet = explode(" ", $namapesertacn['nama']);
			$namaakhir = substr($elmet[2],0,1);
			$ceknamanya = $elmet[0].' '. $elmet[1].' '. $namaakhir;
		} else { $ceknamanya = $namapesertacn['nama'];	}
	}
	else	{
		$riweuhnama = explode("/", $namapesertacn['nama']);
		$ceknamanya = $riweuhnama[0];
	}

		if ($metdncnnya['total_claim'] < 0) {	$totalcn = 0;	}
		else{	$totalcn = $metdncnnya['total_claim']; }

		$pdf->cell(5,6,++$no,1,0,'C');
		$pdf->cell(15,6,$metdncnnya['type_claim'],1,0,'C');
		$pdf->cell(30,6,$namapesertacn['id_dn'],1,0,'C');
		$pdf->cell(14,6,$namapesertacn['id_peserta'],1,0,'C');
		$pdf->cell(50,6,$ceknamanya,1,0,'L');
		$pdf->cell(20,6,duit($namapesertacn['kredit_jumlah']),1,0,'R');
		$pdf->cell(30,6,$metdncnnya['id_cn'],1,0,'C');
		$pdf->cell(15,6,_convertDate($metdncnnya['tgl_claim']),1,0,'C');
		$pdf->cell(20,6,duit($totalcn),1,0,'R');
		$pdf->Ln();
		$totalpremiupcn += $namapesertacn['kredit_jumlah'];
		$totalpremiclaimcn += $totalcn;
		}
		$pdf->setFont('Arial','B',8);
		$pdf->cell(114,6,'Total',1,0,'C');
		$pdf->cell(20,6,duit($totalpremiupcn),1,0,'R');
		$pdf->cell(45,6,"",1,0,'R');
		$pdf->cell(20,6,duit($totalpremiclaimcn),1,0,'R');

		$pdf->Ln();
		$netpremi = $totalTPremi - $totalpremiclaimcn;

		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->cell(30,6, 'Total di bayar :', 1, 0, 'L', 1);
		$pdf->cell(65,6, duit($totalTPremi). ' - ' .duit($totalpremiclaimcn). ' = '.duit($netpremi), 1, 1, 'R', 1);
	}else{
		$pdf->Ln();
	}

	$pdf->MultiCell(90, 5, '', 0, 'R');
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(170, 5, 'Keterangan :'. $metdnnya['ket'], 0);
	$pdf->MultiCell(180, 5, '1. Transfer melalui Bank ke rekening :
		    PT. Adonai Pialang Asuransi
		    '.$metpolis['bank_name'].' - '.$metpolis['bank_branch'].' - No. Rek. '.$metpolis['bank_accNo'].'', 0);
	$pdf->MultiCell(180, 5, '2. Mohon tidak melakukan pembayaran secara tunai', 0);
	$pdf->MultiCell(180, 5, '3. Biaya yang timbul dari proses transfer yang dilakukan harus ditanggung oleh Pemegang Polis', 0);
	$pdf->MultiCell(180, 5, '4. Mohon mencantumkan keterangan Pembayaran Nota Debet No '.$metdnnya['dn_kode'].' pada slip pembayaran pada saat melakukan transfer.', 0);
	$pdf->MultiCell(180, 5, '5. Apabila ada pertanyaan lebih lanjut, mohon untuk dapat menghubungi kami di No. Telepon : 021-......, No. Fax : 021-......', 0);
		//PARAF
	$pdf->setFont('Arial','B',10);
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'Jakarta, '.date("d F Y",strtotime($metdnnya['tgl_createdn'])).'', 0, 0, 'L');
	$pdf->Ln();
	$pdf->Image('../image/ttd_andress.jpg',155);
	//	$pdf->Ln();
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'{ nama }', 0, 0, 'L');
	$pdf->Ln();
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'KADIV TEKNIK', 0, 0, 'L');
	$pdf->Output();
	;
		break;

case "ajkpdfm":
$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();

$pdf->Image('../image/logo_recapitalife.png',10,10);
$dnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$polisnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id = "'.$dnnya['id_nopol'].'"'));

$pdf->setFont('Arial','B',11);
$pdf->setXY(110,20); $pdf->cell(30,6,'DATA PESERTA ASURANSI JIWA KUMPULAN');
$pdf->setXY(100,25); $pdf->cell(30,6,'PT. Bank Pundi Indonesia, Tbk. Kantor Cabang '.$dnnya['id_cabang'].'');
$pdf->setXY(125,30); $pdf->cell(30,6,'Nomor Polis '.$polisnya['nopol'].'');
$pdf->setFont('Arial','B',14);
$pdf->setXY(215,25); $pdf->cell(30,6,'No. DN : '.$dnnya['dn_kode'].'');


$y_initial = 46;
$y_axis1 = 40;

$pdf->setFont('Arial','',8);

$pdf->setFillColor(233,233,233);
$pdf->setY($y_axis1);
$pdf->setX(10);

$pdf->cell(8,6,'No',1,0,'C',1);
$pdf->cell(16,6,'No. Peserta',1,0,'C',1);
$pdf->cell(40,6,'Nama Peserta',1,0,'C',1);
$pdf->cell(16,6,'DOB',1,0,'C',1);
$pdf->cell(8,6,'Usia',1,0,'C',1);
$pdf->cell(20,6,'Awal Asuransi',1,0,'C',1);
$pdf->cell(20,6,'Akhir Asuransi',1,0,'C',1);
$pdf->cell(8,6,'Tenor',1,0,'C',1);
$pdf->cell(20,6,'UP. (Rp)',1,0,'C',1);
$pdf->cell(20,6,'Premi Std (Rp)',1,0,'C',1);
$pdf->cell(15,6,'Disc (Rp)',1,0,'C',1);
$pdf->cell(20,6,'Ext. Premi (Rp)',1,0,'C',1);
$pdf->cell(16,6,'Adm. (Rp)',1,0,'C',1);
$pdf->cell(22,6,'Total Premi (Rp)',1,0,'C',1);
$pdf->cell(25,6,'Cabang (Rp)',1,0,'C',1);

$y = $y_initial + $row;
$peserta = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$dnnya['dn_kode'].'" AND del IS NULL');
$no = 0;
$row = 6;
while ($metpeserta = mysql_fetch_array($peserta))
{
$idmet = 1000000000 + $metpeserta['id'];
$idmet2 = substr($idmet, 1);
	//AKHIR ASURANSI
	$tanggalawal=$metpeserta['kredit_tgl'];
	$tanggalplus=date('d-m-Y',strtotime($tanggalawal."+".$metpeserta['kredit_tenor']." Month"));
	//AKHIR ASURANSI

$metdisc = $metpeserta['premi'] * $polisnya['disc'] / 100;			//DISC DARI POLIS
	$no++;
	$pdf->setY($y);
	$pdf->setX(10);
	$pdf->cell(8,6,$no,1,0,'C');
	$pdf->cell(16,6,$idmet2,1,0,'C');
	$pdf->cell(40,6,$metpeserta['nama'],1,0,'L');
	$pdf->cell(16,6,_convertDate($metpeserta['tgl_lahir']),1,0,'C');
	$pdf->cell(8,6,$metpeserta['usia'],1,0,'C');
	$pdf->cell(20,6,_convertDate($metpeserta['kredit_tgl']),1,0,'C');
	$pdf->cell(20,6,$tanggalplus,1,0,'C');
	$pdf->cell(8,6,$metpeserta['kredit_tenor'],1,0,'C');
	$pdf->cell(20,6,duit($metpeserta['kredit_jumlah']),1,0,'R');
	$pdf->cell(20,6,duit($metpeserta['premi']),1,0,'R');
	$pdf->cell(15,6,duit($metdisc),1,0,'R');
	$pdf->cell(20,6,duit($metpesert['ext_premi']),1,0,'R');
	$pdf->cell(16,6,duit($polisnya['adminfee']),1,0,'R');
	$pdf->cell(22,6,duit($metpeserta['totalpremi']),1,0,'R');
	$pdf->cell(25,6,$metpeserta['cabang'],1,0,'L');
	$y = $y + $row;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalDisc += $metdisc;
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $polisnya['adminfee'];
	$totalTPremi += $metpeserta['totalpremi'];
}


$pdf->setXY(10,$y);
$pdf->setFont('Arial','B',8);
$pdf->cell(136,6,'Total',1,0,'C');
$pdf->cell(20,6,duit($totalUp),1,0,'R');
$pdf->cell(20,6,duit($totalStd),1,0,'R');
$pdf->cell(15,6,duit($totalDisc),1,0,'R');
$pdf->cell(20,6,duit($totalExt),1,0,'R');
$pdf->cell(16,6,duit($totalAdm),1,0,'R');
$pdf->cell(22,6,duit($totalTPremi),1,0,'R');
$pdf->cell(25,6,'',1,0,'R');

//PARAF
$metlokasi= $y + 10;
$metparaf= $metlokasi + 5;
$metnama= $metparaf + 15;
$metbag= $metnama + 5;
$pdf->setFont('Arial','B',10);
$pdf->setXY(220,$metlokasi); $pdf->cell(30,6,'Jakarta, '.$futgl.'');
$pdf->Image('../image/ttd_andress.jpg',225,$metparaf);
$pdf->setFont('Arial','U',10);
$pdf->setXY(220,$metnama); $pdf->cell(30,6,'Andress Manansal');
$pdf->setFont('Arial','',10);
$pdf->setXY(223,$metbag); $pdf->cell(30,6,'KADIV TEKNIK');

$pdf->Output();
	;
	break;

case "ajkpdfcn":
	$pdf=new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('../image/logo_adonai.gif',1,20);
	$pdf->SetFont('Arial','B',14);
	$pdf->Text(90, 30,'CREDIT NOTE');

	$metcnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$_REQUEST['id_cn'].'" AND id="'.$_REQUEST['id'].'"'));
	$mettgldn = explode(" ", $metcnnya['input_time']);
	$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metcnnya['id_cost'].'"'));
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metcnnya['id_nopol'].'"'));
	$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metpolis['benefit_type'].'"'));
	$emetpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metcnnya['id_cn'].'"'));	//PESERTA LAMA
	//$cekpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="" AND nama="'.$metpeserta['nama'].'" AND tgl_lahir="'.$metpeserta['tgl_lahir'].'" AND status_peserta!=""'));	//PESERTA BARU
	$cekpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_peserta="'.$metcnnya['id_peserta'].'"'));	//PESERTA BARU

if ($metcnnya['id_regional']=="") { $cregional = $metcnnya['id_regional_old'];	}	else	{	$cregional = $metcnnya['id_regional'];	}
if ($metcnnya['id_area']=="") { $carea = $metcnnya['id_area_old'];	}				else	{	$carea = $metcnnya['id_area'];	}

/*if ($metpeserta['cabang']=="") {	$ccabang = $metpeserta['cabang_lama'];	}	else	{	$ccabang = $metpeserta['cabang'];	}		// PENDING DATA CABANG 260413*/

	$pdf->SetFont('Arial','B',10);	$pdf->Text(1,45, $metcost['name']);
	$pdf->SetFont('Arial','B',10);	$pdf->Text(1,50, 'Cabang ' .$metcnnya['id_cabang']);
	$pdf->SetFont('Arial','B',10);	$pdf->Text(135,50, 'Type Movement');	$pdf->Text(165,50, ': '.$metcnnya['type_claim']).'';

	$pdf->SetFont('Arial','',10);	$pdf->Text(1,55, 'Nomor Polis');	$pdf->Text(40,55, ': '.$metpolis['nopol']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(1,60, 'Nomor CN');	$pdf->Text(40,60, ': '.$metcnnya['id_cn']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(1,65, 'Jenis Asuransi');	$pdf->Text(40,65, ': Asuransi Jiwa Kredit-'.$metmaster['msdesc']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,55, 'Tanggal Efektif Polis');	$pdf->Text(177,55, ': '._convertDate($metpolis['start_date']));
	//$pdf->SetFont('Arial','',10);	$pdf->Text(135,60, 'Tanggal Nota CN');			$pdf->Text(177,60, ': '._convertDate($metcnnya['tgl_createcn']));
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,60, 'Tanggal Nota CN');			$pdf->Text(177,60, ': '._convertDate($metcnnya['tgl_createcn']));

	$name = ''.$metcnnya['id_cn'].'.pdf';
	header('Content-Disposition: attachment;filename="' . $name . '"');

	$tanggalawal=$metcnnya['tgl_createcn'];
	$tanggalplus=date('m-Y-d',strtotime($tanggalawal."+14 day"));
	$tanggalexp = explode("-", $tanggalplus);
	$tanggaljt = $tanggalexp[2].'-'.$tanggalexp[0].'-'.$tanggalexp[1];
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,65, 'Tanggal Jatuh Tempo');		$pdf->Text(177,65, ': '.$tanggaljt);


	$pdf->SetFont('Arial','B',9);	$pdf->Text(1,75, 'DATA PESERTA ASURANSI JIWA KUMPULAN');
	$y_initial = 84;
	$y_axis1 = 78;
	$pdf->setFont('Arial','',7);
	$pdf->setFillColor(233,233,233);
	$pdf->setY($y_axis1);
	$pdf->setX(1);
	$pdf->cell(5,6,'No',1,0,'C',1);
	$pdf->cell(14,6,'ID Peserta',1,0,'C',1);
	$pdf->cell(30,6,'Nama',1,0,'C',1);
	$pdf->cell(14,6,'DOB',1,0,'C',1);
	$pdf->cell(6,6,'Usia',1,0,'C',1);
	$pdf->cell(17,6,'Awal Asuransi',1,0,'C',1);
	$pdf->cell(7,6,'Tenor',1,0,'C',1);
	$pdf->cell(17,6,'Akhir Asuransi',1,0,'C',1);
	$pdf->cell(15,6,'Tanggal CN',1,0,'C',1);
	$pdf->cell(7,6,'MA-j',1,0,'C',1);
	$pdf->cell(7,6,'MA-s',1,0,'C',1);
	$pdf->cell(20,6,'UP',1,0,'C',1);
	$pdf->cell(15,6,'Premi',1,0,'C',1);
	$pdf->cell(7,6,'Disc',1,0,'C',1);
	$pdf->cell(15,6,'Nilai CN',1,0,'C',1);
	$y = $y_initial + $row;
	$peserta = mysql_query('SELECT * FROM v_fu_ajk_peserta WHERE id_klaim = "'.$metcnnya['id_cn'].'"');

	$no = 0;
	$row = 6;
while ($metpeserta = mysql_fetch_array($peserta))
{
	//TANGGAL KLAIM ATAU TANGGAL MOVEMENT
	if ($metcnnya['type_claim']=="Refund" OR $metcnnya['type_claim']=="Death")
	{	$mamettglclaim = _convertDate($metcnnya['tgl_claim']);	}else{	$mamettglclaim = $cekpeserta['kredit_tgl'];
	}

	// MASA ASURANSI BERJALAN
	if ($metpesertarefund['type_claim']=="Death" OR $metpesertarefund['type_claim']=="Refund") {
		$kreditd = explode("/", $metpeserta['kredit_tgl']);		$nowkreditd = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];	$nowd = new T10DateCalc($nowkreditd);
		$periodbuland = $nowd->compareDate($metcnklaim['tgl_klaim']) / 30.4375 + 1;
		//	$periodbulan = $now->compareDate($metklaim['tgl_klaim']);
		$majalan = ceil($periodbuland);
		$dnclaim ='<a href="ajk_report_fu.php?fu=ajkpdfinvdn&invmove=movemant&id='.$peserta['id'].'" target="_blank">'.substr($peserta['id_dn'], 6).'</a>';
	}else{
		$kredit  = explode("/", $metpeserta['kredit_tgl']);
		if ($kredit[1] > 12) {	$nowkredit  = $kredit[2].'-'.$kredit[0].'-'.$kredit[1];	}	else	{	$nowkredit  = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];		}
		$now = new T10DateCalc($nowkredit);

		$kredit2 = explode("/", $cekpeserta['kredit_tgl']);		$nowkredit2 = $kredit2[2].'-'.$kredit2[1].'-'.$kredit2[0];
		$periodbulan = $now->compareDate($nowkredit2) / 30.4375;
		//	$periodbulan = $now->compareDate($metklaim['tgl_klaim']);
		$majalan = ceil($periodbulan);
		$dnclaim ='<a href="ajk_report_fu.php?fu=ajkpdfinvdn&invmove=movemant&id='.$cekpeserta['id'].'" target="_blank">'.substr($cekpeserta['id_dn'], 6).'</a>';
	}
	$masisa = $metpeserta['kredit_tenor'] - $majalan;

	//NILAI CN KLAIM ATAU MOVING
	if ($metcnnya['type_claim']=="Batal") {
	$metnilaicn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_peserta="'.$metpeserta['id_peserta'].'"'));
	}else{
	$metnilaicn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_peserta="'.$metcnnya['id_peserta'].'" AND id="'.$metcnnya['id'].'"'));
	}

	if ($metnilaicn['total_claim'] < 0) { $totalclaimnya = 0;	}else{ $totalclaimnya =	$metnilaicn['total_claim'];}

	$no++;
	$pdf->setY($y);
	$pdf->setX(1);
	$pdf->cell(5,6,$no,1,0,'C');
	$pdf->cell(14,6,$metpeserta['id_peserta'],1,0,'C');
	$pdf->cell(30,6,$metpeserta['nama'],1,0,'L');
	$pdf->cell(14,6,$metpeserta['tgl_lahir'],1,0,'C');
	$pdf->cell(6,6,$metpeserta['usia'],1,0,'C');
	$pdf->cell(17,6,$metpeserta['kredit_tgl'],1,0,'C');
	$pdf->cell(7,6,$metpeserta['kredit_tenor'],1,0,'C');
	$pdf->cell(17,6,$tanggalplus,1,0,'C');
	$pdf->cell(15,6, $mamettglclaim,1,0,'C');
	$pdf->cell(7,6,$majalan,1,0,'C');
	$pdf->cell(7,6,$masisa,1,0,'C');
	$pdf->cell(20,6,duit($metpeserta['kredit_jumlah']),1,0,'R');
	$pdf->cell(15,6,duit($metpeserta['premi']),1,0,'R');
	$pdf->cell(7,6,duit($metdisc),1,0,'R');
	$pdf->cell(15,6,duit($totalclaimnya),1,0,'R');

	$y = $y + $row;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalDisc += $metdisc;
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpeserta['biaya_adm'];
	$totalTPremi += $totalclaimnya;
}
	$pdf->Ln();
	for($j=1;$j<$i;$j++)
	{
		$pdf->setX(5);
		//menampilkan data dari hasil query database
		$pdf->cell(5,6,$j,1,0,'C');
		$pdf->cell(14,6,$cell[$j][0],1,0,'C');
		$pdf->cell(20,6,$cell[$j][1],1,0,'L');
		$pdf->cell(30,6,$cell[$j][2],1,0,'L');
		$pdf->cell(14,6,$cell[$j][3],1,0,'C');
		$pdf->cell(7,6,$cell[$j][4],1,0,'C');
		$pdf->cell(14,6,$cell[$j][5],1,0,'C');
		$pdf->cell(15,6,$cell[$j][6],1,0,'C');
		$pdf->cell(7,6,$cell[$j][7],1,0,'C');
		$pdf->cell(7,6,$cell[$j][8],1,0,'C');
		$pdf->cell(15,6,$cell[$j][9],1,0,'R');
		$pdf->cell(14,6,$cell[$j][10],1,0,'R');
		$pdf->cell(14,6,$cell[$j][11],1,0,'R');
		$pdf->cell(25,6,$cell[$j][12],1,0,'L');
		$pdf->cell(25,6,$cell[$j][13],1,0,'L');
		$pdf->Ln();
	}
	$pdf->setX(1);
	$pdf->setFont('Arial','B',7);
	$pdf->cell(159,6,'Total',1,0,'C');
	$pdf->cell(15,6,duit($totalStd),1,0,'R');
	$pdf->cell(7,6,"",1,0,'R');
	$pdf->cell(15,6,duit($totalTPremi),1,0,'R');
	//$pdf->cell(25,6,'',1,0,'R');

	$pdf->Ln();
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180, 5, '1. Transfer melalui Bank ke rekening :
		    PT. Asuransi Jiwa Recapital
		    '.$metpolis['bank_name'].' - '.$metpolis['bank_branch'].' - No. Rek. '.$metpolis['bank_accNo'].'', 0);
		$pdf->MultiCell(180, 5, '2. Mohon tidak melakukan pembayaran secara tunai', 0);
		$pdf->MultiCell(180, 5, '3. Biaya yang timbul dari proses transfer yang dilakukan harus ditanggung oleh Pemegang Polis', 0);
		$pdf->MultiCell(180, 5, '4. Mohon mencantumkan keterangan Pembayaran Nota Debet No 13.01.02414 pada slip pembayaran pada saat melakukan transfer.', 0);
		$pdf->MultiCell(180, 5, '5. Apabila ada pertanyaan lebih lanjut, mohon untuk dapat menghubungi kami di No. Telepon : 021-725 6272, No. Fax : 021-7253858', 0);

	$pdf->Ln();
	$pdf->setFont('Arial','',8);
	$pdf->cell(150,6,' ', 0, 0, 'R');
	$pdf->cell(40,6,'Jakarta, '.date("d F Y",strtotime($metcnnya['tgl_createcn'])).'', 0, 0, 'L');
	$pdf->Ln();
	$pdf->setFont('Arial','B',8);
	if ($metcnnya['type_claim']=="Refund" OR $metcnnya['type_claim']=="Death") {
		$rahmadTTD = $pdf->Image('image/ttd_Ibnu.jpg',160,$metparaf);

		$pdf->cell(150,6,' ', 0, 0, 'R');	$rahmadNamina = $pdf->cell(30,6,'Ibnu Prastowo');$pdf->Ln();
		$pdf->cell(150,6,' ', 0, 0, 'R');	$rahmadJbt = $pdf->cell(30,6,'LIFE DIVISION HEAD');
	}else{
		$rahmadTTD = $pdf->Image('image/ttd_andress.jpg',160,$metparaf);

		$pdf->cell(150,6,' ', 0, 0, 'R');	$rahmadNamina = $pdf->cell(30,6,'Andress Manansal');$pdf->Ln();
		$pdf->cell(150,6,' ', 0, 0, 'R');	$rahmadJbt = $pdf->cell(30,6,'KADIV TEKNIK');
	}
	$pdf->setFont('Arial','U',10);

		//PARAF
/*
		$metlokasi= $y + 60;
		$metparaf= $metlokasi + 5;
		$metnama= $metparaf + 15;
		$metbag= $metnama + 5;
		$pdf->setFont('Arial','B',10);
		$pdf->setXY(150,$metlokasi); $pdf->cell(30,6,'Jakarta, '.date("d F Y",strtotime($metcnnya['tgl_createcn'])).'');

		if ($metcnnya['type_claim']=="Refund" OR $metcnnya['type_claim']=="Death") {
		$rahmadTTD = $pdf->Image('image/ttd_Ibnu.jpg',150,$metparaf);
		$rahmadNamina = $pdf->setXY(150,$metnama); $pdf->cell(30,6,'Ibnu Prastowo');
		$rahmadJbt = $pdf->setXY(150,$metbag); $pdf->cell(30,6,'LIFE DIVISION HEAD');
		}else{
		$rahmadTTD = $pdf->Image('image/ttd_andress.jpg',150,$metparaf);
		$rahmadNamina = $pdf->setXY(150,$metnama); $pdf->cell(30,6,'Andress Manansal');
		$rahmadJbt = $pdf->setXY(150,$metbag); $pdf->cell(30,6,'KADIV TEKNIK');
		}
		$pdf->setFont('Arial','U',10);
		$rahmadTTD;
		$rahmadNamina;
		$pdf->setFont('Arial','',10);
		$rahmadJbt;
*/
		$pdf->Output();
		$pdf->Output('../ajk_file/cn/'.$name,"F");
		;
		break;

case "ajkpdfcnrefund":
	$pdf=new FPDF('P','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',14);
	$pdf->Text(90, 40,'CREDIT NOTE');

	$metcnrefund = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$_REQUEST['idcn'].'" AND type_claim="Refund"'));
	$metcnpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metcnrefund['id_nopol'].'"'));
	$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metcnrefund['id_cost'].'"'));
	$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metcnpolis['benefit_type'].'"'));
	$pdf->SetFont('Arial','B',10);	$pdf->Text(10,50, $metcost['name']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10,55, 'Nomor Polis');	$pdf->Text(40,55, ': '.$metcnpolis['nopol']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10,60, 'Nomor CN');	$pdf->Text(40,60, ': '.$_REQUEST['idcn']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10,65, 'Jenis Asuransi');	$pdf->Text(40,65, ': Asuransi Jiwa Kredit-'.$metmaster['msdesc']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,55, 'Tanggal Efektif Polis');	$pdf->Text(170,55, ': '._convertDate($metcnpolis['start_date']));
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,60, 'Tanggal Nota CN');			$pdf->Text(170,60, ': '._convertDate($metcnrefund['tgl_createcn']));
	$pdf->SetFont('Arial','',10);	$pdf->Text(135,65, 'Regional');					$pdf->Text(170,65, ': '. $metcnrefund['id_regional']);

	$pdf->SetFont('Arial','B',9);	$pdf->Text(10,75, 'DATA PESERTA ASURANSI JIWA KUMPULAN');
	$y_initial = 84;
	$y_axis1 = 78;

	$pdf->setFont('Arial','',7);

	$pdf->setFillColor(233,233,233);
	$pdf->setY($y_axis1);
	$pdf->setX(5);

	$pdf->cell(5,6,'No',1,0,'C',1);
	$pdf->cell(14,6,'No. Debitur',1,0,'C',1);
	$pdf->cell(20,6,'No. DN',1,0,'C',1);
	$pdf->cell(30,6,'Nama',1,0,'C',1);
	$pdf->cell(14,6,'Start.Ins',1,0,'C',1);
	$pdf->cell(7,6,'Tenor',1,0,'C',1);
	$pdf->cell(14,6,'End.Ins',1,0,'C',1);
	$pdf->cell(15,6,'Date Refund',1,0,'C',1);
	$pdf->cell(7,6,'MA-j',1,0,'C',1);
	$pdf->cell(7,6,'MA-s',1,0,'C',1);
	$pdf->cell(15,6,'UP',1,0,'C',1);
	$pdf->cell(14,6,'Premi',1,0,'C',1);
	$pdf->cell(14,6,'Total',1,0,'C',1);
	$pdf->cell(25,6,'Cabang',1,0,'C',1);


	$peserta = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim = "'.$_REQUEST['idcn'].'" AND status_peserta="Refund" AND del IS NULL');
	$Jumpesertaref = mysql_num_rows($peserta);

	while ($metpeserta = mysql_fetch_array($peserta))
	{
		$metpesertarefund = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$metpeserta['id_cost'].'" AND id_peserta="'.$metpeserta['id_peserta'].'" AND type_claim="Refund" AND del IS NULL'));
		$kredit = explode("/", $metpeserta['kredit_tgl']);
		$nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
		$now = new T10DateCalc($nowkredit);
		$periodbulan = $now->compareDate($metpesertarefund['tgl_claim']) / 30.4375;
		//	$periodbulan = $now->compareDate($metklaim['tgl_klaim']);
		$maj = ceil($periodbulan);
		$r = $metpeserta['kredit_tenor'] - $maj;
		if ($r < 0) {	$mas = 0;	}else{	$mas = $r;	}

//CEK KARAKTER NAMA
$findmetnama="/";
$fposnama = stripos($metpeserta['nama'], $findmetnama);
if ($fposnama === false) {	$ceknamanya = $metpeserta['nama'];
	if (strlen($metpeserta['nama'])>19) {
		$elmet = explode(" ", $metpeserta['nama']);
		$namaakhir = substr($elmet[2],0,1);
		$ceknamanya = $elmet[0].' '. $elmet[1].' '. $namaakhir;
	} else { $ceknamanya = $metpeserta['nama'];	}
}
else	{	$riweuhnama = explode("/", $metpeserta['nama']);
	$ceknamanya = $riweuhnama[0];
}
//CEK KARAKTER NAMA
//cekformat tanggal refund
		$findtglcn="/";
		$fpostglcn = stripos($metpesertarefund['tgl_claim'], $findtglcn);
		if ($fpostglcn === false) {
			$ftglcn = explode("-", $metpesertarefund['tgl_claim']);	$ftglcn1 = $ftglcn[2].'-'.$ftglcn[1].'-'.$ftglcn[0];		//tanggal refund
		}else{
			$ftglcn1 = _convertDate($metpesertarefund['tgl_claim']);		//tanggal refund
		}
//cekformat tanggal refund

/*
	$no++;
	$pdf->setY($y);
	$pdf->setX(1);
	$pdf->cell(5,6,$no,1,0,'C');
	$pdf->cell(14,6,$metpeserta['id_peserta'],1,0,'C');
	$pdf->cell(30,6,$ceknamanya,1,0,'L');
	$pdf->cell(14,6,$metpeserta['tgl_lahir'],1,0,'C');
	$pdf->cell(17,6,$metpeserta['kredit_tgl'],1,0,'C');
	$pdf->cell(7,6,$metpeserta['kredit_tenor'],1,0,'C');
	$pdf->cell(17,6,$metpeserta['kredit_akhir'],1,0,'C');
	$pdf->cell(15,6, $ftglcn1,1,0,'C');
	$pdf->cell(7,6,$maj,1,0,'C');
	$pdf->cell(7,6,$mas,1,0,'C');
	$pdf->cell(18,6,duit($metpeserta['kredit_jumlah']),1,0,'R');
	$pdf->cell(15,6,duit($metpeserta['premi']),1,0,'R');
	$pdf->cell(16,6,duit($metpesertarefund['total_claim']),1,0,'R');
	$pdf->cell(20,6,$metpesertarefund['id_cabang'],1,0,'C');
	$y = $y + $row;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpeserta['biaya_adm'];
	$totalTPremi += $metpesertarefund['total_claim'];
*/
	$cell[$i][0] = $metpeserta['id_peserta'];
	$cell[$i][1] = substr($metpeserta['id_dn'],6);
	$cell[$i][2] = $ceknamanya;
	$cell[$i][3] = $metpeserta['kredit_tgl'];
	$cell[$i][4] = $metpeserta['kredit_tenor'];
	$cell[$i][5] = $metpeserta['kredit_akhir'];
	$cell[$i][6] = $ftglcn1;
	$cell[$i][7] = $maj;
	$cell[$i][8] = $mas;
	$cell[$i][9] = duit($metpeserta['kredit_jumlah']);
	$cell[$i][10] = duit($metpeserta['premi']);
	$cell[$i][11] = duit($metpesertarefund['total_claim']);
	$cell[$i][12] = $metpesertarefund['id_cabang'];
	$i++;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpeserta['biaya_adm'];
	$totalTPremi += $metpesertarefund['total_claim'];
	}
	$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{
	$pdf->setX(5);
		//menampilkan data dari hasil query database
		$pdf->cell(5,6,$j+1,1,0,'C');
		$pdf->cell(14,6,$cell[$j][0],1,0,'C');
		$pdf->cell(20,6,$cell[$j][1],1,0,'L');
		$pdf->cell(30,6,$cell[$j][2],1,0,'L');
		$pdf->cell(14,6,$cell[$j][3],1,0,'C');
		$pdf->cell(7,6,$cell[$j][4],1,0,'C');
		$pdf->cell(14,6,$cell[$j][5],1,0,'C');
		$pdf->cell(15,6,$cell[$j][6],1,0,'C');
		$pdf->cell(7,6,$cell[$j][7],1,0,'C');
		$pdf->cell(7,6,$cell[$j][8],1,0,'C');
		$pdf->cell(15,6,$cell[$j][9],1,0,'R');
		$pdf->cell(14,6,$cell[$j][10],1,0,'R');
		$pdf->cell(14,6,$cell[$j][11],1,0,'R');
		$pdf->cell(25,6,$cell[$j][12],1,0,'L');
		$pdf->Ln();
	}
	$pdf->setX(5);
	$pdf->setFont('Arial','B',7);
	$pdf->cell(148,6,'Total',1,0,'C');
	$pdf->cell(14,6,duit($totalStd),1,0,'R');
	$pdf->cell(14,6,duit($totalTPremi),1,0,'R');
	$pdf->cell(25,6,'',1,0,'R');
	$pdf->Ln();
	$pdf->Ln();
//if ($Jumpesertaref >= 26) {	$pdf->AddPage();	$pdf->MultiCell(90, 20, '', 0, 'R');	}
//else					  {	$pdf->setXY(1,$y);	$pdf->MultiCell(90, 10, '', 0, 'R');	}

/*
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180, 4, '1. Transfer melalui Bank ke rekening :
	    PT. Asuransi Jiwa Recapital
	    '.$metcnpolis['bank_name'].' - '.$metcnpolis['bank_branch'].' - No. Rek. '.$metcnpolis['bank_accNo'].'', 0);
	$pdf->MultiCell(180, 4, '2. Mohon tidak melakukan pembayaran secara tunai', 0);
	$pdf->MultiCell(180, 4, '3. Biaya yang timbul dari proses transfer yang dilakukan harus ditanggung oleh Pemegang Polis', 0);
	$pdf->MultiCell(180, 4, '4. Mohon mencantumkan keterangan Pembayaran Nota Debet No 13.01.02414 pada slip pembayaran pada saat melakukan transfer.', 0);
	$pdf->MultiCell(180, 4, '5. Apabila ada pertanyaan lebih lanjut, mohon untuk dapat menghubungi kami di No. Telepon : 021-725 6272, No. Fax : 021-7253858', 0);
*/

/*
   $t = $y + 60;
   $pdf->SetFont('Arial','',10);	$pdf->Text(177,$t, ': '._convertDate($metdnnya['tgl_dn']));
   $pdf->Image('image/ttd_andress.jpg',150,$t);
*/
	//PARAF
	if ($j >=23) {
	$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();
	}else{

	}
	$pdf->setFont('Arial','',11);
	$pdf->cell(140,7,' ', 0, 0, 'R');
	$pdf->cell(30,7,'Jakarta, '.date("d F Y",strtotime($metcnrefund['tgl_createcn'])).'', 0, 0, 'L');
	$pdf->Ln();
	$pdf->Image('image/ttd_Ibnu.jpg',155);
//	$pdf->Ln();
	$pdf->setFont('Arial','B','U',11);
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'Ibnu Prastowo', 0, 0, 'L');
	$pdf->Ln();
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'LIFE DIVISION HEAD', 0, 0, 'L');

	$pdf->Output();
	;
	break;

case "pri":
$mamet = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
$cost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

//TOTAL DN
$m = mysql_query('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$mamet['id'].'"');
while ($me = mysql_fetch_array($m)) {	$jdn += $me['totalpremi'];	}	$a =$jdn;
//TOTAL DN

//PENGURANGAN TOTAL CN
$metcn = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$_REQUEST['id'].'"');
while ($mCN = mysql_fetch_array($metcn))	{	$jcnPRM += $mCN['total_claim'];	}	$aCN =$jcnPRM;
//PENGURANGAN TOTAL CN

$totalPRMnya =$mamet['jumlah'] - $a + $aCN;		//TOTAL JUMLAH PRM

$juml = $mamet['jumlah'] - $mamet['terbayar'];
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td align="center"><img src="../image/logo_recapitalife.png" width="150"></td><td align="center" valign="bottom"><font size="6"><b>UPDATE ARM</b></font></td></tr>
	  <tr><td width="15%">Company Name</td><td>: <b>'.$cost['name'].'</b></td></tr>
	  <tr><td>Reg. PRM</td><td>: <b>'.$mamet['id_prm'].'</b></td></tr>
	  <tr><td>Amount</td><td>: '.duit($mamet['jumlah']).'</td></tr>
	  <tr><td>Used Payment Debit Note</td><td>: '.duit($a).'</td></tr>
	  <tr><td>Used Payment Credit Note</td><td>: '.duit($aCN).'</td></tr>
	  <tr><td>Remaining Payment</td><td>: <font color="red">'.duit($totalPRMnya).'</font></td></tr>
	  </table>
	  <table id="table-1" border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr class="yellow"><td width="5%">No</td>
	  	<td>Reg. DN</td>
	  	<td width="10%">Total</td>
	  	<td width="8%">Create Date DN</td>
	  	<td width="5%">Status</td>
	  	<td width="8%">Paid Date DN</td>
	  	<td width="10%">Regional</td>
	  	<td width="15%">Branch</td>
	  </tr>';
$metdn = mysql_query('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$mamet['id'].'"');
while ($fudn = mysql_fetch_array($metdn)){
if ($fudn['id_cabang']=="") {	$metcabang = $fudn['id_cabang_old'];	}
else	{	$metcabang = $fudn['id_cabang'];	}

echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$fudn['dn_kode'].'</td>
		  <td align="right">'.duit($fudn['totalpremi']).'</td>
		  <td align="center">'._convertDate($fudn['tgl_dn_paid']).'</td>
		  <td align="center">'.$fudn['dn_status'].'</td>
		  <td align="center">'._convertDate($fudn['tgl_dn_paid']).'</td>
		  <td align="center">'.$fudn['id_regional'].'</td>
		  <td>'.$metcabang.'</td>
	  </tr>';
$tmamet +=$fudn['totalpremi'];
}
echo '<tr class="yellowtotal"><td colspan="2" align="center">Total DN</td><td align="right">'.duit($tmamet).'</td><td colspan="5"></td></tr>
	  <tr><td> &nbsp;</td></tr>';
echo '<tr class="yellow"><td width="5%">No</td>
	  	<td>Reg. CN</td>
	  	<td width="10%">Total</td>
	  	<td width="8%">Create Date CN</td>
	  	<td width="5%">Status</td>
	  	<td width="8%">Paid Date CN</td>
	  	<td width="10%">Regional</td>
	  	<td width="15%">Branch</td>
	  </tr>';
$metcn = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$mamet['id'].'" ORDER BY tgl_createcn DESC');
while ($fucn = mysql_fetch_array($metcn)){
if ($fucn['id_cabang']=="") {	$metcabangcn = $fucn['id_cabang_old'];	}
else	{	$metcabangcn = $fucn['id_cabang'];	}

echo '<tr><td align="center">'.++$no2.'</td>
			  <td>'.$fucn['id_cn'].'</td>
			  <td align="right">'.duit($fucn['total_claim']).'</td>
			  <td align="center">'._convertDate($fucn['tgl_byr_claim']).'</td>
			  <td align="center">paid</td>
			  <td align="center">'._convertDate($fucn['tgl_byr_claim']).'</td>
			  <td align="center">'.$fucn['id_regional'].'</td>
			  <td>'.$metcabangcn.'</td>
		  </tr>';
	$cmamet +=$fucn['total_claim'];
}
echo '<tr class="yellowtotal"><td colspan="2" align="center">Total CN</td><td align="right">'.duit($cmamet).'</td><td colspan="5"></td></tr>';
echo '</table><br />
	  <table border="0" width="100%">
	  <tr><td width="25%" align="center">TTD 1</td>
	  	  <td width="25%" align="center">TTD 2</td>
	  	  <td width="25%" align="center">TTD 3</td>
	  	  <td width="25%" align="center">TTD 4</td>
	  </tr>';

		if (!$id){
			echo "<script language=javascript>
				function printWindow() {
				bV = parseInt(navigator.appVersion);
				if (bV >= 4) window.print();}
				printWindow();
				</script>";
		}
		;
		break;

case "updatednarm":
if ($_REQUEST['tglpaid'])		{	$satu= 'AND fu_ajk_dn.tgl_dn_paid = "'.$_REQUEST['tglpaid'].'"';	}
if ($_REQUEST['cost'])			{	$dua= 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['cost'].'"';	}
if ($_REQUEST['reg'])			{	$tiga= 'AND fu_ajk_dn.id_regional = "'.$_REQUEST['reg'].'"';	}

$cost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cost'].'"'));
/* QUERY LAMA
$m = mysql_query('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.dn_kode AS jDN,
fu_ajk_dn.totalpremi AS jTotal,
fu_ajk_cn.id_cn AS jCN,
fu_ajk_cn.total_claim AS jNilai,
fu_ajk_cn.type_claim,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_cabang,
fu_ajk_dn.id_cabang_old
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
ORDER BY
fu_ajk_dn.tgl_dn_paid ASC');
*/

$m = mysql_query('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.dn_kode AS jDN,
fu_ajk_dn.totalpremi AS jTotal,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_cabang,
fu_ajk_dn.id_cabang_old
FROM
fu_ajk_dn
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
ORDER BY
fu_ajk_dn.tgl_dn_paid ASC');
while ($nilaim = mysql_fetch_array($m)) {
/* QUERY LAMA
	if ($nilaim['type_claim']=="Death") {	$d_type_nilai = 0;	}else{	$d_type_nilai =$nilaim['jNilai'];	}			//CEK CN BILA ADA DATA CN YANG MENINGGAL DATA TDK DI HITUNG
	 $nettsoa = $fudn['jTotal'] - $d_type_nilai;
	   $tpremi +=$nilaim['jTotal'].'';
	   $tnilai +=$d_type_nilai.'';
	*/
	$tpremi +=$nilaim['jTotal'];
}
//$nettpremi =$tpremi - $tnilai;

//NOMOR DN ARM
$nomordnarm = explode("-", $_REQUEST['tglpaid']);
$_nomordnarm = substr($nomordnarm[0], 2).''.$nomordnarm[1].''.$nomordnarm[2];
//NOMOR DN ARM
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td align="center" width="15%"><img src="../image/logo_recapitalife.png" width="150"></td>
	  	  <td align="center" valign="bottom"><font size="6"><b>UPDATE ARM</b></font><br /><font size="1">No. : ARMDN.'.$_nomordnarm.'-'.$futglreas.'</font>
	  	  </td><td width="15%">&nbsp;</td>
	  </tr>
	  </table>
	  <table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td width="30%">Company Name</td><td>: <b>'.$cost['name'].'</b></td></tr>
	  <tr><td>Date Payment</td><td>: <b>'._convertDate($_REQUEST['tglpaid']).'</b></td></tr>
	  <tr><td>Used Payment Debit Note</td><td>: <b>'.duit($tpremi).'</b></td></tr>
	  </table>
	  <table id="table-1" border="0" width="100%" cellpadding="5" cellspacing="1" align="center">
	  <tr class="yellow"><td width="5%">No</td>
	  	<td width="8%">Tanggal DN</td>
	  	<td width="15%">Debit Note</td>
	  	<td width="10%">Premi</td>
	  	<td>Regional</td>
	  	<td>Branch</td>
	  </tr>';

$metdn = mysql_query('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_regional_old,
fu_ajk_dn.id_area,
fu_ajk_dn.id_cabang,
fu_ajk_dn.id_cabang_old
FROM
fu_ajk_dn
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
ORDER BY
fu_ajk_dn.tgl_createdn ASC');

while ($fudn = mysql_fetch_array($metdn)){
if ($fudn['id_cabang']=="") 	{	$metcabang = $fudn['id_cabang_old'];	}	else	{	$metcabang = $fudn['id_cabang'];	}
if ($fudn['id_regional']=="")	{	$metregional = $fudn['id_regional_lama'];	}	else	{	$metregional = $fudn['id_regional'];	}
//if ($fudn['total_claim']=="") 	{	$nilaicnnya = '';	}else{	$nilaicnnya = duit($fudn['total_claim']);	}

echo '<tr><td class="small" align="center">'.++$no.'</td>
		  <td class="small" align="center">'._convertDate($fudn['tgl_createdn']).'</td>
		  <td class="small">'.$fudn['dn_kode'].'</td>
		  <td class="small" align="right">'.duit($fudn['totalpremi']).'</td>
		  <td class="small">'.$metregional.'</td>
		  <td class="small">'.$metcabang.'</td>
	 </tr>';

//echo $totaldatacn['total_claim'];
	$totalpremi +=$fudn['totalpremi'];
//	$totalnilai +=$d_type_nilai;
//	$tmamet +=$nettsoa;
}

echo '<tr class="yellowtotal"><td colspan="3" align="center"><b>TOTAL</b></td>
							  <td align="right"><b>'.duit($totalpremi).'</b></td>
							  <td colspan="2"></td>
	  </tr>
	</table><br />';
echo '<table id="table-2" border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><td colspan="5" align="right">Jakarta, '.$futgl.'</td></tr>
	  <tr><th width="50%" colspan="2">ARM</th>
	  	  <td width="1%">&nbsp;</td>
	  	  <th width="50%" colspan="2">FINANCE</th>
	  </tr>
	  <tr><td width="25%" align="center">Prepared By,</td>
	  	  <td width="25%" align="center">Checked By,</td>
	  	  <td width="1%">&nbsp;</td>
	  	  <td width="25%" align="center">Received By,</td>
	  	  <td width="25%" align="center">Acknowledge By,</td>
	  </tr>
  	  <tr><td colspan="5">&nbsp;<br /><br /><br /></td></tr>
	  <tr><td align="center"><b>( Samino )<br />'.$futgl.'</td>
	  	  <td align="center"><b>( Erni Sumartini )<br />'.$futgl.'</td>
	  	  <td>&nbsp;</td>
	  	  <td align="center"><b>( Nurdiani )<br />'.$futgl.'</td>
	  	  <td align="center"><b>( Rudy Bhakti Setiawan )<br />'.$futgl.'</td>
	  </tr>

	  </table>';

	if (!$id){
		echo "<script language=javascript>
			function printWindow() {
			bV = parseInt(navigator.appVersion);
			if (bV >= 4) window.print();}
			printWindow();
			</script>";
	}
	;
	break;

case "updatecnarm":
if ($_REQUEST['tglpaid'])		{	$satu= 'AND fu_ajk_dn.tgl_dn_paid = "'.$_REQUEST['tglpaid'].'"';	}
if ($_REQUEST['cost'])			{	$dua= 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['cost'].'"';	}
if ($_REQUEST['reg'])			{	$tiga= 'AND fu_ajk_dn.id_regional = "'.$_REQUEST['reg'].'"';	}

$cost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cost'].'"'));

/*
$m_cn = mysql_query('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.dn_kode AS jDN,
fu_ajk_dn.totalpremi AS jTotal,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_cabang,
fu_ajk_dn.id_cabang_old
FROM
fu_ajk_dn
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
ORDER BY
fu_ajk_dn.tgl_dn_paid ASC');
while ($nilaim = mysql_fetch_array($m)) {
	$tpremi +=$nilaim['jTotal'];
}
*/
$_cn = mysql_query('SELECT
fu_ajk_cn.id_cost,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.total_claim,
fu_ajk_cn.type_claim,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_dn = fu_ajk_dn.dn_kode
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_cn.type_claim !="Death"');
while ($nilaicn = mysql_fetch_array($_cn)) {
	if ($nilaicn['total_claim'] < 0) 	{ 	$nilaicntotal = 0;	}	else	{	$nilaicntotal = $nilaicn['total_claim'];	}
	$tnilai +=$nilaicntotal;
}
//NOMOR CN ARM
$nomorcnarm = explode("-", $_REQUEST['tglpaid']);
$_nomorcnarm = substr($nomorcnarm[0], 2).''.$nomorcnarm[1].''.$nomorcnarm[2];
//NOMOR CN ARM
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td align="center" width="15%"><img src="../image/logo_recapitalife.png" width="150"></td>
	  	  <td align="center" valign="bottom"><font size="6"><b>UPDATE ARM</b></font><br /><font size="1">No. : ARMCN.'.$_nomorcnarm.'-'.$futglreas.'</font>
	  	  </td><td width="15%">&nbsp;</td>
	  </tr>
	  </table>
	  <table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td width="30%">Company Name</td><td>: <b>'.$cost['name'].'</b></td></tr>
	  <tr><td>Date Payment DN</td><td>: <b>'._convertDate($_REQUEST['tglpaid']).'</b></td></tr>
	  <tr><td>Total Credit Note</td><td>: <b>'.duit($tnilai).'</b></td></tr>
	  </table>
	  <table id="table-1" border="0" width="100%" cellpadding="5" cellspacing="1" align="center">
	  <tr class="yellow"><td width="5%">No</td>
	  	<td width="15%">Debit Note</td>
	  	<td width="8%">Tanggal CN</td>
	  	<td width="15%">Credit Note</td>
	  	<td width="10%">Nilai</td>
	  	<td>Regional</td>
	  	<td>Branch</td>
	  </tr>';

$metdn = mysql_query('SELECT
fu_ajk_cn.id_cost,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.total_claim,
fu_ajk_cn.type_claim,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_dn = fu_ajk_dn.dn_kode
WHERE
fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_cn.type_claim !="Death"
ORDER BY fu_ajk_dn.tgl_createdn ASC');

while ($fudn = mysql_fetch_array($metdn)){
if ($fudn['id_cabang']=="") 	{	$metcabang = $fudn['id_cabang_old'];	}	else	{	$metcabang = $fudn['id_cabang'];	}
if ($fudn['id_regional']=="")	{	$metregional = $fudn['id_regional_lama'];	}	else	{	$metregional = $fudn['id_regional'];	}
if ($fudn['total_claim'] < 0) 	{ 	$nilaicn = 0;	}	else	{	$nilaicn = $fudn['total_claim'];	}
echo '<tr><td class="small" align="center">'.++$no.'</td>
		  <td class="small">'.$fudn['id_dn'].'</td>
		  <td class="small" align="center">'._convertDate($fudn['tgl_createcn']).'</td>
		  <td class="small">'.$fudn['id_cn'].'</td>
		  <td class="small" align="right">'.duit($nilaicn).'</td>
		  <td class="small">'.$metregional.'</td>
		  <td class="small">'.$metcabang.'</td>
	 </tr>';
	$totalnilai +=$nilaicn;
}

echo '<tr class="yellowtotal"><td colspan="4" align="center"><b>TOTAL</b></td>
		<td align="right"><b>'.duit($totalnilai).'</b></td>
		<td colspan="2"></td>
	  </tr>
	</table><br />';
echo '<table id="table-2" border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><td colspan="5" align="right">Jakarta, '.$futgl.'</td></tr>
	  <tr><th width="50%" colspan="2">ARM</th>
	  	  <td width="1%">&nbsp;</td>
	  	  <th width="50%" colspan="2">FINANCE</th>
	  </tr>
	  <tr><td width="25%" align="center">Prepared By,</td>
	  	  <td width="25%" align="center">Checked By,</td>
	  	  <td width="1%">&nbsp;</td>
	  	  <td width="25%" align="center">Received By,</td>
	  	  <td width="25%" align="center">Acknowledge By,</td>
	  </tr>
  	  <tr><td colspan="5">&nbsp;<br /><br /><br /></td></tr>
	  <tr><td align="center"><b>( Samino )<br />'.$futgl.'</td>
	  	  <td align="center"><b>( Erni Sumartini )<br />'.$futgl.'</td>
	  	  <td>&nbsp;</td>
	  	  <td align="center"><b>( Nurdiani )<br />'.$futgl.'</td>
	  	  <td align="center"><b>( Rudy Bhakti Setiawan )<br />'.$futgl.'</td>
	  </tr>
	  </table>';

if (!$id){
	echo "<script language=javascript>
		function printWindow() {
		bV = parseInt(navigator.appVersion);
		if (bV >= 4) window.print();}
		printWindow();
		</script>";
}
	;
	break;

case "exl":

		$eldn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
		$el = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$eldn['dn_kode'].'"'));

		$xls =& new Spreadsheet_Excel_Writer();
		$xls->send("Report Data DN.xls");
		$sheet =& $xls->addWorksheet('Nomor DN');

		$sheet->hideGridlines();
		$sheet->setlandscape();

		$formattitle =& $xls->addFormat();	$formattitle->setBold();			$formattitle->setAlign('center');
		$formatheader =& $xls->addFormat();	$formatheader->setAlign('left');	$formatheader->setBorder(1);
		$formatheader1 =& $xls->addFormat();$formatheader1->setAlign('right');	$formatheader1->setBorder(1);
		$formatitems =& $xls->addFormat();	$formatitems->setTextWrap();		$formatitems->setAlign('Center');	$formatitems->setBold();		$formatitems->setColor('Black');	$formatitems->setBorder(1);		$formatitems->setFgColor('silver');
		$formatitems1 =& $xls->addFormat();	$formatitems1->setTextWrap();		$formatitems1->setAlign('left');	$formatitems1->setBorder(1);	$formatitems1->setFgColor('red');
		$formatitems2 =& $xls->addFormat();	$formatitems2->setTextWrap();		$formatitems2->setAlign('right');	$formatitems2->setBorder(1);	$formatitems2->setFgColor('red');
		$formatitems3 =& $xls->addFormat();	$formatitems3->setTextWrap();		$formatitems3->setAlign('left');	$formatitems3->setBorder(1);	$formatitems3->setFgColor('yellow');
		$formatnumber =& $xls->addFormat();	$formatnumber->setAlign('center');	$formatnumber->setBorder(1);

		$sheet->setColumn(0,0, 5);
		$sheet->setColumn(1,1, 25);		$sheet->setMerge(1, 0, 1, 18);		//NAMA PT
		$sheet->setColumn(2,2, 10);		$sheet->setMerge(2, 0, 2, 18);		//Laporan DN

		$sheet->setColumn(3,3, 15);
		$sheet->setColumn(4,4, 16);
		$sheet->setColumn(5,5, 10);
		$sheet->setColumn(6,6, 10);
		$sheet->setColumn(7,7, 10);
		$sheet->setColumn(8,8, 10);
		$sheet->setColumn(8,6, 10);

		$sheet->setMerge(5, 0, 6, 0);		//NO
		$sheet->setMerge(5, 1, 6, 1);		//SPAJ
		$sheet->setMerge(5, 2, 6, 2);		//NAMA
		$sheet->setMerge(5, 3, 6, 3);		//TGL LAHIR
		$sheet->setMerge(5, 4, 6, 4);		//USIA
		$sheet->setMerge(5, 5, 5, 7);		//IDENTITAS KARTU
		$sheet->setMerge(5, 8, 5, 11);		//STATUS KREDIT
		$sheet->setMerge(5, 12, 6, 12);		//PREMI
		$sheet->setMerge(5, 13, 6, 13);		//BIAYA ADM
		$sheet->setMerge(5, 14, 6, 14);		//REFUND
		$sheet->setMerge(5, 15, 6, 15);		//TOTAL PREMI
		$sheet->setMerge(5, 16, 6, 16);		//CABANG
		$sheet->setMerge(5, 17, 6, 17);		//AREA
		$sheet->setMerge(5, 18, 6, 18);		//REEGIONAL

		$sheet->write(1,0,'PT. RECAPITAL', $formattitle);
		$sheet->write(2,0,'LAPORAN DN '.$eldn['dn_kode'].'', $formattitle);

		$sheet->write(5,0,'No',$formatitems);
		$sheet->write(5,1,'SPAK',$formatitems);
		$sheet->write(5,2,'Nama',$formatitems);
		$sheet->write(5,3,'Tgl Lahir',$formatitems);
		$sheet->write(5,4,'Usia',$formatitems);
		$sheet->write(5,5,'Identitas Kartu',$formatitems);
		$sheet->write(6,5,'Type',$formatitems);
		$sheet->write(6,6,'Nomor',$formatitems);
		$sheet->write(6,7,'Periode',$formatitems);
		$sheet->write(5,8,'Status Kredit',$formatitems);
		$sheet->write(6,8,'Tanggal Kredit',$formatitems);
		$sheet->write(6,9,'Tenor',$formatitems);
		$sheet->write(6,10,'Tanggal Akhir',$formatitems);
		$sheet->write(6,11,'Jumlah',$formatitems);
		$sheet->write(5,12,'Premi',$formatitems);
		$sheet->write(5,13,'Adm',$formatitems);
		$sheet->write(5,14,'Refund',$formatitems);
		$sheet->write(5,15,'Total Premi',$formatitems);
		$sheet->write(5,16,'Cabang',$formatitems);
		$sheet->write(5,17,'Area',$formatitems);
		$sheet->write(5,18,'Regional',$formatitems);

		$xls->close();
		;
		break;

case "exlAll":
if ($_REQUEST['rdns']!='' AND $_REQUEST['rdne']!='')	{	$satu= 'AND tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';	}
if ($_REQUEST['rpays']!='' AND $_REQUEST['rpaye']!='')	{	$dua= 'AND tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';	}
if ($_REQUEST['rstat'])									{	$tiga = 'AND dn_status LIKE "%' .$_REQUEST['rstat'] . '%"';	}
if ($_REQUEST['rreg'])									{	$empat = 'AND id_regional LIKE "%' .  $_REQUEST['rreg'] . '%"';		}
if ($_REQUEST['rcabang'])								{	$lima = 'AND id_cabang LIKE "%' . $_REQUEST['rcabang'] . '%"';		}
if ($_REQUEST['rdnno'])									{	$enam = 'AND dn_kode LIKE "%' . $_REQUEST['rdnno'] . '%"';		}
if ($_REQUEST['dns']!='' AND $_REQUEST['dne']!='')		{	$tujuh = 'AND dn_kode BETWEEN \''.$_REQUEST['dns'].'\' AND \''.$_REQUEST['dne'].'\'';		}

$met = mysql_query('SELECT * FROM fu_ajk_dn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.'');

	$xls =& new Spreadsheet_Excel_Writer();
	$xls->send("Report Data DN.xls");
	$sheet =& $xls->addWorksheet('Nomor DN');

	$sheet->hideGridlines();
	$sheet->setlandscape();

	$formattitle =& $xls->addFormat();	$formattitle->setBold();			$formattitle->setAlign('center');
	$formatheader =& $xls->addFormat();	$formatheader->setAlign('left');	$formatheader->setBorder(1);
	$formatheader1 =& $xls->addFormat();$formatheader1->setAlign('right');	$formatheader1->setBorder(1);
	$formatitems =& $xls->addFormat();	$formatitems->setTextWrap();		$formatitems->setAlign('Center');	$formatitems->setBold();		$formatitems->setColor('Black');	$formatitems->setBorder(1);		$formatitems->setFgColor('silver');
	$formatitems1 =& $xls->addFormat();	$formatitems1->setTextWrap();		$formatitems1->setAlign('left');	$formatitems1->setBorder(1);
	$formatitems2 =& $xls->addFormat();	$formatitems2->setTextWrap();		$formatitems2->setAlign('right');	$formatitems2->setBorder(1);
	$formatitems3 =& $xls->addFormat();	$formatitems3->setTextWrap();		$formatitems3->setAlign('left');	$formatitems3->setBorder(1);
	$formatnumber =& $xls->addFormat();	$formatnumber->setAlign('center');	$formatnumber->setBorder(1);

	$sheet->setColumn(0,0, 5);
	$sheet->setColumn(1,1, 25);		$sheet->setMerge(1, 0, 1, 9);		//NAMA PT
	$sheet->setColumn(2,2, 10);		$sheet->setMerge(2, 0, 2, 9);		//Laporan DN
	$sheet->write(1,0,'PT. RECAPITAL', $formattitle);
	$sheet->write(2,0,'REKAP LAPORAN DN APLIKASI AJK-ONLINE', $formattitle);

//	$startawal = (explode)
	$sheet->setColumn(4,0, 25);		$sheet->setMerge(4, 6, 4, 9);		$sheet->write(4,6,'Periode : '.$_REQUEST['rdns'].' s.d '.$_REQUEST['rdne'].'',$formatitems1);
	$sheet->setColumn(5,0, 25);		$sheet->write(5,0,'No',$formatitems);
	$sheet->setColumn(6,0, 25);		$sheet->write(6,0,'NOMOR DN',$formatitems);

	$xls->close();
		;
		break;

case "er_report_premi":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('Report-Premi-'.$_REQUEST['tanggal1'].'-'.$_REQUEST['tanggal2'].'.xls');

	$xls =& new Spreadsheet_Excel_Writer();
	$xls->send("Report_Premi.xls");
	$sheet =& $xls->addWorksheet('Report Premi');

	$sheet->hideGridlines();
	$sheet->setlandscape();

	$formatjudul =& $xls->addFormat();		$formatjudul->setBold();		$formatjudul->setAlign('center');
	$formattitle =& $xls->addFormat();		$formattitle->setTextWrap();	$formattitle->setAlign('Center');	$formattitle->setBold();		$formattitle->setColor('Black');	$formattitle->setBorder(1);		$formattitle->setFgColor('silver');
	$formatitems =& $xls->addFormat();		$formatitems->setTextWrap();	$formatitems->setAlign('center');
	$formatitems1 =& $xls->addFormat();		$formatitems1->setTextWrap();	$formatitems1->setAlign('right');

	$sheet->setMerge(1, 0, 1, 18);		//NAMA PT
	$sheet->setMerge(2, 0, 2, 18);		//Laporan DN

	$sheet->setColumn(0,0, 5);
	$sheet->setColumn(1,1, 15);
	$sheet->setColumn(2,2, 10);
	$sheet->setColumn(3,3, 35);
	$sheet->setColumn(4,4, 15);
	$sheet->setColumn(5,5, 30);
	$sheet->setColumn(6,6, 15);
	$sheet->setColumn(7,7, 8);
	$sheet->setColumn(8,8, 8);
	$sheet->setColumn(9,9, 8);
	$sheet->setColumn(10,10, 15);
	$sheet->setColumn(11,11, 15);
	$sheet->setColumn(12,12, 25);
	$sheet->setColumn(13,13, 10);
	$sheet->setColumn(14,14, 15);
	$sheet->setColumn(15,15, 25);
	$sheet->setColumn(16,16, 15);
	$sheet->setColumn(17,17, 15);
	$sheet->setColumn(18,18, 20);
	$sheet->setColumn(19,19, 20);

	$sheet->write(1,0,'PT. RECAPITAL', $formatjudul);
	$sheet->write(2,0,'LAPORAN PREMI PERIODE '.$tglawal.' s/d '.$tglakhir.'', $formatjudul);

	$sheet->write(5,0,'NO',$formattitle);
	$sheet->write(5,1,'POLIS',$formattitle);
	$sheet->write(5,2,'EFF_DATE',$formattitle);
	$sheet->write(5,3,'CLIENT',$formattitle);
	$sheet->write(5,4,'ID PESERTA',$formattitle);
	$sheet->write(5,5,'NAMA',$formattitle);
	$sheet->write(5,6,'TGL KREDIT',$formattitle);
	$sheet->write(5,7,'TENOR',$formattitle);
	$sheet->write(5,8,'MA-j',$formattitle);
	$sheet->write(5,9,'MA-s',$formattitle);
	$sheet->write(5,10,'UP',$formattitle);
	$sheet->write(5,11,'PREMI',$formattitle);
	$sheet->write(5,12,'NO_DN',$formattitle);
	$sheet->write(5,13,'TGL_DN',$formattitle);
	$sheet->write(5,14,'TGL_BYR_DN',$formattitle);
	$sheet->write(5,15,'NO_CN',$formattitle);
	$sheet->write(5,16,'TGL_CN',$formattitle);
	$sheet->write(5,17,'TGL_BYR_CN',$formattitle);
	$sheet->write(5,18,'CABANG',$formattitle);
	$sheet->write(5,19,'REGIONAL',$formattitle);

$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost ="' . $_REQUEST['id_cost'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND vkredit_tgl BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';		}

	$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id!="" '.$satu.' '.$dua.' ORDER BY vkredit_tgl ASC, input_by DESC');
	$metno = 6;
	$y = 0;
	$erday = date("d/m/Y");
	while ($met = mysql_fetch_array($el)) {
		$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_polis'].'"'));
		$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
		$metdn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$met['id_dn'].'"'));
		$metcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$met['id_klaim'].'"'));

		$awal = explode ("/", $met['kredit_tgl']);		$hari = $awal[0];	$bulan = $awal[1];		$tahun = $awal[2];
		$akhir = explode ("/", $erday);					$hari2 = $akhir[0];	$bulan2 = $akhir[1];	$tahun2 = $akhir[2];
		$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
		$sisahr=floor($jhari);
		$sisabulan =ceil($sisahr / 30.4375);
		$masisa = $met['kredit_tenor'] - $sisabulan;

	$sheet->write($metno,0,++$no, $formatitems);
	$sheet->write($metno,1,$metpolis['nopol'], $formatitems);
	$sheet->write($metno,2,'', $formatitems);
	$sheet->write($metno,3,$metcost['name']);
	$sheet->write($metno,4,"'".$met['id_peserta']);
	$sheet->write($metno,5,$met['nama']);
	$sheet->write($metno,6,$met['kredit_tgl'], $formatitems);
	$sheet->write($metno,7,$met['kredit_tenor'], $formatitems);
	$sheet->write($metno,8,$sisabulan, $formatitems);
	$sheet->write($metno,9,$masisa, $formatitems);
	$sheet->write($metno,10,$met['kredit_jumlah'], $formatitems1);
	$sheet->write($metno,11,$met['premi'], $formatitems1);
	$sheet->write($metno,12,$met['id_dn'], $formatitems);
	$sheet->write($metno,13,$metdn['tgl_createdn'], $formatitems);
	$sheet->write($metno,14,$metdn['tgl_dn_paid'], $formatitems);
	$sheet->write($metno,15,$metcn['id_cn'], $formatitems);
	$sheet->write($metno,16,$metcn['tgl_createcn'], $formatitems);
	$sheet->write($metno,17,$metcn['tgl_byr_claim'], $formatitems);
	$sheet->write($metno,18,$met['cabang'], $formatitems);
	$sheet->write($metno,19,$met['regional'], $formatitems);
	$metno++;
	}
	$xls->close();
	;
	break;

case "er_report_uw_cn":
$tgl1 = explode("-", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("-", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['ccostumer'])		{	$satu = 'AND id_cost ="' . $_REQUEST['ccostumer'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';		}

		$xls =& new Spreadsheet_Excel_Writer();
		$xls->send("Report_CN.xls");
		$sheet =& $xls->addWorksheet('Report CN');

		$sheet->hideGridlines();
		$sheet->setlandscape();

		$formatjudul =& $xls->addFormat();		$formatjudul->setBold();	$formatjudul->setAlign('center');
		$formattitle =& $xls->addFormat();		$formattitle->setTextWrap();$formattitle->setAlign('Center');	$formattitle->setBold();		$formattitle->setColor('Black');	$formattitle->setBorder(1);		$formattitle->setFgColor('silver');
		$formatitems =& $xls->addFormat();		$formatitems->setTextWrap();$formatitems->setAlign('center');
		$formatitems1 =& $xls->addFormat();		$formatitems1->setTextWrap();$formatitems1->setAlign('right');

		$sheet->setMerge(1, 0, 1, 12);		//NAMA PT
		$sheet->setMerge(2, 0, 2, 12);		//Laporan DN

		$sheet->setColumn(0,0, 5);
		$sheet->setColumn(1,1, 15);
		$sheet->setColumn(2,2, 35);
		$sheet->setColumn(3,3, 15);
		$sheet->setColumn(4,4, 30);
		$sheet->setColumn(5,5, 15);
		$sheet->setColumn(6,6, 8);
		$sheet->setColumn(7,7, 8);
		$sheet->setColumn(8,8, 15);
		$sheet->setColumn(9,9, 15);
		$sheet->setColumn(10,10, 15);
		$sheet->setColumn(11,10, 15);
		$sheet->setColumn(12,12, 20);
		$sheet->setColumn(13,13, 20);


		$sheet->write(1,0,'PT. RECAPITAL', $formatjudul);
		$sheet->write(2,0,'LAPORAN PREMI PERIODE '.$tglawal.' s/d '.$tglakhir.'', $formatjudul);

		$sheet->write(5,0,'NO',$formattitle);
		$sheet->write(5,1,'POLIS',$formattitle);
		$sheet->write(5,2,'CLIENT',$formattitle);
		$sheet->write(5,3,'ID PESERTA',$formattitle);
		$sheet->write(5,4,'NAMA',$formattitle);
		$sheet->write(5,5,'TGL KREDIT',$formattitle);
		$sheet->write(5,6,'TENOR',$formattitle);
		$sheet->write(5,7,'UP',$formattitle);
		$sheet->write(5,8,'PREMI',$formattitle);
		$sheet->write(5,9,'NO_CN',$formattitle);
		$sheet->write(5,10,'TGL_CN',$formattitle);
		$sheet->write(5,11,'TGL_BYR_CN',$formattitle);
		$sheet->write(5,12,'CABANG',$formattitle);
		$sheet->write(5,13,'REGIONAL',$formattitle);

		$el = mysql_query('SELECT * FROM fu_ajk_cn INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cn = fu_ajk_peserta.id_klaim
		WHERE fu_ajk_cn.id_cn = fu_ajk_peserta.id_klaim AND fu_ajk_cn.id!="" '.$satu.' '.$dua.' ORDER BY fu_ajk_cn.tgl_createcn DESC, fu_ajk_cn.input_by DESC');
		$metno = 6;
		$y = 0;
		while ($erpeserta = mysql_fetch_array($el)) {
		$Rpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id = "'.$erpeserta['id_nopol'].'"'));
		$Rclient = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id = "'.$erpeserta['id_cost'].'"'));

		$sheet->write($metno,0,++$no, $formatitems);
		$sheet->write($metno,1,$Rpolis['nopol'], $formatitems);
		$sheet->write($metno,2,$Rclient['name'], $formatitems);
		$sheet->write($metno,3,$erpeserta['id_peserta'], $formatitems);
		$sheet->write($metno,4,$erpeserta['nama'], $formatitems);
		$sheet->write($metno,5,$erpeserta['kredit_tgl'], $formatitems);
		$sheet->write($metno,6,$erpeserta['kredit_tenor'], $formatitems);
		$sheet->write($metno,7,$erpeserta['kredit_jumlah'], $formatitems);
		$sheet->write($metno,8,$erpeserta['totalpremi'], $formatitems);
		$sheet->write($metno,9,$erpeserta['id_cn'], $formatitems);
		$sheet->write($metno,10,$erpeserta['tgl_createcn'], $formatitems);
		$sheet->write($metno,11,$erpeserta['tgl_byr_claim'], $formatitems);
		$sheet->write($metno,12,$erpeserta['id_cabang'], $formatitems);
		$sheet->write($metno,13,$erpeserta['id_regional'], $formatitems);
		$metno++;
		}
		$xls->close();
		;
		break;

case "er_report_peserta":
	$tgl1 = explode("-", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("-", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost ="' . $_REQUEST['id_cost'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND vkredit_tgl BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';		}
//if ($_REQUEST['Rpembayaran']=="0")	{	$tiga = 'AND status_bayar = "0"';	}else{	$tiga = 'AND status_bayar LIKE "%'.$_REQUEST['Rpembayaran'].'%"';	}
if ($_REQUEST['Rpembayaran'])	{	$tiga = 'AND status_bayar = "'.$_REQUEST['Rpembayaran'].'"';	}

	$xls =& new Spreadsheet_Excel_Writer();
	$xls->send("Report_Peserta_'".$_REQUEST['tanggal1']."'_s/d_'".$_REQUEST['tanggal2']."'.csv");
	$sheet =& $xls->addWorksheet('Report Peserta');

	$sheet->hideGridlines();
	$sheet->setlandscape();

	$formatjudul =& $xls->addFormat();		$formatjudul->setBold();	$formatjudul->setAlign('center');
	$formattitle =& $xls->addFormat();		$formattitle->setTextWrap();$formattitle->setAlign('Center');	$formattitle->setBold();		$formattitle->setColor('Black');	$formattitle->setBorder(1);		$formattitle->setFgColor('silver');
	$formatitems =& $xls->addFormat();		$formatitems->setTextWrap();$formatitems->setAlign('center');
	$formatitems1 =& $xls->addFormat();		$formatitems1->setTextWrap();$formatitems1->setAlign('right');
	$formatitems2 =& $xls->addFormat();		$formatitems2->setTextWrap();$formatitems2->setAlign('left');

	$sheet->setMerge(1, 0, 1, 12);		//NAMA PT
	$sheet->setMerge(2, 0, 2, 12);		//Laporan DN

	$sheet->setColumn(0,0, 5);			//NO
	$sheet->setColumn(1,1, 15);			//SPAJ
	$sheet->setColumn(2,2, 25);			//DEBIT NOTE
	$sheet->setColumn(3,3, 15);			//ID PESERTA
	$sheet->setColumn(4,4, 35);			//NAMA
	$sheet->setColumn(5,5, 15);			//GENDER
	$sheet->setColumn(6,6, 15);			//TYPE KARTU
	$sheet->setColumn(7,7, 20);			//NO KARTU
	$sheet->setColumn(8,8, 15);			//DOB
	$sheet->setColumn(9,9, 10);			//USIA
	$sheet->setColumn(10,10, 15);		//KREDIT AWAL
	$sheet->setColumn(11,10, 10);		//TENOR
	$sheet->setColumn(12,12, 15);		//KREDIT AKHIR
	$sheet->setColumn(13,13, 15);		//JUMLAH
	$sheet->setColumn(14,14, 15);		//PREMI
	$sheet->setColumn(15,15, 10);		//ADM
	$sheet->setColumn(16,16, 15);		//EXT PREMI
	$sheet->setColumn(17,17, 20);		//TOTAL PREMI
	$sheet->setColumn(18,18, 10);		//MEDICAL
	$sheet->setColumn(19,19, 10);		//STATUS BAYAR
	$sheet->setColumn(20,20, 20);		//CABANG
	$sheet->setColumn(21,21, 20);		//AREA
	$sheet->setColumn(22,22, 20);		//REG


	$sheet->write(1,0,'PT Asuransi Jiwa Recapital (relife)', $formatjudul);
	$sheet->write(2,0,'LAPORAN PESERTA PERIODE '.$tglawal.' s/d '.$tglakhir.'', $formatjudul);

	$sheet->write(5,0,'NO',$formattitle);
	$sheet->write(5,1,'SPAJ',$formattitle);
	$sheet->write(5,2,'DEBIT NOTE',$formattitle);
	$sheet->write(5,3,'ID PESERTA',$formattitle);
	$sheet->write(5,4,'NAMA',$formattitle);
	$sheet->write(5,5,'GENDER',$formattitle);
	$sheet->write(5,6,'TYPE KARTU',$formattitle);
	$sheet->write(5,7,'NMR KARTU',$formattitle);
	$sheet->write(5,8,'DOB',$formattitle);
	$sheet->write(5,9,'USIA',$formattitle);
	$sheet->write(5,10,'KREDIT AWAL',$formattitle);
	$sheet->write(5,11,'TENOR',$formattitle);
	$sheet->write(5,12,'KREDIT AKHIR',$formattitle);
	$sheet->write(5,13,'JUMLAH',$formattitle);
	$sheet->write(5,14,'PREMI',$formattitle);
	$sheet->write(5,15,'ADM',$formattitle);
	$sheet->write(5,16,'EXT PREMI',$formattitle);
	$sheet->write(5,17,'TOTAL PREMI',$formattitle);
	$sheet->write(5,18,'MEDICAL',$formattitle);
	$sheet->write(5,19,'STATUS',$formattitle);
	$sheet->write(5,20,'CABANG',$formattitle);
	$sheet->write(5,21,'AREA',$formattitle);
	$sheet->write(5,22,'REGIONAL',$formattitle);

	$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE nama!= "" '.$satu.' '.$dua.' '.$tiga.' and del IS NULL ORDER BY kredit_tgl ASC, id_dn ASC, input_time DESC, cabang ASC, id DESC');
	$metno = 6;
	$y = 0;
	while ($erpeserta = mysql_fetch_array($el)) {
if ($erpeserta['cabang']=="") 		{	$Rcabang = $erpeserta['cabang_lama'];	} 		else {	$Rcabang = $erpeserta['cabang'];	}
if ($erpeserta['area']=="") 		{	$Rarea = $erpeserta['area_lama'];	} 			else {	$Rarea = $erpeserta['area'];	}
if ($erpeserta['regional']=="") 	{	$Rregional = $erpeserta['regional_lama'];	} 	else {	$Rregional = $erpeserta['regional'];	}
if ($erpeserta['status_bayar']==0) {	$statusnya = 'Unpaid';	}else{	$statusnya = 'Paid';	}
	$sheet->write($metno,0,++$no, $formatitems);
	$sheet->write($metno,1,$erpeserta['spaj'], $formatitems);
	$sheet->write($metno,2,$erpeserta['id_dn'], $formatitems);
	$sheet->write($metno,3,"'".$erpeserta['id_peserta'], $formatitems);
	$sheet->write($metno,4,$erpeserta['nama'], $formatitems2);
	$sheet->write($metno,5,$erpeserta['gender'], $formatitems);
	$sheet->write($metno,6,$erpeserta['kartu_type'], $formatitems);
	$sheet->write($metno,7,"'".$erpeserta['kartu_no'], $formatitems2);
	$sheet->write($metno,8,$erpeserta['tgl_lahir'], $formatitems);
	$sheet->write($metno,9,$erpeserta['usia'], $formatitems);
	$sheet->write($metno,10,$erpeserta['kredit_tgl'], $formatitems);
	$sheet->write($metno,11,$erpeserta['kredit_tenor'], $formatitems);
	$sheet->write($metno,12,$erpeserta['kredit_akhir'], $formatitems);
/*	$sheet->write($metno,13,number_format($erpeserta['kredit_jumlah']), $formatitems1);
	$sheet->write($metno,14,number_format($erpeserta['premi']), $formatitems1);
	$sheet->write($metno,15,number_format($erpeserta['biaya_adm']), $formatitems1);
	$sheet->write($metno,16,number_format($erpeserta['ext_premi']), $formatitems1);
	$sheet->write($metno,17,number_format($erpeserta['totalpremi']), $formatitems1);
*/
	$sheet->write($metno,13,$erpeserta['kredit_jumlah'], $formatitems1);
	$sheet->write($metno,14,$erpeserta['premi'], $formatitems1);
	$sheet->write($metno,15,$erpeserta['biaya_adm'], $formatitems1);
	$sheet->write($metno,16,$erpeserta['ext_premi'], $formatitems1);
	$sheet->write($metno,17,$erpeserta['totalpremi'], $formatitems1);
	$sheet->write($metno,18,$erpeserta['status_medik'], $formatitems);
	$sheet->write($metno,19,$statusnya, $formatitems);
	$sheet->write($metno,20,$Rcabang, $formatitems2);
	$sheet->write($metno,21,$Rarea, $formatitems2);
	$sheet->write($metno,22,$Rregional, $formatitems2);
	$metno++;
	}
	$xls->close();
	;
	break;

case "downloadreass":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel($futglreas.'-IT-All_Import_Reas_dari_AJK-'.$_REQUEST['tgl'].'-'.$_REQUEST['tgl2'].'-'.$_REQUEST['acc'].'.csv');
	//[yymmdd]-it-all import reas dari [core app]-[personil].csv
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('AJK to ReAss');

/*
	$format =& $workbook->add_format();
	$format->set_align('vcenter');
	$format->set_align('center');
	$format->set_color('white');
	$format->set_bold();
	$format->set_pattern();
	$format->set_fg_color('orange');

	// membuat header tabel dengan format



	$worksheet1->set_row(0, 15);
	$worksheet1->set_column(0, 0, 5);	$worksheet1->write_string(0, 0, "prd_dt", $format);
	$worksheet1->set_column(0, 1, 15);	$worksheet1->write_string(0, 1, "prd_id", $format);
	$worksheet1->set_column(0, 2, 20);	$worksheet1->write_string(0, 2, "pol_no", $format);
	$worksheet1->set_column(0, 3, 15);	$worksheet1->write_string(0, 3, "pol_no_x", $format);
	$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "pph_name", $format);
	$worksheet1->set_column(0, 5, 20);	$worksheet1->write_string(0, 5, "pi_name", $format);
	$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "pi_sex", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "pi_dob", $format);
	$worksheet1->set_column(0, 8, 30);	$worksheet1->write_string(0, 8, "occup", $format);
	$worksheet1->set_column(0, 9, 20);	$worksheet1->write_string(0, 9, "cty_dest", $format);
	$worksheet1->set_column(0, 10, 20);	$worksheet1->write_string(0, 10, "pa_cat", $format);
	$worksheet1->set_column(0, 11, 20);	$worksheet1->write_string(0, 11, "med_cat", $format);
	$worksheet1->set_column(0, 12, 20);	$worksheet1->write_string(0, 12, "eff_dt", $format);
	$worksheet1->set_column(0, 13, 20);	$worksheet1->write_string(0, 13, "mpp", $format);
	$worksheet1->set_column(0, 14, 20);	$worksheet1->write_string(0, 14, "mth", $format);
	$worksheet1->set_column(0, 15, 20);	$worksheet1->write_string(0, 15, "mas", $format);
	$worksheet1->set_column(0, 16, 20);	$worksheet1->write_string(0, 16, "mas_mt", $format);
	$worksheet1->set_column(0, 17, 20);	$worksheet1->write_string(0, 17, "pol_st", $format);
	$worksheet1->set_column(0, 18, 20);	$worksheet1->write_string(0, 18, "pst_dt", $format);
	$worksheet1->set_column(0, 19, 20);	$worksheet1->write_string(0, 19, "jprd_dt", $format);
	$worksheet1->set_column(0, 20, 20);	$worksheet1->write_string(0, 20, "end_no", $format);
	$worksheet1->set_column(0, 21, 20);	$worksheet1->write_string(0, 21, "jeff_dt", $format);
	$worksheet1->set_column(0, 22, 20);	$worksheet1->write_string(0, 22, "rc_type", $format);
	$worksheet1->set_column(0, 23, 20);	$worksheet1->write_string(0, 23, "jrc_id", $format);
	$worksheet1->set_column(0, 24, 20);	$worksheet1->write_string(0, 24, "jrcp_id", $format);
	$worksheet1->set_column(0, 25, 20);	$worksheet1->write_string(0, 25, "cur_id", $format);
	$worksheet1->set_column(0, 26, 20);	$worksheet1->write_string(0, 26, "pol_up", $format);
	$worksheet1->set_column(0, 27, 20);	$worksheet1->write_string(0, 27, "cov_st", $format);
	$worksheet1->set_column(0, 28, 20);	$worksheet1->write_string(0, 28, "cst_dt", $format);
	$worksheet1->set_column(0, 29, 20);	$worksheet1->write_string(0, 29, "rm_rate", $format);
*/

	if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND vkredit_tgl BETWEEN "'.$_REQUEST['tgl'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
	$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id!="" '.$satu.' AND status_aktif="aktif" OR status_aktif="Lapse" ORDER BY kredit_tgl ASC');

	$baris = 0;
while ($r = mysql_fetch_array($el))
{
	$ajk_polis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$r['id_polis'].'"'));
	$ajk_costumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$r['id_cost'].'"'));

	$kredit = explode("/", $fudata['kredit_tgl']);		//TGL KREDIT

	$input_date = explode(" ", $r['input_time']);	$input_date1 = explode("-", $input_date[0]);	$pst_dt =$input_date1[1].'/'.$input_date1[2].'/'.$input_date1[0];	//PST_DT
if ($fudata['status_peserta']=="") {	$cov = "Aktif";	}	else	{	$cov = $r['status_peserta'];	}

	//CEK KODE PRODUK//
if ($r['id_cost']=="14" AND $r['id_polis']=="7") { $prodreas = "GBPND1112";	}
elseif ($r['id_cost']=="14" AND $r['id_polis']=="11") { $prodreas = "GBPND1112";	}
else{	$prodreas ="";	}
	//CEK KODE PRODUK//

	//MASA BERLAKU POLIS//
	$bpolis = explode("-", $ajk_polis['start_date']);
	//MASA BERLAKU POLIS//

	//STATUS POLIS//
if ($r['status_aktif']=="aktif") { $statuspolis="01";	}
elseif ($r['status_aktif']=="Lapse") { $statuspolis="11";	}
elseif ($r['status_aktif']=="Cancel") { $statuspolis="13";	}
elseif ($r['status_aktif']=="Maturity") { $statuspolis="16";	}
else	{	$statuspolis=""; }
	//STATUS POLIS//

	//KODE COVER PLAN//
if ($ajk_polis['typeRate']=="Tetap") { $kodecover = "12";	}
elseif ($ajk_polis['typeRate']=="Menurun") { $kodecover = "11";	}
else	{ $kodecover = "10";	}
	//KODE COVER PLAN//
	//RATE PESERTA//
	//CEK UMUR UNTUK PERHITUNGAN RATE//
	$findmet="/";
	$fpos = stripos($r['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $r['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$r['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $r['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$cekdob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$riweuh2 = explode("-", $r['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $r['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$r['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $r['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$cekdob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$riweuh2 = explode("/", $r['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	//CEK FORMAT UMUR
	//CEK UMUR UNTUK PERHITUNGAN RATE//
if ($r['id_cost']=="14") {
	$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND tenor="'.$r['kredit_tenor'].'"'));		// RATE PREMI
}elseif ($r['id_cost']=="14"){
	$RTenor = $r['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
	$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}
	//RATE PESERTA//
	//SET CEK GENDER//
if ($r['gender']=="L") {	$metgender = "M";	}
elseif ($r['gender']=="W" OR $r['gender']=="P") {	$metgender = "F";	}
else{	$metgender = $r['gender'];	}
	//SET CEK GENDER//

if ($r['spaj']=="") {	$pol_no_x = $r['id_peserta'];	}else{	$pol_no_x = $r['spaj'];	}

	$worksheet1->write_string($baris, 0, "".$ajk_polis['start_date']."");
	$worksheet1->write_string($baris, 1, "".$ajk_polis['reas_code']."");
	$worksheet1->write_string($baris, 2, "".$ajk_polis['nopol']."");
	$worksheet1->write_string($baris, 3, "".$pol_no_x."");
	$worksheet1->write_string($baris, 4, "".$ajk_costumer['name']."");
	$worksheet1->write_string($baris, 5, "".$r['nama']."");
	$worksheet1->write_string($baris, 6, "".$metgender."");
	$worksheet1->write_string($baris, 7, "".$cekdob."");
	$worksheet1->write_string($baris, 8, "0");
	$worksheet1->write_string($baris, 9, "0");
	$worksheet1->write_string($baris, 10, "1");
	$worksheet1->write_string($baris, 11, "N");
	$worksheet1->write_string($baris, 12, "".$ajk_polis['start_date']."");
	$worksheet1->write_string($baris, 13, "0");
	$worksheet1->write_string($baris, 14, "0");
	$worksheet1->write_string($baris, 15, "".$bpolis[0]."");
	$worksheet1->write_string($baris, 16, "".$bpolis[1]."");
	$worksheet1->write_string($baris, 17, "".$statuspolis."");
	$worksheet1->write_string($baris, 18, "".$ajk_polis['start_date']."");
	$worksheet1->write_string($baris, 19, "".$ajk_polis['start_date']."");
	$worksheet1->write_string($baris, 20, "0");
	$worksheet1->write_string($baris, 21, "".$r['vkredit_tgl']."");
	$worksheet1->write_string($baris, 22, "B");
	$worksheet1->write_string($baris, 23, "11");
	$worksheet1->write_string($baris, 24, "".$kodecover."");
	$worksheet1->write_string($baris, 25, "00");
	$worksheet1->write_string($baris, 26, "".$r['kredit_jumlah']."");
	$worksheet1->write_string($baris, 27, "".$statuspolis."");
	$worksheet1->write_string($baris, 28, "".$r['vkredit_tgl']."");
	$worksheet1->write_string($baris, 29, "".$cekrate['rate']."");
	$baris++;
}

	$workbook->close();
/*
	$xls =& new Spreadsheet_Excel_Writer();
	$xls->send("Report_ReAss_".$_REQUEST['tgl']."-".$_REQUEST['tgl2'].".csv");
	$sheet =& $xls->addWorksheet('Report ReAss');

$formattitle =& $xls->addFormat();	$formattitle->setBold();			$formattitle->setAlign('center');
$formatitems1 =& $xls->addFormat();	$formatitems1->setTextWrap();		$formatitems1->setAlign('left');	$formatitems1->setBorder(1);

if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND vkredit_tgl BETWEEN "'.$_REQUEST['tgl'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
$met = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id!="" '.$satu.' ORDER BY kredit_tgl ASC');

$sheet->write(0,0,'prd_dt',$formattitle);
$sheet->write(0,1,'prd_id',$formattitle);
$sheet->write(0,2,'pol_no',$formattitle);
$sheet->write(0,3,'pol_no_x',$formattitle);
$sheet->write(0,4,'ph_name',$formattitle);
$sheet->write(0,5,'pi_name',$formattitle);
$sheet->write(0,6,'pi_sex',$formattitle);
$sheet->write(0,7,'pi_dob',$formattitle);
$sheet->write(0,8,'occup',$formattitle);
$sheet->write(0,9,'cty_dest',$formattitle);
$sheet->write(0,10,'pa_cat',$formattitle);
$sheet->write(0,11,'med_cat',$formattitle);
$sheet->write(0,12,'eff_dt',$formattitle);
$sheet->write(0,13,'mpp',$formattitle);
$sheet->write(0,14,'mth',$formattitle);
$sheet->write(0,15,'mas',$formattitle);
$sheet->write(0,16,'mas_mt',$formattitle);
$sheet->write(0,17,'pol_st',$formattitle);
$sheet->write(0,18,'pst_dt',$formattitle);
$sheet->write(0,19,'jprd_dt',$formattitle);
$sheet->write(0,20,'end_no',$formattitle);
$sheet->write(0,21,'jeff_dt',$formattitle);
$sheet->write(0,22,'jrc_type',$formattitle);
$sheet->write(0,23,'jrc_id',$formattitle);
$sheet->write(0,24,'jrcp_id',$formattitle);
$sheet->write(0,25,'cur_id',$formattitle);
$sheet->write(0,26,'pol_up',$formattitle);
$sheet->write(0,27,'cov_st',$formattitle);
$sheet->write(0,28,'cst_dt',$formattitle);
$sheet->write(0,29,'rm_rate',$formattitle);
$no = 1;
$y = 0;
while ($r = mysql_fetch_array($met)) {
	$ajk_polis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$r['id_polis'].'"'));
	$ajk_costumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$r['id_cost'].'"'));

	$kredit = explode("/", $fudata['kredit_tgl']);		//TGL KREDIT

	$input_date = explode(" ", $r['input_time']);	$input_date1 = explode("-", $input_date[0]);	$pst_dt =$input_date1[1].'/'.$input_date1[2].'/'.$input_date1[0];	//PST_DT
if ($fudata['status_peserta']=="") {	$cov = "Aktif";	}	else	{	$cov = $r['status_peserta'];	}

	//CEK KODE PRODUK//
if ($r['id_cost']=="14" AND $r['id_polis']=="7") { $prodreas = "GBPND1112";	}
elseif ($r['id_cost']=="14" AND $r['id_polis']=="11") { $prodreas = "GBPND1112";	}
else{	$prodreas ="";	}
	//CEK KODE PRODUK//

	//MASA BERLAKU POLIS//
	$bpolis = explode("-", $ajk_polis['start_date']);
	//MASA BERLAKU POLIS//

	//STATUS POLIS//
if ($r['status_aktif']=="aktif") { $statuspolis="01";	}
elseif ($r['status_aktif']=="Lapse") { $statuspolis="11";	}
elseif ($r['status_aktif']=="Cancel") { $statuspolis="13";	}
elseif ($r['status_aktif']=="Maturity") { $statuspolis="16";	}
else	{	$statuspolis=""; }
	//STATUS POLIS//

	//KODE COVER PLAN//
if ($ajk_polis['typeRate']=="Tetap") { $kodecover = "12";	}
elseif ($ajk_polis['typeRate']=="Menurun") { $kodecover = "11";	}
else	{ $kodecover = "10";	}
	//KODE COVER PLAN//


	//RATE PESERTA//
	//CEK UMUR UNTUK PERHITUNGAN RATE//
	$findmet="/";
	$fpos = stripos($r['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $r['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$r['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $r['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
														$cekdob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$riweuh2 = explode("-", $r['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $r['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$r['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $r['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
														$cekdob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$riweuh2 = explode("/", $r['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	//CEK FORMAT UMUR
	//CEK UMUR UNTUK PERHITUNGAN RATE//
if ($r['id_cost']=="14") {
	$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND tenor="'.$r['kredit_tenor'].'"'));		// RATE PREMI
}elseif ($r['id_cost']=="14"){
	$RTenor = $r['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
	$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}
	//RATE PESERTA//

//SET CEK GENDER//
if ($r['gender']=="L") {	$metgender = "M";	}
elseif ($r['gender']=="W" OR $r['gender']=="P") {	$metgender = "F";	}
else{	$metgender = $r['gender'];	}
//SET CEK GENDER//
	$sheet->write($no,0,"\"".$ajk_polis['start_date']."\"");
	$sheet->write($no,1,"\"".$prodreas."\"");
	$sheet->write($no,2,"\"".$ajk_polis['nopol']."\"");
	$sheet->write($no,3,"\"".$r['id_peserta']."\"");
	$sheet->write($no,4,"\"".$ajk_costumer['name']."\"");
	$sheet->write($no,5,"\"".$r['nama']."\"");
	$sheet->write($no,6,"\"".$metgender."\"");
	$sheet->write($no,7,"\"".$cekdob."\"");
	$sheet->write($no,8,"0");
	$sheet->write($no,9,"0");
	$sheet->write($no,10,"1");
	$sheet->write($no,11,"N");
	$sheet->write($no,12,"\"".$ajk_polis['start_date']."\"");
	$sheet->write($no,13,"0");
	$sheet->write($no,14,"0");
	$sheet->write($no,15,$bpolis[0]);
	$sheet->write($no,16,$bpolis[1]);
	$sheet->write($no,17,$statuspolis);
	$sheet->write($no,18,"\"".$ajk_polis['start_date']."\"");
	$sheet->write($no,19,"\"".$ajk_polis['start_date']."\"");
	$sheet->write($no,20,"0");
	$sheet->write($no,21,"\"".$r['vkredit_tgl']."\"");
	$sheet->write($no,22,"\""."B"."\"");
	$sheet->write($no,23,"11");
	$sheet->write($no,24,$kodecover);
	$sheet->write($no,25,"00");
	$sheet->write($no,26,$r['kredit_jumlah']);
	$sheet->write($no,27,$statuspolis);
	$sheet->write($no,28,"\"".$r['vkredit_tgl']."\"");
	$sheet->write($no,29,"\"".$cekrate['rate']."\"");
	$no++;
	}

	$xls->close();
*/
	;
	break;

case "Rpremi":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('UW-Premi-'.$_REQUEST['tanggal1'].'-'.$_REQUEST['tanggal2'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data Premi');

	$format =& $workbook->add_format();
	$format->set_align('vcenter');
	$format->set_align('center');
	$format->set_color('white');
	$format->set_bold();
	$format->set_pattern();
	$format->set_fg_color('orange');

	$worksheet1->set_row(0, 15);
	$worksheet1->set_column(0, 0, 5);	$worksheet1->write_string(0, 0, "NO", $format);
	$worksheet1->set_column(0, 1, 10);	$worksheet1->write_string(0, 1, "POLIS", $format);
	$worksheet1->set_column(0, 2, 15);	$worksheet1->write_string(0, 2, "CLIENT", $format);
	$worksheet1->set_column(0, 3, 10);	$worksheet1->write_string(0, 3, "ID PESERTA", $format);
	$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "NAMA", $format);
	$worksheet1->set_column(0, 5, 5);	$worksheet1->write_string(0, 5, "TGL KREDIT", $format);
	$worksheet1->set_column(0, 6, 5);	$worksheet1->write_string(0, 6, "TENOR", $format);
	$worksheet1->set_column(0, 7, 5);	$worksheet1->write_string(0, 7, "MA-j", $format);
	$worksheet1->set_column(0, 8, 5);	$worksheet1->write_string(0, 8, "MA-s", $format);
	$worksheet1->set_column(0, 9, 10);	$worksheet1->write_string(0, 9, "U P", $format);
	$worksheet1->set_column(0, 10, 10);	$worksheet1->write_string(0, 10, "PREMI", $format);
	$worksheet1->set_column(0, 11, 10);	$worksheet1->write_string(0, 11, "NO DN", $format);
	$worksheet1->set_column(0, 12, 5);	$worksheet1->write_string(0, 12, "TGL DN", $format);
	$worksheet1->set_column(0, 13, 5);	$worksheet1->write_string(0, 13, "PAID DN", $format);
	//$worksheet1->set_column(0, 14, 10);	$worksheet1->write_string(0, 14, "NO CN", $format);
	//$worksheet1->set_column(0, 15, 5);	$worksheet1->write_string(0, 15, "TGL CN", $format);
	//$worksheet1->set_column(0, 16, 5);	$worksheet1->write_string(0, 16, "PAID CN", $format);
	$worksheet1->set_column(0, 14, 10);	$worksheet1->write_string(0, 14, "STATUS", $format);
	$worksheet1->set_column(0, 15, 10);	$worksheet1->write_string(0, 15, "TYPE", $format);
	$worksheet1->set_column(0, 16, 15);	$worksheet1->write_string(0, 16, "CABANG", $format);
	$worksheet1->set_column(0, 17, 10);	$worksheet1->write_string(0, 17, "REGIONAL", $format);

	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost ="' . $_REQUEST['id_cost'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND vkredit_tgl BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';		}

	$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id!="" '.$satu.' '.$dua.' AND id_dn!="" ORDER BY vkredit_tgl ASC, input_by DESC');
	$baris = 1;
	$erday = date("d/m/Y");
	while ($met = mysql_fetch_array($el)) {
		$Rpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id = "'.$met['id_polis'].'"'));
		$Rclient = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id = "'.$met['id_cost'].'"'));
		$Rdn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode = "'.$met['id_dn'].'"'));
		//$Rcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn = "'.$met['id_klaim'].'"'));

		$awal = explode ("/", $met['kredit_tgl']);		$hari = $awal[0];	$bulan = $awal[1];		$tahun = $awal[2];
		$akhir = explode ("/", $erday);					$hari2 = $akhir[0];	$bulan2 = $akhir[1];	$tahun2 = $akhir[2];
		$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
		$sisahr=floor($jhari);
		$sisabulan =ceil($sisahr / 30.4375);
		$masisa = $met['kredit_tenor'] - $sisabulan;


		$worksheet1->write_number($baris, 0, ++$no, $formatitems);
		$worksheet1->write_string($baris, 1, $Rpolis['nopol'], $formatitems);
		$worksheet1->write_string($baris, 2, $Rclient['name'], $formatitems);
		$worksheet1->write_string($baris, 3, $met['id_peserta'], $formatitems);
		$worksheet1->write_string($baris, 4, $met['nama'], $formatitems);
		$worksheet1->write_string($baris, 5, $met['kredit_tgl'], $formatitems);
		$worksheet1->write_number($baris, 6, $met['kredit_tenor'], $formatitems);
		$worksheet1->write_number($baris, 7, $sisabulan, $formatitems);
		$worksheet1->write_number($baris, 8, $masisa, $formatitems);
		$worksheet1->write_number($baris, 9, $met['kredit_jumlah'], $formatitems);
		$worksheet1->write_number($baris, 10, $met['premi'], $formatitems);
		$worksheet1->write_string($baris, 11, $met['id_dn'], $formatitems);
		$worksheet1->write_string($baris, 12, $Rdn['tgl_createdn'], $formatitems);
		$worksheet1->write_string($baris, 13, $Rdn['tgl_dn_paid'], $formatitems);
		//$worksheet1->write_string($baris, 14, $Rcn['id_cn'], $formatitems);
		//$worksheet1->write_string($baris, 15, $Rcn['tgl_createcn'], $formatitems);
		//$worksheet1->write_string($baris, 16, $Rcn['tgl_byr_claim'], $formatitems);
		$worksheet1->write_string($baris, 14, $met['status_aktif'], $formatitems);
		$worksheet1->write_string($baris, 15, $met['status_peserta'], $formatitems);
		$worksheet1->write_string($baris, 16, $met['cabang'], $formatitems);
		$worksheet1->write_string($baris, 17, $met['regional'], $formatitems);
		$baris++;
	}
	$workbook->close();
	;
	break;

case "Rpeserta":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
HeaderingExcel('Laporan_Peserta.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data Peserta');

	$format =& $workbook->add_format();
	$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');

	$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
	// membuat header tabel dengan format
	$worksheet1->merge_cells(0, 0, 0, 10);	$worksheet1->write_string(0, 0, "PT ADONAI PIALANG ASURANSI", $fjudul);
	$worksheet1->merge_cells(1, 0, 1, 10);	$worksheet1->write_string(1, 0, "REALISASI AJK PERCEPATAN BUKOPIN", $fjudul);


	$worksheet1->set_row(2, 15);
	$worksheet1->set_column(2, 0, 5);	$worksheet1->write_string(2, 0, "NO", $format);
	$worksheet1->set_column(2, 1, 15);	$worksheet1->write_string(2, 1, "NAMA DEBITUR", $format);
	$worksheet1->set_column(2, 2, 10);	$worksheet1->write_string(2, 2, "CABANG", $format);
	$worksheet1->set_column(2, 3, 10);	$worksheet1->write_string(2, 3, "TGL LAHIR", $format);
	$worksheet1->set_column(2, 4, 5);	$worksheet1->write_string(2, 4, "USIA", $format);
	$worksheet1->set_column(2, 5, 10);	$worksheet1->write_string(2, 5, "PLAFOND", $format);
	$worksheet1->set_column(2, 6, 5);	$worksheet1->write_string(2, 6, "JK.W", $format);
	$worksheet1->set_column(2, 7, 15);	$worksheet1->write_string(2, 7, "TGL AKAD", $format);
	$worksheet1->set_column(2, 8, 15);	$worksheet1->write_string(2, 8, "TGL AKHIR", $format);
	$worksheet1->set_column(2, 9, 10);	$worksheet1->write_string(2, 9, "RATE", $format);
	$worksheet1->set_column(2, 10, 15);	$worksheet1->write_string(2, 10, "TOTAL PREMI", $format);

	$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['company'].'"'));

	// membuat header file excel dan nama filenya


$tgl1 = explode("/", $_REQUEST['tanggal3']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal4']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])			{	$satu = 'AND fu_ajk_dn.id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])		{	$dua = 'AND fu_ajk_dn.id_nopol = "' . $_REQUEST['subcat'] . '"';	}
if ($_REQUEST['subcatreg'])		{	$tiga = 'AND fu_ajk_dn.id_regional = "' . $_REQUEST['subcatreg'] . '"';	}
if ($_REQUEST['subcatarea'])	{	$empat = 'AND fu_ajk_dn.id_area = "' . $_REQUEST['subcatarea'] . '"';	}
if ($_REQUEST['subcatcab'])		{	$lima = 'AND fu_ajk_dn.id_cabang = "' . $_REQUEST['subcatcab'] . '"';	}
if ($_REQUEST['tanggal1'])		{	$enam = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';	}
if ($_REQUEST['tanggal3'])		{	$sepuluh = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tanggal3'].'" AND "'.$_REQUEST['tanggal4'].'" ';	}

/*
if ($_REQUEST['Rpembayaran']=="")			{	$tujuh = 'AND status_bayar != ""';	}
	elseif ($_REQUEST['Rpembayaran']=="0" )	{	$tujuh = 'AND status_bayar = "0"';	}
	else {	$tujuh = 'AND status_bayar = "'.$_REQUEST['Rpembayaran'].'"';	}
*/


$el = mysql_query('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_area,
fu_ajk_dn.id_cabang,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.del,
fu_ajk_peserta.spaj,
fu_ajk_peserta.nama,
fu_ajk_peserta.id_klaim,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_klaim,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.status_aktif
FROM
fu_ajk_dn
INNER JOIN fu_ajk_peserta ON fu_ajk_dn.dn_kode = fu_ajk_peserta.id_dn AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL');
$baris = 3;
while ($mamet = mysql_fetch_array($el))
{
	$metcostumer = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
	$metpolis = mysql_fetch_array(mysql_query('SELECT id, nopol, singlerate FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	if ($metpolis['singlerate']=="T") {
		$metrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND tenor="'.$mamet['kredit_tenor'].'"'));
	}else{
		$tenorthn = $mamet['kredit_tenor'] / 12;
		$metrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$tenorthn.'"'));
	}

	//if ($mamet['status_bayar']==0) {	$statusnya = 'Unpaid';	}else{	$statusnya = 'Paid';	}
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['nama']);
	$worksheet1->write_string($baris, 2, $mamet['id_cabang']);
	$worksheet1->write_string($baris, 3, $mamet['tgl_lahir']);
	$worksheet1->write_string($baris, 4, $mamet['usia']);
	$worksheet1->write_string($baris, 5, $mamet['kredit_jumlah']);
	$worksheet1->write_string($baris, 6, $mamet['kredit_tenor']);
	$worksheet1->write_string($baris, 7, $mamet['kredit_tgl']);
	$worksheet1->write_string($baris, 8, $mamet['kredit_akhir']);
	$worksheet1->write_string($baris, 9, $metrate['rate']);
	$worksheet1->write_string($baris, 10, $mamet['totalpremi']);
	$baris++;
	$jPlafond +=$mamet['kredit_jumlah'];
	$jTPremi +=$mamet['totalpremi'];
}
	$worksheet1->write_string($baris, 5, $jPlafond, $fjudul);
	$worksheet1->write_string($baris, 10, $jTPremi, $fjudul);
$workbook->close();


/*
$tgl1 = explode("/", $_REQUEST['tanggal3']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal4']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])					{	$satu = 'AND fu_ajk_dn.id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])				{	$dua = 'AND fu_ajk_dn.id_nopol = "' . $_REQUEST['subcat'] . '"';	}
if ($_REQUEST['subcatreg'])				{	$tiga = 'AND fu_ajk_dn.id_regional = "' . $_REQUEST['subcatreg'] . '"';	}
if ($_REQUEST['subcatarea'])			{	$empat = 'AND fu_ajk_dn.id_area = "' . $_REQUEST['subcatarea'] . '"';	}
if ($_REQUEST['subcatcab'])				{	$lima = 'AND fu_ajk_dn.id_cabang = "' . $_REQUEST['subcatcab'] . '"';	}
if ($_REQUEST['tanggal3'])				{	$sepuluh = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}

if ($_REQUEST['Rpembayaran']=="")			{	$tujuh = 'AND fu_ajk_peserta.status_bayar != ""';	}
	elseif ($_REQUEST['Rpembayaran']=="0" )	{	$tujuh = 'AND fu_ajk_peserta.status_bayar = "0"';	}
	else {	$tiga = 'AND status_bayar = "'.$_REQUEST['Rpembayaran'].'"';	}

function echocsv( $fields )
{
	$separator = '';
	foreach ( $fields as $field )
	{
		if ( preg_match( '/\\r|\\n|;|"/', $field ) )
		{
			$field = str_replace( '"', '""', $field );
		}
		echo $separator . $field;
		$separator = '|';
	}
	echo "\r\n";
}
$query = sprintf('
SELECT
fu_ajk_dn.id,
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.dn_kode,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.spaj,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_dn.dn_status,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.premi,
fu_ajk_peserta.disc_premi,
fu_ajk_peserta.biaya_adm,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.status_bayar,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_area,
fu_ajk_dn.id_cabang,
fu_ajk_dn.del
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_peserta ON fu_ajk_dn.dn_kode = fu_ajk_peserta.id_dn AND fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.''.$tiga.' '.$empat.' '.$lima.' '.$tujuh.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL');
	$result = mysql_query( $query, $conn ) or die( mysql_error( $conn ) );

	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment;filename=data-peserta.csv' );

	$row = mysql_fetch_assoc( $result );
if ( $row )
{
	echocsv( array_keys( $row ) );
}

while ( $row )
{
	echocsv( $row );
	$row = mysql_fetch_assoc( $result );
}
exit;
*/
		;
		break;

case "Rdn":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('UW-Peserta(DN)-'.$_REQUEST['tanggal1'].'-'.$_REQUEST['tanggal2'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data DN');

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
	$worksheet1->set_column(0, 1, 15);	$worksheet1->write_string(0, 1, "POLIS", $format);
	$worksheet1->set_column(0, 2, 20);	$worksheet1->write_string(0, 2, "DEBIT NOTE", $format);
	$worksheet1->set_column(0, 3, 15);	$worksheet1->write_string(0, 3, "TOTAL PREMI", $format);
	$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "TGL DN", $format);
	$worksheet1->set_column(0, 5, 20);	$worksheet1->write_string(0, 5, "REG. PRM", $format);
	$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "PAID DATE", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "STATUS", $format);
	$worksheet1->set_column(0, 8, 30);	$worksheet1->write_string(0, 8, "BRANCH", $format);
	$worksheet1->set_column(0, 9, 20);	$worksheet1->write_string(0, 9, "AREA", $format);
	$worksheet1->set_column(0, 10, 20);	$worksheet1->write_string(0, 10, "REGIONAL", $format);

	$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['company'].'"'));
	// membuat header file excel dan nama filenya

	$tgl1 = explode("-", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("-", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost ="' . $_REQUEST['id_cost'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND tgl_createdn BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';		}
if ($_REQUEST['Rpembayaran'])	{	$tiga = 'AND dn_status = "'.$_REQUEST['Rpembayaran'].'"';	}

	$el = mysql_query('SELECT * FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL ORDER BY id_nopol DESC, dn_status ASC, tgl_createdn ASC, dn_kode ASC, input_time DESC, id_cabang ASC');
	$baris = 1;
while ($mamet = mysql_fetch_array($el))
{
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metARM = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_prm WHERE id="'.$mamet['id_prm'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

	$worksheet1->write_number($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $metpolis['nopol']);
	$worksheet1->write_string($baris, 2, $mamet['dn_kode']);
	$worksheet1->write_number($baris, 3, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 4, $mamet['tgl_createdn']);
	$worksheet1->write_string($baris, 5, $metARM['id_prm']);
	$worksheet1->write_string($baris, 6, $mamet['tgl_dn_paid']);
	$worksheet1->write_string($baris, 7, $mamet['dn_status']);
	$worksheet1->write_string($baris, 8, $mamet['id_cabang']);
	$worksheet1->write_string($baris, 9, $mamet['id_area']);
	$worksheet1->write_string($baris, 10, $mamet['id_regional']);
	$baris++;
}
	$workbook->close();
	;
	break;

case "Rcn":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('UW-Peserta(CN)-'.$_REQUEST['tanggal1'].'-'.$_REQUEST['tanggal2'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data CN');

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
	$worksheet1->set_column(0, 1, 15);	$worksheet1->write_string(0, 1, "POLIS", $format);
	$worksheet1->set_column(0, 2, 20);	$worksheet1->write_string(0, 2, "NAMA", $format);
	$worksheet1->set_column(0, 3, 20);	$worksheet1->write_string(0, 3, "ID PESERTA", $format);
	$worksheet1->set_column(0, 4, 15);	$worksheet1->write_string(0, 4, "CREDIT NOTE", $format);
	$worksheet1->set_column(0, 5, 20);	$worksheet1->write_string(0, 5, "DEBIT NOTE", $format);
	$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "JUMLAH", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "DATE CN", $format);
	$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "PAID DATE", $format);
	$worksheet1->set_column(0, 9, 10);	$worksheet1->write_string(0, 9, "TGL MULAI", $format);
	$worksheet1->set_column(0, 10, 10);	$worksheet1->write_string(0, 10, "TGL BERAKHIR", $format);
	$worksheet1->set_column(0, 11, 10);	$worksheet1->write_string(0, 11, "TGL LUNAS", $format);
	$worksheet1->set_column(0, 12, 10);	$worksheet1->write_string(0, 12, "STATUS", $format);
	$worksheet1->set_column(0, 13, 10);	$worksheet1->write_string(0, 13, "TYPE", $format);
	$worksheet1->set_column(0, 14, 20);	$worksheet1->write_string(0, 14, "CABANG", $format);
	$worksheet1->set_column(0, 15, 20);	$worksheet1->write_string(0, 15, "REGIONAL", $format);

	$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['company'].'"'));
	// membuat header file excel dan nama filenya

	$tgl1 = explode("-", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("-", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_cn.id_cost ="' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';		}
if ($_REQUEST['subcat'])		{
	$cekregionalnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND fu_ajk_cn.id_regional ="' . $cekregionalnya['name'] . '"';
}
if ($_REQUEST['tipe'])		{	$empat = 'AND fu_ajk_cn.type_claim ="'.$_REQUEST['tipe'].'"';	}

$el = mysql_query('SELECT
fu_ajk_polis.nopol,
fu_ajk_cn.type_claim,
fu_ajk_peserta.nama,
fu_ajk_peserta.id_dn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_akhir,
fu_ajk_cn.id_cn,
fu_ajk_cn.total_claim,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang
FROM
fu_ajk_cn
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND fu_ajk_cn.del IS NULL ORDER BY fu_ajk_cn.tgl_createcn ASC, fu_ajk_cn.id_cn ASC, fu_ajk_cn.input_date DESC, fu_ajk_cn.id_cabang ASC, fu_ajk_cn.id DESC');
	$baris = 1;
while ($mamet = mysql_fetch_array($el))
{
if ($mamet['id_cabang']=="") 		{	$Rcabang = $mamet['id_cabang_old'];	} 		else {	$Rcabang = $mamet['id_cabang'];	}
if ($mamet['id_area']=="") 			{	$Rarea = $mamet['id_area_old'];	} 			else {	$Rarea = $mamet['id_area'];	}
if ($mamet['id_regional']=="") 		{	$Rregional = $mamet['id_regional_old'];	} 	else {	$Rregional = $mamet['id_regional'];	}

	if ($mamet['total_claim'] < 0) {	$nilaicnnya = 0;	}else{	$nilaicnnya=$mamet['total_claim'];	}	//BUAT NILAI 0 BILA NILAINYA MINUS
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['nopol']);
	$worksheet1->write_string($baris, 2, $mamet['nama']);
	$worksheet1->write_string($baris, 3, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 4, $mamet['id_cn']);
	$worksheet1->write_string($baris, 5, $mamet['id_dn']);
	$worksheet1->write_number($baris, 6, $nilaicnnya);
	$worksheet1->write_string($baris, 7, $mamet['tgl_createcn']);
	$worksheet1->write_string($baris, 8, $mamet['tgl_byr_claim']);
	$worksheet1->write_string($baris, 9, $mamet['kredit_tgl']);
	$worksheet1->write_string($baris, 10, $mamet['kredit_akhir']);
	$worksheet1->write_string($baris, 11, $mamet['tgl_claim']);
	$worksheet1->write_string($baris, 12, $mamet['confirm_claim']);
	$worksheet1->write_string($baris, 13, $mamet['type_claim']);
	$worksheet1->write_string($baris, 14, $Rcabang);
	$worksheet1->write_string($baris, 15, $Rregional);
	$baris++;
}
	$workbook->close();
	;
	break;

//REPORT MODUL ARM

case "metpdf":
$tgl1 = explode("/", $_REQUEST['dnpay']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];	if ($_REQUEST['dnpay'])
$tgl2 = explode("/", $_REQUEST['dnpay1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];	if ($_REQUEST['dnpay1'])
if ($_REQUEST['dnpay']!='' AND $_REQUEST['dnpay1']!='')		{	$satu= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN "'.$tglawal.'" AND "'.$tglawal1.'"';	}
if ($_REQUEST['cost'])									{	$dua= 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['cost'].'"';	}
if ($_REQUEST['reg'])									{	$tiga= 'AND fu_ajk_dn.id_regional = "'.$_REQUEST['reg'].'"';	}

	$pdf=new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('image/logo_recapitalife.png',10,20);
	$pdf->SetFont('Arial','B',16);	$pdf->Text(90, 35,'UPDATE ARM');
	//$pdf->SetFont('Arial','',9);	$pdf->Text(90, 40,'No : ');

$cost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cost'].'"'));
	$pdf->SetFont('Arial','B',10);	$pdf->Text(10,50, $cost['name']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10,55, 'Date Payment');	$pdf->Text(40,55, ': '. _convertDate($tglawal) .' s/d '. _convertDate($tglawal1));

/*
$m = mysql_query('SELECT SUM(fu_ajk_dn.totalpremi) AS tpremi FROM fu_ajk_dn WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' AND fu_ajk_dn.del IS NULL');
while ($met = mysql_fetch_array($m)) {	$mets = $met['tpremi'];	}
	$pdf->SetFont('Arial','',10);	$pdf->Text(10,60, 'Used Payment');	$pdf->Text(40,60, ': Rp. '. duit($mets));
*/

	$y_initial = 70;
	$y_axis1 = 64;

	$pdf->setFont('Arial','',7);

	$pdf->setFillColor(233,233,233);
	$pdf->setY($y_axis1);
	$pdf->setX(10);

	$pdf->cell(10,6,'N0',1,0,'C',1);
	$pdf->cell(50,6,'TANGGAL PEMBAYARAN',1,0,'C',1);
	$pdf->cell(15,6,'JUMLAH DN',1,0,'C',1);
	$pdf->cell(25,6,'PREMI',1,0,'C',1);
	$pdf->cell(15,6,'JUMLAH CN',1,0,'C',1);
	$pdf->cell(25,6,'NILAI',1,0,'C',1);
	$pdf->cell(25,6,'NETT PREMI',1,0,'C',1);

	$rahmad = mysql_query('SELECT
fu_ajk_dn.id,
fu_ajk_dn.id_cost AS costid,
fu_ajk_dn.id_regional AS regnya,
fu_ajk_dn.tgl_dn_paid AS tglbayar,
COUNT(DISTINCT fu_ajk_dn.dn_kode) AS jDN,
SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_dn.totalpremi)) AS jPremi,
COUNT(DISTINCT fu_ajk_cn.id_cn) AS jCN,
SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS jNilai,
SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_dn.totalpremi)) - SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS jTotal
FROM fu_ajk_dn LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost, fu_ajk_dn.tgl_dn_paid
ORDER BY fu_ajk_dn.tgl_dn_paid ASC');
	$y = $y_initial + $row;
	$no = 0;
	$row = 6;
while ($r = mysql_fetch_array($rahmad)) {

	$cell[$i][0] = _convertDate($r['tglbayar']);
	$cell[$i][1] = duit($r['jDN']);
	$cell[$i][2] = duit($r['jPremi']);
	$cell[$i][3] = duit($r['jCN']);
	$cell[$i][4] = duit($r['jNilai']);
	$cell[$i][5] = duit($r['jTotal']);
	$i++;
	$tjdn  += $r['jDN'];
	$tjpremi += $r['jPremi'];
	$tjcn += $r['jCN'];
	$tjnilai += $r['jNilai'];
	$tjnett += $r['jTotal'];
}
	$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{
		$pdf->setX(10);
		//menampilkan data dari hasil query database
		$pdf->cell(10,6,$j+1,1,0,'C');
		$pdf->cell(50,6,$cell[$j][0],1,0,'L');
		$pdf->cell(15,6,$cell[$j][1],1,0,'C');
		$pdf->cell(25,6,$cell[$j][2],1,0,'R');
		$pdf->cell(15,6,$cell[$j][3],1,0,'C');
		$pdf->cell(25,6,$cell[$j][4],1,0,'R');
		$pdf->cell(25,6,$cell[$j][5],1,0,'R');
		$pdf->Ln();
	}
	$pdf->setX(10);
	$pdf->setFont('Arial','B',7);
	$pdf->cell(60,6,'Total',1,0,'C');
	$pdf->cell(15,6,duit($tjdn),1,0,'C');
	$pdf->cell(25,6,duit($tjpremi),1,0,'R');
	$pdf->cell(15,6,duit($tjcn),1,0,'C');
	$pdf->cell(25,6,duit($tjnilai),1,0,'R');
	$pdf->cell(25,6,duit($tjnett),1,0,'R');
	$pdf->Ln();
	$pdf->Ln();

	$pdf->setFont('Arial','',11);
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'Jakarta, '.$futgl.'', 0, 0, 'L');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	//$pdf->Image('image/ttd_Ibnu.jpg',155);
	//	$pdf->Ln();
	$pdf->setFont('Arial','B','U',11);
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'Samino', 0, 0, 'L');
	$pdf->Ln();
	$pdf->cell(140,6,' ', 0, 0, 'R');
	$pdf->cell(30,6,'FAI & GA Div. Head', 0, 0, 'L');
	$pdf->Output();
	$pdf->Output('../ajk_file/cn/'.$name,"F");
		;
		break;
//REPORT MODUL ARM

case "metexcel":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
HeaderingExcel('DN_Payment-'.$_REQUEST['dnpay'].'-'.$_REQUEST['dnpay1'].'.xls');
	// SHEET DEBIT NOTE
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('DATA DEBIT NOTE');

	$format =& $workbook->add_format();
	$format->set_align('vcenter');
	$format->set_align('center');
	$format->set_color('white');
	$format->set_bold();
	$format->set_pattern();
	$format->set_fg_color('orange');

	$tgl1 = explode("/", $_REQUEST['dnpay']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];	if ($_REQUEST['dnpay'])
	$tgl2 = explode("/", $_REQUEST['dnpay1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];	if ($_REQUEST['dnpay1'])
	if ($_REQUEST['cost'])										{	$satu= 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['cost'].'"';	}
	if ($_REQUEST['reg'])										{	$tiga= 'AND fu_ajk_dn.id_regional = "'.$_REQUEST['reg'].'"';	}
	if ($_REQUEST['dnpay']!='' AND $_REQUEST['dnpay1']!='')		{	$dua= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN "'.$tglawal.'" AND "'.$tglawal1.'"';	}

	$worksheet1->set_row(0, 15);
	$worksheet1->set_column(0, 0, 5);	$worksheet1->write_string(0, 0, "NO", $format);
	$worksheet1->set_column(0, 1, 15);	$worksheet1->write_string(0, 1, "Tanggal Pembayaran", $format);
	$worksheet1->set_column(0, 2, 20);	$worksheet1->write_string(0, 2, "Jumlah DN", $format);
	$worksheet1->set_column(0, 3, 15);	$worksheet1->write_string(0, 3, "PREMI", $format);

$arm = mysql_query('SELECT
fu_ajk_dn.id_cost AS costid,
fu_ajk_dn.tgl_dn_paid AS tglbayar,
COUNT(fu_ajk_dn.dn_kode) AS jDN,
SUM(fu_ajk_dn.totalpremi) AS jPremi
FROM fu_ajk_dn
WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost, fu_ajk_dn.tgl_dn_paid
ORDER BY fu_ajk_dn.tgl_dn_paid ASC');
	$baris = 1;
	while ($met = mysql_fetch_array($arm)) {
		$worksheet1->write_string($baris, 0, ++$no);
		$worksheet1->write_string($baris, 1, $met['tglbayar']);
		$worksheet1->write_string($baris, 2, $met['jDN']);
		$worksheet1->write_string($baris, 3, duit($met['jPremi']));
		$baris++;
	}

	// SHEET CREDIT NOTE
	$worksheet2 =& $workbook->add_worksheet('DATA CREDIT NOTE');
	$worksheet2->set_row(0, 15);
	$worksheet2->set_column(0, 0, 5);	$worksheet2->write_string(0, 0, "NO", $format);
	$worksheet2->set_column(0, 1, 15);	$worksheet2->write_string(0, 1, "Tanggal Pembayaran", $format);
	$worksheet2->set_column(0, 2, 20);	$worksheet2->write_string(0, 2, "Jumlah CN", $format);
	$worksheet2->set_column(0, 3, 15);	$worksheet2->write_string(0, 3, "Nilai", $format);
$arm_cn = mysql_query('SELECT fu_ajk_dn.id_cost AS costid,
								 fu_ajk_dn.tgl_dn_paid AS tglbayar,
								 COUNT(fu_ajk_cn.id_cn) AS jCN,
								 SUM( IF( fu_ajk_cn.total_claim <0, 0, fu_ajk_cn.total_claim ) ) AS jNilai
								 FROM fu_ajk_cn
								 LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_dn = fu_ajk_dn.dn_kode
								 WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_cn.type_claim != "Death"
								 GROUP BY fu_ajk_dn.tgl_dn_paid
								 ORDER BY fu_ajk_dn.tgl_dn_paid ASC');
$baris2 = 1;
while ($met_cn = mysql_fetch_array($arm_cn)) {
	$worksheet2->write_string($baris2, 0, ++$no1);
	$worksheet2->write_string($baris2, 1, $met_cn['tglbayar']);
	$worksheet2->write_string($baris2, 2, $met_cn['jCN']);
	$worksheet2->write_string($baris2, 3, duit($met_cn['jNilai']));
	$baris2++;
}

// SHEET LIST DEBIT NOTE
	$worksheet3 =& $workbook->add_worksheet('LIST DEBIT NOTE');
	$worksheet3->set_row(0, 15);
	$worksheet3->set_column(0, 0, 5);	$worksheet3->write_string(0, 0, "NO", $format);
	$worksheet3->set_column(0, 1, 15);	$worksheet3->write_string(0, 1, "POLIS", $format);
	$worksheet3->set_column(0, 2, 20);	$worksheet3->write_string(0, 2, "TGL Paid", $format);
	$worksheet3->set_column(0, 3, 15);	$worksheet3->write_string(0, 3, "DEBIT NOTE", $format);
	$worksheet3->set_column(0, 4, 15);	$worksheet3->write_string(0, 4, "PREMI", $format);
	$worksheet3->set_column(0, 5, 15);	$worksheet3->write_string(0, 5, "REGIONAL", $format);
	$worksheet3->set_column(0, 6, 15);	$worksheet3->write_string(0, 6, "AREA", $format);
	$worksheet3->set_column(0, 7, 15);	$worksheet3->write_string(0, 7, "CABANG", $format);

$arm_list_dn = mysql_query('SELECT
fu_ajk_dn.id_nopol,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_area,
fu_ajk_dn.id_cabang
FROM
fu_ajk_dn
WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
ORDER BY fu_ajk_dn.tgl_dn_paid ASC, fu_ajk_dn.dn_kode ASC');
$baris3 = 1;
while ($met_list_dn = mysql_fetch_array($arm_list_dn)) {
$polis_dn = mysql_fetch_array(mysql_query('SELECT id,nopol FROM fu_ajk_polis WHERE id="'.$met_list_dn['id_nopol'].'"'));
	$worksheet3->write_string($baris3, 0, ++$no2);
	$worksheet3->write_string($baris3, 1, $polis_dn['nopol']);
	$worksheet3->write_string($baris3, 2, $met_list_dn['tgl_dn_paid']);
	$worksheet3->write_string($baris3, 3, $met_list_dn['dn_kode']);
	$worksheet3->write_string($baris3, 4, duit($met_list_dn['totalpremi']));
	$worksheet3->write_string($baris3, 5, $met_list_dn['id_regional']);
	$worksheet3->write_string($baris3, 6, $met_list_dn['id_area']);
	$worksheet3->write_string($baris3, 7, $met_list_dn['id_cabang']);
	$baris3++;
}

// SHEET LIST CREDIT NOTE
	$worksheet4 =& $workbook->add_worksheet('LIST CREDIT NOTE');
	$worksheet4->set_row(0, 15);
	$worksheet4->set_column(0, 0, 5);	$worksheet4->write_string(0, 0, "NO", $format);
	$worksheet4->set_column(0, 1, 15);	$worksheet4->write_string(0, 1, "POLIS", $format);
	$worksheet4->set_column(0, 2, 20);	$worksheet4->write_string(0, 2, "TGL Paid", $format);
	$worksheet4->set_column(0, 3, 15);	$worksheet4->write_string(0, 3, "DEBIT NOTE", $format);
	$worksheet4->set_column(0, 4, 15);	$worksheet4->write_string(0, 4, "CREDIT NOTE", $format);
	$worksheet4->set_column(0, 5, 15);	$worksheet4->write_string(0, 5, "NILAI", $format);
	$worksheet4->set_column(0, 6, 15);	$worksheet4->write_string(0, 6, "REGIONAL", $format);
	$worksheet4->set_column(0, 7, 15);	$worksheet4->write_string(0, 7, "CABANG", $format);

$arm_list_cn = mysql_query('SELECT
fu_ajk_cn.id_cost,
fu_ajk_cn.id_nopol,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_cn.id_dn,
fu_ajk_cn.id_cn,
fu_ajk_cn.total_claim,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang
FROM
fu_ajk_cn
LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_dn = fu_ajk_dn.dn_kode
WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_cn.type_claim != "Death"
ORDER BY fu_ajk_dn.tgl_dn_paid ASC, fu_ajk_cn.id_cn ASC');
$baris4 = 1;
while ($met_list_cn = mysql_fetch_array($arm_list_cn)) {
$polis_cn = mysql_fetch_array(mysql_query('SELECT id,nopol FROM fu_ajk_polis WHERE id="'.$met_list_cn['id_nopol'].'"'));
if ($met_list_cn['total_claim'] < 0) { $nilaicn = 0;	}else{	$nilaicn = $met_list_cn['total_claim'];	}
	$worksheet4->write_string($baris4, 0, ++$no3);
	$worksheet4->write_string($baris4, 1, $polis_cn['nopol']);
	$worksheet4->write_string($baris4, 2, $met_list_cn['tgl_dn_paid']);
	$worksheet4->write_string($baris4, 3, $met_list_cn['id_dn']);
	$worksheet4->write_string($baris4, 4, $met_list_cn['id_cn']);
	$worksheet4->write_string($baris4, 5, duit($nilaicn));
	$worksheet4->write_string($baris4, 6, $met_list_cn['id_regional']);
	$worksheet4->write_string($baris4, 7, $met_list_cn['id_cabang']);
	$baris4++;
}
	$workbook->close();
		;
		break;

case "printbatch":
$metbatch = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_batch WHERE idb="'.$_REQUEST['idb'].'"'));
$tglbatch = explode(" ", $metbatch['input_time']);
echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">
	  <tr><td align="center" colspan="2" class="titleprm">DATA VALIDASI<br /><br /></td></tr>
	  <tr class="smalltotal"><td width="20%">No. Batch</td><td>: '.$metbatch['no_batch'].'</td></tr>
	  <tr class="smalltotal"><td>Tanggal</td><td>: '._convertDate($tglbatch[0]).'</td></tr>
	  <tr class="smalltotal"><td align="center" colspan="3">
	  	<table border="0" width="100%" cellpadding="1" cellspacing="1" bordercolorlight="#FF00FF" bordercolordark="#DED" style="border: 1px solid #DED">
	  	<tr bgcolor="#DEDEDE" class="smalltotal"><td align="center" width="1%" rowspan="2">No</td>
	  		<td align="center" colspan="3">DEBIT NOTE</td>
	  		<td align="center" colspan="3">CREBIT NOTE</td>
	  		<td align="center" width="5%" rowspan="2">Nett Premi</td>
	  		<td align="center" width="8%" rowspan="2">Regional</td>
	  		<td align="center" width="8%" rowspan="2">Cabang</td>
		</tr>
		<tr bgcolor="#DEDEDE" class="smalltotal"><td align="center" width="10%">Nomor</td>
			<td align="center" width="1%">Peserta</td>
			<td align="center" width="7%">Premi</td>
			<td align="center" width="10%">Nomor</td>
			<td align="center" width="1%">Peserta</td>
			<td align="center" width="7%">Premi</td>
		</tr>';
$metval = mysql_query('SELECT * FROM fu_ajk_dn WHERE validasi_batch="'.$metbatch['idb'].'" ORDER BY input_time DESC');
while ($metvaldn = mysql_fetch_array($metval)) {
	//JUMLAH PESERTA DN
	$metpeserta = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metvaldn['dn_kode'].'" AND del IS NULL');
	$fupeserta = mysql_num_rows($metpeserta);

	//JUMLAH PESERTA CN
	$valdncn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metvaldn['dn_kode'].'"'));
	if ($valdncn['id_dn']==$metvaldn['dn_kode']) {
		$metValcn = $valdncn['id_cn'];
		$metValcnclaim = duit($valdncn['total_claim']);
		$metValcnpeserta = '<b>1</b>';
	}else{
		$metValcn = '-';
		$metValcnclaim = '-';
		$metValcnpeserta = '-';
	}
	$valnetnya = $metvaldn['totalpremi'] - $valdncn['total_claim'];
echo '<tr class="small"><td bordercolor="#DED" style="border: 1px solid #DED" align="center">'.++$no.'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center">'.$metvaldn['dn_kode'].'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center">'.$fupeserta.'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.duit($metvaldn['totalpremi']).' &nbsp;</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center">'.$metValcn.'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center">'.$metValcnpeserta.'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.$metValcnclaim.' &nbsp;</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.duit($valnetnya).' &nbsp;</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center"> &nbsp; '.$metvaldn['id_regional'].'</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="center"> &nbsp; '.$metvaldn['id_cabang'].'</td>
	  </tr>';
	$totaldn += $metvaldn['totalpremi'];
	$totalcn += $valdncn['total_claim'];
	$totalnet += $valnetnya;
	}
echo '<tr class="smalltotal"><td bordercolor="#DED" style="border: 1px solid #DED" colspan="3">TOTAL</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.duit($totaldn).' &nbsp; </td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" colspan="2">&nbsp;</td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.duit($totalcn).' &nbsp; </td>
		  <td bordercolor="#DED" style="border: 1px solid #DED" align="right">'.duit($totalnet).' &nbsp; </td>
	  </tr></table>';
$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_lengkap="'.$_REQUEST['userna'].'"'));
echo '<tr><td colspan="3"><br />
		<table border="0" width="100%">
		<tr class="small"><td width="70%" colspan="2">&nbsp;</td><td>Jakarta, '.$futgl.'</td></tr>
	  	<tr class="small"><td width="30%">&nbsp;</td>
	  		<td><b>Divisi Underwriting</b><br /><br /><br /><br /><br /><br />'.$q['nm_lengkap'].'</td>
			<td><b>Divisi ARM</b><br /><br /><br /><br /><br /><br />Samino</td>
		</tr>
	  	</table>
	  </td></tr>
	  </table>';

if (!$id){
	echo "<script language=javascript>
		function printWindow() {
		bV = parseInt(navigator.appVersion);
		if (bV >= 4) window.print();}
		printWindow();
		</script>";
}
	//echo '<meta http-equiv="refresh" content="10;URL=ajk_dn.php?r=valbatch">';
	;
	break;

case "refundexcel":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('Refund -'.$_REQUEST['cn'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data CN');

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
	$worksheet1->set_column(0, 1, 15);	$worksheet1->write_string(0, 1, "POLIS", $format);
	$worksheet1->set_column(0, 2, 10);	$worksheet1->write_string(0, 2, "IDPESERTA", $format);
	$worksheet1->set_column(0, 3, 20);	$worksheet1->write_string(0, 3, "NAMA", $format);
	$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "USIA", $format);
	$worksheet1->set_column(0, 5, 15);	$worksheet1->write_string(0, 5, "CREDIT NOTE", $format);
	$worksheet1->set_column(0, 6, 20);	$worksheet1->write_string(0, 6, "DEBIT NOTE", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "PREMI", $format);
	$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "Ext. PREMI", $format);
	$worksheet1->set_column(0, 9, 10);	$worksheet1->write_string(0, 9, "TOTAL PREMI", $format);
	$worksheet1->set_column(0, 10, 10);	$worksheet1->write_string(0, 10, "NILAI REFUND", $format);
	$worksheet1->set_column(0, 11, 10);	$worksheet1->write_string(0, 11, "DATE CN", $format);
	$worksheet1->set_column(0, 12, 10);	$worksheet1->write_string(0, 12, "STATUS", $format);
	$worksheet1->set_column(0, 13, 10);	$worksheet1->write_string(0, 13, "TYPE", $format);
	$worksheet1->set_column(0, 14, 20);	$worksheet1->write_string(0, 14, "CABANG", $format);
	$worksheet1->set_column(0, 15, 20);	$worksheet1->write_string(0, 15, "REGIONAL", $format);

	$metregional = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$metcliennya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['company'].'"'));
	// membuat header file excel dan nama filenya

if ($_REQUEST['cn'])		{	$satu = 'AND id_cn ="' . $_REQUEST['cn'] . '"';		}

$el = mysql_query('SELECT * FROM fu_ajk_cn WHERE id != "" '.$satu.' AND del IS NULL ORDER BY tgl_createcn ASC, id_cn ASC, input_date DESC, id_cabang ASC, id DESC');
$baris = 1;
while ($mamet = mysql_fetch_array($el))
{
if ($mamet['id_cabang']=="") 		{	$Rcabang = $mamet['id_cabang_old'];	} 		else {	$Rcabang = $mamet['id_cabang'];	}
if ($mamet['id_area']=="") 			{	$Rarea = $mamet['id_area_old'];	} 			else {	$Rarea = $mamet['id_area'];	}
if ($mamet['id_regional']=="") 		{	$Rregional = $mamet['id_regional_old'];	} 	else {	$Rregional = $mamet['id_regional'];	}

	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metARM = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_prm WHERE id="'.$mamet['id_prm'].'"'));
	$metcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
	$metpeserta = mysql_fetch_array(mysql_query('SELECT id_klaim, id_dn, id_peserta, nama, usia, premi, ext_premi, totalpremi FROM fu_ajk_peserta WHERE id_klaim="'.$mamet['id_cn'].'" AND id_peserta="'.$mamet['id_peserta'].'" AND id_dn !=""'));

	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $metpolis['nopol']);
	$worksheet1->write_string($baris, 2, $metpeserta['id_peserta']);
	$worksheet1->write_string($baris, 3, $metpeserta['nama']);
	$worksheet1->write_string($baris, 4, $metpeserta['usia']);
	$worksheet1->write_string($baris, 5, $mamet['id_cn']);
	$worksheet1->write_string($baris, 6, $mamet['id_dn']);
	$worksheet1->write_number($baris, 7, $mamet['premi']);
	$worksheet1->write_number($baris, 8, $metpeserta['ext_premi']);
	$worksheet1->write_number($baris, 9, $metpeserta['totalpremi']);
	$worksheet1->write_number($baris, 10, $mamet['total_claim']);
	$worksheet1->write_string($baris, 11, $mamet['tgl_createcn']);
	$worksheet1->write_string($baris, 12, $mamet['confirm_claim']);
	$worksheet1->write_string($baris, 13, $mamet['type_claim']);
	$worksheet1->write_string($baris, 14, $Rcabang);
	$worksheet1->write_string($baris, 15, $Rregional);
	$baris++;
}
	$workbook->close();
	;
	break;

case "refundpdftoxls":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	HeaderingExcel('Refund-'.$_REQUEST['cn'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data CN');
$format =& $workbook->add_format();
$format->set_align('vcenter');	$format->set_align('center');	$format->set_bold();	$format->set_pattern();	$format->set_color('white');	$format->set_fg_color('orange');

$fjudul =& $workbook->add_format();
$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();

	$metdatacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$_REQUEST['cn'].'"'));
	$metclient = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metdatacn['id_cost'].'"'));
	$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metdatacn['id_nopol'].'"'));
	$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metpolis['benefit_type'].'"'));

	$worksheet1->merge_cells(0, 0, 0, 13);	$worksheet1->write_string(0, 0, "CREDIT NOTE", $fjudul);
	$worksheet1->merge_cells(2, 0, 2, 5);	$worksheet1->write_string(2, 0, $metclient['name'], $fjudul);

	$worksheet1->merge_cells(3, 0, 3, 1);	$worksheet1->write_string(3, 0, "Nomor Polis", $fjudul);	$worksheet1->merge_cells(3, 2, 3, 5);	$worksheet1->write_string(3, 2, ": " .$metpolis['nopol'], $fjudul);
	$worksheet1->merge_cells(4, 0, 4, 1);	$worksheet1->write_string(4, 0, "Nomor CN", $fjudul);		$worksheet1->merge_cells(4, 2, 4, 5);	$worksheet1->write_string(4, 2, ": " .$_REQUEST['cn'], $fjudul);
	$worksheet1->merge_cells(5, 0, 5, 1);	$worksheet1->write_string(5, 0, "Jenis Asuransi", $fjudul);	$worksheet1->merge_cells(5, 2, 5, 5);	$worksheet1->write_string(5, 2, ": Asuransi Jiwa Kredit-".$metmaster['msdesc'], $fjudul);

	$worksheet1->merge_cells(3, 11, 3, 12);	$worksheet1->write_string(3, 11, "Tanggal Efektif Polis", $fjudul);
	$worksheet1->set_column(3, 13, 10);	$worksheet1->write_string(3, 13, ": "._convertDate($metpolis['start_date']), $fjudul);

	$worksheet1->merge_cells(4, 11, 4, 12);	$worksheet1->write_string(4, 11, "Tanggal Nota CN", $fjudul);
	$worksheet1->set_column(4, 13, 10);	$worksheet1->write_string(4, 13, ": "._convertDate($metdatacn['tgl_createcn']), $fjudul);

	$worksheet1->merge_cells(5, 11, 5, 12);	$worksheet1->write_string(5, 11, "Regional", $fjudul);
	$worksheet1->set_column(5, 13, 10);	$worksheet1->write_string(5, 13, ": ".$metdatacn['id_regional'], $fjudul);

	$worksheet1->merge_cells(7, 0, 7, 5);	$worksheet1->write_string(7, 0, "DATA PESERTA ASURANSI JIWA KUMPULAN", $fjudul);

	// membuat header tabel dengan format
	$worksheet1->set_row(9, 15);
	$worksheet1->set_column(9, 0, 5);	$worksheet1->write_string(9, 0, "NO", $format);
	$worksheet1->set_column(9, 1, 15);	$worksheet1->write_string(9, 1, "NO. DEBITUR", $format);
	$worksheet1->set_column(9, 2, 10);	$worksheet1->write_string(9, 2, "DEBIT NOTE", $format);
	$worksheet1->set_column(9, 3, 20);	$worksheet1->write_string(9, 3, "NAMA", $format);
	$worksheet1->set_column(9, 4, 15);	$worksheet1->write_string(9, 4, "START.INS", $format);
	$worksheet1->set_column(9, 5, 20);	$worksheet1->write_string(9, 5, "TENOR", $format);
	$worksheet1->set_column(9, 6, 10);	$worksheet1->write_string(9, 6, "END.INS", $format);
	$worksheet1->set_column(9, 7, 10);	$worksheet1->write_string(9, 7, "DATE REFUND", $format);
	$worksheet1->set_column(9, 8, 10);	$worksheet1->write_string(9, 8, "MA-j", $format);
	$worksheet1->set_column(9, 9, 10);	$worksheet1->write_string(9, 9, "MA-s", $format);
	$worksheet1->set_column(9, 10, 10);	$worksheet1->write_string(9, 10, "U P", $format);
	$worksheet1->set_column(9, 11, 10);	$worksheet1->write_string(9, 11, "PREMI", $format);
	$worksheet1->set_column(9, 12, 20);	$worksheet1->write_string(9, 12, "TOTAL", $format);
	$worksheet1->set_column(9, 13, 20);	$worksheet1->write_string(9, 13, "CABANG", $format);

	// membuat header file excel dan nama filenya
if ($_REQUEST['cn'])		{	$satu = 'AND id_cn ="' . $_REQUEST['cn'] . '"';		}
$el = mysql_query('SELECT * FROM fu_ajk_cn WHERE id != "" '.$satu.' AND del IS NULL ORDER BY id_cn DESC, tgl_claim DESC');
$baris = 10;
while ($mamet = mysql_fetch_array($el))
{
	//$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metdatacn['id_nopol'].'"'));
	$metdebitur = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$mamet['id_cost'].'" AND id_peserta="'.$mamet['id_peserta'].'"'));
	//PERUMUSAN MA-j
	$kredit = explode("/", $metdebitur['kredit_tgl']);
	$nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
	$now = new T10DateCalc($nowkredit);

	$periodbulan = $now->compareDate($mamet['tgl_claim']) / 30.4375;
	//	$periodbulan = $now->compareDate($metklaim['tgl_klaim']);
	$maj = ceil($periodbulan);
	//PERUMUSAN MA-j

	//PERUMUSAN MA-s
	$findmet="/";
	$fpos = stripos($metdebitur['kredit_akhir'], $findmet);
	if ($fpos === false) { $cektglnya = $metdebitur['kredit_akhir'];	}
	else	{	$riweuh = explode("/", $metdebitur['kredit_akhir']);						$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];	}

	$r = $metdebitur['kredit_tenor'] - $maj;
	if ($r < 0) {	$mas = 0;	}else{	$mas = $r;	}
	//PERUMUSAN MA-s
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 2, $mamet['id_dn']);
	$worksheet1->write_string($baris, 3, $metdebitur['nama']);
	$worksheet1->write_string($baris, 4, $metdebitur['kredit_tgl']);
	$worksheet1->write_string($baris, 5, $metdebitur['kredit_tenor']);
	$worksheet1->write_string($baris, 6, $metdebitur['kredit_akhir']);
	$worksheet1->write_string($baris, 7, $mamet['tgl_claim']);
	$worksheet1->write_string($baris, 8, $maj);
	$worksheet1->write_string($baris, 9, $mas);
	$worksheet1->write_string($baris, 10, $metdebitur['kredit_jumlah']);
	$worksheet1->write_string($baris, 11, $mamet['premi']);
	$worksheet1->write_string($baris, 12, $mamet['total_claim']);
	$worksheet1->write_string($baris, 13, $mamet['id_cabang']);
	$baris++;
}
	$workbook->close();
		;
		break;

case "metsoa":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
if ($_REQUEST['regional']=="") { $soaregional = "PUSAT";	}else{	$soaregional = $_REQUEST['regional'];	}
	HeaderingExcel('SOA-'.$_REQUEST['dncreate'].'-'.$_REQUEST['dncreate1'].'.xls');
	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('ARM - SOA('.$soaregional.')');

	$formattitle =& $workbook->add_format();	$formattitle->set_align('vcenter');		$formattitle->set_align('center');		$formattitle->set_color('black');		$formattitle->set_bold();		$formattitle->set_pattern();	$formattitle->set_fg_color('white');
	$formattitle1 =& $workbook->add_format();	$formattitle1->set_color('black');		$formattitle1->set_pattern();			$formattitle1->set_fg_color('white');
	$formattitletgl =& $workbook->add_format();	$formattitletgl->set_align('center');	$formattitletgl->set_color('black');	$formattitletgl->set_fg_color('white');
	$format =& $workbook->add_format();			$format->set_align('vcenter');			$format->set_align('center');			$format->set_color('white');			$format->set_bold();		$format->set_pattern();			$format->set_fg_color('orange');
	$format1 =& $workbook->add_format();		$format1->set_align('right');
	$format2 =& $workbook->add_format();		$format2->set_align('center');

	// membuat header tabel dengan format
$tgl1 = explode("/", $_REQUEST['dncreate']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['dncreate1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
$tgl3 = explode("/", $_REQUEST['dnpaid']);		$tglawal2 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
$tgl4 = explode("/", $_REQUEST['dnpaid1']);		$tglawal3 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
if ($_REQUEST['dncreate']!='' AND $_REQUEST['dncreate1']!='')		{	$satu= 'AND fu_ajk_dn.tgl_createdn BETWEEN \''.$tglawal.'\' AND \''.$tglawal1.'\'';	}
if ($_REQUEST['cost']!='')											{	$dua= 'AND fu_ajk_dn.id_cost="'.$_REQUEST['cost'].'"';	}
if ($_REQUEST['regional']!='')										{	$tiga= 'AND fu_ajk_dn.id_regional="'.$_REQUEST['regional'].'"';	}
if ($_REQUEST['dnstatus']!='')										{	$empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['dnstatus'].'"';	}
if ($_REQUEST['dnpaid']!='' AND $_REQUEST['dnpaid1']!='')			{	$lima= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN "'.$tglawal2.'" AND "'.$tglawal3.'"';	}


if ($_REQUEST['armstatus']=="paid") {
	$paidnya = "Paid";
	/*HANYA PERHITUNGAN DN PAID
	   //$totsoa1 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL '));
	   //$totsoapaid=$totsoa1['tpremi'];

	   //NET DN PAID = PERHITUNGAN DN DI KURANG CN*/
/*
	$totsoa1 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid" AND del IS NULL ');
	while ($arraysoadn = mysql_fetch_array($totsoa1)) {
		$totsoadnpaid += $arraysoadn['totalpremi'];
		$soacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'"'));
		$totsoacnpaid += $soacn['total_claim'];
	}
	$totsoapaid = $totsoadnpaid - $totsoacnpaid;
*/
$totsoapaiddn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_kode, SUM(fu_ajk_dn.totalpremi) AS tPremiDN, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del FROM fu_ajk_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

$totsoapaidcn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai FROM fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

	$_totsoapaiddn = $totsoapaiddn_['tPremiDN'];
	$_totsoapaidcn = $totsoapaidcn_['tNilai'];
	$_totsoapaiddncn = $totsoapaiddn_['tPremiDN'] - $totsoapaidcn_['tNilai'];
	$totalnya = $_totsoapaiddncn;

}
elseif ($_REQUEST['armstatus']=="unpaid") {
	$paidnya = "unPaid";
	/*HANYA PERHITUNGAN DN UNPAID//
	   //$totsoa2 = mysql_fetch_array($database->doQuery('SELECT totalpremi AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="unpaid" AND del IS NULL '));
	   //$totsoaunpaid=$totsoa2['tpremi'];

	   //NET DN UNPAID = PERHITUNGAN DN DI KURANG CN*/
/*
	$totsoa2 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL ');
	while ($arraysoadn2 = mysql_fetch_array($totsoa2)) {
		$totsoadnunpaid += $arraysoadn2['totalpremi'];
		$soacn2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn2['dn_kode'].'"'));
		$totsoacnunpaid += $soacn2['total_claim'];
	}
	$totsoaunpaid = $totsoadnunpaid - $totsoacnunpaid;
*/
$totsoaunpaiddn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_kode, SUM(fu_ajk_dn.totalpremi) AS tPremiDN, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del FROM fu_ajk_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

$totsoaunpaidcn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai FROM fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

	$_totsoaunpaiddn = $totsoaunpaiddn_['tPremiDN'];
	$_totsoaunpaidcn = $totsoaunpaidcn_['tNilai'];
	$_totsoaunpaiddncn = $totsoaunpaiddn_['tPremiDN'] - $totsoaunpaidcn_['tNilai'];
	$totalnya = $_totsoaunpaiddncn;
}
else { $paidnya = "Paid dan unPaid";
	/*
	   $totsoa1 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL '));
	   $totsoapaid=$totsoa1['tpremi'];

	   $totsoa2 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="unpaid" AND del IS NULL '));
	   $totsoaunpaid=$totsoa2['tpremi'];
	*/
/*
	$totsoa1 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid" AND del IS NULL ');
while ($arraysoadn = mysql_fetch_array($totsoa1)) {
	$totsoadnpaid += $arraysoadn['totalpremi'];
	$soacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'"'));
	$totsoacnpaid += $soacn['total_claim'];
}
	$totsoapaid = $totsoadnpaid - $totsoacnpaid;

	$totsoa2 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL ');
while ($arraysoadn2 = mysql_fetch_array($totsoa2)) {
	$totsoadnunpaid += $arraysoadn2['totalpremi'];
	$soacn2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn2['dn_kode'].'"'));
	$totsoacnunpaid += $soacn2['total_claim'];
}
	$totsoaunpaid = $totsoadnunpaid - $totsoacnunpaid;

	$totalnya = $totsoaunpaid + $totsoapaid;
*/
/*
$totsoapaiddn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai, SUM(fu_ajk_dn.totalpremi) AS tPremi
FROM fu_ajk_dn LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.dn_status ="paid" '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.dn_status '));

$totsoaunpaiddn_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai, SUM(fu_ajk_dn.totalpremi) AS tPremi
FROM fu_ajk_dn LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.dn_status ="unpaid" '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.dn_status '));
*/
$_totsoapaiddn_ = mysql_query('SELECT fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.id_regional,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.totalpremi AS tPremi,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.del,
fu_ajk_dn.tgl_dn_paid,
SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS tNilai,
fu_ajk_cn.type_claim
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.dn_status,fu_ajk_dn.dn_kode');
while ($_totsoapaiddn__ = mysql_fetch_array($_totsoapaiddn_)) {
if ($_totsoapaiddn__['dn_status']=="paid") {
$totsoapaiddn_1 += $_totsoapaiddn__['tPremi'] - $_totsoapaiddn__['tNilai'];
}else{
$totsoapaiddn_2 += $_totsoapaiddn__['tPremi'] - $_totsoapaiddn__['tNilai'];
}

}

	$_totsoapaiddncn = $totsoapaiddn_1;
	$_totsoaunpaiddncn = $totsoapaiddn_2;
	$totalnya = $_totsoapaiddncn + $_totsoaunpaiddncn;

}

	$metclient = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cost'].'"'));
	$worksheet1->set_column(0, 0, 25);	$worksheet1->merge_cells(0,0,0,15);	$worksheet1->set_column(0, 0, 25);	$worksheet1->write_string(0,0,"STATEMENT of ACCOUNT", $formattitle);
	$worksheet1->set_column(1, 0, 5);	$worksheet1->merge_cells(1,0,1,15);	$worksheet1->set_column(1, 0, 5);	$worksheet1->write_string(1,0,$futgl, $formattitletgl);

	$worksheet1->set_column(3, 0, 5);	$worksheet1->merge_cells(3,0,3,1);	$worksheet1->set_column(3, 0, 5);	$worksheet1->write_string(3,0,"Nama Perusahaan", $formattitle1);
	$worksheet1->set_column(3, 2, 5);	$worksheet1->merge_cells(3,2,3,5);	$worksheet1->set_column(3, 2, 5);	$worksheet1->write_string(3,2,": ".$metclient['name'], $formattitle1);

	$worksheet1->set_column(4, 0, 5);	$worksheet1->merge_cells(4,0,4,1);	$worksheet1->set_column(4, 0, 5);	$worksheet1->write_string(4,0,"Regional", $formattitle1);
	$worksheet1->set_column(4, 2, 5);	$worksheet1->merge_cells(4,2,4,5);	$worksheet1->set_column(4, 2, 5);	$worksheet1->write_string(4,2,": ".$soaregional, $formattitle1);

	$worksheet1->set_column(5, 0, 5);	$worksheet1->merge_cells(5,0,5,1);	$worksheet1->set_column(5, 0, 5);	$worksheet1->write_string(5,0,"Periode", $formattitle1);
	$worksheet1->set_column(5, 2, 5);	$worksheet1->merge_cells(5,2,5,5);	$worksheet1->set_column(5, 2, 5);	$worksheet1->write_string(5,2,": ".$_REQUEST['dncreate'].' - '.$_REQUEST['dncreate1'], $formattitle1);

	$worksheet1->set_column(6, 0, 5);	$worksheet1->merge_cells(6,0,6,1);	$worksheet1->set_column(6, 0, 5);	$worksheet1->write_string(6,0,"Paid", $formattitle1);
	$worksheet1->set_column(6, 2, 5);	$worksheet1->merge_cells(6,2,6,5);	$worksheet1->set_column(6, 2, 5);	$worksheet1->write_string(6,2,": ".duit($_totsoapaiddncn), $formattitle1);

	$worksheet1->set_column(7, 0, 5);	$worksheet1->merge_cells(7,0,7,1);	$worksheet1->set_column(7, 0, 5);	$worksheet1->write_string(7,0,"unPaid", $formattitle1);
	$worksheet1->set_column(7, 2, 5);	$worksheet1->merge_cells(7,2,7,5);	$worksheet1->set_column(7, 2, 5);	$worksheet1->write_string(7,2,": ".duit($_totsoaunpaiddncn), $formattitle1);

	$worksheet1->set_column(8, 0, 5);	$worksheet1->merge_cells(8,0,8,1);	$worksheet1->set_column(8, 0, 5);	$worksheet1->write_string(8,0,"Total", $formattitle1);
	$worksheet1->set_column(8, 2, 5);	$worksheet1->merge_cells(8,2,8,5);	$worksheet1->set_column(8, 2, 5);	$worksheet1->write_string(8,2,": ".duit($totalnya), $formattitle1);

	$worksheet1->set_row(10, 15);
	$worksheet1->set_column(10, 0, 5);	$worksheet1->write_string(10, 0, "NO", $format);
	$worksheet1->set_column(10, 1, 10);	$worksheet1->write_string(10, 1, "POLIS", $format);
	$worksheet1->set_column(10, 2, 15);	$worksheet1->write_string(10, 2, "NOMOR DN", $format);
	$worksheet1->set_column(10, 3, 10);	$worksheet1->write_string(10, 3, "TANGGAL DN", $format);
	$worksheet1->set_column(10, 4, 20);	$worksheet1->write_string(10, 4, "DATE PAYMENT", $format);
	$worksheet1->set_column(10, 5, 15);	$worksheet1->write_string(10, 5, "PREMI DN", $format);
	$worksheet1->set_column(10, 6, 20);	$worksheet1->write_string(10, 6, "NOMOR CN", $format);
	$worksheet1->set_column(10, 7, 10);	$worksheet1->write_string(10, 7, "TANGGAL CN", $format);
//	$worksheet1->set_column(10, 8, 10);	$worksheet1->write_string(10, 8, "PESERTA CN", $format);
	$worksheet1->set_column(10, 8, 10);	$worksheet1->write_string(10, 8, "NILAI CN", $format);
	$worksheet1->set_column(10, 9, 10);	$worksheet1->write_string(10, 9, "NETT PREMI", $format);
	$worksheet1->set_column(10, 10, 20);	$worksheet1->write_string(10, 10, "PAYMENT", $format);
	$worksheet1->set_column(10, 11, 20);	$worksheet1->write_string(10, 11, "BALANCE", $format);
	$worksheet1->set_column(10, 12, 20);	$worksheet1->write_string(10, 12, "STATUS", $format);
	$worksheet1->set_column(10, 13, 20);	$worksheet1->write_string(10, 13, "TYPE", $format);
	$worksheet1->set_column(10, 14, 20);	$worksheet1->write_string(10, 14, "REGIONAL", $format);
	$worksheet1->set_column(10, 15, 20);	$worksheet1->write_string(10, 15, "CABANG", $format);
	$worksheet1->set_column(10, 16, 20);	$worksheet1->write_string(10, 16, "TANGGAL PROSES", $format);

// membuat header file excel dan nama filenya
$el = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL ORDER BY tgl_createdn ASC, dn_kode ASC ');
$baris = 11;
while ($mamet = mysql_fetch_array($el))
{
	$jumpeserta = mysql_num_rows(mysql_query('SELECT id_dn, del FROM fu_ajk_peserta WHERE id_dn="'.$mamet['dn_kode'].'" AND del IS NULL'));

	//DATA CN//
	$soacn = mysql_fetch_array(mysql_query('SELECT fu_ajk_cn.id_cn,
													  fu_ajk_cn.id_dn,
													  fu_ajk_cn.id_cost,
													  fu_ajk_cn.tgl_createcn,
													  fu_ajk_cn.type_claim,
													  SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS total_claim
											   FROM fu_ajk_cn
											   WHERE id_dn="'.$mamet['dn_kode'].'" AND del IS NULL
											   GROUP BY fu_ajk_cn.id_dn'));
if ($soacn['id_dn']==$mamet['dn_kode']) {
	$jumpesertacn = mysql_num_rows(mysql_query('SELECT id_dn, id_klaim, del FROM fu_ajk_peserta WHERE id_klaim="'.$soacn['id_cn'].'" AND del IS NULL'));
	$totalcn = $soacn['total_claim'];
	$netnya = $mamet['totalpremi'] - $soacn['total_claim'];
}else{
	$jumpesertacn ='';
	$totalcn = '';
	$netnya = $mamet['totalpremi'];
}

//TAMPIL POLISNYA
$metdnpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));

//DATA CN//
if ($mamet['dn_total']=="") {	$paymentnya = $mamet['totalpremi'] - $soacn['total_claim']; }	else	{	$paymentnya = $mamet['dn_total'] - $soacn['total_claim'];	}		//NILAI PAYMENT
//$balancenya = $mamet['totalpremi'] - $soacn['total_claim'] - $paymentnya;															//NILAI BALANCE USER ARM - DISABLED
$balancenya = "";																													//NILAI BALANCE

	$worksheet1->write_number($baris, 0, ++$no, $format2);
	$worksheet1->write_string($baris, 1, $metdnpolis['nopol'], $format2);
	$worksheet1->write_string($baris, 2, $mamet['dn_kode'], $format2);
	$worksheet1->write_string($baris, 3, $mamet['tgl_createdn'], $format2);
	$worksheet1->write_string($baris, 4, $mamet['tgl_dn_paid'], $format2);
	//$worksheet1->write_number($baris, 5, $jumpeserta, $format2);
	$worksheet1->write_string($baris, 5, duit($mamet['totalpremi']), $format1);
	$worksheet1->write_string($baris, 6, $soacn['id_cn'], $format2);
	$worksheet1->write_string($baris, 7, $soacn['tgl_createcn']);
	//$worksheet1->write_number($baris, 8, $jumpesertacn, $format2);
	$worksheet1->write_string($baris, 8, duit($totalcn), $format1);
	$worksheet1->write_string($baris, 9, duit($netnya), $format1);
	$worksheet1->write_string($baris, 10, duit($paymentnya), $format1);
	$worksheet1->write_string($baris, 11, duit($balancenya), $format1);
	$worksheet1->write_string($baris, 12, $mamet['dn_status']);
	$worksheet1->write_string($baris, 13, $soacn['type_claim']);
	$worksheet1->write_string($baris, 14, $mamet['id_regional']);
	$worksheet1->write_string($baris, 15, $mamet['id_cabang']);
	$worksheet1->write_string($baris, 16, $mamet['update_time']);
	$baris++;
}
	$workbook->close();
	;
	break;

case "metsoapdf":
	$pdf=new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('image/logo_recapitalife.png',10,20);
	$pdf->SetFont('Arial','B',16);	$pdf->Text(75, 30,'STATEMENT of ACCOUNT');
	$pdf->SetFont('Arial','',9);	$pdf->Text(100,35,$futgl);

if ($_REQUEST['regional']=="") { $soaregional = "PUSAT";	}else{	$soaregional = $_REQUEST['regional'];	}
	$tgl1 = explode("/", $_REQUEST['dncreate']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['dncreate1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
	$tgl3 = explode("/", $_REQUEST['dnpaid']);		$tglawal2 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
	$tgl4 = explode("/", $_REQUEST['dnpaid1']);		$tglawal3 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
if ($_REQUEST['dncreate']!='' AND $_REQUEST['dncreate1']!='')		{	$satu= 'AND tgl_createdn BETWEEN \''.$tglawal.'\' AND \''.$tglawal1.'\'';	}
if ($_REQUEST['cost']!='')											{	$dua= 'AND id_cost="'.$_REQUEST['cost'].'"';	}
if ($_REQUEST['regional']!='')										{	$tiga= 'AND id_regional="'.$_REQUEST['regional'].'"';	}
if ($_REQUEST['dnstatus']!='')										{	$empat = 'AND dn_status = "'.$_REQUEST['dnstatus'].'"';	}
if ($_REQUEST['dnpaid']!='' AND $_REQUEST['dnpaid1']!='')			{	$lima= 'AND tgl_dn_paid BETWEEN "'.$tglawal2.'" AND "'.$tglawal3.'"';	}

if ($_REQUEST['armstatus']=="paid") {
	$paidnya = "Paid";
	/*HANYA PERHITUNGAN DN PAID
	   //$totsoa1 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL '));
	   //$totsoapaid=$totsoa1['tpremi'];

	   //NET DN PAID = PERHITUNGAN DN DI KURANG CN*/
	$totsoa1 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid" AND del IS NULL ');
	while ($arraysoadn = mysql_fetch_array($totsoa1)) {
		$totsoadnpaid += $arraysoadn['totalpremi'];
		$soacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'"'));
		$totsoacnpaid += $soacn['total_claim'];
	}
	$totsoapaid = $totsoadnpaid - $totsoacnpaid;
}
elseif ($_REQUEST['armstatus']=="unpaid") {
	$paidnya = "unPaid";
	/*HANYA PERHITUNGAN DN UNPAID//
	   //$totsoa2 = mysql_fetch_array($database->doQuery('SELECT totalpremi AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="unpaid" AND del IS NULL '));
	   //$totsoaunpaid=$totsoa2['tpremi'];

	   //NET DN UNPAID = PERHITUNGAN DN DI KURANG CN*/
	$totsoa2 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL ');
	while ($arraysoadn2 = mysql_fetch_array($totsoa2)) {
		$totsoadnunpaid += $arraysoadn2['totalpremi'];
		$soacn2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn2['dn_kode'].'"'));
		$totsoacnunpaid += $soacn2['total_claim'];
	}
	$totsoaunpaid = $totsoadnunpaid - $totsoacnunpaid;
}
else { $paidnya = "Paid dan unPaid";
	/*
	   $totsoa1 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL '));
	   $totsoapaid=$totsoa1['tpremi'];

	   $totsoa2 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="unpaid" AND del IS NULL '));
	   $totsoaunpaid=$totsoa2['tpremi'];
	*/
	$totsoa1 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid" AND del IS NULL ');
while ($arraysoadn = mysql_fetch_array($totsoa1)) {
	$totsoadnpaid += $arraysoadn['totalpremi'];
	$soacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'"'));
	$totsoacnpaid += $soacn['total_claim'];
}
	$totsoapaid = $totsoadnpaid - $totsoacnpaid;

	$totsoa2 = mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL ');
while ($arraysoadn2 = mysql_fetch_array($totsoa2)) {
	$totsoadnunpaid += $arraysoadn2['totalpremi'];
	$soacn2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn2['dn_kode'].'"'));
	$totsoacnunpaid += $soacn2['total_claim'];
}
	$totsoaunpaid = $totsoadnunpaid - $totsoacnunpaid;

	$totalnya = $totsoaunpaid + $totsoapaid;
}
	$metclient = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cost'].'"'));
	$pdf->SetFont('Arial','B',10);	$pdf->Text(10, 50,'Nama Perusahaan');	$pdf->Text(50, 50,':');	$pdf->Text(55, 50, $metclient['name']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10, 55,'Regional');			$pdf->Text(50, 55,':');	$pdf->Text(55, 55, $soaregional);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10, 60,'Periode Tgl DN');	$pdf->Text(50, 60,':');	$pdf->Text(55, 60, $_REQUEST['dncreate'].' - '.$_REQUEST['dncreate1']);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10, 65,'Status DN');			$pdf->Text(50, 65,':');	$pdf->Text(55, 65, $paidnya);
	$pdf->SetFont('Arial','',10);	$pdf->Text(10, 70,'Paid');				$pdf->Text(50, 70,':');	$pdf->Text(55, 70, duit($totsoapaid));
	$pdf->SetFont('Arial','',10);	$pdf->Text(10, 75,'unPaid');			$pdf->Text(50, 75,':');	$pdf->Text(55, 75, duit($totsoaunpaid));
	$pdf->SetFont('Arial','B',10);	$pdf->Text(10, 80,'Total');				$pdf->Text(50, 80,':');	$pdf->Text(55, 80, duit($totalnya));

	$y_initial = 84;
	$y_axis1 = 85;
	$pdf->setFont('Arial','',6.5);
	$pdf->setFillColor(233,233,233);
	$pdf->setY($y_axis1);
	$pdf->setX(5);

	$pdf->cell(5,6,'No',1,0,'C',1);
	$pdf->cell(18,6,'NO. DN',1,0,'C',1);
	$pdf->cell(13,6,'TGL PAID',1,0,'C',1);
	$pdf->cell(13,6,'TGL DN',1,0,'C',1);
	$pdf->cell(15,6,'PREMI',1,0,'C',1);
	$pdf->cell(18,6,'NO. CN',1,0,'C',1);
	$pdf->cell(13,6,'TGL CN',1,0,'C',1);
	$pdf->cell(15,6,'NILAI CN',1,0,'C',1);
	$pdf->cell(16,6,'NETT PREMI',1,0,'C',1);
	$pdf->cell(14,6,'PAYMENT',1,0,'C',1);
	$pdf->cell(15,6,'BALANCE',1,0,'C',1);
	$pdf->cell(19,6,'REGIONAL',1,0,'C',1);
	$pdf->cell(26,6,'CABANG',1,0,'C',1);

$el = mysql_query('SELECT * FROM fu_ajk_dn WHERE id_dn != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL ORDER BY tgl_createdn ASC, id_dn ASC ');
while ($metsoa = mysql_fetch_array($el))
{
	//DATA CN//
	$soacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metsoa['dn_kode'].'"'));
if ($soacn['id_dn']==$metsoa['dn_kode']) {
	$jumpesertacn = mysql_num_rows(mysql_query('SELECT id_dn, id_klaim, del FROM fu_ajk_peserta WHERE id_klaim="'.$soacn['id_cn'].'" AND del IS NULL'));
	$totalcn = $soacn['total_claim'];
	if ($totalcn < 0) {	$cektotaldcn = 0;	}	else{	$cektotaldcn = $totalcn;	}
	$netnya = $metsoa['totalpremi'] - $cektotaldcn;
}else{
	$jumpesertacn ='';
	$totalcn = 0;
	$netnya = $metsoa['totalpremi'];
}
	if ($totalcn < 0) {	$cektotaldcn = 0;	}	else{	$cektotaldcn = $totalcn;	}
	//DATA CN//
if ($metsoa['dn_total']=="") {	$paymentnya = $metsoa['totalpremi'] - $cektotaldcn; }	else	{	$paymentnya = $metsoa['dn_total'] - $cektotaldcn;	}			//NILAI PAYMENT
	$balancenya = $netnya - $paymentnya;													//NILAI BALANCE

	$nomordnnya = substr($metsoa['dn_kode'], 6);
	$nomorcnnya = substr($soacn['id_cn'], 6);
	$cell[$i][0] = $nomordnnya;
	$cell[$i][1] = _convertDate($metsoa['tgl_createdn']);
	$cell[$i][2] = _convertDate($metsoa['tgl_dn_paid']);
	$cell[$i][3] = duit($metsoa['totalpremi']);
	$cell[$i][4] = $nomorcnnya;
	$cell[$i][5] = _convertDate($soacn['tgl_createcn']);
	$cell[$i][6] = duit($cektotaldcn);
	$cell[$i][7] = duit($netnya);
	$cell[$i][8] = duit($paymentnya);
	$cell[$i][9] = duit($balancenya);
	$cell[$i][10] = $metsoa['id_regional'];
	$cell[$i][11] = $metsoa['id_cabang'];
	$i++;
	$soatotaldn += $metsoa['totalpremi'];
	$soatotalcn += $totalcn;
	$soatotalnet += $netnya;
	$soatotalpay += $paymentnya;
	$soatotalbalance += $balancenya;

}
	$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{
		$pdf->setX(5);
		//menampilkan data dari hasil query database
		$pdf->cell(5,6,$j+1,1,0,'C');
		$pdf->cell(18,6,$cell[$j][0],1,0,'C');
		$pdf->cell(13,6,$cell[$j][1],1,0,'C');
		$pdf->cell(13,6,$cell[$j][2],1,0,'C');
		$pdf->cell(15,6,$cell[$j][3],1,0,'R');
		$pdf->cell(18,6,$cell[$j][4],1,0,'C');
		$pdf->cell(13,6,$cell[$j][5],1,0,'C');
		$pdf->cell(15,6,$cell[$j][6],1,0,'R');
		$pdf->cell(16,6,$cell[$j][7],1,0,'R');
		$pdf->cell(14,6,$cell[$j][8],1,0,'R');
		$pdf->cell(15,6,$cell[$j][9],1,0,'R');
		$pdf->cell(19,6,$cell[$j][10],1,0,'L');
		$pdf->cell(26,6,$cell[$j][11],1,0,'L');
		$pdf->Ln();
	}
	$pdf->setX(5);
	$pdf->setFont('Arial','B',6);
	$pdf->cell(49,6,'TOTAL',1,0,'C');
	$pdf->cell(15,6,duit($soatotaldn),1,0,'R');
	$pdf->cell(31,6,"",1,0,'R');
	$pdf->cell(15,6,duit($soatotalcn),1,0,'R');
	$pdf->cell(16,6,duit($soatotalnet),1,0,'R');
	$pdf->cell(14,6,duit($soatotalpay),1,0,'R');
	$pdf->cell(15,6,duit($soatotalbalance),1,0,'R');
	$pdf->cell(45,6,"",1,0,'R');

	$pdf->Ln();
	$pdf->setFont('Arial','B',8);
	$pdf->cell(170,6,"Jakarta, ". $futgl,0,0,'R');
	$pdf->Ln();
	$pdf->setFont('Arial','',8);
	$pdf->cell(50,6,"Checked By",0,0,'R');
	$pdf->cell(113,6,"Akcnowledge By",0,0,'R');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->setFont('Arial','B',8);
	$pdf->cell(54,6,"(Erni Sumartini)",0,0,'R');
	$pdf->cell(116,6,"(Rudy Bhakti Setiawan)",0,0,'R');
	$pdf->Output();
	break;

case "ojk":
/*
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
	$ojkcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	HeaderingExcel('Laporan_OJK_('.$futglojk.').xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet($ojkcostumer['name']);

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
	$worksheet1->set_column(0, 2, 15);	$worksheet1->write_string(0, 2, "POLIS", $format);
	$worksheet1->set_column(0, 3, 20);	$worksheet1->write_string(0, 3, "DEBIT NOTE", $format);
	$worksheet1->set_column(0, 4, 12);	$worksheet1->write_string(0, 4, "TANGGAL DN", $format);
	$worksheet1->set_column(0, 5, 12);	$worksheet1->write_string(0, 5, "ID PESERTA", $format);
	$worksheet1->set_column(0, 6, 20);	$worksheet1->write_string(0, 6, "NAMA", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "TGL LAHIR", $format);
	$worksheet1->set_column(0, 8, 5);	$worksheet1->write_string(0, 8, "USIA", $format);
	$worksheet1->set_column(0, 9, 13);	$worksheet1->write_string(0, 9, "KREDIT AWAL", $format);
	$worksheet1->set_column(0, 10, 8);	$worksheet1->write_string(0, 10, "TENOR", $format);
	$worksheet1->set_column(0, 11, 13);	$worksheet1->write_string(0, 11, "KREDIT AKHIR", $format);
	$worksheet1->set_column(0, 12, 10);	$worksheet1->write_string(0, 12, "U P ", $format);
	$worksheet1->set_column(0, 13, 14);	$worksheet1->write_string(0, 13, "TOTAL PREMI", $format);
	$worksheet1->set_column(0, 14, 8);	$worksheet1->write_string(0, 14, "STATUS", $format);
	$worksheet1->set_column(0, 15, 8);	$worksheet1->write_string(0, 15, "PESERTA", $format);
	$worksheet1->set_column(0, 16, 10);	$worksheet1->write_string(0, 16, "TYPE", $format);
	$worksheet1->set_column(0, 17, 20);	$worksheet1->write_string(0, 17, "CABANG", $format);
	$worksheet1->set_column(0, 18, 20);	$worksheet1->write_string(0, 18, "REGIONAL", $format);

// membuat header file excel dan nama filenya
$el = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn!="" AND id_cost="'.$_REQUEST['cat'].'" AND status_aktif="aktif" AND del IS NULL ORDER BY id_polis DESC, id_dn DESC, kredit_tgl DESC');
$baris = 1;
while ($mamet = mysql_fetch_array($el))
{
	$ojkpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$mamet['id_polis'].'"'));
	$ojkdatedn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$mamet['id_dn'].'"'));

	if ($mamet['status_bayar']==0) {	$bayarnya = "unpaid";	}else{	$bayarnya = "paid";	}
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $mamet['spaj']);
	$worksheet1->write_string($baris, 2, $ojkpolis['nopol']);
	$worksheet1->write_string($baris, 3, $ojkdatedn['dn_kode']);
	$worksheet1->write_string($baris, 4, $ojkdatedn['tgl_createdn']);
	$worksheet1->write_string($baris, 5, $mamet['id_peserta']);
	$worksheet1->write_string($baris, 6, $mamet['nama']);
	$worksheet1->write_string($baris, 7, $mamet['tgl_lahir']);
	$worksheet1->write_string($baris, 8, $mamet['usia']);
	$worksheet1->write_string($baris, 9, $mamet['kredit_tgl']);
	$worksheet1->write_string($baris, 10, $mamet['kredit_tenor']);
	$worksheet1->write_string($baris, 11, $mamet['kredit_akhir']);
	$worksheet1->write_string($baris, 12, $mamet['kredit_jumlah']);
	$worksheet1->write_string($baris, 13, $mamet['totalpremi']);
	$worksheet1->write_string($baris, 14, $bayarnya);
	$worksheet1->write_string($baris, 15, $mamet['status_aktif']);
	$worksheet1->write_string($baris, 16, $mamet['status_peserta']);
	$worksheet1->write_string($baris, 17, $mamet['cabang']);
	$worksheet1->write_string($baris, 18, $mamet['regional']);
	$baris++;
}
	$workbook->close();
*/

function echocsv( $fields )
{
	$separator = '';
	foreach ( $fields as $field )
	{
		if ( preg_match( '/\\r|\\n|;|"/', $field ) )
		{
			$field = str_replace( '"', '""', $field );
		}
		echo $separator . $field;
		$separator = ';';
	}
	echo "\r\n";
}

	$ojkcostumer = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	//HeaderingExcel('Laporan_OJK_('.$futglojk.').xls');
if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';	}

	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
if ($_REQUEST['tanggal1'])					{	$dua = 'AND fu_ajk_dn.tgl_createdn <= "'.$tglawal.'" ';	}

$query = sprintf('SELECT
	fu_ajk_costumer.`name` AS CLIENT,
	fu_ajk_polis.nopol AS POLIS,
	fu_ajk_dn.tgl_createdn AS TGL_DN,
	fu_ajk_peserta.id_dn AS NO_DN,
	fu_ajk_peserta.id_peserta AS IDPESERTA,
	fu_ajk_peserta.nama AS NAMA,
	fu_ajk_peserta.tgl_lahir AS DOB,
	fu_ajk_peserta.usia AS USIA,
	fu_ajk_peserta.vkredit_tgl AS TGL_MULAI,
	fu_ajk_peserta.kredit_jumlah AS UP,
	fu_ajk_peserta.kredit_tenor AS TENOR,
	fu_ajk_peserta.vkredit_akhir AS TGL_AKHIR,
	fu_ajk_peserta.totalpremi AS TOTALPREMI,
	fu_ajk_peserta.status_aktif AS STATUS,
	fu_ajk_peserta.regional AS REGIONAL,
	fu_ajk_peserta.area AS AREA,
	fu_ajk_peserta.cabang AS CABANG
	FROM
	fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.dn_kode AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	WHERE
	fu_ajk_peserta.id !=""  '.$satu.' '.$dua.' AND
	fu_ajk_peserta.status_aktif = "aktif" AND
	fu_ajk_peserta.del IS NULL');
	$result = mysql_query( $query, $conn ) or die( mysql_error( $conn ) );

	header( "Content-Type: text/csv" );
	header( "Content-Disposition: attachment;filename=Laporan_OJK_(".$futglojk.").csv" );

	$row = mysql_fetch_assoc( $result );
if ( $row )
{
	echocsv( array_keys( $row ) );
}

while ( $row )
{
	echocsv( $row );
	$row = mysql_fetch_assoc( $result );
}
	exit;
		;
		break;

case "metrefund":
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';			}
if ($_REQUEST['subcat'])		{	$cekregionalnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional ="' . $cekregionalnya['name'] . '"';
}
if ($_REQUEST['tanggal1'])		{	$tiga = 'AND tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}

	$datarefund = mysql_query('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND type_claim="Refund" AND del IS NULL ORDER BY tgl_createcn ASC');
	HeaderingExcel('Laporan_Refund_'.$tglawal.'-'.$tglakhir.'.xls');
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet("Report Refund");

	$format =& $workbook->add_format();
	$format->set_align('vcenter');
	$format->set_align('center');
	$format->set_color('BLACK');
	$format->set_bold();
	$format->set_pattern();
	$format->set_fg_color('orange');

	$worksheet1->set_row(0, 15);
	$worksheet1->set_column(0, 0, 5);	$worksheet1->write_string(0, 0, "NO", $format);
	$worksheet1->set_column(0, 1, 10);	$worksheet1->write_string(0, 1, "POLICY", $format);
	$worksheet1->set_column(0, 2, 15);	$worksheet1->write_string(0, 2, "DEBIT NOTE", $format);
	$worksheet1->set_column(0, 3, 20);	$worksheet1->write_string(0, 3, "DATE DN", $format);
	$worksheet1->set_column(0, 4, 12);	$worksheet1->write_string(0, 4, "CREDIT NOTE", $format);
	$worksheet1->set_column(0, 5, 12);	$worksheet1->write_string(0, 5, "DATE CN", $format);
	$worksheet1->set_column(0, 6, 20);	$worksheet1->write_string(0, 6, "ID", $format);
	$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "NAME", $format);
	$worksheet1->set_column(0, 8, 5);	$worksheet1->write_string(0, 8, "DOB", $format);
	$worksheet1->set_column(0, 9, 5);	$worksheet1->write_string(0, 9, "USIA", $format);
	$worksheet1->set_column(0, 10, 13);	$worksheet1->write_string(0, 10, "START_INS", $format);
	$worksheet1->set_column(0, 11, 8);	$worksheet1->write_string(0, 11, "TENOR", $format);
	$worksheet1->set_column(0, 12, 13);	$worksheet1->write_string(0, 12, "END_INS", $format);
	$worksheet1->set_column(0, 13, 10);	$worksheet1->write_string(0, 13, "U P", $format);
	$worksheet1->set_column(0, 14, 10);	$worksheet1->write_string(0, 14, "PREMI", $format);
	$worksheet1->set_column(0, 15, 10);	$worksheet1->write_string(0, 15, "Ext.PREMI", $format);
	$worksheet1->set_column(0, 16, 10);	$worksheet1->write_string(0, 16, "TOTAL PREMI", $format);
	$worksheet1->set_column(0, 17, 14);	$worksheet1->write_string(0, 17, "MA-j", $format);
	$worksheet1->set_column(0, 18, 8);	$worksheet1->write_string(0, 18, "MA-s", $format);
	$worksheet1->set_column(0, 19, 8);	$worksheet1->write_string(0, 19, "PREMI REFUND", $format);
	$worksheet1->set_column(0, 20, 10);	$worksheet1->write_string(0, 20, "DATE REFUND", $format);
	$worksheet1->set_column(0, 21, 20);	$worksheet1->write_string(0, 21, "BRANCH", $format);
	$worksheet1->set_column(0, 22, 20);	$worksheet1->write_string(0, 22, "REGIONAL", $format);

	$baris = 1;
	while ($setrefund = mysql_fetch_array($datarefund))
	{
		$refpolis = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$setrefund['id_nopol'].'"'));			//POLISNYA
		$refdn = mysql_fetch_array(mysql_query('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$setrefund['id_dn'].'"'));				//TANGGAL DNNYA

		// MASA ASURANSI BERJALAN
		$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$setrefund['id_dn'].'" AND id_klaim="'.$setrefund['id_cn'].'" AND id_peserta="'.$setrefund['id_peserta'].'" '));
		$kredit = explode("/", $metpeserta['kredit_tgl']);
		$nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
		$now = new T10DateCalc($nowkredit);
		$periodbulan = $now->compareDate($setrefund['tgl_claim']) / 30.4375;
		$maj = ceil($periodbulan);
		// MASA ASURANSI BERJALAN
		// MASA ASURANSI SISA
		$r = $metpeserta['kredit_tenor'] - $maj;
		if ($r < 0) {	$mas = 0;	}else{	$mas = $r;	}
		// MASA ASURANSI SISA

		$worksheet1->write_string($baris, 0, ++$no);
		$worksheet1->write_string($baris, 1, $refpolis['nopol']);
		$worksheet1->write_string($baris, 2, $setrefund['id_dn']);
		$worksheet1->write_string($baris, 3, _convertDate($refdn['tgl_createdn']));
		$worksheet1->write_string($baris, 4, $setrefund['id_cn']);
		$worksheet1->write_string($baris, 5, _convertDate($setrefund['tgl_createcn']));
		$worksheet1->write_string($baris, 6, $setrefund['id_peserta']);
		$worksheet1->write_string($baris, 7, $metpeserta['nama']);
		$worksheet1->write_string($baris, 8, $metpeserta['usia']);
		$worksheet1->write_string($baris, 9, $metpeserta['tgl_lahir']);
		$worksheet1->write_string($baris, 10, $metpeserta['kredit_tgl']);
		$worksheet1->write_string($baris, 11, $metpeserta['kredit_tenor']);
		$worksheet1->write_string($baris, 12, $metpeserta['kredit_akhir']);
		$worksheet1->write_string($baris, 13, duit($metpeserta['kredit_jumlah']));
		$worksheet1->write_string($baris, 14, duit($metpeserta['premi']));
		$worksheet1->write_string($baris, 15, duit($metpeserta['ext_premi']));
		$worksheet1->write_string($baris, 16, duit($metpeserta['totalpremi']));
		$worksheet1->write_string($baris, 17, $maj);
		$worksheet1->write_string($baris, 18, $mas);
		$worksheet1->write_string($baris, 19, duit($setrefund['total_claim']));
		$worksheet1->write_string($baris, 20, _convertDate($setrefund['tgl_claim']));
		$worksheet1->write_string($baris, 21, $setrefund['id_cabang']);
		$worksheet1->write_string($baris, 22, $setrefund['id_regional']);
		$baris++;
	}
	$workbook->close();
		;
		break;

case "ajksertifikat":
echo '<style type="text/css">
table.serftifikatrelife {
	font-family: verdana,arial,sans-serif;
	font-size:10px;
	color:#333333;
	border-collapse: collapse;
}
table.serftifikatrelife td {
	padding: 2px;
	background-color: #ffffff;
}
.serffontjudul{
	font-size:14px;
}
</style>';

$met_serf = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$pesertaSerf = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$met_serf['dn_kode'].'"');
while ($metserf = mysql_fetch_array($pesertaSerf)) {
$met_serf_polis = mysql_fetch_array(mysql_query('SELECT id, typeRate, adm_fee, nopol FROM fu_ajk_polis WHERE id="'.$metserf['id_polis'].'"'));
$met_serf_cost = mysql_fetch_array(mysql_query('SELECT id, name, address, city, postcode FROM fu_ajk_costumer WHERE id="'.$metserf['id_cost'].'"'));
$totalSerf = $metserf['premi'] + $met_serf_polis['adm_fee'];
echo '<table border="0" width="95%" cellpadding="1" cellspacing="3" class="serftifikatrelife" align="center">
	  <tr><td align="center" colspan="5" class="serffontjudul"><br /><b>SERTIFIKAT</td></tr>
	  <tr><td align="center" colspan="5"><b>No. '.$metserf['id_peserta'].'<br /><br /><br /><br /></td></tr>
	  <tr><td width="30%">Nama Tertanggung</td><td width="1%">:</td><td colspan="3">'.$metserf['nama'].'</tr>
	  <tr><td>Tanggal Lahir</td><td>:</td><td colspan="3">'.$metserf['tgl_lahir'].'</tr>
	  <tr><td>Jenis Asuransi</td><td>:</td><td colspan="3">Asuransi Jiwa Kredit - Manfaat '.$met_serf_polis['typeRate'].'</tr>
	  <tr><td>Uang Pertanggungan Awal</td><td>:</td><td width="1%">Rp</td><td width="10%" align="right">'.duit($metserf['kredit_jumlah']).'</td><td>&nbsp;</td></tr>
	  <tr><td>Premi</td><td>:</td><td width="1%">Rp</td><td width="10%" align="right">'.duit($metserf['premi']).'</td><td>&nbsp;</td></tr>
	  <tr><td>Biaya Administrasi</td><td>:</td><td width="1%">Rp</td><td width="10%" align="right">'.duit($met_serf_polis['adm_fee']).'</td><td>&nbsp;</td></tr>
	  <tr><td>TotalPremi</td><td>:</td><td width="1%"><b>Rp</b></td><td width="10%" align="right"><b>'.duit($totalSerf).'</b></td><td>&nbsp;</td></tr>
	  <tr><td>Masa Asuransi</td><td>:</td><td colspan="3">'.$metserf['kredit_tenor'].' bulan</tr>
	  <tr><td>Masa Berlaku</td><td>:</td><td colspan="3">'.$metserf['kredit_tgl'].' s/d '.$metserf['kredit_akhir'].'</tr>
	  <tr><td colspan="5">Perjanjian Asuransi Jiwa ini tunduk kepada syarat-syarat umum dan ketentuan lainnya yang tertera pada Polis Induk No. '.$met_serf_polis['nopol'].' '.$met_serf_cost['name'].'. - '.$met_serf_cost['address'].' '.$met_serf_cost['city'].' '.$met_serf_cost['postcode'].'</td></tr>
	  <tr><td colspan="5"><br />Jakarta, '.$futgl.'</td></tr>
	  <tr><td colspan="5"><b>PT. ASURANSI JIWA RECAPITAL</b></td></tr>
	  <tr><td colspan="5"><img src="image/ttd_Cepi.jpg" width="150"><br /><b><u>Cevi Sudarto</u></b></td></tr>
	  <tr><td colspan="5">Direktur Utama</td></tr>
	  </table>';
}
if (!$id){
echo "<script language=javascript>
		function printWindow() {
		bV = parseInt(navigator.appVersion);
		if (bV >= 5) window.print();}
		printWindow();
		</script>";
}
	;
	break;

case "ratioklaim":
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
	HeaderingExcel('Data_analisa-'.$_REQUEST['tanggal1'].'-'.$_REQUEST['tanggal2'].'.xls');

	// membuat workbook baru
	$workbook = new Workbook("");
	$worksheet1 =& $workbook->add_worksheet('Data Analisa');

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
	$worksheet1->set_column(0, 1, 12);	$worksheet1->write_string(0, 1, "PERUSAHAAN", $format);
	$worksheet1->set_column(0, 2, 12);	$worksheet1->write_string(0, 2, "POLIS", $format);
	$worksheet1->set_column(0, 3, 10);	$worksheet1->write_string(0, 3, "ID PESERTA", $format);
	$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "NAMA", $format);
	$worksheet1->set_column(0, 5, 30);	$worksheet1->write_string(0, 5, "MULAI KREDIT", $format);
	$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "TENOR", $format);
	$worksheet1->set_column(0, 7, 5);	$worksheet1->write_string(0, 7, "AKHIR KREDIT", $format);
	$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "TOTAL PREMI", $format);
	$worksheet1->set_column(0, 9, 20);	$worksheet1->write_string(0, 9, "STATUS", $format);
	$worksheet1->set_column(0, 10, 15);	$worksheet1->write_string(0, 10, "TYPE", $format);
	$worksheet1->set_column(0, 11, 10);	$worksheet1->write_string(0, 11, "TGL REFUND", $format);

	// membuat header file excel dan nama filenya

$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tgl_1 = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tgl_2 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';	}
if ($_REQUEST['tanggal1'])					{	$dua = 'AND fu_ajk_peserta.vkredit_tgl BETWEEN "'.$tgl_1.'" AND "'.$tgl_2.'" ';	}

$metratioclaim = mysql_query('SELECT fu_ajk_peserta.id,
									fu_ajk_peserta.id_cost,
									fu_ajk_peserta.id_polis,
									fu_ajk_peserta.id_dn,
									fu_ajk_peserta.id_klaim,
									fu_ajk_peserta.id_peserta,
									fu_ajk_peserta.nama,
									fu_ajk_peserta.kredit_tgl,
									fu_ajk_peserta.vkredit_tgl,
									fu_ajk_peserta.kredit_tenor,
									fu_ajk_peserta.kredit_akhir,
									fu_ajk_peserta.vkredit_akhir,
									fu_ajk_peserta.totalpremi,
									fu_ajk_peserta.status_aktif,
									fu_ajk_peserta.status_peserta,
									fu_ajk_peserta.del
									FROM fu_ajk_peserta
									WHERE fu_ajk_peserta.id !="" '.$satu.' '.$dua.' AND fu_ajk_peserta.status_aktif !="Batal" AND fu_ajk_peserta.status_aktif !="Pending" AND fu_ajk_peserta.del IS NULL ORDER BY fu_ajk_peserta.status_aktif ASC');
$baris = 1;
while ($metratioclaim_ = mysql_fetch_array($metratioclaim)) {
	$met__1 = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$metratioclaim_['id_cost'].'"'));
	$met__2 = mysql_fetch_array(mysql_query('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$metratioclaim_['id_polis'].'"'));
	$met__3 = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_nopol, id_peserta, id_cn, tgl_createcn FROM fu_ajk_cn WHERE id_cost="'.$metratioclaim_['id_cost'].'" AND id_nopol="'.$metratioclaim_['id_polis'].'" AND id_cn="'.$metratioclaim_['id_klaim'].'" AND id_peserta="'.$metratioclaim_['id_peserta'].'"'));
	$worksheet1->write_string($baris, 0, ++$no);
	$worksheet1->write_string($baris, 1, $met__1['name']);
	$worksheet1->write_string($baris, 2, $met__2['nopol']);
	$worksheet1->write_string($baris, 3, $metratioclaim_['id_peserta']);
	$worksheet1->write_string($baris, 4, $metratioclaim_['nama']);
	$worksheet1->write_string($baris, 5, $metratioclaim_['vkredit_tgl']);
	$worksheet1->write_string($baris, 6, $metratioclaim_['kredit_tenor']);
	$worksheet1->write_string($baris, 7, $metratioclaim_['vkredit_akhir']);
	$worksheet1->write_string($baris, 8, $metratioclaim_['totalpremi']);
	$worksheet1->write_string($baris, 9, $metratioclaim_['status_aktif']);
	$worksheet1->write_string($baris, 10, $metratioclaim_['status_peserta']);
	$worksheet1->write_string($baris, 11, $met__3['tgl_createcn']);
	$baris++;
}
$workbook->close();
	;
	break;



	default:
		;
} // switch
function ceiling($number, $significance = 1)
{	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;	}
?>
<style type="text/css">
#table-1 {	font: 11px/24px Verdana, Arial, Helvetica, sans-serif;	border-collapse: collapse;	width: 100%;	}
#table-1 th {	padding: 0 0.5em;	text-align: left;	}
#table-1 tr.yellow td {	border-top: 1px solid #FB7A31;	border-bottom: 1px solid #FB7A31;	background: #FFC;	font-weight: bold;	text-align: center;	}
#table-1 tr.yellowtotal td {	border-top: 1px solid #FB7A31;	border-bottom: 1px solid #FB7A31;	background: #FFC;	font-weight: bold;	}
#table-1 td {	border-bottom: 1px solid #CCC;	padding: 0 0.5em;	}
#table-1 td+td {	border-left: 1px solid #CCC;	}

#table-2 {	font: 11px/24px Verdana, Arial, Helvetica, sans-serif;	border-collapse: collapse;	width: 100%;	}
#table-2 th {	padding: 0 0.5em;	text-align: center;	background: #e6e6e6;	}
#table-2 tr.gray td {	border-top: 1px solid #FB7A31;	border-bottom: 1px solid #FB7A31;	background: #FFC;	font-weight: bold;	text-align: center;	}
#table-2 td {	border-bottom: 0px solid #CCC;	padding: 0 0.5em;	}

.small {	font: 9px Verdana, Arial, Helvetica, sans-serif;	}
.titleprm {	font: 10px Verdana, Arial, Helvetica, sans-serif; font-weight: bold;	}
.smalltotal {	font: 9px Verdana, Arial, Helvetica, sans-serif; font-weight: bold;	}
</style>
