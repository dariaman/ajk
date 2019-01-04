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
	case "spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data SPK</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<form method="post" action="">
			<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
			<tr><td width="40%" align="right">Nama Perusahaan</td><td>: '.$metcost['name'].'</td></tr>
			<tr><td align="right">Nama Produk</td><td>: '.$metpolis['nmproduk'].' ('.$metpolis['nopol'].')</td></tr>
			<tr><td align="right">Tanggal Pemeriksaan <font color="red">*</font></td>
			  	  <td> :';print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
		print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
echo '</td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['tglcheck1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal mulai periksa tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglcheck2']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai periksa tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_spk&cat='.$q['id_cost'].'&subcat='.$q['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>No. SPK</th>
			<th>Tgl Pemeriksaan</th>
			<th>Tgl Terima SPK</th>
			<th>Tgl Lahir</th>
			<th>Usia Awal</th>
			<th>Usia Akhir</th>
			<th>Plafond</th>
			<th>Tenor</th>
			<th>TB</th>
			<th>BB</th>
			<th>SISTOLIK</th>
			<th>DIASTOLIK</th>
			<th>NADI</th>
			<th>PERNAFASAN</th>
			<th>GULA DARAH</th>
			<th>ITEM MEROKOK</th>
			<th>ITEM PERTANYAAN</th>
			<th>CATATAN SKS</th>
			<th>STATUS</th>
			<th>ANALISA DOKTER</th>
			</tr>';
if ($_REQUEST['id_cost'])		{	$satu = 'AND idcost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND tgl_periksa BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
	$met = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE id !="" AND del is NULL '.$satu.' '.$duaa.' ORDER BY tgl_periksa ASC LIMIT '.$m.', 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak_form WHERE id !="" AND del is NULL '.$satu.' '.$duaa.''));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$cekdataspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$met_['idspk'].'"'));
	$tgl_terima_spak = explode(" ", $cekdataspak['input_date']);
	$tolik = explode("/", $met_['tekanandarah']);
	$cekdatadn = mysql_fetch_array($database->doQuery('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$met_['id_dn'].'"'));

	$umur = ceil(((strtotime($met_['tgl_asuransi']) - strtotime($met_['dob'])) / (60*60*24*365.2425)));									// FORMULA USIA
	$umur_last = $umur + $met_['tenor'];

if ($met_['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			  <td>'.$met_['nama'].'</td>
			  <td>'.$met_['cabang'].'</td>
			  <td align="center">'.$cekdataspak['spak'].'</td>
			  <td align="center">'._convertDate($met_['tgl_periksa']).'</td>
			  <td align="center">'._convertDate($tgl_terima_spak[0]).'</td>
			  <td align="center">'._convertDate($met_['dob']).'</td>
			  <td align="center">'.$umur.'</td>
			  <td align="center">'.$umur_last.'</td>
			  <td align="center">'.duit($met_['plafond']).'</td>
			  <td align="center">'.$met_['tenor'].'</td>
			  <td align="center">'.$met_['tinggibadan'].'</td>
			  <td align="center">'.$met_['beratbadan'].'</td>
			  <td align="center">'.$tolik[0].'</td>
			  <td align="center">'.$tolik[1].'</td>
			  <td align="center">'.$met_['nadi'].'</td>
			  <td align="center">'.$met_['pernafasan'].'</td>
			  <td align="center">'.$met_['guladarah'].'</td>
			  <td>'.$pertanyaan6.'</td>
			  <td>'.$met_['ket6'].'</td>
			  <td>'.$met_['catatan'].'</td>
			  <td>'.$cekdataspak['status'].'</td>
			  <td>'.$met_['kesimpulan'].'</td>
			  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_peserta.php?er=spk&re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_produk='.$q['id_polis'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
		;
		break;
	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data Kepesertaan</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));

if ($q['id_cost']!="" AND $q['id_polis']=="" AND $q['level']=="10") {
//SETTING PRODUK BERDASARKAN USER//
	$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
	$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
	while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
	$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
	}
	$kolomproduk .= '</select>';
	$setproduk_met .= $_REQUEST['id_produk'];
//SETTING PRODUK BERDASARKAN USER//

//SETTING REGIONAL BERDASARKAN USER//
$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'"'));
$kolomregional .='<td>: '.$met_reg['name'].'</td>';

$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'"');
$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';
while ($met_cab_ = mysql_fetch_array($met_cab)) {
$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
}
$kolomcabang .='</select></td>';
//SETTING REGIONAL BERDASARKAN USER//
}elseif ($q['id_cost']!="" AND $q['id_polis']=="") {
	//SETTING PRODUK BERDASARKAN USER//
	$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
	$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
	while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
		$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
	}
	$kolomproduk .= '</select>';
	$setproduk_met .= $_REQUEST['id_produk'];
	//SETTING PRODUK BERDASARKAN USER//

	//SETTING REGIONAL BERDASARKAN USER//
	$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
	$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>';
