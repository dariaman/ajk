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
		// Arial bold 15
		$this->SetFont('Arial','B',15);
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
$ldt_tanggal1 = $_REQUEST['tgl1'];
$ldt_tanggal2 = $_REQUEST['tgl2'];
$ls_status = $_REQUEST['status'];
$ls_paid = $_REQUEST['paid'];
$ls_idprod = $_REQUEST['subcat'];
$li_idreg = $_REQUEST['id_reg'];
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

$pdf->SetFont('Times','IB',9);
$pdf->SetY(32);
$pdf->SetX(20);
$pdf->Cell(0,15,$ls_name,0,0,'L');
$pdf->SetFont('Times','B',9);
$pdf->SetY(36);
$pdf->SetX(20);
$pdf->Cell(0,15,'SUMMARY PRODUKSI',0,0,'L');


$pdf->SetFont('Times','',9);
$pdf->SetY(44);
$pdf->SetX(20);
$pdf->Cell(0,15,'Periode per tanggal : '.$ldt_tanggal1.' s/d '.$ldt_tanggal2,0,0,'L');

$queryprod =  mysql_query("SELECT
	fu_ajk_polis.nmproduk
	FROM
	fu_ajk_dn
	LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
	LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_dn.id_cost
	LEFT JOIN fu_ajk_peserta ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	LEFT JOIN fu_ajk_regional ON fu_ajk_regional.name = fu_ajk_dn.id_regional
	WHERE fu_ajk_costumer.id = '".$li_id."'
	AND fu_ajk_dn.tgl_createdn BETWEEN '".$ldt_tanggal1."' AND '".$ldt_tanggal2."'
	AND fu_ajk_peserta.status_aktif like '".$ls_status."'
	AND fu_ajk_peserta.status_bayar like '".$ls_paid."'
	AND fu_ajk_peserta.id_polis like '".$ls_idprod."'
	AND fu_ajk_peserta.del is null
	GROUP BY  fu_ajk_polis.nmproduk");
$li_YProd = 52;
$ldb_granttot = 0;
while($rowprod = mysql_fetch_array($queryprod)){
	$ls_nameprod = $rowprod['nmproduk'];
	$pdf->SetFont('Times','',9);
	$pdf->SetY($li_YProd);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'Nama Produk '.$ls_nameprod,0,0,'L');

	$queryregional =  mysql_query("SELECT fu_ajk_costumer.name, fu_ajk_polis.nmproduk, fu_ajk_peserta.regional,
SUM(fu_ajk_peserta.totalpremi) as totprem FROM fu_ajk_peserta
LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_peserta.id_cost
WHERE fu_ajk_costumer.name = '".$ls_name."'
AND fu_ajk_polis.nmproduk = '".$ls_nameprod."'
AND fu_ajk_peserta.status_aktif like '".$ls_status."' AND fu_ajk_peserta.status_bayar like '".$ls_paid."'
AND fu_ajk_peserta.del is null GROUP BY fu_ajk_costumer.name,
fu_ajk_polis.nmproduk, fu_ajk_peserta.regional");

	//HEADER
	$pdf->SetFont('Times','B',9);
	$pdf->SetY($li_YProd+12);
	$pdf->SetX(20);
	$pdf->Cell(5,5,'No',0,0,'L');
	$pdf->SetY($li_YProd+12);
	$pdf->SetX(25);
	$pdf->Cell(100,5,'Nama Regional',0,0,'L');
	$pdf->SetY($li_YProd+12);
	$pdf->SetX(125);
	$pdf->Cell(40,5,'Total Premi',0,0,'L');

	$li_Yreg = $li_YProd + 14;
	$li_rowreg = 1;
	$ldb_sumtotperm = 0;
	while($rowreg = mysql_fetch_array($queryregional)){
		$ldb_totprem = 0;
		$ls_regional=$rowreg['regional'];
		$ldb_totprem = $rowreg['totprem'];
		$ldb_totpremformat = number_format($ldb_totprem,2,".",",");
		$ldb_sumtotperm = $ldb_sumtotperm +$ldb_totprem;
		$ldb_sumtotpermformat = number_format($ldb_sumtotperm,2,".",",");
		$ldb_granttot = $ldb_granttot + $ldb_sumtotperm;
		$ldb_granttotformat = number_format($ldb_granttot,2,".",",");
		$pdf->SetFont('Times','',9);
		$pdf->SetY($li_Yreg);
		$pdf->SetX(20);
		$pdf->Cell(0,15,$li_rowreg,0,0,'L');
		$pdf->SetY($li_Yreg);
		$pdf->SetX(25);
		$pdf->Cell(100,15,$ls_regional,0,0,'L');
		$pdf->SetY($li_Yreg);
		$pdf->SetX(125);
		$pdf->Cell(30,15,'Rp',0,0,'L');
		$pdf->SetY($li_Yreg);
		$pdf->SetX(125);
		$pdf->Cell(40,15,$ldb_totpremformat,0,0,'R');


		$li_Yreg = $li_Yreg + 4;
		$li_rowreg++;
	}
	$pdf->SetFont('Times','B',9);
	$pdf->SetY($li_Yreg+8);
	$pdf->SetX(20);
	$pdf->Cell(145,0,'',1,0,'L');
	$pdf->SetY($li_Yreg+4);
	$pdf->SetX(25);
	$pdf->Cell(30,15,'subtotal',0,0,'L');
	$pdf->SetY($li_Yreg+4);
	$pdf->SetX(125);
	$pdf->Cell(40,15,'Rp',0,0,'L');
	$pdf->SetY($li_Yreg+4);
	$pdf->SetX(125);
	$pdf->Cell(40,15,$ldb_sumtotpermformat,0,0,'R');
	$pdf->SetY($li_Yreg+16);
	$pdf->SetX(20);
	$pdf->Cell(145,0,'',1,0,'L');

	$li_YProd = $li_Yreg + 12;
}

$pdf->SetY($li_YProd+10);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YProd+11);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YProd+8);
$pdf->SetX(25);
$pdf->Cell(30,15,'GRAND TOTAL',0,0,'L');
$pdf->SetY($li_YProd+8);
$pdf->SetX(125);
$pdf->Cell(40,15,'Rp',0,0,'L');
$pdf->SetY($li_YProd+8);
$pdf->SetX(125);
$pdf->Cell(40,15,$ldb_granttotformat,0,0,'R');
$pdf->SetY($li_YProd+20);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');
$pdf->SetY($li_YProd+21);
$pdf->SetX(20);
$pdf->Cell(145,0,'',1,0,'L');


$pdf->Output();
?>