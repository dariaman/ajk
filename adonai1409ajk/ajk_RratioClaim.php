<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
switch ($_REQUEST['eR']) {
	case "Rpremi":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th align="left">Modul Ratio Claim - Rekap Premi</font></th></tr></table>';
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}
echo '<fieldset style="padding: 5">
	<legend align="center">Rasio - Data Peserta</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td width="25%">Nama Perusahaan</td>
			<td width="30%"> : <select id="cat" name="cat" onchange="reload(this.form)">
			<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{	echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';	}
}
echo '</select></td></tr>
	<tr><td width="10%">Nomor Polis</td>
		<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {
if($noticia['id']==$_REQUEST['subcat']){echo '<option selected value="'.$noticia['id'].'">'.$noticia['nopol'].'</option><BR>';}
else{echo  '<option value="'.$noticia['id'].'">'.$noticia['nopol'].'</option>';}
}
echo '</select></td></tr>
		<tr><td>Tanggal DN dibuat <font color="red">*</font></td><td> :
		<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
		<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
		</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';
if ($_REQUEST['metreport']=="Cari") {
	if (!$_REQUEST['tanggal3']) {	echo '<center><font color="red">Tentukan tanggal DN !</font></center>';
	}else{
		$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawaldn = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
		$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglakhirdn = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
		if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_dn.id_cost = "' . $_REQUEST['cat'] . '"';
			$carisatu = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
			$metcarisatu .='<tr><td width="15%">Klient</td><td width="1%">:</td><td>'.$carisatu['name'].'</td></tr>';	}

		if ($_REQUEST['subcat'])					{	$dua = 'AND fu_ajk_dn.id_nopol = "' . $_REQUEST['subcat'] . '"';
			$caridua = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
			$metcaridua .='<tr><td width="15%">Nomor Polis</td><td width="1%">:</td><td>'.$caridua['nopol'].'</td></tr>';	}

		if ($_REQUEST['tanggal3'])					{	$tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$tglawaldn.'" AND "'.$tglakhirdn.'" ';
			$metcariempat .='<tr><td width="15%">Tanggal Dn dibuat</td><td width="1%">:</td><td>'.$_REQUEST['tanggal3'].' - '.$_REQUEST['tanggal4'].'</td></tr>';	}

$rasio_peserta = $database->doQuery('SELECT
fu_ajk_costumer.`name`,
fu_ajk_dn.id_nopol AS Rpolis,
fu_ajk_polis.nopol,
count(fu_ajk_dn.dn_kode) AS jDN,
SUM(fu_ajk_dn.totalpremi) AS jTPremi
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
WHERE
fu_ajk_dn.id !="" '.$tiga.' '.$dua.' '.$satu.' AND
fu_ajk_dn.del IS NULL
GROUP BY
fu_ajk_dn.id_nopol ORDER BY fu_ajk_dn.id_cost ASC');


echo '<table border="0" width="50%" cellpadding="5" cellspacing="1"  bgcolor="#E2E2E2" align="center">
	  <tr><th>Client</th>
	  	  <th width="20%">Polis</th>
	  	  <th width="10%">Jumlah DN</th>
	  	  <th width="15%">total premi</th>
	 </tr>';
while ($metrasio = mysql_fetch_array($rasio_peserta)) {
$metcostnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metrasio['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  	  <td>'.$metrasio['name'].'</td>
	  	  <td align="center">'.$metrasio['nopol'].'</td>
	  	  <td align="center">'.duit($metrasio['jDN']).'</td>
	  	  <td align="right">'.duit($metrasio['jTPremi']).'</td>
	  </tr>';
}
echo '</table>';
}}
		;
		break;
	case "Rclaim":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th align="left">Modul Ratio Claim - Rekap Claim</font></th></tr></table>';
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}
echo '<fieldset style="padding: 5">
			<legend align="center">Rasio - Data Peserta (Klaim)</legend>
			<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
				<form method="post" action="">
				<tr><td width="25%">Nama Perusahaan</td>
					<td width="30%"> : <select id="cat" name="cat" onchange="reloadcn(this.form)">
					<option value="">---Select Company---</option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{	echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';	}
}
echo '</select></td></tr>
			<tr><td width="10%">Nomor Polis</td>
				<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
		echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {
if($noticia['id']==$_REQUEST['subcat']){echo '<option selected value="'.$noticia['id'].'">'.$noticia['nopol'].'</option><BR>';}
else{echo  '<option value="'.$noticia['id'].'">'.$noticia['nopol'].'</option>';}
}
echo '</select></td></tr>
				<tr><td>Tanggal CN dibuat <font color="red">*</font></td><td> :
				<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
				<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
				</td></tr>
				<tr><td align="center" colspan="2"><input type="submit" name="metreportcn" value="Cari" class="button"></td></tr>
				</form></table></fieldset>';
