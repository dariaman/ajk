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
switch ($_REQUEST['b']) {
	case "x":
		;
		break;
	case "eL_clBank":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Covering Letter</font></th></tr></table>';

if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$searchbank_ = $searchbank['name'];
}else{
$searchbank_ = "SEMUA PERUSAHAAN";
}
if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['subcat'])		{	$duaa = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['tgldn1'])		{	$tigaa = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
	$tanggaldebitnote ='<tr><td>Tanggal Debit Note</td><td>: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
	$tanggalakadasuransi = '<tr><td>Tanggal Akad</td><td>: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
}
if ($_REQUEST['paid'])		{	$empt = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paid'].'"';	}

if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}

/*
if ($_REQUEST['status'])	{
	$status_ = explode("-", $_REQUEST['status']);
	if (!$status_[1]) {
		$lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
	}else{
		$lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
		$delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
	}
}
*/
if ($_REQUEST['status'])		{
	if ($_REQUEST['status']=="Produksi") {
		$lima = 'AND fu_ajk_peserta.status_aktif IN ("Inforce", "Lapse") AND (fu_ajk_peserta.status_peserta NOT IN ("Batal", "Req_Batal") OR fu_ajk_peserta.status_peserta IS NULL )';
	}else{
		$lima = 'AND fu_ajk_peserta.status_aktif = "'.$_REQUEST['status'].'"';
	}
}

if ($_REQUEST['grupprod'])	{	$sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';	}

if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
$tanggaltransaksi ='<tr><td>Tanggal Transaksi</td><td colspan="14">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
}

/*
$met = $database->doQuery('SELECT fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.mpptype,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.nama_mitra,
COUNT(fu_ajk_peserta.nama) AS jPeserta,
fu_ajk_peserta.spaj,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_akhir,
SUM(fu_ajk_peserta.kredit_jumlah) AS tPlafond,
SUM(fu_ajk_peserta.premi) AS tPremi,
SUM(fu_ajk_peserta.ext_premi) tExtPremi,
SUM(fu_ajk_peserta.totalpremi) AS tTotalPremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.mppbln,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" AND fu_ajk_peserta.del is NULL '.$satu.' '.$duaa.' '.$tigaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.'
GROUP BY fu_ajk_peserta.cabang
ORDER BY fu_ajk_peserta.cabang');
*/
$met = $database->doQuery('SELECT fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.mpptype,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgltransaksi,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.nama_mitra,
COUNT(fu_ajk_peserta.nama) AS jPeserta,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.spaj,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
SUM(fu_ajk_peserta.kredit_jumlah) AS tPlafond,
SUM(fu_ajk_peserta.premi) AS tPremi,
SUM(fu_ajk_peserta.ext_premi) AS tExPremi,
SUM(fu_ajk_peserta.totalpremi) AS tTotalPremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.mppbln,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del is NULL '.$satu.' '.$duaa.' '.$tigaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.'
GROUP BY fu_ajk_peserta.cabang
ORDER BY fu_ajk_peserta.cabang ASC');
if ($_REQUEST['paid']=="1") 	{	$searchpaid = "PAID";	}	elseif ($_REQUEST['paid']=="0") {	$searchpaid = "UNPAID";	}	else{	$searchpaid = "SEMUA STATUS PEMBAYARAN";	}
if ($_REQUEST['subcat']=="") 	{	$status_produknya = "SEMUA PRODUK";	}	else{
	$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
	$status_produknya = $searchproduk['nmproduk'];
}
if ($_REQUEST['status']=="") 	{	$statuspeserta = "SEMUA STATUS";	}	else{	$statuspeserta = $_REQUEST['status'];	}
if ($_REQUEST['id_reg']=="") 	{	$dataRegional = "SEMUA REGIONAL";	}	else{	$dataRegional = $met_reg['name'];	}
if ($_REQUEST['id_cab']=="") 	{	$dataCabang = "SEMUA CABANG";		}	else{	$dataCabang = $met_cab['name'];	}
if ($_REQUEST['grupprod']=="") 	{	$status_mitranya = "SEMUA MITRA";	}	else{
	$searchmitra = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$_REQUEST['grupprod'].'"'));
	$status_mitranya = $searchmitra['nmproduk'];
}

echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td width="10%">Nama Perusahaan</td><td>: '.$searchbank_.'</td></tr>
	  <tr><td>Nama Produk</td><td>: '.$status_produknya.'</td></tr>
	  <tr><td>Mitra</td><td>: '.$status_mitranya.'</td></tr>
	  '.$tanggalakadasuransi.'
	  '.$tanggaldebitnote.'
	  '.$tanggaltransaksi.'
	  <tr><td>Status Pembayaran</td><td>: '.$searchpaid.'</td></tr>
	  <tr><td>Status Peserta</td><td>: '.$statuspeserta.'</td></tr>
	  <tr><td>Regional</td><td>: '.$dataRegional.'</td></tr>
	  <tr><td>Cabang</td><td>: '.$dataCabang.'</td></tr>
	  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
			<tr><th width="1%">No</td>
				<th>Cabang</td>
				<th width="10%">Jumlah Peserta</td>
				<th width="20%">Plafond</td>
				<th width="10%">Premi</td>
				<th width="10%">Extre Premi</td>
				<th width="10%">Total Premi</td>
				<th width="10%">Option</td>
			</tr>';
