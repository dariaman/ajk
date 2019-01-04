<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("fpdf.php");
include_once ("includes/fu6106.php");
$futgl = date("d M Y");
switch ($_REQUEST['fu']) {
	case "ajkpdfinvdn":
$keyb = mysql_fetch_array(mysql_query('SELECT id, id_cost, nm_user FROM pengguna WHERE id="'.$_REQUEST['s'].'"'));
		$pdf=new FPDF('P','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$pdf->Image('image/logo_recapitalife.png',10,20);
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
$cekme = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$jumlah = $cekme['j_dl'] + 1;
$dlupdate = mysql_query('UPDATE fu_ajk_dn SET j_dl="'.$jumlah.'" WHERE id="'.$_REQUEST['id'].'"');
$kenalohh = 'USER : '.$keyb['nm_user'].'| Cost : '.$metcost['name'].'| DN : '. $metdnnya['dn_kode'] .'| Datetime : '. $datelog.' '.$timelog.'| IP : '. $alamat_ip.'| Nama Komputer : '.$nama_host.'| Browser : '.$useragent;
$dlkey = mysql_query('INSERT INTO ajk_dl_logger SET dl_data="'.$kenalohh.'"');

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
		$pdf->SetFont('Arial','',10);	$pdf->Text(10,65, 'Jenis Asuransi');	$pdf->Text(40,65, ': Asuransi Jiwa Kredit-'.$metmaster['msdesc']);
		$pdf->SetFont('Arial','',10);	$pdf->Text(135,55, 'Tanggal Efektif Polis');	$pdf->Text(177,55, ': '._convertDate($metpolis['start_date']));
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
		$pdf->cell(15,6,'Premi',1,0,'C',1);
		$pdf->cell(10,6,'Disc',1,0,'C',1);
		$pdf->cell(14,6,'Ex Premi',1,0,'C',1);
		$pdf->cell(15,6,'Adm',1,0,'C',1);
		$pdf->cell(15,6,'Nilai DN',1,0,'C',1);


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
	$cell[$i][0] = $metpeserta['id_peserta'];
	$cell[$i][1] = $ceknamanya;
	$cell[$i][2] = $metpeserta['tgl_lahir'];
	$cell[$i][3] = $umur;
	$cell[$i][4] = $metpeserta['kredit_tgl'];
	$cell[$i][5] = $metpeserta['kredit_tenor'];
	$cell[$i][6] = $tanggalplus;
	$cell[$i][7] = duit($metpeserta['kredit_jumlah']);
	$cell[$i][8] = duit($metpeserta['premi']);
	$cell[$i][9] = duit($metpeserta['disc_premi']);
	$cell[$i][10] = duit($metpeserta['ext_premi']);
	$cell[$i][11] = duit($metpeserta['biaya_adm']);
	$cell[$i][12] = duit($metpeserta['totalpremi']);
	$i++;
	$totalUp  += $metpeserta['kredit_jumlah'];
	$totalStd += $metpeserta['premi'];
	$totalDisc += $metpeserta['disc_premi'];
	$totalExt += $metpeserta['ext_premi'];
	$totalAdm += $metpeserta['biaya_adm'];
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
			$pdf->cell(15,6,$cell[$j][8],1,0,'R');
			$pdf->cell(10,6,$cell[$j][9],1,0,'R');
			$pdf->cell(14,6,$cell[$j][10],1,0,'R');
			$pdf->cell(15,6,$cell[$j][11],1,0,'R');
			$pdf->cell(15,6,$cell[$j][12],1,0,'R');
			$pdf->Ln();
		}
		//$pdf->setXY(10,$y + $i);
		$pdf->setFont('Arial','B',8);
		$pdf->cell(110,6,'Total',1,0,'C');
		$pdf->cell(20,6,duit($totalUp),1,0,'R');
		$pdf->cell(15,6,duit($totalStd),1,0,'R');
		$pdf->cell(10,6,duit($totalDisc),1,0,'R');
		$pdf->cell(14,6,duit($totalExt),1,0,'R');
		$pdf->cell(15,6,duit($totalAdm),1,0,'R');
		$pdf->cell(15,6,duit($totalTPremi),1,0,'R');

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

			$cekdncn = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdnnya['dn_kode'].'" AND id_cost="'.$metdnnya['id_cost'].'" AND del IS NULL');

			while ($metdncnnya = mysql_fetch_array($cekdncn)) {
				if ($metdncnnya['type_claim']=="Batal") {
					$namapesertacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metdncnnya['id_cn'].'" AND id_peserta="'.$metdncnnya['id_peserta'].'"'));
				}else{
					$namapesertacn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metdncnnya['id_cn'].'" AND id_cost="'.$metdnnya['id_cost'].'"'));
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
		    PT. Asuransi Jiwa Recapital
		    '.$metpolis['bank_name'].' - '.$metpolis['bank_branch'].' - No. Rek. '.$metpolis['bank_accNo'].'', 0);
		$pdf->MultiCell(180, 5, '2. Mohon tidak melakukan pembayaran secara tunai', 0);
		$pdf->MultiCell(180, 5, '3. Biaya yang timbul dari proses transfer yang dilakukan harus ditanggung oleh Pemegang Polis', 0);
		$pdf->MultiCell(180, 5, '4. Mohon mencantumkan keterangan Pembayaran Nota Debet No 13.01.02414 pada slip pembayaran pada saat melakukan transfer.', 0);
		$pdf->MultiCell(180, 5, '5. Apabila ada pertanyaan lebih lanjut, mohon untuk dapat menghubungi kami di No. Telepon : 021-725 6272, No. Fax : 021-7253858', 0);
		//PARAF
		$pdf->setFont('Arial','B',10);
		$pdf->cell(140,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,'Jakarta, '.date("d F Y",strtotime($metdnnya['tgl_createdn'])).'', 0, 0, 'L');
		$pdf->Ln();
		$pdf->Image('image/ttd_andress.jpg',155);
		//	$pdf->Ln();
		$pdf->cell(140,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,'Andress Manansal', 0, 0, 'L');
		$pdf->Ln();
		$pdf->cell(140,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,'KADIV TEKNIK', 0, 0, 'L');
		$pdf->Output();
