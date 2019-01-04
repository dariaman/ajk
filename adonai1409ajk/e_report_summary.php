<?php
error_reporting(0);
session_start();
require('fpdf.php');
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
case "eL_rank":

	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
/*
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];
	$ls_status = $_REQUEST['status'];
	$ls_paid = $_REQUEST['paid'];
	$ls_idprod = $_REQUEST['subcat'];
	$li_idreg = $_REQUEST['id_reg'];
	$groupprod = $_REQUEST['gpr'];
	if($ls_status==''){
		$ls_status = '%';
	}
	if($ls_paid==''){
		$ls_paid = '%';
	}
	if($ls_idprod==''){
		$ls_idprod = '%';
	}
	if($li_idreg ==''){
		$li_idreg = '%';
	}
	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];
	$querygprod = mysql_query("SELECT id, nmproduk FROM fu_ajk_polis WHERE grupproduk = '".$groupprod."' ORDER BY id ASC LIMIT 0,3");
	$jmlallpol = mysql_num_rows($querygprod);
	$jml_pol = 1;
	while($rowgprod = mysql_fetch_array($querygprod)){
		$id_polis = $rowgprod['id'];
		$jumlahperserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) as perserta,";
		$jmlkredit .="SUM(case when fu_ajk_peserta.id_polis ='".$id_polis."' then fu_ajk_peserta.kredit_jumlah END) as produk,";
		$klaimprod .= "SUM(case when fu_ajk_peserta.id_polis ='".$id_polis."' then fu_ajk_cn.total_claim END) as klaimprod,";
		$namapol .= "'".$rowgprod['nmproduk']."' as namapol,";
		if($jml_pol==$jmlallpol){
			if($jml_pol>1){
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) as jumlah_peserta,";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0)";
				$jmlklaim .="IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_cn.total_claim,0)";
			}
		}else{
			if($jml_pol>1){
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) +";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) + ";
				$jmlklaim .="IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_cn.total_claim,0) +";
			}elseif($jml_pol==$jmlallpol AND $jml_pol ==1){
				$totalpeserta = "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) as jumlah_peserta,";
				$jumlahkredit = "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) ";
				$jmlklaim ="IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_cn.total_claim,0)";
			}else{
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) +";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) + ";
				$jmlklaim .="IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_cn.total_claim,0) + ";
			}
		}

		$jml_pol++;
	}
	$pdf->SetFont('Times','IB',9);
	$pdf->SetY(10);
	$pdf->SetX(10);
	$pdf->Cell(0,15,$ls_name,0,0,'L');
	$pdf->SetFont('Times','B',9);
	$pdf->SetY(14);
	$pdf->SetX(10);
	$pdf->Cell(0,15,'STATISTIK RANKING',0,0,'L');

	$pdf->SetFont('Times','',9);
	$pdf->SetY(18);
	$pdf->SetX(10);
	$pdf->Cell(0,15,'Periode per tanggal DN: '._convertDate($ldt_tanggal1).' s/d '._convertDate($ldt_tanggal2),0,0,'L');

	$queryprod_bak =  mysql_query("	SELECT 	Count(fu_ajk_peserta.nama) as jml_peserta,
										fu_ajk_peserta.cabang,
										fu_ajk_peserta.id_polis,
										$jmlkreditnol
										$namapol
										$jumlahperserta
										$totalpeserta
										$jmlkredit
										SUM($jumlahkredit) as jumlah_kredit,
										fu_ajk_peserta.cabang
								FROM fu_ajk_peserta
										LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
										AND fu_ajk_dn.id_cabang = fu_ajk_peserta.cabang
										AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis
										AND fu_ajk_dn.id_regional = fu_ajk_peserta.regional
										AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
										LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
										LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
								WHERE fu_ajk_peserta.del IS NULL
										AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
								GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
								HAVING jumlah_peserta > 0
								ORDER BY jumlah_kredit DESC
								LIMIT 0,10");

	$queryprod =  mysql_query("	SELECT 	Count(fu_ajk_peserta.nama) as jml_peserta,
										fu_ajk_peserta.cabang,
										fu_ajk_peserta.id_polis,
										fu_ajk_peserta.cabang
								FROM fu_ajk_peserta
										LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
										AND fu_ajk_dn.id_cabang = fu_ajk_peserta.cabang
										AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis
										AND fu_ajk_dn.id_regional = fu_ajk_peserta.regional
										AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
										LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
										LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
								WHERE fu_ajk_peserta.del IS NULL
										AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
								GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
								LIMIT 0,10");

	$li_YProd = 34;
	$ldb_granttot = 0;
	$li_row = 1;
	$li_y = 10;
	while($rowprod = mysql_fetch_array($queryprod)){
	$ls_namacab = $rowprod['cabang'];
	$ls_spkpeserta = $rowprod['perserta1'];
	$ls_spkpeserta = number_format($ls_spkpeserta,0,".",",");
	$ls_percepatanpeserta = $rowprod['perserta2'];
	$ls_percepatanpeserta = number_format($ls_percepatanpeserta,0,".",",");
	$ls_abripeserta = $rowprod['perserta3'];
	$ls_abripeserta = number_format($ls_abripeserta,0,".",",");
	$jml_spkkredit = $rowprod['produk1'];
	$jml_spkkredit = number_format($jml_spkkredit,2,".",",");
	$jml_perkredit = $rowprod['produk2'];
	$jml_perkredit = number_format($jml_perkredit,2,".",",");
	$jml_abrikredit = $rowprod['produk3'];
	$jml_abrikredit = number_format($jml_abrikredit,2,".",",");
	$jml_kredit = $rowprod['jumlah_kredit'];
	$jml_kredit = number_format($jml_kredit,2,".",",");
	$jml_peserta = $rowprod['jumlah_peserta'];
	$jml_peserta = number_format($jml_peserta,0,".",",");
	$namapol1 = $rowprod['namapol1'];
	$namapol2 = $rowprod['namapol2'];
	$namapol3 = $rowprod['namapol3'];
		if($li_row==1){
			$pdf->SetFont('Times','B',10);
			$pdf->SetY(24);
			$pdf->SetX(10);
			$pdf->Cell(0,15,'10 Cabang dengan Realisasi Kredit Terbesar',0,0,'L');
			//HEADER
			$pdf->SetFont('Times','',7);
			$pdf->SetY(39);
			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(15);
			$pdf->Cell(40,5,'Nama Cabang',1,0,'C');
			$pdf->SetY(34);
			$pdf->SetX(55);
			$pdf->Cell(40,5,$namapol1,1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(55);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(65);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetY(34);
			$pdf->SetX(95);
			$pdf->Cell(40,5,$namapol2,1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(95);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(105);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

			$pdf->SetY(34);
			$pdf->SetX(135);
			$pdf->Cell(40,5,$namapol3,1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(135);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(145);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

			$pdf->SetY(34);
			$pdf->SetX(175);
			$pdf->Cell(40,5,'TOTAL',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(175);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(39);
			$pdf->SetX(185);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

		}
		//SPK
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(10);
		$pdf->Cell(5,5,$li_row,1,0,'C');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(15);
		$pdf->Cell(40,5,$ls_namacab,1,0,'L');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(55);
		$pdf->Cell(10,5,$ls_spkpeserta,1,0,'C');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(65);
		$pdf->Cell(30,5,$jml_spkkredit,1,0,'R');

		//PERCEPATAN
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(95);
		$pdf->Cell(10,5,$ls_percepatanpeserta,1,0,'C');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(105);
		$pdf->Cell(30,5,$jml_perkredit,1,0,'R');

		//ABRI
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(135);
		$pdf->Cell(10,5,$ls_abripeserta,1,0,'C');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(145);
		$pdf->Cell(30,5,$jml_abrikredit,1,0,'R');

		//TOTAL
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(175);
		$pdf->Cell(10,5,$jml_peserta,1,0,'C');
		$pdf->SetY($li_YProd+$li_y);
		$pdf->SetX(185);
		$pdf->Cell(30,5,$jml_kredit,1,0,'R');
		$li_y = $li_y + 5;
	$li_row++;

	}

$queryprod_bak =  mysql_query(" SELECT 	Count(fu_ajk_peserta.nama) as jml_peserta,
									fu_ajk_peserta.cabang,
									fu_ajk_peserta.id_polis,
									$namapol
									$jumlahperserta
									$totalpeserta
									$jmlkredit
									SUM($jumlahkredit) as jumlah_kredit,
									fu_ajk_peserta.cabang
							FROM fu_ajk_peserta
								LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
								AND fu_ajk_dn.id_cabang = fu_ajk_peserta.cabang
								AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis
								AND fu_ajk_dn.id_regional = fu_ajk_peserta.regional
								AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
								LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
								LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
							WHERE fu_ajk_peserta.del IS NULL
							AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
							GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
							HAVING jumlah_peserta > 0
							ORDER BY jumlah_peserta DESC
							LIMIT 0,10");

$queryprod =  mysql_query(" SELECT 	Count(fu_ajk_peserta.nama) as jml_peserta,
									fu_ajk_peserta.cabang,
									fu_ajk_peserta.id_polis,
									fu_ajk_peserta.cabang
							FROM fu_ajk_peserta
								LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
								AND fu_ajk_dn.id_cabang = fu_ajk_peserta.cabang
								AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis
								AND fu_ajk_dn.id_regional = fu_ajk_peserta.regional
								AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
								LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
								LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
							WHERE fu_ajk_peserta.del IS NULL
							AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
							GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
							LIMIT 0,10");

	$li_YProd = 34;
	$ldb_granttot = 0;
	$li_row = 1;
	$li_y = 5;
	while($rowprod = mysql_fetch_array($queryprod)){
		$ls_namacab = $rowprod['cabang'];
		$ls_spkpeserta = $rowprod['perserta1'];
		$ls_spkpeserta = number_format($ls_spkpeserta,0,".",",");
		$ls_percepatanpeserta = $rowprod['perserta2'];
		$ls_percepatanpeserta = number_format($ls_percepatanpeserta,0,".",",");
		$ls_abripeserta = $rowprod['perserta3'];
		$ls_abripeserta = number_format($ls_abripeserta,0,".",",");
		$jml_spkkredit = $rowprod['produk1'];
		$jml_spkkredit = number_format($jml_spkkredit,2,".",",");
		$jml_perkredit = $rowprod['produk2'];
		$jml_perkredit = number_format($jml_perkredit,2,".",",");
		$jml_abrikredit = $rowprod['produk3'];
		$jml_abrikredit = number_format($jml_abrikredit,2,".",",");
		$jml_kredit = $rowprod['jumlah_kredit'];
		$jml_kredit = number_format($jml_kredit,2,".",",");
		$jml_peserta = $rowprod['jumlah_peserta'];
		$jml_peserta = number_format($jml_peserta,0,".",",");
		$namapol1 = $rowprod['namapol1'];
		$namapol2 = $rowprod['namapol2'];
		$namapol3 = $rowprod['namapol3'];
		if($li_row ==1){
			$pdf->SetFont('Times','B',10);
			$pdf->SetY(95);
			$pdf->SetX(10);
			$pdf->Cell(0,15,'10 Cabang dengan jumlah Debitur Terbesar',0,0,'L');

			//HEADER
			$pdf->SetFont('Times','',7);
			$pdf->SetY(110);
			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(15);
			$pdf->Cell(40,5,'Nama Cabang',1,0,'C');
			$pdf->SetY(105);
			$pdf->SetX(55);
			$pdf->Cell(40,5,$namapol1,1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(55);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(65);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

			$pdf->SetY(105);
			$pdf->SetX(95);
			$pdf->Cell(40,5,$namapol2,1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(95);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(105);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

			$pdf->SetY(105);
			$pdf->SetX(135);
			$pdf->Cell(40,5,$namapol3,1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(135);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(145);
			$pdf->Cell(30,5,'Kredit',1,0,'C');

			$pdf->SetY(105);
			$pdf->SetX(175);
			$pdf->Cell(40,5,'TOTAL',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(175);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetY(110);
			$pdf->SetX(185);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
		}
		//SPK
		$pdf->SetY(110+$li_y);
		$pdf->SetX(10);
		$pdf->Cell(5,5,$li_row,1,0,'C');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(15);
		$pdf->Cell(40,5,$ls_namacab,1,0,'L');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(55);
		$pdf->Cell(10,5,$ls_spkpeserta,1,0,'C');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(65);
		$pdf->Cell(30,5,$jml_spkkredit,1,0,'R');

		//PERCEPTAN
		$pdf->SetY(110+$li_y);
		$pdf->SetX(95);
		$pdf->Cell(10,5,$ls_percepatanpeserta,1,0,'C');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(105);
		$pdf->Cell(30,5,$jml_perkredit,1,0,'R');

		//ABRI
		$pdf->SetY(110+$li_y);
		$pdf->SetX(135);
		$pdf->Cell(10,5,$ls_abripeserta,1,0,'C');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(145);
		$pdf->Cell(30,5,$jml_abrikredit,1,0,'R');

		//TOTAL
		$pdf->SetY(110+$li_y);
		$pdf->SetX(175);
		$pdf->Cell(10,5,$jml_peserta,1,0,'C');
		$pdf->SetY(110+$li_y);
		$pdf->SetX(185);
		$pdf->Cell(30,5,$jml_kredit,1,0,'R');

		$li_y = $li_y + 5;
		$li_row++;
	}

	$queryprod =  mysql_query("	SELECT 	fu_ajk_cn.id_cabang,
										$namapol
										$jumlahperserta
										$klaimprod
										SUM($jmlklaim) as total_klaim
								FROM
									fu_ajk_cn
									INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
									INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								WHERE fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
										AND fu_ajk_peserta.del IS NULL
								GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
								HAVING jumlah_peserta > 0
								ORDER BY total_klaim DESC
								LIMIT 10");
	$li_YProd = 34;
	$ldb_granttot = 0;
	$li_row = 1;
	$li_y = 5;
	while($rowprod = mysql_fetch_array($queryprod)){
		$ls_namacab = $rowprod['id_cabang'];
		$ls_spkpeserta = $rowprod['perserta1'];
		$ls_spkpeserta = number_format($ls_spkpeserta,0,".",",");
		$ls_perpeserta = $rowprod['perserta2'];
		$ls_perpeserta = number_format($ls_perpeserta,0,".",",");
		$ls_abripeserta = $rowprod['perserta3'];
		$ls_abripeserta = number_format($ls_abripeserta,0,".",",");
		$jml_spkklaim = $rowprod['klaimprod1'];
		$jml_spkklaim = number_format($jml_spkklaim,2,".",",");
		$jml_perklaim = $rowprod['klaimprod2'];
		$jml_perklaim = number_format($jml_perklaim,2,".",",");
		$jml_abriklaim = $rowprod['klaimprod3'];
		$jml_abriklaim = number_format($jml_abriklaim,2,".",",");
		$jml_klaim = $rowprod['total_klaim'];
		$jml_klaim = number_format($jml_klaim,2,".",",");
		$jml_peserta = $rowprod['jml_peserta'];
		$jml_peserta = number_format($jml_peserta,0,".",",");
		$namapol1 = $rowprod['namapol1'];
		$namapol2 = $rowprod['namapol2'];
		$namapol3 = $rowprod['namapol3'];
		if($li_row == 1){
			$pdf->SetFont('Times','B',10);
			$pdf->SetY(165);
			$pdf->SetX(10);
			$pdf->Cell(0,15,'10 Cabang dengan Klaim Terbanyak',0,0,'L');
			//HEADER
			$pdf->SetFont('Times','',7);
			$pdf->SetY(180);
			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(15);
			$pdf->Cell(20,5,'Nama Cabang',1,0,'C');
			$pdf->SetY(175);
			$pdf->SetX(35);
			$pdf->Cell(40,5,$namapol1,1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(35);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(45);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetY(175);
			$pdf->SetX(75);
			$pdf->Cell(40,5,$namapol3,1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(75);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(85);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');


			$pdf->SetY(175);
			$pdf->SetX(115);
			$pdf->Cell(40,5,$namapol3,1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(115);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(125);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetY(175);
			$pdf->SetX(155);
			$pdf->Cell(40,5,'TOTAL',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(155);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetY(180);
			$pdf->SetX(165);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');
		}


		//SPK
		$pdf->SetY(180+$li_y);
		$pdf->SetX(10);
		$pdf->Cell(5,5,$li_row,1,0,'C');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(15);
		$pdf->Cell(20,5,$ls_namacab,1,0,'L');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(35);
		$pdf->Cell(10,5,$ls_spkpeserta,1,0,'C');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(45);
		$pdf->Cell(30,5,$jml_spkklaim,1,0,'R');

		//PERCEPATAN
		$pdf->SetY(180+$li_y);
		$pdf->SetX(75);
		$pdf->Cell(10,5,$ls_perpeserta,1,0,'C');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(85);
		$pdf->Cell(30,5,$jml_perklaim,1,0,'R');

		//ABRI
		$pdf->SetY(180+$li_y);
		$pdf->SetX(115);
		$pdf->Cell(10,5,$ls_abripeserta,1,0,'C');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(125);
		$pdf->Cell(30,5,$jml_abriklaim,1,0,'R');

		//TOTAL
		$pdf->SetY(180+$li_y);
		$pdf->SetX(155);
		$pdf->Cell(10,5,$jml_peserta,1,0,'C');
		$pdf->SetY(180+$li_y);
		$pdf->SetX(165);
		$pdf->Cell(30,5,$jml_klaim,1,0,'R');
		$li_y = $li_y + 5;
		$li_row++;
	}
*/
$metCustomer = mysql_fetch_array(mysql_query('SELECT fu_ajk_costumer.`name`, fu_ajk_grupproduk.nmproduk
											  FROM fu_ajk_costumer
											  INNER JOIN fu_ajk_grupproduk ON fu_ajk_costumer.id = fu_ajk_grupproduk.id_cost
											  INNER JOIN fu_ajk_polis ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
											  WHERE fu_ajk_costumer.id = "'.$_REQUEST['cat'].'" AND
											  		fu_ajk_grupproduk.id = "'.$_REQUEST['gpr'].'"'));
if ($_REQUEST['gpr']) {
	$cekgroupproduk = 'fu_ajk_peserta.nama_mitra = '.$_REQUEST['gpr'].' AND';
	$metgrupnya = 'GROUP PRODUK '.$metCustomer['nmproduk'].'';
}else{
	$cekgroupproduk = '';
	$metgrupnya = 'ALL PRODUK';
}

$cekproduk = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idp'].'"'));
if ($_REQUEST['idp']) {
	$cekpolis = 'fu_ajk_peserta.id_polis = '.$_REQUEST['idp'].' AND';
	$cekproduk_ = 'PRODUK' .$cekproduk['nmproduk'];
}else{
	$cekpolis = '';
	$cekproduk_ = 'SEMUA PRODUK';
}

	$pdf->SetFont('Times','B',9);
	$pdf->Text(10, 20,$metCustomer['name']);
	$pdf->Text(10, 25,$cekproduk_);
	$pdf->Text(10, 30,'STATISTIK RANKING '.$metgrupnya.'');
	$pdf->SetFont('Times','',9);
	$pdf->Text(10, 35,'Periode Pertanggal Debitnote : '._convertDate($_REQUEST['tgl1']).' s/d '._convertDate($_REQUEST['tgl2']).'');
	$pdf->SetFont('Times','B',10);
	$pdf->Text(10, 45,'10 Cabang dengan Realisasi Kredit Terbesar');

	$pdf->Ln(28);
	$pdf->SetFont('helvetica','',9);
	$pdf->Cell(10,10,'No',1,0,'C');
	$pdf->Cell(50,10,'Cabang',1,0,'L');
	$pdf->Cell(50,5,'Daily ',1,0,'C');		$pdf->Cell(50,5,'Monthly ',1,0,'C');	$pdf->Cell(50,5,'Yearly ',1,0,'C');		$pdf->Cell(50,5,'Total ',1,0,'C');	$pdf->Ln();
	$pdf->Cell(60,5,'',0,0,'L');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');

$met_t1 = explode("-", $_REQUEST['tgl1']);
$met_t2 = explode("-", $_REQUEST['tgl2']);
$metProd = mysql_query('SEELCT * FROM fu_ajk_polis WHERE id_cost="'.$_REQUEST['cat'].'" AND del IS NULL');
while ($metProd_ = mysql_fetch_array($metProd)) {
	$pdf->Cell(10,100,$metProd_['nmproduk'],1,0,'C');
}

$metRanking = mysql_query('SELECT
fu_ajk_peserta.nama_mitra,
fu_ajk_grupproduk.nmproduk,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.regional,
fu_ajk_peserta.area,
fu_ajk_peserta.cabang,
Count(fu_ajk_peserta.nama) AS alldata,
SUM(fu_ajk_peserta.kredit_jumlah) AS allplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m-%d") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" AND "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" THEN fu_ajk_peserta.nama END) AS hrterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m-%d") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" AND "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" THEN fu_ajk_peserta.kredit_jumlah END) AS hrplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'" AND "'.$met_t2[0].'-'.$met_t2[1].'" THEN fu_ajk_peserta.nama END) AS blnterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'" AND "'.$met_t2[0].'-'.$met_t2[1].'" THEN fu_ajk_peserta.kredit_jumlah END) AS blnplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y") BETWEEN "'.$met_t2[0].'" AND "'.$met_t2[0].'" THEN fu_ajk_peserta.nama END) AS thnterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y") BETWEEN "'.$met_t2[0].'" AND "'.$met_t2[0].'" THEN fu_ajk_peserta.kredit_jumlah END) AS thnplafond
FROM fu_ajk_peserta
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
WHERE fu_ajk_peserta.id_cost = '.$_REQUEST['cat'].' AND
	  '.$cekpolis.'
	  '.$cekgroupproduk.'
	  fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" AND
	  fu_ajk_peserta.status_aktif = "Inforce"