while ($metCabang_ = mysql_fetch_array($met)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center" width="5%">'.++$no.'</td>
				  <td>'.$metCabang_['cabang'].'</td>
				  <td align="center">'.duit($metCabang_['jPeserta']).'</td>
				  <td align="right">'.duit($metCabang_['tPlafond']).'</td>
				  <td align="right">'.duit($metCabang_['tPremi']).'</td>
				  <td align="right">'.duit($metCabang_['tExPremi']).'</td>
				  <td align="right">'.duit($metCabang_['tTotalPremi']).'</td>
				  <!--<td align="center"><a href="e_report.php?er=eL_CoveringLetter&id_cost='.$_REQUEST['id_cost'].'&nmProd='.$_REQUEST['nmProd'].'&cbg='.$metCabang_['id_cabang'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'"><img src="image/excel.png" width="25" border="0"></a></td>-->
				  <td align="center"><a title="print covering letter peserta cabang '.strtolower($metCabang_['cabang']).'" href="e_report.php?er=eL_PrintCoveringLetter&val=bank&id_cost='.$_REQUEST['cat'].'&nmProd='.$_REQUEST['subcat'].'&grupprod='.$_REQUEST['grupprod'].'&tgl1='.$_REQUEST['tgldn1'].'&tgl2='.$_REQUEST['tgldn2'].'&tgl3='.$_REQUEST['tglakad1'].'&tgl4='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$status_[0].'&cbg='.$metCabang_['cabang'].'" target="_blank"><img src="image/print.png" width="22" border="0"></a> &nbsp;
									 <a title="print covering letter peserta cabang '.strtolower($metCabang_['cabang']).'" href="e_report.php?er=eL_PrintCoveringLetterPDF&val=bank&id_cost='.$_REQUEST['cat'].'&nmProd='.$_REQUEST['subcat'].'&grupprod='.$_REQUEST['grupprod'].'&tgl1='.$_REQUEST['tgldn1'].'&tgl2='.$_REQUEST['tgldn2'].'&tgl3='.$_REQUEST['tglakad1'].'&tgl4='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$status_[0].'&cbg='.$metCabang_['cabang'].'" target="_blank"><img src="image/dninvoice1.jpg" width="22" border="0"></a></td>
			  </tr>';
$jNama_ += $metCabang_['jPeserta'];
$jPlafond_ += $metCabang_['tPlafond'];
$jPremi_ += $metCabang_['tPremi'];
$jNilaiEM_ += $metCabang_['tExPremi'];
$jTPremi_ += $metCabang_['tTotalPremi'];
}
echo '<tr><td colspan="2"><b>TOTAL</b></td>
		  <td align="center"><b>'.duit($jNama_).' Peserta</b></td>
		  <td align="right"><b>'.duit($jPlafond_).'</b></td>
		  <td align="right"><b>'.duit($jPremi_).'</b></td>
		  <td align="right"><b>'.duit($jNilaiEM_).'</b></td>
		  <td align="right"><b>'.duit($jTPremi_).'</b></td>
	  </tr>
	  </table>';
		;
		break;

	case "rmf":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Risk Management Fund (RMF)</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
			      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
				  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
echo '</select></td></tr>
			  <tr><td align="right">Nama Produk</td>
				<td id="polis_rate">: <select name="id_polis" id="id_polis">
				<option value="">-- Pilih Produk --</option>
				</select></td></tr>
			  <tr><td align="right">Tanggal Akad <font color="red">*</font></td>
			  	  <td> :';print initCalendar();	print calendarBox('tglakad1', 'triger1', $_REQUEST['tglakad1']);	echo 's/d';
		print initCalendar();	print calendarBox('tglakad2', 'triger2', $_REQUEST['tglakad2']);
echo '</td>
			  </tr>
			  <tr><td align="right">Status Pembayaran </td><td> :
					  <select size="1" name="paiddata"><option value="">--- Status ---</option>
					  								  <option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
													  <option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
					  </select>
			  </td></tr>
			  <tr><td align="right">Status Peserta </td><td> :
					  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
					  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Aktif</option>
													  <option value="Lapse"'._selected($_REQUEST['statpeserta'], "Lapse").'>Lapse</option>
													  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
													  <option value="Batal"'._selected($_REQUEST['statpeserta'], "Batal").'>Batal</option>
					  </select>
			  </td></tr>
			  <tr><td align="right">Regional</td>
				<td id="polis_rate">: <select name="id_reg" id="id_reg">
				<option value="">-- Pilih Regional --</option>
				</select></td></tr>
			  <tr><td align="right">Cabang</td>
				<td id="polis_rate">: <select name="id_cab" id="id_cab">
				<option value="">-- Pilih Cabang --</option>
				</select></td></tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
			  </table>
			  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglakad1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglakad2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_polis = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
//if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['paiddata']=="1") {	$empt = 'AND status_bayar = "1"';	}
elseif ($_REQUEST['paiddata']=="0") {	$empt = 'AND status_bayar = "0"';	}
else {	}


if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';	}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';		}

$_namaperusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
//STATUS PRODUK//
$_namaproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
if (!$_REQUEST['id_polis']) {	$_namaproduknya_ = "SEMUA PRODUK";	}	else {	$_namaproduknya_ = $_namaproduk['nmproduk'];	}
//STATUS PRODUK//

//STATUS PEMBAYARAN/
if ($_REQUEST['paiddata']=="1") {	$_statusbayar="PAID";	}
elseif ($_REQUEST['paiddata']=="0") {	$_statusbayar="UNPAID";	}
else {	$_statusbayar="PAID dan UNPAID";	}
//STATUS PEMBAYARAN//

//STATUS WILAYAH//
if (!$_REQUEST['id_reg']) {	$regionalnya = "SEMUA REGIONAL";	}	else{	$regionalnya = $met_reg['name'];	}
if (!$_REQUEST['id_cab']) {	$cabangnya = "SEMUA CABANG";	}		else{	$cabangnya = $met_cab['name'];	}
//STATUS WILAYAH//

$cekdata_paid = $database->doQuery('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, status_bayar, totalpremi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($cekdata_paid_ = mysql_fetch_array($cekdata_paid)) {
	if ($cekdata_paid_['status_bayar']=="1") {
		$met_cekdata_paid += $cekdata_paid_['totalpremi'];
		$met_cekdata_paid_rmf += $cekdata_paid_['rmf'];
	}else{
		$met_cekdata_unpaid += $cekdata_paid_['totalpremi'];
	}
}
$metRMFtotal = $met_cekdata_paid + $met_cekdata_unpaid;
/*
$met_rmf_paid = mysql_query('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, premi, totalpremi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="1" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_paid_ = mysql_fetch_array($met_rmf_paid)) {
	$_metrmfnya_premi += ROUND($met_rmf_paid_['totalpremi']);
	$_metrmfnya_paid += ROUND($met_rmf_paid_['rmf']);
}

$met_rmf_unpaid = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="0" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_unpaid_ = mysql_fetch_array($met_rmf_unpaid)) {
	$cek_rate_RMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_rmf_unpaid_['id_cost'].'" AND id_polis="'.$met_rmf_unpaid_['id_polis'].'" AND tenor="'.$met_rmf_unpaid_['kredit_tenor'].'"'));
	$mametRMF = $met_rmf_unpaid_['kredit_jumlah'] * $cek_rate_RMF['rate'] / 1000;
	$_metrmfnya_unpaid += ROUND($mametRMF);
}
*/
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr>
			<td bgcolor="#FFF"colspan="14"><a href="e_report.php?er=eL_rmf&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&reg='.$_REQUEST['id_reg'].'&cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
			<td bgcolor="#FFF"colspan="2" ><input id="cari" type="text" class="form-control" placeholder="Cari"></td>
		</tr>
		<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$_namaperusahaan['name'].'</td></tr>
		<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$_namaproduknya_.'</td></tr>
		<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' - '._convertDate($_REQUEST['tglakad2']).'</td></tr>
		<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.$_statusbayar.'</td></tr>
		<tr><td colspan="2">Regional</td><td colspan="14">: '.$regionalnya.'</td></tr>
		<tr><td colspan="2">Cabang</td><td colspan="14">: '.$cabangnya.'</td></tr>
		<tr><td colspan="2">Premi RMF</td><td colspan="14">: '.duit($met_cekdata_paid_rmf).'</td></tr>
		<tr><td colspan="2">Premi dibayar</td><td colspan="14">: '.duit($met_cekdata_paid).'</td></tr>
		<tr><td colspan="2">Premi belum dibayar</td><td colspan="14">: '.duit($met_cekdata_unpaid).'</td></tr>
		<tr><td colspan="2"><b>Total</b></td><td colspan="14"><b>: '.duit($metRMFtotal).'</b></td></tr>
		<tr><th width="1%">No</th>
			<th>Debit Note</th>
			<th>Tanggal DN</th>
			<th>No. Reg</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>JK.W</th>
			<th>Mulai Asuransi</th>
			<th>Akhir Asuransi</th>
			<th>Status</th>
			<th>Total Premi</th>
			<th>RMF (paid)</th>
			<th>RMF (unpaid)</th>
		</tr><tbody class="caritable">';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
	$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' ORDER BY kredit_tgl ASC LIMIT '.$m.', 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.''));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($met_ = mysql_fetch_array($met)) {
	$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$dua.' AND tenor="'.$met_['kredit_tenor'].'"'));
	/*NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
	$met_rmf = mysql_fetch_array($database->doQuery('SELECT id, id_cost, rmf FROM fu_ajk_polis WHERE id_cost="'.$met_['id_cost'].'" AND id="'.$met_['id_polis'].'"'));		//NILAI RMF
	$er_rmf = $met_['totalpremi'] * $met_rmf['rmf']/100;
	NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
	*/