if ($_REQUEST['metreportcn']=="Cari") {
	if (!$_REQUEST['tanggal3']) {	echo '<center><font color="red">Tentukan tanggal CN !</font></center>';
	}else{
		$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawaldn = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
		$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglakhirdn = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
		if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_cn.id_cost = "' . $_REQUEST['cat'] . '"';
			$carisatu = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
			$metcarisatu .='<tr><td width="15%">Klient</td><td width="1%">:</td><td>'.$carisatu['name'].'</td></tr>';	}

		if ($_REQUEST['subcat'])					{	$dua = 'AND fu_ajk_cn.id_nopol = "' . $_REQUEST['subcat'] . '"';
			$caridua = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
			$metcaridua .='<tr><td width="15%">Nomor Polis</td><td width="1%">:</td><td>'.$caridua['nopol'].'</td></tr>';	}

		if ($_REQUEST['tanggal3'])					{	$tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$tglawaldn.'" AND "'.$tglakhirdn.'" ';
			$metcariempat .='<tr><td width="15%">Tanggal Dn dibuat</td><td width="1%">:</td><td>'.$_REQUEST['tanggal3'].' - '.$_REQUEST['tanggal4'].'</td></tr>';	}

$rasio_peserta = $database->doQuery('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nopol,
COUNT(fu_ajk_cn.id_cn) jCN,
SUM(fu_ajk_cn.total_claim) jNilai
FROM
fu_ajk_cn
INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.id !="" AND
fu_ajk_cn.type_claim = "Death" '.$tiga.' '.$dua.' '.$satu.' AND
fu_ajk_cn.del IS NULL
GROUP BY fu_ajk_cn.id_nopol	ORDER BY fu_ajk_cn.id_cost ASC');


echo '<table border="0" width="50%" cellpadding="5" cellspacing="1"  bgcolor="#E2E2E2" align="center">
	  <tr><th width="1%">No</th>
	  	  <th>Client</th>
	  	  <th width="20%">Polis</th>
	  	  <th width="10%">Jumlah CN</th>
	  	  <th width="15%">total Nilai</th>
	 </tr>';
while ($metrasio = mysql_fetch_array($rasio_peserta)) {
	$metcostnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metrasio['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  	  <td>'.++$no.'</td>
		  	  <td>'.$metrasio['name'].'</td>
		  	  <td align="center">'.$metrasio['nopol'].'</td>
		  	  <td align="center">'.duit($metrasio['jCN']).'</td>
		  	  <td align="right">'.duit($metrasio['jNilai']).'</td>
		  </tr>';
}
		echo '</table>';
	}}
		;
		break;
	case "Rtenor":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th align="left">Modul Rata-Rata Tenor</font></th></tr></table>';
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
echo "Data Error";	exit;
}
echo '<fieldset style="padding: 5">
	<legend align="center">Rata-Rata Tenor</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	<form method="post" action="">
	<tr><td width="25%">Nama Perusahaan</td>
		<td width="30%"> : <select id="cat" name="cat" onchange="reloadtenor(this.form)">
		<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{	echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';	}
}
echo '</select></td></tr>
			<tr><td width="10%">Nomor Polis</td>
				<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {
if($noticia['id']==$_REQUEST['subcat']){echo '<option selected value="'.$noticia['id'].'">'.$noticia['nopol'].'</option><BR>';}
else{echo  '<option value="'.$noticia['id'].'">'.$noticia['nopol'].'</option>';}
}
echo '</select></td></tr>
	<tr><td>Tanggal DN dibuat <font color="red">*</font></td><td> :
		<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
		<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
	</td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="met_r_Tenor" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['met_r_Tenor']=="Cari") {
	if (!$_REQUEST['tanggal3']) {	echo '<center><font color="red">Tentukan tanggal DN !</font></center>';
	}else{
		$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawaldn = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
		$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglakhirdn = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
		if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';
			$carisatu = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
			$metcarisatu .='<tr><td width="15%">Klient</td><td width="1%">:</td><td>'.$carisatu['name'].'</td></tr>';	}

		if ($_REQUEST['subcat'])					{	$dua = 'AND fu_ajk_peserta.id_nopol = "' . $_REQUEST['subcat'] . '"';
			$caridua = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
			$metcaridua .='<tr><td width="15%">Nomor Polis</td><td width="1%">:</td><td>'.$caridua['nopol'].'</td></tr>';	}

		if ($_REQUEST['tanggal3'])					{	$tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$tglawaldn.'" AND "'.$tglakhirdn.'" ';
			$metcariempat .='<tr><td width="15%">Tanggal Dn dibuat</td><td width="1%">:</td><td>'.$_REQUEST['tanggal3'].' - '.$_REQUEST['tanggal4'].'</td></tr>';	}

$rasio_tenor = $database->doQuery('SELECT `"c"`.`name` AS client, `"p"`.nopol AS polis, AVG( fu_ajk_peserta.kredit_tenor ) AS JtENOR, SUM( fu_ajk_peserta.totalpremi ) AS JPremi
FROM fu_ajk_peserta
LEFT JOIN fu_ajk_polis AS `"p"` ON fu_ajk_peserta.id_polis = `"p"`.id
LEFT JOIN fu_ajk_costumer AS `"c"` ON fu_ajk_peserta.id_cost = `"c"`.id
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost
AND fu_ajk_peserta.id_dn = fu_ajk_dn.dn_kode
WHERE fu_ajk_peserta.id_cost = "14"
AND fu_ajk_peserta.status_aktif != "Batal"
AND fu_ajk_peserta.del IS NULL
AND fu_ajk_dn.tgl_createdn
BETWEEN "2014-01-01"
AND "2014-01-31"
GROUP BY fu_ajk_peserta.id_polis');


echo '<table border="0" width="50%" cellpadding="5" cellspacing="1"  bgcolor="#E2E2E2" align="center">
	  <tr><th>Client</th>
	  	  <th width="20%">Polis</th>
	  	  <th width="20%">Premi</th>
	  	  <th width="10%">Rata-Rata Tenor</th>
	 </tr>';
while ($metrasio = mysql_fetch_array($rasio_tenor)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  	  <td>'.$metrasio['client'].'</td>
		  	  <td align="center">'.$metrasio['polis'].'</td>
		  	  <td align="right">'.duit($metrasio['JPremi']).'</td>
		  	  <td align="right">'.duit($metrasio['JtENOR']).'</td>
		  </tr>';
}
		echo '</table>';
	}}
	;
	break;

	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th align="left">Report Analisa Klaim Ratio</font></th></tr></table>';