while ($_met_reg = mysql_fetch_array($met_reg)) {
	$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
}
	$kolomregional .='</select></td>';

	$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'"');
	$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';
while ($met_cab_ = mysql_fetch_array($met_cab)) {
	$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
}
	$kolomcabang .='</select></td>';
	//SETTING REGIONAL BERDASARKAN USER//
}else{
	$kolomproduk .= $metpolis['nmproduk'];
	$setproduk_met .= $q['id_polis'];
//SETTING REGIONAL BERDASARKAN USER//
$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>';
while ($_met_reg = mysql_fetch_array($met_reg)) {
$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
}
$kolomregional .='</select></td>';

$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select>';
//SETTING REGIONAL BERDASARKAN USER//
}
echo '<form method="post" action="">
	<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	<table border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr><td width="40%" align="right">Nama Perusahaan</td><td>: '.$metcost['name'].'</td></tr>
	<tr><td align="right">Nama Produk</td><td>: '.$kolomproduk.'</td></tr>
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
	</td></tr>';
echo '<tr><td align="right">Regional</td>
	  '.$kolomregional.'</tr>
	  <tr><td align="right">Cabang</td>
	  '.$kolomcabang.'</td></tr>
	<tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	</table>
	</form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglakad1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglakad2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{

if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}

$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
if ($q['id_cost']!="" AND $q['id_polis']!="") {
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
}else{
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_produk'].'"'));
}
if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}else{	$searchpaid = "UNPAID";	}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<!--<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_peserta&cat='.$q['id_cost'].'&subcat='.$setproduk_met.'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>-->
	<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eR_peserta&cat='.$q['id_cost'].'&subcat='.$setproduk_met.'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'" target="_blank"><img src="image/dninvoice1.jpg" width="35" border="0"><br /> &nbsp; PDF</a></td></tr>
	<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
	<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$searchproduk['nmproduk'].'</td></tr>
	<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>
	<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
	<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.$_REQUEST['statpeserta'].'</td></tr>
	<tr><td colspan="2">Regional</td><td colspan="14">: '.$met_reg['name'].'</td></tr>
	<tr><td colspan="2">Cabang</td><td colspan="14">: '.$met_cab['name'].'</td></tr>
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
		<th>Rate Tunggal</th>
		<th>EM (%)</th>
		<th>Total Rate</th>
		<th>Total Premi</th>
	</tr>';
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
									$enam = 'AND regional = "'.$met_reg['name'].'"';
								}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
									$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
								}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$setproduk_met.'" '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del is NULL ORDER BY kredit_tgl ASC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_dn !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$setproduk_met.'" '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del is NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));

if ($met_['type_data']=="SPK") {
	$mettenornya = $met_['kredit_tenor'] / 12;
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND usia="'.$met_['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
}else{
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$duaa.' AND tenor="'.$met_['kredit_tenor'].'" AND status="baru"'));
}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$cekdatadn['dn_kode'].'</td>
	  <td align="center">'._convertDate($cekdatadn['tgl_createdn']).'</td>
	  <td>'.$met_['id_peserta'].'</td>
	  <td>'.$met_['nama'].'</td>
	  <td>'.$met_['cabang'].'</td>
	  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
	  <td align="center">'.$met_['usia'].'</td>
	  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
	  <td align="center">'.$met_['kredit_tenor'].'</td>
	  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
	  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
	  <td align="center">'.$cekdataret['rate'].'</td>
	  <td align="center">'.$met_['ext_premi'].'</td>
	  <td>'.$cekdataret['rate'].'</td>
	  <td align="right">'.duit($met_['totalpremi']).'</td>
	  </tr>';
	$jumUP += $met_['kredit_jumlah'];
	$jumPremi += ROUND($met_['totalpremi']);
}
	echo '<tr class="tr1"><td colspan="8">Total</td><td align="right">'.duit($jumUP).'</td><td colspan="6"></td><td align="right">'.duit($jumPremi).'</td></tr>';
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_peserta.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_produk='.$setproduk_met.'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
	;
} // switch

echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_reg" , {
		elements:{
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setdatacabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>