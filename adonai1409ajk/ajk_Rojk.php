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
$futgldn = date("Y-m-d");
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th width="80%" align="left" colspan="2">Modul Report OJK</font></th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">Report Data OJK</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td width="20%">Nama Perusahaan <font color="red">*</font></td>
	  <td> : <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {	echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';	}
echo '</select></td></tr>
	  <tr><td>Tanggal Laporan <font color="red">*</font></td><td> :
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/>
	  </td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td>
	</tr>
	</form></table></fieldset>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><td bgcolor="#FFF"colspan="27"><a href="ajk_report_fu.php?fu=ojk&tanggal1='.$_REQUEST['tanggal1'].'&cat='.$_REQUEST['cat'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
	<th width="1%" rowspan="2">No</th>
	<th width="5%" rowspan="2">SPAJ</th>
	<th width="5%" rowspan="2">Polis</th>
	<th width="10%" rowspan="2">Debit Note</th>
	<th width="5%" rowspan="2">Tanggal DN</th>
	<th width="5%" rowspan="2">No. Reg</th>
	<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
	<th rowspan="2">Tgl Lahir</th>
	<th rowspan="2">Usia</th>
	<th width="5%" colspan="4">Status Kredit</th>
	<th width="1%" rowspan="2">Premi</th>
	<th colspan="2">Biaya</th>
	<th width="1%" rowspan="2">Total Premi</th>
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

if ($_REQUEST['metreport']=="Cari") {
	if ($_REQUEST['cat']=="") {	echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';	}
	elseif ($_REQUEST['tanggal1']=="") {	echo '<div align="center"><font color="red"><blink>Tentukan tanggal laporan OJK...!!</div></font></blink>';	}
else{
//UPDATE STATUS PESERTA
$cekupdate = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Maturity" WHERE id_cost="'.$_REQUEST['cat'].'" AND status_aktif="aktif" AND vkredit_akhir < "'.$futgldn.'"');

if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';	}

$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
if ($_REQUEST['tanggal1'])					{	$dua = 'AND fu_ajk_dn.tgl_createdn <= "'.$tglawal.'" ';	}


$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 30;	}	else {	$m = 0;		}
$metojk = $database->doQuery('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nopol,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_dn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.vkredit_tgl,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.vkredit_akhir,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.regional,
fu_ajk_peserta.area,
fu_ajk_peserta.cabang
FROM
fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.dn_kode AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
WHERE fu_ajk_peserta.id !=""  '.$satu.' '.$dua.' AND fu_ajk_peserta.status_aktif = "aktif" AND fu_ajk_peserta.del IS NULL AND fu_ajk_dn.del IS NULL LIMIT ' . $m . ' , 30');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_peserta INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.dn_kode AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost WHERE fu_ajk_peserta.id_dn != "" '.$satu.' '.$dua.' AND fu_ajk_peserta.status_aktif="aktif" AND fu_ajk_peserta.del IS NULL '));
$totalRows = $totalRows[0];

while ($ojk = mysql_fetch_array($metojk)) {
//$ojkpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$ojk['id_polis'].'"'));
//$ojkdatedn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$ojk['id_dn'].'"'));

if ($ojk['status_bayar']==0) { $bayar = "Unpaid";	}else{	$bayar = "Paid";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 30).'</td>
		<td align="center">'.$ojk['spaj'].'</td>
		<td align="center">'.$ojk['nopol'].'</td>
		<td align="center">'.$ojk['id_dn'].'</td>
		<td align="center">'._convertDate($ojk['tgl_createdn']).'</td>
		<td align="center">'.$ojk['id_peserta'].'</td>
		<td>'.$ojk['nama'].'</td>
		<td align="center">'.$ojk['tgl_lahir'].'</td>
		<td align="center">'.$ojk['usia'].'</td>
		<td align="center">'.$ojk['vkredit_tgl'].'</td>
		<td align="center">'.$ojk['kredit_tenor'].'</td>
		<td align="center">'.$ojk['vkredit_akhir'].'</td>
		<td align="right">'.duit($ojk['kredit_jumlah']).'</td>
		<td align="right">'.duit($ojk['premi']).'</td>
		<td align="right">'.duit($ojk['biaya_adm']).'</td>
		<td align="right">'.duit($ojk['ext_premi']).'</td>
		<td align="right">'.duit($ojk['totalpremi']).'</td>
		<td align="center">'.$ojk['status_aktif'].'</td>
		<td align="center">'.$bayar.'</td>
		<td>'.$ojk['cabang'].'</td>
		<td>'.$ojk['regional'].'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_Rojk.php?metreport='.$_REQUEST['metreport'].'&cat='.$_REQUEST['cat'].'&tanggal1='.$_REQUEST['tanggal1'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 30);
echo '<b>Total Data DN Unpaid: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}}
?>