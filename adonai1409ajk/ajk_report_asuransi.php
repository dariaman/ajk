<?php
error_reporting(0);
require('fpdf.php');
include "../includes/fu6106.php";

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		if($_REQUEST['er']=='sum3'){
		
		}else{
			// Logo
			$this->Image('../image/adonai_64.gif',10,6,30);
			// Move to the right
			$this->Cell(80);
			// Line break
			$this->Ln(20);
		}
	}
	function Footer(){

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



if ($_REQUEST['id_cost'])	{
	$cost_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_costumer where id="'.$_REQUEST['id_cost'].'"'));
	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
	$cost=$cost_['name'];
}
	
$as='Semua Asuransi';
if ($_REQUEST['id_as'])	{
	$as_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_asuransi where id="'.$_REQUEST['id_as'].'"'));
	$satu = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_as'].'"';
	$as=$as_['name'];
}

$polis='Semua Produk';
if ($_REQUEST['id_polis'])		{
	$polis_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_polis where id="'.$_REQUEST['id_polis'].'"'));
	$dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';
	$polis=$polis_['nmproduk'];
}

$tgl_klaim=$_REQUEST['tglklaim1'].' s/d '.$_REQUEST['tglklaim2'];
if ($_REQUEST['tglklaim1'])		{
		
	$tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tglklaim1'].'" AND "'.$_REQUEST['tglklaim2'].'"';
}

$status='Semua Status';
if ($_REQUEST['status'])		{
	$status_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_klaim_status where id="'.$_REQUEST['status'].'"'));
	$lima = 'AND fu_ajk_klaim.id_klaim_status = "'.$_REQUEST['status'].'"';
	$status=$status_['status_klaim'];
}

$regional ='Semua Regional';
if ($_REQUEST['id_reg']!=="") {
	$regional=$_REQUEST['id_reg'];
	$enam = ' AND fu_ajk_peserta.regional="'.$_REQUEST['id_reg'].'"';
}
$cabang='Semua Cabang';
if ($_REQUEST['id_cab']!=="") {
	$tujuh= ' AND fu_ajk_peserta.cabang="'.$_REQUEST['id_cab'].'"';
	$cabang=$_REQUEST['id_cab'];
}


$pdf = new PDF();
$pdf->AliasNbPages();
if($_REQUEST['er']=='sum1'){

$pdf->AddPage();

$pdf->SetFont('Times','IB',9);
$pdf->SetY(32);
$pdf->SetX(20);
$pdf->Cell(0,15,'PT ADONAI PIALANG ASURANSI',0,0,'L');
$pdf->SetFont('Times','B',9);
$pdf->SetY(36);
$pdf->SetX(20);
$pdf->Cell(0,15,'SUMMARY KLAIM',0,0,'L');

$pdf->SetFont('Times','',9);
$pdf->SetY(52);
$pdf->SetX(20);
$pdf->Cell(0,5,'Periode per tanggal klaim : '.$tgl_klaim ,0,0,'L');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell(0,5,'Status Klaim : '.strtoupper($status),0,0,'L');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell(0,5,'Asuransi : '.strtoupper($as),0,0,'L');
$pdf->Ln();

$pdf->SetX(20);
$pdf->Cell(0,5,'Regional : '.strtoupper($regional),0,0,'L');
$pdf->Ln();

$pdf->SetX(20);
$pdf->Cell(0,5,'cabang : '.strtoupper($cabang),0,0,'L');
$pdf->Ln(10);


$pdf->SetX(20);
$pdf->Cell(0,5,'Nama Klien : '.$cost,0,0,'L');
$pdf->Ln(10);

$pdf->SetFont('Times','B',9);
$pdf->SetX(20);
$pdf->Cell(10,5,'No.',0,0,'C');
$pdf->SetX(30);
$pdf->Cell(60,5,'Product',0,0,'L');
$pdf->SetX(90);
$pdf->Cell(20,5,'Debitur',0,0,'C');
$pdf->SetX(110);
$pdf->Cell(30,5,'Plafond',0,0,'R');
$pdf->SetX(140);
$pdf->Cell(30,5,'Klaim',0,0,'R');
$pdf->SetX(170);
$pdf->Cell(30,5,'Asuransi Bayar',0,0,'R');
$pdf->Ln();


$pdf->SetFont('Times','',9);

$met = mysql_query('SELECT
				fu_ajk_polis.nmproduk,
				count(fu_ajk_peserta.id_peserta) as jml_debitur,
				sum(fu_ajk_peserta.kredit_jumlah) as plafond,
				sum(fu_ajk_cn.total_claim) as klaim,
				sum(fu_ajk_cn.total_bayar_asuransi) as bayar_asuransi
				FROM fu_ajk_peserta
				INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
				INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
				INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL
				
				group by 
		
				fu_ajk_polis.nmproduk');
$no=1;
$jml_data=mysql_num_rows($met);
$jml_debitur=0;
$jml_plafond=0;
$jml_klaim=0;
$as_klaim=0;

while($data_=mysql_fetch_array($met)){

	$jml_debitur+=$data_['jml_debitur'];
	$jml_plafond+=$data_['plafond'];
	$jml_klaim+=$data_['klaim'];
	$as_klaim+=$data_['bayar_asuransi'];
	
	$pdf->SetX(20);
	$pdf->Cell(10,5,$no,0,0,'C');
	$pdf->SetX(30);
	$pdf->Cell(60,5,$data_['nmproduk'],0,0,'L');
	$pdf->SetX(90);
	$pdf->Cell(20,5,$data_['jml_debitur'],0,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(30,5,'Rp. '.duit($data_['plafond']),0,0,'R');
	$pdf->SetX(140);
	$pdf->Cell(30,5,'Rp. '.duit($data_['klaim']),0,0,'R');
	$pdf->SetX(170);
	$pdf->Cell(30,5,'Rp. '.duit($data_['bayar_asuransi']),0,0,'R');
	$pdf->Ln();
	
	if($no==$jml_data){
		$pdf->SetX(20);
		$pdf->Cell(180,0,'',1,1,'C');
		$pdf->Ln(1);
		$pdf->SetFont('Times','B',9);
		$pdf->SetX(20);
		$pdf->Cell(10,5,'',0,0,'C');
		$pdf->SetX(30);
		$pdf->Cell(60,5,'TOTAL',0,0,'C');
		$pdf->SetX(90);
		$pdf->Cell(20,5,$jml_debitur,0,0,'C');
		$pdf->SetX(110);
		$pdf->Cell(30,5,'Rp. '.duit($jml_plafond),0,0,'R');
		$pdf->SetX(140);
		$pdf->Cell(30,5,'Rp. '.duit($jml_klaim),0,0,'R');
		$pdf->SetX(170);
		$pdf->Cell(30,5,'Rp. '.duit($as_klaim),0,0,'R');
		$pdf->Ln();
	}


	$no++;
}

}elseif($_REQUEST['er']=='sum2'){

	$pdf->AddPage();
	
	$pdf->SetFont('Times','IB',9);
	$pdf->SetY(32);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'PT ADONAI PIALANG ASURANSI',0,0,'L');
	$pdf->SetFont('Times','B',9);
	$pdf->SetY(36);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'SUMMARY KLAIM',0,0,'L');
	
	$pdf->SetFont('Times','',9);
	$pdf->SetY(52);
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Periode per tanggal klaim : '.$tgl_klaim ,0,0,'L');
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Status Klaim : '.strtoupper($status),0,0,'L');
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Asuransi : '.strtoupper($as),0,0,'L');
	$pdf->Ln();
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Regional : '.strtoupper($regional),0,0,'L');
	$pdf->Ln();
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'cabang : '.strtoupper($cabang),0,0,'L');
	$pdf->Ln(15);
	
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Nama Klien : '.$cost,0,0,'L');
	$pdf->Ln(10);
	
	$pdf->SetFont('Times','B',9);
	$pdf->SetX(20);
	$pdf->Cell(10,5,'No.',0,0,'C');
	$pdf->SetX(30);
	$pdf->Cell(60,5,'Product',0,0,'L');
	$pdf->SetX(90);
	$pdf->Cell(20,5,'Debitur',0,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(30,5,'Plafond',0,0,'R');
	$pdf->SetX(140);
	$pdf->Cell(30,5,'Klaim',0,0,'R');
	$pdf->SetX(170);
	$pdf->Cell(30,5,'Asuransi Bayar',0,0,'R');
	$pdf->Ln();
	
	
	$pdf->SetFont('Times','',9);
	
	$met1 = mysql_query('SELECT
				fu_ajk_polis.nmproduk,
				fu_ajk_asuransi.name as nama_asuransi,
				count(fu_ajk_peserta.id_peserta) as jml_debitur,
				sum(fu_ajk_peserta.kredit_jumlah) as plafond,
				sum(fu_ajk_cn.total_claim) as klaim,
				sum(fu_ajk_cn.total_bayar_asuransi) as bayar_asuransi
				FROM fu_ajk_peserta
				INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
				INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
				INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL
	
				group by
				fu_ajk_asuransi.name,
				fu_ajk_polis.nmproduk
	
		');
	$no=1;
	$nm_as='';
	$jmlku=mysql_num_rows($met1);

	$jml_debitur1=0;
	$jml_plafond1=0;
	$jml_klaim1=0;
	$as_klaim1=0;
	while($data1_=mysql_fetch_array($met1)){
		
		if($nm_as!==$data1_['nama_asuransi']){
				$pdf->SetFont('Times','B',9);
			if($nm_as!==''){

				$pdf->SetX(20);
				$pdf->Cell(180,0,'',1,1,'C');
				$pdf->Ln(1);
				$pdf->SetX(20);
				$pdf->Cell(10,5,'',0,0,'C');
				$pdf->SetX(30);
				$pdf->Cell(70,5,'SUBTOTAL',0,0,'L');
				$pdf->SetX(90);
				$pdf->Cell(20,5,$jml_debitur,0,0,'C');
				$pdf->SetX(110);
				$pdf->Cell(30,5,'Rp. '.duit($jml_plafond),0,0,'R');
				$pdf->SetX(140);
				$pdf->Cell(30,5,'Rp. '.duit($jml_klaim),0,0,'R');
				$pdf->SetX(170);
				$pdf->Cell(30,5,'Rp. '.duit($as_klaim),0,0,'R');
				$pdf->Ln();
			}
			$pdf->Ln();
			$nm_as=$data1_['nama_asuransi'];
			$pdf->SetX(20);
			$pdf->Cell(110,5,$nm_as,0,0,'L');
			$pdf->Ln();

			$jml_debitur=0;
			$jml_plafond=0;
			$jml_klaim=0;
			$as_klaim=0;
			
		}

		$pdf->SetFont('Times','',9);
		$pdf->SetX(20);
		$pdf->Cell(10,5,$no,0,0,'C');
		$pdf->SetX(30);
		$pdf->Cell(70,5,$data1_['nmproduk'],0,0,'L');
		$pdf->SetX(90);
		$pdf->Cell(20,5,$data1_['jml_debitur'],0,0,'C');
		$pdf->SetX(110);
		$pdf->Cell(30,5,'Rp. '.duit($data1_['plafond']),0,0,'R');
		$pdf->SetX(140);
		$pdf->Cell(30,5,'Rp. '.duit($data1_['klaim']),0,0,'R');
		$pdf->SetX(170);
		$pdf->Cell(30,5,'Rp. '.duit($data1_['bayar_asuransi']),0,0,'R');
		$pdf->Ln();

		$jml_debitur+=$data1_['jml_debitur'];
		$jml_plafond+=$data1_['plafond'];
		$jml_klaim+=$data1_['klaim'];
		$as_klaim+=$data1_['bayar_asuransi'];

		$jml_debitur1+=$data1_['jml_debitur'];
		$jml_plafond1+=$data1_['plafond'];
		$jml_klaim1+=$data1_['klaim'];
		$as_klaim1+=$data1_['bayar_asuransi'];

		if($no==$jmlku){

			$pdf->SetX(20);
			$pdf->Cell(180,0,'',1,1,'C');
			$pdf->Ln(1);
			$pdf->SetFont('Times','B',9);
			$pdf->SetX(20);
			$pdf->Cell(10,5,'',0,0,'C');
			$pdf->SetX(30);
			$pdf->Cell(70,5,'SUBTOTAL',0,0,'L');
			$pdf->SetX(90);
			$pdf->Cell(20,5,$jml_debitur,0,0,'C');
			$pdf->SetX(110);
			$pdf->Cell(30,5,'Rp. '.duit($jml_plafond),0,0,'R');
			$pdf->SetX(140);
			$pdf->Cell(30,5,'Rp. '.duit($jml_klaim),0,0,'R');
			$pdf->SetX(170);
			$pdf->Cell(30,5,'Rp. '.duit($as_klaim),0,0,'R');
			$pdf->Ln(10);

			$pdf->SetX(20);
			$pdf->Cell(180,0,'',1,1,'C');
			$pdf->Ln(1);
			$pdf->SetFont('Times','B',9);
			$pdf->SetX(20);
			$pdf->Cell(10,5,'',0,0,'C');
			$pdf->SetX(30);
			$pdf->Cell(70,5,'TOTAL',0,0,'L');
			$pdf->SetX(90);
			$pdf->Cell(20,5,$jml_debitur1,0,0,'C');
			$pdf->SetX(110);
			$pdf->Cell(30,5,'Rp. '.duit($jml_plafond1),0,0,'R');
			$pdf->SetX(140);
			$pdf->Cell(30,5,'Rp. '.duit($jml_klaim1),0,0,'R');
			$pdf->SetX(170);
			$pdf->Cell(30,5,'Rp. '.duit($as_klaim1),0,0,'R');
			$pdf->Ln(10);
				
		}

		$no++;
	}
}elseif($_REQUEST['er']=='sum3'){


	$pdf->AddPage("L");
	
	$pdf->SetFont('Times','IB',9);
	$pdf->SetY(10);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'PT ADONAI PIALANG ASURANSI',0,0,'L');
	$pdf->SetFont('Times','B',9);
	$pdf->SetY(15);
	$pdf->SetX(20);
	$pdf->Cell(0,15,'SUMMARY KLAIM',0,0,'L');
	
	$pdf->SetFont('Times','',9);
	$pdf->SetY(30);
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Periode per tanggal klaim : '.$tgl_klaim ,0,0,'L');
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Status Klaim : '.strtoupper($status),0,0,'L');
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Asuransi : '.strtoupper($as),0,0,'L');
	$pdf->Ln();
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Regional : '.strtoupper($regional),0,0,'L');
	$pdf->Ln();
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Cabang : '.strtoupper($cabang),0,0,'L');
	$pdf->Ln(15);
	
	
	$pdf->SetX(20);
	$pdf->Cell(0,5,'Nama Klien : '.$cost,0,0,'L');
	$pdf->Ln(10);
	
	
	
	$pdf->SetFont('Times','',9);
	
$met1 = mysql_query('SELECT
     `fu_ajk_costumer`.`name` AS nama_cost
    , `fu_ajk_regional`.`name` AS regional
    , fu_ajk_polis.`nmproduk`
    , aa.jml_peserta
    , aa.kredit
    , aa.premi
    , bb.jml_klaim
    , bb.tuntutan_klaim
    , bb.klaim_dibayar
FROM
    `fu_ajk_regional`
    INNER JOIN `fu_ajk_costumer` 
        ON (`fu_ajk_regional`.`id_cost` = `fu_ajk_costumer`.`id`)
     INNER JOIN fu_ajk_polis 
	ON fu_ajk_costumer.id = fu_ajk_polis.`id_cost`
     LEFT JOIN (
        SELECT
        fu_ajk_peserta.id_cost,
	fu_ajk_peserta.regional,
	fu_ajk_peserta.`id_polis`,
	COUNT(fu_ajk_peserta.id_peserta) AS jml_peserta,
	SUM(fu_ajk_peserta.kredit_jumlah) AS kredit,
	SUM(fu_ajk_peserta.totalpremi) AS premi
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol 
	WHERE fu_ajk_peserta.`del` IS NULL  
	AND fu_ajk_peserta.id_dn !="" AND  fu_ajk_dn.`del` IS NULL '.$satu.' '.$dua.' '.$enam.' '.$tujuh.'
	GROUP BY 
        fu_ajk_peserta.id_cost,
	fu_ajk_peserta.regional,
	fu_ajk_peserta.`id_polis`
	) aa ON  aa.id_cost=`fu_ajk_costumer`.`id` AND aa.regional=`fu_ajk_regional`.`name` AND aa.`id_polis`=fu_ajk_polis.id
	LEFT JOIN (
        SELECT
        fu_ajk_peserta.id_cost,
	fu_ajk_peserta.regional,
	fu_ajk_peserta.`id_polis`,
	COUNT(fu_ajk_peserta.id_peserta) AS jml_klaim,
	SUM(fu_ajk_klaim.`tuntutan_klaim`) AS tuntutan_klaim,
	SUM(IF(fu_ajk_cn.tgl_byr_claim IS NOT NULL,fu_ajk_cn.total_claim,0)) AS klaim_dibayar
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol 
	INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id AND fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
	INNER JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
	WHERE fu_ajk_peserta.`del` IS NULL  AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.type_claim="Death"
	AND fu_ajk_peserta.id_dn !="" AND fu_ajk_dn.`del` IS NULL '.$satu.' '.$dua.' '.$enam.' '.$tujuh.'
	GROUP BY 
        fu_ajk_peserta.id_cost,
	fu_ajk_peserta.regional,
	fu_ajk_peserta.`id_polis`
	) bb ON  bb.id_cost=`fu_ajk_costumer`.`id` AND bb.regional=`fu_ajk_regional`.`name` AND bb.`id_polis`=fu_ajk_polis.id
		where `fu_ajk_regional`.del is null
	order by
	`fu_ajk_costumer`.`name`
    , fu_ajk_polis.`nmproduk`
    , `fu_ajk_regional`.`name`
				
	');
	
	$no=1;
	$nm_prod='';
	$jmlku=mysql_num_rows($met1);
	
	$jml_debitur1=0;
	$jml_plafond1=0;
	$jml_klaim1=0;
	$as_klaim1=0;
	while($data1_=mysql_fetch_array($met1)){
		
		if($nm_prod!==$data1_['nmproduk']){
			
			if($nm_prod!==''){
				$pdf->SetFont('Arial','B',7);
				$pdf->SetX(30);
				$pdf->Cell(70,5,'SUBTOTAL',0,0,'L');
				$pdf->SetX(100);
				$pdf->Cell(10,5,number_format($sjml_peserta),1,0,'C');
				$pdf->SetX(110);
				$pdf->Cell(25,5,number_format($skredit,2),1,0,'R');
				$pdf->SetX(135);
				$pdf->Cell(25,5,number_format($spremi,2),1,0,'R');
				$pdf->SetX(160);
				
				$pdf->Cell(10,5,number_format($sjml_klaim),1,0,'C');
				$pdf->SetX(170);
				$pdf->Cell(25,5,number_format($snilai_klaim,2),1,0,'R');
				$pdf->SetX(195);
				$pdf->Cell(25,5,number_format($sklaim_dibayar,2),1,0,'R');
				$pdf->SetX(220);
				
				
				
				$sperc_peserta=$sjml_klaim/$sjml_peserta*100;
				$sperc_klaim=$snilai_klaim/$spremi*100;
				$sperc_klaim_dibayar=$sklaim_dibayar/$spremi*100;
				
				$pdf->Cell(10,5,number_format($sperc_peserta,2).'%',1,0,'C');
				$pdf->SetX(230);
				$pdf->Cell(25,5,number_format($sperc_klaim,2).'%',1,0,'C');
				$pdf->SetX(255);
				$pdf->Cell(25,5,number_format($sperc_klaim_dibayar,2).'%',1,0,'C');
			}
			
			$no=1;
			$nm_prod=$data1_['nmproduk'];
			$pdf->SetFont('Arial','B',7);

			$pdf->Ln(10);
			$pdf->SetX(20);
			$pdf->Cell(80,5,'Nama Produk : '.$nm_prod,0,0,'L');
			$pdf->SetX(100);
			$pdf->Cell(60,5,'Produksi',1,0,'C');
			$pdf->SetX(160);
			$pdf->Cell(60,5,'Pengajuan Klaim',1,0,'C');
			$pdf->SetX(220);
			$pdf->Cell(60,5,'Loss Ratio',1,0,'C');
			$pdf->Ln();
			

			$nm_prod;
			
			$pdf->SetX(20);
			$pdf->Cell(10,5,'No.',1,0,'C');
			$pdf->SetX(30);
			$pdf->Cell(70,5,'Regional',1,0,'L');
			$pdf->SetX(100);
			
			$pdf->Cell(10,5,'Peserta',1,0,'C');
			$pdf->SetX(110);
			$pdf->Cell(25,5,'Kredit',1,0,'C');
			$pdf->SetX(135);
			$pdf->Cell(25,5,'Premi',1,0,'C');
			$pdf->SetX(160);
			
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(170);
			$pdf->Cell(25,5,'Nilai Klaim',1,0,'C');
			$pdf->SetX(195);
			$pdf->Cell(25,5,'Nilai Klaim Dibayar',1,0,'C');
			$pdf->SetX(220);
				
			$pdf->Cell(10,5,'Qty',1,0,'C');
			$pdf->SetX(230);
			$pdf->Cell(25,5,'Nilai Klaim',1,0,'C');
			$pdf->SetX(255);
			$pdf->Cell(25,5,'Nilai Klaim Dibayar',1,0,'C');
				
			$pdf->Ln();


			$sjml_peserta=0;
			$skredit=0;
			$spremi=0;
			$sjml_klaim=0;
			$snilai_klaim=0;
			$sklaim_dibayar=0;
			
			
			
		}
	
		$pdf->SetFont('Arial','',7);
		

		$pdf->SetX(20);
		$pdf->Cell(10,5,$no,1,0,'C');
		$pdf->SetX(30);
		$pdf->Cell(70,5,$data1_['regional'],1,0,'L');
		$pdf->SetX(100);

		$pdf->Cell(10,5,number_format($data1_['jml_peserta']),1,0,'C');
		$pdf->SetX(110);
		$pdf->Cell(25,5,number_format($data1_['kredit'],2),1,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(25,5,number_format($data1_['premi'],2),1,0,'R');
		$pdf->SetX(160);
			
		$pdf->Cell(10,5,number_format($data1_['jml_klaim']),1,0,'C');
		$pdf->SetX(170);
		$pdf->Cell(25,5,number_format($data1_['nilai_klaim'],2),1,0,'R');
		$pdf->SetX(195);
		$pdf->Cell(25,5,number_format($data1_['klaim_dibayar'],2),1,0,'R');
		$pdf->SetX(220);
		
		
		
		$perc_peserta=$data1_['jml_klaim']/$data1_['jml_peserta']*100;
		$perc_klaim=$data1_['nilai_klaim']/$data1_['premi']*100;
		$perc_klaim_dibayar=$data1_['klaim_dibayar']/$data1_['premi']*100;
		
		$pdf->Cell(10,5,number_format($perc_peserta,2).'%',1,0,'C');
		$pdf->SetX(230);
		$pdf->Cell(25,5,number_format($perc_klaim,2).'%',1,0,'C');
		$pdf->SetX(255);
		$pdf->Cell(25,5,number_format($perc_klaim_dibayar,2).'%',1,0,'C');
		

		$sjml_peserta+=$data1_['jml_peserta'];
		$skredit+=$data1_['kredit'];
		$spremi+=$data1_['premi'];
		$sjml_klaim+=$data1_['jml_klaim'];
		$snilai_klaim+=$data1_['nilai_klaim'];
		$sklaim_dibayar+=$data1_['klaim_dibayar'];
		
		$jml_peserta+=$data1_['jml_peserta'];
		$kredit+=$data1_['kredit'];
		$premi+=$data1_['premi'];
		$jml_klaim+=$data1_['jml_klaim'];
		$nilai_klaim+=$data1_['nilai_klaim'];
		$klaim_dibayar+=$data1_['klaim_dibayar'];
		$pdf->Ln();
		$no++;
	}

	$pdf->SetFont('Arial','B',7);
	$pdf->SetX(30);
	$pdf->Cell(70,5,'SUBTOTAL',0,0,'L');
	$pdf->SetX(100);
	$pdf->Cell(10,5,number_format($sjml_peserta),1,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(25,5,number_format($skredit,2),1,0,'R');
	$pdf->SetX(135);
	$pdf->Cell(25,5,number_format($spremi,2),1,0,'R');
	$pdf->SetX(160);
	
	$pdf->Cell(10,5,number_format($sjml_klaim),1,0,'C');
	$pdf->SetX(170);
	$pdf->Cell(25,5,number_format($snilai_klaim,2),1,0,'R');
	$pdf->SetX(195);
	$pdf->Cell(25,5,number_format($sklaim_dibayar,2),1,0,'R');
	$pdf->SetX(220);
	
	
	
	$sperc_peserta=$sjml_klaim/$sjml_peserta*100;
	$sperc_klaim=$snilai_klaim/$spremi*100;
	$sperc_klaim_dibayar=$sklaim_dibayar/$spremi*100;
	
	$pdf->Cell(10,5,number_format($sperc_peserta,2).'%',1,0,'C');
	$pdf->SetX(230);
	$pdf->Cell(25,5,number_format($sperc_klaim,2).'%',1,0,'C');
	$pdf->SetX(255);
	$pdf->Cell(25,5,number_format($sperc_klaim_dibayar,2).'%',1,0,'C');
	
	
	
	$pdf->SetFont('Arial','B',7);
	$pdf->ln(10);
	$pdf->SetX(30);
	$pdf->Cell(70,5,'TOTAL',0,0,'L');
	$pdf->SetX(100);
	$pdf->Cell(10,5,number_format($jml_peserta),1,0,'C');
	$pdf->SetX(110);
	$pdf->Cell(25,5,number_format($kredit,2),1,0,'R');
	$pdf->SetX(135);
	$pdf->Cell(25,5,number_format($premi,2),1,0,'R');
	$pdf->SetX(160);
		
	$pdf->Cell(10,5,number_format($jml_klaim),1,0,'C');
	$pdf->SetX(170);
	$pdf->Cell(25,5,number_format($nilai_klaim,2),1,0,'R');
	$pdf->SetX(195);
	$pdf->Cell(25,5,number_format($klaim_dibayar,2),1,0,'R');
	$pdf->SetX(220);
	
	
	
	$tperc_peserta=$jml_klaim/$jml_peserta*100;
	$tperc_klaim=$nilai_klaim/$premi*100;
	$tperc_klaim_dibayar=$klaim_dibayar/$premi*100;
	
	$pdf->Cell(10,5,number_format($tperc_peserta,2).'%',1,0,'C');
	$pdf->SetX(230);
	$pdf->Cell(25,5,number_format($tperc_klaim,2).'%',1,0,'C');
	$pdf->SetX(255);
	$pdf->Cell(25,5,number_format($tperc_klaim_dibayar,2).'%',1,0,'C');
	
	
}

$pdf->Output();
?>