//$fp=fopen('ajk_file/'.$name,'wb');
//fwrite($fp,$pdfcode);
//fclose($fp);
	;
	break;

	case "ajkpdfdn":
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
//$pdf->SetMargins(1.5,1,1.5);
// menyisipkan image pada posisi 20 mm mendatar, 30 mm vertikal


$pdf->Image('image/logo_recapitalife.png',10,10);
$pdf->Image('image/ttd_andress.jpg',150,110);
$pdf->SetFont('Arial','B',14);
$pdf->Text(90, 30,'DEBIT NOTE');


$a = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$peserta = mysql_query('SELECT * FROM v_fu_ajk_peserta WHERE id_dn="'.$a['dn_kode'].'"');
while ($metpeserta = mysql_fetch_array($peserta)) {
$totalpremi +=$metpeserta['premi'];
}
$p = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$a['id_nopol'].'"'));
$pdf->SetFont('Arial','',8);	$pdf->Text(10,41, 'Nomor Polis :');
$pdf->SetFont('Arial','B',8);	$pdf->Text(29,41, $p['nopol']);

$c = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$a['id_cost'].'"'));
$pdf->SetFont('Arial','B',8);	$pdf->Text(10,45, $c['name']);
$pdf->SetFont('Arial','B',8);	$pdf->Text(10,48, $a['id_cabang']);

$pdf->SetFont('Arial','',8);	$pdf->Text(115,41,'Nomor DN                   : ');
$pdf->SetFont('Arial','B',8);	$pdf->Text(145,41, $a['dn_kode']);

$pdf->SetFont('Arial','',8);	$pdf->Text(115,45,'Tanggal                       : ');
$pdf->SetFont('Arial','B',8);	$pdf->Text(145,45, _convertDate($a['tgl_dn']));

