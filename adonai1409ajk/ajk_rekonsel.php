<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['r']) {
	case "a":
		;
		break;
	case "s":
		;
		break;
	default:
if ($datelog < "2014-05-19") {
echo '<div class="harus" align="center"><font size="5">Rekonsel kepesertaan Bank Pundi belum bisa dilakukan, Silahkan kembali pada hari senin tanggal<font color="red"> 19 Mei 2014</font>. ! </font></div>';
}else{
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Data Peserta</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	<form method="post" action="">
	<tr><td width="25%">Nama Perusahaan <font color="red">*</font></td>
		<td width="30%"> : <select id="cat" name="cat" onchange="reload(this.form)">
		<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="14" ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';
}
echo '</select></td></tr>
	<tr><td>Tanggal Pencairan Debitur <font color="red">*</font></td><td> :
		<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
		<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
	</td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['metreport']=="Cari") {
	if ($_REQUEST['cat']=="") {	echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';	}
	elseif ($_REQUEST['tanggal3']=="" OR $_REQUEST['tanggal4']==""){	echo '<div align="center"><font color="red"><blink>Tanggal pencairan tidak boleh kosong...!!</div></font></blink>';	}
else{
$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawal1 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglawal2 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><td bgcolor="#FFF"colspan="27"><a href="ajk_rekonsel_report.php?cat='.$_REQUEST['cat'].'&tanggal3='.$_REQUEST['tanggal3'].'&tanggal4='.$_REQUEST['tanggal4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
	<th width="1%" rowspan="2">No</th>
	<th width="5%" rowspan="2">SPAJ</th>
	<th width="5%" rowspan="2">Polis</th>
	<th width="5%" rowspan="2">Debit Note</th>
	<th width="5%" rowspan="2">Tanggal DN</th>
	<th width="5%" rowspan="2">No. Reg</th>
	<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
	<th rowspan="2">Tgl Lahir</th>
	<th rowspan="2">Usia</th>
	<th width="5%" colspan="4">Status Kredit</th>
	<th width="1%" rowspan="2">Premi</th>
	<th width="1%" rowspan="2">Disc</th>
	<th colspan="2">Biaya</th>
	<th width="1%" rowspan="2">Total Premi</th>
	<th rowspan="2">Status</th>
	<th rowspan="2">Peserta</th>
	<th rowspan="2">Type</th>
	<th rowspan="2">Cabang</th>
	<th rowspan="2">Regional</th>
	</tr>
	<tr>
	<th>Kredit Awal</th>
	<th>Tenor</th>
	<th>Kredit Akhir</th>
	<th>Jumlah</th>
	<th>Adm</th>
	<th>Ext. Premi</th>
	</tr>';
if ($_REQUEST['cat'])						{	$satu = 'AND id_cost = "' . $_REQUEST['cat'] . '"';
if ($_REQUEST['tanggal3'])					{	$dua = 'AND vkredit_tgl BETWEEN "'.$tglawal1.'" AND "'.$tglawal2.'" '; }
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$rekonsel = $database->doQuery('SELECT * FROM fu_ajk_peserta_rekonsel WHERE fu_ajk_peserta_rekonsel.id != "" '.$satu.' '.$dua.' ORDER BY vkredit_tgl DESC, id_dn DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_rekonsel WHERE fu_ajk_peserta_rekonsel.id != "" '.$satu.' '.$dua.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($rekonsel)) {
$metpolisnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td align="center">'.$metpolisnya['nopol'].'</td>
		  <td align="center">'.$fudata['id_dn'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_createdn']).'</td>
		  <td align="center">'.$fudata['id_peserta'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'.$fudata['tgl_lahir'].'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'.$fudata['kredit_tgl'].'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.$fudata['kredit_akhir'].'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['disc_premi']).'</td>
		  <td align="right">'.duit($fudata['biaya_adm']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$statusnya.'</td>
		  <td align="center">'.$fudata['status_aktif'].'</td>
		  <td align="center">'.$fudata['status_peserta'].'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_rekonsel.php?cat='.$_REQUEST['cat'].'&
										tanggal3='.$_REQUEST['tanggal3'].'&
										tanggal4='.$_REQUEST['tanggal4'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
	echo '<b>Total Data Peserta Rekonsel: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
echo '</table>';
}
}
}
		;
} // switch

?>
