<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['gr']) {
	case "gpterjaminbank":
if ($_REQUEST['cat'])	{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['cat']=="") {	$status_client = "SEMUA CLIENT";	}
else{
	$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
	$status_client = $searchbank['name'];
}

if ($_REQUEST['subccat'])		{	$dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subccat'].'"';	}
if ($_REQUEST['tgldn1'])		{	$tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
	$tgldn_ = explode("-", $_REQUEST['tgldn2']);
	$labletgl = $tgldn_[0] .' -'.$tgldn_[1];
	if ($tgldn_[1]==1) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,';
	}elseif ($tgldn_[1]==2) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,';
	}elseif ($tgldn_[1]==3) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,';
	}elseif ($tgldn_[1]==4) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,';
	}elseif ($tgldn_[1]==5) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,';
	}elseif ($tgldn_[1]==6) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,';
	}elseif ($tgldn_[1]==7) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,';
	}elseif ($tgldn_[1]==8) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN fu_ajk_peserta.nama END) AS Agustus,';
	}elseif ($tgldn_[1]==9) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN fu_ajk_peserta.nama END) AS Agustus,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN fu_ajk_peserta.nama END) AS September,';
	}elseif ($tgldn_[1]==10) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN fu_ajk_peserta.nama END) AS Agustus,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN fu_ajk_peserta.nama END) AS September,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN fu_ajk_peserta.nama END) AS Oktober,';
	}elseif ($tgldn_[1]==11) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN fu_ajk_peserta.nama END) AS Agustus,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN fu_ajk_peserta.nama END) AS September,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN fu_ajk_peserta.nama END) AS Oktober,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-11" AND "'.$tgldn_[0].'-11" THEN fu_ajk_peserta.nama END) AS November,';
	}elseif ($tgldn_[1]==12) {
		$countterjamin = 'COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN fu_ajk_peserta.nama END) AS January,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN fu_ajk_peserta.nama END) AS February,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN fu_ajk_peserta.nama END) AS March,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN fu_ajk_peserta.nama END) AS April,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN fu_ajk_peserta.nama END) AS May,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN fu_ajk_peserta.nama END) AS June,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN fu_ajk_peserta.nama END) AS July,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN fu_ajk_peserta.nama END) AS Agustus,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN fu_ajk_peserta.nama END) AS September,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN fu_ajk_peserta.nama END) AS Oktober,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-11" AND "'.$tgldn_[0].'-11" THEN fu_ajk_peserta.nama END) AS November,
						  COUNT(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-12" AND "'.$tgldn_[0].'-12" THEN fu_ajk_peserta.nama END) AS Desember,';

	}else{

	}
	$tanggaldebitnote ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Debit Note</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
}

if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
	$tanggaltransaksi ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Transaksi</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
}

//if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
//$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
//}
if ($_REQUEST['paiddata'])		{	$empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_reg']=="") {	$status_regional = "SEMUA REGIONAL";	}	else{	$status_regional = $met_reg['name'];	}

if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}
if ($_REQUEST['id_cab']=="") {	$status_cabang = "SEMUA CABANG";	}	else{	$status_cabang = $met_cab['name'];	}