$tanggalawal=$a['tgl_dn'];
$tanggalplus=date('m-Y-d',strtotime($tanggalawal."+7 day"));
$tanggalexp = explode("-", $tanggalplus);
$tanggaljt = $tanggalexp[2].'-'.$tanggalexp[0].'-'.$tanggalexp[1];
$pdf->SetFont('Arial','',8);	$pdf->Text(115,49,'Tanggal Jatuh Tempo : ');
$pdf->SetFont('Arial','B',8);	$pdf->Text(145,49, $tanggaljt);

		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');
		$pdf->MultiCell(0,5, '');

$pdf->SetFont('Arial','B',8);	$pdf->Text(10,58,'Tagihan Premi');
$pdf->SetFont('Arial','B',8);	$pdf->Text(135,58,'Rp. ');
$pdf->SetFont('Arial','B',8);	$pdf->Cell(165,5,duit($totalpremi),0,1,'R');

$pdf->SetFont('Arial','B',8);	$pdf->Text(10,63,'Biaya Admin');
$pdf->SetFont('Arial','B',8);	$pdf->Text(135,63,'Rp.');
$pdf->SetFont('Arial','B',8);	$pdf->Cell(165,5,duit($c['adminfee']),0,1,'R');

$pdf->SetFont('Arial','B',8);	$pdf->Text(10,68,'Biaya Polis');
$pdf->SetFont('Arial','B',8);	$pdf->Text(135,68,'Rp.');
$pdf->SetFont('Arial','B',8);	$pdf->Cell(165,5,duit($c['bpolis']),0,1,'R');

$pdf->SetFont('Arial','B',8);	$pdf->Text(10,73,'Biaya Materai');
$pdf->SetFont('Arial','B',8);	$pdf->Text(135,73,'Rp.');
$pdf->SetFont('Arial','B',8);	$pdf->Cell(165,5,duit($c['bmaterai']),0,1,'R');

$total = $totalpremi + $c['adminfee'] + $c['bpolis'] + $c['bmaterai'];
$pdf->SetFont('Arial','B',8);	$pdf->Text(115,80,'Total');
$pdf->SetFont('Arial','B',8);	$pdf->Text(135,80,'Rp.');
$pdf->SetFont('Arial','B',10);	$pdf->Cell(165,9,duit($total),0,1,'R');

$pdf->SetFont('Arial','',8);	$pdf->Text(10,80,'Debitur');
$pdf->SetFont('Arial','',8);	$pdf->Text(40,80, $a['id_cabang']);

$pdf->SetFont('Arial','',8);	$pdf->Text(10,85, 'Tanggal Efektif Polis');
$pdf->SetFont('Arial','',8);	$pdf->Text(40,85, _convertDate($p['effdate']));

if ($p['benefit']=="D") {	$typeins = 'Manfaat Menurun';	}	else	{	$typeins = 'Manfaat Tetap';	}
$pdf->SetFont('Arial','',8);	$pdf->Text(10,90, 'Jenis Asuransi');
$pdf->SetFont('Arial','',8);	$pdf->Text(40,90, 'Asuransi Jiwa Kredit - '.$typeins);

$pdf->SetFont('Arial','',8);	$pdf->Text(115,85,'Terbilang :');
$pdf->SetFont('Arial','',8);	$pdf->Text(128,85, mametbilang($total).'rupiah');

//$pdf->MultiCell(60, 8, 'dasds. ', 1, 'R');