echo '<fieldset style="padding: 5">
	<legend align="center">Rasio - Data Peserta</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td width="25%">Nama Perusahaan</td>
			<td width="30%"> : <select id="cat" name="cat">
			<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {	echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';		}
echo '</select></td></tr>
		<tr><td>Tanggal Mulai Asuransi <font color="red">*</font></td><td> :
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
			<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>
		</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="metratio" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['metratio']=="Cari") {
	if (!$_REQUEST['tanggal1']) {	echo '<center><font color="red">Tentukan tanggal mulai asuransi !</font></center>';	}
	else	{
	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tgl_1 = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tgl_2 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];

if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';	}
if ($_REQUEST['tanggal1'])					{	$dua = 'AND fu_ajk_peserta.vkredit_tgl BETWEEN "'.$tgl_1.'" AND "'.$tgl_2.'" ';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$metratioclaim = $database->doQuery('SELECT fu_ajk_peserta.id,
											fu_ajk_peserta.id_cost,
											fu_ajk_peserta.id_polis,
											fu_ajk_peserta.id_dn,
											fu_ajk_peserta.id_klaim,
											fu_ajk_peserta.id_peserta,
											fu_ajk_peserta.nama,
											fu_ajk_peserta.kredit_tgl,
											fu_ajk_peserta.vkredit_tgl,
											fu_ajk_peserta.kredit_tenor,
											fu_ajk_peserta.kredit_akhir,
											fu_ajk_peserta.vkredit_akhir,
											fu_ajk_peserta.totalpremi,
											fu_ajk_peserta.status_aktif,
											fu_ajk_peserta.status_peserta,
											fu_ajk_peserta.del
											FROM fu_ajk_peserta
											WHERE fu_ajk_peserta.id !="" '.$satu.' '.$dua.' AND status_aktif !="Batal" AND status_aktif !="Pending" AND del IS NULL ORDER BY status_aktif ASC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_peserta WHERE fu_ajk_peserta.id !="" '.$satu.' '.$dua.' AND status_aktif !="Batal" AND status_aktif !="Pending" AND del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
echo '<a href="ajk_report_fu.php?fu=ratioklaim&cat='.$_REQUEST['cat'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a>';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1"  bgcolor="#E2E2E2" align="center">
	  <tr><th width="1%">No</th>
	  	  <th>Perusahaan</th>
	  	  <th width="5%">Polis</th>
	  	  <th width="5%">DN</th>
	  	  <th width="5%">CN</th>
	  	  <th width="6%">ID Peserta</th>
	  	  <th width="15%">Nama</th>
	  	  <th width="8%">Mulai Kredit</th>
	  	  <th width="5%">Tenor</th>
	  	  <th width="8%">Akhir Kredit</th>
	  	  <th width="8
	  	  %">Total Premi</th>
	  	  <th width="5%">Status</th>
	  	  <th width="5%">Type</th>
	  	  <th width="5%">Tgl Refund</th>
	 </tr>';
while ($metratioclaim_ = mysql_fetch_array($metratioclaim)) {
$met__1 = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$metratioclaim_['id_cost'].'"'));
$met__2 = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$metratioclaim_['id_polis'].'"'));
$met__3 = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, id_peserta, id_cn, tgl_createcn FROM fu_ajk_cn WHERE id_cost="'.$metratioclaim_['id_cost'].'" AND id_nopol="'.$metratioclaim_['id_polis'].'" AND id_cn="'.$metratioclaim_['id_klaim'].'" AND id_peserta="'.$metratioclaim_['id_peserta'].'"'));

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td>'.$met__1['name'].'</td>
	  <td align="center">'.$met__2['nopol'].'</td>
	  <td align="center">'.$metratioclaim_['id_dn'].'</td>
	  <td align="center">'.$metratioclaim_['id_klaim'].'</td>
	  <td align="center">'.$metratioclaim_['id_peserta'].'</td>
	  <td>'.$metratioclaim_['nama'].'</td>
	  <td align="center">'.$metratioclaim_['vkredit_tgl'].'</td>
	  <td align="center">'.$metratioclaim_['kredit_tenor'].'</td>
	  <td align="center">'.$metratioclaim_['vkredit_akhir'].'</td>
	  <td align="right">'.duit($metratioclaim_['totalpremi']).'</td>
	  <td align="center">'.$metratioclaim_['status_aktif'].'</td>
	  <td align="center">'.$metratioclaim_['status_peserta'].'</td>
	  <td align="center">'.$met__3['tgl_createcn'].'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_RratioClaim.php?metratio='.$_REQUEST['metratio'].'&cat='.$_REQUEST['cat'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data: <u>' . $totalRows . '</u></b></td></tr>';
echo '</form></table>';
		}
	}
	;
} // switch

?>

<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_RratioClaim.php?eR=Rpremi&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadcn(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_RratioClaim.php?eR=Rclaim&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadtenor(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_RratioClaim.php?eR=Rtenor&cat=' + val;
}
</script>

<!--CHECKE ALL STATUS DATA DN/CN-->
<SCRIPT language="javascript">
$(function(){
    $("#selectalldncn").click(function () {	$('.casedncn').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".casedncn").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".casedncn").length == $(".casedncn:checked").length) {
            $("#selectalldncn").attr("checked", "checked");
        } else {
            $("#selectalldncn").removeAttr("checked");
        }

    });
});
</SCRIPT>