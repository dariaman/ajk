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
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data Credit Note</font></th></tr></table>';
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
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL  ORDER BY nmproduk ASC');
}
echo '</td></tr>';
//	<tr><td align="right">Nama Produk :</td>
//	<td> ';
//$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
//echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
//$metregnya = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
//echo '</td></tr>
//	<tr><td align="right">Tanggal Credit Note <font color="red">*</font> :</td>
//		<td>';print initCalendar();	print calendarBox('tgl', 'triger1', $_REQUEST['tgl']);	echo 's/d';
//		print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
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
	$metCentralCabang .= ' OR fu_ajk_cn.id_cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (fu_ajk_cn.id_cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';

}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
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
	<tr><td>Tanggal Creditnote <font color="red">*</font></td>
		<td> : <input type="text" name="tgl1" id="tgl1" class="tanggal" value="'.$_REQUEST['tgl1'].'" size="10"/> s/d
			  <input type="text" name="tgl2" id="tgl2" class="tanggal" value="'.$_REQUEST['tgl2'].'" size="10"/>
		</td>
	</tr>
	<tr><td>Type Klaim </td><td>:
		<select size="1" name="typeklaim"><option value="">--- Type Klaim ---</option>
										 <option value="Refund"'._selected($_REQUEST['typeklaim'], "Refund").'>Refund</option>
										 <option value="Death"'._selected($_REQUEST['typeklaim'], "Death").'>Meninggal</option>
										 <option value="Batal"'._selected($_REQUEST['typeklaim'], "Batal").'>Batal</option>
	</select>
	</td></tr>';
/*
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
*/
echo '<tr><td align="center"colspan="2"><input type="hidden" name="re" value="datacn"><input type="submit" name="ere" value="Cari"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['re']=="datacn") {
if ($_REQUEST['tgl1']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal awal cetak CN tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tgl2']=="") 		{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal akhir cetak CN tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1"  bgcolor="#E2E2E2">
<!--<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_dn&cat='.$q['id_cost'].'&subcat='.$q['id_polis'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&paid='.$_REQUEST['paiddata'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>-->
	<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eR_CN&cat='.$q['id_cost'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'].'&idpolis='.$_REQUEST['idpolis'].'&typeklaim='.$_REQUEST['typeklaim'].'&deb='.$q['id'].'" target="_blank"><img src="image/dninvoice1.jpg" width="35" border="0"><br /> &nbsp; PDF</a></td></tr>
		<th width="1%">No</th>
		<th width="10%">Produk</th>
		<th width="1%">Debit Note</th>
		<th width="1%">Credit Note</th>
		<th width="5%">Tanggal CN</th>
		<th width="5%">ID Peserta</th>
		<th>Nama</th>
		<th width="1%">Premi</th>
		<th width="8%">Nilai CN</th>
		<th width="8%">Tgl Bayar</th>
		<th width="1%">Type Klaim</th>
		<th width="1%">Status</th>
		<th width="8%">Cabang</th>
	</tr>';
if ($_REQUEST['tgl1'])		{	$satu = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['idpolis'])	{	$duaa = 'AND id_nopol = "'.$_REQUEST['idpolis'].'"';	}
if ($_REQUEST['typeklaim'])	{	$tiga = 'AND type_claim = "'.$_REQUEST['typeklaim'].'"';	}
/*
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$tiga = 'AND id_regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$empt = 'AND id_cabang = "'.$met_cab['name'].'"';
}
*/
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL ORDER BY tgl_createcn DESC, id_nopol DESC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' AND del is NULL GROUP BY id_cost'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_klaim="'.$met_['id'].'" AND del IS NULL'));
	$metpesertadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_cost="'.$q['id_cost'].'" AND id="'.$met_['id_dn'].'" AND del IS NULL'));
	$metproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND id="'.$met_['id_nopol'].'" AND del IS NULL'));

if ($met_['type_claim']=="Death") {
	$_typeklaimnya = "Meninggal";
}else{
	$_typeklaimnya = $met_['type_claim'];
}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td align="center">'.$metproduk['nmproduk'].'</td>
		<td align="center">'.$metpesertadn['dn_kode'].'</td>
		<td align="center">'.$met_['id_cn'].'</td>
		<td align="center">'._convertDate($met_['tgl_createcn']).'</td>
		<td align="center">'.$metpeserta['id_peserta'].'</td>
		<td>'.$metpeserta['nama'].'</td>
		<td align="right">'.duit($metpeserta['totalpremi']).'</td>
		<td align="right">'.duit($met_['total_claim']).'</td>
		<td align="center">'._convertDate($met_['tgl_byr_claim']).'</td>
		<td align="center">'.$_typeklaimnya.'</td>
		<td align="center">'.$met_['confirm_claim'].'</td>
		<td>'.$met_['id_cabang'].'</td>
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'er_cn.php?re=datacn&id_cost='.$q['id_cost'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'].'&idpolis='.$_REQUEST['idpolis'].'&typeklaim='.$_REQUEST['typeklaim'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Credit Note: <u>' . $totalRows . '</u></b></td></tr>';
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