$pdf->MultiCell(90, 15, '', 0, 'R');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(115, 5, '1. Mohon tagihan ini diteliti kembali dan akan dianggap benar jika tidak ada pemberitahuan keberatan terhadap tagihan ini dalam waktu 5 (lima) hari kalender.
2. Transfer melalui Bank ke rekening :
	PT. Asuransi Jiwa Recapital
	BCA - Cabang Wisma BCA - No. Rekening 035-309041-8 (IDR)
3. Mohon tidak melakukan pembayaran secara tunai
4. Biaya yang timbul dari proses transfer yang dilakukan harus ditanggung oleh Pemegang Polis
5. Mohon mencantumkan keterangan Pembayaran Nota Debet No 13.01.02414 pada slip pembayaran pada saat melakukan transfer.
6. Apabila ada pertanyaan lebih lanjut, mohon untuk dapat menghubungi kami di No. Telepon : 021 - 725 6272, No. Fax : 021 - 7253858 ', 0);

$pdf->SetFont('Arial','B',8);	$pdf->Text(140,110,'PT. ASURANSI JIWA RECAPITAL');
$pdf->SetFont('Arial','U',8);	$pdf->Text(145,130,'Andreas Manansang AAIJ');
$pdf->SetFont('Arial','',8);	$pdf->Text(153,133,'KADIV TEKNIK');
$pdf->SetFont('Arial','B',8);	$pdf->Text(70,165,'Bukan Merupakan Bukti Pembayaran');

$pdf->Output();
	;
		break;
	case "ajkpdfm":
$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();

$pdf->Image('image/logo_recapitalife.png',10,10);
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
$peserta = mysql_query('SELECT * FROM v_fu_ajk_peserta WHERE id_dn = "'.$dnnya['dn_kode'].'"');
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
$pdf->Image('image/ttd_andress.jpg',225,$metparaf);
$pdf->setFont('Arial','U',10);
$pdf->setXY(220,$metnama); $pdf->cell(30,6,'Andreas Manansal');
$pdf->setFont('Arial','',10);
$pdf->setXY(223,$metbag); $pdf->cell(30,6,'KADIV TEKNIK');

$pdf->Output();
	;
	break;
	case "ajkpdfcn":
		$pdf=new FPDF('P','mm','A4');
		$pdf->AddPage();
		$pdf->Image('image/logo_recapitalife.png',1,20);
		$pdf->SetFont('Arial','B',14);
		$pdf->Text(90, 30,'CREDIT NOTE');

		$metcnnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$_REQUEST['id_cn'].'"'));
		$mettgldn = explode(" ", $metcnnya['input_time']);
		$metcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$metcnnya['id_cost'].'"'));
		$metpolis = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$metcnnya['id_nopol'].'"'));
		$metmaster = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_master WHERE mscode="'.$metpolis['benefit_type'].'"'));
		$emetpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metcnnya['id_cn'].'"'));	//PESERTA LAMA
		//$cekpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="" AND nama="'.$metpeserta['nama'].'" AND tgl_lahir="'.$metpeserta['tgl_lahir'].'" AND status_peserta!=""'));	//PESERTA BARU
		$cekpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_peserta="'.$metcnnya['id_peserta'].'" AND id_cost="'.$metcnnya['id_cost'].'"'));	//PESERTA BARU

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
		$pdf->Output();
		$pdf->Output('ajk_file/cn/'.$name,"F");
		;
		break;
	case "pri":