if ($met_['status_bayar']=="1") {
	$metrmfnya_paid = ROUND($met_['rmf']);
	$metrmfnya_unpaid = '';
	$met_bayar = "PAID";
}else{
	$metrmfnya_paid ='';
	$cek_rate_RMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'].'"'));
	$metrmfnya_unpaid = '';
	//$metrmfnya_unpaid = ROUND($met_['kredit_jumlah']) * $cek_rate_RMF['rate'] / 1000;
	//$metrmfnya_unpaid = round($met_['totalpremi']) * $cekdatapolis['rmf'] / 100;
	$met_bayar = "UNPAID";
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$cekdatadn['dn_kode'].'</td>
		  <td align="center">'._convertDate($cekdatadn['tgl_createdn']).'</td>
		  <td align="center">'.$met_['id_peserta'].'</td>
		  <td>'.$met_['nama'].'</td>
		  <td>'.$met_['cabang'].'</td>
		  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
		  <td align="center">'.$met_['usia'].'</td>
		  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
		  <td align="center">'.$met_['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
		  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
		  <td align="center">'.$met_bayar.'</td>
		  <td align="right">'.duit(round($met_['totalpremi'])).'</td>
		  <td align="right">'.duit($metrmfnya_paid).'</td>
		  <td align="right">'.duit($metrmfnya_unpaid).'</td>
		  </tr>';
		$jumUP += $met_['kredit_jumlah'];
		$jumPremi += ROUND($met_['totalpremi']);
		$jumRMFpaid += ROUND($metrmfnya_paid);
		$jumRMFunpaid += ROUND($metrmfnya_unpaid);
	}
		echo '<tr class="tr1"><td colspan="8"><b>TOTAL</b></td><td align="right"><b>'.duit($jumUP).'</td><td colspan="4"></td><td align="right"><b>'.duit($jumPremi).'</td><td align="right"><b>'.duit($jumRMFpaid).'</td><td align="right"><b>'.duit($jumRMFunpaid).'</td></tr>';
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_re_bank.php?re=datapeserta&b=rmf&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr></tbody>';
		echo '</table>';
	}
	}
		;
		break;

