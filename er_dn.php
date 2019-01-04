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
<tr><td width="10%">Nama Perusahaan</td>
	  <td>: ';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
if(!empty($mametProdukUser)){
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
}else{
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL ORDER BY nmproduk ASC');
}
echo '</td></tr>';
//		<tr><td align="right">Nama Produk :</td>
//			<td> ';
//$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
//echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
//$metregnya = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
//echo '</td></tr>
//		<tr><td align="right">Tanggal Debit Note <font color="red">*</font> :</td>
//			<td>';print initCalendar();	print calendarBox('tgl', 'triger1', $_REQUEST['tgl']);	echo 's/d';
//				  print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
//echo '</td></tr>
if ($q['cabang']=="PUSAT") {
	$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
	$_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
while($userCabang_ = mysql_fetch_array($userCabang)) {
	$_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
}
	$_userCabang .= '</select>';
	$QueryCabang = 'AND cabang !=""';
	//$QueryInput = 'AND input_by !="" AND input_by IS NULL';
}else{
	$_userCabang = $q['cabang'];
	$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
	$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
}

$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR fu_ajk_dn.id_cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (fu_ajk_dn.id_cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';

}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
}
		//CEK DATA CABANG CENTRAL;

echo '<tr><td>Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
	  <!--<tr><td align="right">Nama Produk</td><td>: '.$kolomproduk.'</td></tr>-->
	  <tr><td>Nama Produk</td><td>: <select name="idpolis">
	  <option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
	echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td>Tanggal Debitnote <font color="red">*</font></td>
		<td> : <input type="text" name="tgl1" id="tgl1" class="tanggal" value="'.$_REQUEST['tgl1'].'" size="10"/> s/d
			  <input type="text" name="tgl2" id="tgl2" class="tanggal" value="'.$_REQUEST['tgl2'].'" size="10"/>
		</td>
	</tr>
	<tr><td>Status Pembayaran </td><td>:
		<select size="1" name="paiddata"><option value="">--- Status ---</option>
										 <option value="paid"'._selected($_REQUEST['paiddata'], "paid").'>Lunas</option>
										 <option value="unpaid"'._selected($_REQUEST['paiddata'], "unpaid").'>Belum Lunas</option>
										 <option value="Kurang Bayar"'._selected($_REQUEST['paiddata'], "Kurang Bayar").'>Kurang Bayar</option>
	</select>
	</td></tr>';
//		$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
//		echo '<tr><td>Regional</td>
//				<td id="polis_rate">: <select name="id_reg" id="id_reg">
//				<option value="">-- Pilih Regional --</option>';
//		while ($_met_reg = mysql_fetch_array($met_reg)) {
//			echo '<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
//		}
//		echo '</select></td></tr>
//			  <tr><td>Cabang</td>
//				<td id="polis_rate">: <select name="id_cab" id="id_cab">
//				<option value="">-- Pilih Cabang --</option>
//				</select></td></tr>
echo '<tr><td align="center"colspan="2"><input type="hidden" name="re" value="datadn"><input type="submit" name="ere" value="Cari"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['re']=="datadn") {
if ($_REQUEST['tgl1']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal awal cetak DN tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tgl2']=="") 		{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal akhir cetak DN tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
<!--<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_dn&cat='.$q['id_cost'].'&subcat='.$q['id_polis'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&paid='.$_REQUEST['paiddata'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>-->
	<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eR_DN&cat='.$q['id_cost'].'&subcat='.$_REQUEST['idpolis'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'].'&paid='.$_REQUEST['paiddata'].'&deb='.$q['id'].'" target="_blank"><img src="image/dninvoice1.jpg" width="35" border="0"><br /> &nbsp; PDF</a></td></tr>
		<th width="1%">No</th>
		<th>Produk</th>
		<th>Debit Note</th>
		<th width="10%">Tanggal DN</th>
		<th width="10%">Debitur</th>
		<th width="10%">Total Premi</th>
		<th width="10%">Total Dibayar</th>
		<th width="10%">Pembayaran</th>
		<th width="1%">Tanggal Pelunasan</th>
		<th width="15%">Cabang</th>
	</tr>';
if ($_REQUEST['tgl1'])		{	$satu = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paiddata'] == "paid") {
	$duaa = 'AND (fu_ajk_dn.dn_status = "paid" OR fu_ajk_dn.dn_status = "Lunas")';
}elseif ($_REQUEST['paiddata'] == "unpaid") {
	$duaa = 'AND fu_ajk_dn.dn_status = "unpaid"';
}elseif ($_REQUEST['paiddata'] == "Kurang Bayar") {
	$duaa = 'AND fu_ajk_dn.dn_status = "Kurang Bayar"';
}else{	}

if ($_REQUEST['idpolis'])	{	$tiga = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['idpolis'].'"';	}
/*
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
								$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
							}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
								$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
							}
*/
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
//$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL ORDER BY tgl_createdn DESC LIMIT '.$m.', 25');		// QUEYR LAMA
$met = $database->doQuery('SELECT
fu_ajk_dn.id,
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_polis.nmproduk,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.dn_total,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.id_cabang
FROM
fu_ajk_dn
INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_dn.id != "" AND fu_ajk_dn.id_cost = "'.$q['id_cost'].'"  '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id
ORDER BY fu_ajk_dn.tgl_createdn DESC, fu_ajk_dn.id_nopol DESC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_dn.id) FROM fu_ajk_dn
WHERE fu_ajk_dn.id != "" AND fu_ajk_dn.id_cost = "'.$q['id_cost'].'"  '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
//echo('SELECT id_cost, id_polis, id_dn, COUNT(nama) AS jNama FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_dn="'.$met_['id'].'" AND status_aktif ="Lapse" AND status_peserta !="Batal" GROUP BY id_dn');
//echo '<br />';
$metpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, id_dn, COUNT(nama) AS jNama FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_dn="'.$met_['id'].'" GROUP BY id_dn'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$met_['nmproduk'].'</td>
		  <td align="center">'.$met_['dn_kode'].'</td>
		  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
		  <td align="center">'.$metpeserta['jNama'].' Peserta</td>
		  <td align="right">'.duit($met_['totalpremi']).' &nbsp; </td>
		  <td align="right">'.duit($met_['dn_total']).' &nbsp; </td>
		  <td align="center">'.$met_['dn_status'].'</td>
		  <td align="center">'._convertDate($met_['tgl_dn_paid']).'</td>
		  <td>'.$met_['id_cabang'].'</td>
		  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_dn.php?re=datadn&id_cost='.$q['id_cost'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'].'&paiddata='.$_REQUEST['paiddata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
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
<!--datepicker-->
<link type="text/css" href="includes/Rjs/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="includes/Rjs/jquery-1.3.2.js"></script>
<script type="text/javascript" src="includes/Rjs/ui.core.js"></script>
<script type="text/javascript" src="includes/Rjs/ui.datepicker.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
        $(".tanggal").datepicker({
		dateFormat  : "dd/mm/yy",
          changeMonth : true,
          changeYear  : true
        });
      });
    </script>
<!--datepicker-->