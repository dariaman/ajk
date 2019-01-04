<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
$erday = date("d/m/Y");
switch ($_REQUEST['met']) {
	case "dn":
		;
		break;
	case "cn":
		echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Report U/W - cn</font></th></tr></table>';
echo '<fieldset style="padding: 2">
			<legend align="center">S e a r c h</legend>
			<table border="0" width="100%" cellpadding="3" cellspacing="1">
				<form method="post" action="ajk_Runderwriting.php?met=cn">';
		$reg = $database->doQuery('SELECT * FROM fu_ajk_costumer GROUP BY name ORDER BY name ASC');
echo '<tr><td align="right">Costumer :</td>
				<td><select size="1" name="ccostumer">
				<option value="">- - - Select Costumers - - -</option>';
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['id'].'">'.$creg['name'].'</option>';	}
echo '</select></td></tr>
			<tr><td width="40%" align="right">Per Tanggal :</td><td>
		<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="6"/> s/d
		<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="6"/>';

echo '</td></tr>
					<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td>
					</tr>
					</form></table></fieldset>';
if ($_REQUEST['metreport']=="Cari") {
	if ($_REQUEST['ccostumer']=="") {
		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';
	}elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){
		echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';
	}else{
		$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
		$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr><td bgcolor="#FFF"colspan="20"><a href="ajk_report_fu.php?fu=er_report_uw_cn&id_cost='.$_REQUEST['ccostumer'].'&tanggal1='.$tglawal.'&tanggal2='.$tglakhir.'"><img src="image/print.png" width="25" border="0"><br />Print</a></td></tr>
		<th width="1%" rowspan="2">No</th>
		<th width="1%" rowspan="2">Polis</th>
		<th rowspan="2">Client</th>
		<th width="5%" rowspan="2">Id Peserta</th>
		<th rowspan="2">Nama</th>
		<th width="5%" rowspan="2">Tgl Kredit</th>
		<th width="5%" rowspan="2">Tenor</th>
		<th width="5%" rowspan="2">UP</th>
		<th width="5%" rowspan="2">Premi</th>
		<th width="20%" colspan="3">Credit Note</th>
		<th width="5%" rowspan="2">Cabang</th>
		<th width="5%" rowspan="2">Regional</th>
		</tr>
		<tr><th>Nomor</th>
			<th>Tgl Cetak</th>
			<th>Tgl Bayar</th>
		</tr>';
if ($_REQUEST['ccostumer'])		{	$satu = 'AND fu_ajk_cn.id_cost ="' . $_REQUEST['ccostumer'] . '"';		}
if ($_REQUEST['tanggal1'])		{	$dua = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}
else {	$m = 0;		}
//$metpeserta = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' ORDER BY tgl_createcn DESC, input_by DESC LIMIT ' . $m . ' , 50');
$metpeserta = $database->doQuery('SELECT * FROM	fu_ajk_cn INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cn = fu_ajk_peserta.id_klaim
WHERE fu_ajk_cn.id_cn = fu_ajk_peserta.id_klaim AND fu_ajk_cn.id!="" '.$satu.' '.$dua.' ORDER BY fu_ajk_cn.tgl_createcn DESC, fu_ajk_cn.input_by DESC LIMIT ' . $m . ' , 50');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM fu_ajk_cn WHERE id != "" '.$satu.' '.$dua.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($erpeserta = mysql_fetch_array($metpeserta)) {
$Rpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id = "'.$erpeserta['id_nopol'].'"'));
$Rclient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id = "'.$erpeserta['id_cost'].'"'));

echo '<tr class="'.rowClass(++$i).'">
		  <td align="center" valign="top">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td align="center">'.$Rpolis['nopol'].'</td>
		  <td>'.$Rclient['name'].'</td>
		  <td>'.$erpeserta['id_peserta'].'</td>
		  <td>'.$erpeserta['nama'].'</td>
		  <td align="center">'.$erpeserta['kredit_tgl'].'</td>
		  <td align="center">'.$erpeserta['kredit_tenor'].'</td>
		  <td align="right">'.duit($erpeserta['kredit_jumlah']).'</td>
		  <td align="right">'.duit($erpeserta['totalpremi']).'</td>
		  <td align="center">'.$erpeserta['id_cn'].'</td>
		  <td align="center">'.$erpeserta['tgl_createcn'].'</td>
		  <td align="center">'.$erpeserta['tgl_byr_claim'].'</td>
		  <td align="center">'.$erpeserta['id_cabang'].'</td>
		  <td align="center">'.$erpeserta['id_regional'].'</td>
			</tr>';
}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_Runderwriting.php?met=cn&metreport=Cari&ccostumer='.$_REQUEST['ccostumer'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}

		;
		break;
	default:
		;
} // switch
?>