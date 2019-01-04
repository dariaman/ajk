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
switch ($_REQUEST['b']) {
	case "x":
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
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Bank</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan </td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
	while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
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
	  <tr><td align="right">Tanggal Debit Note<font color="red">*</font></td>
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
if ($_REQUEST['tgldn1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal debit note tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tgldn2']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal debit note tidak boleh kosong</div></font></blink>';	}
//if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
//if ($_REQUEST['tglakad1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
//if ($_REQUEST['tglakad2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
//if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
if ($error_1) {	echo $error_1;	}
else{

if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['tgldn1'])		{	$tigaa = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
$tanggaldebitnote ='<tr><td colspan="2">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
}
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
}
if ($_REQUEST['paiddata'])		{	$empt = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$lima = 'AND fu_ajk_peserta.status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}


$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}else{	$searchpaid = "UNPAID";	}
if ($_REQUEST['id_polis']=="") {	$status_produknya = "SEMUA PRODUK";	}
else{	$status_produknya = $searchproduk['nmproduk'];	}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr>
	<td bgcolor="#FFF"colspan="2"><a href="e_report.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
	<td bgcolor="#FFF"colspan="17"><a target="_blank" href="ajk_report_klien.php?er=eL_peserta&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Klien</a></td>
	</tr>
	<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
	<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$status_produknya.'</td></tr>
	'.$tanggalakadasuransi.'
	'.$tanggaldebitnote.'
	<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
	<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.$_REQUEST['statpeserta'].'</td></tr>
	<tr><td colspan="2">Regional</td><td colspan="14">: '.$met_reg['name'].'</td></tr>
	<tr><td colspan="2">Cabang</td><td colspan="14">: '.$met_cab['name'].'</td></tr>
	<tr><th width="1%">No</th>
	<th>Asuransi</th>
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
	<th>EM (%)</th>
	<th>Total Rate</th>
	<th>Total Premi</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT
fu_ajk_peserta.id_dn,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.id_klaim,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.spaj,
fu_ajk_peserta.type_data,
fu_ajk_peserta.nama,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.premi,
fu_ajk_peserta.disc_premi,
fu_ajk_peserta.bunga,
fu_ajk_peserta.biaya_adm,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.rmf,
fu_ajk_peserta.ket,
fu_ajk_peserta.status_medik,
fu_ajk_peserta.status_bayar,
fu_ajk_peserta.tgl_bayar,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.regional,
fu_ajk_peserta.area,
fu_ajk_peserta.cabang,
fu_ajk_peserta.tgl_laporan,
fu_ajk_peserta.del,
fu_ajk_dn.tgl_createdn
FROM
fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id WHERE fu_ajk_peserta.id_dn !="" AND fu_ajk_peserta.del is NULL '.$satu.' '.$duaa.' '.$tigaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' ORDER BY fu_ajk_peserta.id_polis ASC, fu_ajk_peserta.kredit_tgl ASC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id)	FROM fu_ajk_peserta INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id WHERE fu_ajk_peserta.id !="" AND fu_ajk_peserta.del is NULL '.$satu.' '.$duaa.' '.$tigaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, id_as, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
$cekdataAS = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));

if ($met_['type_data']=="SPK") {
$mettenornya = $met_['kredit_tenor'] / 12;
$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND usia="'.$met_['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI

$met_emnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nama="'.$met_['nama'].'" AND dob="'.$met_['tgl_lahir'].'"'));
$met_emnya_2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$met_emnya['idspk'].'"'));

}else{
$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_polis'].'" AND tenor="'.$met_['kredit_tenor'].'" AND status="baru"'));
}

$metproduknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$met_['id_polis'].'"'));
if ($met_['ext_premi']==0) {	$mametrate_ext = '';	}else{	$mametrate_ext = $met_['ext_premi'];	}
$mettotalrate = $cekdataret['rate'] * (1 + $met_emnya_2['ext_premi'] / 100);

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$cekdataAS['name'].'</td>
	  <td align="center">'.$metproduknya['nmproduk'].'</td>
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
	  <td align="right">'.duit($met_['premi']).'</td>
	  <td align="center">'.$cekdataret['rate'].'</td>
	  <td align="center">'.duit($met_emnya_2['ext_premi']).'</td>
	  <td align="center">'.$mettotalrate.'</td>
	  <td align="right">'.duit($met_['totalpremi']).'</td>
	  </tr>';
	$jumUP += $met_['kredit_jumlah'];
	$jumPremi += ROUND($met_['premi']);
	$jumTotalPremi += ROUND($met_['totalpremi']);
}
echo '<tr class="tr1"><td colspan="8" align="center"><b>TOTAL</b></td>
					  <td align="right"><b>'.duit($jumUP).'</td>
					  <td colspan="3"></td>
					  <td align="right"><b>'.duit($jumPremi).'</td>
					  <td colspan="3"></td>
					  <td align="right"><b>'.duit($jumTotalPremi).'</td>
	  </tr>';
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_re_bank.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
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