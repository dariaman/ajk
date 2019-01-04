<?php
error_reporting(0);
require('fpdf.php');
include "../includes/fu6106.php";

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		// Logo
		$this->Image('../image/adonai_64.gif',10,6,30);
		// Move to the right
		$this->Cell(80);
		// Line break
		$this->Ln(20);
	}


}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$li_idperserta = $_REQUEST['cat'];
$ls_paid = $_REQUEST['paid'];
$ls_idprod = $_REQUEST['subcat'];
$li_idreg = $_REQUEST['id_reg'];
$ls_status = $_REQUEST['status'];
$li_cab =  $_REQUEST['id_cab'];

if($li_idperserta==""){
	$li_idperserta = '%';
}else{
	$li_idperserta = $li_idperserta;
}
if($ls_idpolis==""){
	$ls_idpolis = '%';
}else{
	$ls_idpolis = $ls_idpolis;
}
if($ls_paid==''){
	$ls_paid = '%';
	$bayar = 'SEMUA PEMBAYARAN';
}else{
	if($ls_paid==1){
		$bayar = 'PAID';
	}elseif($ls_paid==0){
		$bayar = 'UNPAID';
	}
}
if($ls_idprod==''){
	$ls_idprod = '%';
}
if($ls_status==''){
	$ls_status = '%';
	$statuspeserta = 'SEMUA STATUS';
}else{
	$statuspeserta = $ls_status;
}
$ldt_tanggal1 = $_REQUEST['tgldn1'];
$ldt_tanggal2 = $_REQUEST['tgldn2'];

//tanggal transaksi
$ldt_tanggaltrans1 = $_REQUEST['tgltrans1'];
$ldt_tanggaltrans2 = $_REQUEST['tgltrans2'];
//tanggal transaksi
if ($_REQUEST['tgldn1']=="") {
	$tanggalDebitnote = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$ldt_tanggaltrans1.'" AND "'.$ldt_tanggaltrans2.'"';
}else{
	$tanggalDebitnote = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$ldt_tanggal1.'" AND "'.$ldt_tanggal2.'"';
}

//TAMBAHAN QUERY TGL TRANSAKSI (TGL UPLOAD EXCEL) 23-05-2016
if($_REQUEST['tgltrans1']==""){
}else{
	$tanggalTransaksi = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$ldt_tanggaltrans1.'" AND "'.$ldt_tanggaltrans2.'"';
}

//TAMBAHAN QUERY TGL TRANSAKSI (TGL UPLOAD EXCEL) 23-05-2016

$pdf->SetFont('Times','IB',9);
$pdf->SetY(32);
$pdf->SetX(20);
$pdf->Cell(0,15,'PT ADONAI PIALANG ASURANSI',0,0,'L');
$pdf->SetFont('Times','B',9);
$pdf->SetY(36);
$pdf->SetX(20);
$pdf->Cell(0,15,'SUMMARY PRODUKSI',0,0,'L');