GROUP BY fu_ajk_peserta.cabang
ORDER BY SUM(fu_ajk_peserta.kredit_jumlah) DESC
LIMIT 0, 10');
while ($metRanking_ = mysql_fetch_array($metRanking)) {
	$cell[$i][0] = $metRanking_['cabang'];
	$cell[$i][1] = duit($metRanking_['hrterjamin']);
	$cell[$i][2] = duit($metRanking_['hrplafond']);
	$cell[$i][3] = duit($metRanking_['blnterjamin']);
	$cell[$i][4] = duit($metRanking_['blnplafond']);
	$cell[$i][5] = duit($metRanking_['thnterjamin']);
	$cell[$i][6] = duit($metRanking_['thnplafond']);
	$cell[$i][7] = duit($metRanking_['alldata']);
	$cell[$i][8] = duit($metRanking_['allplafond']);
	$i++;
}$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{	$pdf->cell(10,5,$j+1,1,0,'C');
		$pdf->cell(50,5,$cell[$j][0],1,0,'L');
		$pdf->cell(25,5,$cell[$j][1],1,0,'C');
		$pdf->cell(25,5,$cell[$j][2],1,0,'R');
		$pdf->cell(25,5,$cell[$j][3],1,0,'C');
		$pdf->cell(25,5,$cell[$j][4],1,0,'R');
		$pdf->cell(25,5,$cell[$j][5],1,0,'C');
		$pdf->cell(25,5,$cell[$j][6],1,0,'R');
		$pdf->cell(25,5,$cell[$j][7],1,0,'C');
		$pdf->cell(25,5,$cell[$j][8],1,0,'R');
		$pdf->Ln();
	}
	$pdf->SetFont('Times','B',10);
	$pdf->Cell(10, 10,'10 Cabang dengan Realisasi Debitur Terbesar');
	$pdf->Ln(9);
	$pdf->SetFont('helvetica','',9);
	$pdf->Cell(10,10,'No',1,0,'C');
	$pdf->Cell(50,10,'Cabang',1,0,'L');
	$pdf->Cell(50,5,'Daily ',1,0,'C');		$pdf->Cell(50,5,'Monthly ',1,0,'C');	$pdf->Cell(50,5,'Yearly ',1,0,'C');		$pdf->Cell(50,5,'Total ',1,0,'C');	$pdf->Ln();
	$pdf->Cell(60,5,'',0,0,'L');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$pdf->Cell(25,5,'Peserta',1,0,'C');		$pdf->Cell(25,5,'Kredit',1,0,'C');
	$metRanking = mysql_query('SELECT
fu_ajk_peserta.nama_mitra,
fu_ajk_grupproduk.nmproduk,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.regional,
fu_ajk_peserta.area,
fu_ajk_peserta.cabang,
Count(fu_ajk_peserta.nama) AS alldata,
SUM(fu_ajk_peserta.kredit_jumlah) AS allplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m-%d") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" AND "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" THEN fu_ajk_peserta.nama END) AS hrterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m-%d") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" AND "'.$met_t2[0].'-'.$met_t2[1].'-'.$met_t2[2].'" THEN fu_ajk_peserta.kredit_jumlah END) AS hrplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'" AND "'.$met_t2[0].'-'.$met_t2[1].'" THEN fu_ajk_peserta.nama END) AS blnterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$met_t2[0].'-'.$met_t2[1].'" AND "'.$met_t2[0].'-'.$met_t2[1].'" THEN fu_ajk_peserta.kredit_jumlah END) AS blnplafond,
COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y") BETWEEN "'.$met_t2[0].'" AND "'.$met_t2[0].'" THEN fu_ajk_peserta.nama END) AS thnterjamin,
SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y") BETWEEN "'.$met_t2[0].'" AND "'.$met_t2[0].'" THEN fu_ajk_peserta.kredit_jumlah END) AS thnplafond
FROM fu_ajk_peserta
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
WHERE fu_ajk_peserta.id_cost = '.$_REQUEST['cat'].' AND
	  '.$cekpolis.'
	  '.$cekgroupproduk.'
	  fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" AND
	  fu_ajk_peserta.status_aktif = "Inforce"
GROUP BY fu_ajk_peserta.cabang
ORDER BY Count(fu_ajk_peserta.nama) DESC
LIMIT 0, 10');
		while ($metRanking_ = mysql_fetch_array($metRanking)) {
			$cell[$i][0] = $metRanking_['cabang'];
			$cell[$i][1] = duit($metRanking_['hrterjamin']);
			$cell[$i][2] = duit($metRanking_['hrplafond']);
			$cell[$i][3] = duit($metRanking_['blnterjamin']);
			$cell[$i][4] = duit($metRanking_['blnplafond']);
			$cell[$i][5] = duit($metRanking_['thnterjamin']);
			$cell[$i][6] = duit($metRanking_['thnplafond']);
			$cell[$i][7] = duit($metRanking_['alldata']);
			$cell[$i][8] = duit($metRanking_['allplafond']);
			$i++;
		}$pdf->Ln();
	for($j<1;$j<$i;$j++)
	{	$pdf->cell(10,5,$j+1,1,0,'C');
		$pdf->cell(50,5,$cell[$j][0],1,0,'L');
		$pdf->cell(25,5,$cell[$j][1],1,0,'C');
		$pdf->cell(25,5,$cell[$j][2],1,0,'R');
		$pdf->cell(25,5,$cell[$j][3],1,0,'C');
		$pdf->cell(25,5,$cell[$j][4],1,0,'R');
		$pdf->cell(25,5,$cell[$j][5],1,0,'C');
		$pdf->cell(25,5,$cell[$j][6],1,0,'R');
		$pdf->cell(25,5,$cell[$j][7],1,0,'C');
		$pdf->cell(25,5,$cell[$j][8],1,0,'R');
		$pdf->Ln();
	}
	$pdf->Output();
	break;
