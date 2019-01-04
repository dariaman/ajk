<?php
error_reporting(0);
session_start();
require('../fpdf.php');
include "../includes/fu6106.php";
function bulanindo($bulan){
	if($bulan=='01'){
		$ls_namabulan =  'Januari';
	}elseif($bulan=='02'){
		$ls_namabulan =  'Februari';
	}elseif($bulan=='03'){
		$ls_namabulan =  'Maret';
	}elseif($bulan=='04'){
		$ls_namabulan =  'April';
	}elseif($bulan=='05'){
		$ls_namabulan =  'Mei';
	}elseif($bulan=='06'){
		$ls_namabulan =  'Juni';
	}elseif($bulan=='07'){
		$ls_namabulan =  'Juli';
	}elseif($bulan=='08'){
		$ls_namabulan =  'Agustus';
	}elseif($bulan=='09'){
		$ls_namabulan =  'September';
	}elseif($bulan=='10'){
		$ls_namabulan =  'Oktober';
	}elseif($bulan=='11'){
		$ls_namabulan =  'November';
	}elseif($bulan=='12'){
		$ls_namabulan =  'Desember';
	}
	return $ls_namabulan;
}
switch ($_REQUEST['er']) {
case "klaim_share":

	$pdf = new FPDF('P');
	$pdf->AddPage();
	$pdf->AliasNbPages();


	// To be implemented in your own inherited class
	$pdf->SetFont('Arial','I',10);								// setting properti font
	$pdf->Cell(180,10,'Aplikasi AJK ONLINE',0,0,'R');			// menulis header
	//$this->Cell(0);	 										// membuat jarak terhadap cell sebelumnya
	$pdf->Ln(0);
	$pdf->SetFont('Arial','B',10);								// setting properti font
	$pdf->Cell(190,0,'PT. Adonai Pialang Asuransi',0,0,'R');
	//$this->Line(11,13,198,13);								// membuat garis dari koordinat (11 mm, 18 mm) sampai koordinat (198 mm,18 mm)
	$pdf->Ln(10);

	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];

	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'PERBANDINGAN SHARE ASURANSI',0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','I',8);
	$pdf->Cell(0,0,'Tanggal '.date("d").' '.bulanindo(date("m")).' '.date("Y"),0,0,'L');
	$pdf->ln(10);
	$pdf->SetFont('Times','',9);
	if($_REQUEST['prod']!=="all"){
		$prod=" and aa.id_polis=".$_REQUEST['prod'];
	}
	$sqlku="select
			fu_ajk_costumer.`name`,
			fu_ajk_asuransi.`name` as nama_asuransi,
			fu_ajk_polis.nmproduk,
			aa.tahun_uw,
			aa.jml_debitur,
			aa.total_premi,
			bb.jml_klaim_uw,
			bb.total_klaim_uw,
			cc.jml_klaim,
			cc.total_klaim_dol
			 from
			(SELECT
			fu_ajk_peserta.id_cost,
			fu_ajk_peserta.id_polis,
			fu_ajk_dn.id_as,
			DATE_FORMAT(fu_ajk_peserta.kredit_tgl,'%Y') as tahun_uw,
			count(fu_ajk_peserta.id) as jml_debitur,
			sum(fu_ajk_peserta.totalpremi) as total_premi
			FROM
			fu_ajk_peserta
			INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn=fu_ajk_dn.id
			where fu_ajk_peserta.del is null and fu_ajk_peserta.id_cost<>''
			group by
			fu_ajk_peserta.id_cost,
			fu_ajk_peserta.id_polis,
			fu_ajk_dn.id_as,
			DATE_FORMAT(fu_ajk_peserta.kredit_tgl,'%Y')
			)aa
			LEFT JOIN
			(SELECT
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_nopol as id_polis,
			fu_ajk_dn.id_as,
			DATE_FORMAT(fu_ajk_cn.tgl_claim,'%Y') as tahun_klaim_uw,
			count(fu_ajk_cn.id) as jml_klaim_uw,
			sum(fu_ajk_cn.total_claim) as total_klaim_uw
			FROM
			fu_ajk_peserta
			INNER JOIN fu_ajk_cn ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			where fu_ajk_cn.type_claim = 'Death' AND
			fu_ajk_cn.del IS NULL
			group by
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_nopol,
			fu_ajk_dn.id_as,
			DATE_FORMAT(fu_ajk_cn.tgl_claim,'%Y')) bb on
			aa.tahun_uw=bb.tahun_klaim_uw and
			aa.id_polis=bb.id_polis and aa.id_cost=bb.id_cost and aa.id_as=bb.id_as

			LEFT JOIN
			(SELECT
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_nopol as id_polis,
			fu_ajk_dn.id_as,
			DATE_FORMAT(fu_ajk_peserta.kredit_tgl,'%Y') as tahun_uw,
			count(fu_ajk_cn.id) as jml_klaim,
			sum(fu_ajk_cn.total_claim) as total_klaim_dol
			FROM
			fu_ajk_peserta
			INNER JOIN fu_ajk_cn ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			where fu_ajk_cn.type_claim = 'Death' AND
			fu_ajk_cn.del IS NULL
			GROUP BY DATE_FORMAT(fu_ajk_peserta.kredit_tgl,'%Y'),
			fu_ajk_peserta.id_cost,
			fu_ajk_peserta.id_polis,
			fu_ajk_dn.id_as
			) cc on cc.tahun_uw=aa.tahun_uw and cc.id_polis=aa.id_polis and aa.id_cost=cc.id_cost and aa.id_as=cc.id_as

			INNER JOIN fu_ajk_polis on aa.id_polis=fu_ajk_polis.id
			INNER JOIN fu_ajk_costumer on aa.id_cost=fu_ajk_costumer.id
			INNER JOIN fu_ajk_asuransi on aa.id_as=fu_ajk_asuransi.id
						where aa.id_cost=".$_REQUEST['id_cost']." ".$prod."
			order by
			fu_ajk_costumer.`name`,
			fu_ajk_asuransi.`name`,
			fu_ajk_polis.nmproduk,
			aa.tahun_uw";

	$asuransi='';
	$produk='';
	$pdf->SetFont('Times','B',7);

	$querypemkes = mysql_query($sqlku);
	while($rows = mysql_fetch_array($querypemkes)){


		if($asuransi !== $rows['nama_asuransi']){
			if($produk!=""){
				$pdf->ln(1);
				$pdf->SetX(10);
				$pdf->Cell(15,5,"TOTAL",0,0,'C');

				$pdf->SetX(25);
				$pdf->Cell(30,5,number_format($jml_debitur),1,0,'R');
				$pdf->SetX(55);
				$pdf->Cell(30,5,number_format($total_premi,2),1,0,'R');
				$pdf->SetX(85);
				$pdf->Cell(30,5,number_format($jml_klaim_uw),1,0,'R');
				$pdf->SetX(115);
				$pdf->Cell(30,5,number_format($total_klaim_uw,2),1,0,'R');
				$pdf->SetX(145);
				$pdf->Cell(30,5,number_format($total_klaim),1,0,'R');
				$pdf->SetX(175);
				$pdf->Cell(30,5,number_format($total_klaim_dol,2),1,0,'R');
				$pdf->ln();

			}
			$pdf->ln();
			$asuransi=$rows['nama_asuransi'];

			$pdf->SetFont('Times','B',7);


			$pdf->SetX(10);
			$pdf->Cell(190,5,$asuransi,0,0,'L');
			$pdf->ln();

			$produk='';
		}

		if($produk<>$rows['nmproduk']){
			if($produk!=""){
				$pdf->ln(1);
				$pdf->SetX(10);
				$pdf->Cell(15,5,"TOTAL",0,0,'C');

				$pdf->SetX(25);
				$pdf->Cell(30,5,number_format($jml_debitur),1,0,'R');
				$pdf->SetX(55);
				$pdf->Cell(30,5,number_format($total_premi,2),1,0,'R');
				$pdf->SetX(85);
				$pdf->Cell(30,5,number_format($jml_klaim_uw),1,0,'R');
				$pdf->SetX(115);
				$pdf->Cell(30,5,number_format($total_klaim_uw,2),1,0,'R');
				$pdf->SetX(145);
				$pdf->Cell(30,5,number_format($total_klaim),1,0,'R');
				$pdf->SetX(175);
				$pdf->Cell(30,5,number_format($total_klaim_dol,2),1,0,'R');
				$pdf->ln();

			}

			$produk=$rows['nmproduk'];

			$pdf->ln();
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(25);
			$pdf->Cell(180,5,$produk,1,0,'C');
			$pdf->ln();


			$pdf->SetX(25);
			$pdf->Cell(30,5,'Debitur',1,0,'C');
			$pdf->SetX(55);
			$pdf->Cell(30,5,'Premi',1,0,'C');
			$pdf->SetX(85);
			$pdf->Cell(30,5,'Jumlah Klaim U/W',1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(30,5,'Nilai Klaim U/W',1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(30,5,'Klaim Dol',1,0,'C');
			$pdf->SetX(175);
			$pdf->Cell(30,5,'Nilai Klaim Dol',1,0,'C');

			$jml_debitur=0;
			$total_premi=0;
			$jml_klaim_uw=0;
			$total_klaim_uw=0;
			$total_klaim=0;
			$total_klaim_dol=0;


			$pdf->ln();
		}
		$pdf->SetX(10);
		$pdf->Cell(15,5,$rows['tahun_uw'],1,0,'C');

		$pdf->SetX(25);
		$pdf->Cell(30,5,number_format($rows['jml_debitur']),1,0,'R');
		$pdf->SetX(55);
		$pdf->Cell(30,5,number_format($rows['total_premi'],2),1,0,'R');
		$pdf->SetX(85);
		$pdf->Cell(30,5,number_format($rows['jml_klaim_uw']),1,0,'R');
		$pdf->SetX(115);
		$pdf->Cell(30,5,number_format($rows['total_klaim_uw'],2),1,0,'R');
		$pdf->SetX(145);
		$pdf->Cell(30,5,number_format($rows['jml_klaim']),1,0,'R');
		$pdf->SetX(175);
		$pdf->Cell(30,5,number_format($rows['total_klaim_dol'],2),1,0,'R');

		$jml_debitur=$jml_debitur+$rows['jml_debitur'];
		$total_premi=$total_premi+$rows['total_premi'];
		$jml_klaim_uw=$jml_klaim_uw+$rows['jml_klaim_uw'];
		$total_klaim_uw=$total_klaim_uw+$rows['total_klaim_uw'];
		$total_klaim=$total_klaim+$rows['jml_klaim'];
		$total_klaim_dol=$total_klaim_dol+$rows['total_klaim_dol'];


		$pdf->ln();
	}


	$pdf->SetFont('Times','',7);
	$pdf->Output();
	break;
case "klaim_share1" :

	
		$pdf = new FPDF('L');
		$pdf->AddPage();
		$pdf->AliasNbPages();
	
	
		// To be implemented in your own inherited class
		$pdf->SetFont('Arial','I',10);								// setting properti font
		$pdf->Cell(180,10,'Aplikasi AJK ONLINE',0,0,'R');			// menulis header
		//$this->Cell(0);	 										// membuat jarak terhadap cell sebelumnya
		$pdf->Ln(0);
		$pdf->SetFont('Arial','B',10);								// setting properti font
		$pdf->Cell(190,0,'PT. Adonai Pialang Asuransi',0,0,'R');
		//$this->Line(11,13,198,13);								// membuat garis dari koordinat (11 mm, 18 mm) sampai koordinat (198 mm,18 mm)
		$pdf->Ln(10);
	
		$li_idperserta = $_REQUEST['cat'];
		$ldt_tanggal1 = $_REQUEST['tgl1'];
		$ldt_tanggal2 = $_REQUEST['tgl2'];
	
		$pdf->SetFont('Times','IB',9);
		$pdf->SetX(10);
		$pdf->Cell(0,5,$ls_name,0,0,'L');
		$pdf->ln();
		$pdf->SetFont('Times','B',9);
		$pdf->SetX(5);
		$pdf->Cell(0,5,'KLAIM BERDASARKAN UNDERWRITING YEAR',0,0,'L');
		$pdf->ln(10);
		$pdf->SetFont('Times','',7);

		$rows=0;

		$pdf->SetX(5);
		$pdf->Cell(15,5,'THN AKAD',1,0,'C');
		for ($kol_tahun = $_GET['y_uw']; $kol_tahun <= date('Y'); $kol_tahun++) {
			if($rows<4){
				$row=$rows*65;
				$pdf->SetX(20+$row);
				$pdf->Cell(65,5,$kol_tahun,1,0,'C');
			}
			$rows++;
		}
		$rows=0;
		$pdf->ln();
		for ($kol_tahun = $_GET['y_uw']; $kol_tahun <= date('Y'); $kol_tahun++) {
			if($rows<4){
				$row=$rows*65;
				$pdf->SetX(20+$row);
				$pdf->Cell(20,5,'PREMI',1,0,'C');
				$pdf->SetX(40+$row);
				$pdf->Cell(20,5,'JML DEBITUR',1,0,'C');
				$pdf->SetX(60+$row);
				$pdf->Cell(25,5,'TUNTUTAN KLAIM',1,0,'C');
			}
			$rows++;
		}

		if($_REQUEST['prod']!=="all"){
			$prod=" and fu_ajk_peserta.id_polis=".$_REQUEST['prod'];
		}
		$pdf->ln();
		for ($kol_tahun = $_GET['y_uw']; $kol_tahun <= date('Y'); $kol_tahun++) {
			if($rows<4){

				$sqlku="SELECT
					YEAR(fu_ajk_peserta.kredit_tgl) AS uw_year,
					YEAR(fu_ajk_cn.tgl_claim) AS dol_year,
					COUNT(fu_ajk_peserta.id) AS jml,
					SUM(fu_ajk_peserta.`totalpremi`) AS totalpremi
					SUM(fu_ajk_cn.`tuntutan_klaim`) AS tuntutan_klaim
					FROM
					fu_ajk_cn
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					WHERE
					fu_ajk_cn.type_claim = 'Death' AND fu_ajk_cn.confirm_claim <> 'Pending' AND fu_ajk_cn.del IS NULL
					and YEAR(fu_ajk_peserta.kredit_tgl)=".$_GET['y_uw']."
					and YEAR(fu_ajk_cn.tgl_claim)=".$kol_tahun."
					GROUP BY
					YEAR(fu_ajk_peserta.kredit_tgl),
					YEAR(fu_ajk_cn.tgl_claim)
					ORDER BY
					YEAR(fu_ajk_peserta.kredit_tgl),
					YEAR(fu_ajk_cn.tgl_claim)
					";

				$querypemkes = mysql_query($sqlku);
				$hasil = mysql_fetch_array($querypemkes);
				
				
				$row=$rows*65;
				$pdf->SetX(20+$row);
				$pdf->Cell(20,5,$hasil['totalpremi'],1,0,'C');
				$pdf->SetX(40+$row);
				$pdf->Cell(20,5,$hasil['jml'],1,0,'C');
				$pdf->SetX(60+$row);
				$pdf->Cell(25,5,$hasil['tuntutan_klaim'],1,0,'C');
			}
			$rows++;
		}
		
		$asuransi='';
		$produk='';
		$pdf->SetFont('Times','B',7);
		
		
		$pdf->SetFont('Times','',7);
		$pdf->Output();
		break;
	case "setuju_as":

		$sqlku="SELECT
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.kredit_tgl,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_klaim.tgl_klaim,
				fu_ajk_klaim.tgl_kirim_dokumen,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_polis.nmproduk,
				fu_ajk_dn.totalpremi,
				fu_ajk_asuransi.code, fu_ajk_asuransi.name AS as_name, fu_ajk_asuransi.address, fu_ajk_asuransi.city, fu_ajk_asuransi.postcode
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id

				WHERE fu_ajk_cn.id =".$_REQUEST['id'];

		$hasilku=mysql_query($sqlku);
		$dataku=mysql_fetch_array($hasilku);



		$pdf = new FPDF('P','mm','A4');
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);

		$pdf->SetFont('Arial','',10);
		$pdf->SetX(160);
		$pdf->Cell(0,5,'Bekasi, '.date("d").' '. bulanindo(date("m")).' '.date("Y"),0,0,'L');

		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'No',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'____/KLM-REM/APA-'.strtoupper($dataku['code']).'/__/'.date('Y'),0,0,'L');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Lamp.',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'-',0,0,'L');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Perihal',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'Surat Persetujuan Pembayaran Klaim a.n. '.$dataku['nama'],0,0,'L');

		$pdf->ln();
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Kepada YTH.',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Yth. Bapak Sri Haryanto – General Manager Tehnik',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,strtoupper($dataku['as_name']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,$dataku['address'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,$dataku['city'].' '.$dataku['postcode'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Dengan hormat,',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Pertama-tama kami mengucapkan terima kasih atas kerjasama yang yang telah terjalin dengan sangat baik selama ini antara '.$dataku['as_name'].' dan PT. Adonai Pialang Asuransi (Adonai).');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Bersama ini kami mohon konfirmasi pembayaran klaim untuk debitur sebagai berikut :');
		$pdf->ln();


		$pdf->SetX(40);
		$pdf->Cell(0,5,'Nama Debitur',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,$dataku['nama'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Lahir',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['tgl_lahir'])).' '.bulanindo(date("m",strtotime($dataku['tgl_lahir']))).' '.date("Y",strtotime($dataku['tgl_lahir'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Akad',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['kredit_tgl'])).' '.bulanindo(date("m",strtotime($dataku['kredit_tgl']))).' '.date("Y",strtotime($dataku['kredit_tgl'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Plafond Kredit',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,'Rp. '.duit($dataku['kredit_jumlah']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tuntutan Klaim',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,'Rp. '.duit($dataku['tuntutan_klaim']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Meninggal Dunia',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['tgl_klaim'])).' '.bulanindo(date("m",strtotime($dataku['tgl_klaim']))).' '.date("Y",strtotime($dataku['tgl_klaim'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Program Asuransi',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,$dataku['nmproduk'],0,0,'L');
		$pdf->ln();

		$pdf->ln();
		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Mengingat dokumen klaim telah kami sampaikan kepada '.$dataku['as_name'].' per tanggal '.date("d",strtotime($dataku['tgl_kirim_dokumen'])).' '.bulanindo(date("m",strtotime($dataku['tgl_kirim_dokumen']))).' '.date("Y",strtotime($dataku['tgl_kirim_dokumen'])).' dengan lengkap dan benar. Sehingga berdasarkan Perjanjian yang telah disepakati bersama antara '.$dataku['code'].' dengan Adonai, bahwa dalam jangka waktu telah melewati 7 (tujuh) hari kalender klaim yang sudah disampaikan dokumennya secara lengkap dan benar namun tidak mendapatkan pemberitahuan secara tertulis tentang persetujuan pembayaran klaim, maka secara otomatis Klaim tersebut dianggap telah disetujui.');

		$pdf->ln();
		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Berkenaan dengan hal tersebut di atas, kami mohon untuk segera '.$dataku['as_name'].' dapat melaksanakan pembayaran klaim dimaksud selambat-lambatnya 7 (tujuh) hari kalender sejak klaim disetujui. ');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Demikian kami sampaikan. Atas perhatian dan kerjasamanya kami mengucapkan terima kasih.');
		$pdf->ln();


		$pdf->SetX(20);
		$pdf->Cell(0,5,'Hormat Kami,',0,0,'L');
		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'PT. Adonai Pialang Asuransi',0,0,'L');
		$pdf->ln();


		$pdf->ln(20);
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Wirawendra',0,0,'L');
		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Claim Head',0,0,'L');

		$pdf->SetFont('Times','',7);
		$pdf->Output();
		break;

	case "realisasi_as":

		$sqlku="SELECT
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.kredit_tgl,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_klaim.tgl_klaim,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_polis.nmproduk,
				fu_ajk_dn.totalpremi,
				fu_ajk_asuransi.code, fu_ajk_asuransi.name AS as_name, fu_ajk_asuransi.address, fu_ajk_asuransi.city, fu_ajk_asuransi.postcode
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id

				WHERE fu_ajk_cn.id =".$_REQUEST['id'];

		$hasilku=mysql_query($sqlku);
		$dataku=mysql_fetch_array($hasilku);



		$pdf = new FPDF('P','mm','A4');
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);

		$pdf->SetFont('Arial','',10);
		$pdf->SetX(160);
		$pdf->Cell(0,5,'Bekasi, '.date("d").' '. bulanindo(date("m")).' '.date("Y"),0,0,'L');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'No',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'____/KLM-REM/APA-'.strtoupper($dataku['code']).'/__/'.date('Y'),0,0,'L');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Lamp.',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'-',0,0,'L');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Perihal',0,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(50);
		$pdf->Cell(0,5,'Realisasi Pembayaran Klaim a.n. '.$dataku['nama'],0,0,'L');
		$pdf->ln();

		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Kepada YTH.',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Yth. Bapak Sri Haryanto – General Manager Tehnik',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,strtoupper($dataku['as_name']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,$dataku['address'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,$dataku['city'].' '.$dataku['postcode'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Dengan hormat,',0,0,'L');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Pertama-tama kami mengucapkan terima kasih atas kerjasama yang yang telah terjalin dengan sangat baik selama ini antara '.$dataku['as_name'].' dan PT. Adonai Pialang Asuransi (Adonai).');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Bersama ini kami mohon konfirmasi pembayaran klaim untuk debitur sebagai berikut :');
		$pdf->ln();


		$pdf->SetX(40);
		$pdf->Cell(0,5,'Nama Debitur',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,$dataku['nama'],0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Lahir',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['tgl_lahir'])).' '.bulanindo(date("m",strtotime($dataku['tgl_lahir']))).' '.date("Y",strtotime($dataku['tgl_lahir'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Akad',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['kredit_tgl'])).' '.bulanindo(date("m",strtotime($dataku['kredit_tgl']))).' '.date("Y",strtotime($dataku['kredit_tgl'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Plafond Kredit',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,'Rp. '.duit($dataku['kredit_jumlah']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tuntutan Klaim',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,'Rp. '.duit($dataku['tuntutan_klaim']),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Tanggal Meninggal Dunia',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,date("d",strtotime($dataku['tgl_klaim'])).' '.bulanindo(date("m",strtotime($dataku['tgl_klaim']))).' '.date("Y",strtotime($dataku['tgl_klaim'])),0,0,'L');
		$pdf->ln();

		$pdf->SetX(40);
		$pdf->Cell(0,5,'Program Asuransi',0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(0,5,':',0,0,'L');
		$pdf->SetX(100);
		$pdf->Cell(0,5,$dataku['nmproduk'],0,0,'L');
		$pdf->ln();

		$pdf->ln();
		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Mengingat  proses penyelesaian klaim yang telah melebihi jangka waktu yang telah disepakati bersama antara '.$dataku['code'].' dengan Adonai yaitu melewati 14 (empat belas) hari sejak dokumen disampaikan secara lengkap dan benar oleh Adonai kepada Bosowa, sehingga bersama ini kami sampaikan agar klaim tersebut di atas dapat dibayarkan dalam waktu dekat.');


		$pdf->ln();
		$pdf->SetX(20);
		$pdf->MultiCell(0,5,'Demikian kami sampaikan. Atas perhatian dan kerjasamanya kami mengucapkan terima kasih.');
		$pdf->ln();

		$pdf->SetX(20);
		$pdf->Cell(0,5,'Hormat Kami,',0,0,'L');
		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'PT. Adonai Pialang Asuransi',0,0,'L');
		$pdf->ln();


		$pdf->ln(20);
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Wirawendra',0,0,'L');
		$pdf->ln();
		$pdf->SetX(20);
		$pdf->Cell(0,5,'Claim Head',0,0,'L');

		$pdf->SetFont('Times','',7);
		$pdf->Output();
		break;

case "tiering_klaim":

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}

	$q1='';
	$q2='';
	$q3='';
	$q4='';
	$q5='';
	$q6='';

	if($_REQUEST['id_asuransi']!=""){
		$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if($_REQUEST['kol']!=""){
		$q2=" and
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>1 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>1 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
	}


	$q3="";
	if(!empty($_REQUEST['status_klaim'])){
		$q3="  and if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."' ";
	}

	$q4='';
	if(!empty($_REQUEST['tgl1'])){
		$q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q5='';
	if(!empty($_REQUEST['tgl3'])){
		$q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}


	$kucing = "SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.code as `name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_klaim.tgl_document_lengkap='0000-00-00','',fu_ajk_klaim.tgl_document_lengkap) as tgl_document_lengkap,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap'))  as keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,

								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_peserta.kredit_jumlah as nilai_tiering,

								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,

								if(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00','',fu_ajk_klaim.tgl_lapor_klaim) as tgl_lapor_klaim,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor,ROUND(fu_ajk_peserta.kredit_tenor/12)) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								datediff(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
								if(fu_ajk_cn.tgl_byr_claim<>'',fu_ajk_cn.total_claim,'')  as bayar_ke_bank,
								fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,


								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta


								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status

								where fu_ajk_cn.type_claim='Death' and fu_ajk_cn.del is null
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
								
								and fu_ajk_cn.policy_liability='NONLIABLE'
								and fu_ajk_cn.confirm_claim !='Pending'
								order by


			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))";
			$hasilku=mysql_query($kucing);

			class PDF extends FPDF
			{
				// Page header
				function Header($kol)
				{
					if(!empty($kol)){
					$line=array("5","5", //1
							"10","20", //2
							"30","15", //3
							"45","15", //4
							"60","35", //5
							"95","15", //6
							"110","5", //7
							"115","15", //8
							"130","10", //9
							"140","15", //10
							"155","15", //11
							"170","12", //12
							"182","10", //13
							"192","13", //14
							"205","10", //15
							"215","15", //16
							"230","25", //17
							"255","15", //18
							"270","40", //19
							"310","20", //20
							"330","15", //21
							"345","20", //22
							"365","15", //23
							"380","20", //24
							"400","10"); //25
					$this->SetFont('Arial','B',12);
					$this->Cell(0,5,'DAFTAR KLAIM AJK PT BANK BUKOPIN, TBK. DENGAN TIERING '.strtoupper($_REQUEST['status_klaim']),0,0,'C');
					$this->ln();


					$q_tglklaim='';
					if(!empty($_REQUEST['tgl1'])){
						$this->Cell(0,5,"Tanggal Lapor ".bulan_convert($_REQUEST ['tgl1'])." s.d ".bulan_convert($_REQUEST ['tgl2'])."",0,0,'C');
						$this->ln(5);
					}


					$q_dol='';
					if(!empty($_REQUEST['tgl3'])){
						$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST ['tgl3'])." s.d ".bulan_convert($_REQUEST ['tgl4'])."",0,0,'C');
						$this->ln(5);
					}

					$this->ln(10);

					$this->SetFont('Arial','B',5);
					$this->SetX($line['0']);
					$this->Cell(200,5,'KOL '.$kol,0,0,'L');

					$this->ln();
					$this->SetFont('Arial','B',5);
					$this->SetX($line['0']);
					$this->Cell($line['1'],10,'','LRT',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],10,'','LRT',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],10,'Cover','LRT',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],10,'','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],10,'','LRT',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11'],10,'','LRT',0,'C');
					$this->SetX($line['12']);
					$this->Cell($line['13'],10,'','LRT',0,'C');
					$this->SetX($line['14']);
					$this->Cell($line['15'],10,'','LRT',0,'C');
					$this->SetX($line['16']);
					$this->Cell($line['17'],10,'','LRT',0,'C');
					$this->SetX($line['18']);
					$this->Cell($line['19'],10,'','LRT',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],10,'','LRT',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],10,'','LRT',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],10,'J. Wkt','LRT',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],10,'','LRT',0,'C');
					$this->SetX($line['28']);
					$this->Cell($line['29'],10,'Akad s/d','LRT',0,'C');
					$this->SetX($line['30']);
					$this->Cell($line['31'],10,'Tgl Lapor','LRT',0,'C');
					$this->SetX($line['32']);
					$this->Cell($line['33'],10,'Kelengkapan','LRT',0,'C');
					$this->SetX($line['34']);
					$this->Cell($line['35'],10,'Tgl Status','LRT',0,'C');
					$this->SetX($line['36']);
					$this->Cell($line['37'],10,'','LRT',0,'C');
					$this->SetX($line['38']);
					$this->Cell($line['39'],10,'Asuransi','LRT',0,'C');
					$this->SetX($line['40']);
					$this->Cell($line['41'],10,'Tgl Bayar','LRT',0,'C');
					$this->SetX($line['42']);
					$this->Cell($line['43'],10,'Bayar Ke','LRT',0,'C');
					$this->SetX($line['44']);
					$this->Cell($line['45'],10,'Tgl Bayar','LRT',0,'C');
					$this->SetX($line['46']);
					$this->Cell($line['47'],10,'','LRT',0,'C');
					$this->SetX($line['48']);
					$this->Cell($line['49'],10,'','LRT',0,'C');

					$this->ln(3);

					$this->SetFont('Arial','B',5);$this->SetX($line['0']);
					$this->Cell($line['1'],10,'No','LRB',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],10,'Bukopin Cabang','LRB',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
					$this->SetX($line['12']);
					$this->Cell($line['13'],10,'Usia','LRB',0,'C');
					$this->SetX($line['14']);
					$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
					$this->SetX($line['16']);
					$this->Cell($line['17'],10,'Persentase','LRB',0,'C');
					$this->SetX($line['18']);
					$this->Cell($line['19'],10,'Nilai Tiering','LRB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],10,'Tuntutan Klaim','LRB',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],10,'Tgl Akad','LRB',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],10,'(Th.)','LRB',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],10,'DOL','LRB',0,'C');
					$this->SetX($line['28']);
					$this->Cell($line['29'],10,'DOL (Hr)','LRB',0,'C');
					$this->SetX($line['30']);
					$this->Cell($line['31'],10,'Asuransi','LRB',0,'C');
					$this->SetX($line['32']);
					$this->Cell($line['33'],10,'Dokumen Klaim','LRB',0,'C');
					$this->SetX($line['34']);
					$this->Cell($line['35'],10,'Lengkap','LRB',0,'C');
					$this->SetX($line['36']);
					$this->Cell($line['37'],10,'Status Klaim','LRB',0,'C');
					$this->SetX($line['38']);
					$this->Cell($line['39'],10,'Bayar (Rp)','LRB',0,'C');
					$this->SetX($line['40']);
					$this->Cell($line['41'],10,'dr Asuransi','LRB',0,'C');
					$this->SetX($line['42']);
					$this->Cell($line['43'],10,'Bank (Rp)','LRB',0,'C');
					$this->SetX($line['44']);
					$this->Cell($line['45'],10,'Ke Client','LRB',0,'C');
					$this->SetX($line['46']);
					$this->Cell($line['47'],10,'Selisih','LRB',0,'C');
					$this->SetX($line['48']);
					$this->Cell($line['49'],10,'KOL','LRB',0,'C');
					$this->ln();

					}
				}

				// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
			}
			}



			$line=array("5","5", //1
					"10","20", //2
					"30","15", //3
					"45","15", //4
					"60","35", //5
					"95","15", //6
					"110","5", //7
					"115","15", //8
					"130","10", //9
					"140","15", //10
					"155","15", //11
					"170","12", //12
					"182","10", //13
					"192","13", //14
					"205","10", //15
					"215","15", //16
					"230","25", //17
					"255","15", //18
					"270","40", //19
					"310","20", //20
					"330","15", //21
					"345","20", //22
					"365","15", //23
					"380","20", //24
					"400","10"); //25

			$pdf = new PDF('L','mm','A3');




			$no=1;
			$kol=0;
			while($dataku=mysql_fetch_array($hasilku)){

				if($kol!==$dataku['kol']){

					if($kol!==0){
						$pdf->SetFont('Arial','B',5);

						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUBTOTAL KOL'.$kol,0,0,'L');
						$pdf->SetX($line['18']);
						$pdf->Cell($line['19'],5,number_format($anilai),0,0,'R');
						$pdf->SetX($line['20']);
						$pdf->Cell($line['21'],5,number_format($atuntutanklaim),0,0,'R');
						$pdf->SetX($line['38']);
						$pdf->Cell($line['39'],5,number_format($aasuransi_bayar),0,0,'R');
						$pdf->SetX($line['42']);
						$pdf->Cell($line['43'],5,number_format($abank_bayar),0,0,'R');
						$pdf->SetX($line['46']);
						$pdf->Cell($line['47'],5,number_format($aselisih),0,0,'R');

						$pdf->ln(10);
					}
					$kol=$dataku['kol'];


					$anilai=0;
					$atuntutanklaim=0;
					$aasuransi_bayar=0;
					$abank_bayar=0;
					$aselisih=0;

					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol);

					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);


				}
				$pdf->SetFont('Arial','',5);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$no,1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,strtoupper($dataku['name']),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,$dataku['persentase_tiering'].'%',1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($dataku['nilai_tiering']),1,0,'R');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line['30']);
				$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_lapor_klaim']),1,0,'C');
				$pdf->SetX($line['32']);
				$pdf->Cell($line['33'],5,$dataku['keterangan'],1,0,'L');
				$pdf->SetX($line['34']);
				$pdf->Cell($line['35'],5,bulan_convert($dataku['tgl_document_lengkap']),1,0,'C');
				$pdf->SetX($line['36']);
				$pdf->Cell($line['37'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line['38']);
				$pdf->Cell($line['39'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				$pdf->SetX($line['40']);
				$pdf->Cell($line['41'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
				$pdf->SetX($line['42']);
				$pdf->Cell($line['43'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line['44']);
				$pdf->Cell($line['45'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
				$pdf->SetX($line['46']);
				$pdf->Cell($line['47'],5,number_format($dataku['selisih']),1,0,'R');
				$pdf->SetX($line['48']);
				$pdf->Cell($line['49'],5,$dataku['kol'],1,0,'C');


				$nilai+=$dataku['nilai_tiering'];
				$tuntutanklaim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$selisih+=$dataku['selisih'];

				$anilai+=$dataku['nilai_tiering'];
				$atuntutanklaim+=$dataku['tuntutan_klaim'];
				$aasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$abank_bayar+=$dataku['bayar_ke_bank'];
				$aselisih+=$dataku['selisih'];

				$no++;
				$pdf->ln();
			}

			$pdf->SetFont('Arial','B',5);

			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,'SUBTOTAL KOL'.$kol,0,0,'L');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,number_format($anilai),0,0,'R');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($atuntutanklaim),0,0,'R');
			$pdf->SetX($line['38']);
			$pdf->Cell($line['39'],5,number_format($aasuransi_bayar),0,0,'R');
			$pdf->SetX($line['42']);
			$pdf->Cell($line['43'],5,number_format($abank_bayar),0,0,'R');
			$pdf->SetX($line['46']);
			$pdf->Cell($line['47'],5,number_format($aselisih),0,0,'R');


			$pdf->ln(10);
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,'Total',0,0,'L');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,number_format($nilai),0,0,'R');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($tuntutanklaim),0,0,'R');
			$pdf->SetX($line['38']);
			$pdf->Cell($line['39'],5,number_format($asuransi_bayar),0,0,'R');
			$pdf->SetX($line['42']);
			$pdf->Cell($line['43'],5,number_format($bank_bayar),0,0,'R');
			$pdf->SetX($line['46']);
			$pdf->Cell($line['47'],5,number_format($selisih),0,0,'R');

			$pdf->SetFont('Times','',7);
			$pdf->Output();
			break;

case "klaim_outstanding":

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);
	
			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}
	
		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}
	
	}
	
	
	
	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}
	
	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}
	
	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
		$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
	}
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}
	
	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}
	
	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}
	
	
	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
	}
	
	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}
	
	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
				IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
				,
				IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}
	
	$sqlku="SELECT
						CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
						fu_ajk_cn.id_cabang,
						fu_ajk_costumer.name as nama_cost,
						fu_ajk_grupproduk.nmproduk AS mitra,
						fu_ajk_asuransi.`name`,
						fu_ajk_asuransi.code,
						fu_ajk_polis.nmproduk,
						IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.total_claim,
						fu_ajk_cn.tuntutan_klaim,
						fu_ajk_peserta.kredit_tgl,
						ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
						fu_ajk_klaim.tgl_klaim AS dol,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
						DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
						DATE(fu_ajk_cn.approve_date) AS input_date,
						DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
						'' AS tgl_update_klaim,
						IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
						fu_ajk_cn.keterangan AS kelengkapan_dokumen,
						IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
						IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
						IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
						CURRENT_DATE() AS today,
						IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
						/*fu_ajk_spak.ext_premi*/ '' AS EM,
						/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
						IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
						fu_ajk_klaim.diagnosa AS hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
						fu_ajk_cn.policy_liability AS polis_liability,
						fu_ajk_pembayaran_status.pembayaran_status,
						fu_ajk_klaim_status.status_klaim,
						if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))  AS status_dokumen,
						'' AS keterangan_asuransi,
						fu_ajk_cn.total_bayar_asuransi,
						'' AS ref_bayar_asuransi,
						if(fu_ajk_cn.tgl_bayar_asuransi='0000-00-00',null,fu_ajk_cn.tgl_bayar_asuransi) as tgl_bayar_asuransi,
						'' AS nilai_pengajuan_keuangan,
						fu_ajk_cn.total_claim  AS bayar_ke_bank,
						'' AS ref_pembayaran_ke_bank,
						fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
						ifnull(fu_ajk_cn.total_bayar_asuransi,0)-ifnull(fu_ajk_cn.total_claim,0) AS selisih,
						fu_ajk_cn.tuntutan_klaim-ifnull(fu_ajk_cn.total_claim,0) AS selisih_bank,
						fu_ajk_cn.tuntutan_klaim-ifnull(fu_ajk_cn.total_bayar_asuransi,0) AS selisih_as,
	
						IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK',
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))),100) as tiering,
	
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
						FROM
						fu_ajk_cn
						INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
						INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id=fu_ajk_peserta.id_cost
						LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
						/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
						LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
						LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
						WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND `fu_ajk_cn`.`confirm_claim` <> 'Pending'
						AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
						ORDER BY fu_ajk_peserta.id_cost,
						fu_ajk_asuransi.id,
						fu_ajk_polis.`typeproduk`,
						fu_ajk_pembayaran_status.pembayaran_status,
						if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap')) desc,
						fu_ajk_cn.policy_liability,
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
						fu_ajk_cn.id DESC";
	$hasilku=mysql_query($sqlku);
	
	
	
	if($_REQUEST['format_report']=='1'){
		class PDF extends FPDF
		{
			// Page header
			function Header($kol='',$cost='',$asuransi='',$status_dok='',$tipe_prod='',$liable='',$status_pemb='')
			{
				if($kol!==''){
					if($liable=='LIABLE'){
						$line=array(
								"5","5",  // KOL 1
								"10","20",  // KOL 2
								"30","15",  // KOL 3
								"45","20",  // KOL 4
								"65","35",  // KOL 5
								"100","15",  // KOL 6
								"115","10",  // KOL 7
								"125","20",  // KOL 8
								"145","20",  // KOL 9
								"165","15",  // KOL 10
								"180","10",  // KOL 11
								"190","15",  // KOL 12
								"205","13",  // KOL 13
								"218","15",  // KOL 14
								"233","27",  // KOL 15
								"260","15",  // KOL 16
								"275","35",  // KOL 16
								"310","15",  // KOL 17
								"325","15",  // KOL 18
								"340","15",  // KOL 19
								"355","15",  // KOL 20
								"370","15",  // KOL 21
								"385","20",  // KOL 22
								"405","10");  // KOL 23
	
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' ('.$liable.' Asuransi '.$tipe_prod.'/ Pembayaran Normal'.$status_pemb.')',0,0,'C');
						$this->ln();
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln(10);
	
						$this->SetFont('Arial','B',7);
	
	
	
						$this->SetX($line['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
	
	
						$this->ln();
						$this->SetX($line['0']);
						$this->Cell($line['1'],10,'','LRT',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'','LRT',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'','LRT',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'','LRT',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'','LRT',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'','LRT',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'','LRT',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'','LRT',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'','LRT',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'','LRT',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33'],10,'','LRT',0,'C');
						$this->SetX($line['34']);
						$this->Cell($line['35'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line['36']);
						$this->Cell($line['37'],10,'Asuransi','LRT',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'','LRT',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'','LRT',0,'C');
	
						$this->ln(3);
	
						$this->SetFont('Arial','B',7);$this->SetX($line['0']);
						$this->Cell($line['1'],10,'No','LRB',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'Cabang','LRB',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'DOL','LRB',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33'],10,'Status Klaim','LRB',0,'C');
						$this->SetX($line['34']);
						$this->Cell($line['35'],10,'Klaim','LRB',0,'C');
						$this->SetX($line['36']);
						$this->Cell($line['37'],10,'Bayar (Rp)','LRB',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'dr Asuransi','LRB',0,'C');
						$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Ke Client','LRB',0,'C');
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'Selisih','LRB',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'KOL','LRB',0,'C');
	
						$this->ln();
					}else{
	
						$line1=array("5","5", //1
								"10","20", //2
								"30","15", //3
								"45","15", //4
								"60","35", //5
								"95","15", //6
								"110","5", //7
								"115","15", //8
								"130","10", //9
								"140","15", //10
								"155","15", //11
								"170","12", //12
								"182","10", //13
								"192","13", //14
								"205","10", //15
								"215","15", //16
								"230","25", //17
								"255","15", //18
								"270","40", //19
								"310","20", //20
								"330","15", //21
								"345","20", //22
								"365","15", //23
								"380","20", //24
								"400","10"); //25
	
	
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' (Asuransi '.$tipe_prod.'/ Pembayaran Tiering'.$status_pemb.')',0,0,'C');
						$this->ln();
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln(10);
	
						$this->SetFont('Arial','B',7);
	
	
	
						$this->SetX($line1['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
	
	
						$this->ln();
						$this->SetFont('Arial','B',5);
						$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'','LRT',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'','LRT',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'','LRT',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'','LRT',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'','LRT',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'','LRT',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'','LRT',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Persentase','LRT',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'','LRT',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'','LRT',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'','LRT',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'','LRT',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line1['36']);
						$this->Cell($line1['37'],10,'','LRT',0,'C');
						$this->SetX($line1['38']);
						$this->Cell($line1['39'],10,'Asuransi','LRT',0,'C');
						$this->SetX($line1['40']);
						$this->Cell($line1['41'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'','LRT',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'','LRT',0,'C');
	
						$this->ln(3);
	
						$this->SetFont('Arial','B',5);$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'No','LRB',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'Bukopin Cabang','LRB',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Tiering','LRB',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'Nilai Tiering','LRB',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'DOL','LRB',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line1['36']);
						$this->Cell($line1['37'],10,'Status Klaim','LRB',0,'C');
						$this->SetX($line1['38']);
						$this->Cell($line1['39'],10,'Bayar (Rp)','LRB',0,'C');
						$this->SetX($line1['40']);
						$this->Cell($line1['41'],10,'dr Asuransi','LRB',0,'C');
						$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Ke Client','LRB',0,'C');
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'Selisih','LRB',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'KOL','LRB',0,'C');
						$this->ln();
	
					}
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
				$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
			}
		}
	
	
		$pdf = new PDF('L','mm','A3');
	
		$line=array(
				"5","5",  // KOL 1
				"10","20",  // KOL 2
				"30","15",  // KOL 3
				"45","20",  // KOL 4
				"65","35",  // KOL 5
				"100","15",  // KOL 6
				"115","10",  // KOL 7
				"125","20",  // KOL 8
				"145","20",  // KOL 9
				"165","15",  // KOL 10
				"180","10",  // KOL 11
				"190","15",  // KOL 12
				"205","13",  // KOL 13
				"218","15",  // KOL 14
				"233","27",  // KOL 15
				"260","15",  // KOL 16
				"275","35",  // KOL 16
				"310","15",  // KOL 17
				"325","15",  // KOL 18
				"340","15",  // KOL 19
				"355","15",  // KOL 20
				"370","15",  // KOL 21
				"385","20",  // KOL 22
				"405","10");  // KOL 23
	
		$line1=array("5","5", //1
				"10","20", //2
				"30","15", //3
				"45","15", //4
				"60","35", //5
				"95","15", //6
				"110","5", //7
				"115","15", //8
				"130","10", //9
				"140","15", //10
				"155","15", //11
				"170","12", //12
				"182","10", //13
				"192","13", //14
				"205","10", //15
				"215","15", //16
				"230","25", //17
				"255","15", //18
				"270","40", //19
				"310","20", //20
				"330","15", //21
				"345","20", //22
				"365","15", //23
				"380","20", //24
				"400","10"); //25
	
	
		$no=1;
		$kol=0;
		$ket='';
		while($dataku=mysql_fetch_array($hasilku)){
			
			if($dataku['polis_liability']=='LIABLE'){
				if($kol!==$dataku['kol']){
	
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['36']);
							$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['38']);
							$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}
	
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
	
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
	
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
	
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
	
				}
				
				else{
				if($polis_liability!==$dataku['polis_liability']){
					if($polis_liability=='LIABLE'){
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
						
						$pdf->ln(10);
					}else{
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line1['8']);
						$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line1['14']);
						$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line1['20']);
						$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line1['38']);
						$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line1['42']);
						$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line1['46']);
						$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
							
						$pdf->ln(10);
					}

					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
						
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
						
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
						
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
				}
			}
	
	
	
				$pdf->SetFont('Arial','',6);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$no,1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line['30']);
				$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line['32']);
				$pdf->SetFont('Arial','',5);
				$pdf->Cell($line['33'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line['34']);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell($line['35'],5,bulan_convert($dataku['input_date']),1,0,'C');
				$pdf->SetX($line['36']);
				$pdf->Cell($line['37'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				$pdf->SetX($line['38']);
				$pdf->Cell($line['39'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
				$pdf->SetX($line['40']);
				$pdf->Cell($line['41'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line['42']);
				$pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
				$pdf->SetX($line['44']);
				$pdf->Cell($line['45'],5,number_format($dataku['selisih']),1,0,'R');
				$pdf->SetX($line['46']);
				$pdf->Cell($line['47'],5,$dataku['kol'],1,0,'C');
	
	
	
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih'];
	
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih'];
	
	
	
	
				$no++;
				$pdf->ln();
			}else{
	
				if($kol!==$dataku['kol']){
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['36']);
							$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['38']);
							$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
	
							$pdf->ln(10);
						}
	
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
	
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
	
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
	
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
	
				}
				
				else{
				if($polis_liability!==$dataku['polis_liability']){
					if($polis_liability=='LIABLE'){
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
							
						$pdf->ln(10);
					}else{
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line1['8']);
						$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line1['14']);
						$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line1['20']);
						$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line1['38']);
						$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line1['42']);
						$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line1['46']);
						$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
							
							
						$pdf->ln(10);
					}

					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
						
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
						
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
						
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
				}
			}
	
	
	
				$pdf->SetFont('Arial','',5);
				$pdf->SetX($line1['0']);
				$pdf->Cell($line1['1'],5,$no,1,0,'C');
				$pdf->SetX($line1['2']);
				$pdf->Cell($line1['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line1['4']);
				$pdf->Cell($line1['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line1['6']);
				$pdf->Cell($line1['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line1['8']);
				$pdf->Cell($line1['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line1['10']);
				$pdf->Cell($line1['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line1['12']);
				$pdf->Cell($line1['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line1['14']);
				$pdf->Cell($line1['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line1['16']);
				$pdf->Cell($line1['17'],5,$dataku['tiering'],1,0,'R');
				$pdf->SetX($line1['18']);
				$pdf->Cell($line1['19'],5,number_format($dataku['tiering']/100*$dataku['kredit_jumlah']),1,0,'C');
				$pdf->SetX($line1['20']);
				$pdf->Cell($line1['21'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line1['22']);
				$pdf->Cell($line1['23'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line1['24']);
				$pdf->Cell($line1['25'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line1['26']);
				$pdf->Cell($line1['27'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line1['28']);
				$pdf->Cell($line1['29'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line1['30']);
				$pdf->Cell($line1['31'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line1['32']);
				$pdf->Cell($line1['33'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line1['34']);
				$pdf->Cell($line1['35'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line1['36']);
				$pdf->SetFont('Arial','',4);
				$pdf->Cell($line1['37'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line1['38']);
				$pdf->SetFont('Arial','',5);
				$pdf->Cell($line1['39'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				$pdf->SetX($line1['40']);
				$pdf->Cell($line1['41'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
				$pdf->SetX($line1['42']);
				$pdf->Cell($line1['43'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line1['44']);
				$pdf->Cell($line1['45'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
				$pdf->SetX($line1['46']);
				$pdf->Cell($line1['47'],5,number_format($dataku['selisih']),1,0,'R');
				$pdf->SetX($line1['48']);
				$pdf->Cell($line1['49'],5,$dataku['kol'],1,0,'C');
	
	
	
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih'];
	
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih'];
	
	
	
	
				$no++;
				$pdf->ln();
			}
		}
	
		$pdf->SetFont('Arial','B',6);
	
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
		$pdf->SetX($line['36']);
		$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
		$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
	
		$pdf->ln(10);
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'TOTAL ',0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($kredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($total_claim),0,0,'R');
		$pdf->SetX($line['36']);
		$pdf->Cell($line['37'],5,number_format($asuransi_bayar),0,0,'R');
		$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($bank_bayar),0,0,'R');
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($total_Selisi),0,0,'R');
	}elseif($_REQUEST['format_report']=='2'){
		class PDF extends FPDF
		{
			// Page header
			function Header($kol='',$cost='',$asuransi='',$status_dok='',$tipe_prod='',$liable='',$status_pemb='')
			{
				if($kol!==''){
					if($liable=='LIABLE'){
						$line=array(
								"5","5",  // KOL 1
								"10","20",  // KOL 2
								"30","15",  // KOL 3
								"45","20",  // KOL 4
								"65","35",  // KOL 5
								"100","15",  // KOL 6
								"115","10",  // KOL 7
								"125","20",  // KOL 8
								"145","20",  // KOL 9
								"165","15",  // KOL 10
								"180","10",  // KOL 11
								"190","15",  // KOL 12
								"205","13",  // KOL 13
								"218","15",  // KOL 14
								"233","27",  // KOL 15
								"260","15",  // KOL 16
								"275","35",  // KOL 16
								"310","15",  // KOL 17
								"325","15",  // KOL 18
								"340","15",  // KOL 19
								"355","15",  // KOL 20
								"370","15",  // KOL 21
								"385","20",  // KOL 22
								"405","10");  // KOL 23
	
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' ('.$liable.' Asuransi '.$tipe_prod.'/ Pembayaran Normal'.$status_pemb.')',0,0,'C');
						$this->ln();
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln(10);
	
						$this->SetFont('Arial','B',7);
	
	
	
						$this->SetX($line['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
	
	
						$this->ln();
						$this->SetX($line['0']);
						$this->Cell($line['1'],10,'','LRT',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'','LRT',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'','LRT',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'','LRT',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'','LRT',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'','LRT',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'','LRT',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'','LRT',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'','LRT',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'','LRT',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33']+$line['35']+$line['37'],10,'','LRT',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'Tgl Lapor','LRT',0,'C');
						/*$this->SetX($line['36']);
						 $this->Cell($line['37'],10,'Asuransi','LRT',0,'C');
						 $this->SetX($line['38']);
						 $this->Cell($line['39'],10,'Tgl Bayar','LRT',0,'C');*/
						$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'','LRT',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'','LRT',0,'C');
	
						$this->ln(3);
	
						$this->SetFont('Arial','B',7);$this->SetX($line['0']);
						$this->Cell($line['1'],10,'No','LRB',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'Cabang','LRB',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'DOL','LRB',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33']+$line['35']+$line['37'],10,'Status Klaim','LRB',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'Klaim','LRB',0,'C');
						/*$this->SetX($line['36']);
						 $this->Cell($line['37'],10,'Bayar (Rp)','LRB',0,'C');
						 $this->SetX($line['38']);
						 $this->Cell($line['39'],10,'dr Asuransi','LRB',0,'C');*/
						$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Ke Client','LRB',0,'C');
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'Selisih','LRB',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'KOL','LRB',0,'C');
	
						$this->ln();
					}else{
	
						$line1=array("5","5", //1
								"10","20", //2
								"30","15", //3
								"45","15", //4
								"60","35", //5
								"95","15", //6
								"110","5", //7
								"115","15", //8
								"130","10", //9
								"140","15", //10
								"155","15", //11
								"170","12", //12
								"182","10", //13
								"192","13", //14
								"205","10", //15
								"215","15", //16
								"230","25", //17
								"255","15", //18
								"270","40", //19
								"310","20", //20
								"330","15", //21
								"345","20", //22
								"365","15", //23
								"380","20", //24
								"400","10"); //25
	
	
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' (Asuransi '.$tipe_prod.'/ Pembayaran Tiering'.$status_pemb.')',0,0,'C');
						$this->ln();
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln(10);
	
						$this->SetFont('Arial','B',7);
	
	
						$this->SetX($line1['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
	
	
						$this->ln();
						$this->SetFont('Arial','B',5);
						$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'','LRT',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'','LRT',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'','LRT',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'','LRT',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'','LRT',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'','LRT',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'','LRT',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Persentase','LRT',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'','LRT',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'','LRT',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'','LRT',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'','LRT',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line1['36']);
						$this->Cell($line1['37']+$line1['39']+$line1['41'],10,'','LRT',0,'C');
						/*$this->SetX($line1['38']);
							$this->Cell($line1['39'],10,'Asuransi','LRT',0,'C');
							$this->SetX($line1['40']);
							$this->Cell($line1['41'],10,'Tgl Bayar','LRT',0,'C');*/
						$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Tgl Bayar','LRT',0,'C');
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'','LRT',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'','LRT',0,'C');
	
						$this->ln(3);
	
						$this->SetFont('Arial','B',5);$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'No','LRB',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'Bukopin Cabang','LRB',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Tiering','LRB',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'Nilai Tiering','LRB',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'DOL','LRB',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line1['36']);
							$this->Cell($line1['37']+$line1['39']+$line1['41'],10,'Status Klaim','LRB',0,'C');
						/*	$this->SetX($line1['38']);
							$this->Cell($line1['39'],10,'Bayar (Rp)','LRB',0,'C');
						$this->SetX($line1['40']);
						$this->Cell($line1['41'],10,'dr Asuransi','LRB',0,'C');*/
						$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Ke Client','LRB',0,'C');
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'Selisih','LRB',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'KOL','LRB',0,'C');
						$this->ln();
	
					}
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
				$this->Cell(0,0,'Dokumen External Adonai (BANK)',0,0,'R');
			}
		}
	
	
		$pdf = new PDF('L','mm','A3');
	
		$line=array(
				"5","5",  // KOL 1
				"10","20",  // KOL 2
				"30","15",  // KOL 3
				"45","20",  // KOL 4
				"65","35",  // KOL 5
				"100","15",  // KOL 6
				"115","10",  // KOL 7
				"125","20",  // KOL 8
				"145","20",  // KOL 9
				"165","15",  // KOL 10
				"180","10",  // KOL 11
				"190","15",  // KOL 12
				"205","13",  // KOL 13
				"218","15",  // KOL 14
				"233","27",  // KOL 15
				"260","15",  // KOL 16
				"275","35",  // KOL 16
				"310","15",  // KOL 17
				"325","15",  // KOL 18
				"340","15",  // KOL 19
				"355","15",  // KOL 20
				"370","15",  // KOL 21
				"385","20",  // KOL 22
				"405","10");  // KOL 23
	
		$line1=array("5","5", //1
				"10","20", //2
				"30","15", //3
				"45","15", //4
				"60","35", //5
				"95","15", //6
				"110","5", //7
				"115","15", //8
				"130","10", //9
				"140","15", //10
				"155","15", //11
				"170","12", //12
				"182","10", //13
				"192","13", //14
				"205","10", //15
				"215","15", //16
				"230","25", //17
				"255","15", //18
				"270","40", //19
				"310","20", //20
				"330","15", //21
				"345","20", //22
				"365","15", //23
				"380","20", //24
				"400","10"); //25
	
	
		$no=1;
		$kol=0;
		$ket='';
		while($dataku=mysql_fetch_array($hasilku)){
			if($dataku['polis_liability']=='LIABLE'){
				if($kol!==$dataku['kol']){
	
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							/*$pdf->SetX($line['36']);
								$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');*/
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							/*$pdf->SetX($line1['38']);
								$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');*/
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}
	
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
	
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
	
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
	
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
	
				}
				else{
				if($polis_liability!==$dataku['polis_liability']){
					if($polis_liability=='LIABLE'){
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
							
						$pdf->ln(10);
					}else{
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line1['8']);
						$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line1['14']);
						$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line1['20']);
						$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line1['42']);
						$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line1['46']);
						$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
							
							
						$pdf->ln(10);
					}

					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
						
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
						
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
						
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
				}
			}
	
	
	
				$pdf->SetFont('Arial','',6);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$no,1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line['30']);
				$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line['32']);
				$pdf->SetFont('Arial','',5);
				$pdf->Cell($line['33']+$line['35']+$line['37'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line['38']);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell($line['39'],5,bulan_convert($dataku['input_date']),1,0,'C');
				/*	$pdf->SetX($line['36']);
					$pdf->Cell($line['37'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
					$pdf->SetX($line['38']);
					$pdf->Cell($line['39'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');*/
				$pdf->SetX($line['40']);
				$pdf->Cell($line['41'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line['42']);
				$pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
				$pdf->SetX($line['44']);
				$pdf->Cell($line['45'],5,number_format($dataku['selisih_bank']),1,0,'R');
				$pdf->SetX($line['46']);
				$pdf->Cell($line['47'],5,$dataku['kol'],1,0,'C');
	
	
	
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih_bank'];
	
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih_bank'];
	
	
	
	
				$no++;
				$pdf->ln();
			}else{
	
				if($kol!==$dataku['kol']){
	
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							/*$pdf->SetX($line['36']);
								$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');*/
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							/*$pdf->SetX($line1['38']);
								$pdf->Cell($line1['39'],5,number_format($sasuransi_bayar),0,0,'R');*/
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
	

							$no=1;
							$pdf->ln(10);
						}
	
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
	
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
	
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
	
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
	
				}
				else{
				if($polis_liability!==$dataku['polis_liability']){
					if($polis_liability=='LIABLE'){
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
							
						$pdf->ln(10);
					}else{
						$pdf->SetFont('Arial','B',6);
						$pdf->SetX($line1['8']);
						$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line1['14']);
						$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line1['20']);
						$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line1['42']);
						$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line1['46']);
						$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
							
							
						$pdf->ln(10);
					}

					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
						
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
						
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
						
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
				}
			}
	
	
	
				$pdf->SetFont('Arial','',5);
				$pdf->SetX($line1['0']);
				$pdf->Cell($line1['1'],5,$no,1,0,'C');
				$pdf->SetX($line1['2']);
				$pdf->Cell($line1['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line1['4']);
				$pdf->Cell($line1['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line1['6']);
				$pdf->Cell($line1['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line1['8']);
				$pdf->Cell($line1['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line1['10']);
				$pdf->Cell($line1['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line1['12']);
				$pdf->Cell($line1['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line1['14']);
				$pdf->Cell($line1['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line1['16']);
				$pdf->Cell($line1['17'],5,$dataku['tiering'],1,0,'R');
				$pdf->SetX($line1['18']);
				$pdf->Cell($line1['19'],5,number_format($dataku['tiering']/100*$dataku['kredit_jumlah']),1,0,'C');
				$pdf->SetX($line1['20']);
				$pdf->Cell($line1['21'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line1['22']);
				$pdf->Cell($line1['23'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line1['24']);
				$pdf->Cell($line1['25'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line1['26']);
				$pdf->Cell($line1['27'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line1['28']);
				$pdf->Cell($line1['29'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line1['30']);
				$pdf->Cell($line1['31'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line1['32']);
				$pdf->Cell($line1['33'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line1['34']);
				$pdf->Cell($line1['35'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line1['36']);
				$pdf->SetFont('Arial','',4);
				$pdf->Cell($line1['37']+$line1['39']+$line1['41'],5,$dataku['status_klaim'],1,0,'L');
				/*	$pdf->SetX($line1['38']);
					$pdf->SetFont('Arial','',5);
					$pdf->Cell($line1['39'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				$pdf->SetX($line1['40']);
				$pdf->Cell($line1['41'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');*/
				$pdf->SetX($line1['42']);
				$pdf->Cell($line1['43'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line1['44']);
				$pdf->Cell($line1['45'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
				$pdf->SetX($line1['46']);
				$pdf->Cell($line1['47'],5,number_format($dataku['selisih_bank']),1,0,'R');
				$pdf->SetX($line1['48']);
				$pdf->Cell($line1['49'],5,$dataku['kol'],1,0,'C');
	
	
	
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih_bank'];
	
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih_bank'];
	
	
	
	
				$no++;
				$pdf->ln();
			}
		}
	
		$pdf->SetFont('Arial','B',6);
	
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
		/*$pdf->SetX($line['36']);
			$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');*/
		$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
	
		$pdf->ln(10);
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'TOTAL ',0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($kredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($total_claim),0,0,'R');
		/*$pdf->SetX($line['36']);
			$pdf->Cell($line['37'],5,number_format($asuransi_bayar),0,0,'R');*/
		$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($bank_bayar),0,0,'R');
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($total_Selisi),0,0,'R');
	}elseif ($_REQUEST['format_report']=='3'){
		class PDF extends FPDF
		{
			// Page header
			function Header($kol='',$cost='',$asuransi='',$status_dok='',$tipe_prod='',$liable='',$status_pemb='')
			{
				if($kol!==''){
					if($liable=='LIABLE'){
						$line=array(
								"5","5",  // KOL 1
								"10","20",  // KOL 2
								"30","15",  // KOL 3
								"45","20",  // KOL 4
								"65","35",  // KOL 5
								"100","15",  // KOL 6
								"115","10",  // KOL 7
								"125","20",  // KOL 8
								"145","20",  // KOL 9
								"165","15",  // KOL 10
								"180","10",  // KOL 11
								"190","15",  // KOL 12
								"205","13",  // KOL 13
								"218","15",  // KOL 14
								"233","27",  // KOL 15
								"260","15",  // KOL 16
								"275","35",  // KOL 16
								"310","15",  // KOL 17
								"325","15",  // KOL 18
								"340","15",  // KOL 19
								"355","15",  // KOL 20
								"370","15",  // KOL 21
								"385","20",  // KOL 22
								"405","10");  // KOL 23
		
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' ('.$liable.' Asuransi '.$tipe_prod.'/ Pembayaran Normal'.$status_pemb.')',0,0,'C');
						$this->ln();
		
						$this->SetFont('Arial','',10);
		
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
		
		
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
		
						$this->ln(10);
		
						$this->SetFont('Arial','B',7);
		
		
		
						$this->SetX($line['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
		
		
						$this->ln();
						$this->SetX($line['0']);
						$this->Cell($line['1'],10,'','LRT',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'','LRT',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'','LRT',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'','LRT',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'','LRT',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'','LRT',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'','LRT',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'','LRT',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'','LRT',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'','LRT',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33']+$line['35']+$line['37'],10,'','LRT',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line['40']);
						 $this->Cell($line['41'],10,'Asuransi','LRT',0,'C');
						 $this->SetX($line['42']);
						 $this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');
						/*$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');*/
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'','LRT',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'','LRT',0,'C');
		
						$this->ln(3);
		
						$this->SetFont('Arial','B',7);$this->SetX($line['0']);
						$this->Cell($line['1'],10,'No','LRB',0,'C');
						$this->SetX($line['2']);
						$this->Cell($line['3'],10,'Cabang','LRB',0,'L');
						$this->SetX($line['4']);
						$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['6']);
						$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line['8']);
						$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line['10']);
						$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line['12']);
						$this->Cell($line['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line['14']);
						$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line['16']);
						$this->Cell($line['17'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line['18']);
						$this->Cell($line['19'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line['20']);
						$this->Cell($line['21'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line['22']);
						$this->Cell($line['23'],10,'DOL','LRB',0,'C');
						$this->SetX($line['24']);
						$this->Cell($line['25'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line['26']);
						$this->Cell($line['27'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line['28']);
						$this->Cell($line['29'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line['30']);
						$this->Cell($line['31'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line['32']);
						$this->Cell($line['33']+$line['35']+$line['37'],10,'Status Klaim','LRB',0,'C');
						$this->SetX($line['38']);
						$this->Cell($line['39'],10,'Klaim','LRB',0,'C');
						$this->SetX($line['40']);
						 $this->Cell($line['41'],10,'Bayar (Rp)','LRB',0,'C');
						 $this->SetX($line['42']);
						 $this->Cell($line['43'],10,'dr Asuransi','LRB',0,'C');
						/*$this->SetX($line['40']);
						$this->Cell($line['41'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line['42']);
						$this->Cell($line['43'],10,'Ke Client','LRB',0,'C');*/
						$this->SetX($line['44']);
						$this->Cell($line['45'],10,'Selisih','LRB',0,'C');
						$this->SetX($line['46']);
						$this->Cell($line['47'],10,'KOL','LRB',0,'C');
		
						$this->ln();
					}else{
		
						$line1=array("5","5", //1
								"10","20", //2
								"30","15", //3
								"45","15", //4
								"60","35", //5
								"95","15", //6
								"110","5", //7
								"115","15", //8
								"130","10", //9
								"140","15", //10
								"155","15", //11
								"170","12", //12
								"182","10", //13
								"192","13", //14
								"205","10", //15
								"215","15", //16
								"230","25", //17
								"255","15", //18
								"270","40", //19
								"310","20", //20
								"330","15", //21
								"345","20", //22
								"365","15", //23
								"380","20", //24
								"400","10"); //25
		
		
						$this->SetFont('Arial','B',12);
						//$this->Cell(0,5,'KLAIM '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK ',0,0,'C');
						$this->Cell(0,5,'KLAIM AJK '.$cost.'_'.$asuransi.' '.strtoupper($status_dok).' (Asuransi '.$tipe_prod.'/ Pembayaran Tiering'.$status_pemb.')',0,0,'C');
						$this->ln();
		
						$this->SetFont('Arial','',10);
		
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
		
		
		
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
		
						$this->ln(10);
		
						$this->SetFont('Arial','B',7);
		
		
		
						$this->SetX($line1['0']);
						$this->Cell(200,5,'KOL '.$kol,0,0,'L');
		
		
						$this->ln();
						$this->SetFont('Arial','B',5);
						$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'','LRT',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'','LRT',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Cover','LRT',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'','LRT',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'','LRT',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'','LRT',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'','LRT',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'','LRT',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Persentase','LRT',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'','LRT',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'','LRT',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'','LRT',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'J. Wkt','LRT',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'','LRT',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'Akad s/d','LRT',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Tgl Lapor','LRT',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Kelengkapan','LRT',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Tgl Status','LRT',0,'C');
						$this->SetX($line1['36']);
						$this->Cell($line1['37']+$line1['39']+$line1['41'],10,'','LRT',0,'C');
						$this->SetX($line1['42']);
						 $this->Cell($line1['43'],10,'Asuransi','LRT',0,'C');
						 $this->SetX($line1['44']);
						 $this->Cell($line1['45'],10,'Tgl Bayar','LRT',0,'C');
						/*$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bayar Ke','LRT',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Tgl Bayar','LRT',0,'C');*/
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'','LRT',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'','LRT',0,'C');
		
						$this->ln(3);
		
						$this->SetFont('Arial','B',5);$this->SetX($line1['0']);
						$this->Cell($line1['1'],10,'No','LRB',0,'C');
						$this->SetX($line1['2']);
						$this->Cell($line1['3'],10,'Bukopin Cabang','LRB',0,'L');
						$this->SetX($line1['4']);
						$this->Cell($line1['5'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['6']);
						$this->Cell($line1['7'],10,'Kategori','LRB',0,'C');
						$this->SetX($line1['8']);
						$this->Cell($line1['9'],10,'Nama debitur','LRB',0,'L');
						$this->SetX($line1['10']);
						$this->Cell($line1['11'],10,'Tgl Lahir','LRB',0,'C');
						$this->SetX($line1['12']);
						$this->Cell($line1['13'],10,'Usia','LRB',0,'C');
						$this->SetX($line1['14']);
						$this->Cell($line1['15'],10,'Plafond Kredit','LRB',0,'C');
						$this->SetX($line1['16']);
						$this->Cell($line1['17'],10,'Tiering','LRB',0,'C');
						$this->SetX($line1['18']);
						$this->Cell($line1['19'],10,'Nilai Tiering','LRB',0,'C');
						$this->SetX($line1['20']);
						$this->Cell($line1['21'],10,'Tuntutan Klaim','LRB',0,'C');
						$this->SetX($line1['22']);
						$this->Cell($line1['23'],10,'Tgl Akad','LRB',0,'C');
						$this->SetX($line1['24']);
						$this->Cell($line1['25'],10,'(Th.)','LRB',0,'C');
						$this->SetX($line1['26']);
						$this->Cell($line1['27'],10,'DOL','LRB',0,'C');
						$this->SetX($line1['28']);
						$this->Cell($line1['29'],10,'DOL (Hr)','LRB',0,'C');
						$this->SetX($line1['30']);
						$this->Cell($line1['31'],10,'Asuransi','LRB',0,'C');
						$this->SetX($line1['32']);
						$this->Cell($line1['33'],10,'Dokumen Klaim','LRB',0,'C');
						$this->SetX($line1['34']);
						$this->Cell($line1['35'],10,'Lengkap','LRB',0,'C');
						$this->SetX($line1['36']);
						$this->Cell($line1['37']+$line1['39']+$line1['41'],10,'Status Klaim','LRB',0,'C');
							$this->SetX($line1['42']);
						 $this->Cell($line1['43'],10,'Bayar (Rp)','LRB',0,'C');
						 $this->SetX($line1['44']);
						 $this->Cell($line1['45'],10,'dr Asuransi','LRB',0,'C');
						/*$this->SetX($line1['42']);
						$this->Cell($line1['43'],10,'Bank (Rp)','LRB',0,'C');
						$this->SetX($line1['44']);
						$this->Cell($line1['45'],10,'Ke Client','LRB',0,'C');*/
						$this->SetX($line1['46']);
						$this->Cell($line1['47'],10,'Selisih','LRB',0,'C');
						$this->SetX($line1['48']);
						$this->Cell($line1['49'],10,'KOL','LRB',0,'C');
						$this->ln();
		
					}
				}
			}
		
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
				$this->Cell(0,0,'Dokumen External Adonai [ASURANSI]',0,0,'R');
			}
		}
		
		
		$pdf = new PDF('L','mm','A3');
		
		$line=array(
				"5","5",  // KOL 1
				"10","20",  // KOL 2
				"30","15",  // KOL 3
				"45","20",  // KOL 4
				"65","35",  // KOL 5
				"100","15",  // KOL 6
				"115","10",  // KOL 7
				"125","20",  // KOL 8
				"145","20",  // KOL 9
				"165","15",  // KOL 10
				"180","10",  // KOL 11
				"190","15",  // KOL 12
				"205","13",  // KOL 13
				"218","15",  // KOL 14
				"233","27",  // KOL 15
				"260","15",  // KOL 16
				"275","35",  // KOL 16
				"310","15",  // KOL 17
				"325","15",  // KOL 18
				"340","15",  // KOL 19
				"355","15",  // KOL 20
				"370","15",  // KOL 21
				"385","20",  // KOL 22
				"405","10");  // KOL 23
		
		$line1=array("5","5", //1
				"10","20", //2
				"30","15", //3
				"45","15", //4
				"60","35", //5
				"95","15", //6
				"110","5", //7
				"115","15", //8
				"130","10", //9
				"140","15", //10
				"155","15", //11
				"170","12", //12
				"182","10", //13
				"192","13", //14
				"205","10", //15
				"215","15", //16
				"230","25", //17
				"255","15", //18
				"270","40", //19
				"310","20", //20
				"330","15", //21
				"345","20", //22
				"365","15", //23
				"380","20", //24
				"400","10"); //25
		
		
		$no=1;
		$kol=0;
		$ket='';
		while($dataku=mysql_fetch_array($hasilku)){
			if($dataku['polis_liability']=='LIABLE'){
				if($kol!==$dataku['kol']){
		
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['40']);
							 $pdf->Cell($line['41'],5,number_format($sasuransi_bayar),0,0,'R');
							/*$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');*/
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['42']);
							 $pdf->Cell($line1['43'],5,number_format($sasuransi_bayar),0,0,'R');
							/*$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');*/
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}
		
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
		
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
		
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
		
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
		
				}
				
				else{
					if($polis_liability!==$dataku['polis_liability']){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
								
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
								
								
							$pdf->ln(10);
						}
		
						$kol=$dataku['kol'];
						$polis_liability=$dataku['polis_liability'];
		
						$skredit_jml=0;
						$stotal_claim=0;
						$sasuransi_bayar=0;
						$sbank_bayar=0;
						$stotal_Selisi=0;
		
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
		
						$pdf->SetLeftMargin(20);
						$pdf->SetTopMargin(20);
						$pdf->SetRightMargin(20);
					}
				}
		
		
		
				$pdf->SetFont('Arial','',6);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$no,1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line['30']);
				$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line['32']);
				$pdf->SetFont('Arial','',5);
				$pdf->Cell($line['33']+$line['35']+$line['37'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line['38']);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell($line['39'],5,bulan_convert($dataku['input_date']),1,0,'C');
				$pdf->SetX($line['40']);
				 $pdf->Cell($line['41'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				 $pdf->SetX($line['42']);
				 $pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
				/*$pdf->SetX($line['40']);
				$pdf->Cell($line['41'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line['42']);
				$pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');*/
				$pdf->SetX($line['44']);
				$pdf->Cell($line['45'],5,number_format($dataku['selisih_as']),1,0,'R');
				$pdf->SetX($line['46']);
				$pdf->Cell($line['47'],5,$dataku['kol'],1,0,'C');
		
		
		
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih_as'];
		
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih_as'];
		
		
		
		
				$no++;
				$pdf->ln();
			}else{
		
				if($kol!==$dataku['kol']){
		
					if($kol!==0){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['40']);
							 $pdf->Cell($line['41'],5,number_format($sasuransi_bayar),0,0,'R');
							/*$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');*/
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['42']);
							 $pdf->Cell($line1['43'],5,number_format($sasuransi_bayar),0,0,'R');
							/*$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sbank_bayar),0,0,'R');*/
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');

							$no=1;
		
							$pdf->ln(10);
						}
		
					}
					$kol=$dataku['kol'];
					$polis_liability=$dataku['polis_liability'];
		
					$skredit_jml=0;
					$stotal_claim=0;
					$sasuransi_bayar=0;
					$sbank_bayar=0;
					$stotal_Selisi=0;
		
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
		
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);
		
				}
				
				else{
					if($polis_liability!==$dataku['polis_liability']){
						if($polis_liability=='LIABLE'){
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
								
							$pdf->ln(10);
						}else{
							$pdf->SetFont('Arial','B',6);
							$pdf->SetX($line1['8']);
							$pdf->Cell($line1['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
							$pdf->SetX($line1['14']);
							$pdf->Cell($line1['15'],5,number_format($skredit_jml),0,0,'R');
							$pdf->SetX($line1['20']);
							$pdf->Cell($line1['21'],5,number_format($stotal_claim),0,0,'R');
							$pdf->SetX($line1['42']);
							$pdf->Cell($line1['43'],5,number_format($sasuransi_bayar),0,0,'R');
							$pdf->SetX($line1['46']);
							$pdf->Cell($line1['47'],5,number_format($stotal_Selisi),0,0,'R');
								
								
							$pdf->ln(10);
						}
		
						$kol=$dataku['kol'];
						$polis_liability=$dataku['polis_liability'];
		
						$skredit_jml=0;
						$stotal_claim=0;
						$sasuransi_bayar=0;
						$sbank_bayar=0;
						$stotal_Selisi=0;
		
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->Header($kol,$dataku['nama_cost'],$dataku['name'],$dataku['status_dokumen'],$dataku['kategori'],$dataku['polis_liability'],$dataku['pembayaran_status']);
		
						$pdf->SetLeftMargin(20);
						$pdf->SetTopMargin(20);
						$pdf->SetRightMargin(20);
					}
				}
		
		
		
				$pdf->SetFont('Arial','',5);
				$pdf->SetX($line1['0']);
				$pdf->Cell($line1['1'],5,$no,1,0,'C');
				$pdf->SetX($line1['2']);
				$pdf->Cell($line1['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
				$pdf->SetX($line1['4']);
				$pdf->Cell($line1['5'],5,strtoupper($dataku['code']),1,0,'C');
				$pdf->SetX($line1['6']);
				$pdf->Cell($line1['7'],5,$dataku['nmproduk'],1,0,'C');
				$pdf->SetX($line1['8']);
				$pdf->Cell($line1['9'],5,strtoupper($dataku['nama']),1,0,'L');
				$pdf->SetX($line1['10']);
				$pdf->Cell($line1['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
				$pdf->SetX($line1['12']);
				$pdf->Cell($line1['13'],5,$dataku['usia'],1,0,'C');
				$pdf->SetX($line1['14']);
				$pdf->Cell($line1['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
				$pdf->SetX($line1['16']);
				$pdf->Cell($line1['17'],5,$dataku['tiering'],1,0,'R');
				$pdf->SetX($line1['18']);
				$pdf->Cell($line1['19'],5,number_format($dataku['tiering']/100*$dataku['kredit_jumlah']),1,0,'C');
				$pdf->SetX($line1['20']);
				$pdf->Cell($line1['21'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
				$pdf->SetX($line1['22']);
				$pdf->Cell($line1['23'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
				$pdf->SetX($line1['24']);
				$pdf->Cell($line1['25'],5,$dataku['kredit_tenor'],1,0,'C');
				$pdf->SetX($line1['26']);
				$pdf->Cell($line1['27'],5,bulan_convert($dataku['dol']),1,0,'C');
				$pdf->SetX($line1['28']);
				$pdf->Cell($line1['29'],5,$dataku['akad_dol'],1,0,'C');
				$pdf->SetX($line1['30']);
				$pdf->Cell($line1['31'],5,bulan_convert($dataku['tgl_lapor_asuransi']),1,0,'C');
				$pdf->SetX($line1['32']);
				$pdf->Cell($line1['33'],5,$dataku['status_dokumen'],1,0,'L');
				$pdf->SetX($line1['34']);
				$pdf->Cell($line1['35'],5,bulan_convert($dataku['tgl_status_lengkap']),1,0,'C');
				$pdf->SetX($line1['36']);
				$pdf->SetFont('Arial','',4);
				$pdf->Cell($line1['37']+$line1['39']+$line1['41'],5,$dataku['status_klaim'],1,0,'L');
				$pdf->SetX($line1['42']);
				 $pdf->SetFont('Arial','',5);
				 $pdf->Cell($line1['43'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
				 $pdf->SetX($line1['44']);
				 $pdf->Cell($line1['45'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
				/*$pdf->SetX($line1['42']);
				$pdf->Cell($line1['43'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
				$pdf->SetX($line1['44']);
				$pdf->Cell($line1['45'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');*/
				$pdf->SetX($line1['46']);
				$pdf->Cell($line1['47'],5,number_format($dataku['selisih_as']),1,0,'R');
				$pdf->SetX($line1['48']);
				$pdf->Cell($line1['49'],5,$dataku['kol'],1,0,'C');
		
		
		
				$kredit_jml+=$dataku['kredit_jumlah'];
				$total_claim+=$dataku['tuntutan_klaim'];
				$asuransi_bayar+=$dataku['total_bayar_asuransi'];
				$bank_bayar+=$dataku['bayar_ke_bank'];
				$total_Selisi+=$dataku['selisih_as'];
		
				$skredit_jml+=$dataku['kredit_jumlah'];
				$stotal_claim+=$dataku['tuntutan_klaim'];
				$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
				$sbank_bayar+=$dataku['bayar_ke_bank'];
				$stotal_Selisi+=$dataku['selisih_as'];
		
		
		
		
				$no++;
				$pdf->ln();
			}
		}
		
		$pdf->SetFont('Arial','B',6);
		
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
		$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($sasuransi_bayar),0,0,'R');
		/*$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');*/
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
		
		$pdf->ln(10);
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,'TOTAL ',0,0,'L');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($kredit_jml),0,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($total_claim),0,0,'R');
		$pdf->SetX($line['40']);
		 $pdf->Cell($line['41'],5,number_format($asuransi_bayar),0,0,'R');
		/*$pdf->SetX($line['40']);
		$pdf->Cell($line['41'],5,number_format($bank_bayar),0,0,'R');*/
		$pdf->SetX($line['44']);
		$pdf->Cell($line['45'],5,number_format($total_Selisi),0,0,'R');
	}
	
	$pdf->SetFont('Times','',7);
	$pdf->Output();
	break;
case "summary_klaim_kol":

				if($_REQUEST['id_asuransi']=="all"){
					$asuransi="";
				}else{
					$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}

				if(empty($_REQUEST['id_polis'])){
					$polis="";
				}else{
					$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
				}


				if(empty($_REQUEST['status_klaim'])){
					$status_klaim="";
				}else{
					$status_klaim=" and fu_ajk_klaim_status.id=".$_REQUEST['status_klaim'];
				}

				if(empty($_REQUEST['kol'])){
					$kol="";
				}else{
					$kol=" and
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
				}




				$hasilku = mysql_query("select
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								count(aa.id) as jml,
								sum(aa.tuntutan_klaim) as tuntutan_klaim,
								sum(aa.kredit_jumlah) as plafond,
								sum(aa.total_bayar_asuransi) as asuransi_bayar,
								sum(aa.total_claim) as nilai_klaim
								from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name`,
								fu_ajk_asuransi.name as nama_asuransi,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.id,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_bayar_asuransi,
								fu_ajk_cn.total_claim,

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol

								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta

								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death'  and fu_ajk_cn.del is null
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."  ".$asuransi." ".$polis." ".$status_klaim." ".$kol."
								and fu_ajk_cn.tgl_createcn between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'
								) aa GROUP BY
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol
								order by
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								aa.status_klaim");

					$pdf = new FPDF('P','mm','A4');
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);

					$pdf->SetFont('Arial','B',12);
					$pdf->Cell(0,5,'KLAIM BERDASARKAN KOLEKBILITAS',0,0,'C');
					$pdf->ln();

					$pdf->SetFont('Arial','',10);
					$pdf->Cell(0,5,'Tanggal Klaim : '.$_REQUEST['tgl1'].' s.d. '.$_REQUEST['tgl2'],0,0,'C');

					$pdf->ln(10);

					$pdf->SetFont('Arial','',8);
					$pdf->SetX(5);
					$pdf->Cell(15,10,'No',1,0,'C');
					$pdf->SetX(20);
					$pdf->Cell(70,10,'Status Klaim',1,0,'L');
					$pdf->SetX(90);
					$pdf->Cell(10,10,'Jumlah',1,0,'C');
					$pdf->SetX(100);
					$pdf->Cell(25,10,'Plafond',1,0,'R');
					$pdf->SetX(125);
					$pdf->Cell(25,10,'Tuntutan Klaim',1,0,'R');
					$pdf->SetX(150);
					$pdf->Cell(25,10,'Pen. Dari Asuransi',1,0,'R');
					$pdf->SetX(175);
					$pdf->Cell(25,10,'Bayar Ke Bank',1,0,'R');
					$pdf->ln();
					$no=1;


					$as_='';
					$prod_='';

					$kol_='';

					$jmldata=mysql_num_rows($hasilku);
					while($dataku=mysql_fetch_array($hasilku)){

						if($prod_!==$dataku['nmproduk']){

							if($kol_!==$dataku['kol'] && $kol_!==''){


								$pdf->SetFont('Arial','B',7);
								$pdf->SetX(5);
								$pdf->Cell(15,5,'',0,0,'C');
								$pdf->SetX(20);
								$pdf->Cell(70,5,'TOTAL',0,0,'L');
								$pdf->SetX(90);
								$pdf->Cell(10,5,$t_jml,0,0,'C');
								$pdf->SetX(100);
								$pdf->Cell(25,5,duit($t_plafond),0,0,'R');
								$pdf->SetX(125);
								$pdf->Cell(25,5,duit($t_tuntutan_klaim),0,0,'R');
								$pdf->SetX(150);
								$pdf->Cell(25,5,duit($t_as_bayar),0,0,'R');
								$pdf->SetX(175);
								$pdf->Cell(25,5,duit($t_nilai_klaim),0,0,'R');

								$pdf->ln();

								$t_jml=0;
								$t_plafond=0;
								$t_tuntutan_klaim=0;
								$t_as_bayar=0;
								$t_nilai_klaim=0;


							}

							$kol_='';
							if(duit($t_plafond)!=="0"){
								$pdf->SetFont('Arial','B',7);
								$pdf->SetX(5);
								$pdf->Cell(15,5,'',0,0,'C');
								$pdf->SetX(20);
								$pdf->Cell(70,5,'TOTAL',0,0,'L');
								$pdf->SetX(90);
								$pdf->Cell(10,5,$t_jml,0,0,'C');
								$pdf->SetX(100);
								$pdf->Cell(25,5,duit($t_plafond),0,0,'R');
								$pdf->SetX(125);
								$pdf->Cell(25,5,duit($t_tuntutan_klaim),0,0,'R');
								$pdf->SetX(150);
								$pdf->Cell(25,5,duit($t_as_bayar),0,0,'R');
								$pdf->SetX(175);
								$pdf->Cell(25,5,duit($t_nilai_klaim),0,0,'R');

								$pdf->ln();

							}
							$pdf->ln(2);
							$pdf->SetFont('Arial','B',8);
							$prod_=$dataku['nmproduk'];
							$pdf->Cell(0,5,'PRODUK : '.$prod_,0,0,'C');
							$pdf->ln(3);


							if($kol_!==$dataku['kol']){
								$kol_=$dataku['kol'];

								$t_jml=0;
								$t_plafond=0;
								$t_tuntutan_klaim=0;
								$t_as_bayar=0;
								$t_nilai_klaim=0;

								$pdf->ln();
								$pdf->SetFont('Arial','B',8);
								$kol_=$dataku['kol'];
								$pdf->SetX(5);
								$pdf->Cell(70,5,'KOLEKBILITAS : '.$kol_,0,0,'L');
								$pdf->ln();
							}

						}else{

							if($kol_!==$dataku['kol']){

								$pdf->SetFont('Arial','B',7);
								$pdf->SetX(5);
								$pdf->Cell(15,5,'',0,0,'C');
								$pdf->SetX(20);
								$pdf->Cell(70,5,'TOTAL',0,0,'L');
								$pdf->SetX(90);
								$pdf->Cell(10,5,$t_jml,0,0,'C');
								$pdf->SetX(100);
								$pdf->Cell(25,5,duit($t_plafond),0,0,'R');
								$pdf->SetX(125);
								$pdf->Cell(25,5,duit($t_tuntutan_klaim),0,0,'R');
								$pdf->SetX(150);
								$pdf->Cell(25,5,duit($t_as_bayar),0,0,'R');
								$pdf->SetX(175);
								$pdf->Cell(25,5,duit($t_nilai_klaim),0,0,'R');


								$pdf->ln();
								$pdf->SetFont('Arial','B',8);
								$kol_=$dataku['kol'];
								$pdf->SetX(5);
								$pdf->Cell(70,5,'KOLEKBILITAS : '.$kol_,0,0,'L');

								$pdf->ln();

								$t_jml=0;
								$t_plafond=0;
								$t_tuntutan_klaim=0;
								$t_as_bayar=0;
								$t_nilai_klaim=0;

							}
						}



						$pdf->SetFont('Arial','',7);
						$pdf->SetX(5);
						$pdf->Cell(15,5,$no,1,0,'C');
						$pdf->SetX(20);
						$pdf->Cell(70,5,$dataku['status_klaim'],1,0,'L');
						$pdf->SetX(90);
						$pdf->Cell(10,5,$dataku['jml'],1,0,'C');
						$pdf->SetX(100);
						$pdf->Cell(25,5,duit($dataku['plafond']),1,0,'R');
						$pdf->SetX(125);
						$pdf->Cell(25,5,duit($dataku['tuntutan_klaim']),1,0,'R');
						$pdf->SetX(150);
						$pdf->Cell(25,5,duit($dataku['asuransi_bayar']),1,0,'R');
						$pdf->SetX(175);
						$pdf->Cell(25,5,duit($dataku['nilai_klaim']),1,0,'R');


						$t_jml+=$dataku['jml'];
						$t_plafond+=$dataku['plafond'];
						$t_tuntutan_klaim+=$dataku['tuntutan_klaim'];
						$t_as_bayar+=$dataku['asuransi_bayar'];
						$t_nilai_klaim+=$dataku['nilai_klaim'];
						$no++;
						$pdf->ln();

						if($jmldata==($no-1)){

							$pdf->SetFont('Arial','B',7);
							$pdf->SetX(5);
							$pdf->Cell(15,5,'',0,0,'C');
							$pdf->SetX(20);
							$pdf->Cell(70,5,'TOTAL',0,0,'L');
							$pdf->SetX(90);
							$pdf->Cell(10,5,$t_jml,0,0,'C');
							$pdf->SetX(100);
							$pdf->Cell(25,5,duit($t_plafond),0,0,'R');
							$pdf->SetX(125);
							$pdf->Cell(25,5,duit($t_tuntutan_klaim),0,0,'R');
							$pdf->SetX(150);
							$pdf->Cell(25,5,duit($t_as_bayar),0,0,'R');
							$pdf->SetX(175);
							$pdf->Cell(25,5,duit($t_nilai_klaim),0,0,'R');
						}
					}

					$pdf->SetFont('Times','',7);
					$pdf->Output();
					break;

case "klaim_liable":
	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
	

	$q_asuransi='';
	if($_REQUEST['id_asuransi']!=""){
		$q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}
		
	
	$q_status="";
	if(!empty($_REQUEST['status_klaim'])){
		$q_status=" and if(`id_klaim_status`=6,'Ditolak',
					if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
					'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
	}


	$q_tglklaim='';
	if(!empty($_REQUEST['tgl1'])){
		$l_tglklaim="Tanggal Lapor ".bulan_convert($_REQUEST ['tgl1'])." s.d ".bulan_convert($_REQUEST ['tgl2'])."";
		$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q_dol='';
	if(!empty($_REQUEST['tgl3'])){
		$l_dol="DOL ".bulan_convert($_REQUEST ['tgl3'])." s.d ".bulan_convert($_REQUEST ['tgl4'])."";
		$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}

	$q_kol='';
	if(!empty($_REQUEST['kol'])){
		$q_kol="and IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}
					$sqlku="SELECT
							CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
							fu_ajk_cn.id_cabang,
							fu_ajk_grupproduk.nmproduk AS mitra,
							fu_ajk_asuransi.code as `name`,
							if(fu_ajk_polis.nmproduk='PERCEPATAN','PERCEPATAN > 1 TAHUN',fu_ajk_polis.nmproduk) as nmproduk,
							IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
							fu_ajk_peserta.id_peserta,
							fu_ajk_peserta.nama,
							fu_ajk_peserta.tgl_lahir,
							fu_ajk_peserta.usia,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							fu_ajk_cn.tuntutan_klaim,
							fu_ajk_peserta.kredit_tgl,
							ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
							fu_ajk_klaim.tgl_klaim AS dol,
							DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
							IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
							DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
							'' AS tgl_update_klaim,
							IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
							fu_ajk_cn.keterangan AS kelengkapan_dokumen,
							IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
							IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
							IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
							CURRENT_DATE() AS today,
							IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
							/*fu_ajk_spak.ext_premi*/ '' AS EM,
							/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
							IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
							fu_ajk_klaim.diagnosa AS hasil_investigasi,
							fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
							fu_ajk_cn.policy_liability AS polis_liability,
							fu_ajk_pembayaran_status.pembayaran_status,
							fu_ajk_klaim_status.status_klaim,
							if(`id_klaim_status`=6,'Ditolak',
							if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							'Dokumen Belum Lengkap')) AS keterangan,
							'' AS keterangan_asuransi,
							fu_ajk_cn.total_bayar_asuransi,
							'' AS ref_bayar_asuransi,
							fu_ajk_cn.tgl_bayar_asuransi,
							'' AS nilai_pengajuan_keuangan,
							fu_ajk_cn.total_claim  AS bayar_ke_bank,
							'' AS ref_pembayaran_ke_bank,
							fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
							fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
							/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
							LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
							LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
							where fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'  and confirm_claim !='Pending' 
							".$q_status."

							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							".$q_asuransi."
							and fu_ajk_cn.confirm_claim !='Pending'
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							/*and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'*/
							/*and ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
							and fu_ajk_cn.policy_liability='LIABLE'
							order by
							fu_ajk_polis.nmproduk,
							if(`id_klaim_status`=6,'Ditolak',
							if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							'Dokumen Belum Lengkap')) desc,
						
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
							fu_ajk_peserta.id
						";


						$hasilku=mysql_query($sqlku);
						class PDF extends FPDF
						{
							// Page header
							function Header($kol='',$status='',$produk='')
							{
								if($kol!==''){
								$line=array(
								"5","5",  // KOL 1
								"10","20",  // KOL 2
								"30","15",  // KOL 3
								"45","20",  // KOL 4
								"65","35",  // KOL 5
								"100","15",  // KOL 6
								"115","10",  // KOL 7
								"125","20",  // KOL 8
								"145","20",  // KOL 9
								"165","15",  // KOL 10
								"180","10",  // KOL 11
								"190","15",  // KOL 12
								"205","13",  // KOL 13
								"218","15",  // KOL 14
								"233","27",  // KOL 15
								"260","15",  // KOL 16
								"275","35",  // KOL 16
								"310","15",  // KOL 17
								"325","15",  // KOL 18
								"340","15",  // KOL 19
								"355","15",  // KOL 20
								"370","15",  // KOL 21
								"385","20",  // KOL 22
								"405","10");  // KOL 23

								$this->SetFont('Arial','B',12);
								//$this->Cell(0,5,'DAFTAR '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK PT BANK BUKOPIN, TBK._'.strtoupper($status),0,0,'C');
								$this->Cell(0,5,'DAFTAR KLAIM '.$produk.'_PT BANK BUKOPIN, TBK._'.strtoupper($status),0,0,'C');
								$this->ln();

								$this->SetFont('Arial','',10);

								if(!empty($_REQUEST['tgl1'])){
									$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
									$this->ln(5);
								}
								if(!empty($_REQUEST['tgl3'])){
									$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
									$this->ln(5);
								}


								if(!empty($_REQUEST['tgl5'])){
									$this->Cell(0,5,$l_tglinput,0,0,'C');
									$this->ln(5);
								}

								$this->ln(10);

								$this->SetFont('Arial','B',7);



								$this->SetX($line['0']);
								$this->Cell(200,5,'KOL '.$kol,0,0,'L');
								$this->ln();
								$this->SetX($line['0']);
								$this->Cell($line['1'],10,'','LRT',0,'C');
								$this->SetX($line['2']);
								$this->Cell($line['3'],10,'','LRT',0,'L');
								$this->SetX($line['4']);
								$this->Cell($line['5'],10,'Cover','LRT',0,'C');
								$this->SetX($line['6']);
								$this->Cell($line['7'],10,'','LRT',0,'C');
								$this->SetX($line['8']);
								$this->Cell($line['9'],10,'','LRT',0,'L');
								$this->SetX($line['10']);
								$this->Cell($line['11'],10,'','LRT',0,'C');
								$this->SetX($line['12']);
								$this->Cell($line['13'],10,'','LRT',0,'C');
								$this->SetX($line['14']);
								$this->Cell($line['15'],10,'','LRT',0,'C');
								$this->SetX($line['16']);
								$this->Cell($line['17'],10,'','LRT',0,'C');
								$this->SetX($line['18']);
								$this->Cell($line['19'],10,'','LRT',0,'C');
								$this->SetX($line['20']);
								$this->Cell($line['21'],10,'J. Wkt','LRT',0,'C');
								$this->SetX($line['22']);
								$this->Cell($line['23'],10,'','LRT',0,'C');
								$this->SetX($line['24']);
								$this->Cell($line['25'],10,'Akad s/d','LRT',0,'C');
								$this->SetX($line['26']);
								$this->Cell($line['27'],10,'Tgl Lapor','LRT',0,'C');
								$this->SetX($line['28']);
								$this->Cell($line['29'],10,'Kelengkapan','LRT',0,'C');
								$this->SetX($line['30']);
								$this->Cell($line['31'],10,'Tgl Status','LRT',0,'C');
								$this->SetX($line['32']);
								$this->Cell($line['33'],10,'','LRT',0,'C');
								$this->SetX($line['34']);
								$this->Cell($line['35'],10,'Tgl Lapor','LRT',0,'C');
								$this->SetX($line['36']);
								$this->Cell($line['37'],10,'Asuransi','LRT',0,'C');
								$this->SetX($line['38']);
								$this->Cell($line['39'],10,'Tgl Bayar','LRT',0,'C');
								$this->SetX($line['40']);
								$this->Cell($line['41'],10,'Bayar Ke','LRT',0,'C');
								$this->SetX($line['42']);
								$this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');
								$this->SetX($line['44']);
								$this->Cell($line['45'],10,'','LRT',0,'C');
								$this->SetX($line['46']);
								$this->Cell($line['47'],10,'','LRT',0,'C');

								$this->ln(3);

								$this->SetFont('Arial','B',7);$this->SetX($line['0']);
								$this->Cell($line['1'],10,'No','LRB',0,'C');
								$this->SetX($line['2']);
								$this->Cell($line['3'],10,'Cabang','LRB',0,'L');
								$this->SetX($line['4']);
								$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
								$this->SetX($line['6']);
								$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
								$this->SetX($line['8']);
								$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
								$this->SetX($line['10']);
								$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
								$this->SetX($line['12']);
								$this->Cell($line['13'],10,'Usia','LRB',0,'C');
								$this->SetX($line['14']);
								$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
								$this->SetX($line['16']);
								$this->Cell($line['17'],10,'Tuntutan Klaim','LRB',0,'C');
								$this->SetX($line['18']);
								$this->Cell($line['19'],10,'Tgl Akad','LRB',0,'C');
								$this->SetX($line['20']);
								$this->Cell($line['21'],10,'(Th.)','LRB',0,'C');
								$this->SetX($line['22']);
								$this->Cell($line['23'],10,'DOL','LRB',0,'C');
								$this->SetX($line['24']);
								$this->Cell($line['25'],10,'DOL (Hr)','LRB',0,'C');
								$this->SetX($line['26']);
								$this->Cell($line['27'],10,'Asuransi','LRB',0,'C');
								$this->SetX($line['28']);
								$this->Cell($line['29'],10,'Dokumen Klaim','LRB',0,'C');
								$this->SetX($line['30']);
								$this->Cell($line['31'],10,'Lengkap','LRB',0,'C');
								$this->SetX($line['32']);
								$this->Cell($line['33'],10,'Status Klaim','LRB',0,'C');
								$this->SetX($line['34']);
								$this->Cell($line['35'],10,'Klaim','LRB',0,'C');
								$this->SetX($line['36']);
								$this->Cell($line['37'],10,'Bayar (Rp)','LRB',0,'C');
								$this->SetX($line['38']);
								$this->Cell($line['39'],10,'dr Asuransi','LRB',0,'C');
								$this->SetX($line['40']);
								$this->Cell($line['41'],10,'Bank (Rp)','LRB',0,'C');
								$this->SetX($line['42']);
								$this->Cell($line['43'],10,'Ke Client','LRB',0,'C');
								$this->SetX($line['44']);
								$this->Cell($line['45'],10,'Selisih','LRB',0,'C');
								$this->SetX($line['46']);
								$this->Cell($line['47'],10,'KOL','LRB',0,'C');

								$this->ln();
								}
							}

							// Page footer
							function Footer()
							{
								// Position at 1.5 cm from bottom
								$this->SetY(-15);
								// Arial italic 8
								$this->SetFont('Arial','I',8);
								// Page number
								$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
							}
						}


						$pdf = new PDF('L','mm','A3');

						$line=array(
								"5","5",  // KOL 1
								"10","20",  // KOL 2
								"30","15",  // KOL 3
								"45","20",  // KOL 4
								"65","35",  // KOL 5
								"100","15",  // KOL 6
								"115","10",  // KOL 7
								"125","20",  // KOL 8
								"145","20",  // KOL 9
								"165","15",  // KOL 10
								"180","10",  // KOL 11
								"190","15",  // KOL 12
								"205","13",  // KOL 13
								"218","15",  // KOL 14
								"233","27",  // KOL 15
								"260","15",  // KOL 16
								"275","35",  // KOL 16
								"310","15",  // KOL 17
								"325","15",  // KOL 18
								"340","15",  // KOL 19
								"355","15",  // KOL 20
								"370","15",  // KOL 21
								"385","20",  // KOL 22
								"405","10");  // KOL 23

						$no=1;
						$kol=0;
						$ket='';
						$status='';
						while($dataku=mysql_fetch_array($hasilku)){


							if($produk!==$dataku['nmproduk']){
								$kol=$dataku['kol'];
								$status=$dataku['keterangan'];
								$produk=$dataku['nmproduk'];
								if($produk!==''){
									$pdf->AddPage();
									$pdf->AliasNbPages();
									$pdf->Header($kol,$status,$produk);
								}
							}
								
						
							if($status!==$dataku['keterangan']){
								$kol=$dataku['kol'];
								$status=$dataku['keterangan'];
								if($status!==''){
									$pdf->AddPage();
									$pdf->AliasNbPages();
									$pdf->Header($kol,$status,$produk);
								}
							}
					
							if($kol!==$dataku['kol']){
								if($kol!==0){
									$pdf->SetFont('Arial','B',6);
									$pdf->SetX($line['8']);
									$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
									$pdf->SetX($line['14']);
									$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
									$pdf->SetX($line['16']);
									$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
									$pdf->SetX($line['36']);
									$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
									$pdf->SetX($line['40']);
									$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
									$pdf->SetX($line['44']);
									$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
									$no=1;
									$pdf->ln(10);
								}
								$kol=$dataku['kol'];


								$skredit_jml=0;
								$stotal_claim=0;
								$sasuransi_bayar=0;
								$sbank_bayar=0;
								$stotal_Selisi=0;

								$pdf->AddPage();
								$pdf->AliasNbPages();
								$pdf->Header($kol,$status,$produk);

								$pdf->SetLeftMargin(20);
								$pdf->SetTopMargin(20);
								$pdf->SetRightMargin(20);

							}


							$pdf->SetFont('Arial','',6);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$no,1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,strtoupper($dataku['name']),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,str_replace(" > 1 TAHUN", "",$dataku['nmproduk']),1,0,'C');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
							$pdf->SetX($line['18']);
							$pdf->Cell($line['19'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
							$pdf->SetX($line['20']);
							$pdf->Cell($line['21'],5,$dataku['kredit_tenor'],1,0,'C');
							$pdf->SetX($line['22']);
							$pdf->Cell($line['23'],5,bulan_convert($dataku['dol']),1,0,'C');
							$pdf->SetX($line['24']);
							$pdf->Cell($line['25'],5,$dataku['akad_dol'],1,0,'C');
							$pdf->SetX($line['26']);
							$pdf->Cell($line['27'],5,bulan_convert($dataku['tgl_lapor_klaim']),1,0,'C');
							$pdf->SetX($line['28']);
							$pdf->Cell($line['29'],5,$dataku['keterangan'],1,0,'L');
							$pdf->SetX($line['30']);
							$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_document_lengkap']),1,0,'C');
							$pdf->SetX($line['32']);
							$pdf->SetFont('Arial','',5);
							$pdf->Cell($line['33'],5,$dataku['status_klaim'],1,0,'L');
							$pdf->SetX($line['34']);
							$pdf->SetFont('Arial','',6);
							$pdf->Cell($line['35'],5,bulan_convert($dataku['input_date']),1,0,'C');
							$pdf->SetX($line['36']);
							$pdf->Cell($line['37'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
							$pdf->SetX($line['38']);
							$pdf->Cell($line['39'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
							$pdf->SetX($line['42']);
							$pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($dataku['selisih']),1,0,'R');
							$pdf->SetX($line['46']);
							$pdf->Cell($line['47'],5,$dataku['kol'],1,0,'C');



							$kredit_jml+=$dataku['kredit_jumlah'];
							$total_claim+=$dataku['tuntutan_klaim'];
							$asuransi_bayar+=$dataku['total_bayar_asuransi'];
							$bank_bayar+=$dataku['bayar_ke_bank'];
							$total_Selisi+=$dataku['selisih'];

							$skredit_jml+=$dataku['kredit_jumlah'];
							$stotal_claim+=$dataku['tuntutan_klaim'];
							$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
							$sbank_bayar+=$dataku['bayar_ke_bank'];
							$stotal_Selisi+=$dataku['selisih'];




							$no++;
							$pdf->ln();
						}

						$pdf->SetFont('Arial','B',6);

						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

						$pdf->ln(10);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'TOTAL ',0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($kredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($total_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($asuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($bank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($total_Selisi),0,0,'R');

						$pdf->SetFont('Times','',7);
						$pdf->Output();
						break;

case "klaim_nonliable":

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}
			
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}

	$q_asuransi='';
	if($_REQUEST['id_asuransi']!=""){
		$q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}
		
$q_status="";
	if(!empty($_REQUEST['status_klaim'])){
		$q_status=" and if(`id_klaim_status`=6,'Ditolak',
					if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
					'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
	}


	$q_tglklaim='';
	if(!empty($_REQUEST['tgl1'])){
		$l_tglklaim="Tanggal Lapor ".$_REQUEST ['tgl1']." s.d ".$_REQUEST ['tgl2']."";
		$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q_dol='';
	if(!empty($_REQUEST['tgl3'])){
		$l_dol="DOL ".$_REQUEST ['tgl3']." s.d ".$_REQUEST ['tgl4']."";
		$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}

	$q_kol='';
	if(!empty($_REQUEST['kol'])){
		$q_kol="and
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}
				$sqlku="SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_grupproduk.nmproduk AS mitra,
								fu_ajk_asuransi.code as `name`,
								fu_ajk_polis.nmproduk,
								IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
								fu_ajk_peserta.id_peserta,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
								IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
								CURRENT_DATE() AS today,
								IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
								/*fu_ajk_spak.ext_premi*/ '' AS EM,
								/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
								IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
								fu_ajk_klaim.diagnosa AS hasil_investigasi,
								fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
								fu_ajk_cn.policy_liability AS polis_liability,
								fu_ajk_pembayaran_status.pembayaran_status,
								fu_ajk_klaim_status.status_klaim,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) AS keterangan,
								'' AS keterangan_asuransi,
								fu_ajk_cn.total_bayar_asuransi,
								'' AS ref_bayar_asuransi,
								fu_ajk_cn.tgl_bayar_asuransi,
								'' AS nilai_pengajuan_keuangan,
								fu_ajk_cn.total_claim  AS bayar_ke_bank,
								'' AS ref_pembayaran_ke_bank,
								fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
								/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
								LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
								LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
					where fu_ajk_cn.type_claim='Death' and confirm_claim !='Pending'
					".$q_status."
					".$q_tglklaim."
					".$q_dol."
					".$q_kol."
					".$q_asuransi."
								and fu_ajk_cn.confirm_claim !='Pending'
					and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
					and fu_ajk_cn.del is null
								and fu_ajk_cn.policy_liability='NONLIABLE'
					/*and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')*/

					order by
							if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) desc,
					IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
					fu_ajk_peserta.id
				";

				$hasilku=mysql_query($sqlku);

				class PDF extends FPDF
				{
					// Page header
					function Header($kol='',$status='')
					{
						if($kol!==''){
							$this->SetFont('Arial','B',12);
							$this->Cell(0,5,'DAFTAR '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK PT BANK BUKOPIN, TBK._'.strtoupper($status),0,0,'C');
							$this->ln();

							$this->SetFont('Arial','',10);
							if(!empty($_REQUEST['tgl1'])){
								$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
								$this->ln(5);
							}
							if(!empty($_REQUEST['tgl3'])){
								$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
								$this->ln(5);
							}


							if(!empty($_REQUEST['tgl5'])){
								$this->Cell(0,5,$l_tglinput,0,0,'C');
								$this->ln(5);
							}


							$line=array(
									"5","5",  // KOL 1
									"10","20",  // KOL 2
									"30","15",  // KOL 3
									"45","20",  // KOL 4
									"65","35",  // KOL 5
									"100","15",  // KOL 6
									"115","10",  // KOL 7
									"125","20",  // KOL 8
									"145","20",  // KOL 9
									"165","15",  // KOL 10
									"180","10",  // KOL 11
									"190","15",  // KOL 12
									"205","13",  // KOL 13
									"218","15",  // KOL 14
									"233","27",  // KOL 15
									"260","15",  // KOL 16
									"275","35",  // KOL 16
									"310","15",  // KOL 17
									"325","15",  // KOL 18
									"340","15",  // KOL 19
									"355","15",  // KOL 20
									"370","15",  // KOL 21
									"385","20",  // KOL 22
									"405","10");  // KOL 23


							$this->SetFont('Arial','B',7);
							$this->SetX($line['0']);
							$this->Cell(200,5,'KOL '.$kol,0,0,'L');
							$this->ln();
							$this->SetX($line['0']);
							$this->Cell($line['1'],10,'','LRT',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3'],10,'','LRT',0,'L');
							$this->SetX($line['4']);
							$this->Cell($line['5'],10,'Cover','LRT',0,'C');
							$this->SetX($line['6']);
							$this->Cell($line['7'],10,'','LRT',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9'],10,'','LRT',0,'L');
							$this->SetX($line['10']);
							$this->Cell($line['11'],10,'','LRT',0,'C');
							$this->SetX($line['12']);
							$this->Cell($line['13'],10,'','LRT',0,'C');
							$this->SetX($line['14']);
							$this->Cell($line['15'],10,'','LRT',0,'C');
							$this->SetX($line['16']);
							$this->Cell($line['17'],10,'','LRT',0,'C');
							$this->SetX($line['18']);
							$this->Cell($line['19'],10,'','LRT',0,'C');
							$this->SetX($line['20']);
							$this->Cell($line['21'],10,'J. Wkt','LRT',0,'C');
							$this->SetX($line['22']);
							$this->Cell($line['23'],10,'','LRT',0,'C');
							$this->SetX($line['24']);
							$this->Cell($line['25'],10,'Akad s/d','LRT',0,'C');
							$this->SetX($line['26']);
							$this->Cell($line['27'],10,'Tgl Lapor','LRT',0,'C');
							$this->SetX($line['28']);
							$this->Cell($line['29'],10,'Kelengkapan','LRT',0,'C');
							$this->SetX($line['30']);
							$this->Cell($line['31'],10,'Tgl Status','LRT',0,'C');
							$this->SetX($line['32']);
							$this->Cell($line['33'],10,'','LRT',0,'C');
							$this->SetX($line['34']);
							$this->Cell($line['35'],10,'Tgl Lapor','LRT',0,'C');
							$this->SetX($line['36']);
							$this->Cell($line['37'],10,'Asuransi','LRT',0,'C');
							$this->SetX($line['38']);
							$this->Cell($line['39'],10,'Tgl Bayar','LRT',0,'C');
							$this->SetX($line['40']);
							$this->Cell($line['41'],10,'Bayar Ke','LRT',0,'C');
							$this->SetX($line['42']);
							$this->Cell($line['43'],10,'Tgl Bayar','LRT',0,'C');
							$this->SetX($line['44']);
							$this->Cell($line['45'],10,'','LRT',0,'C');
							$this->SetX($line['46']);
							$this->Cell($line['47'],10,'','LRT',0,'C');

							$this->ln(3);

							$this->SetFont('Arial','B',7);
							$this->SetX($line['0']);
							$this->Cell($line['1'],10,'No','LRB',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3'],10,'Cabang','LRB',0,'L');
							$this->SetX($line['4']);
							$this->Cell($line['5'],10,'Asuransi','LRB',0,'C');
							$this->SetX($line['6']);
							$this->Cell($line['7'],10,'Kategori','LRB',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9'],10,'Nama debitur','LRB',0,'L');
							$this->SetX($line['10']);
							$this->Cell($line['11'],10,'Tgl Lahir','LRB',0,'C');
							$this->SetX($line['12']);
							$this->Cell($line['13'],10,'Usia','LRB',0,'C');
							$this->SetX($line['14']);
							$this->Cell($line['15'],10,'Plafond Kredit','LRB',0,'C');
							$this->SetX($line['16']);
							$this->Cell($line['17'],10,'Tuntutan Klaim','LRB',0,'C');
							$this->SetX($line['18']);
							$this->Cell($line['19'],10,'Tgl Akad','LRB',0,'C');
							$this->SetX($line['20']);
							$this->Cell($line['21'],10,'(Th.)','LRB',0,'C');
							$this->SetX($line['22']);
							$this->Cell($line['23'],10,'DOL','LRB',0,'C');
							$this->SetX($line['24']);
							$this->Cell($line['25'],10,'DOL (Hr)','LRB',0,'C');
							$this->SetX($line['26']);
							$this->Cell($line['27'],10,'Asuransi','LRB',0,'C');
							$this->SetX($line['28']);
							$this->Cell($line['29'],10,'Dokumen Klaim','LRB',0,'C');
							$this->SetX($line['30']);
							$this->Cell($line['31'],10,'Lengkap','LRB',0,'C');
							$this->SetX($line['32']);
							$this->Cell($line['33'],10,'Status Klaim','LRB',0,'C');
							$this->SetX($line['34']);
							$this->Cell($line['35'],10,'Klaim','LRB',0,'C');
							$this->SetX($line['36']);
							$this->Cell($line['37'],10,'Bayar (Rp)','LRB',0,'C');
							$this->SetX($line['38']);
							$this->Cell($line['39'],10,'dr Asuransi','LRB',0,'C');
							$this->SetX($line['40']);
							$this->Cell($line['41'],10,'Bank (Rp)','LRB',0,'C');
							$this->SetX($line['42']);
							$this->Cell($line['43'],10,'Ke Client','LRB',0,'C');
							$this->SetX($line['44']);
							$this->Cell($line['45'],10,'Selisih','LRB',0,'C');
							$this->SetX($line['46']);
							$this->Cell($line['47'],10,'KOL','LRB',0,'C');

							$this->ln();
						}
					}
					function Footer()
					{
						// Position at 1.5 cm from bottom
						$this->SetY(-15);
						// Arial italic 8
						$this->SetFont('Arial','I',8);
						// Page number
						$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
						$this->SetX(10);
						$this->SetFont('Arial','I',7);
						$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
						$this->SetX(10);
						$this->SetFont('Arial','I',7);
						$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
					}

				}

				
				$pdf = new PDF('L','mm','A3');

				//$pdf->AliasNbPages();
				//$pdf->AddPage();
				$pdf->SetLeftMargin(20);
				$pdf->SetTopMargin(20);
				$pdf->SetRightMargin(20);


				$line=array(
						"5","5",  // KOL 1
						"10","20",  // KOL 2
						"30","15",  // KOL 3
						"45","20",  // KOL 4
						"65","35",  // KOL 5
						"100","15",  // KOL 6
						"115","10",  // KOL 7
						"125","20",  // KOL 8
						"145","20",  // KOL 9
						"165","15",  // KOL 10
						"180","10",  // KOL 11
						"190","15",  // KOL 12
						"205","13",  // KOL 13
						"218","15",  // KOL 14
						"233","27",  // KOL 15
						"260","15",  // KOL 16
						"275","35",  // KOL 16
						"310","15",  // KOL 17
						"325","15",  // KOL 18
						"340","15",  // KOL 19
						"355","15",  // KOL 20
						"370","15",  // KOL 21
						"385","20",  // KOL 22
						"405","10");  // KOL 23

				$no=1;
						$kol=0;
						while($dataku=mysql_fetch_array($hasilku)){
							


							if($status!==$dataku['keterangan']){

								if($kol!==0){
									$pdf->SetFont('Arial','B',6);
									$pdf->SetX($line['8']);
									$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
									$pdf->SetX($line['14']);
									$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
									$pdf->SetX($line['16']);
									$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
									$pdf->SetX($line['36']);
									$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
									$pdf->SetX($line['40']);
									$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
									$pdf->SetX($line['44']);
									$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');
								
									$pdf->ln(10);
									$no=1;
								}
								
								
								$skredit_jml=0;
								$stotal_claim=0;
								$sasuransi_bayar=0;
								$sbank_bayar=0;
								$stotal_Selisi=0;
								
								$kol=$dataku['kol'];
								$status=$dataku['keterangan'];
								if($status!==''){
									$pdf->AddPage();
									$pdf->AliasNbPages();
									$pdf->Header($kol,$status);
								}
							}
							if($kol!==$dataku['kol']){

								if($kol!==0){
									$pdf->SetFont('Arial','B',6);
									$pdf->SetX($line['8']);
									$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
									$pdf->SetX($line['14']);
									$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
									$pdf->SetX($line['16']);
									$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
									$pdf->SetX($line['36']);
									$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
									$pdf->SetX($line['40']);
									$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
									$pdf->SetX($line['44']);
									$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

									$pdf->ln(10);
									$no=1;
								}
								$kol=$dataku['kol'];


								$skredit_jml=0;
								$stotal_claim=0;
								$sasuransi_bayar=0;
								$sbank_bayar=0;
								$stotal_Selisi=0;

								$pdf->AddPage();
								$pdf->AliasNbPages();
								$pdf->Header($kol,$status);

							}


							$pdf->SetFont('Arial','',6);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$no,1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,strtoupper($dataku['id_cabang']),1,0,'L');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,strtoupper($dataku['name']),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,$dataku['nmproduk'],1,0,'C');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,strtoupper($dataku['nama']),1,0,'L');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,bulan_convert($dataku['tgl_lahir']),1,0,'C');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,$dataku['usia'],1,0,'C');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($dataku['kredit_jumlah']),1,0,'R');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
							$pdf->SetX($line['18']);
							$pdf->Cell($line['19'],5,bulan_convert($dataku['kredit_tgl']),1,0,'C');
							$pdf->SetX($line['20']);
							$pdf->Cell($line['21'],5,$dataku['kredit_tenor'],1,0,'C');
							$pdf->SetX($line['22']);
							$pdf->Cell($line['23'],5,bulan_convert($dataku['dol']),1,0,'C');
							$pdf->SetX($line['24']);
							$pdf->Cell($line['25'],5,$dataku['akad_dol'],1,0,'C');
							$pdf->SetX($line['26']);
							$pdf->Cell($line['27'],5,bulan_convert($dataku['tgl_lapor_klaim']),1,0,'C');
							$pdf->SetX($line['28']);
							$pdf->Cell($line['29'],5,$dataku['keterangan'],1,0,'L');
							$pdf->SetX($line['30']);
							$pdf->Cell($line['31'],5,bulan_convert($dataku['tgl_document_lengkap']),1,0,'C');
							$pdf->SetX($line['32']);
							$pdf->SetFont('Arial','',5);
							$pdf->Cell($line['33'],5,$dataku['status_klaim'],1,0,'L');
							$pdf->SetX($line['34']);
							$pdf->SetFont('Arial','',6);
							$pdf->Cell($line['35'],5,bulan_convert($dataku['input_date']),1,0,'C');
							$pdf->SetX($line['36']);
							$pdf->Cell($line['37'],5,number_format($dataku['total_bayar_asuransi']),1,0,'R');
							$pdf->SetX($line['38']);
							$pdf->Cell($line['39'],5,bulan_convert($dataku['tgl_bayar_asuransi']),1,0,'C');
							$pdf->SetX($line['40']);
							$pdf->Cell($line['41'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');
							$pdf->SetX($line['42']);
							$pdf->Cell($line['43'],5,bulan_convert($dataku['tgl_bayar_ke_client']),1,0,'C');
							$pdf->SetX($line['44']);
							$pdf->Cell($line['45'],5,number_format($dataku['selisih']),1,0,'R');
							$pdf->SetX($line['46']);
							$pdf->Cell($line['47'],5,$dataku['kol'],1,0,'C');


							$kredit_jml+=$dataku['kredit_jumlah'];
							$total_claim+=$dataku['tuntutan_klaim'];
							$asuransi_bayar+=$dataku['total_bayar_asuransi'];
							$bank_bayar+=$dataku['bayar_ke_bank'];
							$total_Selisi+=$dataku['selisih'];

							$skredit_jml+=$dataku['kredit_jumlah'];
							$stotal_claim+=$dataku['tuntutan_klaim'];
							$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
							$sbank_bayar+=$dataku['bayar_ke_bank'];
							$stotal_Selisi+=$dataku['selisih'];




							$no++;
							$pdf->ln();
						}

						$pdf->SetFont('Arial','B',6);

						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'SUB TOTAL KOL '.$kol,0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($skredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($stotal_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($sasuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($sbank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($stotal_Selisi),0,0,'R');

						$pdf->ln(10);
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,'TOTAL ',0,0,'L');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($kredit_jml),0,0,'R');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($total_claim),0,0,'R');
						$pdf->SetX($line['36']);
						$pdf->Cell($line['37'],5,number_format($asuransi_bayar),0,0,'R');
						$pdf->SetX($line['40']);
						$pdf->Cell($line['41'],5,number_format($bank_bayar),0,0,'R');
						$pdf->SetX($line['44']);
						$pdf->Cell($line['45'],5,number_format($total_Selisi),0,0,'R');

				$pdf->SetFont('Times','',7);
				$pdf->Output();
				break;

case "klaim_liable_summary":

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
					$q_status="";
					if(!empty($_REQUEST['status_klaim'])){
						$q_status=" and IF(
						IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
					}


					$q_tglklaim='';
					if(!empty($_REQUEST['tgl1'])){
						$l_tglklaim="Tanggal Lapor ".bulan_convert($_REQUEST ['tgl1'])." s.d ".bulan_convert($_REQUEST ['tgl2'])."";
						$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
					}


					$q_dol='';
					if(!empty($_REQUEST['tgl3'])){
						$l_dol="DOL ".bulan_convert($_REQUEST ['tgl3'])." s.d ".bulan_convert($_REQUEST ['tgl4'])."";
						$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
					}

					$q_kol='';
					if(!empty($_REQUEST['kol'])){
						$q_kol="and
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
					}
					$sqlku="SELECT
							aa.id_cost,
							aa.code,
							aa.status_klaim,
							aa.kol,
							aa.jml_all,
							aa.plafond_all,
							aa.klaim_all,
							bb.jml_bank,
							bb.plafond_bank,
							ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
							ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
							cc.jml_talangan_bank,
							cc.klaim_talangan_bank,
							bb.klaim_bank,
							ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
							ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
							ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
							FROM (
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_all,
							SUM(kredit_jumlah) AS plafond_all,
							SUM(tuntutan_klaim) AS klaim_all FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.tuntutan_klaim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							WHERE fu_ajk_cn.type_claim='Death'
							".$q_status."
							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
							AND fu_ajk_cn.del IS NULL
							AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')) ab
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) aa
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_bank,
							SUM(kredit_jumlah) AS plafond_bank,
							SUM(total_claim) AS klaim_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							WHERE fu_ajk_cn.type_claim='Death'
							".$q_status."
							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
							AND fu_ajk_cn.del IS NULL
							AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
							AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kol=bb.kol
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_talangan_bank,
							SUM(kredit_jumlah) AS plafond_talangan_bank,
							SUM(total_claim) AS klaim_talangan_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							WHERE fu_ajk_cn.type_claim='Death'
							".$q_status."
							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
							AND fu_ajk_cn.del IS NULL
							and fu_ajk_cn.`tgl_bayar_asuransi` is null
							AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
							AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kol=cc.kol

							";

					$hasilku=mysql_query($sqlku);
					class PDF extends FPDF
					{
						// Page header
						function Header()
						{

							$line=array(
									"5","15",
									"20","15",
									"35","25",
									"60","25",
									"85","15",
									"100","25",
									"125","15",
									"140","25",
									"165","15",
									"180","25",
									"205","25",
									"230","15",
									"245","25",
									"270","25");

							$this->SetFont('Arial','B',12);
							$this->Cell(0,5,'DAFTAR '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK PT BANK BUKOPIN, TBK._'.strtoupper($_REQUEST['status_klaim']),0,0,'C');
							$this->ln();

							$this->SetFont('Arial','',10);

							if(!empty($_REQUEST['tgl1'])){
								$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
								$this->ln(5);
							}
							if(!empty($_REQUEST['tgl3'])){
								$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
								$this->ln(5);
							}


							if(!empty($_REQUEST['tgl5'])){
								$this->Cell(0,5,$l_tglinput,0,0,'C');
								$this->ln(5);
							}

							$this->ln(10);

							$this->SetFont('Arial','B',7);
							$this->SetX($line['0']);
							$this->Cell($line['1'],4,'','LRT',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');

							$this->SetX($line['22']);
							$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR KE BANK','LRT',0,'C');

							$this->ln(4);
							$this->SetX($line['0']);
							$this->Cell($line['1'],4,'','LR',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3'],4,'','LRT',0,'L');
							$this->SetX($line['4']);
							$this->Cell($line['5'],4,'','LRT',0,'C');
							$this->SetX($line['6']);
							$this->Cell($line['7'],4,'','LRT',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9'],4,'','LRT',0,'L');
							$this->SetX($line['10']);
							$this->Cell($line['11'],4,'','LRT',0,'C');
							$this->SetX($line['12']);
							$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
							$this->SetX($line['20']);
							$this->Cell($line['21'],4,'','LRT',0,'C');
							$this->SetX($line['22']);
							$this->Cell($line['23'],4,'','LRT',0,'C');
							$this->SetX($line['24']);
							$this->Cell($line['25'],4,'','LRT',0,'C');
							$this->SetX($line['26']);
							$this->Cell($line['27'],4,'','LRT',0,'C');

							$this->ln(4);
							$this->SetX($line['0']);
							$this->Cell($line['1'],4,'KOL','LR',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
							$this->SetX($line['4']);
							$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
							$this->SetX($line['6']);
							$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
							$this->SetX($line['10']);
							$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
							$this->SetX($line['12']);
							$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
							$this->SetX($line['16']);
							$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
							$this->SetX($line['20']);
							$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
							$this->SetX($line['22']);
							$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
							$this->SetX($line['24']);
							$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
							$this->SetX($line['26']);
							$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');

							$this->ln(4);
							$this->SetX($line['0']);
							$this->Cell($line['1'],4,'','LRB',0,'C');
							$this->SetX($line['2']);
							$this->Cell($line['3'],4,'','LRB',0,'L');
							$this->SetX($line['4']);
							$this->Cell($line['5'],4,'','LRB',0,'C');
							$this->SetX($line['6']);
							$this->Cell($line['7'],4,'','LRB',0,'C');
							$this->SetX($line['8']);
							$this->Cell($line['9'],4,'','LRB',0,'L');
							$this->SetX($line['10']);
							$this->Cell($line['11'],4,'','LRB',0,'C');
							$this->SetX($line['12']);
							$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
							$this->SetX($line['14']);
							$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
							$this->SetX($line['16']);
							$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
							$this->SetX($line['18']);
							$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
							$this->SetX($line['20']);
							$this->Cell($line['21'],4,'','LRB',0,'C');
							$this->SetX($line['22']);
							$this->Cell($line['23'],4,'','LRB',0,'C');
							$this->SetX($line['24']);
							$this->Cell($line['25'],4,'','LRB',0,'C');
							$this->SetX($line['26']);
							$this->Cell($line['27'],4,'','LRB',0,'C');
							$this->ln(4);
						}

						// Page footer
						function Footer()
						{
							// Position at 1.5 cm from bottom
							$this->SetY(-15);
							// Arial italic 8
							$this->SetFont('Arial','I',8);
							// Page number
							$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
							$this->SetX(10);
							$this->SetFont('Arial','I',7);
							$this->Cell(0,0,'Dokumen Untuk Klien',0,0,'R');
						}
					}


					$pdf = new PDF('L','mm','A4');
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->SetLeftMargin(20);
					$pdf->SetTopMargin(20);
					$pdf->SetRightMargin(20);


					$line=array(
							"5","15",
							"20","15",
							"35","25",
							"60","25",
							"85","15",
							"100","25",
							"125","15",
							"140","25",
							"165","15",
							"180","25",
							"205","25",
							"230","15",
							"245","25",
							"270","25");
					$no=1;
					$kol=0;
					$ket='';
					while($dataku=mysql_fetch_array($hasilku)){



						$pdf->SetFont('Arial','',7);
						$pdf->SetX($line['0']);
						$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
						$pdf->SetX($line['2']);
						$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
						$pdf->SetX($line['4']);
						$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
						$pdf->SetX($line['6']);
						$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
						$pdf->SetX($line['10']);
						$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
						$pdf->SetX($line['12']);
						$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
						$pdf->SetX($line['14']);
						$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'C');
						$pdf->SetX($line['16']);
						$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
						$pdf->SetX($line['18']);
						$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'C');
						$pdf->SetX($line['20']);
						$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
						$pdf->SetX($line['22']);
						$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
						$pdf->SetX($line['24']);
						$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
						$pdf->SetX($line['26']);
						$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');

						/*$kredit_jml+=$dataku['kredit_jumlah'];
						$total_claim+=$dataku['total_claim'];
						$asuransi_bayar+=$dataku['total_bayar_asuransi'];
						$bank_bayar+=$dataku['bayar_ke_bank'];
						$total_Selisi+=$dataku['selisih'];

						$skredit_jml+=$dataku['kredit_jumlah'];
						$stotal_claim+=$dataku['total_claim'];
						$sasuransi_bayar+=$dataku['total_bayar_asuransi'];
						$sbank_bayar+=$dataku['bayar_ke_bank'];
						$stotal_Selisi+=$dataku['selisih'];
							*/



						$no++;
						$pdf->ln();
					}

					$pdf->SetFont('Arial','B',6);
				/*
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],10,'SUB TOTAL KOL '.$kol,0,0,'L');
					$pdf->SetX($line['14']);
					$pdf->Cell($line['15'],5,number_format($skredit_jml,2),0,0,'R');
					$pdf->SetX($line['16']);
					$pdf->Cell($line['17'],5,number_format($stotal_claim,2),0,0,'R');
					$pdf->SetX($line['34']);
					$pdf->Cell($line['35'],5,number_format($sasuransi_bayar,2),0,0,'R');
					$pdf->SetX($line['38']);
					$pdf->Cell($line['39'],5,number_format($sbank_bayar,2),0,0,'R');
					$pdf->SetX($line['42']);
					$pdf->Cell($line['43'],5,number_format($stotal_Selisi,2),0,0,'R');

					$pdf->ln(10);
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],10,'TOTAL ',0,0,'L');
					$pdf->SetX($line['14']);
					$pdf->Cell($line['15'],5,number_format($kredit_jml,2),0,0,'R');
					$pdf->SetX($line['16']);
					$pdf->Cell($line['17'],5,number_format($total_claim,2),0,0,'R');
					$pdf->SetX($line['34']);
					$pdf->Cell($line['35'],5,number_format($asuransi_bayar,2),0,0,'R');
					$pdf->SetX($line['38']);
					$pdf->Cell($line['39'],5,number_format($bank_bayar,2),0,0,'R');
					$pdf->SetX($line['42']);
					$pdf->Cell($line['43'],5,number_format($total_Selisi,2),0,0,'R');
				*/
					$pdf->SetFont('Times','',7);
					$pdf->Output();
					break;
case "summary_tiering_klaim" :

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
	if($_REQUEST['id_asuransi']!=""){
		$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if($_REQUEST['kol']!=""){
		$q2=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
	}


	$q3="";
	if(!empty($_REQUEST['status_klaim'])){
		$q3="  and IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
								'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
	}

	$q4='';
	if(!empty($_REQUEST['tgl1'])){
		$q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q5='';
	if(!empty($_REQUEST['tgl3'])){
		$q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}
	$sqlku="SELECT
							aa.id_cost,
							aa.code,
							aa.status_klaim,
							aa.kol,
							aa.jml_all,
							aa.plafond_all,
							aa.klaim_all,
							bb.jml_bank,
							bb.plafond_bank,
							ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
							ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
							cc.jml_talangan_bank,
							cc.klaim_talangan_bank,
							bb.klaim_bank,
							ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
							ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
							ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi,
							dd.estimasi
							FROM (
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_all,
							SUM(kredit_jumlah) AS plafond_all,
							SUM(tuntutan_klaim) AS klaim_all FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.tuntutan_klaim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							where fu_ajk_cn.type_claim='Death'
							AND fu_ajk_cn.del IS NULL
							and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
							and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK' and fu_ajk_klaim.sebab_meninggal<>7)
							) ab
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) aa
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_bank,
							SUM(kredit_jumlah) AS plafond_bank,
							SUM(total_claim) AS klaim_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							where fu_ajk_cn.type_claim='Death'
							AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
							AND fu_ajk_cn.del IS NULL
							and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
							and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK' and fu_ajk_klaim.sebab_meninggal<>7)
							) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kol=bb.kol
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_talangan_bank,
							SUM(kredit_jumlah) AS plafond_talangan_bank,
							SUM(total_claim) AS klaim_talangan_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							where fu_ajk_cn.type_claim='Death'
							AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
							and fu_ajk_cn.`tgl_bayar_asuransi` is null
							AND fu_ajk_cn.del IS NULL
							and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
							and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK' and fu_ajk_klaim.sebab_meninggal<>7)) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kol=cc.kol
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_unpaid_bank,
							SUM(estimasi) AS estimasi FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) * fu_ajk_cn.`tuntutan_klaim`/100 AS estimasi,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							WHERE fu_ajk_cn.type_claim='Death'
							AND DATE(fu_ajk_cn.tgl_byr_claim) IS NULL
							AND fu_ajk_cn.del IS NULL
							and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
							AND (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK' AND fu_ajk_klaim.sebab_meninggal<>7)
							) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) dd ON aa.id_cost=dd.id_cost AND aa.id_as=dd.id_as AND aa.status_klaim=dd.status_klaim AND aa.kol=dd.kol
								";

	$hasilku=mysql_query($sqlku);
	class PDF extends FPDF
	{
		// Page header
		function Header()
		{

			$line=array(
					"2","10",
					"12","15",
					"27","23",
					"50","23",
					"73","15",
					"88","23",
					"111","15",
					"126","23",
					"149","15",
					"164","23",
					"187","23",
					"210","15",
					"225","23",
					"248","23",
					"271","23"

			);

			$this->SetFont('Arial','B',12);
			$this->Cell(0,5,'DAFTAR '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK PT BANK BUKOPIN, TBK._'.strtoupper($_REQUEST['status_klaim']),0,0,'C');
			$this->ln();

			$this->SetFont('Arial','',10);

			if(!empty($_REQUEST['tgl1'])){
				$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
				$this->ln(5);
			}
			if(!empty($_REQUEST['tgl3'])){
				$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
				$this->ln(5);
			}


			if(!empty($_REQUEST['tgl5'])){
				$this->Cell(0,5,$l_tglinput,0,0,'C');
				$this->ln(5);
			}

			$this->ln(10);

			$this->SetFont('Arial','B',6);
			$this->SetX($line['0']);
			$this->Cell($line['1'],4,'','LRT',0,'C');
			$this->SetX($line['2']);
			$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
			$this->SetX($line['8']);
			$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');

			$this->SetX($line['22']);
			$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR KE BANK','LRT',0,'C');
			$this->SetX($line['28']);
			$this->Cell($line['29'],4,'','LRT',0,'C');

			$this->ln(4);
			$this->SetX($line['0']);
			$this->Cell($line['1'],4,'','LR',0,'C');
			$this->SetX($line['2']);
			$this->Cell($line['3'],4,'','LRT',0,'L');
			$this->SetX($line['4']);
			$this->Cell($line['5'],4,'','LRT',0,'C');
			$this->SetX($line['6']);
			$this->Cell($line['7'],4,'','LRT',0,'C');
			$this->SetX($line['8']);
			$this->Cell($line['9'],4,'','LRT',0,'L');
			$this->SetX($line['10']);
			$this->Cell($line['11'],4,'','LRT',0,'C');
			$this->SetX($line['12']);
			$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
			$this->SetX($line['20']);
			$this->Cell($line['21'],4,'','LRT',0,'C');
			$this->SetX($line['22']);
			$this->Cell($line['23'],4,'','LRT',0,'C');
			$this->SetX($line['24']);
			$this->Cell($line['25'],4,'','LRT',0,'C');
			$this->SetX($line['26']);
			$this->Cell($line['27'],4,'','LRT',0,'C');
			$this->SetX($line['28']);
			$this->Cell($line['29'],4,'','LR',0,'C');

			$this->ln(4);
			$this->SetX($line['0']);
			$this->Cell($line['1'],4,'KOL','LR',0,'C');
			$this->SetX($line['2']);
			$this->Cell($line['3'],4,'DEBITUR','LR',0,'C');
			$this->SetX($line['4']);
			$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
			$this->SetX($line['6']);
			$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
			$this->SetX($line['8']);
			$this->Cell($line['9'],4,'DEBITUR','LR',0,'C');
			$this->SetX($line['10']);
			$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
			$this->SetX($line['12']);
			$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
			$this->SetX($line['16']);
			$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
			$this->SetX($line['20']);
			$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
			$this->SetX($line['22']);
			$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
			$this->SetX($line['24']);
			$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
			$this->SetX($line['26']);
			$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
			$this->SetX($line['28']);
			$this->Cell($line['29'],4,'ESTIMASI','LR',0,'C');

			$this->ln(4);
			$this->SetX($line['0']);
			$this->Cell($line['1'],4,'','LRB',0,'C');
			$this->SetX($line['2']);
			$this->Cell($line['3'],4,'','LRB',0,'L');
			$this->SetX($line['4']);
			$this->Cell($line['5'],4,'','LRB',0,'C');
			$this->SetX($line['6']);
			$this->Cell($line['7'],4,'','LRB',0,'C');
			$this->SetX($line['8']);
			$this->Cell($line['9'],4,'','LRB',0,'L');
			$this->SetX($line['10']);
			$this->Cell($line['11'],4,'','LRB',0,'C');
			$this->SetX($line['12']);
			$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
			$this->SetX($line['14']);
			$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
			$this->SetX($line['16']);
			$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
			$this->SetX($line['18']);
			$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
			$this->SetX($line['20']);
			$this->Cell($line['21'],4,'','LRB',0,'C');
			$this->SetX($line['22']);
			$this->Cell($line['23'],4,'','LRB',0,'C');
			$this->SetX($line['24']);
			$this->Cell($line['25'],4,'','LRB',0,'C');
			$this->SetX($line['26']);
			$this->Cell($line['27'],4,'','LRB',0,'C');
			$this->SetX($line['28']);
			$this->Cell($line['29'],4,'','LRB',0,'C');
			$this->ln(4);

		}


		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Untuk Klien',0,0,'R');
		}
	}


	$pdf = new PDF('L','mm','A4');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetLeftMargin(20);
	$pdf->SetTopMargin(20);
	$pdf->SetRightMargin(20);


	$line=array(
			"2","10",
			"12","15",
			"27","23",
			"50","23",
			"73","15",
			"88","23",
			"111","15",
			"126","23",
			"149","15",
			"164","23",
			"187","23",
			"210","15",
			"225","23",
			"248","23",
			"271","23"

	);
	$no=1;
	$kol=0;
	$ket='';
	while($dataku=mysql_fetch_array($hasilku)){



		$pdf->SetFont('Arial','',6);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'R');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'R');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'R');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'R');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
		$pdf->SetX($line['18']);
		$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'R');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'R');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'R');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'R');
		$pdf->SetX($line['28']);
		$pdf->Cell($line['29'],5,number_format($dataku['estimasi'],2),1,0,'R');

		/*$kredit_jml+=$dataku['kredit_jumlah'];
		 $total_claim+=$dataku['total_claim'];
		 $asuransi_bayar+=$dataku['total_bayar_asuransi'];
		 $bank_bayar+=$dataku['bayar_ke_bank'];
		 $total_Selisi+=$dataku['selisih'];

		 $skredit_jml+=$dataku['kredit_jumlah'];
		 $stotal_claim+=$dataku['total_claim'];
		 $sasuransi_bayar+=$dataku['total_bayar_asuransi'];
		 $sbank_bayar+=$dataku['bayar_ke_bank'];
		 $stotal_Selisi+=$dataku['selisih'];
		 */



		$no++;
		$pdf->ln();
	}

	$pdf->SetFont('Arial','B',6);
	/*
	 $pdf->SetX($line['8']);
	 $pdf->Cell($line['9'],10,'SUB TOTAL KOL '.$kol,0,0,'L');
	 $pdf->SetX($line['14']);
	 $pdf->Cell($line['15'],5,number_format($skredit_jml,2),0,0,'R');
	 $pdf->SetX($line['16']);
	 $pdf->Cell($line['17'],5,number_format($stotal_claim,2),0,0,'R');
	 $pdf->SetX($line['34']);
	 $pdf->Cell($line['35'],5,number_format($sasuransi_bayar,2),0,0,'R');
	 $pdf->SetX($line['38']);
	 $pdf->Cell($line['39'],5,number_format($sbank_bayar,2),0,0,'R');
	 $pdf->SetX($line['42']);
	 $pdf->Cell($line['43'],5,number_format($stotal_Selisi,2),0,0,'R');

	 $pdf->ln(10);
	 $pdf->SetX($line['8']);
	 $pdf->Cell($line['9'],10,'TOTAL ',0,0,'L');
	 $pdf->SetX($line['14']);
	 $pdf->Cell($line['15'],5,number_format($kredit_jml,2),0,0,'R');
	 $pdf->SetX($line['16']);
	 $pdf->Cell($line['17'],5,number_format($total_claim,2),0,0,'R');
	 $pdf->SetX($line['34']);
	 $pdf->Cell($line['35'],5,number_format($asuransi_bayar,2),0,0,'R');
	 $pdf->SetX($line['38']);
	 $pdf->Cell($line['39'],5,number_format($bank_bayar,2),0,0,'R');
	 $pdf->SetX($line['42']);
	 $pdf->Cell($line['43'],5,number_format($total_Selisi,2),0,0,'R');
	 */
	$pdf->SetFont('Times','',7);
	$pdf->Output();
	break;

case "klaim_nonliable_summary":

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
		$q_status="";
		if(!empty($_REQUEST['status_klaim'])){
			$q_status=" and IF(
						IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
		}


		$q_tglklaim='';
		if(!empty($_REQUEST['tgl1'])){
			$l_tglklaim="Tanggal Lapor ".bulan_convert($_REQUEST ['tgl1'])." s.d ".bulan_convert($_REQUEST ['tgl2'])."";
			$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
		}


		$q_dol='';
		if(!empty($_REQUEST['tgl3'])){
			$l_dol="DOL ".bulan_convert($_REQUEST ['tgl3'])." s.d ".($_REQUEST ['tgl4'])."";
			$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
		}

		$q_kol='';
		if(!empty($_REQUEST['kol'])){
			$q_kol="and
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
		}
		$sqlku="SELECT
							aa.id_cost,
							aa.code,
							aa.status_klaim,
							aa.kol,
							aa.jml_all,
							aa.plafond_all,
							aa.klaim_all,
							bb.jml_bank,
							bb.plafond_bank,
							ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
							ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
							cc.jml_talangan_bank,
							cc.klaim_talangan_bank,
							bb.klaim_bank,
							ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
							ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
							ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
							FROM (
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_all,
							SUM(kredit_jumlah) AS plafond_all,
							SUM(total_claim) AS klaim_all FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id

							where fu_ajk_cn.type_claim='Death'
							".$q_status."
							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
							AND fu_ajk_cn.del IS NULL
							and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')) ab
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) aa
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_bank,
							SUM(kredit_jumlah) AS plafond_bank,
							SUM(total_claim) AS klaim_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM
							fu_ajk_cn
							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id

							where fu_ajk_cn.type_claim='Death'
							".$q_status."
							".$q_tglklaim."
							".$q_dol."
							".$q_kol."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
							AND fu_ajk_cn.del IS NULL
							AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
							and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')) ac
							GROUP BY id_cost,
							id_as,
							`code`,
							status_klaim,
							kol
							) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kol=bb.kol
							LEFT JOIN
							(
							SELECT
							id_cost,
							id_as,
							`code`,
							status_klaim,
							kol,
							COUNT(id) AS jml_talangan_bank,
							SUM(kredit_jumlah) AS plafond_talangan_bank,
							SUM(total_claim) AS klaim_talangan_bank FROM(
							SELECT
							IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
							IFNULL(fu_ajk_dn.id_as,'') AS id_as,
							fu_ajk_asuransi.`code`,
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
							'Dokumen Belum Lengkap')) AS `status_klaim`,
							fu_ajk_klaim.id,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
							FROM

					fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta

					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
					LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk

					where fu_ajk_cn.type_claim='Death'
					".$q_status."
					".$q_tglklaim."
					".$q_dol."
					".$q_kol."
					and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
					and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
					AND fu_ajk_cn.del IS NULL
					and fu_ajk_cn.`tgl_bayar_asuransi` is null
					AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
					and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')) ac

					GROUP BY id_cost,
					id_as,
					`code`,
					status_klaim,
					kol
					) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kol=cc.kol

					";

		$hasilku=mysql_query($sqlku);
		class PDF extends FPDF
		{
			// Page header
			function Header()
			{

				$line=array(
						"5","15",
						"20","15",
						"35","25",
						"60","25",
						"85","15",
						"100","25",
						"125","15",
						"140","25",
						"165","15",
						"180","25",
						"205","25",
						"230","15",
						"245","25",
						"270","25");

				$this->SetFont('Arial','B',12);
				$this->Cell(0,5,'DAFTAR '.str_replace("_", " ", strtoupper($_REQUEST['er'])).' AJK PT BANK BUKOPIN, TBK._'.strtoupper($_REQUEST['status_klaim']),0,0,'C');
				$this->ln();

				$this->SetFont('Arial','',10);

				if(!empty($_REQUEST['tgl1'])){
					$this->Cell(0,5,"TANGGAL LAPOR "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2']),0,0,'C');
					$this->ln(5);
				}
				if(!empty($_REQUEST['tgl3'])){
					$this->Cell(0,5,"DOL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4']),0,0,'C');
					$this->ln(5);
				}


				if(!empty($_REQUEST['tgl5'])){
					$this->Cell(0,5,$l_tglinput,0,0,'C');
					$this->ln(5);
				}

				$this->ln(10);

				$this->SetFont('Arial','B',7);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRT',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');

				$this->SetX($line['22']);
				$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRT',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRT',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRT',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRT',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRT',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRT',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRT',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRT',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'KOL','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRB',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRB',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRB',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRB',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRB',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRB',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['14']);
				$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['18']);
				$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRB',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRB',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRB',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRB',0,'C');
				$this->ln(5);
			}

			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			}
		}


		$pdf = new PDF('L','mm','A4');
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);


		$line=array(
				"5","15",
				"20","15",
				"35","25",
				"60","25",
				"85","15",
				"100","25",
				"125","15",
				"140","25",
				"165","15",
				"180","25",
				"205","25",
				"230","15",
				"245","25",
				"270","25");
		$no=1;
		$kol=0;
		$ket='';
		while($dataku=mysql_fetch_array($hasilku)){



			$pdf->SetFont('Arial','',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
			$pdf->SetX($line['12']);
			$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
			$pdf->SetX($line['14']);
			$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'C');
			$pdf->SetX($line['16']);
			$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');

			/*$kredit_jml+=$dataku['kredit_jumlah'];
			 $total_claim+=$dataku['total_claim'];
			 $asuransi_bayar+=$dataku['total_bayar_asuransi'];
			 $bank_bayar+=$dataku['bayar_ke_bank'];
			 $total_Selisi+=$dataku['selisih'];

			 $skredit_jml+=$dataku['kredit_jumlah'];
			 $stotal_claim+=$dataku['total_claim'];
			 $sasuransi_bayar+=$dataku['total_bayar_asuransi'];
			 $sbank_bayar+=$dataku['bayar_ke_bank'];
			 $stotal_Selisi+=$dataku['selisih'];
			 */



			$no++;
			$pdf->ln();
		}

		$pdf->SetFont('Arial','B',6);
		/*
		 $pdf->SetX($line['8']);
		 $pdf->Cell($line['9'],10,'SUB TOTAL KOL '.$kol,0,0,'L');
		 $pdf->SetX($line['14']);
		 $pdf->Cell($line['15'],5,number_format($skredit_jml,2),0,0,'R');
		 $pdf->SetX($line['16']);
		 $pdf->Cell($line['17'],5,number_format($stotal_claim,2),0,0,'R');
		 $pdf->SetX($line['34']);
		 $pdf->Cell($line['35'],5,number_format($sasuransi_bayar,2),0,0,'R');
		 $pdf->SetX($line['38']);
		 $pdf->Cell($line['39'],5,number_format($sbank_bayar,2),0,0,'R');
		 $pdf->SetX($line['42']);
		 $pdf->Cell($line['43'],5,number_format($stotal_Selisi,2),0,0,'R');

		 $pdf->ln(10);
		 $pdf->SetX($line['8']);
		 $pdf->Cell($line['9'],10,'TOTAL ',0,0,'L');
		 $pdf->SetX($line['14']);
		 $pdf->Cell($line['15'],5,number_format($kredit_jml,2),0,0,'R');
		 $pdf->SetX($line['16']);
		 $pdf->Cell($line['17'],5,number_format($total_claim,2),0,0,'R');
		 $pdf->SetX($line['34']);
		 $pdf->Cell($line['35'],5,number_format($asuransi_bayar,2),0,0,'R');
		 $pdf->SetX($line['38']);
		 $pdf->Cell($line['39'],5,number_format($bank_bayar,2),0,0,'R');
		 $pdf->SetX($line['42']);
		 $pdf->Cell($line['43'],5,number_format($total_Selisi,2),0,0,'R');
		 */
		$pdf->SetFont('Times','',7);
		$pdf->Output();
		break;
case "summary_status_pengajuan"	:

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}


	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}

	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
		$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
	}
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}

	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}

	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}


	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
					if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
					'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
	}

	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}

	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
				IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
				,
				IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}

	$sqlku="SELECT
				fu_ajk_costumer.name as nama_cost,
				fu_ajk_polis.nmproduk,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim,
				COUNT(fu_ajk_peserta.id_peserta) AS jml_peserta,
				SUM(fu_ajk_peserta.kredit_jumlah) AS plafond,
				SUM(fu_ajk_cn.tuntutan_klaim) AS tuntutan_klaim,
				SUM(fu_ajk_cn.total_bayar_asuransi) AS asuransi_bayar,
				SUM(fu_ajk_cn.total_claim) AS bayar_ke_bank
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			    INNER JOIN `fu_ajk_costumer` ON (`fu_ajk_peserta`.`id_cost` = `fu_ajk_costumer`.`id`)
				LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
				WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.confirm_claim !='Pending' 
				AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
				GROUP BY
				fu_ajk_costumer.name,
				fu_ajk_polis.nmproduk,fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim";

						$hasilku=mysql_query($sqlku);
						class PDF extends FPDF
						{
							// Page header
							function Header($produk='')
							{
								if($produk!==''){

									$line=array(
									"15","10",  // KOL 1
									"25","70",  // KOL 2
									"95","20",  // KOL 3
									"115","40",  // KOL 4
									"155","40",  // KOL 5
									"195","40",  // KOL 6
									"235","40",  // KOL 7
									);  // KOL 23



								$this->SetX($line['0']);
								$this->Cell(200,5,strtoupper($produk),0,0,'L');
								$this->ln();

								$this->SetFont('Arial','B',7);
								$this->SetX($line['0']);
								$this->Cell($line['1'],10,'NO',1,0,'C');
								$this->SetX($line['2']);
								$this->Cell($line['3'],10,'STATUS KLAIM',1,0,'L');
								$this->SetX($line['4']);
								$this->Cell($line['5'],10,'JUMLAH',1,0,'C');
								$this->SetX($line['6']);
								$this->Cell($line['7'],10,'PLAFOND',1,0,'C');
								$this->SetX($line['8']);
								$this->Cell($line['9'],10,'TUNTUTAN KLAIM',1,0,'C');
								$this->SetX($line['10']);
								$this->Cell($line['11'],10,'PENERIMAAN DARI ASURANSI',1,0,'C');
								$this->SetX($line['12']);
								$this->Cell($line['13'],10,'PEMBAYARAN KE BANK',1,0,'C');

								$this->ln();
								}else{

									$this->SetFont('Arial','B',12);
									$this->SetX(15);
									$this->Cell(0,5,'DAFTAR SUMMARY REPORT KLAIM BERDASARKAN STATUS PENGAJUAN KLAIM (BANK)',0,0,'L');
									$this->ln();

									$this->SetFont('Arial','',8);


									$sqlnya="select name from fu_ajk_costumer where id=".$_REQUEST['id_cost'];
									$resultnya=mysql_query($sqlnya);
									$datanya=mysql_fetch_array($resultnya);
										$this->SetX(15);
										$this->Cell(0,5,strtoupper($datanya['name']),0,0,'L');
										$this->ln(5);


									if(!empty($_REQUEST['tglcheck1'])){
									$this->SetX(15);
										$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'L');
										$this->ln(5);
									}
									if(!empty($_REQUEST['tglcheck3'])){
										$this->SetX(15);
										$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'L');
										$this->ln(5);
									}


									if(!empty($_REQUEST['tgl5'])){
										$this->Cell(0,5,$l_tglinput,0,0,'C');
										$this->ln(5);
									}

									$this->ln(10);

									$this->SetFont('Arial','B',8);


								}
							}

							// Page footer
							function Footer()
							{
								// Position at 1.5 cm from bottom
								$this->SetY(-15);
								// Arial italic 8
								$this->SetFont('Arial','I',8);
								// Page number
								$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
							}
						}


						$pdf = new PDF('L','mm','A4');

						$pdf->AddPage();
						$pdf->AliasNbPages();
						$line=array(
								"15","10",  // KOL 1
								"25","70",  // KOL 2
								"95","20",  // KOL 3
								"115","40",  // KOL 4
								"155","40",  // KOL 5
								"195","40",  // KOL 6
								"235","40"  // KOL 7
								);

						$no=1;
						$produk='';
						$ket='';
						while($dataku=mysql_fetch_array($hasilku)){

							if($produk!==$dataku['nmproduk']){

								if($produk!==""){
									$pdf->SetFont('Arial','B',8);

									$pdf->SetX($line['2']);
									$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
									$pdf->SetX($line['4']);
									$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
									$pdf->SetX($line['6']);
									$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
									$pdf->SetX($line['8']);
									$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
									$pdf->SetX($line['10']);
									$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
									$pdf->SetX($line['12']);
									$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
									$pdf->ln(10);
								}
								$produk=$dataku['nmproduk'];
								$no=1;
								$sum1=0;
								$sum2=0;
								$sum3=0;
								$sum4=0;
								$sum5=0;

								$pdf->Header($produk);

								$pdf->SetLeftMargin(20);
								$pdf->SetTopMargin(20);
								$pdf->SetRightMargin(20);

							}



							$pdf->SetFont('Arial','',7);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$no,1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,strtoupper($dataku['status_klaim']),1,0,'L');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,number_format($dataku['jml_peserta']),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,number_format($dataku['plafond']),1,0,'R');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,number_format($dataku['asuransi_bayar']),1,0,'R');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');


							$summ1+=$dataku['jml_peserta'];
							$summ2+=$dataku['plafond'];
							$summ3+=$dataku['tuntutan_klaim'];
							$summ4+=$dataku['asuransi_bayar'];
							$summ5+=$dataku['bayar_ke_bank'];

							$sum1+=$dataku['jml_peserta'];
							$sum2+=$dataku['plafond'];
							$sum3+=$dataku['tuntutan_klaim'];
							$sum4+=$dataku['asuransi_bayar'];
							$sum5+=$dataku['bayar_ke_bank'];



							$no++;
							$pdf->ln();
						}

						$pdf->SetFont('Arial','B',8);

						$pdf->SetX($line['2']);
						$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
						$pdf->SetX($line['4']);
						$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
						$pdf->SetX($line['6']);
						$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
						$pdf->SetX($line['10']);
						$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
						$pdf->SetX($line['12']);
						$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
						$pdf->ln(10);


						$pdf->SetFont('Arial','B',8);

						$pdf->SetX($line['2']);
						$pdf->Cell($line['3'],5,'TOTAL',0,0,'L');
						$pdf->SetX($line['4']);
						$pdf->Cell($line['5'],5,number_format($summ1),1,0,'C');
						$pdf->SetX($line['6']);
						$pdf->Cell($line['7'],5,number_format($summ2),1,0,'R');
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,number_format($summ3),1,0,'R');
						$pdf->SetX($line['10']);
						$pdf->Cell($line['11'],5,number_format($summ4),1,0,'R');
						$pdf->SetX($line['12']);
						$pdf->Cell($line['13'],5,number_format($summ5),1,0,'R');
						$pdf->ln(10);
						$pdf->Output();

break;

case "summary_status_kol"	:

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}


	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}

	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
		$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
	}
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}

	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}

	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}


	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
				if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
				'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
	}

	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}

	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
				IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
				,
				IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}

	/*$sqlku="SELECT nama_cost,nmproduk,status_klaim,kol,COUNT(id_peserta) AS jml,SUM(tuntutan_klaim) AS nilai_klaim
			FROM (SELECT
			fu_ajk_costumer.name AS nama_cost,
			fu_ajk_polis.nmproduk,
			fu_ajk_klaim_status.id as id_status_klaim,
			fu_ajk_klaim_status.status_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol,
			fu_ajk_peserta.id_peserta,
			fu_ajk_cn.tuntutan_klaim
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN `fu_ajk_costumer`
			ON (`fu_ajk_peserta`.`id_cost` = `fu_ajk_costumer`.`id`)
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
			WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL
			AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
			) aa
			GROUP BY nama_cost,nmproduk,status_klaim,kol
			ORDER BY nama_cost,nmproduk,status_klaim,kol";*/

	$sqlku='select id,nmproduk from fu_ajk_polis where del is null and id_cost='.$_REQUEST['id_cost'];

	$hasilku=mysql_query($sqlku);
	class PDF extends FPDF
	{
		// Page header
		function Header($produk='')
		{
			if($produk!==''){

				$line=array(
						"5","10",  // KOL 1
						"15","55",  // KOL 2
						"70","20",  // KOL 3
						"90","25",  // KOL 4
						"115","20",  // KOL 5
						"135","25",  // KOL 6
						"160","20",  // KOL 7
						"180","25",  // KOL 8
						"205","20",  // KOL 9
						"225","25",  // KOL 10
						"250","20",  // KOL 9
						"270","25",  // KOL 10
				);  // KOL 23

				$this->SetX($line['0']);
				$this->Cell(200,5,strtoupper($produk),0,0,'L');

				$this->SetX($line['4']);
				$this->Cell($line['7']+$line['5'],10,'KOL 2',1,0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9']+$line['11'],10,'KOL 3',1,0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15'],10,'KOL 4',1,0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17']+$line['19'],10,'KOL 5',1,0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21']+$line['23'],10,'TOTAL',1,0,'C');
				$this->ln();

				$this->SetFont('Arial','B',6);
				$this->SetX($line['0']);
				$this->Cell($line['1'],10,'NO',1,0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],10,'STATUS KLAIM',1,0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],10,'JML DEBITUR',1,0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],10,'NILAI KLAIM',1,0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],10,'JML DEBITUR',1,0,'C');
				$this->SetX($line['10']);
				$this->Cell($line['11'],10,'NILAI KLAIM',1,0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13'],10,'JML DEBITUR',1,0,'C');
				$this->SetX($line['14']);
				$this->Cell($line['15'],10,'NILAI KLAIM',1,0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17'],10,'JML DEBITUR',1,0,'C');
				$this->SetX($line['18']);
				$this->Cell($line['19'],10,'NILAI KLAIM',1,0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],10,'JML DEBITUR',1,0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],10,'NILAI KLAIm',1,0,'C');

				$this->ln();
			}else{

				$this->SetFont('Arial','B',12);
				$this->SetX(5);
				$this->Cell(0,5,'DAFTAR SUMMARY REPORT KLAIM BERDASARKAN KLAIM PER KOL (BANK)',0,0,'L');
				$this->ln();

				$this->SetFont('Arial','',8);


				$sqlnya="select name from fu_ajk_costumer where id=".$_REQUEST['id_cost'];
				$resultnya=mysql_query($sqlnya);
				$datanya=mysql_fetch_array($resultnya);
				$this->SetX(5);
				$this->Cell(0,5,strtoupper($datanya['name']),0,0,'L');
				$this->ln(5);


				if(!empty($_REQUEST['tglcheck1'])){
					$this->SetX(5);
					$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'L');
					$this->ln(5);
				}
				if(!empty($_REQUEST['tglcheck3'])){
					$this->SetX(5);
					$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'L');
					$this->ln(5);
				}


				if(!empty($_REQUEST['tgl5'])){
					$this->Cell(0,5,$l_tglinput,0,0,'C');
					$this->ln(5);
				}

				$this->ln(10);

				$this->SetFont('Arial','B',8);


			}
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
		}
	}


	$pdf = new PDF('L','mm','A4');

	$pdf->AddPage();
	$pdf->AliasNbPages();

				$line=array(
						"5","10",  // KOL 1
						"15","55",  // KOL 2
						"70","20",  // KOL 3
						"90","25",  // KOL 4
						"115","20",  // KOL 5
						"135","25",  // KOL 6
						"160","20",  // KOL 7
						"180","25",  // KOL 8
						"205","20",  // KOL 9
						"225","25",  // KOL 10
						"250","20",  // KOL 9
						"270","25",  // KOL 10
				);  // KOL 23


	$no=1;
	$produk='';
	$ket='';

	$sjml2=0;
	$ssum2=0;
	$sjml3=0;
	$ssum3=0;
	$sjml4=0;
	$ssum4=0;
	$sjml5=0;
	$ssum5=0;
	$sjml_bawah=0;
	$stotal_bawah=0;
	while($dataku=mysql_fetch_array($hasilku)){

		if($produk!==$dataku['nmproduk']){

			if($produk!==""){
				$pdf->SetFont('Arial','B',6);

				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($jml2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($jml3),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($sum3),1,0,'R');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($jml4),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($sum4),1,0,'R');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($jml5),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($sum5),1,0,'R');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($jml_bawah),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($total_bawah),1,0,'R');


				$pdf->ln(15);
			}
			$produk=$dataku['nmproduk'];

			$jml2=0;
			$sum2=0;
			$jml3=0;
			$sum3=0;
			$jml4=0;
			$sum4=0;
			$jml5=0;
			$sum5=0;
			$jml_bawah=0;
			$total_bawah=0;

			$no=1;
			$pdf->Header($produk);

			$pdf->SetLeftMargin(20);
			$pdf->SetTopMargin(20);
			$pdf->SetRightMargin(20);

		}


		$sqlna="SELECT
		    id,`status_klaim`
		    , `order_list`
			FROM
		    `fu_ajk_klaim_status` order by order_list";
		$hasilna=mysql_query($sqlna);
		while($datana=mysql_fetch_array($hasilna)){
			$sqlb="SELECT nama_cost,nmproduk,status_klaim,kol,COUNT(id_peserta) AS jml,SUM(tuntutan_klaim) AS nilai_klaim
			FROM (SELECT
			fu_ajk_costumer.name AS nama_cost,
			fu_ajk_polis.nmproduk,
			fu_ajk_klaim_status.id AS id_status_klaim,
			fu_ajk_klaim_status.status_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol,
			fu_ajk_peserta.id_peserta,
			fu_ajk_cn.tuntutan_klaim
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN `fu_ajk_costumer` ON (`fu_ajk_peserta`.`id_cost` = `fu_ajk_costumer`.`id`)
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
			WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.confirm_claim !='Pending' 
			AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			and fu_ajk_polis.id=".$dataku['id']." and fu_ajk_klaim_status.id=".$datana['id']."
			".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
			) aa
			GROUP BY nama_cost,nmproduk,status_klaim,kol
			ORDER BY nama_cost,nmproduk,status_klaim,kol";

			$hasilb=mysql_query($sqlb);

			$pdf->SetFont('Arial','',6);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$no,1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,strtoupper($datana['status_klaim']),1,0,'L');

			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,'',1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,'',1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,'',1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11'],5,'',1,0,'C');
			$pdf->SetX($line['12']);
			$pdf->Cell($line['13'],5,'',1,0,'C');
			$pdf->SetX($line['14']);
			$pdf->Cell($line['15'],5,'',1,0,'C');
			$pdf->SetX($line['16']);
			$pdf->Cell($line['17'],5,'',1,0,'C');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,'',1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,'',1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,'',1,0,'C');
			$mykol2='';
			$mykol3='';
			$mykol4='';
			$mykol5='';
			$total_kanan=0;
			$jml_kanan=0;
			while($datab=mysql_fetch_array($hasilb)){


				if($datab['kol']=='2'){
					$mykol2='2';
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,$datab['jml'],0,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($datab['nilai_klaim']),0,0,'R');

					$jml2+=$datab['jml'];
					$sum2+=$datab['nilai_klaim'];
					$sjml2+=$datab['jml'];
					$ssum2+=$datab['nilai_klaim'];
				}elseif($datab['kol']=='3'){

					$mykol3='3';
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,$datab['jml'],0,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11'],5,number_format($datab['nilai_klaim']),0,0,'R');

					$jml3+=$datab['jml'];
					$sum3+=$datab['nilai_klaim'];
					$sjml3+=$datab['jml'];
					$ssum3+=$datab['nilai_klaim'];
				}elseif($datab['kol']=='4'){

					$mykol4='4';
					$pdf->SetX($line['12']);
					$pdf->Cell($line['13'],5,$datab['jml'],0,0,'C');
					$pdf->SetX($line['14']);
					$pdf->Cell($line['15'],5,number_format($datab['nilai_klaim']),0,0,'R');

					$jml4+=$datab['jml'];
					$sum4+=$datab['nilai_klaim'];
					$sjml4+=$datab['jml'];
					$ssum4+=$datab['nilai_klaim'];
				}elseif($datab['kol']=='5'){

					$mykol5='5';
					$pdf->SetX($line['16']);
					$pdf->Cell($line['17'],5,$datab['jml'],0,0,'C');
					$pdf->SetX($line['18']);
					$pdf->Cell($line['19'],5,number_format($datab['nilai_klaim']),0,0,'R');

					$jml5+=$datab['jml'];
					$sum5+=$datab['nilai_klaim'];
					$sjml5+=$datab['jml'];
					$ssum5+=$datab['nilai_klaim'];
				}

				$total_kanan+=$datab['nilai_klaim'];
				$jml_kanan+=$datab['jml'];

				$total_bawah+=$datab['nilai_klaim'];
				$jml_bawah+=$datab['jml'];

				$stotal_bawah+=$datab['nilai_klaim'];
				$sjml_bawah+=$datab['jml'];

			}


			if($mykol2==''){

				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,'0',0,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,'0',0,0,'R');
			}
			if($mykol3==''){

				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,'0',0,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,'0',0,0,'R');
			}
			if($mykol4==''){

				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,'0',0,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,'0',0,0,'R');
			}
			if($mykol5==''){

				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,'0',0,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,'0',0,0,'R');
			}


			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($jml_kanan),0,0,'C');

			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($total_kanan),0,0,'R');
			$pdf->Ln();

			$no++;
		}


	}

	$pdf->SetFont('Arial','B',6);

	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($jml2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($jml3),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($sum3),1,0,'R');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($jml4),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($sum4),1,0,'R');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($jml5),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($sum5),1,0,'R');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($jml_bawah),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($total_bawah),1,0,'R');


	$pdf->ln(10);

	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,'TOTAL',0,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($sjml2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($ssum2),1,0,'R');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($sjml3),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($ssum3),1,0,'R');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($sjml4),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($ssum4),1,0,'R');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($sjml5),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($ssum5),1,0,'R');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($sjml_bawah),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($stotal_bawah),1,0,'R');
	$pdf->Output();

	break;
case "summary_status_asuransi"	:

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}


	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}

	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
		$liability=' and fu_ajk_cm.policy_liability="'.$_REQUEST['liability'].'"';
	}
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}

	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}

	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}


	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
	}

	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}

	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
				IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
				,
				IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}

	$sqlku="SELECT
				fu_ajk_costumer.name as nama_cost,
				fu_ajk_asuransi.name as asuransi,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim,
				COUNT(fu_ajk_peserta.id_peserta) AS jml_peserta,
				SUM(fu_ajk_peserta.kredit_jumlah) AS plafond,
				SUM(fu_ajk_cn.tuntutan_klaim) AS tuntutan_klaim,
				SUM(fu_ajk_cn.total_bayar_asuransi) AS asuransi_bayar,
				SUM(fu_ajk_cn.total_claim) AS bayar_ke_bank
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			    INNER JOIN `fu_ajk_costumer` ON (`fu_ajk_peserta`.`id_cost` = `fu_ajk_costumer`.`id`)
				LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
				WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.confirm_claim !='Pending' 
				AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
				GROUP BY
				fu_ajk_costumer.name,
				fu_ajk_asuransi.name,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim";

						$hasilku=mysql_query($sqlku);
						class PDF extends FPDF
						{
							// Page header
							function Header($produk='')
							{
								if($produk!==''){

									$line=array(
									"15","10",  // KOL 1
									"25","70",  // KOL 2
									"95","20",  // KOL 3
									"115","40",  // KOL 4
									"155","40",  // KOL 5
									"195","40",  // KOL 6
									"235","40",  // KOL 7
									);  // KOL 23



								$this->SetX($line['0']);
								$this->Cell(200,5,strtoupper($produk),0,0,'L');
								$this->ln();

								$this->SetFont('Arial','B',7);
								$this->SetX($line['0']);
								$this->Cell($line['1'],10,'NO',1,0,'C');
								$this->SetX($line['2']);
								$this->Cell($line['3'],10,'STATUS KLAIM',1,0,'L');
								$this->SetX($line['4']);
								$this->Cell($line['5'],10,'JUMLAH',1,0,'C');
								$this->SetX($line['6']);
								$this->Cell($line['7'],10,'PLAFOND',1,0,'C');
								$this->SetX($line['8']);
								$this->Cell($line['9'],10,'TUNTUTAN KLAIM',1,0,'C');
								$this->SetX($line['10']);
								$this->Cell($line['11'],10,'PENERIMAAN DARI ASURANSI',1,0,'C');
								$this->SetX($line['12']);
								$this->Cell($line['13'],10,'PEMBAYARAN KE BANK',1,0,'C');

								$this->ln();
								}else{

									$this->SetFont('Arial','B',12);
									$this->SetX(15);
									$this->Cell(0,5,'DAFTAR SUMMARY REPORT KLAIM BERDASARKAN ASURANSI',0,0,'L');
									$this->ln();

									$this->SetFont('Arial','',8);


									$sqlnya="select name from fu_ajk_costumer where id=".$_REQUEST['id_cost'];
									$resultnya=mysql_query($sqlnya);
									$datanya=mysql_fetch_array($resultnya);
										$this->SetX(15);
										$this->Cell(0,5,strtoupper($datanya['name']),0,0,'L');
										$this->ln(5);


									if(!empty($_REQUEST['tglcheck1'])){
									$this->SetX(15);
										$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'L');
										$this->ln(5);
									}
									if(!empty($_REQUEST['tglcheck3'])){
										$this->SetX(15);
										$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'L');
										$this->ln(5);
									}


									if(!empty($_REQUEST['tgl5'])){
										$this->Cell(0,5,$l_tglinput,0,0,'C');
										$this->ln(5);
									}

									$this->ln(10);

									$this->SetFont('Arial','B',8);


								}
							}

							// Page footer
							function Footer()
							{
								// Position at 1.5 cm from bottom
								$this->SetY(-15);
								// Arial italic 8
								$this->SetFont('Arial','I',8);
								// Page number
								$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
								$this->SetX(10);
								$this->SetFont('Arial','I',7);
								$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
							}
						}


						$pdf = new PDF('L','mm','A4');

						$pdf->AddPage();
						$pdf->AliasNbPages();
						$line=array(
								"15","10",  // KOL 1
								"25","70",  // KOL 2
								"95","20",  // KOL 3
								"115","40",  // KOL 4
								"155","40",  // KOL 5
								"195","40",  // KOL 6
								"235","40"  // KOL 7
								);

						$no=1;
						$produk='';
						$ket='';
						while($dataku=mysql_fetch_array($hasilku)){

							if($produk!==$dataku['asuransi']){

								if($produk!==""){
									$pdf->SetFont('Arial','B',8);

									$pdf->SetX($line['2']);
									$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
									$pdf->SetX($line['4']);
									$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
									$pdf->SetX($line['6']);
									$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
									$pdf->SetX($line['8']);
									$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
									$pdf->SetX($line['10']);
									$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
									$pdf->SetX($line['12']);
									$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
									$pdf->ln(10);
								}
								$produk=$dataku['asuransi'];
								$no=1;
								$sum1=0;
								$sum2=0;
								$sum3=0;
								$sum4=0;
								$sum5=0;

								$pdf->Header($produk);

								$pdf->SetLeftMargin(20);
								$pdf->SetTopMargin(20);
								$pdf->SetRightMargin(20);

							}



							$pdf->SetFont('Arial','',7);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$no,1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,strtoupper($dataku['status_klaim']),1,0,'L');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,number_format($dataku['jml_peserta']),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,number_format($dataku['plafond']),1,0,'R');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,number_format($dataku['asuransi_bayar']),1,0,'R');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');


							$summ1+=$dataku['jml_peserta'];
							$summ2+=$dataku['plafond'];
							$summ3+=$dataku['tuntutan_klaim'];
							$summ4+=$dataku['asuransi_bayar'];
							$summ5+=$dataku['bayar_ke_bank'];

							$sum1+=$dataku['jml_peserta'];
							$sum2+=$dataku['plafond'];
							$sum3+=$dataku['tuntutan_klaim'];
							$sum4+=$dataku['asuransi_bayar'];
							$sum5+=$dataku['bayar_ke_bank'];



							$no++;
							$pdf->ln();
						}

						$pdf->SetFont('Arial','B',8);

						$pdf->SetX($line['2']);
						$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
						$pdf->SetX($line['4']);
						$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
						$pdf->SetX($line['6']);
						$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
						$pdf->SetX($line['10']);
						$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
						$pdf->SetX($line['12']);
						$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
						$pdf->ln(10);


						$pdf->SetFont('Arial','B',8);

						$pdf->SetX($line['2']);
						$pdf->Cell($line['3'],5,'TOTAL',0,0,'L');
						$pdf->SetX($line['4']);
						$pdf->Cell($line['5'],5,number_format($summ1),1,0,'C');
						$pdf->SetX($line['6']);
						$pdf->Cell($line['7'],5,number_format($summ2),1,0,'R');
						$pdf->SetX($line['8']);
						$pdf->Cell($line['9'],5,number_format($summ3),1,0,'R');
						$pdf->SetX($line['10']);
						$pdf->Cell($line['11'],5,number_format($summ4),1,0,'R');
						$pdf->SetX($line['12']);
						$pdf->Cell($line['13'],5,number_format($summ5),1,0,'R');
						$pdf->ln(10);
						$pdf->Output();

break;

case "summary_status_liability"	:

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}


	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}

	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
		$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
	}
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}

	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}

	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}


	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
	}

	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}

	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
				IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
				,
				IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
				IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}

	$sqlku="SELECT
				fu_ajk_costumer.name as nama_cost,
				IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK','TIERING','LIABLE') as liability,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim,
				COUNT(fu_ajk_peserta.id_peserta) AS jml_peserta,
				SUM(fu_ajk_peserta.kredit_jumlah) AS plafond,
				SUM(fu_ajk_cn.tuntutan_klaim) AS tuntutan_klaim,
				SUM(fu_ajk_cn.total_bayar_asuransi) AS asuransi_bayar,
				SUM(fu_ajk_cn.total_claim) AS bayar_ke_bank
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			    INNER JOIN `fu_ajk_costumer` ON (`fu_ajk_peserta`.`id_cost` = `fu_ajk_costumer`.`id`)
				LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
				WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.confirm_claim !='Pending' 
				AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor."
				GROUP BY
				fu_ajk_costumer.name,
				fu_ajk_cn.policy_liability,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim_status.status_klaim";

	$hasilku=mysql_query($sqlku);
	class PDF extends FPDF
	{
		// Page header
		function Header($produk='')
		{
			if($produk!==''){

				$line=array(
						"15","10",  // KOL 1
						"25","70",  // KOL 2
						"95","20",  // KOL 3
						"115","40",  // KOL 4
						"155","40",  // KOL 5
						"195","40",  // KOL 6
						"235","40",  // KOL 7
				);  // KOL 23



				$this->SetX($line['0']);
				$this->Cell(200,5,'POLICY LIABILITY : '.strtoupper($produk),0,0,'L');
				$this->ln();

				$this->SetFont('Arial','B',7);
				$this->SetX($line['0']);
				$this->Cell($line['1'],10,'NO',1,0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],10,'STATUS KLAIM',1,0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],10,'JUMLAH',1,0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],10,'PLAFOND',1,0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],10,'TUNTUTAN KLAIM',1,0,'C');
				$this->SetX($line['10']);
				$this->Cell($line['11'],10,'PENERIMAAN DARI ASURANSI',1,0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13'],10,'PEMBAYARAN KE BANK',1,0,'C');

				$this->ln();
			}else{

				$this->SetFont('Arial','B',12);
				$this->SetX(15);
				$this->Cell(0,5,'DAFTAR SUMMARY REPORT KLAIM BERDASARKAN POLICY LIABILY (BANK)',0,0,'L');
				$this->ln();

				$this->SetFont('Arial','',8);


				$sqlnya="select name from fu_ajk_costumer where id=".$_REQUEST['id_cost'];
				$resultnya=mysql_query($sqlnya);
				$datanya=mysql_fetch_array($resultnya);
				$this->SetX(15);
				$this->Cell(0,5,strtoupper($datanya['name']),0,0,'L');
				$this->ln(5);


				if(!empty($_REQUEST['tglcheck1'])){
					$this->SetX(15);
					$this->Cell(0,5,"BERDASARKAN TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'L');
					$this->ln(5);
				}
				if(!empty($_REQUEST['tglcheck3'])){
					$this->SetX(15);
					$this->Cell(0,5,"BERDASARKAN DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'L');
					$this->ln(5);
				}


				if(!empty($_REQUEST['tgl5'])){
					$this->Cell(0,5,$l_tglinput,0,0,'C');
					$this->ln(5);
				}

				$this->ln(10);

				$this->SetFont('Arial','B',8);


			}
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
		}
	}


	$pdf = new PDF('L','mm','A4');

	$pdf->AddPage();
	$pdf->AliasNbPages();
	$line=array(
			"15","10",  // KOL 1
			"25","70",  // KOL 2
			"95","20",  // KOL 3
			"115","40",  // KOL 4
			"155","40",  // KOL 5
			"195","40",  // KOL 6
			"235","40"  // KOL 7
	);

	$no=1;
	$produk='';
	$ket='';
	while($dataku=mysql_fetch_array($hasilku)){

		if($produk!==$dataku['liability']){

			if($produk!==""){
				$pdf->SetFont('Arial','B',8);

				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
				$pdf->ln(10);
			}
			$produk=$dataku['liability'];
			$no=1;
			$sum1=0;
			$sum2=0;
			$sum3=0;
			$sum4=0;
			$sum5=0;

			$pdf->Header($produk);

			$pdf->SetLeftMargin(20);
			$pdf->SetTopMargin(20);
			$pdf->SetRightMargin(20);

		}



		$pdf->SetFont('Arial','',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,$no,1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,strtoupper($dataku['status_klaim']),1,0,'L');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($dataku['jml_peserta']),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($dataku['plafond']),1,0,'R');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($dataku['tuntutan_klaim']),1,0,'R');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($dataku['asuransi_bayar']),1,0,'R');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($dataku['bayar_ke_bank']),1,0,'R');


		$summ1+=$dataku['jml_peserta'];
		$summ2+=$dataku['plafond'];
		$summ3+=$dataku['tuntutan_klaim'];
		$summ4+=$dataku['asuransi_bayar'];
		$summ5+=$dataku['bayar_ke_bank'];

		$sum1+=$dataku['jml_peserta'];
		$sum2+=$dataku['plafond'];
		$sum3+=$dataku['tuntutan_klaim'];
		$sum4+=$dataku['asuransi_bayar'];
		$sum5+=$dataku['bayar_ke_bank'];



		$no++;
		$pdf->ln();
	}

	$pdf->SetFont('Arial','B',8);

	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,'SUBTOTAL',0,0,'L');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($sum1),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($sum2),1,0,'R');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($sum3),1,0,'R');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($sum4),1,0,'R');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($sum5),1,0,'R');
	$pdf->ln(10);


	$pdf->SetFont('Arial','B',8);

	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,'TOTAL',0,0,'L');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($summ1),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($summ2),1,0,'R');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($summ3),1,0,'R');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($summ4),1,0,'R');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($summ5),1,0,'R');
	$pdf->ln(10);
	$pdf->Output();

	break;

case "summary_klaim_tiering_all" :

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
	if($_REQUEST['id_asuransi']!=""){
		$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}

	if($_REQUEST['kol']!=""){
		$q2=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
	}


	$q3="";
	if(!empty($_REQUEST['status_klaim'])){
		$q3="  and if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."' ";
	}

	$q4='';
	if(!empty($_REQUEST['tgl1'])){
		$q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q5='';
	if(!empty($_REQUEST['tgl3'])){
		$q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}
	$sqlku="SELECT
aa.kol,
aa.id_cost,
aa.code,
aa.kategori,
aa.status_klaim,
aa.jml_all,
aa.plafond_all,
aa.klaim_all,
bb.jml_bank,
bb.plafond_bank,
IFNULL(bb.jml_bank,0)-IFNULL(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
IFNULL(bb.klaim_bank,0)-IFNULL(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
cc.jml_talangan_bank,
cc.klaim_talangan_bank,
bb.klaim_bank,
IFNULL(aa.jml_all,0)-IFNULL(bb.jml_bank,0) AS jml_asuransi,
IFNULL(aa.plafond_all,0)-IFNULL(bb.plafond_bank,0) AS plafond_asuransi,
IFNULL(aa.klaim_all,0)-IFNULL(bb.klaim_bank,0) AS klaim_asiransi,
dd.estimasi
FROM (
SELECT
id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol,
COUNT(id) AS jml_all,
SUM(kredit_jumlah) AS plafond_all,
SUM(tuntutan_klaim) AS klaim_all FROM(
SELECT
IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
IFNULL(fu_ajk_dn.id_as,'') AS id_as,
fu_ajk_asuransi.`code`,
IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,
if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap')) AS `status_klaim`,
fu_ajk_klaim.id,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_cn.tuntutan_klaim,
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
,
IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
WHERE fu_ajk_cn.type_claim='Death'
AND fu_ajk_cn.del IS NULL
AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
and fu_ajk_cn.policy_liability='NONLIABLE'
and fu_ajk_cn.confirm_claim !='Pending'
) ab
GROUP BY id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol
) aa
LEFT JOIN
(
SELECT
id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol,
COUNT(id) AS jml_bank,
SUM(kredit_jumlah) AS plafond_bank,
SUM(total_claim) AS klaim_bank FROM(
SELECT
IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
IFNULL(fu_ajk_dn.id_as,'') AS id_as,
fu_ajk_asuransi.`code`,
IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,
if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap'))  AS `status_klaim`,
fu_ajk_klaim.id,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_cn.total_claim,
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
,
IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
WHERE fu_ajk_cn.type_claim='Death'
AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
AND fu_ajk_cn.del IS NULL
AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

and fu_ajk_cn.confirm_claim !='Pending'
and fu_ajk_cn.policy_liability='NONLIABLE'
) ac
GROUP BY id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol
) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
LEFT JOIN
(
SELECT
id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol,
COUNT(id) AS jml_talangan_bank,
SUM(kredit_jumlah) AS plafond_talangan_bank,
SUM(total_claim) AS klaim_talangan_bank FROM(
SELECT
IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
IFNULL(fu_ajk_dn.id_as,'') AS id_as,
fu_ajk_asuransi.`code`,
IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,
if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap'))  AS `status_klaim`,
fu_ajk_klaim.id,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_cn.total_claim,
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
,
IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
WHERE fu_ajk_cn.type_claim='Death'
AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
AND fu_ajk_cn.`tgl_bayar_asuransi` IS NULL
AND fu_ajk_cn.del IS NULL
AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

and fu_ajk_cn.confirm_claim !='Pending'
and fu_ajk_cn.policy_liability='NONLIABLE'
) ac
GROUP BY id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol
) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
LEFT JOIN
(
SELECT
id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol,
COUNT(id) AS jml_unpaid_bank,
SUM(estimasi) AS estimasi FROM(
SELECT
IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
IFNULL(fu_ajk_dn.id_as,'') AS id_as,
fu_ajk_asuransi.`code`,
IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,
if(`id_klaim_status`=6,'Ditolak',
if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
'Dokumen Belum Lengkap'))  AS `status_klaim`,
fu_ajk_klaim.id,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) * fu_ajk_cn.`tuntutan_klaim`/100 AS estimasi,
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
,
IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
FROM
fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
WHERE fu_ajk_cn.type_claim='Death'
AND DATE(fu_ajk_cn.tgl_byr_claim) IS NULL
AND fu_ajk_cn.del IS NULL
AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
and fu_ajk_cn.policy_liability='NONLIABLE'
and fu_ajk_cn.confirm_claim !='Pending'
) ac
GROUP BY id_cost,
id_as,
`code`,
kategori,
status_klaim,
kol
) dd ON aa.id_cost=dd.id_cost AND aa.id_as=dd.id_as AND aa.status_klaim=dd.status_klaim AND aa.kategori=dd.kategori AND aa.kol=dd.kol
ORDER BY
aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc";

	$hasilku=mysql_query($sqlku);

	class PDF extends FPDF
	{
		// Page header
		function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
		{
			if($status_klaim!==''){

			$line=array(
					"2","10",
					"12","15",
					"27","23",
					"50","23",
					"73","15",
					"88","23",
					"111","15",
					"126","23",
					"149","15",
					"164","23",
					"187","23",
					"210","15",
					"225","23",
					"248","23",
					"271","23"

			);
				if($headernya=='1'){
					$this->SetFont('Arial','B',12);
					$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM TIERING ( PERCEPATAN DOL < 1 Tahun)',0,0,'C');
					$this->ln();

					$this->SetFont('Arial','',10);

					if(!empty($_REQUEST['tgl1'])){
						$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
						$this->ln(5);
					}
					if(!empty($_REQUEST['tgl3'])){
						$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
						$this->ln(5);
					}


					if(!empty($_REQUEST['tgl5'])){
						$this->Cell(0,5,$l_tglinput,0,0,'C');
						$this->ln(5);
					}

					$this->ln();
				}

				$this->SetFont('Arial','B',9);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
				$this->ln(5);
				$this->SetFont('Arial','B',7);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRT',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');

				$this->SetX($line['22']);

				if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DALAM PROSES','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DITOLAK'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DI DALAM BANDING','LRT',0,'C');
				}else{
					$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL PROSES DI ASURANSI','LRT',0,'C');
				}
				$this->SetX($line['28']);
				$this->Cell($line['29'],4,'','LRT',0,'C');
				//$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR KE BANK','LRT',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRT',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRT',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRT',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRT',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRT',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRT',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRT',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRT',0,'C');
				$this->SetX($line['28']);
				$this->Cell($line['29'],4,'','LR',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'KOL','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
				$this->SetX($line['28']);
				$this->Cell($line['29'],4,'ESTIMASI','LR',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRB',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRB',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRB',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRB',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRB',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRB',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['14']);
				$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['18']);
				$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRB',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRB',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRB',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRB',0,'C');
				$this->SetX($line['28']);
				$this->Cell($line['29'],4,'','LRB',0,'C');
				$this->ln(4);

			}
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
		}
	}


	$pdf = new PDF('L','mm','A4');


	$pdf->AliasNbPages();
	$pdf->SetLeftMargin(20);
	$pdf->SetTopMargin(20);
	$pdf->SetRightMargin(20);


	$line=array(
			"2","10",
			"12","15",
			"27","23",
			"50","23",
			"73","15",
			"88","23",
			"111","15",
			"126","23",
			"149","15",
			"164","23",
			"187","23",
			"210","15",
			"225","23",
			"248","23",
			"271","23"

	);
	$no=1;
	$kol=0;
	$ket='';
	$status_klaim='';
	$kategori='';
	while($dataku=mysql_fetch_array($hasilku)){
		$head_kol='';
		if($kategori!==$dataku['kategori']){

			if($kategori!==''){

				$pdf->ln(10);
				$pdf->Header('TOTAL','','','0');
				sort($list);
				for ($row = 0; $row < count($list)+1; $row++) {

					if($head_kol!==$list[$row]['0']){
						if($head_kol!==''){
							$pdf->SetFont('Arial','',6);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
							$pdf->SetX($line['18']);
							$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
							$pdf->SetX($line['20']);
							$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
							$pdf->SetX($line['22']);
							$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
							$pdf->SetX($line['24']);
							$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
							$pdf->SetX($line['26']);
							$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
							$pdf->SetX($line['28']);
							$pdf->Cell($line['29'],5,number_format($estimasi,2),1,0,'R');
							$pdf->Ln();
							$head_kol=$list[$row]['0'];

							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
							$estimasi=0;
						}else{

							$head_kol=$list[$row]['0'];

							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
							$estimasi=0;
						}
					}

					$jml_all+=$list[$row]['5'];
					$plafond_all+=$list[$row]['6'];
					$klaim_all+=$list[$row]['7'];
					$jml_bank+=$list[$row]['8'];
					$plafond_bank+=$list[$row]['9'];
					$jml_bayar_asuransi+=$list[$row]['10'];
					$klaim_bayar_asuransi+=$list[$row]['11'];
					$jml_talangan_bank+=$list[$row]['12'];
					$klaim_talangan_bank+=$list[$row]['13'];
					$klaim_bank+=$list[$row]['14'];
					$jml_asuransi+=$list[$row]['15'];
					$plafond_asuransi+=$list[$row]['16'];
					$klaim_asiransi+=$list[$row]['17'];
					$estimasi+=$list[$row]['18'];


				}
				unset($list);
			}

			$pdf->AddPage();
			$pdf->AliasNbPages();
			$kategori=$dataku['kategori'];
			$status_klaim=$dataku['status_klaim'];
			$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');

		}else{
			if($status_klaim!==$dataku['status_klaim']){
				$status_klaim=$dataku['status_klaim'];

				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($_jml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($_plafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($_klaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($_jml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($_plafond_bank,2),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($_jml_bayar_asuransi),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($_klaim_bayar_asuransi,2),1,0,'C');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($_jml_talangan_bank),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($_klaim_talangan_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($_klaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($_jml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($_plafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($_klaim_asiransi,2),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,number_format($_estimasi,2),1,0,'C');
				$pdf->Ln(10);

				$_jml_all	=	0;
				$_plafond_all	=	0;
				$_klaim_all	=	0;
				$_jml_bank	=	0;
				$_plafond_bank	=	0;
				$_jml_bayar_asuransi	=	0;
				$_klaim_bayar_asuransi	=	0;
				$_jml_talangan_bank	=	0;
				$_klaim_talangan_bank	=	0;
				$_klaim_bank	=	0;
				$_jml_asuransi	=	0;
				$_plafond_asuransi	=	0;
				$_klaim_asiransi	=	0;
				$_estimasi	=	0;
				
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');

			}
		}
		
		$pdf->SetFont('Arial','',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'C');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
		$pdf->SetX($line['18']);
		$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
		$pdf->SetX($line['28']);
		$pdf->Cell($line['29'],5,number_format($dataku['estimasi'],2),1,0,'C');
		
		$_jml_all	+=	$dataku['jml_all'];
		$_plafond_all	+=	$dataku['plafond_all'];
		$_klaim_all	+=	$dataku['klaim_all'];
		$_jml_bank	+=	$dataku['jml_bank'];
		$_plafond_bank	+=	$dataku['plafond_bank'];
		$_jml_bayar_asuransi	+=	$dataku['jml_bayar_asuransi'];
		$_klaim_bayar_asuransi	+=	$dataku['klaim_bayar_asuransi'];
		$_jml_talangan_bank	+=	$dataku['jml_talangan_bank'];
		$_klaim_talangan_bank	+=	$dataku['klaim_talangan_bank'];
		$_klaim_bank	+=	$dataku['klaim_bank'];
		$_jml_asuransi	+=	$dataku['jml_asuransi'];
		$_plafond_asuransi	+=	$dataku['plafond_asuransi'];
		$_klaim_asiransi	+=	$dataku['klaim_asiransi'];
		$_estimasi	+=	$dataku['estimasi'];
		
		
		$no++;
		$pdf->ln();

		$list[] = $dataku;


	}

	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($_jml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($_plafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($_klaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($_jml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($_plafond_bank,2),1,0,'C');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($_jml_bayar_asuransi),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($_klaim_bayar_asuransi,2),1,0,'C');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($_jml_talangan_bank),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($_klaim_talangan_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($_klaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($_jml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($_plafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($_klaim_asiransi,2),1,0,'C');
	$pdf->SetX($line['28']);
	$pdf->Cell($line['29'],5,number_format($_estimasi,2),1,0,'C');
	$pdf->ln(35);
	$pdf->Header('TOTAL','','','0');
	sort($list);
	for ($row = 0; $row < count($list)+1; $row++) {


		if($head_kol!==$list[$row]['0']){
			if($head_kol!==''){
				$pdf->SetFont('Arial','',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
				$pdf->SetX($line['28']);
				$pdf->Cell($line['29'],5,number_format($estimasi,2),1,0,'C');
				$pdf->Ln();
				$head_kol=$list[$row]['0'];

				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
				$estimasi=0;
			}else{

				$head_kol=$list[$row]['0'];

				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
				$estimasi=0;
			}
		}

		$jml_all+=$list[$row]['5'];
		$plafond_all+=$list[$row]['6'];
		$klaim_all+=$list[$row]['7'];
		$jml_bank+=$list[$row]['8'];
		$plafond_bank+=$list[$row]['9'];
		$jml_bayar_asuransi+=$list[$row]['10'];
		$klaim_bayar_asuransi+=$list[$row]['11'];
		$jml_talangan_bank+=$list[$row]['12'];
		$klaim_talangan_bank+=$list[$row]['13'];
		$klaim_bank+=$list[$row]['14'];
		$jml_asuransi+=$list[$row]['15'];
		$plafond_asuransi+=$list[$row]['16'];
		$klaim_asiransi+=$list[$row]['17'];
		$estimasi+=$list[$row]['18'];


		$zjml_all+=$list[$row]['5'];
		$zplafond_all+=$list[$row]['6'];
		$zklaim_all+=$list[$row]['7'];
		$zjml_bank+=$list[$row]['8'];
		$zplafond_bank+=$list[$row]['9'];
		$zjml_bayar_asuransi+=$list[$row]['10'];
		$zklaim_bayar_asuransi+=$list[$row]['11'];
		$zjml_talangan_bank+=$list[$row]['12'];
		$zklaim_talangan_bank+=$list[$row]['13'];
		$zklaim_bank+=$list[$row]['14'];
		$zjml_asuransi+=$list[$row]['15'];
		$zplafond_asuransi+=$list[$row]['16'];
		$zklaim_asiransi+=$list[$row]['17'];
		$zestimasi+=$list[$row]['18'];

	}


	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($zplafond_bank,2),1,0,'C');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($zjml_bayar_asuransi),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($zklaim_bayar_asuransi,2),1,0,'C');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($zjml_talangan_bank),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($zklaim_talangan_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	$pdf->SetX($line['28']);
	$pdf->Cell($line['29'],5,number_format($zestimasi,2),1,0,'C');
	$pdf->SetFont('Arial','B',6);
	$pdf->SetFont('Times','',7);
	$pdf->Output();

	break;

case "summary_klaim_nonliable_all" :
	
		function bulan_convert($tanggal){
			$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
			if($dateku!==""){
				$tgl=explode("-", $dateku);
	
				if($tgl['1']=='01'){
					$ls_namabulan =  'Jan';
				}elseif($tgl['1']=='02'){
					$ls_namabulan =  'Feb';
				}elseif($tgl['1']=='03'){
					$ls_namabulan =  'Mar';
				}elseif($tgl['1']=='04'){
					$ls_namabulan =  'Apr';
				}elseif($tgl['1']=='05'){
					$ls_namabulan =  'Mei';
				}elseif($tgl['1']=='06'){
					$ls_namabulan =  'Jun';
				}elseif($tgl['1']=='07'){
					$ls_namabulan =  'Jul';
				}elseif($tgl['1']=='08'){
					$ls_namabulan =  'Agt';
				}elseif($tgl['1']=='09'){
					$ls_namabulan =  'Sep';
				}elseif($tgl['1']=='10'){
					$ls_namabulan =  'Okt';
				}elseif($tgl['1']=='11'){
					$ls_namabulan =  'Nov';
				}elseif($tgl['1']=='12'){
					$ls_namabulan =  'Des';
				}
	
			
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
			}
	
		}
		if($_REQUEST['id_asuransi']!=""){
			$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
		}
	
		if($_REQUEST['kol']!=""){
			$q2=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
		}
	
	
		$q3="";
		if(!empty($_REQUEST['status_klaim'])){
			$q3="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
		}
	
		$q4='';
		if(!empty($_REQUEST['tgl1'])){
			$q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
		}
	
	
		$q5='';
		if(!empty($_REQUEST['tgl3'])){
			$q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
		}
		$sqlku="SELECT
		aa.kol,
		aa.id_cost,
		aa.code,
		aa.kategori,
		aa.status_klaim,
		aa.jml_all,
		aa.plafond_all,
		aa.klaim_all,
		bb.jml_bank,
		bb.plafond_bank,
		IFNULL(bb.jml_bank,0)-IFNULL(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
		IFNULL(bb.klaim_bank,0)-IFNULL(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
		cc.jml_talangan_bank,
		cc.klaim_talangan_bank,
		bb.klaim_bank,
		IFNULL(aa.jml_all,0)-IFNULL(bb.jml_bank,0) AS jml_asuransi,
		IFNULL(aa.plafond_all,0)-IFNULL(bb.plafond_bank,0) AS plafond_asuransi,
		IFNULL(aa.klaim_all,0)-IFNULL(bb.klaim_bank,0) AS klaim_asiransi,
		dd.estimasi
		FROM (
		SELECT
		id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol,
		COUNT(id) AS jml_all,
		SUM(kredit_jumlah) AS plafond_all,
		SUM(tuntutan_klaim) AS klaim_all FROM(
		SELECT
		IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
		IFNULL(fu_ajk_dn.id_as,'') AS id_as,
		fu_ajk_asuransi.`code`,
		fu_ajk_polis.nmproduk as kategori,/*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,*/
		if(`id_klaim_status`=6,'Ditolak',
		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
		'Dokumen Belum Lengkap')) AS `status_klaim`,
		fu_ajk_klaim.id,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_cn.tuntutan_klaim,
		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
		,
		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
		WHERE fu_ajk_cn.type_claim='Death'
		AND fu_ajk_cn.del IS NULL
		AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
			
		and fu_ajk_cn.policy_liability='NONLIABLE'
		and fu_ajk_cn.confirm_claim !='Pending'
		/*AND (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK' AND fu_ajk_klaim.sebab_meninggal<>7)*/
		) ab
		GROUP BY id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol
		) aa
		LEFT JOIN
		(
		SELECT
		id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol,
		COUNT(id) AS jml_bank,
		SUM(kredit_jumlah) AS plafond_bank,
		SUM(total_claim) AS klaim_bank FROM(
		SELECT
		IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
		IFNULL(fu_ajk_dn.id_as,'') AS id_as,
		fu_ajk_asuransi.`code`,
		fu_ajk_polis.nmproduk as kategori,/*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,*/
		if(`id_klaim_status`=6,'Ditolak',
		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
		'Dokumen Belum Lengkap')) AS `status_klaim`,
		fu_ajk_klaim.id,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_cn.total_claim,
		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
		,
		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
		WHERE fu_ajk_cn.type_claim='Death'
		and fu_ajk_cn.confirm_claim !='Pending'
		AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
		AND fu_ajk_cn.del IS NULL
		AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
			
		and fu_ajk_cn.policy_liability='NONLIABLE'
				/*AND (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK' AND fu_ajk_klaim.sebab_meninggal<>7)*/
		) ac
		GROUP BY id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol
		) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
		LEFT JOIN
		(
		SELECT
		id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol,
		COUNT(id) AS jml_talangan_bank,
		SUM(kredit_jumlah) AS plafond_talangan_bank,
		SUM(total_claim) AS klaim_talangan_bank FROM(
		SELECT
		IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
		IFNULL(fu_ajk_dn.id_as,'') AS id_as,
		fu_ajk_asuransi.`code`,
		fu_ajk_polis.nmproduk as kategori,/*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,*/
		if(`id_klaim_status`=6,'Ditolak',
		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
		'Dokumen Belum Lengkap')) AS `status_klaim`,
		fu_ajk_klaim.id,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_cn.total_claim,
		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
		,
		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
		WHERE fu_ajk_cn.type_claim='Death'
		and fu_ajk_cn.confirm_claim !='Pending'
		AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
		AND fu_ajk_cn.`tgl_bayar_asuransi` IS NULL
		AND fu_ajk_cn.del IS NULL
		AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
		/*AND (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK' AND fu_ajk_klaim.sebab_meninggal<>7)*/
			
		and fu_ajk_cn.policy_liability='NONLIABLE'
		) ac
		GROUP BY id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol
		) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
		LEFT JOIN
		(
		SELECT
		id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol,
		COUNT(id) AS jml_unpaid_bank,
		SUM(estimasi) AS estimasi FROM(
		SELECT
		IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
		IFNULL(fu_ajk_dn.id_as,'') AS id_as,
		fu_ajk_asuransi.`code`,
		fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') AS kategori,*/
		if(`id_klaim_status`=6,'Ditolak',
		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
		'Dokumen Belum Lengkap')) AS `status_klaim`,
		fu_ajk_klaim.id,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
		IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) * fu_ajk_cn.`tuntutan_klaim`/100 AS estimasi,
		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
			
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
		,
		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
		WHERE fu_ajk_cn.type_claim='Death'
		and fu_ajk_cn.confirm_claim !='Pending'
		AND DATE(fu_ajk_cn.tgl_byr_claim) IS NULL
		AND fu_ajk_cn.del IS NULL
		AND fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."
		/*AND (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK' AND fu_ajk_klaim.sebab_meninggal<>7)*/
		and fu_ajk_cn.policy_liability='NONLIABLE'
		) ac
		GROUP BY id_cost,
		id_as,
		`code`,
		kategori,
		status_klaim,
		kol
		) dd ON aa.id_cost=dd.id_cost AND aa.id_as=dd.id_as AND aa.status_klaim=dd.status_klaim AND aa.kategori=dd.kategori AND aa.kol=dd.kol
		ORDER BY
		aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc";
	
		$hasilku=mysql_query($sqlku);
	
		class PDF extends FPDF
		{
			// Page header
			function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
			{
				if($status_klaim!==''){
	
					$line=array(
							"2","10",
							"12","15",
							"27","23",
							"50","23",
							"73","15",
							"88","23",
							"111","15",
							"126","23",
							"149","15",
							"164","23",
							"187","23",
							"210","15",
							"225","23",
							"248","23",
							"271","23"
	
					);
					if($headernya=='1'){
						$this->SetFont('Arial','B',12);
						$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM TIERING ( '.$kategori.' DOL < 1 Tahun)',0,0,'C');
						$this->ln();
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tgl1'])){
							$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tgl3'])){
							$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln();
					}
	
					$this->SetFont('Arial','B',9);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
					$this->ln(5);
					$this->SetFont('Arial','B',7);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRT',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');
	
					$this->SetX($line['22']);
	
					if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DALAM PROSES','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DITOLAK'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DI DALAM BANDING','LRT',0,'C');
					}else{
						$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL PROSES DI ASURANSI','LRT',0,'C');
					}
					$this->SetX($line['28']);
					$this->Cell($line['29'],4,'','LRT',0,'C');
					//$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR KE BANK','LRT',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRT',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRT',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRT',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11'],4,'','LRT',0,'C');
					$this->SetX($line['12']);
					$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRT',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRT',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRT',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRT',0,'C');
					$this->SetX($line['28']);
					$this->Cell($line['29'],4,'','LR',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'KOL','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['10']);
					$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['12']);
					$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
					$this->SetX($line['16']);
					$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
					$this->SetX($line['28']);
					$this->Cell($line['29'],4,'ESTIMASI','LR',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRB',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRB',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRB',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRB',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRB',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11'],4,'','LRB',0,'C');
					$this->SetX($line['12']);
					$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
					$this->SetX($line['14']);
					$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
					$this->SetX($line['16']);
					$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
					$this->SetX($line['18']);
					$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRB',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRB',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRB',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRB',0,'C');
					$this->SetX($line['28']);
					$this->Cell($line['29'],4,'','LRB',0,'C');
					$this->ln(4);
	
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
			}
		}
	
	
		$pdf = new PDF('L','mm','A4');
	
	
		//$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);
	
	
		$line=array(
				"2","10",
				"12","15",
				"27","23",
				"50","23",
				"73","15",
				"88","23",
				"111","15",
				"126","23",
				"149","15",
				"164","23",
				"187","23",
				"210","15",
				"225","23",
				"248","23",
				"271","23"
	
		);
		$no=1;
		$kol=0;
		$ket='';
		$status_klaim='';
		$kategori='';
		while($dataku=mysql_fetch_array($hasilku)){
			$head_kol='';
			if($kategori!==$dataku['kategori']){
	
				if($kategori!==''){
	
					$pdf->ln(10);
					$pdf->Header('TOTAL','','','0');
					sort($list);
					for ($row = 0; $row < count($list)+1; $row++) {
	
						if($head_kol!==$list[$row]['0']){
							if($head_kol!==''){
								$pdf->SetFont('Arial','',6);
								$pdf->SetX($line['0']);
								$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
								$pdf->SetX($line['2']);
								$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
								$pdf->SetX($line['4']);
								$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
								$pdf->SetX($line['6']);
								$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
								$pdf->SetX($line['8']);
								$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
								$pdf->SetX($line['10']);
								$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
								$pdf->SetX($line['12']);
								$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
								$pdf->SetX($line['14']);
								$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
								$pdf->SetX($line['16']);
								$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
								$pdf->SetX($line['18']);
								$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
								$pdf->SetX($line['20']);
								$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
								$pdf->SetX($line['22']);
								$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
								$pdf->SetX($line['24']);
								$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
								$pdf->SetX($line['26']);
								$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
								$pdf->SetX($line['28']);
								$pdf->Cell($line['29'],5,number_format($estimasi,2),1,0,'R');
								$pdf->Ln();
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
								$estimasi=0;
							}else{
	
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
								$estimasi=0;
							}
						}
	
						$jml_all+=$list[$row]['5'];
						$plafond_all+=$list[$row]['6'];
						$klaim_all+=$list[$row]['7'];
						$jml_bank+=$list[$row]['8'];
						$plafond_bank+=$list[$row]['9'];
						$jml_bayar_asuransi+=$list[$row]['10'];
						$klaim_bayar_asuransi+=$list[$row]['11'];
						$jml_talangan_bank+=$list[$row]['12'];
						$klaim_talangan_bank+=$list[$row]['13'];
						$klaim_bank+=$list[$row]['14'];
						$jml_asuransi+=$list[$row]['15'];
						$plafond_asuransi+=$list[$row]['16'];
						$klaim_asiransi+=$list[$row]['17'];
						$estimasi+=$list[$row]['18'];
	
	
					}
					unset($list);
				}
	
				$pdf->AddPage();
				$pdf->AliasNbPages();
				$kategori=$dataku['kategori'];
				$status_klaim=$dataku['status_klaim'];
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');
	
			}else{
				if($status_klaim!==$dataku['status_klaim']){

					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11'],5,number_format($oplafond_bank,2),1,0,'C');
					$pdf->SetX($line['12']);
					$pdf->Cell($line['13'],5,number_format($ojml_bayar_asuransi),1,0,'C');
					$pdf->SetX($line['14']);
					$pdf->Cell($line['15'],5,number_format($oklaim_bayar_asuransi,2),1,0,'C');
					$pdf->SetX($line['16']);
					$pdf->Cell($line['17'],5,number_format($ojml_talangan_bank),1,0,'C');
					$pdf->SetX($line['18']);
					$pdf->Cell($line['19'],5,number_format($oklaim_talangan_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
					$pdf->SetX($line['28']);
					$pdf->Cell($line['29'],5,number_format($oestimasi,2),1,0,'C');
					
					$pdf->ln();
					
					$ojml_all=0;
					$oplafond_all=0;
					$oklaim_all=0;
					$ojml_bank=0;
					$oplafond_bank=0;
					$ojml_bayar_asuransi=0;
					$oklaim_bayar_asuransi=0;
					$ojml_talangan_bank=0;
					$oklaim_talangan_bank=0;
					$oklaim_bank=0;
					$ojml_asuransi=0;
					$oplafond_asuransi=0;
					$oklaim_asiransi=0;
					
					
					$status_klaim=$dataku['status_klaim'];
					$pdf->Ln(10);
					$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');
	
				}
			}
	
			$pdf->SetFont('Arial','',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
			$pdf->SetX($line['12']);
			$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
			$pdf->SetX($line['14']);
			$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'C');
			$pdf->SetX($line['16']);
			$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
			$pdf->SetX($line['28']);
			$pdf->Cell($line['29'],5,number_format($dataku['estimasi'],2),1,0,'C');
	
	
			$no++;
			$pdf->ln();


			$ojml_all+=$dataku['jml_all'];
			$oplafond_all+=$dataku['plafond_all'];
			$oklaim_all+=$dataku['klaim_all'];
			$ojml_bank+=$dataku['jml_bank'];
			$oplafond_bank+=$dataku['plafond_bank'];
			$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
			$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
			$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
			$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
			$oklaim_bank+=$dataku['klaim_bank'];
			$ojml_asuransi+=$dataku['jml_asuransi'];
			$oplafond_asuransi+=$dataku['plafond_asuransi'];
			$oklaim_asiransi+=$dataku['klaim_asiransi'];
			$oestimasi+=$dataku['estimasi'];
			
			$list[] = $dataku;
	
	
		}

		
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($oplafond_bank,2),1,0,'C');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($ojml_bayar_asuransi),1,0,'C');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($oklaim_bayar_asuransi,2),1,0,'C');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($ojml_talangan_bank),1,0,'C');
		$pdf->SetX($line['18']);
		$pdf->Cell($line['19'],5,number_format($oklaim_talangan_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
		$pdf->SetX($line['28']);
		$pdf->Cell($line['29'],5,number_format($oestimasi,2),1,0,'C');
			
		$pdf->ln(25);
		$pdf->Header('TOTAL','','','0');
		sort($list);
		for ($row = 0; $row < count($list)+1; $row++) {
	
	
			if($head_kol!==$list[$row]['0']){
				if($head_kol!==''){
					$pdf->SetFont('Arial','',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
					$pdf->SetX($line['12']);
					$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
					$pdf->SetX($line['14']);
					$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
					$pdf->SetX($line['16']);
					$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
					$pdf->SetX($line['18']);
					$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
					$pdf->SetX($line['28']);
					$pdf->Cell($line['29'],5,number_format($estimasi,2),1,0,'C');
					$pdf->Ln();
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
					$estimasi=0;
				}else{
	
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
					$estimasi=0;
				}
			}
	
			$jml_all+=$list[$row]['5'];
			$plafond_all+=$list[$row]['6'];
			$klaim_all+=$list[$row]['7'];
			$jml_bank+=$list[$row]['8'];
			$plafond_bank+=$list[$row]['9'];
			$jml_bayar_asuransi+=$list[$row]['10'];
			$klaim_bayar_asuransi+=$list[$row]['11'];
			$jml_talangan_bank+=$list[$row]['12'];
			$klaim_talangan_bank+=$list[$row]['13'];
			$klaim_bank+=$list[$row]['14'];
			$jml_asuransi+=$list[$row]['15'];
			$plafond_asuransi+=$list[$row]['16'];
			$klaim_asiransi+=$list[$row]['17'];
			$estimasi+=$list[$row]['18'];
	
	
			$zjml_all+=$list[$row]['5'];
			$zplafond_all+=$list[$row]['6'];
			$zklaim_all+=$list[$row]['7'];
			$zjml_bank+=$list[$row]['8'];
			$zplafond_bank+=$list[$row]['9'];
			$zjml_bayar_asuransi+=$list[$row]['10'];
			$zklaim_bayar_asuransi+=$list[$row]['11'];
			$zjml_talangan_bank+=$list[$row]['12'];
			$zklaim_talangan_bank+=$list[$row]['13'];
			$zklaim_bank+=$list[$row]['14'];
			$zjml_asuransi+=$list[$row]['15'];
			$zplafond_asuransi+=$list[$row]['16'];
			$zklaim_asiransi+=$list[$row]['17'];
			$zestimasi+=$list[$row]['18'];
	
		}
	
		
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($zplafond_bank,2),1,0,'C');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($zjml_bayar_asuransi),1,0,'C');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($zklaim_bayar_asuransi,2),1,0,'C');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($zjml_talangan_bank),1,0,'C');
		$pdf->SetX($line['18']);
		$pdf->Cell($line['19'],5,number_format($zklaim_talangan_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
		$pdf->SetX($line['28']);
		$pdf->Cell($line['29'],5,number_format($zestimasi,2),1,0,'C');
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFont('Times','',7);
		$pdf->Output();
	
		break;
	
case "summary_klaim_liable_all" :

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);

			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}

		
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}

	}
	$q_asuransi='';
	if(!empty($_REQUEST['id_asuransi'])){
		$q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."";
	}
	$q_status="";
	if(!empty($_REQUEST['status_klaim'])){
		$q_status=" and if(`id_klaim_status`=6,'Ditolak',
		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
		'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
	}


	$q_tglklaim='';
	if(!empty($_REQUEST['tgl1'])){
		$l_tglklaim="Tanggal Lapor ".bulan_convert($_REQUEST ['tgl1'])." s.d ".bulan_convert($_REQUEST ['tgl2'])."";
		$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
	}


	$q_dol='';
	if(!empty($_REQUEST['tgl3'])){
		$l_dol="DOL ".bulan_convert($_REQUEST ['tgl3'])." s.d ".bulan_convert($_REQUEST ['tgl4'])."";
		$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
	}

	$q_kol='';
	if(!empty($_REQUEST['kol'])){
		$q_kol="and
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}
	$sqlku="SELECT
			aa.kol,
			aa.id_cost,
			aa.code,
			aa.status_klaim,
			aa.kategori,
			aa.jml_all,
			aa.plafond_all,
			aa.klaim_all,
			bb.jml_bank,
			bb.plafond_bank,
			ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
			ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
			cc.jml_talangan_bank,
			cc.klaim_talangan_bank,
			bb.klaim_bank,
			ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
			ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
			ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
			FROM (
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_all,
			SUM(kredit_jumlah) AS plafond_all,
			SUM(tuntutan_klaim) AS klaim_all FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan')*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tuntutan_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'
			".$q_status."
			".$q_tglklaim."
			".$q_dol."
			".$q_kol."
			".$q_asuransi."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ab
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) aa
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_bank,
			SUM(kredit_jumlah) AS plafond_bank,
			SUM(total_claim) AS klaim_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
			".$q_status."
			".$q_tglklaim."
			".$q_dol."
			".$q_kol."
			".$q_asuransi."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_talangan_bank,
			SUM(kredit_jumlah) AS plafond_talangan_bank,
			SUM(total_claim) AS klaim_talangan_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
			".$q_status."
			".$q_tglklaim."
			".$q_dol."
			".$q_kol."
			".$q_asuransi."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.`tgl_bayar_asuransi` is null
			AND DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			status_klaim,
			kategori,
			kol
			) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
			order by
					aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc
							";

	$hasilku=mysql_query($sqlku);
	class PDF extends FPDF
	{
		// Page header
		function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
		{
			if($status_klaim!==''){
			$line=array(
					"5","15",
					"20","15",
					"35","25",
					"60","25",
					"85","15",
					"100","25",
					"125","15",
					"140","25",
					"165","15",
					"180","25",
					"205","25",
					"230","15",
					"245","25",
					"270","25");
				if($headernya=='1'){
					$this->SetFont('Arial','B',12);
					$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM LIABLE '.$kategori.' (Pembayaran Normal)',0,0,'C');
					$this->ln(10);

					$this->SetFont('Arial','',10);

					if(!empty($_REQUEST['tgl1'])){
						$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tgl1'])." s/d ".bulan_convert($_REQUEST['tgl2']),0,0,'C');
						$this->ln(5);
					}
					if(!empty($_REQUEST['tgl3'])){
						$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tgl3'])." s/d ".bulan_convert($_REQUEST['tgl4']),0,0,'C');
						$this->ln(5);
					}


					if(!empty($_REQUEST['tgl5'])){
						$this->Cell(0,5,$l_tglinput,0,0,'C');
						$this->ln(5);
					}

					$this->ln();
				}

				$this->SetFont('Arial','B',9);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');

				$this->ln(5);
				$this->SetFont('Arial','B',7);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRT',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');

				$this->SetX($line['22']);
				if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DALAM PROSES','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DITOLAK'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'KLAIM DI DALAM BANDING','LRT',0,'C');
				}else{
					$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL PROSES DI ASURANSI','LRT',0,'C');
				}
				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRT',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRT',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRT',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRT',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15']+$line['17']+$line['19'],4,'NILAI KLAIM','LRT',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRT',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRT',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRT',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRT',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'KOL','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13']+$line['15'],4,'DARI ASURANSI','LRTB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17']+$line['19'],4,'DANA TALANGAN ADONAI','LRTB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');

				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRB',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRB',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRB',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRB',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRB',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11'],4,'','LRB',0,'C');
				$this->SetX($line['12']);
				$this->Cell($line['13'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['14']);
				$this->Cell($line['15'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['16']);
				$this->Cell($line['17'],4,'DEBITUR','LRB',0,'C');
				$this->SetX($line['18']);
				$this->Cell($line['19'],4,'KLAIM DIBAYAR','LRB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRB',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRB',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRB',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRB',0,'C');
				$this->ln(4);
			}
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Internal Adonai',0,0,'R');

			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
		}
	}


	$pdf = new PDF('L','mm','A4');
	//$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetLeftMargin(20);
	$pdf->SetTopMargin(20);
	$pdf->SetRightMargin(20);


	$line=array(
			"5","15",
			"20","15",
			"35","25",
			"60","25",
			"85","15",
			"100","25",
			"125","15",
			"140","25",
			"165","15",
			"180","25",
			"205","25",
			"230","15",
			"245","25",
			"270","25");
	$no=1;
	$kol=0;
	$ket='';
	$status_klaim='';
	$kategori='';
	while($dataku=mysql_fetch_array($hasilku)){
		$head_kol='';
		if($kategori!==$dataku['kategori']){

			$pdf->SetFont('Arial','B',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11'],5,number_format($oplafond_bank,2),1,0,'C');
			$pdf->SetX($line['12']);
			$pdf->Cell($line['13'],5,number_format($ojml_bayar_asuransi),1,0,'C');
			$pdf->SetX($line['14']);
			$pdf->Cell($line['15'],5,number_format($oklaim_bayar_asuransi,2),1,0,'C');
			$pdf->SetX($line['16']);
			$pdf->Cell($line['17'],5,number_format($ojml_talangan_bank),1,0,'C');
			$pdf->SetX($line['18']);
			$pdf->Cell($line['19'],5,number_format($oklaim_talangan_bank,2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');

			$ojml_all=0;
			$oplafond_all=0;
			$oklaim_all=0;
			$ojml_bank=0;
			$oplafond_bank=0;
			$ojml_bayar_asuransi=0;
			$oklaim_bayar_asuransi=0;
			$ojml_talangan_bank=0;
			$oklaim_talangan_bank=0;
			$oklaim_bank=0;
			$ojml_asuransi=0;
			$oplafond_asuransi=0;
			$oklaim_asiransi=0;
			
			if($kategori!==''){

				$zjml_all=0;
				$zplafond_all=0;
				$zklaim_all=0;
				$zjml_bank=0;
				$zplafond_bank=0;
				$zjml_bayar_asuransi=0;
				$zklaim_bayar_asuransi=0;
				$zjml_talangan_bank=0;
				$zklaim_talangan_bank=0;
				$zklaim_bank=0;
				$zjml_asuransi=0;
				$zplafond_asuransi=0;
				$zklaim_asiransi=0;

				$pdf->ln(10);
				$pdf->Header('TOTAL','','','0');
				sort($list);
				for ($row = 0; $row < count($list)+1; $row++) {

					if($head_kol!==$list[$row]['0']){
						if($head_kol!==''){
							$pdf->SetFont('Arial','',7);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
							$pdf->SetX($line['12']);
							$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
							$pdf->SetX($line['14']);
							$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
							$pdf->SetX($line['16']);
							$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
							$pdf->SetX($line['18']);
							$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
							$pdf->SetX($line['20']);
							$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
							$pdf->SetX($line['22']);
							$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
							$pdf->SetX($line['24']);
							$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
							$pdf->SetX($line['26']);
							$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
							$pdf->Ln();
							$head_kol=$list[$row]['0'];

							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
						}else{

							$head_kol=$list[$row]['0'];

							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
						}
					}

					$jml_all+=$list[$row]['5'];
					$plafond_all+=$list[$row]['6'];
					$klaim_all+=$list[$row]['7'];
					$jml_bank+=$list[$row]['8'];
					$plafond_bank+=$list[$row]['9'];
					$jml_bayar_asuransi+=$list[$row]['10'];
					$klaim_bayar_asuransi+=$list[$row]['11'];
					$jml_talangan_bank+=$list[$row]['12'];
					$klaim_talangan_bank+=$list[$row]['13'];
					$klaim_bank+=$list[$row]['14'];
					$jml_asuransi+=$list[$row]['15'];
					$plafond_asuransi+=$list[$row]['16'];
					$klaim_asiransi+=$list[$row]['17'];

					$zjml_all+=$list[$row]['5'];
					$zplafond_all+=$list[$row]['6'];
					$zklaim_all+=$list[$row]['7'];
					$zjml_bank+=$list[$row]['8'];
					$zplafond_bank+=$list[$row]['9'];
					$zjml_bayar_asuransi+=$list[$row]['10'];
					$zklaim_bayar_asuransi+=$list[$row]['11'];
					$zjml_talangan_bank+=$list[$row]['12'];
					$zklaim_talangan_bank+=$list[$row]['13'];
					$zklaim_bank+=$list[$row]['14'];
					$zjml_asuransi+=$list[$row]['15'];
					$zplafond_asuransi+=$list[$row]['16'];
					$zklaim_asiransi+=$list[$row]['17'];

				}

				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($zplafond_bank,2),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($zjml_bayar_asuransi),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($zklaim_bayar_asuransi,2),1,0,'C');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($zjml_talangan_bank),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($zklaim_talangan_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');

				unset($list);
			}

			$pdf->AddPage();
			$pdf->AliasNbPages();
			$kategori=$dataku['kategori'];
			$status_klaim=$dataku['status_klaim'];
			$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');

		}else{
			if($status_klaim!==$dataku['status_klaim']){
				

				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($oplafond_bank,2),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($ojml_bayar_asuransi),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($oklaim_bayar_asuransi,2),1,0,'C');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($ojml_talangan_bank),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($oklaim_talangan_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
				
				$pdf->ln();
				
				$ojml_all=0;
				$oplafond_all=0;
				$oklaim_all=0;
				$ojml_bank=0;
				$oplafond_bank=0;
				$ojml_bayar_asuransi=0;
				$oklaim_bayar_asuransi=0;
				$ojml_talangan_bank=0;
				$oklaim_talangan_bank=0;
				$oklaim_bank=0;
				$ojml_asuransi=0;
				$oplafond_asuransi=0;
				$oklaim_asiransi=0;
				
				
				$status_klaim=$dataku['status_klaim'];
				$pdf->Ln(10);
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');

			}
		}

		$pdf->SetFont('Arial','',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
		$pdf->SetX($line['12']);
		$pdf->Cell($line['13'],5,number_format($dataku['jml_bayar_asuransi']),1,0,'C');
		$pdf->SetX($line['14']);
		$pdf->Cell($line['15'],5,number_format($dataku['klaim_bayar_asuransi'],2),1,0,'C');
		$pdf->SetX($line['16']);
		$pdf->Cell($line['17'],5,number_format($dataku['jml_talangan_bank']),1,0,'C');
		$pdf->SetX($line['18']);
		$pdf->Cell($line['19'],5,number_format($dataku['klaim_talangan_bank'],2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');


		$no++;
		$pdf->ln();


		$ojml_all+=$dataku['jml_all'];
		$oplafond_all+=$dataku['plafond_all'];
		$oklaim_all+=$dataku['klaim_all'];
		$ojml_bank+=$dataku['jml_bank'];
		$oplafond_bank+=$dataku['plafond_bank'];
		$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
		$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
		$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
		$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
		$oklaim_bank+=$dataku['klaim_bank'];
		$ojml_asuransi+=$dataku['jml_asuransi'];
		$oplafond_asuransi+=$dataku['plafond_asuransi'];
		$oklaim_asiransi+=$dataku['klaim_asiransi'];
		
		
		$list[] = $dataku;


	}


	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($oplafond_bank,2),1,0,'C');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($ojml_bayar_asuransi),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($oklaim_bayar_asuransi,2),1,0,'C');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($ojml_talangan_bank),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($oklaim_talangan_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
	$pdf->ln(10);
	$pdf->Header('TOTAL','','','0');
	sort($list);

	$zjml_all=0;
	$zplafond_all=0;
	$zklaim_all=0;
	$zjml_bank=0;
	$zplafond_bank=0;
	$zjml_bayar_asuransi=0;
	$zklaim_bayar_asuransi=0;
	$zjml_talangan_bank=0;
	$zklaim_talangan_bank=0;
	$zklaim_bank=0;
	$zjml_asuransi=0;
	$zplafond_asuransi=0;
	$zklaim_asiransi=0;


	for ($row = 0; $row < count($list)+1; $row++) {


		if($head_kol!==$list[$row]['0']){
			if(!empty($head_kol)){
				$pdf->SetFont('Arial','',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11'],5,number_format($plafond_bank,2),1,0,'C');
				$pdf->SetX($line['12']);
				$pdf->Cell($line['13'],5,number_format($jml_bayar_asuransi),1,0,'C');
				$pdf->SetX($line['14']);
				$pdf->Cell($line['15'],5,number_format($klaim_bayar_asuransi,2),1,0,'C');
				$pdf->SetX($line['16']);
				$pdf->Cell($line['17'],5,number_format($jml_talangan_bank),1,0,'C');
				$pdf->SetX($line['18']);
				$pdf->Cell($line['19'],5,number_format($klaim_talangan_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
				$pdf->Ln();
				$head_kol=$list[$row]['0'];

				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
			}else{

				$head_kol=$list[$row]['0'];

				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
			}
		}

		$jml_all+=$list[$row]['5'];
		$plafond_all+=$list[$row]['6'];
		$klaim_all+=$list[$row]['7'];
		$jml_bank+=$list[$row]['8'];
		$plafond_bank+=$list[$row]['9'];
		$jml_bayar_asuransi+=$list[$row]['10'];
		$klaim_bayar_asuransi+=$list[$row]['11'];
		$jml_talangan_bank+=$list[$row]['12'];
		$klaim_talangan_bank+=$list[$row]['13'];
		$klaim_bank+=$list[$row]['14'];
		$jml_asuransi+=$list[$row]['15'];
		$plafond_asuransi+=$list[$row]['16'];
		$klaim_asiransi+=$list[$row]['17'];

		$zjml_all+=$list[$row]['5'];
		$zplafond_all+=$list[$row]['6'];
		$zklaim_all+=$list[$row]['7'];
		$zjml_bank+=$list[$row]['8'];
		$zplafond_bank+=$list[$row]['9'];
		$zjml_bayar_asuransi+=$list[$row]['10'];
		$zklaim_bayar_asuransi+=$list[$row]['11'];
		$zjml_talangan_bank+=$list[$row]['12'];
		$zklaim_talangan_bank+=$list[$row]['13'];
		$zklaim_bank+=$list[$row]['14'];
		$zjml_asuransi+=$list[$row]['15'];
		$zplafond_asuransi+=$list[$row]['16'];
		$zklaim_asiransi+=$list[$row]['17'];

	}


	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11'],5,number_format($zplafond_bank,2),1,0,'C');
	$pdf->SetX($line['12']);
	$pdf->Cell($line['13'],5,number_format($zjml_bayar_asuransi),1,0,'C');
	$pdf->SetX($line['14']);
	$pdf->Cell($line['15'],5,number_format($zklaim_bayar_asuransi,2),1,0,'C');
	$pdf->SetX($line['16']);
	$pdf->Cell($line['17'],5,number_format($zjml_talangan_bank),1,0,'C');
	$pdf->SetX($line['18']);
	$pdf->Cell($line['19'],5,number_format($zklaim_talangan_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');


	$pdf->SetFont('Arial','B',6);
	$pdf->SetFont('Times','',7);
	$pdf->Output();

	break;

case "summary_klaim_bank_liable_all" :
	
		function bulan_convert($tanggal){
			$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
			if($dateku!==""){
				$tgl=explode("-", $dateku);
	
				if($tgl['1']=='01'){
					$ls_namabulan =  'Jan';
				}elseif($tgl['1']=='02'){
					$ls_namabulan =  'Feb';
				}elseif($tgl['1']=='03'){
					$ls_namabulan =  'Mar';
				}elseif($tgl['1']=='04'){
					$ls_namabulan =  'Apr';
				}elseif($tgl['1']=='05'){
					$ls_namabulan =  'Mei';
				}elseif($tgl['1']=='06'){
					$ls_namabulan =  'Jun';
				}elseif($tgl['1']=='07'){
					$ls_namabulan =  'Jul';
				}elseif($tgl['1']=='08'){
					$ls_namabulan =  'Agt';
				}elseif($tgl['1']=='09'){
					$ls_namabulan =  'Sep';
				}elseif($tgl['1']=='10'){
					$ls_namabulan =  'Okt';
				}elseif($tgl['1']=='11'){
					$ls_namabulan =  'Nov';
				}elseif($tgl['1']=='12'){
					$ls_namabulan =  'Des';
				}
	
	
				$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
				if($tglku=='30-Nov-'){
					return "";
				}else{
					return $tglku;
				}
			}
	
		}

		if($_REQUEST['id_asuransi']=="all"){
			$asuransi="";
		}else{
			$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
		}
		
		if(empty($_REQUEST['id_polis'])){
			$polis="";
		}else{
			$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
		}
		if($_REQUEST['liability']=='ALL'){
			$liability='';
		}else{
		
			$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
		}

		$tgl_lapor='';
		if($_REQUEST['tglcheck1']!==""){
			$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}

		$tipe_produk='';
		if($_REQUEST['tipe_produk']!=="All"){
			$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
		}

		$tgl_dol='';
		if($_REQUEST['tglcheck3']!==""){
			$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}
		

		if(empty($_REQUEST['status_klaim'])){
			$status_klaim="";
		}else{
			$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
		}

		if(empty($_REQUEST['status_bayar'])){
			$status_bayar="";
		}else{
			$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
		}

		if(empty($_REQUEST['kol'])){
			$kol="";
		}else{
			$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
		
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
		}
		$sqlku="SELECT
			aa.kol,
			aa.id_cost,
			aa.code,
			aa.status_klaim,
			aa.kategori,
			aa.jml_all,
			aa.plafond_all,
			aa.klaim_all,
			bb.jml_bank,
			bb.plafond_bank,
			ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
			ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
			cc.jml_talangan_bank,
			cc.klaim_talangan_bank,
			bb.klaim_bank,
			ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
			ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
			ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
			FROM (
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_all,
			SUM(kredit_jumlah) AS plafond_all,
			SUM(tuntutan_klaim) AS klaim_all FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan')*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tuntutan_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'
			
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ab
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) aa
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_bank,
			SUM(kredit_jumlah) AS plafond_bank,
			SUM(total_claim) AS klaim_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
			
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_talangan_bank,
			SUM(kredit_jumlah) AS plafond_talangan_bank,
			SUM(total_claim) AS klaim_talangan_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
			
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.`tgl_bayar_asuransi` is null
			AND DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			status_klaim,
			kategori,
			kol
			) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
			order by
					aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc
							";
	
		$hasilku=mysql_query($sqlku);
		class PDF extends FPDF
		{
			// Page header
			function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
			{
				if($status_klaim!==''){
					$line=array(
							"10","20",
							"30","20",
							"50","30",
							"80","30",
							"110","20",
							"130","10",
							"140","10",
							"150","10",
							"160","10",
							"170","0",
							"170","30",
							"200","20",
							"220","30",
							"250","30");
					if($headernya=='1'){
						$this->SetFont('Arial','B',12);
						$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM LIABLE '.$kategori.' (Pembayaran Normal)',0,0,'C');
						$this->ln(10);
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln();
					}
	
					$this->SetFont('Arial','B',9);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
	
					$this->ln(5);
					$this->SetFont('Arial','B',7);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRT',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');
	
					$this->SetX($line['22']);
					if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DITOLAK'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
					}else{
						$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
					}
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRT',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRT',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRT',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRT',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRT',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRT',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRT',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRT',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'KOL','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRB',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRB',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRB',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRB',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRB',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRB',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRB',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRB',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRB',0,'C');
					$this->ln(4);
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Dokumen Klien',0,0,'R');
	
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
			}
		}
	
	
		$pdf = new PDF('L','mm','A4');
		//$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);
	
	
		$line=array(
							"10","20",
							"30","20",
							"50","30",
							"80","30",
							"110","20",
							"130","10",
							"140","10",
							"150","10",
							"160","10",
							"170","0",
							"170","30",
							"200","20",
							"220","30",
							"250","30");
		$no=1;
		$kol=0;
		$ket='';
		$status_klaim='';
		$kategori='';
		while($dataku=mysql_fetch_array($hasilku)){
			$head_kol='';
			if($kategori!==$dataku['kategori']){
	
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
				$ojml_all=0;
				$oplafond_all=0;
				$oklaim_all=0;
				$ojml_bank=0;
				$oplafond_bank=0;
				$ojml_bayar_asuransi=0;
				$oklaim_bayar_asuransi=0;
				$ojml_talangan_bank=0;
				$oklaim_talangan_bank=0;
				$oklaim_bank=0;
				$ojml_asuransi=0;
				$oplafond_asuransi=0;
				$oklaim_asiransi=0;
					
				if($kategori!==''){
	
					$zjml_all=0;
					$zplafond_all=0;
					$zklaim_all=0;
					$zjml_bank=0;
					$zplafond_bank=0;
					$zjml_bayar_asuransi=0;
					$zklaim_bayar_asuransi=0;
					$zjml_talangan_bank=0;
					$zklaim_talangan_bank=0;
					$zklaim_bank=0;
					$zjml_asuransi=0;
					$zplafond_asuransi=0;
					$zklaim_asiransi=0;
	
					$pdf->ln(10);
					$pdf->Header('TOTAL','','','0');
					sort($list);
					for ($row = 0; $row < count($list)+1; $row++) {
	
						if($head_kol!==$list[$row]['0']){
							if($head_kol!==''){
								$pdf->SetFont('Arial','',7);
								$pdf->SetX($line['0']);
								$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
								$pdf->SetX($line['2']);
								$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
								$pdf->SetX($line['4']);
								$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
								$pdf->SetX($line['6']);
								$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
								$pdf->SetX($line['8']);
								$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
								$pdf->SetX($line['10']);
								$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
								$pdf->SetX($line['20']);
								$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
								$pdf->SetX($line['22']);
								$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
								$pdf->SetX($line['24']);
								$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
								$pdf->SetX($line['26']);
								$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
								$pdf->Ln();
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}else{
	
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}
						}
	
						$jml_all+=$list[$row]['5'];
						$plafond_all+=$list[$row]['6'];
						$klaim_all+=$list[$row]['7'];
						$jml_bank+=$list[$row]['8'];
						$plafond_bank+=$list[$row]['9'];
						$jml_bayar_asuransi+=$list[$row]['10'];
						$klaim_bayar_asuransi+=$list[$row]['11'];
						$jml_talangan_bank+=$list[$row]['12'];
						$klaim_talangan_bank+=$list[$row]['13'];
						$klaim_bank+=$list[$row]['14'];
						$jml_asuransi+=$list[$row]['15'];
						$plafond_asuransi+=$list[$row]['16'];
						$klaim_asiransi+=$list[$row]['17'];
	
						$zjml_all+=$list[$row]['5'];
						$zplafond_all+=$list[$row]['6'];
						$zklaim_all+=$list[$row]['7'];
						$zjml_bank+=$list[$row]['8'];
						$zplafond_bank+=$list[$row]['9'];
						$zjml_bayar_asuransi+=$list[$row]['10'];
						$zklaim_bayar_asuransi+=$list[$row]['11'];
						$zjml_talangan_bank+=$list[$row]['12'];
						$zklaim_talangan_bank+=$list[$row]['13'];
						$zklaim_bank+=$list[$row]['14'];
						$zjml_asuransi+=$list[$row]['15'];
						$zplafond_asuransi+=$list[$row]['16'];
						$zklaim_asiransi+=$list[$row]['17'];
	
					}
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
					unset($list);
				}
	
				$pdf->AddPage();
				$pdf->AliasNbPages();
				$kategori=$dataku['kategori'];
				$status_klaim=$dataku['status_klaim'];
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');
	
			}else{
				if($status_klaim!==$dataku['status_klaim']){
	
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
					$pdf->ln();
	
					$ojml_all=0;
					$oplafond_all=0;
					$oklaim_all=0;
					$ojml_bank=0;
					$oplafond_bank=0;
					$ojml_bayar_asuransi=0;
					$oklaim_bayar_asuransi=0;
					$ojml_talangan_bank=0;
					$oklaim_talangan_bank=0;
					$oklaim_bank=0;
					$ojml_asuransi=0;
					$oplafond_asuransi=0;
					$oklaim_asiransi=0;
	
	
					$status_klaim=$dataku['status_klaim'];
					$pdf->Ln(10);
					$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');
	
				}
			}
	
			$pdf->SetFont('Arial','',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
	
	
			$no++;
			$pdf->ln();
	
	
			$ojml_all+=$dataku['jml_all'];
			$oplafond_all+=$dataku['plafond_all'];
			$oklaim_all+=$dataku['klaim_all'];
			$ojml_bank+=$dataku['jml_bank'];
			$oplafond_bank+=$dataku['plafond_bank'];
			$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
			$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
			$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
			$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
			$oklaim_bank+=$dataku['klaim_bank'];
			$ojml_asuransi+=$dataku['jml_asuransi'];
			$oplafond_asuransi+=$dataku['plafond_asuransi'];
			$oklaim_asiransi+=$dataku['klaim_asiransi'];
	
	
			$list[] = $dataku;
	
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
		$pdf->ln(10);
		$pdf->Header('TOTAL','','','0');
		sort($list);
	
		$zjml_all=0;
		$zplafond_all=0;
		$zklaim_all=0;
		$zjml_bank=0;
		$zplafond_bank=0;
		$zjml_bayar_asuransi=0;
		$zklaim_bayar_asuransi=0;
		$zjml_talangan_bank=0;
		$zklaim_talangan_bank=0;
		$zklaim_bank=0;
		$zjml_asuransi=0;
		$zplafond_asuransi=0;
		$zklaim_asiransi=0;
	
	
		for ($row = 0; $row < count($list)+1; $row++) {
	
	
			if($head_kol!==$list[$row]['0']){
				if(!empty($head_kol)){
					$pdf->SetFont('Arial','',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
					$pdf->Ln();
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}else{
	
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}
			}
	
			$jml_all+=$list[$row]['5'];
			$plafond_all+=$list[$row]['6'];
			$klaim_all+=$list[$row]['7'];
			$jml_bank+=$list[$row]['8'];
			$plafond_bank+=$list[$row]['9'];
			$jml_bayar_asuransi+=$list[$row]['10'];
			$klaim_bayar_asuransi+=$list[$row]['11'];
			$jml_talangan_bank+=$list[$row]['12'];
			$klaim_talangan_bank+=$list[$row]['13'];
			$klaim_bank+=$list[$row]['14'];
			$jml_asuransi+=$list[$row]['15'];
			$plafond_asuransi+=$list[$row]['16'];
			$klaim_asiransi+=$list[$row]['17'];
	
			$zjml_all+=$list[$row]['5'];
			$zplafond_all+=$list[$row]['6'];
			$zklaim_all+=$list[$row]['7'];
			$zjml_bank+=$list[$row]['8'];
			$zplafond_bank+=$list[$row]['9'];
			$zjml_bayar_asuransi+=$list[$row]['10'];
			$zklaim_bayar_asuransi+=$list[$row]['11'];
			$zjml_talangan_bank+=$list[$row]['12'];
			$zklaim_talangan_bank+=$list[$row]['13'];
			$zklaim_bank+=$list[$row]['14'];
			$zjml_asuransi+=$list[$row]['15'];
			$zplafond_asuransi+=$list[$row]['16'];
			$zklaim_asiransi+=$list[$row]['17'];
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
	
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFont('Times','',7);
		$pdf->Output();
	
		break;


case "summary_klaim_bank_nonliable_all" :

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);
	
			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}
	
	
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}
	
	}
	
	if($_REQUEST['id_asuransi']=="all"){
		$asuransi="";
	}else{
		$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
	}
	
	if(empty($_REQUEST['id_polis'])){
		$polis="";
	}else{
		$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
	}
	if($_REQUEST['liability']=='ALL'){
		$liability='';
	}else{
	
		$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
	}
	
	$tgl_lapor='';
	if($_REQUEST['tglcheck1']!==""){
		$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}
	
	$tipe_produk='';
	if($_REQUEST['tipe_produk']!=="All"){
		$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
	}
	
	$tgl_dol='';
	if($_REQUEST['tglcheck3']!==""){
		$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
	}
	
	
	if(empty($_REQUEST['status_klaim'])){
		$status_klaim="";
	}else{
		$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
	}
	
	if(empty($_REQUEST['status_bayar'])){
		$status_bayar="";
	}else{
		$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
	}
	
	if(empty($_REQUEST['kol'])){
		$kol="";
	}else{
		$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
	}
	$sqlku="SELECT
			aa.kol,
			aa.id_cost,
			aa.code,
			aa.status_klaim,
			aa.kategori,
			aa.jml_all,
			aa.plafond_all,
			aa.klaim_all,
			bb.jml_bank,
			bb.plafond_bank,
			ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
			ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
			cc.jml_talangan_bank,
			cc.klaim_talangan_bank,
			bb.klaim_bank,
			ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
			ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
			ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
			FROM (
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_all,
			SUM(kredit_jumlah) AS plafond_all,
			SUM(tuntutan_klaim) AS klaim_all FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan')*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tuntutan_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'
		
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ab
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) aa
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_bank,
			SUM(kredit_jumlah) AS plafond_bank,
			SUM(total_claim) AS klaim_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
		
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			AND  DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_talangan_bank,
			SUM(kredit_jumlah) AS plafond_talangan_bank,
			SUM(total_claim) AS klaim_talangan_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
		
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.`tgl_bayar_asuransi` is null
			AND DATE(fu_ajk_cn.tgl_byr_claim) IS NOT NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			status_klaim,
			kategori,
			kol
			) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
			order by
					aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc
							";
	
	$hasilku=mysql_query($sqlku);
	class PDF extends FPDF
	{
		// Page header
		function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
		{
			if($status_klaim!==''){
				$line=array(
						"10","20",
						"30","20",
						"50","30",
						"80","30",
						"110","20",
						"130","10",
						"140","10",
						"150","10",
						"160","10",
						"170","0",
						"170","30",
						"200","20",
						"220","30",
						"250","30");
				if($headernya=='1'){
					$this->SetFont('Arial','B',12);
					$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM Tiering '.$kategori.' (Percepatan DOL < 1 Tahun)',0,0,'C');
					$this->ln(10);
	
					$this->SetFont('Arial','',10);
	
					if(!empty($_REQUEST['tglcheck1'])){
						$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
						$this->ln(5);
					}
					if(!empty($_REQUEST['tglcheck3'])){
						$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
						$this->ln(5);
					}
	
	
					if(!empty($_REQUEST['tgl5'])){
						$this->Cell(0,5,$l_tglinput,0,0,'C');
						$this->ln(5);
					}
	
					$this->ln();
				}
	
				$this->SetFont('Arial','B',9);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
	
				$this->ln(5);
				$this->SetFont('Arial','B',7);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRT',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR KE BANK','LRT',0,'C');
	
				$this->SetX($line['22']);
				if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}elseif(strtoupper($status_klaim)=='DITOLAK'){
					$this->Cell($line['23']+$line['25']+$line['27'],4,'MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}else{
					$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL MENUNGGU APPROVAL ASURANSI','LRT',0,'C');
				}
				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRT',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRT',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRT',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRT',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRT',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRT',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRT',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRT',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRT',0,'C');
	
				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'KOL','LR',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
	
				$this->ln(4);
				$this->SetX($line['0']);
				$this->Cell($line['1'],4,'','LRB',0,'C');
				$this->SetX($line['2']);
				$this->Cell($line['3'],4,'','LRB',0,'L');
				$this->SetX($line['4']);
				$this->Cell($line['5'],4,'','LRB',0,'C');
				$this->SetX($line['6']);
				$this->Cell($line['7'],4,'','LRB',0,'C');
				$this->SetX($line['8']);
				$this->Cell($line['9'],4,'','LRB',0,'L');
				$this->SetX($line['10']);
				$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRB',0,'C');
				$this->SetX($line['20']);
				$this->Cell($line['21'],4,'','LRB',0,'C');
				$this->SetX($line['22']);
				$this->Cell($line['23'],4,'','LRB',0,'C');
				$this->SetX($line['24']);
				$this->Cell($line['25'],4,'','LRB',0,'C');
				$this->SetX($line['26']);
				$this->Cell($line['27'],4,'','LRB',0,'C');
				$this->ln(4);
			}
		}
	
		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Dokumen Klien',0,0,'R');
	
			$this->SetX(10);
			$this->SetFont('Arial','I',7);
			$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
		}
	}
	
	
	$pdf = new PDF('L','mm','A4');
	//$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetLeftMargin(20);
	$pdf->SetTopMargin(20);
	$pdf->SetRightMargin(20);
	
	
	$line=array(
			"10","20",
			"30","20",
			"50","30",
			"80","30",
			"110","20",
			"130","10",
			"140","10",
			"150","10",
			"160","10",
			"170","0",
			"170","30",
			"200","20",
			"220","30",
			"250","30");
	$no=1;
	$kol=0;
	$ket='';
	$status_klaim='';
	$kategori='';
	$asuransi='';
	while($dataku=mysql_fetch_array($hasilku)){
		$head_kol='';
		if($asuransi!==$dataku['code']){
	
			$pdf->SetFont('Arial','B',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
			$ojml_all=0;
			$oplafond_all=0;
			$oklaim_all=0;
			$ojml_bank=0;
			$oplafond_bank=0;
			$ojml_bayar_asuransi=0;
			$oklaim_bayar_asuransi=0;
			$ojml_talangan_bank=0;
			$oklaim_talangan_bank=0;
			$oklaim_bank=0;
			$ojml_asuransi=0;
			$oplafond_asuransi=0;
			$oklaim_asiransi=0;
				
			if($asuransi!==''){
	
				$zjml_all=0;
				$zplafond_all=0;
				$zklaim_all=0;
				$zjml_bank=0;
				$zplafond_bank=0;
				$zjml_bayar_asuransi=0;
				$zklaim_bayar_asuransi=0;
				$zjml_talangan_bank=0;
				$zklaim_talangan_bank=0;
				$zklaim_bank=0;
				$zjml_asuransi=0;
				$zplafond_asuransi=0;
				$zklaim_asiransi=0;

				$pdf->Ln(10);
				$pdf->ln(10);
				$pdf->Header('TOTAL','','','0');
				sort($list);
				for ($row = 0; $row < count($list)+1; $row++) {
	
					if($head_kol!==$list[$row]['0']){
						if($head_kol!==''){
							$pdf->SetFont('Arial','',7);
							$pdf->SetX($line['0']);
							$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
							$pdf->SetX($line['2']);
							$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
							$pdf->SetX($line['4']);
							$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
							$pdf->SetX($line['6']);
							$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
							$pdf->SetX($line['8']);
							$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
							$pdf->SetX($line['10']);
							$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
							$pdf->SetX($line['20']);
							$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
							$pdf->SetX($line['22']);
							$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
							$pdf->SetX($line['24']);
							$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
							$pdf->SetX($line['26']);
							$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
							$pdf->Ln();
							$head_kol=$list[$row]['0'];
	
							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
						}else{
	
							$head_kol=$list[$row]['0'];
	
							$jml_all=0;
							$plafond_all=0;
							$klaim_all=0;
							$jml_bank=0;
							$plafond_bank=0;
							$jml_bayar_asuransi=0;
							$klaim_bayar_asuransi=0;
							$jml_talangan_bank=0;
							$klaim_talangan_bank=0;
							$klaim_bank=0;
							$jml_asuransi=0;
							$plafond_asuransi=0;
							$klaim_asiransi=0;
						}
					}
	
					$jml_all+=$list[$row]['5'];
					$plafond_all+=$list[$row]['6'];
					$klaim_all+=$list[$row]['7'];
					$jml_bank+=$list[$row]['8'];
					$plafond_bank+=$list[$row]['9'];
					$jml_bayar_asuransi+=$list[$row]['10'];
					$klaim_bayar_asuransi+=$list[$row]['11'];
					$jml_talangan_bank+=$list[$row]['12'];
					$klaim_talangan_bank+=$list[$row]['13'];
					$klaim_bank+=$list[$row]['14'];
					$jml_asuransi+=$list[$row]['15'];
					$plafond_asuransi+=$list[$row]['16'];
					$klaim_asiransi+=$list[$row]['17'];
	
					$zjml_all+=$list[$row]['5'];
					$zplafond_all+=$list[$row]['6'];
					$zklaim_all+=$list[$row]['7'];
					$zjml_bank+=$list[$row]['8'];
					$zplafond_bank+=$list[$row]['9'];
					$zjml_bayar_asuransi+=$list[$row]['10'];
					$zklaim_bayar_asuransi+=$list[$row]['11'];
					$zjml_talangan_bank+=$list[$row]['12'];
					$zklaim_talangan_bank+=$list[$row]['13'];
					$zklaim_bank+=$list[$row]['14'];
					$zjml_asuransi+=$list[$row]['15'];
					$zplafond_asuransi+=$list[$row]['16'];
					$zklaim_asiransi+=$list[$row]['17'];
	
				}
	
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
				unset($list);
			}
	
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$kategori=$dataku['kategori'];
			$status_klaim=$dataku['status_klaim'];
			$asuransi=$dataku['code'];
			$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');
	
		}else{
			if($status_klaim!==$dataku['status_klaim']){
	
	
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
				$pdf->ln();
	
				$ojml_all=0;
				$oplafond_all=0;
				$oklaim_all=0;
				$ojml_bank=0;
				$oplafond_bank=0;
				$ojml_bayar_asuransi=0;
				$oklaim_bayar_asuransi=0;
				$ojml_talangan_bank=0;
				$oklaim_talangan_bank=0;
				$oklaim_bank=0;
				$ojml_asuransi=0;
				$oplafond_asuransi=0;
				$oklaim_asiransi=0;
	
	
				$status_klaim=$dataku['status_klaim'];
				$pdf->Ln(10);
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');
	
			}
		}
	
		$pdf->SetFont('Arial','',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
	
	
		$no++;
		$pdf->ln();
	
	
		$ojml_all+=$dataku['jml_all'];
		$oplafond_all+=$dataku['plafond_all'];
		$oklaim_all+=$dataku['klaim_all'];
		$ojml_bank+=$dataku['jml_bank'];
		$oplafond_bank+=$dataku['plafond_bank'];
		$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
		$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
		$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
		$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
		$oklaim_bank+=$dataku['klaim_bank'];
		$ojml_asuransi+=$dataku['jml_asuransi'];
		$oplafond_asuransi+=$dataku['plafond_asuransi'];
		$oklaim_asiransi+=$dataku['klaim_asiransi'];
	
	
		$list[] = $dataku;
	
	
	}


	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
	$pdf->ln(10);
	$pdf->Header('TOTAL','','','0');
	sort($list);
	
	$zjml_all=0;
	$zplafond_all=0;
	$zklaim_all=0;
	$zjml_bank=0;
	$zplafond_bank=0;
	$zjml_bayar_asuransi=0;
	$zklaim_bayar_asuransi=0;
	$zjml_talangan_bank=0;
	$zklaim_talangan_bank=0;
	$zklaim_bank=0;
	$zjml_asuransi=0;
	$zplafond_asuransi=0;
	$zklaim_asiransi=0;
	

	for ($row = 0; $row < count($list)+1; $row++) {
	
	
		if($head_kol!==$list[$row]['0']){
			if(!empty($head_kol)){
				$pdf->SetFont('Arial','',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
				$pdf->Ln();
				$head_kol=$list[$row]['0'];
	
				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
			}else{
	
				$head_kol=$list[$row]['0'];
	
				$jml_all=0;
				$plafond_all=0;
				$klaim_all=0;
				$jml_bank=0;
				$plafond_bank=0;
				$jml_bayar_asuransi=0;
				$klaim_bayar_asuransi=0;
				$jml_talangan_bank=0;
				$klaim_talangan_bank=0;
				$klaim_bank=0;
				$jml_asuransi=0;
				$plafond_asuransi=0;
				$klaim_asiransi=0;
			}
		}
	
		$jml_all+=$list[$row]['5'];
		$plafond_all+=$list[$row]['6'];
		$klaim_all+=$list[$row]['7'];
		$jml_bank+=$list[$row]['8'];
		$plafond_bank+=$list[$row]['9'];
		$jml_bayar_asuransi+=$list[$row]['10'];
		$klaim_bayar_asuransi+=$list[$row]['11'];
		$jml_talangan_bank+=$list[$row]['12'];
		$klaim_talangan_bank+=$list[$row]['13'];
		$klaim_bank+=$list[$row]['14'];
		$jml_asuransi+=$list[$row]['15'];
		$plafond_asuransi+=$list[$row]['16'];
		$klaim_asiransi+=$list[$row]['17'];
	
		$zjml_all+=$list[$row]['5'];
		$zplafond_all+=$list[$row]['6'];
		$zklaim_all+=$list[$row]['7'];
		$zjml_bank+=$list[$row]['8'];
		$zplafond_bank+=$list[$row]['9'];
		$zjml_bayar_asuransi+=$list[$row]['10'];
		$zklaim_bayar_asuransi+=$list[$row]['11'];
		$zjml_talangan_bank+=$list[$row]['12'];
		$zklaim_talangan_bank+=$list[$row]['13'];
		$zklaim_bank+=$list[$row]['14'];
		$zjml_asuransi+=$list[$row]['15'];
		$zplafond_asuransi+=$list[$row]['16'];
		$zklaim_asiransi+=$list[$row]['17'];
	
	}

	
	$pdf->SetFont('Arial','B',7);
	$pdf->SetX($line['0']);
	$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
	$pdf->SetX($line['2']);
	$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
	$pdf->SetX($line['4']);
	$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
	$pdf->SetX($line['6']);
	$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
	$pdf->SetX($line['8']);
	$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
	$pdf->SetX($line['10']);
	$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
	$pdf->SetX($line['20']);
	$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
	$pdf->SetX($line['22']);
	$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
	$pdf->SetX($line['24']);
	$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
	$pdf->SetX($line['26']);
	$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
	
	$pdf->SetFont('Arial','B',6);
	$pdf->SetFont('Times','',7);
	$pdf->Output();
	
	break;

	case "summary_klaim_asuransi_liable_all" :
	
		function bulan_convert($tanggal){
			$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
			if($dateku!==""){
				$tgl=explode("-", $dateku);
	
				if($tgl['1']=='01'){
					$ls_namabulan =  'Jan';
				}elseif($tgl['1']=='02'){
					$ls_namabulan =  'Feb';
				}elseif($tgl['1']=='03'){
					$ls_namabulan =  'Mar';
				}elseif($tgl['1']=='04'){
					$ls_namabulan =  'Apr';
				}elseif($tgl['1']=='05'){
					$ls_namabulan =  'Mei';
				}elseif($tgl['1']=='06'){
					$ls_namabulan =  'Jun';
				}elseif($tgl['1']=='07'){
					$ls_namabulan =  'Jul';
				}elseif($tgl['1']=='08'){
					$ls_namabulan =  'Agt';
				}elseif($tgl['1']=='09'){
					$ls_namabulan =  'Sep';
				}elseif($tgl['1']=='10'){
					$ls_namabulan =  'Okt';
				}elseif($tgl['1']=='11'){
					$ls_namabulan =  'Nov';
				}elseif($tgl['1']=='12'){
					$ls_namabulan =  'Des';
				}
	
	
				$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
				if($tglku=='30-Nov-'){
					return "";
				}else{
					return $tglku;
				}
			}
	
		}
	
		if($_REQUEST['id_asuransi']=="all"){
			$asuransi="";
		}else{
			$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
		}
	
		if(empty($_REQUEST['id_polis'])){
			$polis="";
		}else{
			$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
		}
		if($_REQUEST['liability']=='ALL'){
			$liability='';
		}else{
	
			$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
		}
	
		$tgl_lapor='';
		if($_REQUEST['tglcheck1']!==""){
			$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}
	
		$tipe_produk='';
		if($_REQUEST['tipe_produk']!=="All"){
			$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
		}
	
		$tgl_dol='';
		if($_REQUEST['tglcheck3']!==""){
			$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}
	
	
		if(empty($_REQUEST['status_klaim'])){
			$status_klaim="";
		}else{
			$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
		}
	
		if(empty($_REQUEST['status_bayar'])){
			$status_bayar="";
		}else{
			$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
		}
	
		if(empty($_REQUEST['kol'])){
			$kol="";
		}else{
			$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
		}
		$sqlku="SELECT
			aa.kol,
			aa.id_cost,
			aa.code,
			aa.status_klaim,
			aa.kategori,
			aa.jml_all,
			aa.plafond_all,
			aa.klaim_all,
			bb.jml_bank,
			bb.plafond_bank,
			ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
			ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
			cc.jml_talangan_bank,
			cc.klaim_talangan_bank,
			bb.klaim_bank,
			ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
			ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
			ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
			FROM (
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_all,
			SUM(kredit_jumlah) AS plafond_all,
			SUM(tuntutan_klaim) AS klaim_all FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan')*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tuntutan_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'
		
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ab
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) aa
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_bank,
			SUM(kredit_jumlah) AS plafond_bank,
			SUM(total_claim) AS klaim_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_bayar_asuransi as total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			AND  DATE(fu_ajk_cn.tgl_bayar_asuransi) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_talangan_bank,
			SUM(kredit_jumlah) AS plafond_talangan_bank,
			SUM(total_claim) AS klaim_talangan_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
		
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and DATE(fu_ajk_cn.`tgl_byr_claim`) is null
			AND DATE(fu_ajk_cn.tgl_bayar_asuransi) IS NOT NULL
			and fu_ajk_cn.policy_liability='LIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			status_klaim,
			kategori,
			kol
			) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
			order by
					aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc
							";
	
		$hasilku=mysql_query($sqlku);
		class PDF extends FPDF
		{
			// Page header
			function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
			{
				if($status_klaim!==''){
					$line=array(
							"10","20",
							"30","20",
							"50","30",
							"80","30",
							"110","20",
							"130","10",
							"140","10",
							"150","10",
							"160","10",
							"170","0",
							"170","30",
							"200","20",
							"220","30",
							"250","30");
					if($headernya=='1'){
						$this->SetFont('Arial','B',12);
						$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM LIABLE '.$kategori.' (Pembayaran Normal)',0,0,'C');
						$this->ln(10);
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln();
					}
	
					$this->SetFont('Arial','B',9);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
	
					$this->ln(5);
					$this->SetFont('Arial','B',7);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRT',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR ASURANSI','LRT',0,'C');
	
					$this->SetX($line['22']);
					if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DITOLAK'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}else{
						$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRT',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRT',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRT',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRT',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRT',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRT',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRT',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRT',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'KOL','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'DIBAYAR ASURANSI','LR',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRB',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRB',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRB',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRB',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRB',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRB',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRB',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRB',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRB',0,'C');
					$this->ln(4);
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Dokumen Asuransi',0,0,'R');
	
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
			}
		}
	
	
		$pdf = new PDF('L','mm','A4');
				
		//$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);
	
	
		$line=array(
				"10","20",
				"30","20",
				"50","30",
				"80","30",
				"110","20",
				"130","10",
				"140","10",
				"150","10",
				"160","10",
				"170","0",
				"170","30",
				"200","20",
				"220","30",
				"250","30");
		$no=1;
		$kol=0;
		$ket='';
		$status_klaim='';
		$kategori='';
		while($dataku=mysql_fetch_array($hasilku)){
			$head_kol='';
			if($kategori!==$dataku['kategori']){
	
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
				$ojml_all=0;
				$oplafond_all=0;
				$oklaim_all=0;
				$ojml_bank=0;
				$oplafond_bank=0;
				$ojml_bayar_asuransi=0;
				$oklaim_bayar_asuransi=0;
				$ojml_talangan_bank=0;
				$oklaim_talangan_bank=0;
				$oklaim_bank=0;
				$ojml_asuransi=0;
				$oplafond_asuransi=0;
				$oklaim_asiransi=0;
					
				if($kategori!==''){
	
					$zjml_all=0;
					$zplafond_all=0;
					$zklaim_all=0;
					$zjml_bank=0;
					$zplafond_bank=0;
					$zjml_bayar_asuransi=0;
					$zklaim_bayar_asuransi=0;
					$zjml_talangan_bank=0;
					$zklaim_talangan_bank=0;
					$zklaim_bank=0;
					$zjml_asuransi=0;
					$zplafond_asuransi=0;
					$zklaim_asiransi=0;
	
					$pdf->ln(10);
					$pdf->Header('TOTAL','','','0');
					sort($list);
					for ($row = 0; $row < count($list)+1; $row++) {
	
						if($head_kol!==$list[$row]['0']){
							if($head_kol!==''){
								$pdf->SetFont('Arial','',7);
								$pdf->SetX($line['0']);
								$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
								$pdf->SetX($line['2']);
								$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
								$pdf->SetX($line['4']);
								$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
								$pdf->SetX($line['6']);
								$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
								$pdf->SetX($line['8']);
								$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
								$pdf->SetX($line['10']);
								$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
								$pdf->SetX($line['20']);
								$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
								$pdf->SetX($line['22']);
								$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
								$pdf->SetX($line['24']);
								$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
								$pdf->SetX($line['26']);
								$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
								$pdf->Ln();
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}else{
	
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}
						}
	
						$jml_all+=$list[$row]['5'];
						$plafond_all+=$list[$row]['6'];
						$klaim_all+=$list[$row]['7'];
						$jml_bank+=$list[$row]['8'];
						$plafond_bank+=$list[$row]['9'];
						$jml_bayar_asuransi+=$list[$row]['10'];
						$klaim_bayar_asuransi+=$list[$row]['11'];
						$jml_talangan_bank+=$list[$row]['12'];
						$klaim_talangan_bank+=$list[$row]['13'];
						$klaim_bank+=$list[$row]['14'];
						$jml_asuransi+=$list[$row]['15'];
						$plafond_asuransi+=$list[$row]['16'];
						$klaim_asiransi+=$list[$row]['17'];
	
						$zjml_all+=$list[$row]['5'];
						$zplafond_all+=$list[$row]['6'];
						$zklaim_all+=$list[$row]['7'];
						$zjml_bank+=$list[$row]['8'];
						$zplafond_bank+=$list[$row]['9'];
						$zjml_bayar_asuransi+=$list[$row]['10'];
						$zklaim_bayar_asuransi+=$list[$row]['11'];
						$zjml_talangan_bank+=$list[$row]['12'];
						$zklaim_talangan_bank+=$list[$row]['13'];
						$zklaim_bank+=$list[$row]['14'];
						$zjml_asuransi+=$list[$row]['15'];
						$zplafond_asuransi+=$list[$row]['16'];
						$zklaim_asiransi+=$list[$row]['17'];
	
					}
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
					unset($list);
				}
	
				$pdf->AddPage();
				$pdf->AliasNbPages();
				$kategori=$dataku['kategori'];
				$status_klaim=$dataku['status_klaim'];
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');
	
			}else{
				if($status_klaim!==$dataku['status_klaim']){
	
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
					$pdf->ln();
	
					$ojml_all=0;
					$oplafond_all=0;
					$oklaim_all=0;
					$ojml_bank=0;
					$oplafond_bank=0;
					$ojml_bayar_asuransi=0;
					$oklaim_bayar_asuransi=0;
					$ojml_talangan_bank=0;
					$oklaim_talangan_bank=0;
					$oklaim_bank=0;
					$ojml_asuransi=0;
					$oplafond_asuransi=0;
					$oklaim_asiransi=0;
	
	
					$status_klaim=$dataku['status_klaim'];
					$pdf->Ln(10);
					$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');
	
				}
			}
	
			$pdf->SetFont('Arial','',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
	
	
			$no++;
			$pdf->ln();
	
	
			$ojml_all+=$dataku['jml_all'];
			$oplafond_all+=$dataku['plafond_all'];
			$oklaim_all+=$dataku['klaim_all'];
			$ojml_bank+=$dataku['jml_bank'];
			$oplafond_bank+=$dataku['plafond_bank'];
			$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
			$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
			$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
			$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
			$oklaim_bank+=$dataku['klaim_bank'];
			$ojml_asuransi+=$dataku['jml_asuransi'];
			$oplafond_asuransi+=$dataku['plafond_asuransi'];
			$oklaim_asiransi+=$dataku['klaim_asiransi'];
	
	
			$list[] = $dataku;
	
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
		$pdf->ln(10);
		$pdf->Header('TOTAL','','','0');
		sort($list);
	
		$zjml_all=0;
		$zplafond_all=0;
		$zklaim_all=0;
		$zjml_bank=0;
		$zplafond_bank=0;
		$zjml_bayar_asuransi=0;
		$zklaim_bayar_asuransi=0;
		$zjml_talangan_bank=0;
		$zklaim_talangan_bank=0;
		$zklaim_bank=0;
		$zjml_asuransi=0;
		$zplafond_asuransi=0;
		$zklaim_asiransi=0;
	
	
		for ($row = 0; $row < count($list)+1; $row++) {
	
	
			if($head_kol!==$list[$row]['0']){
				if(!empty($head_kol)){
					$pdf->SetFont('Arial','',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
					$pdf->Ln();
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}else{
	
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}
			}
	
			$jml_all+=$list[$row]['5'];
			$plafond_all+=$list[$row]['6'];
			$klaim_all+=$list[$row]['7'];
			$jml_bank+=$list[$row]['8'];
			$plafond_bank+=$list[$row]['9'];
			$jml_bayar_asuransi+=$list[$row]['10'];
			$klaim_bayar_asuransi+=$list[$row]['11'];
			$jml_talangan_bank+=$list[$row]['12'];
			$klaim_talangan_bank+=$list[$row]['13'];
			$klaim_bank+=$list[$row]['14'];
			$jml_asuransi+=$list[$row]['15'];
			$plafond_asuransi+=$list[$row]['16'];
			$klaim_asiransi+=$list[$row]['17'];
	
			$zjml_all+=$list[$row]['5'];
			$zplafond_all+=$list[$row]['6'];
			$zklaim_all+=$list[$row]['7'];
			$zjml_bank+=$list[$row]['8'];
			$zplafond_bank+=$list[$row]['9'];
			$zjml_bayar_asuransi+=$list[$row]['10'];
			$zklaim_bayar_asuransi+=$list[$row]['11'];
			$zjml_talangan_bank+=$list[$row]['12'];
			$zklaim_talangan_bank+=$list[$row]['13'];
			$zklaim_bank+=$list[$row]['14'];
			$zjml_asuransi+=$list[$row]['15'];
			$zplafond_asuransi+=$list[$row]['16'];
			$zklaim_asiransi+=$list[$row]['17'];
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
	
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFont('Times','',7);
		$pdf->Output();
	
		break;
	
	
	case "summary_klaim_asuransi_nonliable_all" :
	
		function bulan_convert($tanggal){
			$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
			if($dateku!==""){
				$tgl=explode("-", $dateku);
	
				if($tgl['1']=='01'){
					$ls_namabulan =  'Jan';
				}elseif($tgl['1']=='02'){
					$ls_namabulan =  'Feb';
				}elseif($tgl['1']=='03'){
					$ls_namabulan =  'Mar';
				}elseif($tgl['1']=='04'){
					$ls_namabulan =  'Apr';
				}elseif($tgl['1']=='05'){
					$ls_namabulan =  'Mei';
				}elseif($tgl['1']=='06'){
					$ls_namabulan =  'Jun';
				}elseif($tgl['1']=='07'){
					$ls_namabulan =  'Jul';
				}elseif($tgl['1']=='08'){
					$ls_namabulan =  'Agt';
				}elseif($tgl['1']=='09'){
					$ls_namabulan =  'Sep';
				}elseif($tgl['1']=='10'){
					$ls_namabulan =  'Okt';
				}elseif($tgl['1']=='11'){
					$ls_namabulan =  'Nov';
				}elseif($tgl['1']=='12'){
					$ls_namabulan =  'Des';
				}
	
	
				$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
				if($tglku=='30-Nov-'){
					return "";
				}else{
					return $tglku;
				}
			}
	
		}
	
		if($_REQUEST['id_asuransi']=="all"){
			$asuransi="";
		}else{
			$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
		}
	
		if(empty($_REQUEST['id_polis'])){
			$polis="";
		}else{
			$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
		}
		if($_REQUEST['liability']=='ALL'){
			$liability='';
		}else{
	
			$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
		}
	
		$tgl_lapor='';
		if($_REQUEST['tglcheck1']!==""){
			$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}
	
		$tipe_produk='';
		if($_REQUEST['tipe_produk']!=="All"){
			$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
		}
	
		$tgl_dol='';
		if($_REQUEST['tglcheck3']!==""){
			$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
		}
	
	
		if(empty($_REQUEST['status_klaim'])){
			$status_klaim="";
		}else{
			$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
		}
	
		if(empty($_REQUEST['status_bayar'])){
			$status_bayar="";
		}else{
			$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
		}
	
		if(empty($_REQUEST['kol'])){
			$kol="";
		}else{
			$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
		}
		$sqlku="SELECT
			aa.kol,
			aa.id_cost,
			aa.code,
			aa.status_klaim,
			aa.kategori,
			aa.jml_all,
			aa.plafond_all,
			aa.klaim_all,
			bb.jml_bank,
			bb.plafond_bank,
			ifnull(bb.jml_bank,0)-ifnull(cc.jml_talangan_bank,0) AS jml_bayar_asuransi,
			ifnull(bb.klaim_bank,0)-ifnull(cc.klaim_talangan_bank,0) AS klaim_bayar_asuransi,
			cc.jml_talangan_bank,
			cc.klaim_talangan_bank,
			bb.klaim_bank,
			ifnull(aa.jml_all,0)-ifnull(bb.jml_bank,0) AS jml_asuransi,
			ifnull(aa.plafond_all,0)-ifnull(bb.plafond_bank,0) AS plafond_asuransi,
			ifnull(aa.klaim_all,0)-ifnull(bb.klaim_bank,0) AS klaim_asiransi
			FROM (
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_all,
			SUM(kredit_jumlah) AS plafond_all,
			SUM(tuntutan_klaim) AS klaim_all FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan')*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tuntutan_klaim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death'
	
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ab
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) aa
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_bank,
			SUM(kredit_jumlah) AS plafond_bank,
			SUM(total_claim) AS klaim_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_bayar_asuransi as total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
	
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			AND  DATE(fu_ajk_cn.tgl_bayar_asuransi) IS NOT NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol
			) bb ON aa.id_cost=bb.id_cost AND aa.id_as=bb.id_as AND aa.status_klaim=bb.status_klaim AND aa.kategori=bb.kategori AND aa.kol=bb.kol
			LEFT JOIN
			(
			SELECT
			id_cost,
			id_as,
			`code`,
			kategori,
			status_klaim,
			kol,
			COUNT(id) AS jml_talangan_bank,
			SUM(kredit_jumlah) AS plafond_talangan_bank,
			SUM(total_claim) AS klaim_talangan_bank FROM(
			SELECT
			IFNULL(fu_ajk_dn.id_cost,'') AS id_cost,
			IFNULL(fu_ajk_dn.id_as,'') AS id_as,
			fu_ajk_asuransi.`code`,
			fu_ajk_polis.nmproduk as kategori, /*IF(fu_ajk_polis.typeproduk='NON SPK','Reguler','Percepatan') as kategori,*/
			if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) AS `status_klaim`,
			fu_ajk_klaim.id,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.total_claim,
			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
	
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
			WHERE fu_ajk_cn.del is null and  fu_ajk_cn.type_claim='Death'
	
			".$asuransi."
			".$polis."
			".$tgl_lapor."
			".$tipe_produk."
			".$tgl_dol."
			".$status_klaim."
			".$status_bayar."
			".$kol."
			and fu_ajk_cn.confirm_claim !='Pending'
			and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
			AND fu_ajk_cn.del IS NULL
			and DATE(fu_ajk_cn.`tgl_byr_claim`) is null
			AND DATE(fu_ajk_cn.tgl_bayar_asuransi) IS NOT NULL
			and fu_ajk_cn.policy_liability='NONLIABLE'
			/*AND ((DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365) or fu_ajk_polis.typeproduk='SPK')*/
			) ac
			GROUP BY id_cost,
			id_as,
			`code`,
			status_klaim,
			kategori,
			kol
			) cc ON aa.id_cost=cc.id_cost AND aa.id_as=cc.id_as AND aa.status_klaim=cc.status_klaim AND aa.kategori=cc.kategori AND aa.kol=cc.kol
			order by
					aa.id_cost,aa.id_as,aa.kategori,aa.status_klaim desc, aa.kol asc
							";
	
		$hasilku=mysql_query($sqlku);
		class PDF extends FPDF
		{
			// Page header
			function Header($status_klaim='',$asuransi='',$kategori='',$headernya='')
			{
				if($status_klaim!==''){
					$line=array(
							"10","20",
							"30","20",
							"50","30",
							"80","30",
							"110","20",
							"130","10",
							"140","10",
							"150","10",
							"160","10",
							"170","0",
							"170","30",
							"200","20",
							"220","30",
							"250","30");
					if($headernya=='1'){
						$this->SetFont('Arial','B',12);
						$this->Cell(0,5,'SUMMARY KLAIM AJK PT BANK BUKOPIN, TBK._ASURANSI '.$asuransi.' KLAIM Tiering '.$kategori.' (Percepatan DOL < 1 Tahun)',0,0,'C');
						$this->ln(10);
	
						$this->SetFont('Arial','',10);
	
						if(!empty($_REQUEST['tglcheck1'])){
							$this->Cell(0,5,"TANGGAL LAPOR ".bulan_convert($_REQUEST['tglcheck1'])." s/d ".bulan_convert($_REQUEST['tglcheck2']),0,0,'C');
							$this->ln(5);
						}
						if(!empty($_REQUEST['tglcheck3'])){
							$this->Cell(0,5,"DOL ".bulan_convert($_REQUEST['tglcheck3'])." s/d ".bulan_convert($_REQUEST['tglcheck4']),0,0,'C');
							$this->ln(5);
						}
	
	
						if(!empty($_REQUEST['tgl5'])){
							$this->Cell(0,5,$l_tglinput,0,0,'C');
							$this->ln(5);
						}
	
						$this->ln();
					}
	
					$this->SetFont('Arial','B',9);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,strtoupper($status_klaim),0,0,'L');
	
					$this->ln(5);
					$this->SetFont('Arial','B',7);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRT',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3']+$line['5']+$line['7'],4,'TOTAL KLAIM','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9']+$line['11']+$line['13']+$line['15']+$line['17']+$line['19']+$line['21'],4,'SUDAH DIBAYAR ASURANSI','LRT',0,'C');
	
					$this->SetX($line['22']);
					if(strtoupper($status_klaim)=='DOKUMEN SUDAH LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DOKUMEN BELUM LENGKAP'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}elseif(strtoupper($status_klaim)=='DITOLAK'){
						$this->Cell($line['23']+$line['25']+$line['27'],4,'BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}else{
						$this->Cell($line['23']+$line['25']+$line['27'],4,'TOTAL BELUM DIBAYAR ASURANSI','LRT',0,'C');
					}
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRT',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRT',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRT',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRT',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRT',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRT',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRT',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRT',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRT',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'KOL','LR',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'NILAI KLAIM','LR',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'DEBITUR','LR',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'DIBAYAR KE BANK','LR',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'DEBITUR','LR',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'PLAFOND','LR',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'NILAI KLAIM','LR',0,'C');
	
					$this->ln(4);
					$this->SetX($line['0']);
					$this->Cell($line['1'],4,'','LRB',0,'C');
					$this->SetX($line['2']);
					$this->Cell($line['3'],4,'','LRB',0,'L');
					$this->SetX($line['4']);
					$this->Cell($line['5'],4,'','LRB',0,'C');
					$this->SetX($line['6']);
					$this->Cell($line['7'],4,'','LRB',0,'C');
					$this->SetX($line['8']);
					$this->Cell($line['9'],4,'','LRB',0,'L');
					$this->SetX($line['10']);
					$this->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],4,'','LRB',0,'C');
					$this->SetX($line['20']);
					$this->Cell($line['21'],4,'','LRB',0,'C');
					$this->SetX($line['22']);
					$this->Cell($line['23'],4,'','LRB',0,'C');
					$this->SetX($line['24']);
					$this->Cell($line['25'],4,'','LRB',0,'C');
					$this->SetX($line['26']);
					$this->Cell($line['27'],4,'','LRB',0,'C');
					$this->ln(4);
				}
			}
	
			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Dokumen Asuransi',0,0,'R');
	
				$this->SetX(10);
				$this->SetFont('Arial','I',7);
				$this->Cell(0,0,'Tanggal Cetak '.date("Y-m-d h:i:s"),0,0,'L');
			}
		}
	
	
		$pdf = new PDF('L','mm','A4');
		//$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetLeftMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->SetRightMargin(20);
	
	
		$line=array(
				"10","20",
				"30","20",
				"50","30",
				"80","30",
				"110","20",
				"130","10",
				"140","10",
				"150","10",
				"160","10",
				"170","0",
				"170","30",
				"200","20",
				"220","30",
				"250","30");
		$no=1;
		$kol=0;
		$ket='';
		$status_klaim='';
		$kategori='';
		$asuransi='';
		while($dataku=mysql_fetch_array($hasilku)){
			$head_kol='';
			if($asuransi!==$dataku['code']){
	
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX($line['0']);
				$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
				$pdf->SetX($line['2']);
				$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
				$pdf->SetX($line['4']);
				$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
				$pdf->SetX($line['6']);
				$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
				$pdf->SetX($line['8']);
				$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
				$pdf->SetX($line['10']);
				$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
				$pdf->SetX($line['20']);
				$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
				$pdf->SetX($line['22']);
				$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
				$pdf->SetX($line['24']);
				$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
				$pdf->SetX($line['26']);
				$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
				$ojml_all=0;
				$oplafond_all=0;
				$oklaim_all=0;
				$ojml_bank=0;
				$oplafond_bank=0;
				$ojml_bayar_asuransi=0;
				$oklaim_bayar_asuransi=0;
				$ojml_talangan_bank=0;
				$oklaim_talangan_bank=0;
				$oklaim_bank=0;
				$ojml_asuransi=0;
				$oplafond_asuransi=0;
				$oklaim_asiransi=0;
	
				if($asuransi!==''){
	
					$zjml_all=0;
					$zplafond_all=0;
					$zklaim_all=0;
					$zjml_bank=0;
					$zplafond_bank=0;
					$zjml_bayar_asuransi=0;
					$zklaim_bayar_asuransi=0;
					$zjml_talangan_bank=0;
					$zklaim_talangan_bank=0;
					$zklaim_bank=0;
					$zjml_asuransi=0;
					$zplafond_asuransi=0;
					$zklaim_asiransi=0;
	
					$pdf->Ln(10);
					$pdf->ln(10);
					$pdf->Header('TOTAL','','','0');
					sort($list);
					for ($row = 0; $row < count($list)+1; $row++) {
	
						if($head_kol!==$list[$row]['0']){
							if($head_kol!==''){
								$pdf->SetFont('Arial','',7);
								$pdf->SetX($line['0']);
								$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
								$pdf->SetX($line['2']);
								$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
								$pdf->SetX($line['4']);
								$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
								$pdf->SetX($line['6']);
								$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
								$pdf->SetX($line['8']);
								$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
								$pdf->SetX($line['10']);
								$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
								$pdf->SetX($line['20']);
								$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
								$pdf->SetX($line['22']);
								$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
								$pdf->SetX($line['24']);
								$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
								$pdf->SetX($line['26']);
								$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
								$pdf->Ln();
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}else{
	
								$head_kol=$list[$row]['0'];
	
								$jml_all=0;
								$plafond_all=0;
								$klaim_all=0;
								$jml_bank=0;
								$plafond_bank=0;
								$jml_bayar_asuransi=0;
								$klaim_bayar_asuransi=0;
								$jml_talangan_bank=0;
								$klaim_talangan_bank=0;
								$klaim_bank=0;
								$jml_asuransi=0;
								$plafond_asuransi=0;
								$klaim_asiransi=0;
							}
						}
	
						$jml_all+=$list[$row]['5'];
						$plafond_all+=$list[$row]['6'];
						$klaim_all+=$list[$row]['7'];
						$jml_bank+=$list[$row]['8'];
						$plafond_bank+=$list[$row]['9'];
						$jml_bayar_asuransi+=$list[$row]['10'];
						$klaim_bayar_asuransi+=$list[$row]['11'];
						$jml_talangan_bank+=$list[$row]['12'];
						$klaim_talangan_bank+=$list[$row]['13'];
						$klaim_bank+=$list[$row]['14'];
						$jml_asuransi+=$list[$row]['15'];
						$plafond_asuransi+=$list[$row]['16'];
						$klaim_asiransi+=$list[$row]['17'];
	
						$zjml_all+=$list[$row]['5'];
						$zplafond_all+=$list[$row]['6'];
						$zklaim_all+=$list[$row]['7'];
						$zjml_bank+=$list[$row]['8'];
						$zplafond_bank+=$list[$row]['9'];
						$zjml_bayar_asuransi+=$list[$row]['10'];
						$zklaim_bayar_asuransi+=$list[$row]['11'];
						$zjml_talangan_bank+=$list[$row]['12'];
						$zklaim_talangan_bank+=$list[$row]['13'];
						$zklaim_bank+=$list[$row]['14'];
						$zjml_asuransi+=$list[$row]['15'];
						$zplafond_asuransi+=$list[$row]['16'];
						$zklaim_asiransi+=$list[$row]['17'];
	
					}
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
					unset($list);
				}
	
				$pdf->AddPage();
				$pdf->AliasNbPages();
				$kategori=$dataku['kategori'];
				$status_klaim=$dataku['status_klaim'];
				$asuransi=$dataku['code'];
				$pdf->Header($status_klaim,$dataku['code'],$kategori,'1');
	
			}else{
				if($status_klaim!==$dataku['status_klaim']){
	
	
					$pdf->SetFont('Arial','B',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
					$pdf->ln();
	
					$ojml_all=0;
					$oplafond_all=0;
					$oklaim_all=0;
					$ojml_bank=0;
					$oplafond_bank=0;
					$ojml_bayar_asuransi=0;
					$oklaim_bayar_asuransi=0;
					$ojml_talangan_bank=0;
					$oklaim_talangan_bank=0;
					$oklaim_bank=0;
					$ojml_asuransi=0;
					$oplafond_asuransi=0;
					$oklaim_asiransi=0;
	
	
					$status_klaim=$dataku['status_klaim'];
					$pdf->Ln(10);
					$pdf->Header($status_klaim,$dataku['code'],$kategori,'0');
	
				}
			}
	
			$pdf->SetFont('Arial','',7);
			$pdf->SetX($line['0']);
			$pdf->Cell($line['1'],5,$dataku['kol'],1,0,'C');
			$pdf->SetX($line['2']);
			$pdf->Cell($line['3'],5,number_format($dataku['jml_all']),1,0,'C');
			$pdf->SetX($line['4']);
			$pdf->Cell($line['5'],5,number_format($dataku['plafond_all'],2),1,0,'C');
			$pdf->SetX($line['6']);
			$pdf->Cell($line['7'],5,number_format($dataku['klaim_all'],2),1,0,'C');
			$pdf->SetX($line['8']);
			$pdf->Cell($line['9'],5,number_format($dataku['jml_bank']),1,0,'C');
			$pdf->SetX($line['10']);
			$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($dataku['plafond_bank'],2),1,0,'C');
			$pdf->SetX($line['20']);
			$pdf->Cell($line['21'],5,number_format($dataku['klaim_bank'],2),1,0,'C');
			$pdf->SetX($line['22']);
			$pdf->Cell($line['23'],5,number_format($dataku['jml_asuransi']),1,0,'C');
			$pdf->SetX($line['24']);
			$pdf->Cell($line['25'],5,number_format($dataku['plafond_asuransi'],2),1,0,'C');
			$pdf->SetX($line['26']);
			$pdf->Cell($line['27'],5,number_format($dataku['klaim_asiransi'],2),1,0,'C');
	
	
			$no++;
			$pdf->ln();
	
	
			$ojml_all+=$dataku['jml_all'];
			$oplafond_all+=$dataku['plafond_all'];
			$oklaim_all+=$dataku['klaim_all'];
			$ojml_bank+=$dataku['jml_bank'];
			$oplafond_bank+=$dataku['plafond_bank'];
			$ojml_bayar_asuransi+=$dataku['jml_bayar_asuransi'];
			$oklaim_bayar_asuransi+=$dataku['klaim_bayar_asuransi'];
			$ojml_talangan_bank+=$dataku['jml_talangan_bank'];
			$oklaim_talangan_bank+=$dataku['klaim_talangan_bank'];
			$oklaim_bank+=$dataku['klaim_bank'];
			$ojml_asuransi+=$dataku['jml_asuransi'];
			$oplafond_asuransi+=$dataku['plafond_asuransi'];
			$oklaim_asiransi+=$dataku['klaim_asiransi'];
	
	
			$list[] = $dataku;
	
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($ojml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($oplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($oklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($ojml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($oplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($oklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($ojml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($oplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($oklaim_asiransi,2),1,0,'C');
	
		$pdf->ln(10);
		$pdf->Header('TOTAL','','','0');
		sort($list);
	
		$zjml_all=0;
		$zplafond_all=0;
		$zklaim_all=0;
		$zjml_bank=0;
		$zplafond_bank=0;
		$zjml_bayar_asuransi=0;
		$zklaim_bayar_asuransi=0;
		$zjml_talangan_bank=0;
		$zklaim_talangan_bank=0;
		$zklaim_bank=0;
		$zjml_asuransi=0;
		$zplafond_asuransi=0;
		$zklaim_asiransi=0;
	
	
		for ($row = 0; $row < count($list)+1; $row++) {
	
	
			if($head_kol!==$list[$row]['0']){
				if(!empty($head_kol)){
					$pdf->SetFont('Arial','',7);
					$pdf->SetX($line['0']);
					$pdf->Cell($line['1'],5,$list[$row-1]['0'],1,0,'C');
					$pdf->SetX($line['2']);
					$pdf->Cell($line['3'],5,number_format($jml_all),1,0,'C');
					$pdf->SetX($line['4']);
					$pdf->Cell($line['5'],5,number_format($plafond_all,2),1,0,'C');
					$pdf->SetX($line['6']);
					$pdf->Cell($line['7'],5,number_format($klaim_all,2),1,0,'C');
					$pdf->SetX($line['8']);
					$pdf->Cell($line['9'],5,number_format($jml_bank),1,0,'C');
					$pdf->SetX($line['10']);
					$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($plafond_bank,2),1,0,'C');
					$pdf->SetX($line['20']);
					$pdf->Cell($line['21'],5,number_format($klaim_bank,2),1,0,'C');
					$pdf->SetX($line['22']);
					$pdf->Cell($line['23'],5,number_format($jml_asuransi),1,0,'C');
					$pdf->SetX($line['24']);
					$pdf->Cell($line['25'],5,number_format($plafond_asuransi,2),1,0,'C');
					$pdf->SetX($line['26']);
					$pdf->Cell($line['27'],5,number_format($klaim_asiransi,2),1,0,'C');
					$pdf->Ln();
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}else{
	
					$head_kol=$list[$row]['0'];
	
					$jml_all=0;
					$plafond_all=0;
					$klaim_all=0;
					$jml_bank=0;
					$plafond_bank=0;
					$jml_bayar_asuransi=0;
					$klaim_bayar_asuransi=0;
					$jml_talangan_bank=0;
					$klaim_talangan_bank=0;
					$klaim_bank=0;
					$jml_asuransi=0;
					$plafond_asuransi=0;
					$klaim_asiransi=0;
				}
			}
	
			$jml_all+=$list[$row]['5'];
			$plafond_all+=$list[$row]['6'];
			$klaim_all+=$list[$row]['7'];
			$jml_bank+=$list[$row]['8'];
			$plafond_bank+=$list[$row]['9'];
			$jml_bayar_asuransi+=$list[$row]['10'];
			$klaim_bayar_asuransi+=$list[$row]['11'];
			$jml_talangan_bank+=$list[$row]['12'];
			$klaim_talangan_bank+=$list[$row]['13'];
			$klaim_bank+=$list[$row]['14'];
			$jml_asuransi+=$list[$row]['15'];
			$plafond_asuransi+=$list[$row]['16'];
			$klaim_asiransi+=$list[$row]['17'];
	
			$zjml_all+=$list[$row]['5'];
			$zplafond_all+=$list[$row]['6'];
			$zklaim_all+=$list[$row]['7'];
			$zjml_bank+=$list[$row]['8'];
			$zplafond_bank+=$list[$row]['9'];
			$zjml_bayar_asuransi+=$list[$row]['10'];
			$zklaim_bayar_asuransi+=$list[$row]['11'];
			$zjml_talangan_bank+=$list[$row]['12'];
			$zklaim_talangan_bank+=$list[$row]['13'];
			$zklaim_bank+=$list[$row]['14'];
			$zjml_asuransi+=$list[$row]['15'];
			$zplafond_asuransi+=$list[$row]['16'];
			$zklaim_asiransi+=$list[$row]['17'];
	
		}
	
	
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($line['0']);
		$pdf->Cell($line['1'],5,'TOTAL',1,0,'C');
		$pdf->SetX($line['2']);
		$pdf->Cell($line['3'],5,number_format($zjml_all),1,0,'C');
		$pdf->SetX($line['4']);
		$pdf->Cell($line['5'],5,number_format($zplafond_all,2),1,0,'C');
		$pdf->SetX($line['6']);
		$pdf->Cell($line['7'],5,number_format($zklaim_all,2),1,0,'C');
		$pdf->SetX($line['8']);
		$pdf->Cell($line['9'],5,number_format($zjml_bank),1,0,'C');
		$pdf->SetX($line['10']);
		$pdf->Cell($line['11']+$line['13']+$line['15']+$line['17']+$line['19'],5,number_format($zplafond_bank,2),1,0,'C');
		$pdf->SetX($line['20']);
		$pdf->Cell($line['21'],5,number_format($zklaim_bank,2),1,0,'C');
		$pdf->SetX($line['22']);
		$pdf->Cell($line['23'],5,number_format($zjml_asuransi),1,0,'C');
		$pdf->SetX($line['24']);
		$pdf->Cell($line['25'],5,number_format($zplafond_asuransi,2),1,0,'C');
		$pdf->SetX($line['26']);
		$pdf->Cell($line['27'],5,number_format($zklaim_asiransi,2),1,0,'C');
	
	
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFont('Times','',7);
		$pdf->Output();
	
		break;

case "_invklaim":
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

	function bulan_convert($tanggal){
		$dateku=str_replace("01-01-1970", "",  date('d-m-Y',strtotime($tanggal)));
		if($dateku!==""){
			$tgl=explode("-", $dateku);
	
			if($tgl['1']=='01'){
				$ls_namabulan =  'Jan';
			}elseif($tgl['1']=='02'){
				$ls_namabulan =  'Feb';
			}elseif($tgl['1']=='03'){
				$ls_namabulan =  'Mar';
			}elseif($tgl['1']=='04'){
				$ls_namabulan =  'Apr';
			}elseif($tgl['1']=='05'){
				$ls_namabulan =  'Mei';
			}elseif($tgl['1']=='06'){
				$ls_namabulan =  'Jun';
			}elseif($tgl['1']=='07'){
				$ls_namabulan =  'Jul';
			}elseif($tgl['1']=='08'){
				$ls_namabulan =  'Agt';
			}elseif($tgl['1']=='09'){
				$ls_namabulan =  'Sep';
			}elseif($tgl['1']=='10'){
				$ls_namabulan =  'Okt';
			}elseif($tgl['1']=='11'){
				$ls_namabulan =  'Nov';
			}elseif($tgl['1']=='12'){
				$ls_namabulan =  'Des';
			}
	
	
			$tglku=$tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
			if($tglku=='30-Nov-'){
				return "";
			}else{
				return $tglku;
			}
		}
	
	}

	class PDF extends FPDF
	{
		// Page header
		function Header()
		{

			$this->Image('image/logo-amk.jpg',50,10,-300);
		}
	
		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}
	

			$pdf=new PDF('P','mm','A4');
			//$pdf=new PDF_Code39();
			//$pdf->Open();
			$pdf->AliasNbPages();
			$pdf->AddPage();

			$cekLogoHeader = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_logoheader WHERE id="1"'));
			if ($cekLogoHeader['creditnote']=="Y") {
				//$pdf->Image('image/logo-amk.jpg',1,2);
				//$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,'A D O N A I');
				//$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
				$pdf->SetFont('helvetica','B',14);
			}else{
				$pdf->SetFont('helvetica','B',20);
				$pdf->SetFont('helvetica','',14);
			}

			$met_klaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idC'].'" AND del IS NULL'));
			$met_klaim_ = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE /*id_cost="'.$met_klaim['id_cost'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_cn="'.$met_klaim['id_cn'].'" AND*/ id_peserta="'.$met_klaim['id_peserta'].'" and type_klaim="Death" AND del IS NULL'));
			$met_klaim_polis = mysql_fetch_array(mysql_query('SELECT id, id_cost, nopol, nmproduk, bank_2, cabang_2, rek_2 FROM fu_ajk_polis WHERE id="'.$met_klaim['id_nopol'].'" AND id_cost="'.$met_klaim['id_cost'].'" AND del IS NULL'));
			$met_klaim_peserta = mysql_fetch_array(mysql_query('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kredit_tenor FROM fu_ajk_peserta WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_polis="'.$met_klaim['id_nopol'].'" AND id_peserta="'.$met_klaim['id_peserta'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_klaim="'.$met_klaim['id'].'" AND status_peserta="Death" AND del IS NULL'));
			$met_klaim_dn = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_nopol="'.$met_klaim['id_nopol'].'" AND id="'.$met_klaim['id_dn'].'" AND del IS NULL'));
			$met_penyakit = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_namapenyakit WHERE id="'.$met_klaim['nmpenyakit'].'"'));
			//if ($met_klaim['confirm_claim']!="Approve(paid)") {	$headtagihan = $pdf->Text(83, 40,'MEDICAL OPINION');	}
			//else{	$headtagihan = $pdf->Text(83, 40,'MEDICAL OPINION');	}
			$mets =datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
			$usiapolis = explode(",", $mets);
			$pdf->SetFont('helvetica','B',12);
			$headtagihan;
			$pdf->SetFont('helvetica','B',10);
			$metcost = mysql_fetch_array(mysql_query('SELECT id, name, address FROM fu_ajk_costumer WHERE id="'.$met_klaim['id_cost'].'"'));
			$pdf->Text(10, 50,$metcost['name']);
			$pdf->SetFont('helvetica','',10);
			$pdf->Text(10, 55,'ID Peserta');		$pdf->Text(50, 55,': '.$met_klaim_peserta['id_peserta']);
			$pdf->Text(10, 60,'Nama');				$pdf->Text(50, 60,': '.$met_klaim_peserta['nama']);
			$pdf->Text(10, 65,'Tanggal Lahir');		$pdf->Text(50, 65,': '._convertDate($met_klaim_peserta['tgl_lahir']));
			$pdf->Text(10, 70,'Usia');				$pdf->Text(50, 70,': '.$met_klaim_peserta['usia'].' Tahun');
			$pdf->Text(10, 75,'Plafond');			$pdf->Text(50, 75,': Rp. '.duit($met_klaim_peserta['kredit_jumlah']));
			$pdf->Text(10, 80,'Nilai Tuntutan Klaim');	$pdf->Text(50, 80,': Rp. '.duit($met_klaim['tuntutan_klaim']));
			$pdf->Text(10, 85,'Tanggal Akad');		$pdf->Text(50, 85,': '._convertDate($met_klaim_peserta['kredit_tgl']));
			$pdf->Text(10, 90,'Tanggal Akhir');		$pdf->Text(50, 90,': '._convertDate($met_klaim_peserta['kredit_akhir']));
			$pdf->Text(10, 95,'Tenor');				$pdf->Text(50, 95,': '.$met_klaim_peserta['kredit_tenor'].' Bulan');
			$pdf->Text(10, 100,'Penyebab Meninggal');$pdf->Text(50, 100,': '.$met_penyakit['namapenyakit']);
			$pdf->Text(135, 55,'Nama Produk');		$pdf->Text(165, 55,': '.$met_klaim_polis['nmproduk']);
			$pdf->Text(135, 60,'Tanggal DOL');	$pdf->Text(165, 60,': '._convertDate($met_klaim['tgl_claim']));
			$pdf->Text(135, 65,'Usia Polis');		$pdf->Text(165, 65,': '.$usiapolis[0].' Tahun '.$usiapolis[1].' Bulan '.$usiapolis[2].' Hari');
			if($met_klaim_['kategori_klaim']=="I"){
				$kategori_klaim="I (Satu)";
			}elseif($met_klaim_['kategori_klaim']=="II"){
				$kategori_klaim="II (Dua)";
			}elseif($met_klaim_['kategori_klaim']=="III"){
				$kategori_klaim="III (Tiga)";
			}
			
			//$pdf->Text(10, 110,'Tanggal Investigasi');$pdf->Text(50, 110,': '.bulan_convert($met_klaim_['tgl_investigasi']));
			//$pdf->Text(10, 115,'Kategori Klaim');$pdf->Text(50, 115,': '.$kategori_klaim);
			$y_axis1 = 108;
			$y_initial = 98;
			$pdf->setFont('Arial','B',10);
			$pdf->Ln(100);
				
			$pdf->SetFillColor(200,220,255);
			$pdf->Cell(0,6,"MEDICAL OPINION",0,1,'C',true);

			$pdf->Ln(5);
			$pdf->setFont('Arial','',10);
			$pdf->Cell(0,4,"Preexisting Condition",0,1,'L',false);
			$pdf->Ln(1);
			$pdf->setFont('Arial','',8);
			$pdf->MultiCell(0,6,$met_klaim_['preexisting_cond'],1);
			
			$pdf->Ln(3);
			$pdf->setFont('Arial','',10);
			$pdf->Cell(0,4,"ICD X",0,1,'L',false);
			$pdf->Ln(1);
			$pdf->setFont('Arial','',8);
			$pdf->MultiCell(0,6,$met_klaim_['ic_diagnosis'],1);
				
			$pdf->Ln(3);
			$pdf->setFont('Arial','',10);
			$pdf->Cell(0,4,"Analisa Dokter Adonai",0,1,'L',false);
			$pdf->Ln(1);
			$pdf->setFont('Arial','',8);
			$pdf->MultiCell(0,6,$met_klaim_['ket_dokter'],1);
			
			$pdf->Output();
			;
	break;
		
default:;
}