case "summary_report" :
			echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Klaim</font></th></tr></table>';
			$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
			$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk ORDER BY nmproduk ASC');
			$met_status = $database->doQuery('SELECT * FROM fu_ajk_klaim_status ORDER BY order_list ASC');
			$metAsuransi = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name ASC');
			echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan </td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
			while($metcost_ = mysql_fetch_array($metcost)) {
				echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
			}
			echo '</select></td></tr>

	<tr><td align="right">Pilih Asuransi</td>
		<!--<td id="polis_rate">: <select name="id_as" id="id_as">
		<option value="">-- Semua Asuransi --</option>
		</select></td> DISABLED 04112015-->
		<td id="polis_rate">: <select name="id_as" id="id_as">
		<option value="">-- Semua Asuransi --</option>';
	while($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
		echo  '<option value="'.$metAsuransi_['id'].'"'._selected($_REQUEST['id_as'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
	}
	echo '</select></td></tr>
		<tr><td align="right">Nama Produk</td>
			<td id="polis_rate">: <select name="id_polis" id="id_polis">
			<option value="">-- Pilih Produk --</option>
			</select></td></tr>

	  <tr><td align="right">Tanggal Klaim</td>
	  	  <td> : <input type="text" id="fromdn1" name="tglklaim1" value="'.$_REQUEST['tglklaim1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglklaim2" value="'.$_REQUEST['tglklaim2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
			echo '</td>
	  </tr>
	  <tr><td align="right">Status Klaim </td><td> :
			  <select size="1" name="status"><option value="">--- Status ---</option>';
				while($met_status_ = mysql_fetch_array($met_status)) {
					echo '<option value="'.$met_status_['id'].'"'._selected($_REQUEST['status'], $met_status_['id']).'>'.$met_status_['status_klaim'].'</option>';
				}
			  echo '</select>
	  </td></tr>
	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
			if ($_REQUEST['re']=="datapeserta") {

				if(empty($_REQUEST['id_cost'])) {
					$error_1='Silahkan pilih nama perusahaan!';
				}

				if(empty($_REQUEST['tglklaim1']) or empty($_REQUEST['tglklaim2'])){
					$error_2='Silahkan pilih rentang tanggal klaim!';
				}




				if ($error_1 or $error_2) {	echo $error_1.$error_2;	}
				else{

					if ($_REQUEST['id_cost'])	{
						$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
					}


					if ($_REQUEST['id_as'])	{
						$satu = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_as'].'"';
					}


					if ($_REQUEST['id_polis'])		{
						$dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';

					}

					if ($_REQUEST['tglklaim1'])		{
						$tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tglklaim1'].'" AND "'.$_REQUEST['tglklaim2'].'"';
					}


					if ($_REQUEST['status'])		{
						$lima = 'AND fu_ajk_klaim.id_klaim_status = "'.$_REQUEST['status'].'"';
					}

					if ($_REQUEST['id_reg']!=="") {
						$enam = 'AND fu_ajk_peserta.regional="'.$_REQUEST['id_reg'].'"';
					}

					if ($_REQUEST['id_cab']!=="") {
						$tujuh= 'AND fu_ajk_peserta.cabang="'.$_REQUEST['id_cab'].'"';
					}

					if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
					$met = $database->doQuery('SELECT
							fu_ajk_costumer.`name`,
							fu_ajk_polis.nmproduk,
							fu_ajk_polis.mpptype,
							fu_ajk_dn.dn_kode,
							fu_ajk_dn.dn_status,
							fu_ajk_dn.tgltransaksi,
							fu_ajk_dn.tgl_dn_paid,
							fu_ajk_dn.tgl_createdn,
							fu_ajk_peserta.id_peserta,
							fu_ajk_peserta.id_cost,
							fu_ajk_peserta.id_polis,
							fu_ajk_peserta.nama_mitra,
							fu_ajk_peserta.nama,
							fu_ajk_peserta.tgl_lahir,
							fu_ajk_peserta.spaj,
							fu_ajk_peserta.usia,
							fu_ajk_peserta.kredit_tgl,
							fu_ajk_peserta.kredit_tenor,
							fu_ajk_peserta.kredit_akhir,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_peserta.premi,
							fu_ajk_peserta.ext_premi,
							fu_ajk_peserta.totalpremi,
							fu_ajk_peserta.type_data,
							fu_ajk_peserta.status_aktif,
							fu_ajk_peserta.status_peserta,
							fu_ajk_peserta.mppbln,
							fu_ajk_peserta.regional,
							fu_ajk_peserta.cabang,
							fu_ajk_cn.tgl_createcn,
							fu_ajk_cn.total_claim,
							fu_ajk_cn.tgl_byr_claim,
							fu_ajk_cn.tgl_bayar_asuransi,
							fu_ajk_cn.total_bayar_asuransi,
							fu_ajk_klaim_status.status_klaim,
							fu_ajk_asuransi.`name` AS nmAsuransi
							FROM fu_ajk_peserta
							INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
							INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
							WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL
							ORDER BY fu_ajk_peserta.id_polis ASC,
									 fu_ajk_dn.id_as ASC,
									 fu_ajk_peserta.cabang ASC,
									 fu_ajk_peserta.nama ASC
							LIMIT '.$m.', 25');


					$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id_peserta)
							FROM fu_ajk_peserta
							INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
							INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
							WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL'));
					echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">

							<tr><td><a href="e_report.php?er=eL_Rekap_Klaim&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$_REQUEST['id_polis'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25">Excel</a></td>
								<td><a target="_blank" href="ajk_report_asuransi.php?er=sum1&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$_REQUEST['id_polis'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25">Klaim Summary Per Produk</a>
									<br><a target="_blank" href="ajk_report_asuransi.php?er=sum2&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$_REQUEST['id_polis'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25">Klaim Summary Per Produk & asuransi</a></td>
								<td><a target="_blank" href="ajk_re_bank.php?b=eL_clAsuransi&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$_REQUEST['id_polis'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dnmember.png" width="25">Covering Letter</a>
								<!-- <a target="_blank" href="ajk_report_asuransi.php?er=sum3&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$_REQUEST['id_polis'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25">Loss Rasio</a> --!>
								</td></tr>
							<tr><th width="1%">No</th>
							<th>Asuransi</th>
							<th>Produk</th>
							<th>Debit Note</th>
							<th>Tanggal DN</th>
							<th>Mulai Asuransi</th>
							<th>Akhir Asuransi</th>							
							<th>No. Reg</th>
							<th>Nama Debitur</th>
							<th>Cabang</th>
							<th>Tgl Lahir</th>
							<th>Usia</th>
							<th>Plafond</th>
							<th>Total Premi</th>
							<th>Nilai Klaim</th>
							<th>Asuransi Bayar</th>
							<th>Status</th>
							</tr>';

					$totalRows = $totalRows[0];
					$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
					while ($met_ = mysql_fetch_array($met)) {
						if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
						echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
						  <td align="center">'.$met_['nmAsuransi'].'</td>
						  <td align="center">'.$met_['nmproduk'].'</td>
						  <td align="center">'.$met_['dn_kode'].'</td>
						  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
						  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
						  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
						  <td align="center">'.$met_['id_peserta'].'</td>
						  <td>'.$met_['nama'].'</td>
						  <td>'.$met_['cabang'].'</td>
						  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
						  <td align="center">'.$met_['usia'].'</td>
						  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
						  <td align="right">'.duit($met_['totalpremi']).'</td>
						  <td align="right">'.duit($met_['total_claim']).'</td>
						  <td align="right">'.duit($met_['total_bayar_asuransi']).'</td>
						  <td align="center">'.$met_['status_klaim'].'</td>
						  </tr>';
						$jumUP += $met_['kredit_jumlah'];
						$jumPremi += ROUND($met_['premi']);
						$jumTotalPremi += ROUND($met_['totalpremi']);
						$jumTotalKlaim += ROUND($met_['total_claim']);
						$jumTotalKlaim_as += ROUND($met_['total_bayar_asuransi']);
					}
					echo '<tr class="tr1"><td colspan="12" align="center"><b>TOTAL</b></td>
					  <td align="right"><b>'.duit($jumUP).'</td>
					  <td align="right"><b>'.duit($jumTotalPremi).'</td>
					  <td align="right"><b>'.duit($jumTotalKlaim).'</td>
					  <td align="right"><b>'.duit($jumTotalKlaim_as).'</td>
					  		</tr>';
					echo '<tr><td colspan="22">';
					echo createPageNavigations($file = 'ajk_re_bank.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&grupprod='.$_REQUEST['grupprod'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
					echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
					echo '</table>';
				}
			}
			;
	break;
case "eL_clAsuransi":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Covering Letter</font></th></tr></table>';

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

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

		$met = mysql_query('SELECT
				fu_ajk_peserta.cabang,
				fu_ajk_polis.nmproduk,
				fu_ajk_polis.id,
				fu_ajk_asuransi.name as nama_asuransi,
				count(fu_ajk_peserta.id_peserta) as jml_debitur,
				sum(fu_ajk_peserta.kredit_jumlah) as plafond,
				sum(fu_ajk_peserta.totalpremi) as premi,
				sum(fu_ajk_cn.total_claim) as klaim,
				sum(fu_ajk_cn.total_bayar_asuransi) as asuransi_bayar
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
				fu_ajk_peserta.cabang,
				fu_ajk_polis.id,
				fu_ajk_polis.nmproduk

		');

		echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td width="10%">Nama Perusahaan</td><td>: '.$cost.'</td></tr>
	  <tr><td>Nama Asuransi</td><td>: '.$as.'</td></tr>
	  <tr><td>Nama Produk</td><td>: '.$polis.'</td></tr>
	  <tr><td>Tanggal Klaim</td><td>: '.$tgl_klaim.'</td></tr>
	  <tr><td>Status Klaim</td><td>: '.$status.'</td></tr>
	  <tr><td>Regional</td><td>: '.$regional.'</td></tr>
	  <tr><td>Cabang</td><td>: '.$cabang.'</td></tr>
	  </table>';
		echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
			<tr><th width="1%">No</td>
				<th>Cabang</td>
				<th width="20%">Produk</td>
				<th width="10%">Jumlah Klaim</td>
				<th width="10%">Plafond</td>
				<th width="10%">Premi</td>
				<th width="10%">Klaim</td>
				<th width="10%">Asuransi Bayar</td>
				<th width="10%">Option</td>
			</tr>';
		while ($metCabang_ = mysql_fetch_array($met)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center" width="5%">'.++$no.'</td>
				  <td>'.$metCabang_['cabang'].'</td>
				  <td align="center">'.$metCabang_['nmproduk'].'</td>
				  <td align="center">'.$metCabang_['jml_debitur'].'</td>
				  <td align="right">'.duit($metCabang_['plafond']).'</td>
				  <td align="right">'.duit($metCabang_['premi']).'</td>
				  <td align="right">'.duit($metCabang_['klaim']).'</td>
				  <td align="right">'.duit($metCabang_['asuransi_bayar']).'</td>
				  <td align="center">
				  		<a title="print covering letter peserta cabang '.strtolower($metCabang_['cabang']).'" href="e_report.php?er=eL_PrintCoveringLetterPDFAsuransi&id_cost='.$_REQUEST['id_cost'].'&id_as='.$_REQUEST['id_as'].'&id_polis='.$metCabang_['id'].'&tglklaim1='.$_REQUEST['tglklaim1'].'&tglklaim2='.$_REQUEST['tglklaim2'].'&status='.$_REQUEST['status'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$metCabang_['cabang'].'" target="_blank"><img src="image/dninvoice1.jpg" width="22" border="0"></a></td>
			  </tr>';
			$jdebitur_ += $metCabang_['jml_debitur'];
			$jpremi_ += $metCabang_['premi'];
			$jplafond_ += $metCabang_['plafond'];
			$jklaim_ += $metCabang_['klaim'];
			$aklaim_ += $metCabang_['asuransi_bayar'];
		}
		echo '<tr><td colspan="3"><b>TOTAL</b></td>
		  <td align="center"><b>'.$jdebitur_.' Peserta</b></td>
		  <td align="right"><b>'.duit($jplafond_).'</b></td>
		  <td align="right"><b>'.duit($jpremi_).'</b></td>
		  <td align="right"><b>'.duit($jklaim_).'</b></td>
		  <td align="right"><b>'.duit($aklaim_).'</b></td>
	  </tr>
	  </table>';
		;
		break;
case "rptas";
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Bank</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk ORDER BY nmproduk ASC');
$metAsuransi = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name ASC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
	while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
	}
echo '</select></td></tr>

	<tr><td align="right">Nama Mitra</td><td>: <select name="grupprod" id="grupprod">
		<option value="">---Pilih Mitra---</option>';
while($met_Mitra_ = mysql_fetch_array($met_Mitra)) {
echo '<option value="'.$met_Mitra_['id'].'"'._selected($_REQUEST['idmitra'], $met_Mitra_['id']).'>'.$met_Mitra_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>
		<tr>
			<td align="right">Nama Asuransi</td>
			<td>: <select name="idas" id="idas">
							<option value="">-- Semua Asuransi --</option>';
			while($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
				echo '<option value="'.$metAsuransi_['id'].'"'._selected($_REQUEST['idas'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
			}
			echo '</select>
			</td>
		</tr>
	  <tr><td align="right">Tanggal Akad</td>
	  	  <td> : <input type="text" id="from" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="from1" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Tanggal Debitnote</td>
	  	  <td> : <input type="text" id="fromdn1" name="tgldn1" value="'.$_REQUEST['tgldn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tgldn2" value="'.$_REQUEST['tgldn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Tanggal Transaksi</td>
	  	  <td> : <input type="text" id="fromdn3" name="tgltrans1" value="'.$_REQUEST['tgltrans1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn4" name="tgltrans2" value="'.$_REQUEST['tgltrans2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Status Pembayaran </td><td> :
			  <select size="1" name="paiddata"><option value="">--- Status ---</option>
			  								  <option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
											  <option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Status Peserta </td><td> :
			  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
			  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Inforce</option>
											  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
											  <option value="Pending"'._selected($_REQUEST['statpeserta'], "Pending").'>Pending</option>
											  <option value="Lapse-Batal"'._selected($_REQUEST['statpeserta'], "Lapse-Batal").'>Lapse - Batal</option>
											  <option value="Lapse-Refund"'._selected($_REQUEST['statpeserta'], "Lapse-Refund").'>Lapse - Refund</option>
											  <option value="Lapse-Death"'._selected($_REQUEST['statpeserta'], "Lapse-Death").'>Lapse - Meninggal</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
		<tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['tgldn1']=="" AND $_REQUEST['tgldn2']=="" AND $_REQUEST['tgltrans1']=="" AND $_REQUEST['tgltrans1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal debitnote atau tanggal transaksi tidak boleh kosong<br /></div></font></blink>';	}

if ($error_1) {	echo $error_1;	}
else{

if ($_REQUEST['id_cost'])	{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_cost']=="") {	$status_client = "SEMUA CLIENT";	}
else{
$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
$status_client = $searchbank['name'];
}


if ($_REQUEST['id_polis'])		{	$dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';	}
//if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['tgldn1'])		{	$tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
$tanggaldebitnote ='<tr><td colspan="3">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
}

if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
$tanggaltransaksi ='<tr><td colspan="3">Tanggal Transaksi</td><td colspan="14">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
}

if ($_REQUEST['idas'])			{	$duabelas = 'AND fu_ajk_asuransi.id = "' . $_REQUEST['idas'] . '"';		}

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

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr>
	<td bgcolor="#FFF" colspan="2"><a href="e_report.php?er=eL_peserta_as&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&idas='.$_REQUEST['idas'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
	<td bgcolor="#FFF" colspan="2"><a href="e_report.php?er=eL_peserta_as_summary&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&idas='.$_REQUEST['idas'].'"><img src="image/excel.png" width="25" border="0"><br />Summary Produksi</a></td>
	</tr>
	<tr><td colspan="3">Nama Perusahaan</td><td colspan="14">: '.$status_client.'</td></tr>
	<tr><td colspan="3">Nama Produk</td><td colspan="14">: '.$status_produknya.'</td></tr>
	'.$tanggalakadasuransi.'
	'.$tanggaldebitnote.'
	'.$tanggaltransaksi.'
	<tr><td colspan="3">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
	<tr><td colspan="3">Status Peserta</td><td colspan="14">: '.strtoupper($_REQUEST['statpeserta']).'</td></tr>
	<tr><td colspan="3">Regional</td><td colspan="14">: '.$status_regional.'</td></tr>
	<tr><td colspan="3">Cabang</td><td colspan="14">: '.$status_cabang.'</td></tr>
	<tr><th width="1%">No</th>
	<th>Asuransi</th>
	<th>Mitra</th>
	<th>SPAJ</th>
	<th>Produk</th>
	<th>Debit Note</th>
	<th>Tanggal DN</th>
	<th>No. Reg</th>
	<th>Nama Debitur</th>
	<th>Cabang</th>
	<th>Tgl Lahir</th>
	<th>Usia</th>
	<th>Plafond</th>
	<th>JK.W</th>
	<th>Mulai Asuransi</th>
	<th>Akhir Asuransi</th>
	<th>Premi</th>
	<th>Rate Tunggal</th>
	<th>EM(%)</th>
	<th>Extra Premi</th>
	<th>Total Rate</th>
	<th>Total Premi</th>
	<th>Rate Asuransi</th>
	<th>Premi Asuransi</th>
	<th>Status</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}


$met = $database->doQuery('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.mpptype,
fu_ajk_polis.singlerate,
fu_ajk_polis.typeproduk,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgltransaksi,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.spaj,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.premi,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.mppbln,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
DATE_FORMAT(fu_ajk_peserta.input_time, "%Y-%m-%d") AS tglinput,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' '.$duabelas.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
ORDER BY fu_ajk_peserta.id_polis ASC,
		 fu_ajk_dn.id_as ASC,
		 fu_ajk_peserta.cabang ASC,
		 fu_ajk_peserta.nama ASC
LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id_peserta)
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' '.$duabelas.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, id_as, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
$cekdataAS = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));

$asuransi = mysql_fetch_array($database->doQuery("SELECT rateasuransi,nettpremi FROM fu_ajk_peserta_as WHERE id_peserta = '".$met_['id_peserta']."'"));

$mettenornya = $met_['kredit_tenor'];

	if ($met_['typeproduk']=="SPK") {
		if ($met_['mpptype']=="Y") {
			$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'] .'" AND '.$met_['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}else{
			if ($met_['tglinput'] <= "2016-08-31" AND ($met_['id_polis']=="1" OR $met_['id_polis']=="2")) {		// Produk SPK REGULER dan PERCEPATAN yg tgl input dibawah tgl 31 agustus 2016 menggunakan rate lama
				$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND usia="'.$met_['usia'].'" AND tenor="'.$met_['kredit_tenor'].'" AND status="lama" AND del IS NULL'));		// RATE PREMI
			}else{
				$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND usia="'.$met_['usia'].'" AND tenor="'.$met_['kredit_tenor'].'" AND status="baru" AND del IS NULL'));		// RATE PREMI
			}
		}
	$met_emnya_2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE spak="'.$met_['spaj'].'"  AND (status="Realisasi" OR status="Aktif") AND del IS NULL'));
	}else{
		if ($met_['mpptype']=="Y") {
			$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'] / 12 .'" AND '.$met_['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}else{
			if ($met_['tglinput'] <= "2016-08-31" AND ($met_['id_polis']=="1" OR $met_['id_polis']=="2")) {		// Produk SPK REGULER dan PERCEPATAN yg tgl input dibawah tgl 31 agustus 2016 menggunakan rate lama
				$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'].'" AND status="lama" AND del IS NULL'));		// RATE PREMI
			}else{
				$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'].'" AND status="baru" AND del IS NULL'));		// RATE PREMI
			}
		}
	$met_emnya_2 ='';
	}

$metproduknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$met_['id_polis'].'"'));
if ($met_['ext_premi']==0) {	$mametrate_ext = '';	}else{	$mametrate_ext = $met_['ext_premi'];	}
$mettotalrate = $cekdataret['rate'] * (1 + $met_emnya_2['ext_premi'] / 100);

if ($met_['type_data']=="SPK") {	$tenorpeserta = $met_['kredit_tenor'] * 12;	}else{	$tenorpeserta = $met_['kredit_tenor'];	}
if ($met_['status_peserta']=="Death") {	$_statuspeserta = "Meninggal";	}else{	$_statuspeserta = $met_['status_peserta'];	}

$metgproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$met_['nama_mitra'].'"'));
if ($met_['nama_mitra']=="") {	$groupProduk = "BUKOPIN";	}else{	$groupProduk = $metgproduk['nmproduk'];	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$met_['nmAsuransi'].'</td>
	  <td align="center">'.$groupProduk.'</td>
	  <td align="center">'.$met_['spaj'].'</td>
	  <td align="center">'.$met_['nmproduk'].'</td>
	  <td align="center">'.$met_['dn_kode'].'</td>
	  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
	  <td align="center">'.$met_['id_peserta'].'</td>
	  <td>'.$met_['nama'].'</td>
	  <td>'.$met_['cabang'].'</td>
	  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
	  <td align="center">'.$met_['usia'].'</td>
	  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
	  <td align="center">'.$tenorpeserta.'</td>
	  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
	  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
	  <td align="right">'.duit($met_['premi']).'</td>
	  <td align="center">'.$cekdataret['rate'].'</td>
	  <td align="center">'.$met_emnya_2['ext_premi'].'</td>
	  <td align="center">'.duit($met_['ext_premi']).'</td>
	  <td align="center">'.$mettotalrate.'</td>
	  <td align="right">'.duit($met_['totalpremi']).'</td>
	  <td align="right">'.$asuransi['rateasuransi'].'</td>
	  <td align="right">'.duit($asuransi['nettpremi']).'</td>
	  <td align="center">'.$met_['status_aktif'].' '.$_statuspeserta.' </td>
	  </tr>';
	$jumUP += $met_['kredit_jumlah'];
	$jumPremi += ROUND($met_['premi']);
	$jumTotalPremi += ROUND($met_['totalpremi']);
}
echo '<tr class="tr1"><td colspan="12" align="center"><b>TOTAL</b></td>
					  <td align="right"><b>'.duit($jumUP).'</td>
					  <td colspan="3"></td>
					  <td align="right"><b>'.duit($jumPremi).'</td>
					  <td colspan="4"></td>
					  <td align="right"><b>'.duit($jumTotalPremi).'</td>
	  </tr>';
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_re_bank.php?b=rptas&re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&grupprod='.$_REQUEST['grupprod'].'$idas='.$_REQUEST['id_asuransi'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}
}
break;

