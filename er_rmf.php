<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['er']) {
	case "s":
		;
		break;
	case "d":
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Risk Management Fund (RMF)</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$metproduk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" ORDER BY id ASC');
echo '<form method="post" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	  <tr><td width="40%" align="right">Nama Perusahaan</td><td> : '.$metcost['name'].'</td></tr>
	  <tr><td align="right">Nama Produk</td><td> : <select name="id_polis" id="id_polis">
		  	<option value="">---Pilih Produk---</option>';
while($metproduk_ = mysql_fetch_array($metproduk)) {
echo  '<option value= "2"'._selected($metproduk_['id'], "2").' DISABLED>'.$metproduk_['nmproduk'].'</option>';
}
echo '</select></td></tr>

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
//if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_polis = "'.$_REQUEST['id_polis'].'"';	} DISABLED
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}

	$met_rmf_paid = mysql_query('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, premi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="1" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_paid_ = mysql_fetch_array($met_rmf_paid)) {
	$_metrmfnya_paid += ROUND($met_rmf_paid_['rmf']);
}

	$met_rmf_unpaid = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="0" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_unpaid_ = mysql_fetch_array($met_rmf_unpaid)) {
	$cek_rate_RMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_rmf_unpaid_['id_cost'].'" AND id_polis="'.$met_rmf_unpaid_['id_polis'].'" AND tenor="'.$met_rmf_unpaid_['kredit_tenor'].'"'));
	$mametRMF = $met_rmf_unpaid_['kredit_jumlah'] * $cek_rate_RMF['rate'] / 1000;
	$_metrmfnya_unpaid += ROUND($mametRMF);
}
	$metRMFtotal = $_metrmfnya_paid + $_metrmfnya_unpaid;
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
		<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_rmf&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
		<tr><td colspan="2">Paid</td><td colspan="14">: '.duit($_metrmfnya_paid).'</td></tr>
		<tr><td colspan="2">UnPaid</td><td colspan="14">: '.duit($_metrmfnya_unpaid).'</td></tr>
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
		</tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
	$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" AND id_polis="2" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' ORDER BY kredit_tgl ASC LIMIT '.$m.', 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id !="" AND id_polis="2" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.''));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$dua.' AND tenor="'.$met_['kredit_tenor'].'"'));

if ($met_['status_bayar']=="1") {
	$metrmfnya_paid = ROUND($met_['rmf']);
	$metrmfnya_unpaid = '';
	$met_bayar = "PAID";
}else{
	$metrmfnya_paid ='';
	$cek_rate_RMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'].'"'));
	$metrmfnya_unpaid = ROUND($met_['kredit_jumlah']) * $cek_rate_RMF['rate'] / 1000;
	//$metrmfnya_unpaid = round($met_['totalpremi']) * $cekdatapolis['rmf'] / 100;
	$met_bayar = "UNPAID";
}
	/*NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
	   $met_rmf = mysql_fetch_array($database->doQuery('SELECT id, id_cost, rmf FROM fu_ajk_polis WHERE id_cost="'.$met_['id_cost'].'" AND id="'.$met_['id_polis'].'"'));		//NILAI RMF
	   $er_rmf = $met_['totalpremi'] * $met_rmf['rmf']/100;
	   NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
	*/
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
echo '<tr class="tr1"><td colspan="8">Total</td><td align="right">'.duit($jumUP).'</td><td colspan="4"></td><td align="right">'.duit($jumPremi).'</td><td align="right">'.duit($jumRMFpaid).'</td><td align="right">'.duit($jumRMFunpaid).'</td></tr>';
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'er_rmf.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}
}
		;
} // switch

?>