$pdf->SetFont('Times','',9);
$pdf->SetY(44);
$pdf->SetX(20);
$pdf->Cell(0,15,'Periode per tanggal Debitnote: '._convertDate($ldt_tanggal1).' s/d '._convertDate($ldt_tanggal2),0,0,'L');
$querycust = mysql_query("SELECT DISTINCT fu_ajk_costumer.`name`, fu_ajk_costumer.id
						  FROM fu_ajk_costumer
						  LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
						  where fu_ajk_dn.id_cost like'".$li_idperserta."' ".$tanggalDebitnote."");
/*
$querycust = mysql_query("SELECT DISTINCT fu_ajk_costumer.`name`,
										  fu_ajk_costumer.id
						  FROM fu_ajk_costumer
						  LEFT JOIN fu_ajk_dn ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
						  LEFT JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
						  where fu_ajk_dn.id_cost like'".$li_idperserta."' '".$tanggalDebitnote."' '".$tanggalTransaksi."'");
*/
$queryreg =  mysql_query("SELECT * FROM fu_ajk_regional where id ='".$li_idreg."'");
$rowreg = mysql_fetch_array($queryreg);
$ls_regionalname = $rowreg['name'];

$num_reg= mysql_num_rows($queryreg);
IF ($num_reg > 0){
	$ls_regionalname = $ls_regionalname;
	$namaregional = $ls_regionalname;
}else{
	$ls_regionalname = '%';
	$namaregional = 'SEMUA REGIONAL';
}
$querycab =  mysql_query("SELECT * FROM fu_ajk_cabang where id like'".$li_cab."'");
$rowcab = mysql_fetch_array($querycab);
$ls_cabangname = $rowcab['name'];
$num_cab= mysql_num_rows($querycab);
IF ($num_cab > 0){
	$ls_cabangname = $ls_cabangname;
	$namacabang = $ls_cabangname;
}else{
	$ls_cabangname = '%';
	$namacabang = 'SEMUA CABANG';
}

$pdf->SetY(48);
$pdf->SetX(20);
$pdf->Cell(0,15,'Status Pembayaran : '.$bayar,0,0,'L');
$pdf->SetY(52);
$pdf->SetX(20);
$pdf->Cell(0,15,'Status Peserta : '.$statuspeserta,0,0,'L');
$pdf->SetY(56);
$pdf->SetX(20);
$pdf->Cell(0,15,'Regional : '.$namaregional,0,0,'L');
$pdf->SetY(60);
$pdf->SetX(20);
$pdf->Cell(0,15,'Cabang : '.$namacabang,0,0,'L');
$li_YKlien = 72;
$ldb_granttot = 0;

while($rowcust = mysql_fetch_array($querycust)){
	$ls_name = $rowcust['name'];
	$id_cost = $rowcust['id'];
	$pdf->SetFont('Times','',9);
	$pdf->SetY($li_YKlien+3);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'Nama Klien : '.$ls_name,0,0,'L');

	//HEADER
	$pdf->SetFont('Times','B',9);
	$pdf->SetY($li_YKlien+12);
	$pdf->SetX(20);
	$pdf->Cell(5,5,'No',0,0,'L');
	$pdf->SetY($li_YKlien+12);
	$pdf->SetX(25);
	$pdf->Cell(100,5,'Nama Produk',0,0,'L');
	$pdf->SetY($li_YKlien+12);
	$pdf->SetX(125);
	$pdf->Cell(40,5,'Total Premi',0,0,'L');



	if ($ls_status=="Produksi") {
		$lima = 'AND fu_ajk_peserta.status_aktif IN ("Inforce", "Lapse") AND (fu_ajk_peserta.status_peserta NOT IN ("Batal") OR fu_ajk_peserta.status_peserta IS NULL )';
	}else{
		$lima = 'AND fu_ajk_peserta.status_aktif like "'.$ls_status.'"';
	}


$queryprod = mysql_query('SELECT
SUM(IF(fu_ajk_peserta.status_bayar="0" AND fu_ajk_peserta.status_peserta ="Batal", fu_ajk_peserta.totalpremi, fu_ajk_peserta.totalpremi)) AS totprem,
fu_ajk_polis.nmproduk
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !=""
AND fu_ajk_dn.del IS NULL
AND fu_ajk_peserta.del IS NULL
AND fu_ajk_peserta.id_cost like "'.$id_cost.'"
'.$tanggalDebitnote.'
AND ifnull(fu_ajk_peserta.status_bayar,0) like "'.$ls_paid.'"
'.$lima.'
'.$tanggalTransaksi.'
AND fu_ajk_peserta.regional like "'.$ls_regionalname.'"
AND fu_ajk_peserta.cabang like "'.$ls_cabangname.'"
AND fu_ajk_peserta.id_polis like "'.$ls_idprod.'"
GROUP BY fu_ajk_peserta.id_cost,  fu_ajk_peserta.id_polis ORDER BY fu_ajk_polis.urut ASC');
	$li_YProd = $li_YKlien + 16;
	$li_rowprod = 1;
	$ldb_totprem = 0;
	while($rowprod = mysql_fetch_array($queryprod)){
		$ls_namaprod =$rowprod['nmproduk'];
		$ldb_totprem =$rowprod['totprem'];

		$ldb_totpremformat = duitkoma($ldb_totprem);
		$ldb_sumtotprem = $ldb_sumtotprem + $ldb_totprem;
		$ldb_sumtotpremformat = duitkoma($ldb_sumtotprem);

	//DETAIL
		$pdf->SetFont('Times','',9);
		$pdf->SetY($li_YProd);
		$pdf->SetX(20);
		$pdf->Cell(5,5,$li_rowprod,0,0,'L');
		$pdf->SetY($li_YProd);
		$pdf->SetX(25);
		$pdf->Cell(100,5,$ls_namaprod,0,0,'L');
		$pdf->SetY($li_YProd);
		$pdf->SetX(125);
		$pdf->Cell(40,5,'Rp',0,0,'L');
		$pdf->SetY($li_YProd);
		$pdf->SetX(125);
		$pdf->Cell(40,5,$ldb_totpremformat,0,0,'R');

		$li_YProd = $li_YProd + 4;
		$li_rowprod++;
	}
	$ldb_granttot = $ldb_granttot + $ldb_sumtotprem;
	$ldb_sumtotprem=0;
	$ldb_granttotformat = duitkoma($ldb_granttot);
	$pdf->SetFont('Times','B',9);
	$pdf->SetY($li_YProd+2);
	$pdf->SetX(20);
	$pdf->Cell(145,0,'',1,0,'L');
	$pdf->SetY($li_YProd-2);
	$pdf->SetX(25);
	$pdf->Cell(30,15,'subtotal',0,0,'L');
	$pdf->SetY($li_YProd-2);
	$pdf->SetX(125);
	$pdf->Cell(40,15,'Rp',0,0,'L');
	$pdf->SetY($li_YProd-2);
	$pdf->SetX(125);
	$pdf->Cell(40,15,$ldb_sumtotpremformat,0,0,'R');
	$pdf->SetY($li_YProd+10);
	$pdf->SetX(20);
	$pdf->Cell(145,0,'',1,0,'L');

	$li_YKlien = $li_YProd + 4;


}

$pdf->SetFont('Times','B',9);
$pdf->SetY($li_YKlien+10);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YKlien+11);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YKlien+8);
$pdf->SetX(25);
$pdf->Cell(30,15,'GRAND TOTAL',0,0,'L');
$pdf->SetY($li_YKlien+8);
$pdf->SetX(125);
$pdf->Cell(40,15,'Rp',0,0,'L');
$pdf->SetY($li_YKlien+8);
$pdf->SetX(125);
$pdf->Cell(40,15,$ldb_granttotformat,0,0,'R');
$pdf->SetY($li_YKlien+20);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YKlien+21);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');


$pdf->Output();
?>