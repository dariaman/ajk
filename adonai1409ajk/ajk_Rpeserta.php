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

switch ($_REQUEST['report']) {
	case "uwcn":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat))	{	echo "Data Error";		exit;	}// to check if $cat is numeric data or not.

echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="90%" align="left">Modul Report CN</font></th></th></tr>
		</table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Credit Note</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="20%" align="right">Company</td>
	<td width="30%">: <select id="cat" name="cat" onchange="reloadcn(this.form)">
	  	<option value="">---Select Company---</option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
		<tr><td align="right">Regional</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
	echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['id'].'">'.$noticia['name'].'</option>';
}
echo '</td></tr>
		<tr><td align="right">Type Data</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT DISTINCT type_claim FROM fu_ajk_cn where id_cost="'.$cat.'" AND type_claim !="" ORDER BY type_claim ASC');
}else{$quer=$database->doQuery('SELECT DISTINCT type_claim FROM fu_ajk_cn where id_cost="'.$cat.'" AND type_claim !="" ORDER BY type_claim ASC'); }
		echo '<select name="typedata"><option value="">---Type Data---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['type_claim'].'">'.$noticia['type_claim'].'</option>';
}
echo '</td></tr>
		<tr><td align="right">Create CN </td><td>:
				<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
				<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	  <tr><td width="5%" align="center" colspan="3"><input type="submit" name="metreportcn" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';