if ($_REQUEST['statpeserta'])	{
	$status_ = explode("-", $_REQUEST['statpeserta']);
	if (!$status_[1]) {	$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';	}
	else{	$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
		$delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
	}
}
if ($_REQUEST['grupprod'])	{	$sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';	}
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}else{	$searchpaid = "UNPAID";	}
if ($_REQUEST['id_polis']=="") {	$status_produknya = "SEMUA PRODUK";	}	else{	$status_produknya = $searchproduk['nmproduk'];	}
$graph1 = $database->doQuery('SELECT fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.typeproduk,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
'.$countterjamin.'
COUNT(fu_ajk_peserta.nama) AS jData,
SUM(fu_ajk_peserta.kredit_jumlah) AS jPlafond
FROM fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
								GROUP BY fu_ajk_polis.typeproduk, fu_ajk_polis.id
								ORDER BY jData DESC');
while ($graph1_ = mysql_fetch_array($graph1)) {
	$totalterjaminData1 += $graph1_['January'];
	$totalterjaminData2 += $graph1_['February'];
	$totalterjaminData3 += $graph1_['March'];
	$totalterjaminData4 += $graph1_['April'];
	$totalterjaminData5 += $graph1_['May'];
	$totalterjaminData6 += $graph1_['June'];
	$totalterjaminData7 += $graph1_['July'];
	$totalterjaminData8 += $graph1_['Agustus'];
	$totalterjaminData9 += $graph1_['September'];
	$totalterjaminData10 += $graph1_['Oktober'];
	$totalterjaminData11 += $graph1_['November'];
	$totalterjaminData12 += $graph1_['Desember'];
	//$namabulan .= '{	label :"'.$graph1_['nmproduk'].'", backgroundColor: "rgba('.MetRandom(3).','.MetRandom(3).','.MetRandom(3).',10)", data: ['.$graph1_['January'].', '.$graph1_['February'].', '.$graph1_['March'].', '.$graph1_['April'].', '.$graph1_['May'].', '.$graph1_['June'].', '.$graph1_['July'].', '.$graph1_['Agustus'].', '.$graph1_['September'].', '.$graph1_['Oktober'].', '.$graph1_['November'].', '.$graph1_['Desember'].']	},';
	$namabulan .= '{	name: "'.$graph1_['nmproduk'].'",	data: ['.$graph1_['January'].', '.$graph1_['February'].', '.$graph1_['March'].', '.$graph1_['April'].', '.$graph1_['May'].', '.$graph1_['June'].', '.$graph1_['July'].', '.$graph1_['Agustus'].', '.$graph1_['September'].', '.$graph1_['Oktober'].', '.$graph1_['November'].', '.$graph1_['Desember'].']	},';
}
/*
echo '<style>	canvas {	-moz-user-select: none;	-webkit-user-select: none;	-ms-user-select: none;	}	</style>
<script>
var randomScalingFactor = function() {	return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);	};
var randomColorFactor = function() {	return Math.round(Math.random() * 255);	};
var barChartData = {
	labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
	datasets: ['.$namabulan.']
};
window.onload = function() {
	var ctx = document.getElementById("canvas").getContext("2d");
	window.myBar = new Chart(ctx, {
		type: "bar",
		data: barChartData,
		options: {
			title:{	display:true,	text:"Report Summary Data Terjamin"	},
			tooltips: {	mode: "label"	},
			responsive: true,
			scales: {
				xAxes: [{	stacked: true,	}],
				yAxes: [{	stacked: true	}]
			}
		}
	});
};

$("#randomizeData").click(function() {
	$.each(barChartData.datasets, function(i, dataset) {
		dataset.backgroundColor = "rgba(" + randomColorFactor() + "," + randomColorFactor() + "," + randomColorFactor() + ",.7)";
		dataset.data = [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100];
	});
window.myBar.update();
});
</script>';
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Grafik Data Tejamin Bank</font></th></tr></table>';
echo '<center><div style="width: 70%"><canvas id="canvas"></canvas></div></center>';
echo '<table border="0" cellpadding="5" cellspacing="0" width="75%" align="center">
	  <tr><td> </td>
	  	  <td>Januari</td>
	  	  <td>Februari</td>
	  	  <td>Maret</td>
	  	  <td>April</td>
	  	  <td>Mei</td>
	  	  <td>Juni</td>
	  	  <td>Juli</td>
	  	  <td>Agustus</td>
	  	  <td>September</td>
	  	  <td>Oktober</td>
	  	  <td>November</td>
	  	  <td>Desember</td>
	  </tr>
	  <tr><td> </td>
	  	  <td>'.duit($totalterjaminData1).'</td>
	  	  <td>'.duit($totalterjaminData2).'</td>
	  	  <td>'.duit($totalterjaminData3).'</td>
	  	  <td>'.duit($totalterjaminData4).'</td>
	  	  <td>'.duit($totalterjaminData5).'</td>
	  	  <td>'.duit($totalterjaminData6).'</td>
	  	  <td>'.duit($totalterjaminData7).'</td>
	  	  <td>'.duit($totalterjaminData8).'</td>
	  	  <td>'.duit($totalterjaminData9).'</td>
	  	  <td>'.duit($totalterjaminData10).'</td>
	  	  <td>'.duit($totalterjaminData11).'</td>
	  	  <td>'.duit($totalterjaminData12).'</td>
	  </tr>
	  </table>';
*/
?>
<script type="text/javascript" src="javascript/graph/metjquery.min.1.8.js"></script>
<style type="text/css">	${demo.css}	</style>
<script type="text/javascript">
$(function () {
	$('#container').highcharts({
		chart: {	type: 'column'	},
		title: {	text: 'Grafik Data Terjamin'	},
		xAxis: {	categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]	},
		yAxis: {
			min: 0,
			title: {	text: 'Total Data Terjamin'	},
			stackLabels: {
				enabled: true,
				style: {	fontWeight: 'bold',	color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'	}
			}
		},
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 25,
			floating: true,
			backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false
		},
		tooltip: {
			headerFormat: '<b>{point.x}</b><br/>',
			pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				}
			}
		},
		series: [
		<?php
			echo $namabulan;
		?>
		]
	});
});
</script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		<?php
		;
		break;

	case "gpplafondbank":
		if ($_REQUEST['cat'])	{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';	}
		if ($_REQUEST['cat']=="") {	$status_client = "SEMUA CLIENT";	}
		else{
			$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
			$status_client = $searchbank['name'];
		}

		if ($_REQUEST['subccat'])		{	$dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';	}
		if ($_REQUEST['tgldn1'])		{	$tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
			$tgldn_ = explode("-", $_REQUEST['tgldn2']);
			$labletgl = $tgldn_[0] .' -'.$tgldn_[1];
			if ($tgldn_[1]==1) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,';
			}elseif ($tgldn_[1]==2) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,';
			}elseif ($tgldn_[1]==3) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,';
			}elseif ($tgldn_[1]==4) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,';
			}elseif ($tgldn_[1]==5) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,';
			}elseif ($tgldn_[1]==6) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,';
			}elseif ($tgldn_[1]==7) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,';
			}elseif ($tgldn_[1]==8) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Agustus,';
			}elseif ($tgldn_[1]==9) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Agustus,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS September,';
			}elseif ($tgldn_[1]==10) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Agustus,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS September,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Oktober,';
			}elseif ($tgldn_[1]==11) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Agustus,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS September,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Oktober,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-11" AND "'.$tgldn_[0].'-11" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS November,';
			}elseif ($tgldn_[1]==12) {
				$countterjamin = 'SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-01" AND "'.$tgldn_[0].'-01" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS January,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-02" AND "'.$tgldn_[0].'-02" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS February,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-03" AND "'.$tgldn_[0].'-03" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS March,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-04" AND "'.$tgldn_[0].'-04" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS April,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-05" AND "'.$tgldn_[0].'-05" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS May,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-06" AND "'.$tgldn_[0].'-06" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS June,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-07" AND "'.$tgldn_[0].'-07" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS July,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-08" AND "'.$tgldn_[0].'-08" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Agustus,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-09" AND "'.$tgldn_[0].'-09" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS September,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-10" AND "'.$tgldn_[0].'-10" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Oktober,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-11" AND "'.$tgldn_[0].'-11" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS November,
								  SUM(CASE WHEN DATE_FORMAT(fu_ajk_dn.tgl_createdn,"%Y-%m") BETWEEN "'.$tgldn_[0].'-12" AND "'.$tgldn_[0].'-12" THEN ROUND(fu_ajk_peserta.kredit_jumlah / 100000) END) AS Desember,';

			}else{

			}
			$tanggaldebitnote ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Debit Note</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
		}

		if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
			$tanggaltransaksi ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Transaksi</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
		}

		//if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
		//$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
		//}
		if ($_REQUEST['paiddata'])		{	$empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';	}
		if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
			$lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
		}
		if ($_REQUEST['id_reg']=="") {	$status_regional = "SEMUA REGIONAL";	}	else{	$status_regional = $met_reg['name'];	}

		if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
			$enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
		}
		if ($_REQUEST['id_cab']=="") {	$status_cabang = "SEMUA CABANG";	}	else{	$status_cabang = $met_cab['name'];	}

		if ($_REQUEST['statpeserta'])	{
			$status_ = explode("-", $_REQUEST['statpeserta']);
			if (!$status_[1]) {	$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';	}
			else{	$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
				$delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
			}
		}
		if ($_REQUEST['grupprod'])	{	$sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';	}
		$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
		if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}else{	$searchpaid = "UNPAID";	}
		if ($_REQUEST['id_polis']=="") {	$status_produknya = "SEMUA PRODUK";	}	else{	$status_produknya = $searchproduk['nmproduk'];	}
$graph2 = $database->doQuery('SELECT fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.typeproduk,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
'.$countterjamin.'
COUNT(fu_ajk_peserta.nama) AS jData,
SUM(fu_ajk_peserta.kredit_jumlah) AS jPlafond
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
GROUP BY fu_ajk_polis.typeproduk, fu_ajk_polis.id
ORDER BY jPlafond DESC');
while ($graph2_ = mysql_fetch_array($graph2)) {
	if ($graph2_['January']==0) 	{	$graph2__ = 0;	}else{	$graph2__ = $graph2_['January'];	}
	if ($graph2_['February']==0)	{	$graph3__ = 0;	}else{	$graph3__ = $graph2_['February'];	}
	if ($graph2_['March']==0)	 	{	$graph4__ = 0;	}else{	$graph4__ = $graph2_['March'];	}
	if ($graph2_['April']==0) 		{	$graph5__ = 0;	}else{	$graph5__ = $graph2_['April'];	}
	if ($graph2_['May']==0) 		{	$graph6__ = 0;	}else{	$graph6__ = $graph2_['May'];	}
	if ($graph2_['June']==0) 		{	$graph7__ = 0;	}else{	$graph7__ = $graph2_['June'];	}
	if ($graph2_['July']==0) 		{	$graph8__ = 0;	}else{	$graph8__ = $graph2_['July'];	}
	if ($graph2_['Agustus']==0) 	{	$graph9__ = 0;	}else{	$graph9__ = $graph2_['Agustus'];	}
	if ($graph2_['September']==0) 	{	$graph10__ = 0;	}else{	$graph10__ = $graph2_['September'];	}
	if ($graph2_['Oktober']==0) 	{	$graph11__ = 0;	}else{	$graph11__ = $graph2_['Oktober'];	}
	if ($graph2_['November']==0) 	{	$graph12__ = 0;	}else{	$graph12__ = $graph2_['November'];	}
	if ($graph2_['Desember']==0) 	{	$graph13__ = 0;	}else{	$graph13__ = $graph2_['Desember'];	}
	$totalterjaminData1 += $graph2_['January'];
	$totalterjaminData2 += $graph2_['February'];
	$totalterjaminData3 += $graph2_['March'];
	$totalterjaminData4 += $graph2_['April'];
	$totalterjaminData5 += $graph2_['May'];
	$totalterjaminData6 += $graph2_['June'];
	$totalterjaminData7 += $graph2_['July'];
	$totalterjaminData8 += $graph2_['Agustus'];
	$totalterjaminData9 += $graph2_['September'];
	$totalterjaminData10 += $graph2_['Oktober'];
	$totalterjaminData11 += $graph2_['November'];
	$totalterjaminData12 += $graph2_['Desember'];
	//$plafondgraph .= '{	label :"'.$graph2_['nmproduk'].'", backgroundColor: "rgba('.MetRandom(3).','.MetRandom(3).','.MetRandom(3).',10)", data: ['.$graph2__.', '.$graph3__.', '.$graph4__.', '.$graph5__.', '.$graph6__.', '.$graph7__.', '.$graph8__.', '.$graph9__.', '.$graph10__.', '.$graph11__.', '.$graph12__.', '.$graph13__.']	},';
	$plafondgraph .= '{	name: "'.$graph2_['nmproduk'].'",	data: ['.$graph2__.', '.$graph3__.', '.$graph4__.', '.$graph5__.', '.$graph6__.', '.$graph7__.', '.$graph8__.', '.$graph9__.', '.$graph10__.', '.$graph11__.', '.$graph12__.', '.$graph13__.']	},';
}
/*
echo '<style>	canvas {	-moz-user-select: none;	-webkit-user-select: none;	-ms-user-select: none;	}	</style>
		<script>
		var randomScalingFactor = function() {	return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);	};
		var randomColorFactor = function() {	return Math.round(Math.random() * 255);	};
		var barChartData = {
			labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			datasets: ['.$plafondgraph.']
		};
		window.onload = function() {
			var ctx = document.getElementById("canvas").getContext("2d");
			window.myBar = new Chart(ctx, {
				type: "bar",
				data: barChartData,
				options: {
					title:{	display:true,	text:"Report Summary Plafond Terjamin  /1.000.000"	},
					tooltips: {	mode: "label"	},
					responsive: true,
					scales: {
						xAxes: [{	stacked: true,	}],
						yAxes: [{	stacked: true	}]
					}
				}
			});
		};

		$("#randomizeData").click(function() {
			$.each(barChartData.datasets, function(i, dataset) {
				dataset.backgroundColor = "rgba(" + randomColorFactor() + "," + randomColorFactor() + "," + randomColorFactor() + ",.7)";
				dataset.data = [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100];
			});
		window.myBar.update();
		});
		</script>';

echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Grafik Data Plafond Bank</font></th></tr></table>';
echo '<center><div style="width: 80%"><canvas id="canvas"></canvas></div></center>';
echo '<table border="0" cellpadding="5" cellspacing="0" width="75%" align="center">
	  <tr><td> </td>
	  	  <td>Januari</td>
	  	  <td>Februari</td>
	  	  <td>Maret</td>
	  	  <td>April</td>
	  	  <td>Mei</td>
	  	  <td>Juni</td>
	  	  <td>Juli</td>
	  	  <td>Agustus</td>
	  	  <td>September</td>
	  	  <td>Oktober</td>
	  	  <td>November</td>
	  	  <td>Desember</td>
	  </tr>
	  <tr><td> </td>
	  	  <td>'.duit($totalterjaminData1).'</td>
	  	  <td>'.duit($totalterjaminData2).'</td>
	  	  <td>'.duit($totalterjaminData3).'</td>
	  	  <td>'.duit($totalterjaminData4).'</td>
	  	  <td>'.duit($totalterjaminData5).'</td>
	  	  <td>'.duit($totalterjaminData6).'</td>
	  	  <td>'.duit($totalterjaminData7).'</td>
	  	  <td>'.duit($totalterjaminData8).'</td>
	  	  <td>'.duit($totalterjaminData9).'</td>
	  	  <td>'.duit($totalterjaminData10).'</td>
	  	  <td>'.duit($totalterjaminData11).'</td>
	  	  <td>'.duit($totalterjaminData12).'</td>
	  </tr>
	  </table>';
*/


?>
<script type="text/javascript" src="javascript/graph/metjquery.min.1.8.js"></script>
<style type="text/css">	${demo.css}	</style>
<script type="text/javascript">
$(function () {
	$('#container').highcharts({
		chart: {	type: 'column'	},
		title: {	text: 'Grafik Data Plafond Terjamin  /100.000'	},
		xAxis: {	categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]	},
		yAxis: {
			min: 0,
			title: {	text: 'Total Plafond Terjamin'	},
			stackLabels: {
				enabled: true,
				style: {	fontWeight: 'bold',	color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'	}
			}
		},
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 25,
			floating: true,
			backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false
		},
		tooltip: {
			headerFormat: '<b>{point.x}</b><br/>',
			pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				}
			}
		},
		series: [
	<?php
	echo $plafondgraph;
	?>
		]
	});
});
		</script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php
		;
		break;

		break;
	default:
		;
} // switch

?>
<script src="javascript/graph/methighcharts.js"></script>
<script src="javascript/graph/metexporting.js"></script>