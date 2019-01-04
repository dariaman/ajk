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
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data Debit Note</font></th></tr></table>';
switch ($_REQUEST['er']) {
	case "s":
		;
		break;
	case "d":
		;
		break;
	default:
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="20%" align="right">Nama Perusahaan :</td>
	  <td width="30%">';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
echo '</td></tr>
		<tr><td align="right">Nama Produk :</td>
			<td> ';
$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
$metregnya = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
echo '</td></tr>
		<tr><td align="right">Tanggal Debit Note <font color="red">*</font> :</td>
			<td>';print initCalendar();	print calendarBox('tgl', 'triger1', $_REQUEST['tgl']);	echo 's/d';
				  print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
echo '</td></tr>
		<tr><td align="right">Status Pembayaran :</td><td>
		<select size="1" name="paiddata"><option value="">--- Status ---</option>
										 <option value="paid"'._selected($_REQUEST['paiddata'], "paid").'>Paid</option>
										 <option value="unpaid"'._selected($_REQUEST['paiddata'], "unpaid").'>Unpaid</option>
	</select>
	</td></tr>';
		$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
		echo '<tr><td align="right">Regional :</td>
				<td id="polis_rate"><select name="id_reg" id="id_reg">
				<option value="">-- Pilih Regional --</option>';
		while ($_met_reg = mysql_fetch_array($met_reg)) {
			echo '<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
		}
		echo '</select></td></tr>
			  <tr><td align="right">Cabang :</td>
				<td id="polis_rate"><select name="id_cab" id="id_cab">
				<option value="">-- Pilih Cabang --</option>
				</select></td></tr>
	<tr><td align="center"colspan="2"><input type="hidden" name="re" value="datadn"><input type="submit" name="ere" value="Cari"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['re']=="datadn") {
if ($_REQUEST['tgl']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal awal cetak DN tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tgl2']=="") 		{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal akhir cetak DN tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
<!--<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_dn&cat='.$q['id_cost'].'&subcat='.$q['id_polis'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&paid='.$_REQUEST['paiddata'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>-->
	<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eR_DN&cat='.$q['id_cost'].'&subcat='.$q['id_polis'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&paid='.$_REQUEST['paiddata'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'" target="_blank"><img src="image/dninvoice1.jpg" width="35" border="0"><br /> &nbsp; PDF</a></td></tr>
		<th width="1%">No</th>
		<th>Debit Note</th>
		<th width="10%">Tanggal DN</th>
		<th width="10%">jData</th>
		<th width="10%">Total Premi</th>
		<th width="10%">Pembayaran</th>
		<th width="10%">Tgl Bayar</th>
		<th width="15%">Regional</th>
		<th width="15%">Cabang</th>
	</tr>';
if ($_REQUEST['tgl'])		{	$satu = 'AND tgl_createdn BETWEEN "'.$_REQUEST['tgl'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paiddata'])	{	$duaa = 'AND dn_status = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
								$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
							}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
								$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
							}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_nopol="'.$q['id_polis'].'" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL ORDER BY tgl_createdn ASC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id !="" AND id_nopol="'.$q['id_polis'].'" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$metpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, id_dn, COUNT(nama) AS jNama FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND id_dn="'.$met_['id'].'" GROUP BY id_dn'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$met_['dn_kode'].'</td>
		  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
		  <td align="center">'.$metpeserta['jNama'].' Peserta</td>
		  <td align="right">'.duit($met_['totalpremi']).' &nbsp; </td>
		  <td align="center">'.$met_['dn_status'].'</td>
		  <td align="center">'._convertDate($met_['tgl_dn_paid']).'</td>
		  <td>'.$met_['id_regional'].'</td>
		  <td>'.$met_['id_cabang'].'</td>
		  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_dn.php?re=datadn&id_cost='.$q['id_cost'].'&id_polis='.$q['id_polis'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&paiddata='.$_REQUEST['paiddata'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Debit Note: <u>' . $totalRows . '</u></b></td></tr>';
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