$mamet = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
$cost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
$m = mysql_query('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$mamet['id'].'"');
while ($me = mysql_fetch_array($m)) {	$jdn += $me['totalpremi'];	}	$a =$jdn;
$juml = $mamet['jumlah'] - $a;
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr><td align="center"><img src="image/logo_recapitalife.png" width="150"></td><td align="center" valign="bottom"><font size="6"><b>REPORT PRM</b></font></td></tr>
	  <tr><td width="15%">Company Name</td><td>: <b>'.$cost['name'].'</b></td></tr>
	  <tr><td>Reg. PRM</td><td>: <b>'.$mamet['id_prm'].'</b></td></tr>
	  <tr><td>Amount</td><td>: '.duit($mamet['jumlah']).'</td></tr>
	  <tr><td>Used Payment</td><td>: '.duit($a).'</td></tr>
	  <tr><td>Remaining Payment</td><td>: <font color="red">'.duit($juml).'</font></td></tr>
	  </table>
	  <table id="table-1" border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr class="yellow"><td width="5%">No</td><td>Reg. DN</td><td width="15%">Date</td><td width="15%">Total</td><td width="20%">Branch</td></tr>';
$metdn = mysql_query('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$mamet['id'].'"');
while ($fudn = mysql_fetch_array($metdn)){
echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$fudn['dn_kode'].'</td>
		  <td align="center">'.$fudn['tgl_dn_paid'].'</td>
		  <td align="right">'.duit($fudn['totalpremi']).'</td>
		  <td>'.$fudn['id_cabang'].'</td>
	  </tr>';
$tmamet +=$fudn['totalpremi'];
}
echo '<tr class="yellowtotal"><td colspan="3" align="center">Total</td><td align="right">'.duit($tmamet).'</td><td></td></table>';
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
	case "ajkpdfAll":
//$pdf=new FPDF('P','mm','A4');
//$pdf->AddPage();
if ($_REQUEST['rdns']!='' AND $_REQUEST['rdne']!='')	{	$satu= 'AND tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';	}
if ($_REQUEST['rpays']!='' AND $_REQUEST['rpaye']!='')	{	$dua= 'AND tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';	}
if ($_REQUEST['rstat'])									{	$tiga = 'AND dn_status LIKE "%' .$_REQUEST['rstat'] . '%"';	}
if ($_REQUEST['rreg'])									{	$empat = 'AND id_regional LIKE "%' .  $_REQUEST['rreg'] . '%"';		}
if ($_REQUEST['rcabang'])								{	$lima = 'AND id_cabang LIKE "%' . $_REQUEST['rcabang'] . '%"';		}
if ($_REQUEST['dns'])									{	$enam = 'AND dn_kode BETWEEN \''.$_REQUEST['dns'].'\' AND \''.$_REQUEST['dne'].'\'';		}

$el = mysql_query('SELECT * FROM fu_ajk_dn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'');
while ($ell = mysql_fetch_array($el)) {
$a .=$ell['dn_kode'];
}
function zipFilesAndDownload($file_names,$archive_file_name,$file_path){
	$zip = new ZipArchive();
	//create the file and throw the error if unsuccessful
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
		exit("cannot open <$archive_file_name>\n");
	}
	//add each files of $file_name array to archive
	foreach($a as $files)	{
		$zip->addFile($file_path.$files,$files);
	}
	$zip->close();
	$zipped_size = filesize($archive_file_name);
	header("Content-Description: File Transfer");
	header("Content-type: application/zip");
	header("Content-Type: application/force-download");// some browsers need this
	header("Content-Disposition: attachment; filename=$archive_file_name");
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header("Content-Length:". " $zipped_size");
	ob_clean();
	flush();
	readfile("$archive_file_name");
	unlink("$archive_file_name"); // Now delete the temp file (some servers need this option)
	exit;
}
if(isset($_POST['formSubmit'])) {
	//$file_names=$_POST['items'];// Always sanitize your submitted data!!!!!!
	//$file_names = filter_var_array($_POST['items']);//works but it's the wrong method
	$filter = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ;
	$file_names = $filter['ajkpdf'] ;
	//Archive name
	$archive_file_name='DEMO-archive.zip';
	//Download Files path
	$file_path= getcwd(). '/ajk_file/';
	//cal the function
	zipFilesAndDownload($a,$archive_file_name,$file_path);
} else {

	header("Refresh: 5; url= ./index.php ");
print '<h1 style="text-align:center">You you shouldn\'t be here ......</pre>
	<p style="color: red;"><strong>redirection in 5 seconds</strong></p>
	<pre>';

	exit;
}
		;
		break;

	default:
		;
} // switch
function ceiling($number, $significance = 1)
{	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;	}
?>
<style type="text/css">
#table-1 {
	font: 11px/24px Verdana, Arial, Helvetica, sans-serif;
	border-collapse: collapse;
	width: 100%;
	}

#table-1 th {
	padding: 0 0.5em;
	text-align: left;
	}

#table-1 tr.yellow td {
	border-top: 1px solid #FB7A31;
	border-bottom: 1px solid #FB7A31;
	background: #FFC;
	font-weight: bold;
	text-align: center;
	}

#table-1 tr.yellowtotal td {
	border-top: 1px solid #FB7A31;
	border-bottom: 1px solid #FB7A31;
	background: #FFC;
	font-weight: bold;
	}

#table-1 td {
	border-bottom: 1px solid #CCC;
	padding: 0 0.5em;
	}

#table-1 td+td {
	border-left: 1px solid #CCC;
	}
</style>