if ($_REQUEST['metreportcn']=="Cari") {
	if ($_REQUEST['cat']=="") {		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';		}
elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){	echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';	}
else
	{
		$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
		$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
		if ($_REQUEST['cat'])			{	$satu = 'AND fu_ajk_cn.id_cost ="' . $cat . '"';			}
		if ($_REQUEST['subcat'])		{
			$cekregionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
			$dua = 'AND fu_ajk_cn.id_regional ="' . $cekregionalnya['name'] . '"';			}
		if ($_REQUEST['tanggal1'])		{	$tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}
		if ($_REQUEST['typedata'])		{	$empat = 'AND fu_ajk_cn.type_claim ="'.$_REQUEST['typedata'].'"';	}

$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
if ($_REQUEST['subcat']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

$met1 = $database->doQuery('SELECT *, SUM(total_claim) AS claimpaid FROM fu_ajk_cn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND confirm_claim="Approve(paid)" AND del IS NULL GROUP BY id');
$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){	$e += $tpaid['claimpaid'];	};

$met2 = $database->doQuery('SELECT *, SUM(total_claim) AS claimunpaid FROM fu_ajk_cn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND confirm_claim="Approve(unpaid)" AND del IS NULL GROUP BY id');
$tunpaiddn = mysql_num_rows($met2);
while ($tunpaid = mysql_fetch_array($met2)){	$er += $tunpaid['claimunpaid'];	};

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
			  <tr><td align="left" width="10%"><b>Company</b></td>
			  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
				  <td align="right" rowspan="2" colspan="5"><a href="ajk_report_fu.php?fu=Rcn&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&tipe='.$_REQUEST['typedata'].'&tanggal1='.$tglawal.'&tanggal2='.$tglakhir.'"><img src="image/excel.png" width="25" border="0"> &nbsp; <br />Excel</a> &nbsp; </td>
			  </tr>
		 	  <tr><td align="left" width="10%"><b>Regional</b></td>
		 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
			  <tr><td align="left" width="10%"><b>Type Data</b></td>
		 	  	  <td align="left" colspan="2"><b>: '.$_REQUEST['typedata'].'</b></td></tr>
		 	  <tr><td align="left"><b>Paid</b></td>
		 	  	  <td width="10%"><b>: '.$tpaiddn.' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
		 	  <tr><td align="left"><b>Unpaid</b></td>
		 	  	  <td><b>: '.$tunpaiddn.' DN</td><td><b>Rp.  '.duit($er).'</b></td></tr>
		 	  <tr><td align="left"><b>Grand Total</b></td>
		 	  	  <td><b>: '.duit($tpaiddn + $tunpaiddn).' DN</td><td><b>Rp. '.duit($er + $e).'</b></td></tr>
			  </table>';

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%">No</th>
		<th width="5%">Polis</th>
		<th width="5%">type</th>
		<th>Nama</th>
		<th width="13%">Credit Note</th>
		<th width="13%">Debit Note (p.Baru)</th>
		<th width="5%">Nilai CN</th>
		<th width="6%">date CN</th>
		<th width="6%">Paid Date</th>
		<th width="5%">Status</th>
		<th width="10%">Branch</th>
		<th width="10%">Regional</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}
else {	$m = 0;		}
$data = $database->doQuery('SELECT
fu_ajk_polis.nopol,
fu_ajk_cn.type_claim,
fu_ajk_peserta.nama,
fu_ajk_peserta.id_dn,
fu_ajk_cn.id_cn,
fu_ajk_cn.total_claim,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang
FROM
fu_ajk_cn
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND fu_ajk_cn.del IS NULL ORDER BY fu_ajk_cn.tgl_createcn ASC, fu_ajk_cn.id_cn ASC, fu_ajk_cn.input_date DESC, fu_ajk_cn.id_cabang ASC, fu_ajk_cn.id DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
if ($fudata['id_cabang']=="") 		{	$Rcabang = $fudata['id_cabang_old'];	} 		else {	$Rcabang = $fudata['id_cabang'];	}
if ($fudata['id_area']=="") 		{	$Rarea = $fudata['id_area_old'];	} 			else {	$Rarea = $fudata['id_area'];	}
if ($fudata['id_regional']=="") 	{	$Rregional = $fudata['id_regional_old'];	} 	else {	$Rregional = $fudata['id_regional'];	}

if ($fudata['confirm_claim']=="Approve(unpaid)") {	$statuscnnya = '<blink><font color="red">'.$fudata['confirm_claim'].'</font></blink>'; }
else	{	$statuscnnya = '<font color="blue">'.$fudata['confirm_claim'].'</font>'; }

if ($fudata['total_claim'] < 0) {	$nilaicnnya = 0;	}else{	$nilaicnnya=$fudata['total_claim'];	}	//BUAT NILAI 0 BILA NILAINYA MINUS
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td align="center">'.$fudata['nopol'].'</td>
		  <td align="center">'.$fudata['type_claim'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'.$fudata['id_cn'].'</td>
		  <td align="center">'.$fudata['id_dn'].'</td>
		  <td align="right">'.duit($nilaicnnya).'</td>
		  <td align="center">'._convertDate($fudata['tgl_createcn']).'</td>
		  <td align="center">'._convertDate($fudata['tgl_byr_claim']).'</td>
		  <td align="center">'.$statuscnnya.'</td>
		  <td>'.$Rcabang.'</td>
		  <td>'.$Rregional.'</td>
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_Rpeserta.php?report=uwcn&metreportcn=Cari&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&typedata='.$_REQUEST['typedata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data CN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
		;
		break;
	case "uwdn":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Report DN Create</font></th></th></tr>
</table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Debit Note</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="20%" align="right">Company</td>
	<td width="30%">: <select id="cat" name="cat" onchange="reloaddn(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
		<tr><td align="right">Regional</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
echo  '<option value="'.$noticia['id'].'">'.$noticia['name'].'</option>';
}
echo '</td></tr>
		<tr><td align="right">Create DN </td><td>:
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
			<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	  <tr><td align="right" width="3%">Status</td>
 		  <td width="10%">: <input type="radio"'.pilih($_REQUEST['armstatus'], "paid").' name="armstatus" value="paid">Paid &nbsp;
 		  					<input type="radio"'.pilih($_REQUEST['armstatus'], "unpaid").' name="armstatus" value="unpaid">Unpaid &nbsp;
 		  					<input type="radio"'.pilih($_REQUEST['armstatus'], "").' name="armstatus" value="">All</td>
 	  </tr>
	  <tr><td width="5%" align="center" colspan="3"><input type="submit" name="metreportdn" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';

if ($_REQUEST['metreportdn']=="Cari") {
	if ($_REQUEST['cat']=="") {
		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';
	}elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){
		echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';
	}else{
	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
	if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $cat . '"';			}
	if ($_REQUEST['subcat'])		{
		$cekregionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
		$dua = 'AND id_regional ="' . $cekregionalnya['name'] . '"';			}
	if ($_REQUEST['tanggal1'])		{	$tiga = 'AND tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}
	if ($_REQUEST['armstatus'])		{	$empat = 'AND dn_status = "'.$_REQUEST['armstatus'].'"';	}

$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
if ($_REQUEST['subcat']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

$met1 = $database->doQuery('SELECT *, SUM(totalpremi) AS premipaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND dn_status="paid" AND del IS NULL GROUP BY id');
$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){	$e += $tpaid['totalpremi'];	};

$met2 = $database->doQuery('SELECT *, SUM(totalpremi) AS premiunpaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND dn_status="unpaid" AND del IS NULL GROUP BY id');
$tunpaiddn = mysql_num_rows($met2);
while ($tunpaid = mysql_fetch_array($met2)){	$er += $tunpaid['totalpremi'];	};

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
	  <tr><td align="left" width="10%"><b>Company</b></td>
	  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
		  <td align="right" rowspan="2" colspan="5"><a href="ajk_report_fu.php?fu=Rdn&id_cost='.$_REQUEST['cat'].'&tanggal1='.$tglawal.'&tanggal2='.$tglakhir.'&Rpembayaran='.$_REQUEST['Rpembayaran'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
	  </tr>
 	  <tr><td align="left" width="10%"><b>Regional</b></td>
 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
	 	  <tr><td align="left"><b>Paid</b></td>
 	  	  <td width="10%"><b>: '.$tpaiddn.' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
 	  <tr><td align="left"><b>Unpaid</b></td>
 	  	  <td><b>: '.$tunpaiddn.' DN</td><td><b>Rp.  '.duit($er).'</b></td></tr>
 	  <tr><td align="left"><b>Grand Total</b></td>
 	  	  <td><b>: '.duit($tpaiddn + $tunpaiddn).' DN</td><td><b>Rp. '.duit($er + $e).'</b></td></tr>
	  </table>';

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr><td bgcolor="#FFF"colspan="25"></td></tr>
		<th width="1%">No</th>
		<th width="5%">Polis</th>
		<th>Debit Note</th>
		<th width="10%">Total Premi</th>
		<th width="8%">Date DN</th>
		<th width="15%">REG. PRM</th>
		<th width="8%">Paid Date</th>
		<th width="5%">Status</th>
		<th width="10%">Branch</th>
		<th width="10%">Area</th>
		<th width="10%">Regional</th>
		</tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL ORDER BY dn_kode DESC, id_cabang DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" ' . $satu . ' '.$dua.' '.$tiga.''.$empat.' AND del IS NULL '));
$totalRows = $totalRows[0];

while ($fudata = mysql_fetch_array($met)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
if ($fudata['id_cabang']=="") 		{	$Rcabang = $fudata['id_cabang_old'];	} 		else {	$Rcabang = $fudata['id_cabang'];	}
if ($fudata['id_area']=="") 		{	$Rarea = $fudata['id_area_old'];	} 			else {	$Rarea = $fudata['id_area'];	}
if ($fudata['id_regional']=="") 	{	$Rregional = $fudata['id_regional_old'];	} 	else {	$Rregional = $fudata['id_regional'];	}
if ($fudata['dn_status']=="unpaid") {	$statusnya = '<blink><font color="red">Unpaid</font><blink>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}

$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_nopol'].'"'));
$metARM = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$fudata['id_prm'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$fudata['id_cost'].'"'));
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
			  <td align="center">'.$metpolis['nopol'].'</td>
			  <td align="center">'.$fudata['dn_kode'].'</td>
			  <td align="right">'.duit($fudata['totalpremi']).'</td>
			  <td align="center">'._convertDate($fudata['tgl_createdn']).'</td>
			  <td align="center">'.$metARM['id_prm'].'</td>
			  <td align="center">'._convertDate($fudata['tgl_dn_paid']).'</td>
			  <td align="center">'.$statusnya.'</td>
			  <td align="center">'.$Rcabang.'</td>
			  <td align="center">'.$Rarea.'</td>
			  <td align="center">'.$Rregional.'</td>
		</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_Rpeserta.php?report=uwdn&metreportdn=Cari&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&armstatus='.$_REQUEST['armstatus'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
	;
	break;
	default:
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
echo "Data Error";	exit;
}
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Data Peserta</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td rowspan="9"> </td><td width="30%">Nama Perusahaan <font color="red">*</font></td>
	  		<td width="60%"> : <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Perusahaan---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td>Nama Produk <font color="red">*</font></td>
		<td> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
echo '<select name="subcat"><option value="">---Produk---</option>';
while($noticia = mysql_fetch_array($quer)) {
if($noticia['id']==$_REQUEST['subcat']){echo '<option selected value="'.$noticia['id'].'">'.$noticia['nmproduk'].'</option><BR>';}
else{echo  '<option value="'.$noticia['id'].'">'.$noticia['nmproduk'].'</option>';}
}
echo '</select></td></tr>
<tr><td>Tanggal DN</td><td> :
<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
</td></tr>
<tr><td>Tanggal Mulai Asuransi</td><td> :
<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>
</td></tr>

	<tr><td width="10%">Regional</td><td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');	}else{ }
echo '<select name="subcatreg"><option value="">---Pilih Regional---</option>';
while($regquer = mysql_fetch_array($quer)) {
if($regquer['name']==$_REQUEST['subcatreg']){echo '<option selected value="'.$regquer['name'].'">'.$regquer['name'].'</option><BR>';}
else{	echo  '<option value="'.$regquer['name'].'">'.$regquer['name'].'</option>';	}
}
echo '</select></td></tr>
	<tr><td width="10%">Area</td><td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_area where id_cost="'.$cat.'" ORDER BY name ASC');	}else{ }
echo '<select name="subcatarea"><option value="">---Pilih Area---</option>';
while($areaquer = mysql_fetch_array($quer)) {
if($areaquer['name']==$_REQUEST['subcatarea']){echo '<option selected value="'.$areaquer['name'].'">'.$areaquer['name'].'</option><BR>';}
else{	echo '<option value="'.$areaquer['name'].'">'.$areaquer['name'].'</option>';	}
}
echo '</select></td></tr>
	<tr><td width="10%">Cabang</td><td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_cabang where id_cost="'.$cat.'" ORDER BY name ASC');	}else{ }
echo '<select name="subcatcab"><option value="">---Pilih Cabang---</option>';
while($cabquer = mysql_fetch_array($quer)) {
if($cabquer['name']==$_REQUEST['subcatcab']){echo '<option selected value="'.$cabquer['name'].'">'.$cabquer['name'].'</option><BR>';}
else{	echo  '<option value='.$cabquer['name'].'>'.$cabquer['name'].'</option>';	}
}
echo '</select></td></tr>
<tr><td>Status Pembayaran </td><td> :
	  <select size="1" name="paidata">
  	  <option value="">--- Status ---</option>
  	  <option value="paid"'._selected($_REQUEST['paidata'], "paid").'>Paid</option>
  	  <option value="unpaid"'._selected($_REQUEST['paidata'], "unpaid").'>Unpaid</option>
	  </select>
</td></tr>
<!--<tr><td>Tanggal Mulai Asuransi</td><td> :
<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>
</td></tr>
	<tr><td>Status Pembayaran <input type="radio"'.pilih($_REQUEST['Rpembayaran'], "3").' name="Rpembayaran" value="3">All</td></td><td> :
		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "1").' name="Rpembayaran" value="1">Paid &nbsp;
 		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "2").' name="Rpembayaran" value="2">Unpaid &nbsp;
		</td></tr>
	<tr><td>Status Peserta <input type="checkbox" id="selectall" />ALL</td><td> :
	 <input type="checkbox" class="case" name="statspeserta[]" value="aktif" id="cbx">Inforce &nbsp;
	 <input type="checkbox" class="case" name="statspeserta[]" value="Lapse" id="cbx">Lapse
	 <input type="checkbox" class="case" name="statspeserta[]" value="Maturity" id="cbx">Maturity
	 <input type="checkbox" class="case" name="statspeserta[]" value="Pending" id="cbx">Pending
	 <input type="checkbox" class="case" name="statspeserta[]" value="Cancel" id="cbx">Cancel
	  </td></tr>
		<tr><td>Tipe DN/CN <input type="checkbox" id="selectalldncn" checked />ALL</td><td> :
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Restruktur" id="cbx2">Restruktur &nbsp;
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Top Up" id="cbx2">Top Up
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Baloon" id="cbx2">Baloon
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Refund" id="cbx2">Refund
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Death" id="cbx2">Meninggal
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Batal" id="cbx2">Batal
	</td></tr>-->
	<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td>
	</tr>
	</form></table></fieldset>';
if ($_REQUEST['metreport']=="Cari") {
	if ($_REQUEST['cat']=="") {	echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';	}
	elseif ($_REQUEST['subcat']=="") {	echo '<div align="center"><font color="red"><blink>Silahkan pilih Polis...!!</div></font></blink>';	}
else{

	if ($_REQUEST['cat'])						{	$satu = 'AND fu_ajk_dn.id_cost = "' . $_REQUEST['cat'] . '"';
	$carisatu = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
	$metcarisatu .='<tr><td width="15%">Nama Perusahaan</td><td width="1%">:</td><td>'.$carisatu['name'].'</td></tr>';	}

	if ($_REQUEST['subcat'])					{	$dua = 'AND fu_ajk_dn.id_nopol = "' . $_REQUEST['subcat'] . '"';
		$caridua = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
		$metcaridua .='<tr><td width="15%">Nama Produk</td><td width="1%">:</td><td>'.strtoupper($caridua['nmproduk']).' ('.$caridua['nopol'].')</td></tr>';	}

	if ($_REQUEST['subcatreg'])					{	$tiga = 'AND fu_ajk_dn.id_regional = "' . $_REQUEST['subcatreg'] . '"';
		$metcaritiga .='<tr><td width="15%">Regional</td><td width="1%">:</td><td>'.$_REQUEST['subcatreg'].'</td></tr>';	}

	if ($_REQUEST['subcatarea'])				{	$empat = 'AND fu_ajk_dn.id_area = "' . $_REQUEST['subcatarea'] . '"';
		$metcariempat .='<tr><td width="15%">Area</td><td width="1%">:</td><td>'.$_REQUEST['subcatarea'].'</td></tr>';	}

	if ($_REQUEST['subcatcab'])					{	$lima = 'AND fu_ajk_dn.id_cabang = "' . $_REQUEST['subcatcab'] . '"';
		$metcarilima .='<tr><td width="15%">Cabang</td><td width="1%">:</td><td>'.$_REQUEST['subcatcab'].'</td></tr>';	}

	if ($_REQUEST['tanggal1'])					{	$enam = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tanggal1'].'" AND "'.$_REQUEST['tanggal2'].'" ';
		$metcarienam .='<tr><td width="15%">Tanggal Mulai Asuransi</td><td width="1%">:</td><td>'._convertDate($_REQUEST['tanggal1']).' - '._convertDate($_REQUEST['tanggal2']).'</td></tr>';	}

	if ($_REQUEST['Rpembayaran'])		{
		if($_REQUEST['Rpembayaran']=="1"){
			$tujuh = 'AND fu_ajk_dn.dn_status = "paid"';
			$metcaritujuh .='<tr><td width="15%">Status Pembayaran </td><td width="1%">:</td><td>Paid</td></tr>';
		}	elseif($_REQUEST['Rpembayaran']=="2")		{
			$tujuh = 'AND fu_ajk_dn.dn_status = "unpaid"';
			$metcaritujuh .='<tr><td width="15%">Status Pembayaran </td><td width="1%">:</td><td>Unpaid</td></tr>';
		}	else {	$tujuh = 'AND fu_ajk_dn.dn_status != ""';
			$metcaritujuh .='<tr><td width="15%">Status Pembayaran </td><td width="1%">:</td><td>Paid dan Unpaid</td></tr>';
		}
	}
	//if ($_REQUEST['statspeserta'])				{	$empat = 'AND status_aktif = "' . $_REQUEST['statspeserta'] . '"';			}
if ($_REQUEST['statspeserta']){
if ($_POST['metreport']) {
	$cbx = $_POST['statspeserta'];
		for($i=0; $i < count($cbx); $i++) {
			if ($i<1) {	$delapanan .= '"'.$cbx[$i].'"';				$statusny .=$cbx[$i].'';
						$delapan = 'AND fu_ajk_peserta.status_aktif = '.$delapanan.'';
					  }
			else	  {	$delapanan .= ' OR "'.$cbx[$i].'"';		$statusny .=', '.$cbx[$i].'';
					  	$delapan = 'AND fu_ajk_peserta.status_aktif = ('.$delapanan.')';
					  }
	}
	$metcaridelapan .='<tr><td width="15%">Status Peserta </td><td width="1%">:</td><td>'.$statusny.'</td></tr>';
	}
}

if ($_REQUEST['typepeserta']){
if ($_POST['metreport']) {
	$cbx2 = $_POST['typepeserta'];
	for($i=0; $i < count($cbx2); $i++) {
		if ($i<1)  {	$sembilanan .= '"'.$cbx2[$i].'"';				$statusnya2 .=$cbx2[$i].'';
						$sembilan = 'AND fu_ajk_peserta.status_peserta = '.$sembilanan.'';
				   }
				  //{	$sembilan .= 'AND fu_ajk_peserta.status_peserta = "'.$cbx2[$i].'"';			$statusnya2 .=$cbx2[$i];		}
		else	   {	$sembilanan .= ' OR "'.$cbx2[$i].'"';			$statusnya2 .=', '.$cbx2[$i].'';
						$sembilan = 'AND fu_ajk_peserta.status_peserta = ('.$sembilanan.')';
				   }
				   //{	$sembilan .= ' OR fu_ajk_peserta.status_peserta = "'.$cbx2[$i].'"';			$statusnya2 .=', '.$cbx2[$i].'';		}
	}
	$metcarisembilan .='<tr><td width="15%">Type </td><td width="1%">:</td><td>'.$statusnya2.'</td></tr>';
	}
}

if ($_REQUEST['tanggal3'])		{	$sepuluh = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tanggal3'].'" AND "'.$_REQUEST['tanggal4'].'" ';
$metcarisepuluh .='<tr><td width="15%">Tanggal DN dibuat </td><td width="1%">:</td><td>'.$_REQUEST['tanggal3'].' - '.$_REQUEST['tanggal4'].'</td></tr>';	}


echo '<table border="0" width="100%" cellpadding="1" cellspacing="">
	  <tr><td colspan="3">Laporan Data Kepesertaan</td></tr>
	  '.$metcarisatu.'
	  '.$metcaridua.'
	  '.$metcaritiga.'
	  '.$metcariempat.'
	  '.$metcarilima.'
	  '.$metcarienam.'
	  '.$metcaritujuh.'
	  '.$metcaridelapan.'
	  '.$metcarisembilan.'
	  '.$metcarisepuluh.'
	  </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><td bgcolor="#FFF"colspan="27"><a href="ajk_report_fu.php?fu=Rpeserta&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&subcatreg='.$_REQUEST['subcatreg'].'&subcatarea='.$_REQUEST['subcatarea'].'&subcatcab='.$_REQUEST['subcatcab'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&tanggal3='.$_REQUEST['tanggal3'].'&tanggal4='.$_REQUEST['tanggal4'].'&Rpembayaran='.$_REQUEST['Rpembayaran'].'&statspeserta='.$statusny.'&typepeserta='.$statusnya2.'&tanggal3='.$_REQUEST['tanggal3'].'&tanggal4='.$_REQUEST['tanggal4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
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

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}
else {	$m = 0;		}
$data2 = $database->doQuery('SELECT
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_area,
fu_ajk_dn.id_cabang,
fu_ajk_dn.dn_kode,
fu_ajk_dn.totalpremi,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.del,
fu_ajk_peserta.spaj,
fu_ajk_peserta.nama,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.status_aktif
FROM
fu_ajk_dn
INNER JOIN fu_ajk_peserta ON fu_ajk_dn.dn_kode = fu_ajk_peserta.id_dn AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL LIMIT '.$m.', 50');


   $datahit = $database->doQuery('SELECT
   fu_ajk_dn.id_cost,
   fu_ajk_dn.id_nopol,
   fu_ajk_dn.id_regional,
   fu_ajk_dn.id_area,
   fu_ajk_dn.id_cabang,
   fu_ajk_dn.dn_kode,
   fu_ajk_dn.totalpremi,
   fu_ajk_dn.dn_status,
   fu_ajk_dn.tgl_createdn,
   fu_ajk_dn.del,
   fu_ajk_peserta.spaj,
   fu_ajk_peserta.nama,
   fu_ajk_peserta.id_peserta,
   fu_ajk_peserta.gender,
   fu_ajk_peserta.tgl_lahir,
   fu_ajk_peserta.usia,
   fu_ajk_peserta.kredit_tgl,
   fu_ajk_peserta.kredit_tenor,
   fu_ajk_peserta.kredit_akhir,
   fu_ajk_peserta.kredit_jumlah,
   fu_ajk_peserta.totalpremi,
   fu_ajk_peserta.status_aktif
   FROM
   fu_ajk_dn
   INNER JOIN fu_ajk_peserta ON fu_ajk_dn.dn_kode = fu_ajk_peserta.id_dn AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost
   WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL');
$jumdatanya = mysql_num_rows($datahit);
$totalRows = $jumdatanya;


$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data2)) {

if ($fudata['id_cabang']=="") 		{	$Rcabang = $fudata['cabang_lama'];	} 		else {	$Rcabang = $fudata['id_cabang'];	}
if ($fudata['id_area']=="") 		{	$Rarea = $fudata['area_lama'];	} 			else {	$Rarea = $fudata['id_area'];	}
if ($fudata['id_regional']=="") 	{	$Rregional = $fudata['regional_lama'];	} 	else {	$Rregional = $fudata['id_regional'];	}
if ($fudata['status_bayar']==0) {	$statusnya = '<font color="red">Unpaid</font>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}

$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_nopol'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$fudata['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td align="center">'.$metpolis['nopol'].'</td>
		  <td align="center">'.$fudata['dn_kode'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_createdn']).'</td>
		  <td align="center">'.$fudata['id_peserta'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['disc_premi']).'</td>
		  <td align="right">'.duit($fudata['biaya_adm']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$statusnya.'</td>
		  <td align="center">'.$fudata['status_aktif'].'</td>
		  <td align="center">'.$Rcabang.'</td>
		  <td align="center">'.$Rregional.'</td>
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_Rpeserta.php?cat='.$_REQUEST['cat'].'&
									subcat='.$_REQUEST['subcat'].'&
									subcatreg='.$_REQUEST['subcatreg'].'&
									subcatarea='.$_REQUEST['subcatarea'].'&
									subcatcab='.$_REQUEST['subcatcab'].'&
									tanggal1='.$_REQUEST['tanggal1'].'&
									tanggal2='.$_REQUEST['tanggal2'].'&
									Rpembayaran='.$_REQUEST['Rpembayaran'].'&
									statspeserta='.$_REQUEST['statspeserta'].'&
									typepeserta='.$_REQUEST['typepeserta'].'&
									tanggal3='.$_REQUEST['tanggal3'].'&
									tanggal4='.$_REQUEST['tanggal4'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
	;
} // switch
?>

<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_Rpeserta.php?cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloaddn(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_Rpeserta.php?report=uwdn&cat=' + val;
}
</script>
<SCRIPT language=JavaScript>
function reloadcn(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_Rpeserta.php?report=uwcn&cat=' + val;
}
</script>

<!--CHECKE ALL STATUS PESERTA-->
<SCRIPT language="javascript">
$(function(){
    $("#selectall").click(function () {	$('.case').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});
</SCRIPT>

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