default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Bank</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk ORDER BY nmproduk ASC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan </td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
	while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
	}
echo '</select></td></tr>

	<tr><td align="right">Nama Mitra</td><td>: <select name="grupprod" id="grupprod">
		<option value="">---Pilih Mitra---</option>';
while($met_Mitra_ = mysql_fetch_array($met_Mitra)) {
echo '<option value="'.$met_Mitra_['id'].'"'._selected($_REQUEST['idmitra'], $met_Mitra_['id']).'>'.$met_Mitra_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>
	  <tr><td align="right">Tanggal Akad</td>
	  	  <td> : <input type="text" id="from" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="from1" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Tanggal Debitnote</td>
	  	  <td> : <input type="text" id="fromdn1" name="tgldn1" value="'.$_REQUEST['tgldn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tgldn2" value="'.$_REQUEST['tgldn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Tanggal Transaksi</td>
	  	  <td> : <input type="text" id="fromdn3" name="tgltrans1" value="'.$_REQUEST['tgltrans1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn4" name="tgltrans2" value="'.$_REQUEST['tgltrans2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="right">Status Pembayaran </td><td> :
			  <select size="1" name="paiddata"><option value="">--- Status ---</option>
			  								  <option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
											  <option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Status Peserta </td><td> :
			  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
			  								  <option value="Produksi"'._selected($_REQUEST['statpeserta'], "Produksi").'>Produksi</option>
			  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Inforce</option>
											  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
											  <option value="Pending"'._selected($_REQUEST['statpeserta'], "Pending").'>Pending</option>
											  <option value="Lapse-Batal"'._selected($_REQUEST['statpeserta'], "Lapse-Batal").'>Lapse - Batal</option>
											  <option value="Lapse-Refund"'._selected($_REQUEST['statpeserta'], "Lapse-Refund").'>Lapse - Refund</option>
											  <option value="Lapse-Death"'._selected($_REQUEST['statpeserta'], "Lapse-Death").'>Lapse - Meninggal</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['tgldn1']=="" AND $_REQUEST['tgldn2']=="" AND $_REQUEST['tgltrans1']=="" AND $_REQUEST['tgltrans1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal debitnote atau tanggal transaksi tidak boleh kosong<br /></div></font></blink>';	}
if ($error_1) {	echo $error_1;	
}else{

	if ($_REQUEST['id_cost'])	{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
	if ($_REQUEST['id_cost']=="") {	
		$status_client = "SEMUA CLIENT";	
	}else{
		$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
		$status_client = $searchbank['name'];
	}

	if ($_REQUEST['id_polis'])		{	$dua = 'AND id_polis = "'.$_REQUEST['id_polis'].'"';	}
	if ($_REQUEST['tgldn1'])		{	$tiga = 'AND tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
		$tanggaldebitnote ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Debit Note</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
	}

	if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
		$tanggaltransaksi ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Transaksi</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
	}

	if ($_REQUEST['tglakad1'])		{	$sebelas = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'"';
		$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
	}
	if ($_REQUEST['paiddata']!="")		{	$empat = ' AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
	if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
		$lima = 'AND regional = "'.$met_reg['name'].'"';
	}
	if ($_REQUEST['id_reg']=="") {	$status_regional = "SEMUA REGIONAL";	}	else{	$status_regional = $met_reg['name'];	}

	if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
		$enam = 'AND cabang = "'.$met_cab['name'].'"';
	}
	if ($_REQUEST['id_cab']=="") {	$status_cabang = "SEMUA CABANG";	}	else{	$status_cabang = $met_cab['name'];	}

	if ($_REQUEST['statpeserta'])	{
		$searchstatus = strtoupper($_REQUEST['statpeserta']);
		$status_ = explode("-", $_REQUEST['statpeserta']);
		if (!$status_[1]) {
			if ($_REQUEST['statpeserta']=="Produksi") {
				$tujuh = 'AND status_aktif IN ("Inforce", "Lapse","Pending") AND (status_peserta NOT IN ("Batal", "Req_Batal") OR status_peserta IS NULL )';
			}else{
				$tujuh = 'AND status_aktif = "'.$status_[0].'"';
			}
		}
		else{
			$tujuh = 'AND status_aktif = "'.$status_[0].'"';
			$delapan = 'AND status_peserta = "'.$status_[1].'"';
		}
	}else{
		$searchstatus = "SEMUA STATUS";
	}
	if ($_REQUEST['grupprod'])	{	$sembilan = 'AND nama_mitra = "'.$_REQUEST['grupprod'].'"';	}

	$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
	if ($_REQUEST['paiddata']=="1") {
		$searchpaid = "PAID";
		}
		elseif($_REQUEST['paiddata']=="0"){	$searchpaid = "UNPAID";
		}
		else {
		$searchpaid = "SEMUA STATUS PEMBAYARAN";
		}

	if ($_REQUEST['id_polis']=="") {	$status_produknya = "SEMUA PRODUK";	}	else{	$status_produknya = $searchproduk['nmproduk'];	}

	if($_REQUEST['paiddata']=="0"){
		$paid = 'unpaid';
	}elseif($_REQUEST['paiddata']=="1"){
		$paid = 'paid';
	}else{
		$paid = '';
	}

	echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr>
		<!--<td bgcolor="#FFF" colspan="2" align="center"><a href="e_report.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>-->
		<td bgcolor="#FFF" colspan="2" align="center"><a href="e_report.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$paid.'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
		<td bgcolor="#FFF" colspan="2" align="center"><a href="e_report.php?er=el_peserta_covering&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$paid.'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel Covering</a></td>
		<td bgcolor="#FFF" colspan="2" align="center"><a target="_blank" href="ajk_report_klien.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Klien</a></td>
		<!--<td bgcolor="#FFF" colspan="18"><a target="_blank" href="ajk_report_klien.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dnmember.png" width="30" border="0"><br />Covering Letter Bank</a></td>-->
		<td bgcolor="#FFF" colspan="2" align="center"><a target="_blank" href="ajk_re_bank.php?b=eL_clBank&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dnmember.png" width="30" border="0"><br />Covering Letter Bank</a></td>
		<td bgcolor="#FFF" colspan="2" align="center"><a target="_blank" href="graph.php?gr=gpterjaminbank&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="images/statistics.png" width="30" border="0"><br />Grafik Data Terjamin</a></td>
		<td bgcolor="#FFF" colspan="2" align="center"><a target="_blank" href="graph.php?gr=gpplafondbank&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&grupprod='.$_REQUEST['grupprod'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="images/graph.png" width="30" border="0"><br />Grafik Plafond Terjamin</a></td>
		<td bgcolor="#FFF" colspan="20">&nbsp;</td>
		</tr>
		<tr><td bgcolor="#FFF" colspan="3">Nama Perusahaan</td><td bgcolor="#FFF" colspan="20">: '.$status_client.'</td></tr>
		<tr><td bgcolor="#FFF" colspan="3">Nama Produk</td><td bgcolor="#FFF" colspan="20">: '.$status_produknya.'</td></tr>
		'.$tanggalakadasuransi.'
		'.$tanggaldebitnote.'
		'.$tanggaltransaksi.'
		<tr><td bgcolor="#FFF" colspan="3">Status Pembayaran</td><td bgcolor="#FFF" colspan="20">: '.$searchpaid.'</td></tr>
		<tr><td bgcolor="#FFF" colspan="3">Status Peserta</td><td bgcolor="#FFF" colspan="20">: '.$searchstatus.'</td></tr>
		<tr><td bgcolor="#FFF" colspan="3">Regional</td><td bgcolor="#FFF" colspan="20">: '.$status_regional.'</td></tr>
		<tr><td bgcolor="#FFF" colspan="3">Cabang</td><td bgcolor="#FFF" colspan="20">: '.$status_cabang.'</td></tr>
		<tr><th width="1%">No</th>
		<th>Asuransi</th>
		<th>Mitra</th>
		<th>SPAJ</th>
		<th>Produk</th>
		<th>Debit Note</th>
		<th>Tanggal DN</th>
		<th>No. Reg</th>
		<th>Nama Debitur</th>
		<th>Cabang</th>
		<th>Tgl Lahir</th>
		<th>Usia</th>
		<th>Plafond</th>
		<th>JK.W</th>
		<th>Mulai Asuransi</th>
		<th>Akhir Asuransi</th>
		<th>Premi</th>
		<th>Rate Tunggal</th>
		<th>EM(%)</th>
		<th>Extra Premi</th>
		<th>Total Rate</th>
		<th>Total Premi</th>
		<th>Status</th>
		</tr>';
	if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

	$query = 'SELECT * 
						FROM v_rpt_rekapbank 
						WHERE id_dn <> ""
									'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas;

	$met = $database->doQuery($query.' LIMIT '.$m.', 25');

	$totalRows = mysql_num_rows($database->doQuery($query));
	
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
	while ($met_ = mysql_fetch_array($met)) {
	$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, id_as, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
	$cekdataAS = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));

	$mettenornya = $met_['kredit_tenor'];

	if ($met_['ext_premi']==0) {	$mametrate_ext = '';	}else{	$mametrate_ext = $met_['ext_premi'];	}
	
	$mettotalrate = $met_['ratebank'] * (1 + $met_['em'] / 100);

	if ($met_['type_data']=="SPK") {
		if ($met_['mpptype']=="Y") {
			if($met_['danatalangan']==1){
				$tenorpeserta = $met_['kredit_tenor'];
			}else{
				$tenorpeserta = $met_['kredit_tenor'] * 12;
			}
		}else{
			$tenorpeserta = $met_['kredit_tenor'] * 12;
		}

	}else{
		$tenorpeserta = $met_['kredit_tenor'];
	}

	if ($met_['status_peserta']=="Death") {	$_statuspeserta = "Meninggal";	}else{	$_statuspeserta = $met_['status_peserta'];	}

	$metgproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$met_['nama_mitra'].'"'));
	if ($met_['nama_mitra']=="") {	$groupProduk = "BUKOPIN";	}else{	$groupProduk = $metgproduk['nmproduk'];	}
	if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$met_['nmAsuransi'].'</td>
		  <td align="center">'.$groupProduk.'</td>
		  <td align="center">'.$met_['spaj'].'</td>
		  <td align="center">'.$met_['nmproduk'].'</td>
		  <td align="center">'.$met_['dn_kode'].'</td>
		  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
		  <td align="center">'.$met_['id_peserta'].'</td>
		  <td>'.$met_['nama'].'</td>
		  <td>'.$met_['cabang'].'</td>
		  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
		  <td align="center">'.$met_['usia'].'</td>
		  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
		  <td align="center">'.$tenorpeserta.'</td>
		  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
		  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
		  <td align="right">'.duit($met_['premi']).'</td>
		  <td align="center">'.$met_['ratebank'].'</td>
		  <td align="center">'.$met_['em'].'</td>
		  <td align="center">'.duit($met_['ext_premi']).'</td>
		  <td align="center">'.$mettotalrate.'</td>
		  <td align="right">'.duit($met_['totalpremi']).'</td>
		  <td align="center">'.$met_['status_aktif'].' '.$_statuspeserta.' </td>
		  </tr>';
		$jumUP += $met_['kredit_jumlah'];
		$jumPremi += ROUND($met_['premi']);
		$jumTotalPremi += ROUND($met_['totalpremi']);
	}
	echo '<tr class="tr1"><td colspan="12" align="center"><b>TOTAL</b></td>
						  <td align="right"><b>'.duit($jumUP).'</td>
						  <td colspan="3"></td>
						  <td align="right"><b>'.duit($jumPremi).'</td>
						  <td colspan="4"></td>
						  <td align="right"><b>'.duit($jumTotalPremi).'</td>
		  </tr>';
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_re_bank.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&grupprod='.$_REQUEST['grupprod'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
	}
}
		;
} // switch
echo '<!--WILAYAH COMBOBOX-->
<!-- <script src="javascript/metcombo/prototype.js"></script>
<!-- <script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
/*
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			"id_reg":		{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
*/
</script>';
echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
<script type='text/javascript'>//<![CDATA[
 	$(window).load(function(){
 		$(document).ready(function () {
 			(function ($) {
 				$('#cari').keyup(function () {
 					var rex = new RegExp($(this).val(), 'i');
 					$('.caritable tr').hide();
 					$('.caritable tr').filter(function () {
 						return rex.test($(this).text());
 					}).show();

 				})

 			}(jQuery));

			$('#id_cost').change(function(){
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumer',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_polis').html(returndata);
					}
				});

				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumerregional',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_reg').html(returndata);
					}
				});

			});
			$('#id_reg').change(function(){
			var noreg = document.getElementById('id_reg').value;
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
					cache:false,
					success:function(returndata) {
						$('#id_cab').html(returndata);
					}
				});

			});

 		});
 			var idcost = document.getElementById('id_cost').value;
			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumer',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_polis').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumerregional',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_reg').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
				cache:false,
				success:function(returndata) {
					$('#id_cab').html(returndata);
				}
			});
 	});


</script>";
?>