case "eL_lossrasio":
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];

	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];

	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'SUMMARY LOSS RATIO',0,0,'L');
	$pdf->ln();

	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'Periode per tanggal DN: '._convertDate($ldt_tanggal1).' s/d '._convertDate($ldt_tanggal2),0,0,'L');
	$pdf->ln();


	$queryprod =  mysql_query("SELECT produk FROM rpt_report_summary
	WHERE UserName = '".$_SESSION['nm_user']."'
	GROUP BY produk ORDER BY Produk ASC");

	while($rowprod = mysql_fetch_array($queryprod)){
		$ls_namaproduk = $rowprod['produk'];
		//HEADER
		$pdf->SetFont('Times','B',7);
		$pdf->SetX(10);
		$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
		$pdf->SetX(65);
		$pdf->Cell(70,5,'Produksi',1,0,'C');
		$pdf->SetX(135);
		$pdf->Cell(70,5,'Pengajuan Klaim',1,0,'C');
		$pdf->SetX(205);
		$pdf->Cell(70,5,'Loss Ratio',1,0,'C');
		$pdf->ln();

		$pdf->SetX(10);
		$pdf->Cell(5,5,'No',1,0,'C');
		$pdf->SetX(15);
		$pdf->Cell(50,5,'Nama Regional',1,0,'C');

		$pdf->SetX(65);
		$pdf->Cell(10,5,'Peserta',1,0,'C');
		$pdf->SetX(75);
		$pdf->Cell(30,5,'Kredit',1,0,'C');
		$pdf->SetX(105);
		if($_REQUEST['opt']=="asuransi"){
			$pdf->Cell(30,5,'Premi (*)',1,0,'C');
		}else{
			$pdf->Cell(30,5,'Premi',1,0,'C');
		}
		$pdf->SetX(135);
		$pdf->Cell(10,5,'Qty',1,0,'C');
		$pdf->SetX(145);
		$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');
		$pdf->SetX(175);
		$pdf->Cell(30,5,'Nilai Klaim Dibayar',1,0,'C');

		$pdf->SetX(205);
		$pdf->Cell(10,5,'Peserta',1,0,'C');
		$pdf->SetX(215);
		$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');
		$pdf->SetX(245);
		$pdf->Cell(30,5,'Nilai Klaim Dibayar',1,0,'C');
		$pdf->ln();
		$pdf->SetFont('Times','',7);
		$querylossratio =  mysql_query("SELECT Regional,TotalPremi,TotalPremiAs, JumlahKredit, JumlahPeserta, NilaiKlaim, KlaimPaid, PesertaKlaim
		FROM rpt_report_summary
		WHERE Produk = '".$ls_namaproduk."' and UserName = '".$_SESSION['nm_user']."'");
		$ld_subtotalprem = 0;
		$ld_subtotalkredit = 0;
		$ld_subtotalpeserta = 0;
		$ld_subtotalklaimnilai = 0;
		$ld_nilai_klaimdibayar = 0;
		$ld_subtotalklaimpeserta = 0;
		$sub_pesertarasio = 0;
		$sub_nilairasio = 0;
		$sub_bayarrasio = 0;
		$li_row = 1;
		while($rowlossratio = mysql_fetch_array($querylossratio)){
			$ls_regional = $rowlossratio['Regional'];
			if($_REQUEST['opt']=="asuransi"){
				$ld_premi = $rowlossratio['TotalPremiAs'];
			}else{
				$ld_premi = $rowlossratio['TotalPremi'];
			}
			$ld_subtotalprem = $ld_subtotalprem + $ld_premi;
			$ld_premiformat = number_format($ld_premi,0,".",",");
			$ls_kredit = $rowlossratio['JumlahKredit'];
			$ld_subtotalkredit = $ld_subtotalkredit + $ls_kredit;
			$ls_kreditformat = number_format($ls_kredit,0,".",",");
			$li_peserta = $rowlossratio['JumlahPeserta'];
			$ld_subtotalpeserta = $ld_subtotalpeserta + $li_peserta;
			$li_pesertaformat = number_format($li_peserta,0,".",",");
			$ld_nilai_klaim = $rowlossratio['NilaiKlaim'];
			$ld_subtotalklaimnilai += $ld_nilai_klaim;
			$ld_nilai_klaimformat = number_format($ld_nilai_klaim,0,".",",");
			$ld_nilai_klaimdibayar = $rowlossratio['KlaimPaid'];
			$ld_subtotalklaimbayar += $ld_nilai_klaimdibayar;
			$ld_nilai_klaimdibayarformat = number_format($ld_nilai_klaimdibayar,0,".",",");
			$li_peserta_klaim = $rowlossratio['PesertaKlaim'];
			$ld_subtotalklaimpeserta += $li_peserta_klaim;
			$li_peserta_klaimformat = number_format($li_peserta_klaim,0,".",",");

			$pesertarasio =  $li_peserta_klaim / $li_peserta*100;
			$nilairasio = $ld_nilai_klaim / $ld_premi * 100;
			$bayarrasio = $ld_nilai_klaimdibayar / $ld_premi * 100;

			$sub_pesertarasio += $pesertarasio;
			$sub_nilairasio += $nilairasio;
			$sub_bayarrasio += $bayarrasio;

			$pesertarasioformat = number_format($pesertarasio,0,".",",");
			$nilairasioformat = number_format($nilairasio,0,".",",");
			$bayarrasioformat = number_format($bayarrasio,0,".",",");

			$ld_subtotalpremformat = number_format($ld_subtotalprem,0,".",",");
			$ld_subtotalkreditformat = number_format($ld_subtotalkredit,0,".",",");
			$ld_subtotalpesertaformat = number_format($ld_subtotalpeserta,0,".",",");

			$ld_klaimpeserta = number_format($ld_subtotalklaimpeserta,0,".",",");
			$ld_klaim_nilai = number_format($ld_subtotalklaimnilai,0,".",",");
			$ld_klaim_bayar = number_format($ld_nilai_klaimdibayar,0,".",",");

			$pdf->SetX(10);
			$pdf->Cell(5,5,$li_row,1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(50,5,$ls_regional,1,0,'L');

			$pdf->SetX(65);
			$pdf->Cell(10,5,$li_pesertaformat,1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(30,5,$ls_kreditformat,1,0,'R');
			$pdf->SetX(105);
			$pdf->Cell(30,5,$ld_premiformat,1,0,'R');

			$pdf->SetX(135);
			$pdf->Cell(10,5,$li_peserta_klaimformat,1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(30,5,$ld_nilai_klaimformat,1,0,'R');
			$pdf->SetX(175);
			$pdf->Cell(30,5,$ld_nilai_klaimdibayarformat,1,0,'R');

			$pdf->SetX(205);
			$pdf->Cell(10,5,$pesertarasioformat.'%',1,0,'C');
			$pdf->SetX(215);
			$pdf->Cell(30,5,$nilairasioformat.'%',1,0,'C');
			$pdf->SetX(245);
			$pdf->Cell(30,5,$bayarrasioformat.'%',1,0,'C');
			$pdf->ln();
			$li_row++;
		}
		$li_loop++;
		$sub_pesertarasio = $ld_subtotalklaimpeserta/$ld_subtotalpeserta*100;
		$sub_nilairasio = $ld_subtotalklaimnilai/$ld_subtotalprem*100;
		$sub_bayarrasio = $ld_subtotalklaimbayar/$ld_subtotalkredit*100;
		$sub_pesertarasioformat = number_format($sub_pesertarasio,0,".",",");
		$sub_nilairasioformat = number_format($sub_nilairasio,0,".",",");
		$sub_bayarrasioformat = number_format($sub_bayarrasio,0,".",",");
		$ld_grantotalprem += $ld_subtotalprem;
		$ld_grantotalkredit += $ld_subtotalkredit;
		$ld_grantotalpeserta += $ld_subtotalpeserta;
		$ld_totpeserta += $ld_subtotalklaimpeserta;
		$ld_totnilai += $ld_subtotalklaimnilai;
		$ld_totbayar += $ld_subtotalklaimbayar;



		$pdf->SetFont('Times','B',7);
		$pdf->SetX(10);
		$pdf->Cell(55,5,'subtotal',1,0,'C');
		$pdf->SetX(65);
		$pdf->Cell(10,5,$ld_subtotalpesertaformat,1,0,'C');
		$pdf->SetX(75);
		$pdf->Cell(30,5,$ld_subtotalkreditformat,1,0,'R');
		$pdf->SetX(105);
		$pdf->Cell(30,5,$ld_subtotalpremformat,1,0,'R');

		$pdf->SetX(135);
		$pdf->Cell(10,5,$ld_klaimpeserta,1,0,'C');
		$pdf->SetX(145);
		$pdf->Cell(30,5,$ld_klaim_nilai,1,0,'R');
		$pdf->SetX(175);
		$pdf->Cell(30,5,$ld_klaim_bayar,1,0,'R');

		$pdf->SetX(205);
		$pdf->Cell(10,5,$sub_pesertarasioformat.'%',1,0,'C');
		$pdf->SetX(215);
		$pdf->Cell(30,5,$sub_nilairasioformat.'%',1,0,'C');
		$pdf->SetX(245);
		$pdf->Cell(30,5,$sub_bayarrasioformat.'%',1,0,'C');

		$pdf->ln();$pdf->ln();
		if($li_loop==3){
			$li_loop=0;
			$pdf->AddPage();
		}
	}
	$ld_totpesertarasio = $ld_totpeserta/$ld_grantotalpeserta*100;
	$ld_totnilairasio = $ld_totnilai/$ld_grantotalprem*100;
	$ld_totbayarrasio = $ld_totbayar/$ld_grantotalkredit*100;
	$ld_grantotalprem = number_format($ld_grantotalprem,0,".",",");
	$ld_grantotalkredit = number_format($ld_grantotalkredit,0,".",",");
	$ld_grantotalpeserta = number_format($ld_grantotalpeserta,0,".",",");
	$ld_totpesertaformat = number_format($ld_totpeserta,0,".",",");
	$ld_totnilaiformat = number_format($ld_totnilai,0,".",",");
	$ld_totbayarformat = number_format($ld_totbayar,0,".",",");

	$ld_totpesertarasioformat = number_format($ld_totpesertarasio,0,".",",");
	$ld_totnilairasioformat = number_format($ld_totnilairasio,0,".",",");
	$ld_totbayarrasioformat = number_format($ld_totbayarrasio,0,".",",");

	$pdf->SetFont('Times','B',7);
	$pdf->SetX(10);
	$pdf->Cell(55,5,'TOTAL',1,0,'C');
	$pdf->SetX(65);
	$pdf->Cell(10,5,$ld_grantotalpeserta,1,0,'C');
	$pdf->SetX(75);
	$pdf->Cell(30,5,$ld_grantotalkredit,1,0,'R');
	$pdf->SetX(105);
	$pdf->Cell(30,5,$ld_grantotalprem,1,0,'R');

	$pdf->SetX(135);
	$pdf->Cell(10,5,$ld_totpesertaformat,1,0,'C');
	$pdf->SetX(145);
	$pdf->Cell(30,5,$ld_totnilaiformat,1,0,'R');
	$pdf->SetX(175);
	$pdf->Cell(30,5,$ld_totbayarformat,1,0,'R');

	$pdf->SetX(205);
	$pdf->Cell(10,5,$ld_totpesertarasioformat.'%',1,0,'C');
	$pdf->SetX(215);
	$pdf->Cell(30,5,$ld_totnilairasioformat.'%',1,0,'C');
	$pdf->SetX(245);
	$pdf->Cell(30,5,$ld_totbayarrasioformat.'%',1,0,'C');
	$pdf->Output();
	break;
case "eL_klaim":
		$pdf = new FPDF('L');
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$li_idperserta = $_REQUEST['cat'];
		$ldt_tanggal1 = $_REQUEST['tgl1'];
		$ldt_tanggal2 = $_REQUEST['tgl2'];
		$ls_status = $_REQUEST['status'];
		$ls_paid = $_REQUEST['paid'];
		$ls_idprod = $_REQUEST['subcat'];
		$li_idreg = $_REQUEST['id_reg'];

		$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
		$rowcust = mysql_fetch_array($querycust);
		$ls_name = $rowcust['cust_name'];
		$li_id = $rowcust['id'];

		$pdf->SetFont('Times','IB',9);
		$pdf->SetX(10);
		$pdf->Cell(0,5,$ls_name,0,0,'L');
		$pdf->ln();
		$pdf->SetFont('Times','B',9);
		$pdf->SetX(10);
		$pdf->Cell(0,5,'SUMMARY KLAIM',0,0,'L');
		$pdf->ln();

		$pdf->SetFont('Times','',9);
		$pdf->SetX(10);
		$pdf->Cell(0,5,'Periode per tanggal : '._convertDate($ldt_tanggal1).' s/d '._convertDate($ldt_tanggal2),0,0,'L');
		$pdf->ln();


		$queryprod =  mysql_query("SELECT
		fu_ajk_polis.nmproduk
		FROM
		fu_ajk_peserta
		LEFT JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
		LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
		LEFT JOIN fu_ajk_cn ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost
		AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis
		AND fu_ajk_cn.id_regional = fu_ajk_peserta.regional
		AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		WHERE fu_ajk_peserta.id !=''
		AND fu_ajk_peserta.del is NULL
		AND fu_ajk_peserta.id_cost = '".$li_idperserta."'
		AND fu_ajk_polis.nmproduk IS NOT NULL
		AND DATE_FORMAT(fu_ajk_cn.input_date,'%Y-%m-%d') BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
		AND fu_ajk_cn.type_claim = 'Death'
		GROUP BY fu_ajk_polis.nmproduk");
		$ld_grantotaladmpeserta = 0;
		$ld_grantotaladmnilai = 0;
		$ld_grantotalpropeserta = 0;
		$ld_grantotalpronilai = 0;
		$ld_grantotalfinishpeserta = 0;
		$ld_grantotalfinishnilai = 0;
		$ld_grantotalallpeserta = 0;
		$ld_grantotalallnilai = 0;
		while($rowprod = mysql_fetch_array($queryprod)){
			$ls_namaproduk = $rowprod['nmproduk'];
			//HEADER
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
			$pdf->SetX(65);
			$pdf->Cell(40,5,'Validasi Administrasi',1,0,'C');
			$pdf->SetX(105);
			$pdf->Cell(40,5,'Proses Klaim',1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(40,5,'Klaim Setteld',1,0,'C');
			$pdf->SetX(185);
			$pdf->Cell(40,5,'Klaim Finish',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(40,5,'All Klaim',1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(50,5,'Nama Regional',1,0,'C');

			$pdf->SetX(65);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetX(105);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetX(145);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(155);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetX(185);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->SetX(225);
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(235);
			$pdf->Cell(30,5,'Nilai Klaim',1,0,'C');

			$pdf->ln();
			$pdf->SetFont('Times','',7);
			$querylossratio =  mysql_query("SELECT fu_ajk_costumer.name, fu_ajk_polis.nmproduk, fu_ajk_peserta.regional,
		SUM(fu_ajk_peserta.totalpremi) as totprem, sum(kredit_jumlah) as jumlah_kredit, count(fu_ajk_peserta.nama) AS jml_peserta,
		sum(case when fu_ajk_cn.confirm_claim = 'Approve(unpaid)' then fu_ajk_cn.total_claim END) AS adm_nilai_klaim,
		sum(case when fu_ajk_cn.confirm_claim = 'Approve(paid)' then fu_ajk_cn.total_claim END) AS klaim_finish,
		count(case when fu_ajk_cn.confirm_claim = 'Approve(paid)' then fu_ajk_peserta.nama END) AS klaim_peserta_finish,
		count(case when fu_ajk_cn.confirm_claim = 'Approve(unpaid)' then fu_ajk_peserta.nama END) AS adm_klaim_peserta,
		sum(case when fu_ajk_cn.confirm_claim = 'Approve(unpaid)' OR fu_ajk_cn.confirm_claim = 'Approve(paid)' then fu_ajk_cn.total_claim END) AS all_klaim_nilai,
		count(case when fu_ajk_cn.confirm_claim = 'Approve(unpaid)' OR fu_ajk_cn.confirm_claim = 'Approve(paid)' then fu_ajk_peserta.nama END) AS all_klaim_peserta
		FROM fu_ajk_peserta
		LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
		LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
		LEFT JOIN fu_ajk_cn ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost
		AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis
		AND fu_ajk_cn.id_regional = fu_ajk_peserta.regional
		AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		WHERE fu_ajk_polis.nmproduk = '".$ls_namaproduk."'
		AND DATE_FORMAT(fu_ajk_cn.input_date,'%Y-%m-%d') BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
		AND fu_ajk_peserta.id !=''
		AND fu_ajk_peserta.del is NULL
		AND fu_ajk_cn.type_claim = 'Death'
		GROUP BY fu_ajk_costumer.name,
		fu_ajk_polis.nmproduk, fu_ajk_peserta.regional
		ORDER BY all_klaim_nilai DESC");
			$ld_subtotaladmpeserta = 0;
			$ld_subtotaladmnilai = 0;
			$ld_subtotalpropeserta = 0;
			$ld_subtotalpronilai = 0;
			$ld_subtotalfinishnilai = 0;
			$ld_subtotalfinishpeserta = 0;
			$ld_subtotalallpeserta = 0;
			$ld_subtotalallnilai = 0;
			$li_row = 1;
			while($rowlossratio = mysql_fetch_array($querylossratio)){
				$ld_adm_klaim = 0;
				$li_adm_klaim_peserta = 0;
				$ld_klaim_finish = 0;
				$li_peserta_finish = 0;
				$ld_all_klaim = 0;
				$li_all_klaim_peserta = 0;
				$ls_regional = $rowlossratio['regional'];
				$ld_adm_klaim = $rowlossratio['adm_nilai_klaim'];
				$ld_adm_klaim_format = number_format($ld_adm_klaim,2,".",",");
				$li_adm_klaim_peserta = $rowlossratio['adm_klaim_peserta'];
				$li_adm_klaim_peserta_format = number_format($li_adm_klaim_peserta,0,".",",");
				$ld_klaim_finish = $rowlossratio['klaim_finish'];
				$ld_klaim_finish_format = number_format($ld_klaim_finish,2,".",",");
				$li_peserta_finish = $rowlossratio['klaim_peserta_finish'];
				$li_peserta_finish_format = number_format($li_peserta_finish,0,".",",");
				$ld_all_klaim = $rowlossratio['all_klaim_nilai'];
				$ld_all_klaim_format = number_format($ld_all_klaim,2,".",",");
				$li_all_klaim_peserta = $rowlossratio['all_klaim_peserta'];
				$li_all_klaim_peserta_format = number_format($li_all_klaim_peserta,0,".",",");

				$ld_subtotaladmpeserta += $li_adm_klaim_peserta; $ld_subtotaladmpeserta_format = number_format($ld_subtotaladmpeserta,0,".",",");
				$ld_subtotaladmnilai += $ld_adm_klaim; $ld_subtotaladmnilai_format = number_format($ld_subtotaladmnilai,2,".",",");

				$ld_subtotalpropeserta += $li_adm_klaim_peserta; $ld_subtotalpropeserta_format = number_format($ld_subtotaladmpeserta,0,".",",");
				$ld_subtotalpronilai += $ld_adm_klaim; $ld_subtotaladmnilai_format = number_format($ld_subtotaladmnilai,2,".",",");

				$ld_subtotalfinishnilai += $ld_klaim_finish; $ld_subtotalfinishnilai_format = number_format($ld_subtotalfinishnilai,2,".",",");
				$ld_subtotalfinishpeserta += $li_peserta_finish; $ld_subtotalfinishpeserta_format = number_format($ld_subtotalfinishpeserta,0,".",",");

				$ld_subtotalallpeserta += $li_all_klaim_peserta; $ld_subtotalallpeserta_format = number_format($ld_subtotalallpeserta,0,".",",");
				$ld_subtotalallnilai += $ld_all_klaim; $ld_subtotalallnilai_format = number_format($ld_subtotalallnilai,2,".",",");


				$pdf->SetX(10);
				$pdf->Cell(5,5,$li_row,1,0,'C');
				$pdf->SetX(15);
				$pdf->Cell(50,5,$ls_regional,1,0,'L');

				$pdf->SetX(65);
				$pdf->Cell(10,5,$li_adm_klaim_peserta_format,1,0,'C');
				$pdf->SetX(75);
				$pdf->Cell(30,5,$ld_adm_klaim_format,1,0,'R');

				$pdf->SetX(105);
				$pdf->Cell(10,5,$li_adm_klaim_peserta_format,1,0,'C');
				$pdf->SetX(115);
				$pdf->Cell(30,5,$ld_adm_klaim_format,1,0,'R');

				$pdf->SetX(145);
				$pdf->Cell(10,5,'',1,0,'C');
				$pdf->SetX(155);
				$pdf->Cell(30,5,'',1,0,'R');

				$pdf->SetX(185);
				$pdf->Cell(10,5,$li_peserta_finish_format,1,0,'C');
				$pdf->SetX(195);
				$pdf->Cell(30,5,$ld_klaim_finish_format,1,0,'R');

				$pdf->SetX(225);
				$pdf->Cell(10,5,$li_all_klaim_peserta_format,1,0,'C');
				$pdf->SetX(235);
				$pdf->Cell(30,5,$ld_all_klaim_format,1,0,'R');

				$pdf->ln();
				$li_row++;
			}
			$ld_grantotaladmpeserta += $ld_subtotaladmpeserta;
			$ld_grantotaladmnilai += $ld_subtotaladmnilai;
			$ld_grantotalpropeserta += $ld_subtotalpropeserta;
			$ld_grantotalpronilai += $ld_subtotalpronilai;
			$ld_grantotalfinishpeserta += $ld_subtotalfinishpeserta;
			$ld_grantotalfinishnilai += $ld_subtotalfinishnilai;
			$ld_grantotalallpeserta += $ld_subtotalallpeserta;
			$ld_grantotalallnilai += $ld_subtotalallnilai;

			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(65,5,'subtotal',1,0,'C');
			$pdf->SetX(65);
			$pdf->Cell(10,5,$ld_subtotaladmpeserta_format,1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(30,5,$ld_subtotaladmnilai_format,1,0,'R');

			$pdf->SetX(105);
			$pdf->Cell(10,5,$ld_subtotalpropeserta,1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(30,5,$ld_subtotaladmnilai_format,1,0,'R');

			$pdf->SetX(145);
			$pdf->Cell(10,5,'',1,0,'C');
			$pdf->SetX(155);
			$pdf->Cell(30,5,'',1,0,'C');

			$pdf->SetX(185);
			$pdf->Cell(10,5,$ld_subtotalfinishpeserta_format,1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(30,5,$ld_subtotalfinishnilai_format,1,0,'R');

			$pdf->SetX(225);
			$pdf->Cell(10,5,$ld_subtotalallpeserta_format,1,0,'C');
			$pdf->SetX(235);
			$pdf->Cell(30,5,$ld_subtotalallnilai_format,1,0,'R');

			$pdf->ln();$pdf->ln();
		}
		$ld_grantotaladmpeserta_format = number_format($ld_grantotaladmpeserta,0,".",",");
		$ld_grantotaladmnilai_format = number_format($ld_grantotaladmnilai,2,".",",");
		$ld_grantotalpropeserta_format = number_format($ld_grantotalpropeserta,0,".",",");
		$ld_grantotalpronilai_format = number_format($ld_grantotalpronilai,2,".",",");
		$ld_grantotalfinishpeserta_format = number_format($ld_grantotalfinishpeserta,0,".",",");
		$ld_grantotalfinishnilai_format = number_format($ld_grantotalfinishnilai,2,".",",");
		$ld_grantotalallpeserta_format = number_format($ld_grantotalallpeserta,0,".",",");
		$ld_grantotalallnilai_format = number_format($ld_grantotalallnilai,2,".",",");

		$pdf->SetFont('Times','B',7);
		$pdf->SetX(10);
		$pdf->Cell(55,5,'TOTAL',1,0,'C');
		$pdf->SetX(65);
		$pdf->Cell(10,5,$ld_grantotaladmpeserta_format,1,0,'C');
		$pdf->SetX(75);
		$pdf->Cell(30,5,$ld_grantotaladmnilai_format,1,0,'R');

		$pdf->SetX(105);
		$pdf->Cell(10,5,$ld_grantotalpropeserta_format,1,0,'C');
		$pdf->SetX(115);
		$pdf->Cell(30,5,$ld_grantotalpronilai_format,1,0,'R');

		$pdf->SetX(145);
		$pdf->Cell(10,5,'',1,0,'C');
		$pdf->SetX(155);
		$pdf->Cell(30,5,'',1,0,'C');

		$pdf->SetX(185);
		$pdf->Cell(10,5,$ld_grantotalfinishpeserta_format,1,0,'C');
		$pdf->SetX(195);
		$pdf->Cell(30,5,$ld_grantotalfinishnilai_format,1,0,'C');

		$pdf->SetX(225);
		$pdf->Cell(10,5,$ld_grantotalallpeserta_format,1,0,'C');
		$pdf->SetX(235);
		$pdf->Cell(30,5,$ld_grantotalallnilai_format,1,0,'R');
		$pdf->Output();
		break;
case "eL_prod":
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];
	$ls_status = $_REQUEST['status'];
	$ls_paid = $_REQUEST['paid'];
	$ls_idprod = $_REQUEST['subcat'];
	$li_idreg = $_REQUEST['id_reg'];

	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];

	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'SUMMARY PRODUKSI',0,0,'L');
	$pdf->ln();

	$bulan = substr($ldt_tanggal2,5,2);
	$tahun = substr($ldt_tanggal2,0,4);
	$ls_namabulan = bulanindo($bulan);
	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'Periode Bulan : '.$ls_namabulan.' '.$tahun,0,0,'L');
	$pdf->ln();
	$pdf->Cell(0,5,'as per tanggal DN: '._convertDate($ldt_tanggal2),0,0,'L');
	$pdf->ln();
	if($_REQUEST['statpeserta']!==''){
		$pdf->Cell(0,5,'Status Peserta: '.$_REQUEST['statpeserta'],0,0,'L');
		$pdf->ln();
	}
	$year= date("Y");
	$monthly = date("Y-m", strtotime($dateEnd));
	$startMonth = $monthly."-01";
	$startDate = $year."-01-01";


	$queryproduk =  mysql_query("SELECT
	fu_ajk_polis.nmproduk
	FROM
	fu_ajk_peserta
	LEFT JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
	LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
	WHERE fu_ajk_peserta.id !=''
	AND fu_ajk_peserta.del is NULL
	AND fu_ajk_peserta.status_aktif='Inforce'
	AND fu_ajk_peserta.id_cost = '".$li_idperserta."'
	AND fu_ajk_polis.nmproduk IS NOT NULL
	AND (day(fu_ajk_dn.tgl_createdn) = day('".$ldt_tanggal2."') OR (MONTH(fu_ajk_dn.tgl_createdn) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_dn.tgl_createdn) = YEAR('".$ldt_tanggal2."')) OR YEAR(fu_ajk_dn.tgl_createdn) = YEAR('".$ldt_tanggal2."'))
	GROUP BY fu_ajk_polis.nmproduk");
	$li_grantot_dailyperserta = 0;
	$li_grantot_monthlypeserta = 0;
	$li_grantot_yearlypeserta = 0;
	$ld_grantot_dailykredit = 0;
	$ld_grantot_monthlykredit = 0;
	$ld_grantot_yearlykredit = 0;
	$ld_grantot_dailypremi = 0;
	$ld_grantot_monthlypremi = 0;
	$ld_grantot_yearlypremi = 0;

	while($rowproduk = mysql_fetch_array($queryproduk)){
		$ls_namaproduk = $rowproduk['nmproduk'];
		//HEADER
		$pdf->SetFont('Times','B',7);
		$pdf->SetX(10);
		$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
		$pdf->SetX(65);
		$pdf->Cell(70,5,'DAILY',1,0,'C');
		$pdf->SetX(135);
		$pdf->Cell(70,5,'MONTHLY',1,0,'C');
		$pdf->SetX(205);
		$pdf->Cell(70,5,'YEARLY',1,0,'C');
		$pdf->ln();

		$pdf->SetX(10);
		$pdf->Cell(5,5,'No',1,0,'C');
		$pdf->SetX(15);
		$pdf->Cell(50,5,'Nama Regional',1,0,'C');

		$pdf->SetX(65);
		$pdf->Cell(10,5,'Peserta',1,0,'C');
		$pdf->SetX(75);
		$pdf->Cell(30,5,'Kredit',1,0,'C');
		$pdf->SetX(105);
		$pdf->Cell(30,5,'Premi',1,0,'C');

		$pdf->SetX(135);
		$pdf->Cell(10,5,'Peserta',1,0,'C');
		$pdf->SetX(145);
		$pdf->Cell(30,5,'Kredit',1,0,'C');
		$pdf->SetX(175);
		$pdf->Cell(30,5,'Premi',1,0,'C');

		$pdf->SetX(205);
		$pdf->Cell(10,5,'Peserta',1,0,'C');
		$pdf->SetX(215);
		$pdf->Cell(30,5,'Kredit',1,0,'C');
		$pdf->SetX(245);
		$pdf->Cell(30,5,'Premi',1,0,'C');
		$pdf->ln();
		$pdf->SetFont('Times','',7);
		$tujuh='';
		$delapan='';

		if ($_REQUEST['statpeserta'])	{
			$status_ = explode("-", $_REQUEST['statpeserta']);
			if (!$status_[1]) {
				$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
			}else{
				$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
				$delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
			}
		}

$queryproduksi =  mysql_query("SELECT
	fu_ajk_peserta.regional,
	fu_ajk_polis.nmproduk,
	count(case when fu_ajk_dn.tgl_createdn = '".$ldt_tanggal2."' then fu_ajk_peserta.nama END) AS daily_peserta,
	count(case when fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.nama END) AS monthly_peserta,
	count(case when fu_ajk_dn.tgl_createdn BETWEEN '".$startDate."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.nama END) AS yearly_peserta,
	sum(case when fu_ajk_dn.tgl_createdn = '".$ldt_tanggal2."' then fu_ajk_peserta.kredit_jumlah END) AS daily_kredit,
	sum(case when fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.kredit_jumlah END) AS monthly_kredit,
	sum(case when fu_ajk_dn.tgl_createdn BETWEEN '".$startDate."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.kredit_jumlah END) AS yearly_kredit,
	sum(case when fu_ajk_dn.tgl_createdn = '".$ldt_tanggal2."' then fu_ajk_peserta.totalpremi END) AS daily_premi,
	sum(case when fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.totalpremi END) AS monthly_premi,
	sum(case when fu_ajk_dn.tgl_createdn  BETWEEN '".$startDate."' AND '".$ldt_tanggal2."' then fu_ajk_peserta.totalpremi END) AS yearly_premi
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	WHERE fu_ajk_peserta.id_dn !=''
	AND fu_ajk_dn.del IS NULL
	AND fu_ajk_peserta.del IS NULL
	AND fu_ajk_polis.nmproduk = '".$ls_namaproduk."'
	AND (
			(day(fu_ajk_dn.tgl_createdn) = day('".$ldt_tanggal2."') AND MONTH(fu_ajk_dn.tgl_createdn) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_dn.tgl_createdn) = YEAR('".$ldt_tanggal2."')
		)
		OR (
			MONTH(fu_ajk_dn.tgl_createdn) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_dn.tgl_createdn) = YEAR('".$ldt_tanggal2."')
			)
		OR (YEAR(fu_ajk_dn.tgl_createdn) = YEAR('".$ldt_tanggal2."'))
		)
AND fu_ajk_peserta.id_cost = '".$li_idperserta."'
AND fu_ajk_peserta.regional !=''
".$tujuh." ".$delapan."
GROUP BY fu_ajk_costumer.name,
fu_ajk_polis.nmproduk, fu_ajk_peserta.regional");
		$li_sub_dailyperserta = 0;
		$li_sub_monthlypeserta = 0;
		$li_sub_yearlypeserta = 0;
		$ld_sub_dailykredit = 0;
		$ld_sub_monthlykredit = 0;
		$ld_sub_yearlykredit = 0;
		$ld_sub_dailypremi = 0;
		$ld_sub_monthlypremi = 0;
		$ld_sub_yearlypremi = 0;
		$li_row = 1;
		while($rowproduksi = mysql_fetch_array($queryproduksi)){
			$ls_regional = $rowproduksi['regional'];
			$li_daily_peserta = $rowproduksi['daily_peserta'];
			$li_daily_peserta_format = number_format($li_daily_peserta,0,".",",");
			$li_monthly_peserta = $rowproduksi['monthly_peserta'];
			$li_monthly_peserta_format = number_format($li_monthly_peserta,0,".",",");
			$li_yearly_peserta = $rowproduksi['yearly_peserta'];
			$li_yearly_peserta_format = number_format($li_yearly_peserta,0,".",",");

			$ld_daily_kredit = $rowproduksi['daily_kredit'];
			$ld_daily_kredit_format = number_format($ld_daily_kredit,2,".",",");
			$ld_monthly_kredit = $rowproduksi['monthly_kredit'];
			$ld_monthly_kredit_format = number_format($ld_monthly_kredit,2,".",",");
			$ld_yearly_kredit = $rowproduksi['yearly_kredit'];
			$ld_yearly_kredit_format = number_format($ld_yearly_kredit,2,".",",");

			$ld_daily_premi = $rowproduksi['daily_premi'];
			$ld_daily_premi_format = number_format($ld_daily_premi,2,".",",");
			$ld_monthly_premi = $rowproduksi['monthly_premi'];
			$ld_monthly_premi_format = number_format($ld_monthly_premi,2,".",",");
			$ld_yearly_premi = $rowproduksi['yearly_premi'];
			$ld_yearly_premi_format = number_format($ld_yearly_premi,2,".",",");

			$li_sub_dailyperserta += $li_daily_peserta; $li_sub_dailyperserta_format = number_format($li_sub_dailyperserta,0,".",",");
			$li_sub_monthlypeserta += $li_monthly_peserta; $li_sub_monthlypeserta_format = number_format($li_sub_monthlypeserta,0,".",",");
			$li_sub_yearlypeserta += $li_yearly_peserta; $li_sub_yearlypeserta_format = number_format($li_sub_yearlypeserta,0,".",",");
			$ld_sub_dailykredit += $ld_daily_kredit; $ld_sub_dailykredit_format = number_format($ld_sub_dailykredit,2,".",",");
			$ld_sub_monthlykredit += $ld_monthly_kredit; $ld_sub_monthlykredit_format = number_format($ld_sub_monthlykredit,2,".",",");
			$ld_sub_yearlykredit += $ld_yearly_kredit; $ld_sub_yearlykredit_format = number_format($ld_sub_yearlykredit,2,".",",");
			$ld_sub_dailypremi += $ld_daily_premi; $ld_sub_dailypremi_format = number_format($ld_sub_dailypremi,2,".",",");
			$ld_sub_monthlypremi += $ld_monthly_premi; $ld_sub_monthlypremi_format = number_format($ld_sub_monthlypremi,2,".",",");
			$ld_sub_yearlypremi += $ld_yearly_premi; $ld_sub_yearlypremi_format = number_format($ld_sub_yearlypremi,2,".",",");



			$pdf->SetX(10);
			$pdf->Cell(5,5,$li_row,1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(50,5,$ls_regional,1,0,'L');

			$pdf->SetX(65);
			$pdf->Cell(10,5,$li_daily_peserta_format,1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(30,5,$ld_daily_kredit_format,1,0,'R');
			$pdf->SetX(105);
			$pdf->Cell(30,5,$ld_daily_premi_format,1,0,'R');

			$pdf->SetX(135);
			$pdf->Cell(10,5,$li_monthly_peserta_format,1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(30,5,$ld_monthly_kredit_format,1,0,'R');
			$pdf->SetX(175);
			$pdf->Cell(30,5,$ld_monthly_premi_format,1,0,'R');

			$pdf->SetX(205);
			$pdf->Cell(10,5,$li_yearly_peserta_format,1,0,'C');
			$pdf->SetX(215);
			$pdf->Cell(30,5,$ld_yearly_kredit_format,1,0,'R');
			$pdf->SetX(245);
			$pdf->Cell(30,5,$ld_yearly_premi_format,1,0,'R');
			$pdf->ln();
			$li_row++;
		}
		$li_loop++;
		$li_grantot_dailyperserta += $li_sub_dailyperserta;
		$li_grantot_monthlypeserta += $li_sub_monthlypeserta;
		$li_grantot_yearlypeserta += $li_sub_yearlypeserta;
		$ld_grantot_dailykredit += $ld_sub_dailykredit;
		$ld_grantot_monthlykredit += $ld_sub_monthlykredit;
		$ld_grantot_yearlykredit += $ld_sub_yearlykredit;
		$ld_grantot_dailypremi += $ld_sub_dailypremi;
		$ld_grantot_monthlypremi += $ld_sub_monthlypremi;
		$ld_grantot_yearlypremi += $ld_sub_yearlypremi;

		$pdf->SetFont('Times','B',7);
		$pdf->SetX(10);
		$pdf->Cell(55,5,'subtotal',1,0,'C');
		$pdf->SetX(65);
		$pdf->Cell(10,5,$li_sub_dailyperserta_format,1,0,'C');
		$pdf->SetX(75);
		$pdf->Cell(30,5,$ld_sub_dailykredit_format,1,0,'R');
		$pdf->SetX(105);
		$pdf->Cell(30,5,$ld_sub_dailypremi_format,1,0,'R');

		$pdf->SetX(135);
		$pdf->Cell(10,5,$li_sub_monthlypeserta_format,1,0,'C');
		$pdf->SetX(145);
		$pdf->Cell(30,5,$ld_sub_monthlykredit_format,1,0,'R');
		$pdf->SetX(175);
		$pdf->Cell(30,5,$ld_sub_monthlypremi_format,1,0,'R');

		$pdf->SetX(205);
		$pdf->Cell(10,5,$li_sub_yearlypeserta_format,1,0,'C');
		$pdf->SetX(215);
		$pdf->Cell(30,5,$ld_sub_yearlykredit_format,1,0,'R');
		$pdf->SetX(245);
		$pdf->Cell(30,5,$ld_sub_yearlypremi_format,1,0,'R');
		$li_jumlah = $li_jumlah + $li_row;
		if($li_loop==2){
			$li_loop=0;
			$pdf->AddPage();
		}

		if($li_loop=="3"){

		}
		$pdf->ln();$pdf->ln();
	}
	$li_grantot_dailyperserta = number_format($li_grantot_dailyperserta,0,".",",");
	$li_grantot_monthlypeserta = number_format($li_grantot_monthlypeserta,0,".",",");
	$li_grantot_yearlypeserta = number_format($li_grantot_yearlypeserta,0,".",",");
	$ld_grantot_dailykredit = number_format($ld_grantot_dailykredit,2,".",",");
	$ld_grantot_monthlykredit = number_format($ld_grantot_monthlykredit,2,".",",");
	$ld_grantot_yearlykredit = number_format($ld_grantot_yearlykredit,2,".",",");
	$ld_grantot_dailypremi = number_format($ld_grantot_dailypremi,2,".",",");
	$ld_grantot_monthlypremi = number_format($ld_grantot_monthlypremi,2,".",",");
	$ld_grantot_yearlypremi = number_format($ld_grantot_yearlypremi,2,".",",");

	$pdf->SetFont('Times','B',7);
	$pdf->SetX(10);
	$pdf->Cell(55,5,'TOTAL',1,0,'C');
	$pdf->SetX(65);
	$pdf->Cell(10,5,$li_grantot_dailyperserta,1,0,'C');
	$pdf->SetX(75);
	$pdf->Cell(30,5,$ld_grantot_dailykredit,1,0,'R');
	$pdf->SetX(105);
	$pdf->Cell(30,5,$ld_grantot_dailypremi,1,0,'R');

	$pdf->SetX(135);
	$pdf->Cell(10,5,$li_grantot_monthlypeserta,1,0,'C');
	$pdf->SetX(145);
	$pdf->Cell(30,5,$ld_grantot_monthlykredit,1,0,'R');
	$pdf->SetX(175);
	$pdf->Cell(30,5,$ld_grantot_monthlypremi,1,0,'R');

	$pdf->SetX(205);
	$pdf->Cell(10,5,$li_grantot_yearlypeserta,1,0,'C');
	$pdf->SetX(215);
	$pdf->Cell(30,5,$ld_grantot_yearlykredit,1,0,'R');
	$pdf->SetX(245);
	$pdf->Cell(30,5,$ld_grantot_yearlypremi,1,0,'R');
	$pdf->Output();
	break;
case "eL_tagprem":
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];
	$ls_status = $_REQUEST['status'];
	$ls_paid = $_REQUEST['paid'];
	$ls_idprod = $_REQUEST['subcat'];
	$li_idreg = $_REQUEST['id_reg'];
	if ($_REQUEST['gpr']) {
		$groupprod = $_REQUEST['gpr'];
		$_groupproduk = 'HAVING granprem > 0';
	}else{
		$groupprod = $_REQUEST['gpr'];
		$_groupproduk = '';
	}

	if ($_REQUEST['paiddata']=="paid") {
		$paiddebitnote = "AND (fu_ajk_dn.dn_status ='paid' OR fu_ajk_dn.dn_status ='Lunas')";
	}elseif ($_REQUEST['paiddata']=="paid(*)") {
		$paiddebitnote = "AND (fu_ajk_dn.dn_status ='paid(*)' OR fu_ajk_dn.dn_status ='Kurang Bayar')";
	}elseif ($_REQUEST['paiddata']=="unpaid") {
		$paiddebitnote = "AND fu_ajk_dn.dn_status ='unpaid'";
	}else{
		$paiddebitnote = "";
	}

	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];
	$querygprod = mysql_query("SELECT id, nmproduk FROM fu_ajk_polis WHERE grupproduk = '".$groupprod."' ORDER BY id ASC LIMIT 0,3");
	$jmlallpol = mysql_num_rows($querygprod);
	$jml_pol =1;
	while($rowgprod = mysql_fetch_array($querygprod)){
		$id_polis = $rowgprod['id'];
		$totprem .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) as totPrem$jml_pol,";
		$totprembayar .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."' AND (fu_ajk_dn.dn_status = 'paid' OR fu_ajk_dn.dn_status = 'Lunas'),IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) as premiBayar$jml_pol,";
		$totpremout .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."' AND (fu_ajk_dn.dn_status <> 'paid' OR fu_ajk_dn.dn_status <> 'Lunas'),IF(fu_ajk_peserta.status_bayar='1' AND fu_ajk_peserta.status_peserta !='Batal', 0, fu_ajk_peserta.totalpremi),0)) as premiOut$jml_pol,";
		$namapol .= "'".$rowgprod['nmproduk']."' as namapol$jml_pol,";
		if($jml_pol==$jmlallpol){
			if($jml_pol > 1){
				$granttotprem .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) as granprem,";
			}
		}else{
			if($jml_pol > 1){
				$granttotprem .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) +";
			}else{
				$granttotprem .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) as granprem,";
			}
		}

		$jml_pol++;
	}

	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'SUMMARY OUTSTANDING TAGIHAN PREMI',0,0,'L');
	$pdf->ln();

	$tgl1 = substr($ldt_tanggal1,8,2);
	$bulan1 = substr($ldt_tanggal1,5,2);
	$tahun1 = substr($ldt_tanggal1,0,4);
	$tgl2 = substr($ldt_tanggal2,8,2);
	$bulan2 = substr($ldt_tanggal2,5,2);
	$tahun2 = substr($ldt_tanggal2,0,4);
	$ls_namabulan1 = bulanindo($bulan1);
	$ls_namabulan2 = bulanindo($bulan2);
	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'Periode : '.$tgl1.' '.$ls_namabulan1.' '.$tahun1. ' s/d '.$tgl2.' '.$ls_namabulan2.' '.$tahun2,0,0,'L');
	$pdf->ln();
	$pdf->Cell(0,5,'as per tanggal DN: '._convertDate($ldt_tanggal2),0,0,'L');
	$pdf->ln();

	$li_row = 1;
	$queryout =  mysql_query("SELECT
	Count(fu_ajk_peserta.nama) as jml_peserta,
	fu_ajk_peserta.cabang,
	$granttotprem
	$namapol
	$totprem
	$totprembayar
	$totpremout
	fu_ajk_peserta.id_polis
	FROM fu_ajk_peserta
	LEFT JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
	LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
	WHERE fu_ajk_peserta.id !='' AND fu_ajk_peserta.id is not null
	AND fu_ajk_peserta.del is NULL
	$paiddebitnote
	AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
	GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
	$_groupproduk
	ORDER BY fu_ajk_peserta.cabang ASC");
	$li_loop = 26;
	while($rowout = mysql_fetch_array($queryout)){
		$ls_cabang = $rowout['cabang'];
		$ld_bayarSPK = $rowout['premiBayar1'];
		$ld_bayarPercepatan = $rowout['premiBayar2'];
		$ld_bayarABRI = $rowout['premiBayar3'];
		$ld_OutSPK = $rowout['premiOut1'];
		$ld_OutPercepatan = $rowout['premiOut2'];
		$ld_OutABRI = $rowout['premiOut3'];

		$ld_totbayarSPK = $rowout['totPrem1']; //$ld_bayarSPK + $ld_OutSPK;
		$ld_totbayarPercepatan = $rowout['totPrem2']; //$ld_bayarPercepatan + $ld_OutPercepatan;
		$ld_totbayarABRI = $rowout['totPrem3']; //$ld_bayarABRI + $ld_OutABRI;

		$ld_OutSPK = $ld_totbayarSPK - $ld_bayarSPK;
		$ld_OutPercepatan = $ld_totbayarPercepatan - $ld_bayarPercepatan;
		$ld_OutABRI = $ld_totbayarABRI - $ld_bayarABRI;

		$ld_bayarSPK_format = number_format($ld_bayarSPK,2,".",",");
		$ld_bayarPercepatan_format = number_format($ld_bayarPercepatan,2,".",",");
		$ld_bayarABRI_format = number_format($ld_bayarABRI,2,".",",");
		$ld_OutSPK_format = number_format($ld_OutSPK,2,".",",");
		$ld_OutPercepatan_format = number_format($ld_OutPercepatan,2,".",",");
		$ld_OutABRI_format = number_format($ld_OutABRI,2,".",",");

		$ld_totbayarSPK_format = number_format($ld_totbayarSPK,2,".",",");
		$ld_totbayarPercepatan_format = number_format($ld_totbayarPercepatan,2,".",",");
		$ld_totbayarABRI_format = number_format($ld_totbayarABRI,2,".",",");

		$li_percenSPK = $ld_OutSPK / $ld_totbayarSPK *100;
		$li_percenPercepatan = $ld_OutPercepatan / $ld_totbayarPercepatan *100;
		$li_percenABRI = $ld_OutABRI / $ld_totbayarABRI *100;

		$li_percenSPK_format = number_format($li_percenSPK,0,".",",");
		$li_percenPercepatan_format = number_format($li_percenPercepatan,0,".",",");
		$li_percenABRI_format = number_format($li_percenABRI,0,".",",");

		$ld_granbayarSPK += $ld_bayarSPK;
		$ld_granbayarPercepatan += $ld_bayarPercepatan;
		$ld_granbayarABRI += $ld_bayarABRI;
		$ld_granOutSPK += $ld_OutSPK;
		$ld_granOutPercepatan += $ld_OutPercepatan;
		$ld_granOutABRI += $ld_OutABRI;
		$ld_grantotbayarSPK += $ld_totbayarSPK;
		$ld_grantotbayarPercepatan += $ld_totbayarPercepatan;
		$ld_grantotbayarABRI += $ld_totbayarABRI;
		$namapol1 = $rowout['namapol1'];
		$namapol2 = $rowout['namapol2'];
		$namapol3 = $rowout['namapol3'];

		if($li_row==1){
			//HEADER
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
			$pdf->SetX(45);
			$pdf->Cell(80,5,$namapol1,1,0,'C');
			$pdf->SetX(125);
			$pdf->Cell(80,5,$namapol2,1,0,'C');
			$pdf->SetX(205);
			$pdf->Cell(80,5,$namapol3,1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

			$pdf->SetX(45);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(65);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(90);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(10,5,'%',1,0,'C');

			$pdf->SetX(125);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(170);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(10,5,'%',1,0,'C');

			$pdf->SetX(205);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(250);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(275);
			$pdf->Cell(10,5,'%',1,0,'C');

			$pdf->ln();
			$pdf->SetFont('Times','',7);
		}

		$pdf->SetX(10);
		$pdf->Cell(5,5,$li_row,1,0,'C');
		$pdf->SetX(15);
		$pdf->Cell(30,5,$ls_cabang,1,0,'L');

		$pdf->SetX(45);
		$pdf->Cell(20,5,$ld_totbayarSPK_format,1,0,'R');
		$pdf->SetX(65);
		$pdf->Cell(25,5,$ld_bayarSPK_format,1,0,'R');
		$pdf->SetX(90);
		$pdf->Cell(25,5,$ld_OutSPK_format,1,0,'R');
		$pdf->SetX(115);
		$pdf->Cell(10,5,$li_percenSPK_format.'%',1,0,'C');

		$pdf->SetX(125);
		$pdf->Cell(20,5,$ld_totbayarPercepatan_format,1,0,'R');
		$pdf->SetX(145);
		$pdf->Cell(25,5,$ld_bayarPercepatan_format,1,0,'R');
		$pdf->SetX(170);
		$pdf->Cell(25,5,$ld_OutPercepatan_format,1,0,'R');
		$pdf->SetX(195);
		$pdf->Cell(10,5,$li_percenPercepatan_format.'%',1,0,'C');

		$pdf->SetX(205);
		$pdf->Cell(20,5,$ld_totbayarABRI_format,1,0,'R');
		$pdf->SetX(225);
		$pdf->Cell(25,5,$ld_bayarABRI_format,1,0,'R');
		$pdf->SetX(250);
		$pdf->Cell(25,5,$ld_OutABRI_format,1,0,'R');
		$pdf->SetX(275);
		$pdf->Cell(10,5,$li_percenABRI_format.'%',1,0,'C');
		$pdf->ln();
		$li_row++;

		if($li_loop==$li_row){
			$li_loop = $li_loop+26;
			$pdf->AddPage();
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
			$pdf->SetX(45);
			$pdf->Cell(80,5,$namapol1,1,0,'C');
			$pdf->SetX(125);
			$pdf->Cell(80,5,$namapol2,1,0,'C');
			$pdf->SetX(205);
			$pdf->Cell(80,5,$namapol3,1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

			$pdf->SetX(45);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(65);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(90);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(10,5,'%',1,0,'C');

			$pdf->SetX(125);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(145);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(170);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(10,5,'%',1,0,'C');

			$pdf->SetX(205);
			$pdf->Cell(20,5,'TOTAL PREMI',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(25,5,'PREMI DIBAYAR',1,0,'C');
			$pdf->SetX(250);
			$pdf->Cell(25,5,'OUTSTANDING',1,0,'C');
			$pdf->SetX(275);
			$pdf->Cell(10,5,'%',1,0,'C');
			$pdf->ln();
			$pdf->SetFont('Times','',7);
		}

	}

	$li_granpercenSPK = $ld_granOutSPK / $ld_grantotbayarSPK * 100;
	$li_granpercenPercepatan = $ld_granOutPercepatan / $ld_grantotbayarPercepatan * 100;
	$li_granpercenABRI = $ld_granOutABRI / $ld_grantotbayarABRI * 100;

	$ld_granbayarSPK = number_format($ld_granbayarSPK,2,".",",");
	$ld_granbayarPercepatan = number_format($ld_granbayarPercepatan,2,".",",");
	$ld_granbayarABRI = number_format($ld_granbayarABRI,2,".",",");
	$ld_granOutSPK = number_format($ld_granOutSPK,2,".",",");
	$ld_granOutPercepatan = number_format($ld_granOutPercepatan,2,".",",");
	$ld_granOutABRI = number_format($ld_granOutABRI,2,".",",");
	$li_granpercenSPK = number_format($li_granpercenSPK,0,".",",");
	$li_granpercenPercepatan = number_format($li_granpercenPercepatan,0,".",",");
	$li_granpercenABRI = number_format($li_granpercenABRI,0,".",",");
	$ld_grantotbayarSPK = number_format($ld_grantotbayarSPK,2,".",",");
	$ld_grantotbayarPercepatan = number_format($ld_grantotbayarPercepatan,2,".",",");
	$ld_grantotbayarABRI = number_format($ld_grantotbayarABRI,2,".",",");

	$pdf->SetFont('Times','B',8);
	$pdf->SetX(10);
	$pdf->Cell(35,5,'Total',1,0,'C');

	$pdf->SetX(45);
	$pdf->Cell(20,5,$ld_grantotbayarSPK,1,0,'R');
	$pdf->SetX(65);
	$pdf->Cell(25,5,$ld_granbayarSPK,1,0,'R');
	$pdf->SetX(90);
	$pdf->Cell(25,5,$ld_granOutSPK,1,0,'R');
	$pdf->SetX(115);
	$pdf->Cell(10,5,$li_granpercenSPK.'%',1,0,'C');

	$pdf->SetX(125);
	$pdf->Cell(20,5,$ld_grantotbayarPercepatan,1,0,'R');
	$pdf->SetX(145);
	$pdf->Cell(25,5,$ld_granbayarPercepatan,1,0,'R');
	$pdf->SetX(170);
	$pdf->Cell(25,5,$ld_granOutPercepatan,1,0,'R');
	$pdf->SetX(195);
	$pdf->Cell(10,5,$li_granpercenPercepatan.'%',1,0,'C');

	$pdf->SetX(205);
	$pdf->Cell(20,5,$ld_grantotbayarABRI,1,0,'R');
	$pdf->SetX(225);
	$pdf->Cell(25,5,$ld_granbayarABRI,1,0,'R');
	$pdf->SetX(250);
	$pdf->Cell(25,5,$ld_granOutABRI,1,0,'R');
	$pdf->SetX(275);
	$pdf->Cell(10,5,$li_granpercenABRI.'%',1,0,'C');
	$pdf->Output();
	break;
case "eL_prodcab":
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];
	$ls_status = $_REQUEST['status'];
	$ls_paid = $_REQUEST['paid'];
	$ls_idprod = $_REQUEST['subcat'];
	$li_idreg = $_REQUEST['id_reg'];
	$groupprod = $_REQUEST['gpr'];

	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];
	$querygprod = mysql_query("SELECT id, nmproduk FROM fu_ajk_polis WHERE grupproduk = '".$groupprod."' ORDER BY id ASC LIMIT 0,3");
	$jmlallpol = mysql_num_rows($querygprod);
	$jml_pol = 1;
	while($rowgprod = mysql_fetch_array($querygprod)){
		$id_polis = $rowgprod['id'];
		$jumlahperserta .= "COUNT(case when id_polis = '".$id_polis."' then fu_ajk_peserta.nama end) as perserta$jml_pol,";
		$totprem .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.totalpremi),0)) as totPrem$jml_pol,";
		$jmlkredit .= "SUM(IF(fu_ajk_peserta.id_polis = '".$id_polis."',IF(fu_ajk_peserta.status_bayar='0' AND fu_ajk_peserta.status_peserta ='Batal', 0, fu_ajk_peserta.kredit_jumlah),0)) as produk$jml_pol,";
		$namapol .= "'".$rowgprod['nmproduk']."' as namapol$jml_pol,";
		if($jml_pol==$jmlallpol){
			if($jml_pol>1){
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) as jumlah_peserta,";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0)";
				$jmlklaim .="IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_cn.total_claim,0)";
			}
		}else{
			if($jml_pol>1){
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) +";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) + ";
			}elseif($jml_pol==$jmlallpol AND $jml_pol ==1){
				$totalpeserta = "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) as jumlah_peserta,";
				$jumlahkredit = "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) ";
			}else{
				$totalpeserta .= "count(case when fu_ajk_peserta.id_polis = '".$id_polis."' then fu_ajk_peserta.nama END) +";
				$jumlahkredit .= "IF(fu_ajk_peserta.id_polis = '".$id_polis."', fu_ajk_peserta.kredit_jumlah,0) + ";
			}
		}

		$jml_pol++;
	}
	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'SUMMARY PRODUKSI PER CABANG',0,0,'L');
	$pdf->ln();

	$bulan = substr($ldt_tanggal2,5,2);
	$tahun = substr($ldt_tanggal2,0,4);
	$ls_namabulan = bulanindo($bulan);
	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'Periode Bulan : '.$ls_namabulan.' '.$tahun,0,0,'L');
	$pdf->ln();
	$pdf->Cell(0,5,'as per tanggal DN: '._convertDate($ldt_tanggal2),0,0,'L');
	$pdf->ln();

	$li_row = 1;
	$li_loop=26;
	$queryprodukcab =  mysql_query("SELECT
	Count(fu_ajk_peserta.nama) as jml_peserta,
	fu_ajk_peserta.cabang,
	$totalpeserta
	$jumlahperserta
	$jmlkredit
	$namapol
	SUM($jumlahkredit) as jumlah_kredit,
	$totprem
	fu_ajk_peserta.id_polis
	FROM fu_ajk_peserta
	LEFT JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
	LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
	WHERE fu_ajk_dn.id != '' AND fu_ajk_peserta.id IS NOT NULL AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
			/*fu_ajk_peserta.id !='' AND fu_ajk_peserta.id is not null AND fu_ajk_peserta.del is NULL*/
	AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
	GROUP BY fu_ajk_peserta.id_cost,fu_ajk_peserta.cabang
	HAVING jumlah_peserta > 0
	ORDER BY fu_ajk_peserta.cabang ASC");

	while($rowprodcab = mysql_fetch_array($queryprodukcab)){
		$ls_cabang = $rowprodcab['cabang'];
		$li_pesertaSPK = $rowprodcab['perserta1'];
		$li_pesertaPercepatan  = $rowprodcab['perserta2'];
		$li_pesertaAbri = $rowprodcab['perserta3'];
		$ld_krditSPK = $rowprodcab['produk1'];
		$ld_krditPercepatan = $rowprodcab['produk2'];
		$ld_krditABRI = $rowprodcab['produk3'];
		$ld_premiSPK = $rowprodcab['totPrem1'];
		$ld_premiPercepatan = $rowprodcab['totPrem2'];
		$ld_premiABRI = $rowprodcab['totPrem3'];

		$li_pesertaSPK_format = number_format($li_pesertaSPK,0,".",",");
		$li_pesertaPercepatan_format = number_format($li_pesertaPercepatan,0,".",",");
		$li_pesertaAbri_format = number_format($li_pesertaAbri,0,".",",");
		$ld_krditSPK_format = number_format($ld_krditSPK,2,".",",");
		$ld_krditPercepatan_format = number_format($ld_krditPercepatan,2,".",",");
		$ld_krditABRI_format = number_format($ld_krditABRI,2,".",",");
		$ld_premiSPK_format = number_format($ld_premiSPK,2,".",",");
		$ld_premiPercepatan_format = number_format($ld_premiPercepatan,2,".",",");
		$ld_premiABRI_format = number_format($ld_premiABRI,2,".",",");

		$li_tot_pesertaSPK += $li_pesertaSPK;
		$li_tot_pesertaPercepatan += $li_pesertaPercepatan;
		$li_tot_pesertaAbri += $li_pesertaAbri;
		$ld_tot_krditSPK += $ld_krditSPK;
		$ld_tot_krditPercepatan += $ld_krditPercepatan;
		$ld_tot_krditABRI += $ld_krditABRI;
		$ld_tot_premiSPK += $ld_premiSPK;
		$ld_tot_premiPercepatan += $ld_premiPercepatan;
		$ld_tot_premiABRI += $ld_premiABRI;
		$namapol1 = $rowprodcab['namapol1'];
		$namapol2 = $rowprodcab['namapol2'];
		$namapol3 = $rowprodcab['namapol3'];

		if($li_row==1){
			//HEADER
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
			$pdf->SetX(45);
			$pdf->Cell(70,5,$namapol1,1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(70,5,$namapol2,1,0,'C');
			$pdf->SetX(185);
			$pdf->Cell(70,5,$namapol3,1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

			$pdf->SetX(45);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(55);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(85);
			$pdf->Cell(30,5,'Premi',1,0,'C');

			$pdf->SetX(115);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(125);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(155);
			$pdf->Cell(30,5,'Premi',1,0,'C');

			$pdf->SetX(185);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(30,5,'Premi',1,0,'C');
			$pdf->ln();
			$pdf->SetFont('Times','',7);
		}
		$pdf->SetX(10);
		$pdf->Cell(5,5,$li_row,1,0,'C');
		$pdf->SetX(15);
		$pdf->Cell(30,5,$ls_cabang,1,0,'L');

		$pdf->SetX(45);
		$pdf->Cell(10,5,$li_pesertaSPK_format,1,0,'C');
		$pdf->SetX(55);
		$pdf->Cell(30,5,$ld_krditSPK_format,1,0,'R');
		$pdf->SetX(85);
		$pdf->Cell(30,5,$ld_premiSPK_format,1,0,'R');

		$pdf->SetX(115);
		$pdf->Cell(10,5,$li_pesertaPercepatan_format,1,0,'C');
		$pdf->SetX(125);
		$pdf->Cell(30,5,$ld_krditPercepatan_format,1,0,'R');
		$pdf->SetX(155);
		$pdf->Cell(30,5,$ld_premiPercepatan_format,1,0,'R');

		$pdf->SetX(185);
		$pdf->Cell(10,5,$li_pesertaAbri_format,1,0,'C');
		$pdf->SetX(195);
		$pdf->Cell(30,5,$ld_krditABRI_format,1,0,'R');
		$pdf->SetX(225);
		$pdf->Cell(30,5,$ld_premiABRI_format,1,0,'R');
		$pdf->ln();
		$li_row++;
		if($li_loop==$li_row){
			$li_loop = $li_loop+26;
			//HEADER
			$pdf->AddPage();
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(10);
			$pdf->Cell(0,5,'Nama Produk : '.$ls_namaproduk,0,0,'L');
			$pdf->SetX(45);
			$pdf->Cell(70,5,$namapol1,1,0,'C');
			$pdf->SetX(115);
			$pdf->Cell(70,5,$namapol2,1,0,'C');
			$pdf->SetX(185);
			$pdf->Cell(70,5,$namapol3,1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

			$pdf->SetX(45);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(55);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(85);
			$pdf->Cell(30,5,'Premi',1,0,'C');

			$pdf->SetX(115);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(125);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(155);
			$pdf->Cell(30,5,'Premi',1,0,'C');

			$pdf->SetX(185);
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(30,5,'Kredit',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(30,5,'Premi',1,0,'C');
			$pdf->ln();
			$pdf->SetFont('Times','',7);
		}
	}

	$li_tot_pesertaSPK = number_format($li_tot_pesertaSPK,0,".",",");
	$li_tot_pesertaPercepatan = number_format($li_tot_pesertaPercepatan,0,".",",");
	$li_tot_pesertaAbri = number_format($li_tot_pesertaAbri,0,".",",");
	$ld_tot_krditSPK = number_format($ld_tot_krditSPK,2,".",",");
	$ld_tot_krditPercepatan = number_format($ld_tot_krditPercepatan,2,".",",");
	$ld_tot_krditABRI =number_format($ld_tot_krditABRI,2,".",",");
	$ld_tot_premiSPK = number_format($ld_tot_premiSPK,2,".",",");
	$ld_tot_premiPercepatan = number_format($ld_tot_premiPercepatan,2,".",",");
	$ld_tot_premiABRI = number_format($ld_tot_premiABRI,2,".",",");
	$pdf->SetFont('Times','B',8);
	$pdf->SetX(10);
	$pdf->Cell(35,5,'Total',1,0,'C');

	$pdf->SetX(45);
	$pdf->Cell(10,5,$li_tot_pesertaSPK,1,0,'C');
	$pdf->SetX(55);
	$pdf->Cell(30,5,$ld_tot_krditSPK,1,0,'R');
	$pdf->SetX(85);
	$pdf->Cell(30,5,$ld_tot_premiSPK,1,0,'R');

	$pdf->SetX(115);
	$pdf->Cell(10,5,$li_tot_pesertaPercepatan,1,0,'C');
	$pdf->SetX(125);
	$pdf->Cell(30,5,$ld_tot_krditPercepatan,1,0,'R');
	$pdf->SetX(155);
	$pdf->Cell(30,5,$ld_tot_premiPercepatan,1,0,'R');

	$pdf->SetX(185);
	$pdf->Cell(10,5,$li_tot_pesertaAbri,1,0,'C');
	$pdf->SetX(195);
	$pdf->Cell(30,5,$ld_tot_krditABRI,1,0,'R');
	$pdf->SetX(225);
	$pdf->Cell(30,5,$ld_tot_premiABRI,1,0,'R');
	$pdf->Output();
break;
case "eL_pemkes":
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$li_idperserta = $_REQUEST['cat'];
	$ldt_tanggal1 = $_REQUEST['tgl1'];
	$ldt_tanggal2 = $_REQUEST['tgl2'];
	$ls_status = $_REQUEST['status'];
	$ls_paid = $_REQUEST['paid'];
	$ls_idprod = $_REQUEST['subcat'];
	$li_idreg = $_REQUEST['id_reg'];

	$querycust =  mysql_query("select id, fu_ajk_costumer.name as cust_name from fu_ajk_costumer where id='".$li_idperserta."'") or die("gagal query customer");
	$rowcust = mysql_fetch_array($querycust);
	$ls_name = $rowcust['cust_name'];
	$li_id = $rowcust['id'];

	$pdf->SetFont('Times','IB',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,$ls_name,0,0,'L');
	$pdf->ln();
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'SUMMARY LAPORAN PEMERIKSAAN KESEHATAN',0,0,'L');
	$pdf->ln();

	$bulan = substr($ldt_tanggal2,5,2);
	$tahun = substr($ldt_tanggal2,0,4);
	$ls_namabulan = bulanindo($bulan);
	$pdf->SetFont('Times','',9);
	$pdf->SetX(10);
	$pdf->Cell(0,5,'Periode Bulan : '.$ls_namabulan.' '.$tahun,0,0,'L');
	$pdf->ln();
	$pdf->Cell(0,5,'as per tanggal : '._convertDate($ldt_tanggal2),0,0,'L');
	$pdf->ln();

	//HEADER
	$pdf->SetFont('Times','B',7);
	$pdf->SetX(45);
	$pdf->Cell(75,5,'DAILY',1,0,'C');
	$pdf->SetX(120);
	$pdf->Cell(75,5,'MONTHLY',1,0,'C');
	$pdf->SetX(195);
	$pdf->Cell(75,5,'YEARLY',1,0,'C');
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(5,5,'No',1,0,'C');
	$pdf->SetX(15);
	$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

	$pdf->SetX(45);
	$pdf->Cell(15,5,'Masuk',1,0,'C');
	$pdf->SetX(60);
	$pdf->Cell(15,5,'Diterima',1,0,'C');
	$pdf->SetX(75);
	$pdf->Cell(15,5,'Ditolak',1,0,'C');
	$pdf->SetX(90);
	$pdf->Cell(15,5,'Realisasi',1,0,'C');
	$pdf->SetX(105);
	$pdf->Cell(15,5,'Batal',1,0,'C');

	$pdf->SetX(120);
	$pdf->Cell(15,5,'Masuk',1,0,'C');
	$pdf->SetX(135);
	$pdf->Cell(15,5,'Diterima',1,0,'C');
	$pdf->SetX(150);
	$pdf->Cell(15,5,'Ditolak',1,0,'C');
	$pdf->SetX(165);
	$pdf->Cell(15,5,'Realisasi',1,0,'C');
	$pdf->SetX(180);
	$pdf->Cell(15,5,'Batal',1,0,'C');

	$pdf->SetX(195);
	$pdf->Cell(15,5,'Masuk',1,0,'C');
	$pdf->SetX(210);
	$pdf->Cell(15,5,'Diterima',1,0,'C');
	$pdf->SetX(225);
	$pdf->Cell(15,5,'Ditolak',1,0,'C');
	$pdf->SetX(240);
	$pdf->Cell(15,5,'Realisasi',1,0,'C');
	$pdf->SetX(255);
	$pdf->Cell(15,5,'Batal',1,0,'C');
	$pdf->ln();

	$querypemkes =  mysql_query("SELECT
	fu_ajk_spak.`status`,
	Count(fu_ajk_spak_form.nama) AS jSPK,
	fu_ajk_cabang.`name` AS cabang,
	COUNT(case when DAY(fu_ajk_spak.update_date) = DAY('".$ldt_tanggal2."') AND MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') then fu_ajk_spak.spak END) daily_masuk,
	COUNT(case when MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') then fu_ajk_spak.spak END) monthly_masuk,
	COUNT(case when YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') then fu_ajk_spak.spak END) yearly_masuk,

	COUNT(case when DAY(fu_ajk_spak.update_date) = DAY('".$ldt_tanggal2."') AND MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Aktif'  then fu_ajk_spak.spak END) AS daily_diterima,
	COUNT(case when MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Aktif' then fu_ajk_spak.spak END) AS monthly_diterima,
	COUNT(case when YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Aktif' then fu_ajk_spak.spak END) AS yearly_diterima,

	COUNT(case when DAY(fu_ajk_spak.update_date) = DAY('".$ldt_tanggal2."') AND MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Batal'  then fu_ajk_spak.spak END) AS daily_batal,
	COUNT(case when MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Batal' then fu_ajk_spak.spak END) AS monthly_batal,
	COUNT(case when YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Batal' then fu_ajk_spak.spak END) AS yearly_batal,

	COUNT(case when DAY(fu_ajk_spak.update_date) = DAY('".$ldt_tanggal2."') AND MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Tolak'  then fu_ajk_spak.spak END) AS daily_tolak,
	COUNT(case when MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Tolak' then fu_ajk_spak.spak END) AS monthly_tolak,
	COUNT(case when YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Tolak' then fu_ajk_spak.spak END) AS yearly_tolak,

	COUNT(case when DAY(fu_ajk_spak.update_date) = DAY('".$ldt_tanggal2."') AND MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('2017-01-08') AND fu_ajk_spak.`status`='Realisasi' then fu_ajk_spak.spak END) AS daily_realisasi,
	COUNT(case when MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Realisasi' then fu_ajk_spak.spak END) AS monthly_realisasi,
	COUNT(case when YEAR(fu_ajk_spak.update_date) = YEAR ('".$ldt_tanggal2."') AND fu_ajk_spak.`status`='Realisasi' then fu_ajk_spak.spak END) AS yearly_realisasi

	FROM
	fu_ajk_spak
	INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
	INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
	WHERE
	fu_ajk_spak.id_polis = 1 AND fu_ajk_spak.del IS NULL
	AND (fu_ajk_spak.update_date = '".$ldt_tanggal2."' OR MONTH(fu_ajk_spak.update_date) = MONTH('".$ldt_tanggal2."') AND YEAR(fu_ajk_spak.update_date) = YEAR('".$ldt_tanggal2."') )
	GROUP BY
	fu_ajk_spak_form.cabang,
	fu_ajk_spak.`status`
	ORDER BY
	fu_ajk_spak_form.input_date ASC
	");
	$li_row = 1;
	$li_loop = 26;
	while($rowpemkes = mysql_fetch_array($querypemkes)){
		$ls_cabang = $rowpemkes['cabang'];
		$li_dailymasuk = $rowpemkes['daily_masuk'];
		$li_monthlymasuk = $rowpemkes['monthly_masuk'];
		$li_yearlymasuk = $rowpemkes['yearly_masuk'];

		$li_dailyditerima =  $rowpemkes['daily_diterima'];
		$li_monthlyditerima =  $rowpemkes['monthly_diterima'];
		$li_yearlyditerima =  $rowpemkes['yearly_diterima'];

		$li_dailytolak =  $rowpemkes['daily_tolak'];
		$li_monthlytolak =  $rowpemkes['monthly_tolak'];
		$li_yearlytolak =  $rowpemkes['yearly_tolak'];

		$li_dailyrealisasi =  $rowpemkes['daily_realisasi'];
		$li_monthlyrealisasi =  $rowpemkes['monthly_realisasi'];
		$li_yearlyrealisasi =  $rowpemkes['yearly_realisasi'];

		$li_dailybatal =  $rowpemkes['daily_batal'];
		$li_monthlybatal =  $rowpemkes['monthly_batal'];
		$li_yearlybatal =  $rowpemkes['yearly_batal'];

		$li_granttotdailymasuk += $li_dailymasuk;
		$li_granttotmonthlymasuk += $li_monthlymasuk;
		$li_granttotyearlymasuk += $li_yearlymasuk;

		$li_granttotdailyditerima += $li_dailyditerima;
		$li_granttotmonthlyditerima += $li_monthlyditerima;
		$li_granttotyearlyditerima += $li_yearlyditerima;

		$li_granttotdailytolak +=  $li_dailytolak;
		$li_granttotmonthlytolak += $li_monthlytolak;
		$li_granttotyearlytolak +=  $li_yearlytolak;

		$li_granttotdailyrealisasi += $li_dailyrealisasi;
		$li_granttotmonthlyrealisasi += $li_monthlyrealisasi;
		$li_granttotyearlyrealisasi += $li_yearlyrealisasi;

		$li_granttotdailybatal += $li_dailybatal;
		$li_granttotmonthlybatal += $li_monthlybatal;
		$li_granttotyearlybatal += $li_yearlybatal;


			$pdf->SetX(10);
			$pdf->Cell(5,5,$li_row,1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,$ls_cabang,1,0,'L');

			$pdf->SetX(45);
			$pdf->Cell(15,5,$li_dailymasuk,1,0,'C');
			$pdf->SetX(60);
			$pdf->Cell(15,5,$li_dailyditerima,1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(15,5,$li_dailytolak,1,0,'C');
			$pdf->SetX(90);
			$pdf->Cell(15,5,$li_dailyrealisasi,1,0,'C');
			$pdf->SetX(105);
			$pdf->Cell(15,5,$li_dailybatal,1,0,'C');

			$pdf->SetX(120);
			$pdf->Cell(15,5,$li_monthlymasuk,1,0,'C');
			$pdf->SetX(135);
			$pdf->Cell(15,5,$li_monthlyditerima,1,0,'C');
			$pdf->SetX(150);
			$pdf->Cell(15,5,$li_monthlytolak,1,0,'C');
			$pdf->SetX(165);
			$pdf->Cell(15,5,$li_monthlyrealisasi,1,0,'C');
			$pdf->SetX(180);
			$pdf->Cell(15,5,$li_monthlybatal,1,0,'C');

			$pdf->SetX(195);
			$pdf->Cell(15,5,$li_yearlymasuk,1,0,'C');
			$pdf->SetX(210);
			$pdf->Cell(15,5,$li_yearlyditerima,1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(15,5,$li_yearlytolak,1,0,'C');
			$pdf->SetX(240);
			$pdf->Cell(15,5,$li_yearlyrealisasi,1,0,'C');
			$pdf->SetX(255);
			$pdf->Cell(15,5,$li_yearlybatal,1,0,'C');
			$pdf->ln();
			$li_row++;

		if($li_loop==$li_row){
			$li_loop = $li_loop+26;
			$pdf->AddPage();
			//HEADER
			$pdf->SetFont('Times','B',7);
			$pdf->SetX(45);
			$pdf->Cell(75,5,'DAILY',1,0,'C');
			$pdf->SetX(120);
			$pdf->Cell(75,5,'MONTHLY',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(75,5,'YEARLY',1,0,'C');
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(5,5,'No',1,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(30,5,'Nama Cabang',1,0,'C');

			$pdf->SetX(45);
			$pdf->Cell(15,5,'Masuk',1,0,'C');
			$pdf->SetX(60);
			$pdf->Cell(15,5,'Diterima',1,0,'C');
			$pdf->SetX(75);
			$pdf->Cell(15,5,'Ditolak',1,0,'C');
			$pdf->SetX(90);
			$pdf->Cell(15,5,'Realisasi',1,0,'C');
			$pdf->SetX(105);
			$pdf->Cell(15,5,'Batal',1,0,'C');

			$pdf->SetX(120);
			$pdf->Cell(15,5,'Masuk',1,0,'C');
			$pdf->SetX(135);
			$pdf->Cell(15,5,'Diterima',1,0,'C');
			$pdf->SetX(150);
			$pdf->Cell(15,5,'Ditolak',1,0,'C');
			$pdf->SetX(165);
			$pdf->Cell(15,5,'Realisasi',1,0,'C');
			$pdf->SetX(180);
			$pdf->Cell(15,5,'Batal',1,0,'C');

			$pdf->SetX(195);
			$pdf->Cell(15,5,'Masuk',1,0,'C');
			$pdf->SetX(210);
			$pdf->Cell(15,5,'Diterima',1,0,'C');
			$pdf->SetX(225);
			$pdf->Cell(15,5,'Ditolak',1,0,'C');
			$pdf->SetX(240);
			$pdf->Cell(15,5,'Realisasi',1,0,'C');
			$pdf->SetX(255);
			$pdf->Cell(15,5,'Batal',1,0,'C');
			$pdf->ln();

		}

	}

	$pdf->SetFont('Times','B',7);
	$pdf->SetX(10);
	$pdf->Cell(35,5,'TOTAL',1,0,'C');
	$pdf->SetX(45);
	$pdf->Cell(15,5,$li_granttotdailymasuk,1,0,'C');
	$pdf->SetX(60);
	$pdf->Cell(15,5,$li_granttotdailyditerima,1,0,'C');
	$pdf->SetX(75);
	$pdf->Cell(15,5,$li_granttotdailytolak,1,0,'C');
	$pdf->SetX(90);
	$pdf->Cell(15,5,$li_granttotdailyrealisasi,1,0,'C');
	$pdf->SetX(105);
	$pdf->Cell(15,5,$li_granttotdailybatal,1,0,'C');

	$pdf->SetX(120);
	$pdf->Cell(15,5,$li_granttotmonthlymasuk,1,0,'C');
	$pdf->SetX(135);
	$pdf->Cell(15,5,$li_granttotmonthlyditerima,1,0,'C');
	$pdf->SetX(150);
	$pdf->Cell(15,5,$li_granttotmonthlytolak,1,0,'C');
	$pdf->SetX(165);
	$pdf->Cell(15,5,$li_granttotmonthlyrealisasi,1,0,'C');
	$pdf->SetX(180);
	$pdf->Cell(15,5,$li_granttotmonthlybatal,1,0,'C');

	$pdf->SetX(195);
	$pdf->Cell(15,5,$li_granttotyearlymasuk,1,0,'C');
	$pdf->SetX(210);
	$pdf->Cell(15,5,$li_granttotyearlyditerima,1,0,'C');
	$pdf->SetX(225);
	$pdf->Cell(15,5,$li_granttotyearlytolak,1,0,'C');
	$pdf->SetX(240);
	$pdf->Cell(15,5,$li_granttotyearlyrealisasi,1,0,'C');
	$pdf->SetX(255);
	$pdf->Cell(15,5,$li_granttotyearlybatal,1,0,'C');
	$pdf->Output();
	break;

case 'su_lossratio' :

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

			return $tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
		}

	}
	class PDF extends FPDF
	{
		// Page header
		function Header()
		{

			$this->SetFont('Arial','B',12);
			$this->Cell(0,5,'SUMMARY KLAIM LOSS RATIO',0,0,'C');
			$this->ln();
			$this->SetFont('Arial','',9);
			$this->Cell(0,5,'Berdasarkan Tanggal Debit Note : '.bulan_convert($_REQUEST['tgl1']).' s.d '.bulan_convert($_REQUEST['tgl2']),0,0,'C');

			// Arial bold 15
			$this->SetFont('Arial','B',15);
			// Line break
			$this->Ln(10);
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
	$pdf = new PDF('L');
	$pdf->AddPage();
	$pdf->AliasNbPages();

	//HEADER
	$pdf->SetFont('Times','B',7);
	$pdf->SetX(10);
	$pdf->Cell(15,10,'TAHUN',1,0,'C');

	$query="SELECT
		tahun_uw,
		tahun_dol,
		COUNT(nama) AS jml_debitur,
		SUM(kredit_jumlah) AS plafond,
		SUM(totalpremi) AS premi,
		SUM(tuntutan_klaim) AS klaim
		FROM
		(
		SELECT
		YEAR(fu_ajk_peserta.kredit_tgl) AS tahun_uw,
		YEAR(fu_ajk_klaim.tgl_klaim) AS tahun_dol,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.totalpremi,
		fu_ajk_cn.tuntutan_klaim
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
		WHERE fu_ajk_cn.type_claim='Death'  AND fu_ajk_cn.`del` IS NULL
		and fu_ajk_dn.`tgl_createdn` between '".$_REQUEST['tgl1']."' and '".$_REQUEST['tgl2']."'
		) AA
		GROUP BY
		tahun_uw,
		tahun_dol";
	$row=0;
	$y=$pdf->GetY();

	for($a=2013;$a<=2016;$a++){
		$line=25;
		$col=65;
		$pdf->SetY($y);
		$pdf->SetX(($col*$row)+$line);
		$pdf->Cell(($col),5,$a,1,0,'C');
		$pdf->SetY($y+5);

		$pdf->SetX(($col*$row)+$line);
		$pdf->Cell((10),5,'JML',1,0,'C');
		$pdf->SetX(($col*$row)+$line+10);
		$pdf->Cell((20),5,'PLAFOND',1,0,'C');
		$pdf->SetX(($col*$row)+$line+30);
		$pdf->Cell((17),5,'PREMI',1,0,'C');
		$pdf->SetX(($col*$row)+$line+47);
		$pdf->Cell((18),5,'KLAIM',1,0,'C');

		$row++;
	}
	$thn='';
	$result=mysql_query($query);
	$pdf->SetFont('Times','',6);
	while($data=mysql_fetch_array($result)){

		if($thn!==$data['tahun_uw']){
			$pdf->ln();
			$thn=$data['tahun_uw'];
			$pdf->SetX(10);
			$pdf->Cell(15,5,$thn,1,0,'C');

			$row=0;
			for($a=2013;$a<=2016;$a++){

				$line=25;
				$col=65;
				$pdf->SetX(($col*$row)+$line);
				$pdf->Cell((10),5,'',1,0,'C');
				$pdf->SetX(($col*$row)+$line+10);
				$pdf->Cell((20),5,'',1,0,'C');
				$pdf->SetX(($col*$row)+$line+30);
				$pdf->Cell((17),5,'',1,0,'C');
				$pdf->SetX(($col*$row)+$line+47);
				$pdf->Cell((18),5,'',1,0,'C');

				$row++;
			}
		}

		$row=0;
		for($a=2013;$a<=2016;$a++){
			if(($data['tahun_dol']==$a)){
				$line=25;
				$col=65;
				$pdf->SetX(($col*$row)+$line);
				$pdf->Cell((10),5,number_format($data['jml_debitur']),0,0,'C');
				$pdf->SetX(($col*$row)+$line+10);
				$pdf->Cell((20),5,number_format($data['plafond'],2),0,0,'R');
				$pdf->SetX(($col*$row)+$line+30);
				$pdf->Cell((17),5,number_format($data['premi'],2),0,0,'R');
				$pdf->SetX(($col*$row)+$line+47);
				$pdf->Cell((18),5,number_format($data['klaim'],2),0,0,'R');
			}
			$row++;
		}
	}


	$pdf->Output();
	break;
default:;
}
?>
